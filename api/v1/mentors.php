<?php
// GET /api/v1/mentors.php -- the mobile Mentors screen (SLS-Academy-App-Spec.md 8.7/8.22).
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
    SELECT m.id, m.display_name, m.focus_area,
           r.id AS request_id, r.status AS request_status
    FROM mentors m
    LEFT JOIN mentor_requests r
        ON r.mentor_id = m.id AND r.student_id = ? AND r.status IN ('requested', 'confirmed')
    WHERE m.is_active = 1
    ORDER BY m.display_name
", [$student['id']])->fetchAll(PDO::FETCH_ASSOC);

$items = array_map(fn ($r) => [
    'id' => (string) $r['id'],
    'name' => $r['display_name'],
    'focusArea' => $r['focus_area'],
    'requested' => $r['request_status'] !== null,
    // The actual mentor_requests row id -- cancelMentorRequest() needs this, not the mentor's own
    // id, to cancel the right row (DELETE filters by mentor_requests.id).
    'requestId' => $r['request_id'] !== null ? (string) $r['request_id'] : null,
], $rows);

echo json_encode(['items' => $items]);
