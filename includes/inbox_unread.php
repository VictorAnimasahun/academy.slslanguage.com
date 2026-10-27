<?php
// What the inbox (messages.php) counts as unread: announcements, course updates and reminders not yet opened, plus
// conversations whose latest message is from the other side and unread (one per conversation). One count for the
// bell's glow (includes/topbar.php, at page build) and api/get_unread_count.php (the bell's refresh), so they agree.
if (!function_exists('inbox_unread_count')) {
    function inbox_unread_count(PDO $db, int $studentId): int {
        $n = (int) executeQuery($db, "
            SELECT COUNT(*) FROM broadcast_messages m
            LEFT JOIN broadcast_message_reads r ON r.message_id = m.id AND r.student_id = ?
            WHERE (m.target_all_students = 1
                   OR FIND_IN_SET(?, m.target_student_ids)
                   OR EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids)))
              AND r.id IS NULL
        ", [$studentId, $studentId, $studentId])->fetchColumn();
        $n += (int) executeQuery($db, "
            SELECT COUNT(*) FROM threads t
            JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
            JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
            WHERE lm.sender_id != ? AND lm.id > COALESCE(tp.last_read_message_id, 0)
        ", [$studentId, $studentId])->fetchColumn();
        return $n;
    }
}
