<?php
// POST /api/v1/thread_read.php  body: {"id": "1"} -- MSG-01: mark a conversation read
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$threadId = (int) ($input['id'] ?? 0);

$lastId = (int) executeQuery($db, "SELECT MAX(id) FROM thread_messages WHERE thread_id = ?", [$threadId])->fetchColumn();
executeQuery($db, "UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?", [$lastId, $threadId, $student['id']]);

echo json_encode(['success' => true]);
