<?php
// GET /api/v1/result_detail.php?id= -- Result detail (SLS-Academy-App-Spec.md 8.21), one
// mock_sessions row. RSD-02's full per-question answer sheet isn't built here -- this is the
// overall + per-skill breakdown (real: band, and correct/total for Listening/Reading from
// test_attempts), which is what My results already shows; the full question-by-question review
// would need attempt_answers joined per question (resources/practice_tests/my_results.php does
// this for the web) and is a larger follow-up, not done tonight.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$id = (int) ($_GET['id'] ?? 0);

$row = executeQuery($db, "
    SELECT ms.id, ms.overall_band, ms.writing_band, ms.speaking_band,
           " . academyUtcExpr('ms.released_at') . " AS released_at,
           t.title AS exam_title,
           la.score AS l_score, la.max_score AS l_max, la.band_score AS l_band, la.time_spent AS l_time,
           ra.score AS r_score, ra.max_score AS r_max, ra.band_score AS r_band, ra.time_spent AS r_time
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    LEFT JOIN test_attempts la ON la.id = ms.listening_attempt_id
    LEFT JOIN test_attempts ra ON ra.id = ms.reading_attempt_id
    WHERE ms.id = ? AND ms.student_id = ? AND ms.status = 'results_released'
", [$id, $student['id']])->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'Result not found']);
    exit();
}

$timeSpent = max((int) $row['l_time'], (int) $row['r_time']);
$timeLabel = $timeSpent > 0 ? sprintf('%dh %dm', intdiv($timeSpent, 3600), intdiv($timeSpent % 3600, 60)) : null;

echo json_encode([
    'id' => (string) $row['id'],
    'title' => $row['exam_title'],
    'takenAt' => $row['released_at'] ? (new DateTime($row['released_at']))->format('j M Y') : null,
    'timeToFinish' => $timeLabel,
    'skills' => [
        [
            'skill' => 'listening', 'band' => $row['l_band'] !== null ? (float) $row['l_band'] : null,
            'detail' => $row['l_score'] !== null ? ((int) $row['l_score']) . ' of ' . ((int) $row['l_max']) . ' correct' : null,
        ],
        [
            'skill' => 'reading', 'band' => $row['r_band'] !== null ? (float) $row['r_band'] : null,
            'detail' => $row['r_score'] !== null ? ((int) $row['r_score']) . ' of ' . ((int) $row['r_max']) . ' correct' : null,
        ],
        ['skill' => 'writing', 'band' => $row['writing_band'] !== null ? (float) $row['writing_band'] : null, 'detail' => null],
        ['skill' => 'speaking', 'band' => $row['speaking_band'] !== null ? (float) $row['speaking_band'] : null, 'detail' => null],
    ],
]);
