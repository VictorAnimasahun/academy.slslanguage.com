<?php
// GET /api/v1/vocab_words.php[?letter=A] -- the mobile Vocabulary banks home (prototype's
// vocabView()/word-of-the-day hero). Reuses the exact same real table and "word of the day" date
// math as resources/vocabulary_banks/vocab_home.php (no DB writes, so there's nothing to keep in
// sync beyond reading the same formula) -- 36 real words, not sample data.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

requireMobileAuth($db);

$words = $db->query(
    "SELECT id, headword, phonetic, word_class, cefr_level, is_awl, definition, sort_order
       FROM vocabulary_words
      WHERE is_active = 1
      ORDER BY sort_order, headword"
)->fetchAll(PDO::FETCH_ASSOC);

$dailyWord = null;
if ($words) {
    $idx = (int) (date('z') + date('Y')) % count($words);
    $dailyWord = $words[$idx];
}

$filter = strtoupper(trim($_GET['letter'] ?? ''));
$list = $filter ? array_values(array_filter($words, fn ($w) => strtoupper($w['headword'][0]) === $filter)) : $words;

$shape = fn ($w) => [
    'id' => (int) $w['id'],
    'headword' => $w['headword'],
    'phonetic' => $w['phonetic'],
    'wordClass' => $w['word_class'],
    'cefrLevel' => $w['cefr_level'],
    'isAwl' => (bool) $w['is_awl'],
    'definition' => $w['definition'],
];

echo json_encode([
    'dailyWord' => $dailyWord ? $shape($dailyWord) : null,
    'words' => array_map($shape, $list),
    'totalCount' => count($words),
], JSON_UNESCAPED_UNICODE);
