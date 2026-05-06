<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['messages' => []]);
    exit;
}

$uid = (int)$_SESSION['user_id'];

$stmt = executeQuery($db, "
    SELECT m.*,
           (SELECT COUNT(*) FROM broadcast_message_reads WHERE message_id = m.id AND student_id = ?) AS is_read
    FROM broadcast_messages m
    WHERE (
        m.target_all_students = 1
        OR FIND_IN_SET(?, m.target_student_ids)
        OR EXISTS (
            SELECT 1 FROM enrollments e
            WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids)
        )
    )
    ORDER BY m.created_at DESC
    LIMIT 5
", [$uid, $uid, $uid]);

$messages = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Trim content for preview
foreach ($messages as &$m) {
    $m['preview'] = mb_substr(strip_tags($m['content']), 0, 100);
    $m['is_read']  = (bool)$m['is_read'];
    unset($m['content'], $m['target_student_ids'], $m['target_course_ids']);
}

echo json_encode(['messages' => $messages]);
