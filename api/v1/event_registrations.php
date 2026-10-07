<?php
// POST   /api/v1/event_registrations.php  body: {"event_id": "1"} -- EVT-01: Join
// DELETE /api/v1/event_registrations.php  body: {"event_id": "1"} -- EVT-02: leave
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$eventId = (int) ($input['event_id'] ?? 0);

if (!$eventId) {
    http_response_code(400);
    echo json_encode(['error' => 'event_id is required']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    executeQuery($db, "INSERT IGNORE INTO event_registrations (event_id, student_id) VALUES (?, ?)", [$eventId, $student['id']]);
    echo json_encode(['success' => true]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    executeQuery($db, "DELETE FROM event_registrations WHERE event_id = ? AND student_id = ?", [$eventId, $student['id']]);
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
