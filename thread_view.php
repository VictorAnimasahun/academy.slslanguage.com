<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$threadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mentorId = (int) ($_GET['mentor'] ?? $_POST['mentor'] ?? 0);   // POST: the Inbox's Compose window

// Reading happens in the Inbox now (2026-10-27): a conversation opens there, a new one in its Compose window.
// This page only sends (POST).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ($threadId ? "inbox.php?open=t$threadId" : ($mentorId ? "inbox.php?compose=$mentorId" : 'inbox.php')));
    exit();
}

// A message to a mentor (the Compose window): added to the existing conversation with them, or the conversation
// is created with this first message.
if (!$threadId && $mentorId) {
    $existing = executeQuery($db, "
        SELECT t.id FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        WHERE t.mentor_id = ?
    ", [$studentId, $mentorId])->fetch(PDO::FETCH_ASSOC);
    if ($existing) $threadId = (int) $existing['id'];   // already talking to this mentor: add to that conversation
}

$mentor = $mentorId ? executeQuery($db, "SELECT * FROM mentors WHERE id = ? AND is_active = 1", [$mentorId])->fetch(PDO::FETCH_ASSOC) : null;

if ($threadId) {
    $thread = executeQuery($db, "
        SELECT t.*, m.display_name AS mentor_name FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        LEFT JOIN mentors m ON m.id = t.mentor_id
        WHERE t.id = ?
    ", [$studentId, $threadId])->fetch(PDO::FETCH_ASSOC);
    if (!$thread) { header("Location: inbox.php"); exit(); }
    $mentor = ['id' => $thread['mentor_id'], 'display_name' => $thread['mentor_name']];
} elseif (!$mentor) {
    header("Location: inbox.php"); exit();
}

// MSG-02/03: send a message. Creates the thread lazily on the first message if it doesn't exist.
{
    $body = trim((string) ($_POST['body'] ?? ''));
    if ($body !== '') {
        if (!$threadId) {
            $db->beginTransaction();
            $db->prepare("INSERT INTO threads (type, mentor_id) VALUES ('coach', ?)")->execute([$mentorId]);
            $threadId = (int) $db->lastInsertId();
            $db->prepare("INSERT INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $studentId]);
            // The mentor's own staff login (if any) is a participant too, so they see it on their side.
            $mentorStaffStudentId = executeQuery($db, "
                SELECT sa.student_id FROM mentors m JOIN staff_accounts sa ON sa.id = m.staff_account_id WHERE m.id = ?
            ", [$mentorId])->fetchColumn();
            if ($mentorStaffStudentId) {
                $db->prepare("INSERT IGNORE INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $mentorStaffStudentId]);
            }
            $db->commit();
        }
        executeQuery($db, "INSERT INTO thread_messages (thread_id, sender_id, body) VALUES (?, ?, ?)", [$threadId, $studentId, $body]);
        // MSG-01 equivalent for the sender's own view: sending counts as having read up to now.
        $lastId = (int) executeQuery($db, "SELECT MAX(id) FROM thread_messages WHERE thread_id = ?", [$threadId])->fetchColumn();
        executeQuery($db, "UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?", [$lastId, $threadId, $studentId]);
    }
    header("Location: inbox.php?open=t$threadId");
    exit();
}
