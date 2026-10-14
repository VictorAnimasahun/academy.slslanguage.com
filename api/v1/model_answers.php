<?php
// GET /api/v1/model_answers.php -- the mobile Model answers screen (prototype's modelsView()).
// Same real content as resources/model_answers/*.php: Cambridge Tests 1-5 Writing scripts, the
// Academic Writing Band 5/6 script comparison (with the real scanned handwriting images), and the
// official IELTS Speaking sample transcript. Shared _*.php data files are the one source of truth
// for the writing tests and the speaking transcript (both now require()'d by their web page too);
// the academic writing comparison's data file isn't yet wired back into its own web page (that
// page is still templated HTML, not array-driven) -- noted here rather than silently assumed in
// sync, since this is the one piece of content this endpoint doesn't fully share a single source
// with. No DB involved anywhere in this file.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once dirname(__DIR__, 2) . '/resources/model_answers/_writing_tests_data.php';
require_once dirname(__DIR__, 2) . '/resources/model_answers/_academic_writing_1_data.php';
require_once dirname(__DIR__, 2) . '/resources/model_answers/_speaking_1_data.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

requireMobileAuth($db);

$writingTests = [];
foreach (writingTestsData() as $num => $test) {
    $writingTests[] = [
        'num' => $num,
        'label' => $test['label'],
        'sourceNote' => $test['source_note'] ?? null,
        'tasks' => array_map(fn ($t) => [
            'id' => $t['id'],
            'taskNum' => $t['task_num'],
            'title' => $t['title'],
            'type' => $t['type'],
            'band' => $t['band'],
            'question' => $t['question'],
            'answer' => $t['answer'],
            'comment' => $t['comment'],
            'commentLabel' => $t['comment_label'] ?? "Examiner's Comment",
        ], $test['tasks']),
    ];
}

$aw = academicWriting1Data();
// ACADEMY_URL (paths.php) is root-relative only ('/academy/' local, '/' live) -- fine for an <img
// src> on the same page, meaningless to a mobile Image component with no "current origin". Rebuild
// a fully qualified URL from the request's own host, which is already whatever host the app
// reached this endpoint on (10.0.2.2:8888 local, academy.slslanguage.com live).
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$absoluteBase = $scheme . '://' . $_SERVER['HTTP_HOST'] . ACADEMY_URL;
$imgUrl = fn ($path) => $absoluteBase . $path;
$academicWriting1 = [
    'howMarked' => $aw['howMarked'],
    'task1' => [
        'label' => $aw['task1']['label'],
        'prompt' => $aw['task1']['prompt'],
        'note' => $aw['task1']['note'],
        'chartImageUrl' => $imgUrl($aw['task1']['chartImage']),
        'scripts' => array_map(fn ($s) => ['name' => $s['name'], 'band' => $s['band'], 'imageUrl' => $imgUrl($s['image']), 'comment' => $s['comment']], $aw['task1']['scripts']),
    ],
    'task2' => [
        'label' => $aw['task2']['label'],
        'prompt' => $aw['task2']['prompt'],
        'note' => $aw['task2']['note'],
        'scripts' => array_map(fn ($s) => ['name' => $s['name'], 'band' => $s['band'], 'imageUrl' => $imgUrl($s['image']), 'comment' => $s['comment']], $aw['task2']['scripts']),
    ],
    'source' => $aw['source'],
];

$speaking1 = array_map(fn ($p) => [
    'label' => $p['label'],
    'cueCard' => $p['cue_card'] ?? null,
    'exchanges' => $p['exchanges'],
], array_values(speaking1Data()));

echo json_encode([
    'writingTests' => $writingTests,
    'academicWriting1' => $academicWriting1,
    'speaking1' => $speaking1,
], JSON_UNESCAPED_UNICODE);
