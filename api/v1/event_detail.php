<?php
// GET /api/v1/event_detail.php?id=1 -- Event detail (SLS-Academy-App-Spec.md 8.12/8.23),
// the full version of what events.php's list card already shows on the web.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$eventId = (int) ($_GET['id'] ?? 0);

$row = $eventId ? executeQuery($db, "
    SELECT ev.*, " . academyUtcExpr('ev.starts_at') . " AS starts_at_utc,
           " . academyUtcExpr('ev.ends_at') . " AS ends_at_utc,
           (r.id IS NOT NULL) AS registered
    FROM events ev
    LEFT JOIN event_registrations r ON r.event_id = ev.id AND r.student_id = ?
    WHERE ev.id = ?
", [$student['id'], $eventId])->fetch(PDO::FETCH_ASSOC) : null;

if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'Event not found']);
    exit();
}

echo json_encode([
    'id' => (string) $row['id'],
    'title' => $row['title'],
    'description' => $row['description'],
    'host' => $row['host'],
    'startsAt' => str_replace(' ', 'T', $row['starts_at_utc']) . 'Z',
    'endsAt' => $row['ends_at_utc'] ? str_replace(' ', 'T', $row['ends_at_utc']) . 'Z' : null,
    'joinUrl' => $row['join_url'],
    'detailUrl' => $row['detail_url'],
    'tint' => $row['tint'],
    'liveBadgeLabel' => $row['is_live_badge_label'],
    'registered' => (bool) $row['registered'],
]);
