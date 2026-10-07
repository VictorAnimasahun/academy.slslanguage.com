<?php
// GET  /api/v1/thread_messages.php?id=     -- the conversation (SLS-Academy-App-Spec.md 8.10)
// POST /api/v1/thread_messages.php         body: {"id": "1", "body": "..."} -- send (MSG-02/03)
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

$student = requireMobileAuth($db);

function threadBelongsToStudent(PDO $db, int $threadId, int $studentId): bool {
    return (bool) executeQuery($db, "SELECT 1 FROM thread_participants WHERE thread_id = ? AND student_id = ?", [$threadId, $studentId])->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $threadId = (int) ($_GET['id'] ?? 0);
    if (!$threadId || !threadBelongsToStudent($db, $threadId, $student['id'])) {
        http_response_code(404);
        echo json_encode(['error' => 'Conversation not found']);
        exit();
    }
    $rows = executeQuery($db, "
        SELECT id, sender_id, body, " . academyUtcExpr('created_at') . " AS created_at
        FROM thread_messages WHERE thread_id = ? ORDER BY id ASC
    ", [$threadId])->fetchAll(PDO::FETCH_ASSOC);

    $items = array_map(fn ($m) => [
        'id' => (string) $m['id'],
        'mine' => (int) $m['sender_id'] === (int) $student['id'],
        'text' => $m['body'],
        'createdAt' => str_replace(' ', 'T', $m['created_at']) . 'Z',
    ], $rows);
    echo json_encode(['items' => $items]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $threadId = (int) ($input['id'] ?? 0);
    $body = trim((string) ($input['body'] ?? ''));

    if (!$threadId || !threadBelongsToStudent($db, $threadId, $student['id'])) {
        http_response_code(404);
        echo json_encode(['error' => 'Conversation not found']);
        exit();
    }
    if ($body === '') {
        http_response_code(400);
        echo json_encode(['error' => 'Message cannot be empty']);
        exit();
    }

    executeQuery($db, "INSERT INTO thread_messages (thread_id, sender_id, body) VALUES (?, ?, ?)", [$threadId, $student['id'], $body]);
    $newId = (int) $db->lastInsertId();
    executeQuery($db, "UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?", [$newId, $threadId, $student['id']]);

    $row = executeQuery($db, "SELECT id, sender_id, body, " . academyUtcExpr('created_at') . " AS created_at FROM thread_messages WHERE id = ?", [$newId])->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['item' => [
        'id' => (string) $row['id'],
        'mine' => true,
        'text' => $row['body'],
        'createdAt' => str_replace(' ', 'T', $row['created_at']) . 'Z',
    ]]);
    exit();
}

http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
