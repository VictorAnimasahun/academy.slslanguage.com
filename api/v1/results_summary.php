<?php
// GET /api/v1/results_summary.php -- the mobile My results screen (SLS-Academy-App-Spec.md 8.8).
// Real mock-test results: mock_sessions (one row per full mock attempt), released only -- results
// are held back until an instructor adds Speaking, same rule as the web (memory: Mock Test Results
// System). listening/reading come from test_attempts (score/max_score + band_score); writing/
// speaking bands live on mock_sessions directly, since a coach marks those.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);

$sessions = executeQuery($db, "
    SELECT ms.id, ms.overall_band, ms.writing_band, ms.speaking_band,
           " . academyUtcExpr('ms.released_at') . " AS released_at,
           t.title AS exam_title,
           la.score AS l_score, la.max_score AS l_max, la.band_score AS l_band,
           ra.score AS r_score, ra.max_score AS r_max, ra.band_score AS r_band
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    LEFT JOIN test_attempts la ON la.id = ms.listening_attempt_id
    LEFT JOIN test_attempts ra ON ra.id = ms.reading_attempt_id
    WHERE ms.student_id = ? AND ms.status = 'results_released'
    ORDER BY ms.released_at DESC
", [$student['id']])->fetchAll(PDO::FETCH_ASSOC);

if (!$sessions) {
    echo json_encode(['latestDate' => null, 'skills' => [], 'recent' => []]);
    exit();
}

$latest = $sessions[0];
$skills = [
    ['skill' => 'listening', 'band' => $latest['l_band'] !== null ? (float) $latest['l_band'] : null, 'awaitingFeedback' => $latest['l_band'] === null],
    ['skill' => 'reading', 'band' => $latest['r_band'] !== null ? (float) $latest['r_band'] : null, 'awaitingFeedback' => $latest['r_band'] === null],
    ['skill' => 'writing', 'band' => $latest['writing_band'] !== null ? (float) $latest['writing_band'] : null, 'awaitingFeedback' => $latest['writing_band'] === null],
    ['skill' => 'speaking', 'band' => $latest['speaking_band'] !== null ? (float) $latest['speaking_band'] : null, 'awaitingFeedback' => $latest['speaking_band'] === null],
];

$recent = array_map(fn ($s) => [
    'id' => (string) $s['id'],
    'title' => $s['exam_title'],
    'date' => substr($s['released_at'], 0, 10),
    'band' => $s['overall_band'] !== null ? (float) $s['overall_band'] : 0,
], array_slice($sessions, 0, 10));

echo json_encode([
    'latestDate' => $latest['released_at'] ? (new DateTime($latest['released_at']))->format('j M Y') : null,
    'skills' => $skills,
    'recent' => $recent,
]);
