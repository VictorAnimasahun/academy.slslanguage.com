<?php
// GET /api/v1/vocab_word_detail.php?id=N -- the mobile Word screen. Mirrors
// resources/vocabulary_banks/word.php's exact fields (synonyms/antonyms/collocations/word_family
// split, usages grouped by exam/skill, prev/next/random nav, quiz question count), just as JSON.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

requireMobileAuth($db);

$wordId = (int) ($_GET['id'] ?? 0);
if ($wordId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'A word id is required']);
    exit();
}

$stmt = $db->prepare('SELECT * FROM vocabulary_words WHERE id = ? AND is_active = 1');
$stmt->execute([$wordId]);
$word = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$word) {
    http_response_code(404);
    echo json_encode(['error' => 'Word not found']);
    exit();
}

$allWords = $db->query('SELECT id, headword FROM vocabulary_words WHERE is_active = 1 ORDER BY sort_order, headword')->fetchAll(PDO::FETCH_ASSOC);
$wordIds = array_column($allWords, 'id');
$pos = array_search($wordId, $wordIds, true);
$prevWord = $pos > 0 ? $allWords[$pos - 1] : null;
$nextWord = $pos < count($wordIds) - 1 ? $allWords[$pos + 1] : null;
$randomPool = array_values(array_filter($wordIds, fn ($id) => $id !== $wordId));
$randomId = $randomPool ? $randomPool[array_rand($randomPool)] : null;

$usageStmt = $db->prepare('SELECT * FROM word_test_usages WHERE word_id = ? ORDER BY exam_type, skill, sort_order');
$usageStmt->execute([$wordId]);
$usagesByExam = [];
foreach ($usageStmt->fetchAll(PDO::FETCH_ASSOC) as $u) {
    $usagesByExam[$u['exam_type']][$u['skill']][] = [
        'subSection' => $u['sub_section'],
        'exampleSentence' => $u['example_sentence'],
        'contextNote' => $u['context_note'],
    ];
}
$usageExams = [];
foreach ($usagesByExam as $exam => $skills) {
    $skillGroups = [];
    foreach ($skills as $skill => $rows) {
        $skillGroups[] = ['skill' => $skill, 'items' => $rows];
    }
    $usageExams[] = ['exam' => $exam, 'skills' => $skillGroups];
}

$qCountStmt = $db->prepare('SELECT COUNT(*) FROM questions WHERE word_id = ?');
$qCountStmt->execute([$wordId]);
$qCount = (int) $qCountStmt->fetchColumn();

function splitComma(?string $s): array {
    if (!$s) return [];
    return array_values(array_filter(array_map('trim', explode(',', $s))));
}

echo json_encode([
    'word' => [
        'id' => (int) $word['id'],
        'headword' => $word['headword'],
        'phonetic' => $word['phonetic'],
        'wordClass' => $word['word_class'],
        'cefrLevel' => $word['cefr_level'],
        'isAwl' => (bool) $word['is_awl'],
        'definition' => $word['definition'],
        'secondaryDefinitions' => $word['secondary_definitions'],
        'synonyms' => splitComma($word['synonyms']),
        'antonyms' => splitComma($word['antonyms']),
        'collocations' => splitComma($word['collocations']),
        'wordFamily' => splitComma($word['word_family']),
    ],
    'usageExams' => $usageExams,
    'quizQuestionCount' => $qCount,
    'prevWord' => $prevWord ? ['id' => (int) $prevWord['id'], 'headword' => $prevWord['headword']] : null,
    'nextWord' => $nextWord ? ['id' => (int) $nextWord['id'], 'headword' => $nextWord['headword']] : null,
    'randomWordId' => $randomId !== null ? (int) $randomId : null,
], JSON_UNESCAPED_UNICODE);
