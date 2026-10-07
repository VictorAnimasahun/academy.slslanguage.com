<?php
// GET /api/v1/threads.php -- the mobile Messages list (SLS-Academy-App-Spec.md 8.10).
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);

// MySQL's own clock currently runs its session time zone (SYSTEM), not UTC, while PHP is UTC
// (see migration 144's note) -- normalize every timestamp to true UTC here with the live offset,
// rather than assume the two already agree. academyUtc() is defined in mobile_auth.php.
$rows = executeQuery($db, "
    SELECT t.id, t.type, m.display_name AS mentor_name,
           lm.body AS last_body, " . academyUtcExpr('lm.created_at') . " AS last_at, lm.sender_id AS last_sender_id,
           (SELECT COUNT(*) FROM thread_messages tm2
             WHERE tm2.thread_id = t.id AND tm2.sender_id != ?
               AND tm2.id > COALESCE(tp.last_read_message_id, 0)) AS unread_count
    FROM threads t
    JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
    LEFT JOIN mentors m ON m.id = t.mentor_id
    LEFT JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
    ORDER BY lm.created_at DESC
", [$student['id'], $student['id']])->fetchAll(PDO::FETCH_ASSOC);

$items = array_map(fn ($r) => [
    'id' => (string) $r['id'],
    'name' => $r['mentor_name'] ?? 'Conversation',
    'kind' => $r['type'],
    'lastMessage' => $r['last_body'],
    'lastMessageMine' => $r['last_sender_id'] !== null && (int) $r['last_sender_id'] === (int) $student['id'],
    'lastMessageAt' => $r['last_at'] ? str_replace(' ', 'T', $r['last_at']) . 'Z' : null,
    'unread' => (int) $r['unread_count'] > 0,
], $rows);

echo json_encode(['items' => $items]);
