<?php
// GET /api/v1/analytics.php?range=week|month -- the mobile Analytics screen
// (SLS-Academy-App-Spec 8.11). This endpoint never existed -- the app's analytics.tsx has always
// been calling a 404 (found 2026-10-14 while walking the real APK against the interactive
// prototype). WEB_FIRST_FOR_MOBILE_SPEC_v0.3.md flags true study-time (video watch minutes) as
// blocked: `students_activity` has zero rows and nothing writes to it, because lesson_progress
// video-watch tracking was never built either (memory: Class completion rules). Rather than fake
// that data, this reuses the one real time-on-task signal that already exists and is written by
// real code -- test_attempts.time_spent (same source analytics.php on the web already shows as
// "Time Practising") -- plus lesson_progress completions for the streak. Honest, real, just
// narrower than "study time" implies until lesson-level time tracking is built.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$range = ($_GET['range'] ?? 'week') === 'month' ? 'month' : 'week';

// All bucketing below is done entirely in MySQL's own clock (CURDATE()/DATE()), never mixed with
// PHP's time() -- test_attempts.completed_at is written via MySQL's NOW() (save_attempt.php),
// so this stays internally consistent rather than risking the NOW()-vs-time() mismatch documented
// elsewhere in this codebase.
$today = $db->query('SELECT CURDATE() AS d')->fetch(PDO::FETCH_ASSOC)['d'];
$todayTs = strtotime($today);

if ($range === 'week') {
    $rows = executeQuery($db, "
        SELECT DATE(completed_at) AS d, SUM(time_spent) AS secs
        FROM test_attempts
        WHERE student_id = ? AND status = 'completed' AND completed_at >= CURDATE() - INTERVAL 6 DAY
        GROUP BY DATE(completed_at)
    ", [$student['id']])->fetchAll(PDO::FETCH_ASSOC);
    $byDate = [];
    foreach ($rows as $r) {
        $byDate[$r['d']] = (int) round(($r['secs'] ?? 0) / 60);
    }

    $bars = [];
    for ($i = 6; $i >= 0; $i--) {
        $day = date('Y-m-d', $todayTs - $i * 86400);
        $bars[] = ['label' => date('D', strtotime($day)), 'minutes' => $byDate[$day] ?? 0];
    }
    $spanDays = 6;
} else {
    $rows = executeQuery($db, "
        SELECT FLOOR(DATEDIFF(CURDATE(), DATE(completed_at)) / 7) AS wk, SUM(time_spent) AS secs
        FROM test_attempts
        WHERE student_id = ? AND status = 'completed' AND completed_at >= CURDATE() - INTERVAL 27 DAY
        GROUP BY wk
    ", [$student['id']])->fetchAll(PDO::FETCH_ASSOC);
    $byWeek = [];
    foreach ($rows as $r) {
        $byWeek[(int) $r['wk']] = (int) round(($r['secs'] ?? 0) / 60);
    }

    $bars = [];
    for ($w = 3; $w >= 0; $w--) {
        $bars[] = ['label' => $w === 0 ? 'This wk' : $w . ' wk ago', 'minutes' => $byWeek[$w] ?? 0];
    }
    $spanDays = 27;
}

$countRow = executeQuery($db, "
    SELECT COUNT(*) AS cnt, COALESCE(SUM(time_spent), 0) AS secs
    FROM test_attempts
    WHERE student_id = ? AND status = 'completed' AND completed_at >= CURDATE() - INTERVAL $spanDays DAY
", [$student['id']])->fetch(PDO::FETCH_ASSOC);

$totalMinutes = (int) round($countRow['secs'] / 60);
$practiceTests = (int) $countRow['cnt'];

// Streak: consecutive days (ending today, or yesterday if nothing today yet so a streak isn't
// wiped out before the day is even over) with any completed test attempt or lesson completion.
$activeDays = executeQuery($db, "
    SELECT DATE(completed_at) AS d FROM test_attempts WHERE student_id = ? AND status = 'completed'
    UNION
    SELECT DATE(completed_at) AS d FROM lesson_progress WHERE student_id = ? AND completed = 1
", [$student['id'], $student['id']])->fetchAll(PDO::FETCH_COLUMN);
$activeSet = array_flip($activeDays);

$cursor = $todayTs;
if (!isset($activeSet[date('Y-m-d', $cursor)])) {
    $cursor -= 86400;
}
$streak = 0;
while (isset($activeSet[date('Y-m-d', $cursor)])) {
    $streak++;
    $cursor -= 86400;
}

echo json_encode([
    'bars' => $bars,
    'totalMinutes' => $totalMinutes,
    'practiceTests' => $practiceTests,
    'streakDays' => $streak,
]);
