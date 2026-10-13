<?php
// POST /api/v1/thread_start.php  body: {"mentor_id": "1", "subject": "...", "body": "...", "urgent": true}
// 8.37 New message / CMP-01..03: a student starting a brand-new conversation, not replying
// inside one a coach already started (the only path that existed before tonight -- see
// academy/thread_view.php's own create-thread block, mirrored here).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once CONFIG_PATH . '/email_helper.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$mentorId = (int) ($input['mentor_id'] ?? 0);
$subject = trim((string) ($input['subject'] ?? ''));
$body = trim((string) ($input['body'] ?? ''));
$urgent = !empty($input['urgent']);

if (!$mentorId) {
    http_response_code(400);
    echo json_encode(['error' => 'Choose who to send it to']);
    exit();
}
if ($subject === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Add a subject']);
    exit();
}
if ($body === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Write your message']);
    exit();
}

$mentor = executeQuery($db, "SELECT id, display_name, staff_account_id FROM mentors WHERE id = ? AND is_active = 1", [$mentorId])->fetch(PDO::FETCH_ASSOC);
if (!$mentor) {
    http_response_code(404);
    echo json_encode(['error' => 'Recipient not found']);
    exit();
}

// CMP-03 (`[PROPOSED]`): limit urgent to 3 per student per day.
if ($urgent) {
    $urgentToday = (int) executeQuery($db, "
        SELECT COUNT(*) FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        WHERE t.is_urgent = 1 AND t.created_at > NOW() - INTERVAL 1 DAY
    ", [$student['id']])->fetchColumn();
    if ($urgentToday >= 3) {
        http_response_code(429);
        echo json_encode(['error' => "You've marked 3 messages urgent today. Please wait before sending another."]);
        exit();
    }
}

try {
    $db->beginTransaction();

    $db->prepare("INSERT INTO threads (type, mentor_id, subject, is_urgent) VALUES ('coach', ?, ?, ?)")
        ->execute([$mentorId, $subject, $urgent ? 1 : 0]);
    $threadId = (int) $db->lastInsertId();

    $db->prepare("INSERT INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $student['id']]);

    // The mentor's own staff login (if any) is a participant too, so they see it on their side --
    // same as thread_view.php's existing reply-thread creation. SLS Support (staff_account_id
    // NULL) has no such login; its threads are visible via mentor_id in the admin inbox instead.
    if ($mentor['staff_account_id']) {
        $mentorStudentId = executeQuery($db, "SELECT student_id FROM staff_accounts WHERE id = ?", [$mentor['staff_account_id']])->fetchColumn();
        if ($mentorStudentId) {
            $db->prepare("INSERT IGNORE INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $mentorStudentId]);
        }
    }

    $db->prepare("INSERT INTO thread_messages (thread_id, sender_id, body) VALUES (?, ?, ?)")->execute([$threadId, $student['id'], $body]);
    $lastId = (int) $db->lastInsertId();
    $db->prepare("UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?")->execute([$lastId, $threadId, $student['id']]);

    $db->commit();
} catch (PDOException $e) {
    $db->rollBack();
    error_log('thread_start error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not send that. Please try again later.']);
    exit();
}

$emailSent = false;
if ($urgent) {
    $recipientEmail = $mentor['staff_account_id'] === null ? 'info@slslanguage.com' : null;
    if ($recipientEmail === null) {
        $recipientEmail = executeQuery($db, "
            SELECT s.email FROM staff_accounts sa JOIN students s ON s.id = sa.student_id WHERE sa.id = ?
        ", [$mentor['staff_account_id']])->fetchColumn() ?: null;
    }
    if ($recipientEmail) {
        $preview = mb_strlen($body) > 200 ? mb_substr($body, 0, 200) . '...' : $body;
        $emailSent = send_email(
            $recipientEmail,
            $mentor['display_name'],
            "Urgent message from a student: {$subject}",
            '<p><strong>' . htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) . '</strong> marked a message urgent.</p>'
                . '<p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>'
                . '<p>' . nl2br(htmlspecialchars($preview)) . '</p>'
                . '<p>Reply from the SLS Academy admin panel.</p>',
            "{$student['firstname']} {$student['lastname']} marked a message urgent.\nSubject: {$subject}\n\n{$preview}\n\nReply from the SLS Academy admin panel."
        );
    }
}

echo json_encode(['success' => true, 'threadId' => (string) $threadId, 'urgentEmailSent' => $emailSent]);
