<?php
// Shared notifications feed logic, used by notifications.php (web) and api/v1/notifications.php
// (mobile) so the two never drift apart. See notifications.php's own comments for the rules this
// follows (SLS-Academy-App-Spec.md 8.17, NTF-01..04).

if (defined('NOTIFICATIONS_FEED_LOADED')) {
    return;
}
define('NOTIFICATIONS_FEED_LOADED', true);

/**
 * Returns the student's notification items, newest first. Each item:
 * kind, icon (Bootstrap Icons class, web only), tint, title, body, at (MySQL datetime string,
 * local/SYSTEM time -- callers normalize to UTC themselves if they need to), unread, url
 * (relative to the academy root).
 */
function buildNotificationItems($db, int $studentId): array {
    $items = [];

    $announcements = executeQuery($db, "
        SELECT m.id, m.title, m.content, m.created_at,
               (r.id IS NOT NULL) AS is_read
        FROM broadcast_messages m
        LEFT JOIN broadcast_message_reads r ON r.message_id = m.id AND r.student_id = ?
        WHERE m.target_all_students = 1 OR FIND_IN_SET(?, m.target_student_ids)
           OR EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids))
        ORDER BY m.created_at DESC LIMIT 20
    ", [$studentId, $studentId, $studentId])->fetchAll(PDO::FETCH_ASSOC);
    foreach ($announcements as $a) {
        $items[] = [
            'kind' => 'announcement', 'icon' => 'bi-megaphone-fill', 'tint' => 'orange',
            'title' => $a['title'], 'body' => mb_substr(strip_tags($a['content']), 0, 140),
            'at' => $a['created_at'], 'unread' => !$a['is_read'], 'url' => 'message_view.php?id=' . $a['id'],
        ];
    }

    $threadsUnread = executeQuery($db, "
        SELECT t.id AS thread_id, m.display_name AS mentor_name, lm.body, lm.created_at
        FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        LEFT JOIN mentors m ON m.id = t.mentor_id
        JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
        WHERE lm.sender_id != ? AND lm.id > COALESCE(tp.last_read_message_id, 0)
    ", [$studentId, $studentId])->fetchAll(PDO::FETCH_ASSOC);
    foreach ($threadsUnread as $t) {
        $items[] = [
            'kind' => 'message', 'icon' => 'bi-chat-dots-fill', 'tint' => 'blue',
            'title' => $t['mentor_name'] ?? 'Message', 'body' => mb_substr($t['body'], 0, 140),
            'at' => $t['created_at'], 'unread' => true, 'url' => 'thread_view.php?id=' . $t['thread_id'],
        ];
    }

    $dueSoon = executeQuery($db, "
        SELECT a.id, a.title, a.due_date
        FROM assignments a
        JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
        LEFT JOIN (
            SELECT ta1.test_id, ta1.status FROM test_attempts ta1
            JOIN (SELECT test_id, MAX(id) AS max_id FROM test_attempts WHERE student_id = ? GROUP BY test_id) ta2 ON ta1.id = ta2.max_id
        ) latest ON latest.test_id = a.test_id
        WHERE (a.student_id IS NULL OR a.student_id = ?)
          AND a.due_date IS NOT NULL AND a.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
          AND (latest.status IS NULL OR latest.status != 'completed')
    ", [$studentId, $studentId, $studentId])->fetchAll(PDO::FETCH_ASSOC);
    foreach ($dueSoon as $a) {
        $items[] = [
            'kind' => 'assignment_due', 'icon' => 'bi-file-text-fill', 'tint' => 'orange',
            'title' => 'Due soon', 'body' => htmlspecialchars($a['title'], ENT_QUOTES, 'UTF-8') . ' is due ' . (new DateTime($a['due_date']))->format('j M'),
            'at' => $a['due_date'] . ' 00:00:00', 'unread' => false, 'url' => 'assignments.php',
        ];
    }

    $soonEvents = executeQuery($db, "
        SELECT ev.id, ev.title, ev.starts_at FROM events ev
        JOIN event_registrations r ON r.event_id = ev.id AND r.student_id = ?
        WHERE ev.starts_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
    ", [$studentId])->fetchAll(PDO::FETCH_ASSOC);
    foreach ($soonEvents as $ev) {
        $items[] = [
            'kind' => 'event_reminder', 'icon' => 'bi-calendar-event-fill', 'tint' => 'purple',
            'title' => 'Starting soon', 'body' => htmlspecialchars($ev['title'], ENT_QUOTES, 'UTF-8') . ' at ' . (new DateTime($ev['starts_at']))->format('g:i A'),
            'at' => $ev['starts_at'], 'unread' => false, 'url' => 'events.php',
        ];
    }

    usort($items, fn ($a, $b) => strcmp($b['at'], $a['at']));
    return $items;
}

/** NTF-01's "Mark all as read" -- only the kinds with a real read state (announcements, messages). */
function markAllNotificationsRead($db, int $studentId): void {
    executeQuery($db, "
        INSERT IGNORE INTO broadcast_message_reads (message_id, student_id)
        SELECT m.id, ? FROM broadcast_messages m
        WHERE m.target_all_students = 1 OR FIND_IN_SET(?, m.target_student_ids)
           OR EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids))
    ", [$studentId, $studentId, $studentId]);
    executeQuery($db, "
        UPDATE thread_participants tp
        JOIN (SELECT thread_id, MAX(id) AS max_id FROM thread_messages GROUP BY thread_id) m ON m.thread_id = tp.thread_id
        SET tp.last_read_message_id = m.max_id
        WHERE tp.student_id = ?
    ", [$studentId]);
}
