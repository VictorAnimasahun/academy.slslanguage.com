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
            (student_id, test_id, attempt_number, mode, started_at, completed_at, score, max_score, band_score, time_spent, status)
        VALUES (?, ?, ?, 'practice', ?, NOW(), ?, ?, ?, ?, 'completed')
    ");
    $stmt->execute([$student_id, $test_id, $attempt_number, $started_at, $score, $max_score, $band_score, $time_spent]);
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

    // "Choose TWO letters" pairs are scored as a set (either order, one mark per
    // distinct correct letter). Pairs are declared PER TEST CODE: every other
    // test's numbered questions are ordinary single-answer questions and must
    // never be caught by this exception (a past bug — see project memory).
    $pair_defs_by_test = [
        'IELTS_PT_L_001' => [[[29, 30], ['b', 'd']]],
        'IELTS_PT_L_002' => [[[21, 22], ['b', 'd']], [[23, 24], ['b', 'c']]],
    ];
    $pair_scores = [];   // question_number => 0.0|1.0
    foreach ($pair_defs_by_test[$test_code] ?? [] as [$pair_qs, $pair_correct]) {
        $chosen = [];
        foreach ($pair_qs as $pq) {
            $sel = strtolower(trim($answers[$pq] ?? ''));
            if ($sel !== '') $chosen[$sel] = true;
        }
        // Give each correct letter the chosen set contains to one of the two slots
        $awards = 0;
        foreach ($pair_correct as $c) if (isset($chosen[$c])) $awards++;
        foreach ($pair_qs as $i => $pq) $pair_scores[$pq] = ($i < $awards) ? 1.0 : 0.0;
    }

    // Insert per-question attempt_answers
    $ins = $db->prepare("
        INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, answer_text, score_awarded)
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($questions as $q_num => $q_info) {
        $q_id        = $q_info['id'];
        $user_answer = trim($answers[$q_num] ?? '');
        $opt_id      = null;
        $score_awd   = 0.0;

        if (isset($pair_scores[$q_num])) {
            // "Choose TWO" pair member
            $score_awd = $pair_scores[$q_num];
        } elseif (isset($options_map[$q_id])) {
            // Any question with defined answer options (multiple_choice_single/multiple,
            // and option-based "matching" questions like CELPIP's paragraph-matching
            // dropdowns, whose correct answer lives on question_options.is_correct
            // rather than question_correct_answers) — score by correct option label.
            // Branching on data presence rather than $q_type keeps this correct even
            // when 'matching' is used both ways (typed-letter vs. dropdown-of-options).
            if ($user_answer !== '' && isset($correct_opts[$q_id])) {
                if (in_array(strtolower($user_answer), $correct_opts[$q_id])) {
                    $score_awd = 1.0;
                }
            }
            if ($user_answer !== '' && isset($options_map[$q_id][strtoupper($user_answer)])) {
                $opt_id = $options_map[$q_id][strtoupper($user_answer)];
            }
        } else {
            // Text answer: form_note_completion, table_completion, sentence_completion,
            // typed-letter matching (e.g. "Write the correct letter, A-E"), etc.
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
