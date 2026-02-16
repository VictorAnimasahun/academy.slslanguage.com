<?php
// File: academy/api/get_unread_count.php
header('Content-Type: application/json');

// Go up one level to academy root, then require bootstrap
require_once __DIR__ . '/../bootstrap.php';

$unread_count = 0;

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];

    $stmt = executeQuery($db, "
        SELECT COUNT(*) AS unread_count
        FROM broadcast_messages m
        LEFT JOIN broadcast_message_reads r 
            ON r.message_id = m.id AND r.student_id = ?
        WHERE (
            m.target_all_students = 1
            OR FIND_IN_SET(?, m.target_student_ids)
            OR EXISTS (
                SELECT 1 FROM enrollments e
                WHERE e.student_id = ? 
                AND FIND_IN_SET(e.course_id, m.target_course_ids)
            )
        )
        AND (r.id IS NULL)
    ", [$uid, $uid, $uid]);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $unread_count = intval($row['unread_count']);
    }
}

echo json_encode(['unread_count' => $unread_count]);
?>