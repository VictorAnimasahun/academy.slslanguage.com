<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once CONFIG_PATH . '/api_keys.php';

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

$session_id = (int)($input['session_id'] ?? 0);
$section    = $input['section'] ?? '';
$time_spent = max(0, (int)($input['time_spent'] ?? 0));
$student_id = (int)$_SESSION['user_id'];

if (!$session_id || !in_array($section, ['listening', 'reading', 'writing'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit();
}

$stmt = $db->prepare("
    SELECT ms.*, t.code AS mock_code
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) {
    http_response_code(404);
    echo json_encode(['error' => 'Session not found']);
    exit();
}

if ($session['status'] !== 'in_progress') {
    echo json_encode(['success' => true, 'redirect' => 'mock_start.php']);
    exit();
}

$attempt_col = $section . '_attempt_id';
if (!is_null($session[$attempt_col])) {
    echo json_encode(['success' => true, 'redirect' => nextSection($section, $session_id, $session['mock_code'])]);
    exit();
}

$map      = require INCLUDES_PATH . '/mock_test_map.php';
$mockCode = $session['mock_code'];

if (!isset($map[$mockCode][$section])) {
    http_response_code(500);
    echo json_encode(['error' => 'Section test not configured for this mock']);
    exit();
}

$sectionInfo = $map[$mockCode][$section];
$testCode    = is_array($sectionInfo) ? ($sectionInfo['test_code'] ?? '') : $sectionInfo;
$stmt = $db->prepare("SELECT * FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    http_response_code(404);
    echo json_encode(['error' => 'Test record not found: ' . $testCode]);
    exit();
}
$test_id = (int)$test['id'];

try {
    $db->beginTransaction();

    $stmt = $db->prepare("SELECT COUNT(*) FROM test_attempts WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$student_id, $test_id]);
    $attempt_number = (int)$stmt->fetchColumn() + 1;
    $started_at = date('Y-m-d H:i:s', time() - $time_spent);

    if ($section === 'writing') {
        $t1q = trim($input['task1_question'] ?? '');
        $t1e = trim($input['task1_essay'] ?? '');
        $t2q = trim($input['task2_question'] ?? '');
        $t2e = trim($input['task2_essay'] ?? '');

        $r1 = $t1e ? gradeMockEssay($t1q, $t1e, 'writing_task1') : ['band' => 0.0, 'overall_feedback' => ''];
        $r2 = $t2e ? gradeMockEssay($t2q, $t2e, 'writing_task2') : ['band' => 0.0, 'overall_feedback' => ''];

        $band1 = (float)($r1['band'] ?? 0);
        $band2 = (float)($r2['band'] ?? 0);
        $writing_band = round((($band1 + $band2) / 2) * 2) / 2;

        $writing_feedback = "Task 1 — Band {$band1}\n" . ($r1['overall_feedback'] ?? '')
            . "\n\nTask 2 — Band {$band2}\n" . ($r2['overall_feedback'] ?? '');

        $stmt = $db->prepare("
            INSERT INTO test_attempts
                (student_id, test_id, attempt_number, mode, started_at, completed_at, score, max_score, band_score, time_spent, status)
            VALUES (?, ?, ?, 'mock', ?, NOW(), 0, 2, ?, ?, 'completed')
        ");
        $stmt->execute([$student_id, $test_id, $attempt_number, $started_at, $writing_band, $time_spent]);
        $attempt_id = (int)$db->lastInsertId();

        // Look up actual question IDs for Task 1 and Task 2
        $wq_stmt = $db->prepare("SELECT question_number, id FROM questions WHERE test_id = ? AND question_number IN (1,2) ORDER BY question_number");
        $wq_stmt->execute([$test_id]);
        $writing_q_ids = [];
        foreach ($wq_stmt->fetchAll(PDO::FETCH_ASSOC) as $wq) {
            $writing_q_ids[(int)$wq['question_number']] = (int)$wq['id'];
        }

        $ins = $db->prepare("INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, answer_text, score_awarded) VALUES (?, ?, NULL, ?, ?)");
        if ($t1e && isset($writing_q_ids[1])) $ins->execute([$attempt_id, $writing_q_ids[1], $t1e, $band1]);
        if ($t2e && isset($writing_q_ids[2])) $ins->execute([$attempt_id, $writing_q_ids[2], $t2e, $band2]);

        $db->prepare("UPDATE mock_sessions SET writing_attempt_id=?, writing_band=?, writing_ai_feedback=? WHERE id=?")
           ->execute([$attempt_id, $writing_band, $writing_feedback, $session_id]);

    } else {
        $answers = is_array($input['answers']) ? $input['answers'] : [];

        $stmt = $db->prepare("SELECT id, question_number, question_type FROM questions WHERE test_id = ? ORDER BY question_number");
        $stmt->execute([$test_id]);
        $questions = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $q) {
            $questions[(int)$q['question_number']] = ['id' => (int)$q['id'], 'type' => $q['question_type']];
        }

        $stmt = $db->prepare("SELECT qo.id, qo.question_id, UPPER(qo.option_label) AS label FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = ?");
        $stmt->execute([$test_id]);
        $options_map = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $o) {
            $options_map[(int)$o['question_id']][$o['label']] = (int)$o['id'];
        }

        $stmt = $db->prepare("SELECT qca.question_id, qca.answer_text FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = ?");
        $stmt->execute([$test_id]);
        $correct_text = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $ca) {
            $correct_text[(int)$ca['question_id']][] = strtolower($ca['answer_text']);
        }

        $stmt = $db->prepare("SELECT qo.question_id, LOWER(qo.option_label) AS label FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = ? AND qo.is_correct = 1");
        $stmt->execute([$test_id]);
        $correct_opts = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $co) {
            $correct_opts[(int)$co['question_id']][] = $co['label'];
        }

        // Choose-TWO pair scoring (listening only).
        // Each entry: [qA, qB, [correct_letter_a, correct_letter_b]] — either order accepted.
        $pair_defs = ($section === 'listening') ? [
            [21, 22, ['c', 'e']],
            [23, 24, ['b', 'e']],
        ] : [];

        $pair_q_to_score = [];
        foreach ($pair_defs as [$qa, $qb, $pair_correct]) {
            $answer_a = strtolower(trim($answers[$qa] ?? ''));
            $answer_b = strtolower(trim($answers[$qb] ?? ''));
            $distinct = ($answer_a !== $answer_b);
            $pair_q_to_score[$qa] = ($answer_a !== '' && in_array($answer_a, $pair_correct) && $distinct) ? 1.0 : 0.0;
            $pair_q_to_score[$qb] = ($answer_b !== '' && in_array($answer_b, $pair_correct) && $distinct) ? 1.0 : 0.0;
        }

        $score      = 0.0;
        $max_score  = (float)($test['total_questions'] ?? 40);
        $answer_rows = [];

        foreach ($questions as $q_num => $q_info) {
            $q_id        = $q_info['id'];
            $q_type      = $q_info['type'];
            $user_answer = trim($answers[$q_num] ?? '');
            $opt_id      = null;
            $score_awd   = 0.0;

            if (array_key_exists($q_num, $pair_q_to_score)) {
                $score_awd = $pair_q_to_score[$q_num];
            } elseif (in_array($q_type, ['multiple_choice_single', 'multiple_choice_multiple'])) {
                if ($user_answer !== '' && isset($correct_opts[$q_id]) && in_array(strtolower($user_answer), $correct_opts[$q_id])) {
                    $score_awd = 1.0;
                }
                if ($user_answer !== '' && isset($options_map[$q_id][strtoupper($user_answer)])) {
                    $opt_id = $options_map[$q_id][strtoupper($user_answer)];
                }
            } else {
                if ($user_answer !== '' && isset($correct_text[$q_id]) && in_array(strtolower($user_answer), $correct_text[$q_id])) {
                    $score_awd = 1.0;
                }
            }

            $score += $score_awd;
            $answer_rows[] = [$q_id, $opt_id, $user_answer !== '' ? $user_answer : null, $score_awd];
        }

        $band_score = $section === 'listening' ? listeningBand($score) : readingBand($score);

        $stmt = $db->prepare("
            INSERT INTO test_attempts
                (student_id, test_id, attempt_number, mode, started_at, completed_at, score, max_score, band_score, time_spent, status)
            VALUES (?, ?, ?, 'mock', ?, NOW(), ?, ?, ?, ?, 'completed')
        ");
        $stmt->execute([$student_id, $test_id, $attempt_number, $started_at, $score, $max_score, $band_score, $time_spent]);
        $attempt_id = (int)$db->lastInsertId();

        $ins = $db->prepare("INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, answer_text, score_awarded) VALUES (?, ?, ?, ?, ?)");
        foreach ($answer_rows as $row) {
            $ins->execute(array_merge([$attempt_id], $row));
        }

        $col = $section . '_attempt_id';
        $db->prepare("UPDATE mock_sessions SET {$col} = ? WHERE id = ?")
           ->execute([$attempt_id, $session_id]);
    }

    $db->commit();
    echo json_encode(['success' => true, 'redirect' => nextSection($section, $session_id, $session['mock_code'])]);

} catch (PDOException $e) {
    if ($db->inTransaction()) $db->rollBack();
    error_log('mock_save_section PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error. Please try again.']);
}

