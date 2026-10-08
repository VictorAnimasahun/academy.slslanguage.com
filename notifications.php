<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}
require_once INCLUDES_PATH . '/notifications_feed.php';

// NTF-01: "Mark all as read" -- only applies to the kinds that have a real read/unread state
// (announcements, messages). Assignment-due and event-reminder rows are always-shown reminders
// while their window is open, not a read/unread inbox item.
if (isset($_GET['mark_all_read'])) {
    markAllNotificationsRead($db, $studentId);
    header("Location: notifications.php");
    exit();
}

$items = buildNotificationItems($db, $studentId);
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
