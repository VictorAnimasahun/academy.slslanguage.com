<?php
// GET  /api/v1/notifications.php              -- the mobile Notifications screen (spec 8.17)
// POST /api/v1/notifications.php  body: {"mark_all_read": true}  -- NTF-01
// Same unified feed as the web's notifications.php, via the shared includes/notifications_feed.php
// (announcements + unread messages + assignments due soon + events starting soon).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once dirname(__DIR__, 2) . '/includes/notifications_feed.php';
header('Content-Type: application/json; charset=utf-8');

$student = requireMobileAuth($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    if (!empty($input['mark_all_read'])) {
        markAllNotificationsRead($db, $student['id']);
    }
    echo json_encode(['success' => true]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// The web's items carry a relative `url` (message_view.php?id=.., thread_view.php?id=.., ..);
// the app needs a native route/kind/id instead, since those pages aren't in the app.
function mobileNotificationTarget(string $kind, string $url): array {
    if ($kind === 'message' && preg_match('/id=(\d+)/', $url, $m)) {
        return ['screen' => 'thread', 'id' => $m[1]];
    }
    if ($kind === 'assignment_due') {
        return ['screen' => 'assignments', 'id' => null];
    }
    if ($kind === 'event_reminder') {
        return ['screen' => 'events', 'id' => null];
    }
    return ['screen' => null, 'id' => null]; // announcement: shown inline, nothing to open natively yet
}

$items = buildNotificationItems($db, $student['id']);
$out = array_map(function ($i) use ($db) {
    $target = mobileNotificationTarget($i['kind'], $i['url']);
    return [
        'id' => $i['kind'] . '-' . md5($i['url'] . $i['at']),
        'kind' => $i['kind'],
        'title' => $i['title'],
        'body' => $i['body'],
        'at' => academyUtcIso($db, $i['at']) . 'Z',
        'unread' => $i['unread'],
        'targetScreen' => $target['screen'],
        'targetId' => $target['id'],
    ];
}, $items);

echo json_encode(['items' => $out]);
