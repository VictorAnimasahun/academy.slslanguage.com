<?php
// Saves a completed practice test attempt to test_attempts + attempt_answers.
// Called via fetch() POST from the test page JS on submit.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit();
}

$test_code  = preg_replace('/[^A-Z0-9_]/', '', strtoupper($input['test_code'] ?? ''));
$student_id = (int)$_SESSION['user_id'];
$score      = (float)($input['score']      ?? 0);
$max_score  = (float)($input['max_score']  ?? 40);
$band_score = (float)($input['band_score'] ?? 0);
$time_spent = max(0, (int)($input['time_spent'] ?? 0)); // seconds
$answers    = is_array($input['answers']) ? $input['answers'] : [];

if (!$test_code) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing test_code']);
    exit();
}

try {
    $db->beginTransaction();

    // Resolve test
    $stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
    $stmt->execute([$test_code]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$test) {
        $db->rollBack();
        http_response_code(404);
        echo json_encode(['error' => 'Test not found: ' . $test_code]);
        exit();
    }
    $test_id = (int)$test['id'];

    // Attempt number
    $stmt = $db->prepare("SELECT COUNT(*) FROM test_attempts WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$student_id, $test_id]);
    $attempt_number = (int)$stmt->fetchColumn() + 1;

    $started_at = date('Y-m-d H:i:s', time() - $time_spent);

    // Insert attempt
    $stmt = $db->prepare("
        INSERT INTO test_attempts
            (student_id, test_id, attempt_number, mode, started_at, completed_at, score, max_score, band_score, status)
        VALUES (?, ?, ?, 'practice', ?, NOW(), ?, ?, ?, 'completed')
    ");
    $stmt->execute([$student_id, $test_id, $attempt_number, $started_at, $score, $max_score, $band_score]);
    $attempt_id = (int)$db->lastInsertId();

    // Load all questions for this test
    $stmt = $db->prepare("
        SELECT id, question_number, question_type
        FROM questions WHERE test_id = ? ORDER BY question_number
    ");
    $stmt->execute([$test_id]);
    $questions = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $q) {
        $questions[(int)$q['question_number']] = ['id' => (int)$q['id'], 'type' => $q['question_type']];
    }

    // Load MCQ options keyed by [question_id][label_upper] => option_id
    $stmt = $db->prepare("
        SELECT qo.id, qo.question_id, UPPER(qo.option_label) AS label
        FROM question_options qo
        JOIN questions q ON q.id = qo.question_id
        WHERE q.test_id = ?
    ");
    $stmt->execute([$test_id]);
    $options_map = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $o) {
        $options_map[(int)$o['question_id']][$o['label']] = (int)$o['id'];
    }

    // Load correct answers for text-type questions [question_id] => [lc_answer, ...]
    $stmt = $db->prepare("
        SELECT qca.question_id, qca.answer_text
        FROM question_correct_answers qca
        JOIN questions q ON q.id = qca.question_id
        WHERE q.test_id = ?
    ");
    $stmt->execute([$test_id]);
    $correct_text = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $ca) {
        $correct_text[(int)$ca['question_id']][] = strtolower($ca['answer_text']);
    }

    // Load correct options for MCQ [question_id] => [lc_label, ...]
    $stmt = $db->prepare("
        SELECT qo.question_id, LOWER(qo.option_label) AS label
        FROM question_options qo
        JOIN questions q ON q.id = qo.question_id
        WHERE q.test_id = ? AND qo.is_correct = 1
    ");
    $stmt->execute([$test_id]);
    $correct_opts = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $co) {
        $correct_opts[(int)$co['question_id']][] = $co['label'];
    }

    // Pre-compute Q29-30 pair scores
    // Correct pair = {b, d}. Award 1 mark per correct unique letter selected across both slots.
    $pair_q_nums   = [29, 30];
    $pair_correct  = ['b', 'd'];
    $pair_selected = [];
    foreach ($pair_q_nums as $pq) {
        $pair_selected[$pq] = strtolower(trim($answers[$pq] ?? ''));
    }
    $pair_unique = array_unique(array_filter(array_values($pair_selected)));
    $pair_scores = [29 => 0.0, 30 => 0.0];

    // Only award marks if the pair has no duplicates and each slot selected a correct letter
    if (count($pair_unique) === count(array_filter(array_values($pair_selected)))) {
        foreach ($pair_q_nums as $pq) {
            if (in_array($pair_selected[$pq], $pair_correct) && $pair_selected[29] !== $pair_selected[30]) {
                $pair_scores[$pq] = 1.0;
            }
        }
    }

    // Insert per-question attempt_answers
    $ins = $db->prepare("
        INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, answer_text, score_awarded)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($questions as $q_num => $q_info) {
        $q_id        = $q_info['id'];
        $q_type      = $q_info['type'];
        $user_answer = trim($answers[$q_num] ?? '');
        $opt_id      = null;
        $score_awd   = 0.0;

        if (in_array($q_num, $pair_q_nums)) {
            // Q29-30 pair
            $score_awd = $pair_scores[$q_num];
        } elseif (in_array($q_type, ['multiple_choice_single', 'multiple_choice_multiple'])) {
            // MCQ: score by correct option label
            if ($user_answer !== '' && isset($correct_opts[$q_id])) {
                if (in_array(strtolower($user_answer), $correct_opts[$q_id])) {
                    $score_awd = 1.0;
                }
            }
            if ($user_answer !== '' && isset($options_map[$q_id][strtoupper($user_answer)])) {
                $opt_id = $options_map[$q_id][strtoupper($user_answer)];
            }
        } else {
            // Text answer: form_note_completion, table_completion, matching, sentence_completion, etc.
            if ($user_answer !== '' && isset($correct_text[$q_id])) {
                if (in_array(strtolower($user_answer), $correct_text[$q_id])) {
                    $score_awd = 1.0;
                }
            }
        }

        $ins->execute([$attempt_id, $q_id, $opt_id, $user_answer !== '' ? $user_answer : null, $score_awd]);
    }

    $db->commit();

    echo json_encode([
        'success'    => true,
        'attempt_id' => $attempt_id,
        'score'      => $score,
        'max_score'  => $max_score,
        'band_score' => $band_score,
    ]);

} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    error_log('save_attempt.php PDO error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error. Please try again.']);
}
