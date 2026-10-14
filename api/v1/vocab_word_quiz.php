<?php
// GET /api/v1/vocab_word_quiz.php?word_id=N -- quiz questions for one word.
// POST /api/v1/vocab_word_quiz.php {word_id, answers: {questionId: value}} -- mark it.
// Same scoring rule and the same test_attempts/attempt_answers writes as
// resources/vocabulary_banks/word_quiz.php (test row 'VOCAB_WORD_NNN', looked up by the word's
// sort_order) -- one source of truth for what counts as a right answer, just reachable from the
// app instead of only the browser.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

$student = requireMobileAuth($db);

$wordId = (int) ($_GET['word_id'] ?? ($_SERVER['REQUEST_METHOD'] === 'POST' ? null : 0));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $wordId = (int) ($input['word_id'] ?? 0);
}
if ($wordId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'A word id is required']);
    exit();
}

$wstmt = $db->prepare('SELECT * FROM vocabulary_words WHERE id = ? AND is_active = 1');
$wstmt->execute([$wordId]);
$word = $wstmt->fetch(PDO::FETCH_ASSOC);
if (!$word) {
    http_response_code(404);
    echo json_encode(['error' => 'Word not found']);
    exit();
}

$qstmt = $db->prepare('SELECT * FROM questions WHERE word_id = ? ORDER BY display_order, question_number');
$qstmt->execute([$wordId]);
$questions = $qstmt->fetchAll(PDO::FETCH_ASSOC);
if (!$questions) {
    http_response_code(404);
    echo json_encode(['error' => 'No quiz for this word yet']);
    exit();
}

$qids = array_column($questions, 'id');
$placeholders = implode(',', array_fill(0, count($qids), '?'));

$optStmt = $db->prepare("SELECT * FROM question_options WHERE question_id IN ($placeholders) ORDER BY display_order");
$optStmt->execute($qids);
$optsByQ = [];
foreach ($optStmt->fetchAll(PDO::FETCH_ASSOC) as $o) {
    $optsByQ[$o['question_id']][] = $o;
}

$ansStmt = $db->prepare("SELECT * FROM question_correct_answers WHERE question_id IN ($placeholders)");
$ansStmt->execute($qids);
$answersByQ = [];
foreach ($ansStmt->fetchAll(PDO::FETCH_ASSOC) as $a) {
    $answersByQ[$a['question_id']][] = $a;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $out = array_map(function ($q) use ($optsByQ) {
        return [
            'id' => (int) $q['id'],
            'questionText' => $q['question_text'],
            'questionType' => $q['question_type'],
            'options' => array_map(fn ($o) => ['id' => (int) $o['id'], 'label' => $o['option_label'], 'text' => $o['option_text']], $optsByQ[$q['id']] ?? []),
        ];
    }, $questions);
    echo json_encode(['word' => ['id' => (int) $word['id'], 'headword' => $word['headword']], 'questions' => $out], JSON_UNESCAPED_UNICODE);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$answers = $input['answers'] ?? [];

$totalScore = 0.0;
$maxScore = 0.0;
$resultDetails = [];

foreach ($questions as $q) {
    $qid = (int) $q['id'];
    $type = $q['question_type'];
    $pts = (float) $q['points'];
    $maxScore += $pts;
    $awarded = 0.0;
    $correct = false;
    $userAnswer = '';
    $correctAnswer = '';
    $given = $answers[(string) $qid] ?? $answers[$qid] ?? null;

    if ($type === 'multiple_choice_single') {
        $selectedId = (int) $given;
        foreach ($optsByQ[$qid] ?? [] as $opt) {
            if ((int) $opt['id'] === $selectedId) {
                $userAnswer = $opt['option_label'] . ': ' . $opt['option_text'];
                if ($opt['is_correct']) {
                    $awarded = $pts;
                    $correct = true;
                }
            }
            if ($opt['is_correct']) {
                $correctAnswer = $opt['option_label'] . ': ' . $opt['option_text'];
            }
        }
    } elseif ($type === 'gap_fill') {
        $userAnswer = trim((string) $given);
        foreach ($answersByQ[$qid] ?? [] as $ca) {
            $match = $ca['is_case_sensitive'] ? ($userAnswer === $ca['answer_text']) : (strtolower($userAnswer) === strtolower($ca['answer_text']));
            if ($match) {
                $awarded = $pts;
                $correct = true;
            }
            if (!$correctAnswer) {
                $correctAnswer = $ca['answer_text'];
            }
        }
    }

    $totalScore += $awarded;
    $resultDetails[] = [
        'questionId' => $qid,
        'correct' => $correct,
        'awarded' => $awarded,
        'userAnswer' => $userAnswer,
        'correctAnswer' => $correctAnswer,
        'selectedOptionId' => $type === 'multiple_choice_single' && $given !== null ? (int) $given : null,
        'gapAnswerText' => $type === 'gap_fill' ? $userAnswer : null,
    ];
}

$testStmt = $db->prepare("SELECT id FROM tests WHERE test_type = 'Vocabulary' AND code = ?");
$code = 'VOCAB_WORD_' . str_pad((string) $word['sort_order'], 3, '0', STR_PAD_LEFT);
$testStmt->execute([$code]);
$testRow = $testStmt->fetch(PDO::FETCH_ASSOC);

if ($testRow) {
    $testId = (int) $testRow['id'];
    $maxAttemptStmt = $db->prepare('SELECT COALESCE(MAX(attempt_number), 0) FROM test_attempts WHERE student_id = ? AND test_id = ?');
    $maxAttemptStmt->execute([$student['id'], $testId]);
    $maxAttempt = (int) $maxAttemptStmt->fetchColumn();

    $db->prepare(
        "INSERT INTO test_attempts (student_id, test_id, attempt_number, mode, score, max_score, status, completed_at)
         VALUES (?, ?, ?, 'practice', ?, ?, 'completed', NOW())"
    )->execute([$student['id'], $testId, $maxAttempt + 1, $totalScore, $maxScore]);
    $attemptId = (int) $db->lastInsertId();

    foreach ($resultDetails as $rd) {
        $db->prepare(
            'INSERT INTO attempt_answers (attempt_id, question_id, selected_option_id, answer_text, score_awarded) VALUES (?, ?, ?, ?, ?)'
        )->execute([$attemptId, $rd['questionId'], $rd['selectedOptionId'], $rd['gapAnswerText'], $rd['awarded']]);
    }
}

echo json_encode([
    'score' => $totalScore,
    'maxScore' => $maxScore,
    'results' => $resultDetails,
], JSON_UNESCAPED_UNICODE);
