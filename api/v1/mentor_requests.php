<?php
// POST /api/v1/mentor_requests.php   body: {"mentor_id": "1"}        -- MEN-01: request a mentor
// DELETE /api/v1/mentor_requests.php body: {"request_id": "1"}       -- MBK-04: cancel it
// No slot is required here -- the app's one-tap Book/Requested pill doesn't have a day/time picker
// yet (that is the web's mentor_book.php, spec 8.22); a request with no preferred time is valid,
// and the mentor follows up with a time in Messages (MBK-02).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mentorId = (int) ($input['mentor_id'] ?? 0);
    $mentor = $mentorId ? executeQuery($db, "SELECT id FROM mentors WHERE id = ? AND is_active = 1", [$mentorId])->fetch() : null;
    if (!$mentor) {
        http_response_code(404);
        echo json_encode(['error' => 'Mentor not found']);
        exit();
    }
    $open = executeQuery($db, "SELECT 1 FROM mentor_requests WHERE mentor_id = ? AND student_id = ? AND status IN ('requested','confirmed')", [$mentorId, $student['id']])->fetch();
    if ($open) {
        http_response_code(409);
        echo json_encode(['error' => 'You already have an open request with this mentor.']);
        exit();
    }
    executeQuery($db, "INSERT INTO mentor_requests (mentor_id, student_id, status) VALUES (?, ?, 'requested')", [$mentorId, $student['id']]);
    echo json_encode(['success' => true]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $requestId = (int) ($input['request_id'] ?? 0);
    executeQuery($db, "UPDATE mentor_requests SET status = 'cancelled' WHERE id = ? AND student_id = ?", [$requestId, $student['id']]);
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
