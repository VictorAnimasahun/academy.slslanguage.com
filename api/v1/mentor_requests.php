<?php
// POST /api/v1/mentor_requests.php   body: {"mentor_id": "1", "slot": "2026-10-14 09:00:00", "note": "..."}
//                                                                                -- MEN-01/MBK-01..03: request a mentor
// DELETE /api/v1/mentor_requests.php body: {"request_id": "1"}       -- MBK-04: cancel it
// "slot" and "note" are both optional -- mentors.tsx's one-tap pill still posts just mentor_id
// (the mentor follows up with a time in Messages), while mentor-detail.tsx's real day/time
// picker posts a slot, re-validated here the same way mentor_book.php validates it on the web
// (shared includes/mentor_booking.php -- same function, not a reimplementation that could drift).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once dirname(__DIR__, 2) . '/includes/mentor_booking.php';
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

    $slotInput = trim((string) ($input['slot'] ?? ''));
    $note = trim((string) ($input['note'] ?? ''));

    if ($slotInput !== '') {
        $parsed = DateTime::createFromFormat('Y-m-d H:i:s', $slotInput);
        if (!$parsed) {
            http_response_code(400);
            echo json_encode(['error' => 'Choose a day and time.']);
            exit();
        }
        $error = validateMentorBookingSlot($db, $mentorId, $student['id'], $parsed);
        if ($error) {
            http_response_code(409);
            echo json_encode(['error' => $error]);
            exit();
        }
        executeQuery($db, "INSERT INTO mentor_requests (mentor_id, student_id, preferred_at, note, status) VALUES (?, ?, ?, ?, 'requested')", [
            $mentorId, $student['id'], $parsed->format('Y-m-d H:i:s'), $note !== '' ? $note : null,
        ]);
        echo json_encode(['success' => true]);
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
