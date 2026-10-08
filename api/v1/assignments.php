<?php
// GET /api/v1/assignments.php?status=all|to_do|done -- the mobile Assignments screen
// (SLS-Academy-App-Spec.md 8.6). Reuses the exact query assignments.php (the real web page) uses.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$status = $_GET['status'] ?? 'all';

$rows = executeQuery($db, "
    SELECT
        a.id, a.title, a.due_date,
        latest.score AS attempt_score, latest.max_score AS attempt_max,
        latest.band_score AS attempt_band, latest.status AS attempt_status,
        latest.completed_at AS attempt_date
    FROM assignments a
    JOIN courses c      ON c.id = a.course_id
    JOIN enrollments e  ON e.course_id = a.course_id AND e.student_id = ?
    LEFT JOIN (
        SELECT ta1.test_id, ta1.score, ta1.max_score, ta1.band_score, ta1.status, ta1.completed_at
        FROM test_attempts ta1
        JOIN (
            SELECT test_id, MAX(id) AS max_id FROM test_attempts WHERE student_id = ? GROUP BY test_id
        ) ta2 ON ta1.id = ta2.max_id
    ) latest ON latest.test_id = a.test_id
    WHERE a.student_id IS NULL OR a.student_id = ?
    ORDER BY CASE WHEN a.due_date IS NULL THEN 1 ELSE 0 END, a.due_date ASC, a.created_at DESC
", [$student['id'], $student['id'], $student['id']])->fetchAll(PDO::FETCH_ASSOC);

$items = [];
foreach ($rows as $r) {
    $isDone = $r['attempt_status'] === 'completed';
    $hasBand = $r['attempt_band'] !== null;
    $hasScore = $r['attempt_score'] !== null && $r['attempt_max'] !== null;

    $appStatus = !$isDone ? 'to_do' : ($hasBand ? 'graded' : 'submitted');

    $resultLabel = null;
    if ($isDone) {
        if ($hasBand) {
            $resultLabel = 'Graded · Band ' . number_format((float) $r['attempt_band'], 1);
        } elseif ($hasScore) {
            $date = $r['attempt_date'] ? (new DateTime($r['attempt_date']))->format('j M') : '';
            $resultLabel = trim("Submitted $date · {$r['attempt_score']}/{$r['attempt_max']}", ' ·');
        } else {
            $resultLabel = 'Submitted';
        }
    }

    if ($status === 'to_do' && $appStatus !== 'to_do') continue;
    if ($status === 'done' && $appStatus === 'to_do') continue;

    $items[] = [
        'id' => (string) $r['id'],
        'title' => $r['title'],
        'dueAt' => $r['due_date'],
        'status' => $appStatus,
        'resultLabel' => $resultLabel,
    ];
}

echo json_encode(['items' => $items]);
