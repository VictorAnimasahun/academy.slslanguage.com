<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

// NTF-01: "Mark all as read" -- only applies to the kinds that have a real read/unread state
// (announcements, messages). Assignment-due and event-reminder rows are always-shown reminders
// while their window is open, not a read/unread inbox item.
if (isset($_GET['mark_all_read'])) {
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
    header("Location: notifications.php");
    exit();
}

$items = [];

// Kind: announcement (broadcast_messages) -- unchanged source, just folded into one feed.
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

// Kind: message -- one row per thread with something unread.
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

// Kind: assignment due -- within the next 2 days, not completed. Always shown while the window is
// open; no stored read state (ASG/NTF don't define one for this kind).
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
        'title' => 'Due soon', 'body' => e($a['title']) . ' is due ' . (new DateTime($a['due_date']))->format('j M'),
        'at' => $a['due_date'] . ' 00:00:00', 'unread' => false, 'url' => 'assignments.php',
    ];
}

// Kind: event reminder -- registered and starting within 24 hours.
$soonEvents = executeQuery($db, "
    SELECT ev.id, ev.title, ev.starts_at FROM events ev
    JOIN event_registrations r ON r.event_id = ev.id AND r.student_id = ?
    WHERE ev.starts_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 24 HOUR)
", [$studentId])->fetchAll(PDO::FETCH_ASSOC);
foreach ($soonEvents as $ev) {
    $items[] = [
        'kind' => 'event_reminder', 'icon' => 'bi-calendar-event-fill', 'tint' => 'purple',
        'title' => 'Starting soon', 'body' => e($ev['title']) . ' at ' . (new DateTime($ev['starts_at']))->format('g:i A'),
        'at' => $ev['starts_at'], 'unread' => false, 'url' => 'events.php',
    ];
}

usort($items, fn($a, $b) => strcmp($b['at'], $a['at']));
$today = date('Y-m-d');
$todayItems = array_filter($items, fn($i) => substr($i['at'], 0, 10) === $today);
$earlierItems = array_filter($items, fn($i) => substr($i['at'], 0, 10) !== $today);
$anyUnread = (bool) array_filter($items, fn($i) => $i['unread']);

$tintBg = ['blue' => '#E3EEFF', 'orange' => '#FFE9CC', 'purple' => '#EAE3FF', 'pink' => '#FFE0EA', 'teal' => '#D6F3F0'];
$tintFg = ['blue' => '#2F6FE0', 'orange' => '#D9771A', 'purple' => '#6D3FE0', 'pink' => '#D6336C', 'teal' => '#0F9488'];

function render_notif_group($group, $tintBg, $tintFg) {
    foreach ($group as $i) {
        $bg = $tintBg[$i['tint']]; $fg = $tintFg[$i['tint']];
        echo '<a href="' . htmlspecialchars($i['url']) . '" class="notif-row' . ($i['unread'] ? ' unread' : '') . '">';
        echo '<div class="notif-icon" style="background:' . $bg . ';color:' . $fg . ';"><i class="bi ' . $i['icon'] . '"></i></div>';
        echo '<div class="flex-grow-1"><div class="fw-semibold">' . htmlspecialchars($i['title']) . '</div>';
        echo '<div class="text-muted small">' . $i['body'] . '</div></div>';
        if ($i['unread']) echo '<span class="notif-dot"></span>';
        echo '</a>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Notifications - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
        .notif-row { display:flex; align-items:center; gap:1rem; background:#fff; border-radius:14px; padding:.9rem 1.1rem; margin-bottom:.5rem; text-decoration:none; color:inherit; }
        body.dark .notif-row { background:#1f1f1f; }
        .notif-row.unread { border-left:3px solid #2f6fe0; }
        .notif-icon { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .notif-dot { width:8px; height:8px; border-radius:50%; background:#2f6fe0; flex-shrink:0; }
        .notif-group-label { font-size:.75rem; text-transform:uppercase; letter-spacing:.06em; color:#9ca3af; font-weight:700; margin:1rem 0 .5rem; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4" style="max-width:680px;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-bell-fill fs-2 text-primary"></i>
                    <div>
                        <h2 class="mb-0">Notifications</h2>
                        <p class="text-muted mb-0"><?= $anyUnread ? 'You have unread notifications' : 'You are all caught up' ?></p>
                    </div>
                </div>
                <?php if ($anyUnread): ?><a href="?mark_all_read=1" class="small">Mark all as read</a><?php endif; ?>
            </div>

            <?php if (!$items): ?>
                <div class="stat-card text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Nothing here yet</h5>
                </div>
            <?php else: ?>
                <?php if ($todayItems): ?><p class="notif-group-label">Today</p><?php render_notif_group($todayItems, $tintBg, $tintFg); ?><?php endif; ?>
                <?php if ($earlierItems): ?><p class="notif-group-label">Earlier</p><?php render_notif_group($earlierItems, $tintBg, $tintFg); ?><?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
