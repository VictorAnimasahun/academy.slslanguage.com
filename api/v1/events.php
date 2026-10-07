<?php
// GET /api/v1/events.php -- the mobile Events screen (SLS-Academy-App-Spec.md 8.12).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);

$rows = executeQuery($db, "
    SELECT ev.id, ev.title, " . academyUtcExpr('ev.starts_at') . " AS starts_at,
           (r.id IS NOT NULL) AS registered
    FROM events ev
    LEFT JOIN event_registrations r ON r.event_id = ev.id AND r.student_id = ?
    WHERE ev.starts_at >= NOW()
    ORDER BY ev.starts_at ASC
", [$student['id']])->fetchAll(PDO::FETCH_ASSOC);

$items = array_map(fn ($r) => [
    'id' => (string) $r['id'],
    'title' => $r['title'],
    'startsAt' => str_replace(' ', 'T', $r['starts_at']) . 'Z',
    'registered' => (bool) $r['registered'],
], $rows);

echo json_encode(['items' => $items]);