function nextSection(string $section, int $session_id, string $mockCode): string {
    if ($section === 'writing') {
        return 'mock_speaking.php?session_id=' . $session_id;
    }
    $next = ['listening' => 'reading', 'reading' => 'writing'];
    $nextSection = $next[$section] ?? null;
    if (!$nextSection) return 'mock_start.php';

    $map = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$mockCode][$nextSection]['file'] ?? 'mock_start.php';
    return $file . '?session_id=' . $session_id;
}

function listeningBand(float $score): float {
    foreach ([39=>9,37=>8.5,35=>8,33=>7.5,30=>7,27=>6.5,23=>6,20=>5.5,16=>5,13=>4.5,10=>4,8=>3.5,6=>3,4=>2.5] as $min=>$band) {
        if ($score >= $min) return $band;
    }
    return 1.0;
}

function readingBand(float $score): float {
    foreach ([39=>9,37=>8.5,35=>8,33=>7.5,30=>7,27=>6.5,23=>6,19=>5.5,15=>5,13=>4.5,10=>4,8=>3.5,6=>3,4=>2.5] as $min=>$band) {
        if ($score >= $min) return $band;
    }
    return 1.0;
}

function gradeMockEssay(string $question, string $essay, string $taskType): array {
    $rubric = $taskType === 'writing_task1'
        ? "You are an official IELTS Writing Task 1 examiner. Score this response on: Task Achievement, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy."
        : "You are an official IELTS Writing Task 2 examiner. Score this essay on: Task Response, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.";

    $prompt = "Question: {$question}\n\n{$rubric}\n\nRespond ONLY with valid JSON, exactly this structure: "
        . '{"band":<0-9 in 0.5 steps>,"task_achievement":"...","coherence_cohesion":"...","lexical_resource":"...","grammatical_range":"...","overall_feedback":"...","improvements":["..."]}'
        . "\n\nCandidate response:\n{$essay}";

    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;
    $ch  = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode([
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]]
        ]),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    ]);
    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError || $httpCode !== 200) {
        error_log("gradeMockEssay Gemini error {$httpCode}: {$response}");
        return ['band' => 0.0, 'overall_feedback' => 'AI grading temporarily unavailable.'];
    }

    $result = json_decode($response, true);
    $text   = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
    $text   = preg_replace('/^```(?:json)?\s*/m', '', trim($text));
    $text   = preg_replace('/\s*```$/m', '', $text);
    $parsed = json_decode(trim($text), true);

    return $parsed ?? ['band' => 0.0, 'overall_feedback' => 'Could not parse AI response.'];
}
