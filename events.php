<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

// EVT-01/02: join/leave, same-page POST like the rest of this site's small write actions.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int) ($_POST['event_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($eventId && $action === 'join') {
        executeQuery($db, "INSERT IGNORE INTO event_registrations (event_id, student_id) VALUES (?, ?)", [$eventId, $studentId]);
    } elseif ($eventId && $action === 'leave') {
        executeQuery($db, "DELETE FROM event_registrations WHERE event_id = ? AND student_id = ?", [$eventId, $studentId]);
    }
    header("Location: events.php");
    exit();
}

$tintBg = ['blue' => '#E3EEFF', 'green' => '#DDF5E6', 'orange' => '#FFE9CC', 'purple' => '#EAE3FF', 'pink' => '#FFE0EA', 'teal' => '#D6F3F0', 'yellow' => '#FFF0C2'];
$tintFg = ['blue' => '#2F6FE0', 'green' => '#1E9E5A', 'orange' => '#D9771A', 'purple' => '#6D3FE0', 'pink' => '#D6336C', 'teal' => '#0F9488', 'yellow' => '#B7860B'];

// EVT-04: upcoming only, soonest first; past events get their own (collapsed) section.
$upcoming = executeQuery($db, "
    SELECT e.*, (r.id IS NOT NULL) AS registered
    FROM events e
    LEFT JOIN event_registrations r ON r.event_id = e.id AND r.student_id = ?
    WHERE e.starts_at >= NOW()
    ORDER BY e.starts_at ASC
", [$studentId])->fetchAll(PDO::FETCH_ASSOC);

$past = executeQuery($db, "
    SELECT e.*, (r.id IS NOT NULL) AS registered
    FROM events e
    LEFT JOIN event_registrations r ON r.event_id = e.id AND r.student_id = ?
    WHERE e.starts_at < NOW()
    ORDER BY e.starts_at DESC
    LIMIT 10
", [$studentId])->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Events - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
        .event-card { border-radius:16px; background:#fff; box-shadow:0 4px 16px rgba(0,0,0,.06); padding:1.75rem; margin-bottom:1rem; }
        body.dark .event-card { background:#1f1f1f; }
        .event-badge { display:inline-flex; align-items:center; gap:.35rem; background:#fee2e2; color:#991b1b; border-radius:20px; padding:.25rem .8rem; font-size:.75rem; font-weight:700; }
        .event-meta span { font-size:.88rem; color:#6b7280; display:inline-flex; align-items:center; gap:.35rem; margin-right:1rem; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4" style="max-width:760px;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-calendar-event-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Events</h2>
                    <p class="text-muted mb-0">Upcoming workshops, webinars, and live sessions</p>
                </div>
            </div>

            <?php if (!$upcoming): ?>
                <div class="stat-card text-center py-5">
                    <i class="bi bi-calendar-plus fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No upcoming events</h5>
                </div>
            <?php else: foreach ($upcoming as $ev): $start = new DateTime($ev['starts_at']); ?>
                <div class="event-card">
                    <?php if ($ev['is_live_badge_label']): ?>
                        <span class="event-badge mb-2"><i class="bi bi-broadcast"></i> <?= e($ev['is_live_badge_label']) ?></span>
                    <?php endif; ?>
                    <h4 class="fw-bold mb-2"><?= e($ev['title']) ?></h4>
                    <div class="event-meta mb-2">
                        <span><i class="bi bi-calendar3"></i> <?= e($start->format('l, j F Y')) ?></span>
                        <span><i class="bi bi-clock"></i> <?= e($start->format('g:i A')) ?></span>
                        <?php if ($ev['host']): ?><span><i class="bi bi-person"></i> <?= e($ev['host']) ?></span><?php endif; ?>
                    </div>
                    <?php if ($ev['description']): ?><p class="text-muted" style="font-size:.93rem;"><?= e($ev['description']) ?></p><?php endif; ?>

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <?php if ($ev['registered']): ?>
                            <span class="badge rounded-pill" style="background:#dcfce7;color:#166534;padding:.5rem 1rem;"><i class="bi bi-check-circle-fill"></i> Going</span>
                            <form method="post" class="d-inline" onsubmit="return confirm('Leave this event? Your reminder is removed too.');">
                                <input type="hidden" name="event_id" value="<?= (int) $ev['id'] ?>">
                                <input type="hidden" name="action" value="leave">
                                <button type="submit" class="btn btn-sm btn-outline-secondary">I can't make it</button>
                            </form>
                        <?php else: ?>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="event_id" value="<?= (int) $ev['id'] ?>">
                                <input type="hidden" name="action" value="join">
                                <button type="submit" class="btn btn-primary">Join this event</button>
                            </form>
                        <?php endif; ?>
                        <?php if ($ev['detail_url']): ?>
                            <a href="<?= e($ev['detail_url']) ?>" class="btn btn-sm btn-outline-primary">Full details</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; endif; ?>

            <?php if ($past): ?>
                <p class="text-muted mt-4 mb-2" style="cursor:pointer;" data-bs-toggle="collapse" href="#pastEvents">
                    <i class="bi bi-chevron-down"></i> Past events
                </p>
                <div class="collapse" id="pastEvents">
                    <?php foreach ($past as $ev): $start = new DateTime($ev['starts_at']); ?>
                        <div class="event-card" style="opacity:.7;">
                            <h5 class="fw-semibold mb-1"><?= e($ev['title']) ?></h5>
                            <div class="event-meta"><span><i class="bi bi-calendar3"></i> <?= e($start->format('j F Y')) ?></span></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
