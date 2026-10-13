<?php
// GET /api/v1/message_recipients.php -- the "To" step of 8.37 New message (CMP-01).
//
// CMP-01 says recipients are "the coaches on the student's courses, plus SLS support" -- there is
// no course-to-mentor link in the schema today (mentors aren't assigned per course, migration 142),
// and only one real mentor exists, so this simply returns every active mentor (bookable or not --
// unlike the Mentors tab's booking list, SLS Support belongs here even though it isn't bookable).
// Revisit with a real per-course filter if/when multiple coaches exist.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

requireMobileAuth($db);

$rows = executeQuery($db, "
    SELECT id, display_name, focus_area, staff_account_id
    FROM mentors
    WHERE is_active = 1
    ORDER BY (staff_account_id IS NULL) ASC, display_name
")->fetchAll(PDO::FETCH_ASSOC);

$items = array_map(fn ($r) => [
    'id' => (string) $r['id'],
    'name' => $r['display_name'],
    'focusArea' => $r['focus_area'],
    'isSupport' => $r['staff_account_id'] === null,
], $rows);

echo json_encode(['items' => $items]);
