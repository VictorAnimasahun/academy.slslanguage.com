<?php
// Inbox (founder, 2026-10-27: "I want the same UI" as Canvas's Inbox; "call it Inbox, not Messages"; Notifications fold
// in). Everything for the student in one place -- announcements, course updates, reminders, mentor conversations,
// assignments due and events starting soon (includes/inbox.php) -- with Canvas's toolbar: course and mailbox filters,
// search, reply / reply all / archive / delete / more, a checkbox, read dot and star on each row, and the open item
// beside the list with its course under the sender. Works without JavaScript (filters are GET, actions are POST);
// on a phone the list and the open item take the screen in turn. The bell in the top bar links here.
require_once __DIR__ . '/bootstrap.php';
require_once INCLUDES_PATH . '/inbox.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+your+inbox");
    exit();
}
$sid = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$mailboxes = ['inbox' => 'Inbox', 'unread' => 'Unread', 'starred' => 'Starred', 'sent' => 'Sent', 'archived' => 'Archived'];
$box    = isset($mailboxes[$_GET['box'] ?? '']) ? $_GET['box'] : 'inbox';
$course = (int) ($_GET['course'] ?? 0);
$search = trim((string) ($_GET['q'] ?? ''));
$open   = preg_match('/^[atde]\d+$/', (string) ($_GET['open'] ?? '')) ? $_GET['open'] : '';
$state  = function (array $extra) use ($box, $course, $search, &$open): string {   // &$open: an archived/deleted open item closes
    return 'inbox.php?' . http_build_query(array_filter($extra + ['box' => $box === 'inbox' ? null : $box,
            'course' => $course ?: null, 'q' => $search ?: null, 'open' => $open ?: null], fn ($v) => $v !== null && $v !== ''));
};

// Actions: the toolbar's (on the ticked rows, or the open item when none is ticked), or one row's star / read dot.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['one']) && preg_match('/^(star|unstar|read|unread):([atde]\d+)$/', $_POST['one'], $m)) {
        inbox_apply($db, $sid, $m[1], [$m[2]]);
    } elseif (in_array($_POST['action'] ?? '', ['read', 'unread', 'star', 'unstar', 'archive', 'unarchive', 'delete', 'read_all'], true)) {
        $keys = array_values(array_filter((array) ($_POST['keys'] ?? []), fn ($k) => preg_match('/^[atde]\d+$/', $k)));
        if (!$keys && $open) $keys = [$open];
        inbox_apply($db, $sid, $_POST['action'], $keys);
        if (in_array($_POST['action'], ['archive', 'unarchive', 'delete'], true) && in_array($open, $keys, true)) $open = '';
    }
    header('Location: ' . $state([]));
    exit();
}

// Opening an item marks it read.
if ($open) inbox_apply($db, $sid, 'read', [$open]);

$items   = inbox_items($db, $sid);
$courses = inbox_courses($db, $sid);
$unreadTotal = count(array_filter($items, fn ($i) => $i['unread'] && !$i['archived'] && !$i['deleted']));
$shown = array_values(array_filter($items, function ($i) use ($box, $course, $search) {
    if ($i['deleted']) return false;
    if ($box === 'inbox' && $i['archived']) return false;
    if ($box === 'unread' && (!$i['unread'] || $i['archived'])) return false;
    if ($box === 'starred' && !$i['starred']) return false;
    if ($box === 'sent' && !$i['sent']) return false;
    if ($box === 'archived' && !$i['archived']) return false;
    if ($course && $i['course_id'] !== $course) return false;
    if ($search !== '' && mb_stripos($i['sender'] . ' ' . $i['subject'] . ' ' . $i['body'], $search) === false) return false;
    return true;
}));
$current = null;
foreach ($items as $i) if ($i['key'] === $open && !$i['deleted']) $current = $i;
$log = $current && $current['kind'] === 'conversation'
    ? array_reverse(executeQuery($db, "SELECT * FROM thread_messages WHERE thread_id = ? ORDER BY id ASC", [(int) substr($current['key'], 1)])->fetchAll(PDO::FETCH_ASSOC))
    : [];
$mentors = executeQuery($db, "SELECT id, display_name FROM mentors WHERE is_active = 1 ORDER BY display_name")->fetchAll(PDO::FETCH_ASSOC);
$composeTo = (int) ($_GET['compose'] ?? 0);
$isConversation = $current && $current['kind'] === 'conversation';

$fullDate = fn ($at) => date('M j, Y \a\t g:ia', strtotime($at));
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Inbox - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        /* Use exactly the space between the menu rail and the advert column (shown from 1200px, --advert-w wide), and
           drop .content's own top offset there -- .main-wrapper already clears the top bar (topbar.php), so the two
           together left an empty band above the title. */
        @media (min-width:1200px) { .content { max-width:calc(100vw - var(--sidebar-w) - var(--advert-w)); margin-top:0; } }
        .ib-title { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding-bottom:1rem; border-bottom:1px solid #d6dde6; flex-wrap:wrap; }
        .ib-title h1 { font-size:2.4rem; font-weight:600; margin:0; color:#1f2937; }
        .ib-compose { display:inline-flex; align-items:center; gap:.5rem; background:#0b77ff; color:#fff; border:1px solid #0b77ff; border-radius:3px; padding:.55rem 1.1rem; font-size:1rem; font-weight:500; }
        .ib-compose:hover { background:#0a66db; }
        .ib-bar { display:flex; align-items:center; gap:.75rem; flex-wrap:nowrap; padding:.9rem 1rem; background:#f2f4f7; border-bottom:1px solid #d6dde6; }
        .ib-bar select, .ib-bar input[type=search] { height:40px; border:1px solid #c7cdd5; border-radius:3px; background:#fff; padding:0 .8rem; font-size:.98rem; color:#1f2937; }
        .ib-bar select { flex:0 1 220px; min-width:120px; }
        .ib-search { position:relative; flex:1 1 220px; min-width:120px; }
        .ib-search input { width:100%; padding-left:2.2rem !important; }
        .ib-search i { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#4b5563; }
        .ib-tools { display:flex; gap:.5rem; flex-shrink:0; }
        .ib-tool { width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center; border:1px solid #c7cdd5; border-radius:3px; background:#fff; color:#1f2937; font-size:1.15rem; }
        .ib-tool:hover:not(:disabled) { background:#e8edf3; }
        .ib-tool:disabled { opacity:.45; cursor:not-allowed; }
        .ib-more { position:relative; }
        .ib-more summary { list-style:none; cursor:pointer; }
        .ib-more summary::-webkit-details-marker { display:none; }
        .ib-menu { position:absolute; right:0; top:46px; background:#fff; border:1px solid #c7cdd5; border-radius:3px; box-shadow:0 6px 20px rgba(15,23,42,.12); min-width:190px; z-index:30; padding:.3rem 0; }
        .ib-menu button { display:block; width:100%; text-align:left; background:none; border:0; padding:.5rem 1rem; font-size:.93rem; color:#1f2937; }
        .ib-menu button:hover { background:#f2f4f7; }
        .ib-panes { display:grid; grid-template-columns:minmax(340px, 38%) minmax(0, 1fr); min-height:calc(100vh - 260px); background:#fff; border:1px solid #d6dde6; border-top:0; }
        .ib-list, .ib-read, .ib-row > * { min-width:0; }
        .ib-list { overflow-y:auto; max-height:calc(100vh - 220px); position:sticky; top:calc(var(--topbar-h) + 8px); align-self:start; }
        .ib-read { border-left:1px solid #d6dde6; }   /* the divider runs the full height of a long message */
        .ib-row { display:grid; grid-template-columns:28px minmax(0,1fr) 34px; gap:.2rem .5rem; padding:.9rem .9rem .9rem .8rem; border-bottom:1px solid #d6dde6; position:relative; }
        .ib-row:hover { background:#f7f9fb; }
        .ib-row.active { background:#e5f0fb; }
        .ib-row a.ib-hit { position:absolute; inset:0; z-index:1; }
        .ib-row input[type=checkbox], .ib-row button { position:relative; z-index:2; }
        .ib-row input[type=checkbox] { width:18px; height:18px; margin-top:.15rem; accent-color:#1f2937; }
        .ib-date { color:#0b77ff; font-size:.95rem; }
        .ib-count { justify-self:end; background:#1f2937; color:#fff; border-radius:3px; font-size:.75rem; font-weight:700; min-width:22px; height:22px; display:inline-flex; align-items:center; justify-content:center; padding:0 .35rem; }
        .ib-dotbtn { background:none; border:0; padding:0; width:28px; height:22px; display:inline-flex; align-items:center; }
        .ib-dotbtn span { width:16px; height:16px; border-radius:50%; border:1.5px solid #4b5563; display:block; }
        .ib-dotbtn.on span { background:#0b77ff; border-color:#0b77ff; }
        .ib-who { font-size:.98rem; color:#1f2937; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-subj { grid-column:2; font-size:.95rem; color:#1f2937; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-prev { grid-column:2; font-size:.9rem; color:#6b7280; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-row.unread .ib-who, .ib-row.unread .ib-subj { font-weight:700; }
        .ib-star { grid-column:3; grid-row:3 / span 2; align-self:end; justify-self:end; background:none; border:0; padding:0; font-size:1.2rem; color:#4b5563; }
        .ib-star.on { color:#d97706; }
        .ib-empty { padding:3rem 1.5rem; text-align:center; color:#6b7280; }
        .ib-read { display:flex; flex-direction:column; }
        .ib-read-head { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1.1rem 1.4rem; border-bottom:1px solid #d6dde6; }
        .ib-read-head h2 { font-size:1.55rem; font-weight:600; margin:0; color:#1f2937; }
        .ib-head-tools { display:flex; gap:.4rem; }
        .ib-icon { background:none; border:0; font-size:1.3rem; color:#1f2937; width:36px; height:36px; border-radius:3px; display:inline-flex; align-items:center; justify-content:center; text-decoration:none; }
        .ib-icon:hover { background:#f2f4f7; color:#1f2937; }
        .ib-icon.on, .ib-icon.on:hover { color:#d97706; }
        .ib-back { display:none; }
        .ib-msg { padding:1.1rem 1.4rem; border-bottom:1px solid #eef1f4; }
        .ib-msg:last-child { border-bottom:0; }
        .ib-from { display:flex; gap:1rem; align-items:flex-start; margin-bottom:.8rem; }
        .ib-avatar { width:58px; height:58px; border-radius:50%; border:2px solid #9aa4b1; color:#0b77ff; display:flex; align-items:center; justify-content:center; font-size:1.3rem; font-weight:500; flex-shrink:0; background:#fff; }
        .ib-from .n { font-size:1.1rem; font-weight:600; color:#1f2937; }
        .ib-from .m { font-size:.92rem; color:#374151; margin-top:.15rem; }
        .ib-body { font-size:1.05rem; line-height:1.7; color:#1f2937; overflow-wrap:anywhere; }
        .ib-body ul { padding-left:1.3rem; }
        .ib-link { display:inline-flex; align-items:center; gap:.4rem; margin-top:.6rem; background:#0b77ff; color:#fff; padding:.55rem 1.1rem; border-radius:3px; text-decoration:none; font-weight:600; }
        .ib-link:hover { background:#0a66db; color:#fff; }
        .ib-reply { padding:1rem 1.4rem; border-bottom:1px solid #d6dde6; background:#f7f9fb; }
        .ib-reply textarea { width:100%; border:1px solid #c7cdd5; border-radius:3px; padding:.6rem .8rem; font:inherit; resize:vertical; }
        .ib-none { margin:auto; text-align:center; color:#6b7280; padding:3rem 1rem; }
        .ib-none i { font-size:3rem; color:#c7cdd5; display:block; margin-bottom:.4rem; }
        dialog.ib-dialog { border:1px solid #c7cdd5; border-radius:3px; padding:0; width:min(560px, 94vw); }
        dialog.ib-dialog::backdrop { background:rgba(15,23,42,.45); }
        .ib-dialog header { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.2rem; border-bottom:1px solid #d6dde6; }
        .ib-dialog header h3 { font-size:1.2rem; margin:0; }
        .ib-dialog .b { padding:1rem 1.2rem; display:grid; gap:.8rem; }
        .ib-dialog label { font-weight:600; font-size:.9rem; }
        .ib-dialog select, .ib-dialog textarea { width:100%; border:1px solid #c7cdd5; border-radius:3px; padding:.5rem .7rem; font:inherit; }
        .ib-dialog footer { display:flex; justify-content:flex-end; gap:.6rem; padding:.9rem 1.2rem; border-top:1px solid #d6dde6; }
        body.dark .ib-title h1, body.dark .ib-read-head h2, body.dark .ib-from .n, body.dark .ib-who, body.dark .ib-subj, body.dark .ib-body, body.dark .ib-icon { color:#e5e7eb; }
        body.dark .ib-panes, body.dark .ib-bar select, body.dark .ib-bar input, body.dark .ib-tool, body.dark .ib-menu, body.dark .ib-avatar, body.dark dialog.ib-dialog { background:#1e1e1e; color:#e5e7eb; border-color:#374151; }
        body.dark .ib-bar, body.dark .ib-reply { background:#181818; border-color:#374151; }
        body.dark .ib-row, body.dark .ib-list, body.dark .ib-read-head, body.dark .ib-msg { border-color:#2b2b2b; }
        body.dark .ib-row:hover { background:#252525; } body.dark .ib-row.active { background:#1e3a5f; }
        body.dark .ib-menu button { color:#e5e7eb; } body.dark .ib-menu button:hover { background:#2a2a2a; }
        @media (max-width: 900px) {
            .ib-title h1 { font-size:1.9rem; }
            .ib-bar { flex-wrap:wrap; }
            .ib-bar select { width:auto; min-width:0; max-width:100%; flex:1 1 100%; }
            .ib-search { max-width:none; flex:1 1 100%; }
            .ib-tools { margin-left:0; }
            .ib-panes { grid-template-columns:minmax(0, 1fr); }
            .ib-list { max-height:none; position:static; }
            .ib-read { border-left:0; }
            .ib-panes.has-open .ib-list { display:none; }
            .ib-panes:not(.has-open) .ib-read { display:none; }
            .ib-back { display:inline-flex; }
            .ib-read-head { padding:.9rem 1rem; } .ib-read-head h2 { font-size:1.25rem; }
            .ib-msg, .ib-reply { padding-left:1rem; padding-right:1rem; }
            .ib-avatar { width:46px; height:46px; font-size:1.05rem; }
        }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div class="ib-title">
                <h1>Inbox</h1>
                <button type="button" class="ib-compose" id="ibComposeBtn"><i class="bi bi-pencil-square"></i> Compose</button>
            </div>

            <!-- Toolbar actions post this form: the ticked rows (keys[]), else the open item. -->
            <form method="post" id="ibAct" action="<?= e($state([])) ?>"></form>

            <form method="get" class="ib-bar" role="search">
                <select name="course" aria-label="Course" onchange="this.form.submit()">
                    <option value="">All Courses</option>
                    <?php foreach ($courses as $cid => $title): ?>
                        <option value="<?= $cid ?>" <?= $course === $cid ? 'selected' : '' ?>><?= e($title) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="box" aria-label="Mailbox" onchange="this.form.submit()">
                    <?php foreach ($mailboxes as $v => $l): ?>
                        <option value="<?= $v ?>" <?= $box === $v ? 'selected' : '' ?>><?= $l ?><?= $v === 'unread' && $unreadTotal ? " ($unreadTotal)" : '' ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="ib-search"><i class="bi bi-search"></i><input type="search" name="q" value="<?= e($search) ?>" placeholder="Search..." aria-label="Search the inbox"></div>
                <div class="ib-tools">
                    <button type="button" class="ib-tool" data-reply title="Reply" aria-label="Reply" <?= $isConversation ? '' : 'disabled' ?>><i class="bi bi-reply"></i></button>
                    <button type="button" class="ib-tool" data-reply title="Reply all" aria-label="Reply all" <?= $isConversation ? '' : 'disabled' ?>><i class="bi bi-reply-all"></i></button>
                    <?php if ($box === 'archived'): ?>
                        <button type="submit" form="ibAct" name="action" value="unarchive" class="ib-tool" data-needs title="Move to Inbox" aria-label="Move to Inbox"><i class="bi bi-inbox"></i></button>
                    <?php else: ?>
                        <button type="submit" form="ibAct" name="action" value="archive" class="ib-tool" data-needs title="Archive" aria-label="Archive"><i class="bi bi-archive"></i></button>
                    <?php endif; ?>
                    <button type="submit" form="ibAct" name="action" value="delete" class="ib-tool" data-needs title="Delete" aria-label="Delete"
                            onclick="return confirm('Delete the selected item(s)? They are removed from your inbox.');"><i class="bi bi-trash3"></i></button>
                    <details class="ib-more">
                        <summary class="ib-tool" title="More" aria-label="More"><i class="bi bi-three-dots-vertical"></i></summary>
                        <div class="ib-menu">
                            <button type="submit" form="ibAct" name="action" value="read">Mark as read</button>
                            <button type="submit" form="ibAct" name="action" value="unread">Mark as unread</button>
                            <button type="submit" form="ibAct" name="action" value="star">Star</button>
                            <button type="submit" form="ibAct" name="action" value="unstar">Unstar</button>
                            <button type="submit" form="ibAct" name="action" value="read_all">Mark all as read</button>
                        </div>
                    </details>
                </div>
            </form>

            <div class="ib-panes <?= $current ? 'has-open' : '' ?>">
                <div class="ib-list">
                    <?php if (!$shown): ?>
                        <div class="ib-empty"><?= $search !== '' || $course ? 'Nothing matches.' : ($box === 'inbox' ? 'Your inbox is empty. Announcements, reminders, conversations with mentors, assignments due and events starting soon all arrive here.' : 'Nothing in ' . e($mailboxes[$box]) . '.') ?></div>
                    <?php endif; ?>
                    <?php foreach ($shown as $i): ?>
                        <div class="ib-row <?= $i['unread'] ? 'unread' : '' ?> <?= $current && $current['key'] === $i['key'] ? 'active' : '' ?>">
                            <a class="ib-hit" href="<?= e($state(['open' => $i['key']])) ?>" aria-label="Open <?= e($i['subject']) ?>"></a>
                            <input type="checkbox" form="ibAct" name="keys[]" value="<?= e($i['key']) ?>" aria-label="Select <?= e($i['subject']) ?>">
                            <span class="ib-date"><?= e(date('M j, Y', strtotime($i['at']))) ?></span>
                            <?php if ($i['count'] > 0): ?><span class="ib-count"><?= $i['count'] ?></span><?php else: ?><span></span><?php endif; ?>
                            <button type="submit" form="ibAct" name="one" value="<?= ($i['unread'] ? 'read:' : 'unread:') . e($i['key']) ?>" class="ib-dotbtn <?= $i['unread'] ? 'on' : '' ?>"
                                    title="<?= $i['unread'] ? 'Mark as read' : 'Mark as unread' ?>" aria-label="<?= $i['unread'] ? 'Unread. Mark as read' : 'Read. Mark as unread' ?>"><span></span></button>
                            <span class="ib-who"><?= e($i['sender']) ?></span>
                            <span></span>
                            <span class="ib-subj"><?= e($i['subject']) ?></span>
                            <span class="ib-prev"><?= e(inbox_preview($i['body'])) ?></span>
                            <button type="submit" form="ibAct" name="one" value="<?= ($i['starred'] ? 'unstar:' : 'star:') . e($i['key']) ?>" class="ib-star <?= $i['starred'] ? 'on' : '' ?>"
                                    title="<?= $i['starred'] ? 'Unstar' : 'Star' ?>" aria-label="<?= $i['starred'] ? 'Starred. Unstar' : 'Star' ?>"><i class="bi bi-star<?= $i['starred'] ? '-fill' : '' ?>"></i></button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <section class="ib-read">
                    <?php if (!$current): ?>
                        <div class="ib-none"><i class="bi bi-envelope-open"></i>No conversation selected</div>
                    <?php else: ?>
                        <div class="ib-read-head">
                            <div>
                                <a class="ib-icon ib-back" href="<?= e($state(['open' => ''])) ?>" aria-label="Back to the inbox"><i class="bi bi-arrow-left"></i></a>
                                <h2><?= e($current['subject']) ?></h2>
                            </div>
                            <div class="ib-head-tools">
                                <?php if ($isConversation): ?><button type="button" class="ib-icon" data-reply title="Reply" aria-label="Reply"><i class="bi bi-reply"></i></button><?php endif; ?>
                                <button type="submit" form="ibAct" name="one" value="<?= ($current['starred'] ? 'unstar:' : 'star:') . e($current['key']) ?>" class="ib-icon <?= $current['starred'] ? 'on' : '' ?>" title="<?= $current['starred'] ? 'Unstar' : 'Star' ?>" aria-label="<?= $current['starred'] ? 'Unstar' : 'Star' ?>"><i class="bi bi-star<?= $current['starred'] ? '-fill' : '' ?>"></i></button>
                            </div>
                        </div>

                        <?php if ($isConversation): ?>
                            <form method="post" action="thread_view.php?id=<?= (int) substr($current['key'], 1) ?>" class="ib-reply" id="ibReply" hidden>
                                <input type="hidden" name="back" value="inbox">
                                <textarea name="body" rows="3" placeholder="Reply to <?= e($current['sender']) ?>" required></textarea>
                                <div class="d-flex justify-content-end gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-secondary" data-cancel-reply>Cancel</button>
                                    <button type="submit" class="btn btn-primary" style="background:#0b77ff;border-color:#0b77ff;">Send</button>
                                </div>
                            </form>
                            <noscript><style>#ibReply { display:block !important; }</style></noscript>
                            <?php $meName = trim((string) ($_SESSION['user_firstname'] ?? ''));
                            foreach ($log as $m): $me = (int) $m['sender_id'] === $sid; $who = $me ? ($meName !== '' ? "$meName (you)" : 'You') : $current['sender']; ?>
                                <div class="ib-msg">
                                    <div class="ib-from">
                                        <div class="ib-avatar"><?= e(inbox_initials($me ? ($meName !== '' ? $meName : 'You') : $current['sender'])) ?></div>
                                        <div>
                                            <div class="n"><?= e($who) ?></div>
                                            <div class="m"><?= $me ? 'To ' . e($current['sender']) : 'Mentor' ?></div>
                                            <div class="m"><?= e($fullDate($m['created_at'])) ?></div>
                                        </div>
                                    </div>
                                    <div class="ib-body"><?= nl2br(e($m['body'])) ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="ib-msg">
                                <div class="ib-from">
                                    <div class="ib-avatar"><?= $current['kind'] === 'announcement' ? 'SLS' : e(inbox_initials($current['sender'])) ?></div>
                                    <div>
                                        <div class="n"><?= e($current['sender']) ?></div>
                                        <?php if ($current['course']): ?><div class="m"><?= e($current['course']) ?></div><?php endif; ?>
                                        <div class="m"><?= e($fullDate($current['at'])) ?></div>
                                    </div>
                                </div>
                                <div class="ib-body"><?= inbox_markdown($current['body']) ?></div>
                                <?php if ($current['link_url']): ?>
                                    <a class="ib-link" href="<?= e($current['link_url']) ?>"><?= e($current['link_text']) ?> <i class="bi bi-arrow-right"></i></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
</div>

<dialog class="ib-dialog" id="ibCompose">
    <form method="post" action="thread_view.php">
        <input type="hidden" name="back" value="inbox">
        <header><h3>Compose Message</h3><button type="button" class="ib-icon" data-close aria-label="Close"><i class="bi bi-x-lg"></i></button></header>
        <div class="b">
            <div>
                <label for="ibTo">To</label>
                <select name="mentor" id="ibTo" required>
                    <option value="">Choose a mentor</option>
                    <?php foreach ($mentors as $mt): ?><option value="<?= (int) $mt['id'] ?>" <?= $composeTo === (int) $mt['id'] ? 'selected' : '' ?>><?= e($mt['display_name']) ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="ibBody">Message</label>
                <textarea name="body" id="ibBody" rows="6" required></textarea>
            </div>
        </div>
        <footer>
            <button type="button" class="btn btn-outline-secondary" data-close>Cancel</button>
            <button type="submit" class="btn btn-primary" style="background:#0b77ff;border-color:#0b77ff;">Send</button>
        </footer>
    </form>
</dialog>

<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
(function () {
    // Reply / Cancel: delegated, so they keep working after the reading pane is swapped below.
    document.addEventListener('click', e => {
        const reply = document.getElementById('ibReply');
        if (e.target.closest('[data-reply]') && reply) { reply.hidden = false; reply.querySelector('textarea').focus(); }
        if (e.target.closest('[data-cancel-reply]') && reply) reply.hidden = true;
    });

    // Nothing in the Inbox reloads the page (founder, 2026-10-28): opening a message, going back to the list, and every
    // action -- star, read dot, archive, delete, the More menu, a reply, a new message -- happen in the background,
    // and only what changes is swapped in (reading pane, list, toolbar, mailbox counts, bell). Links and forms stay
    // real links and forms: without JavaScript, on cmd/ctrl-click, or if anything unexpected happens, they work as
    // an ordinary page load.
    const panes = document.querySelector('.ib-panes');

    /** Put the inbox page in `res` (already fetched) on screen. False if it isn't an inbox page. */
    async function swapIn(res) {
        if (!res.ok || !new URL(res.url).pathname.endsWith('/inbox.php')) return false;
        const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
        if (!doc.querySelector('.ib-panes')) return false;
        const top = document.querySelector('.ib-list').scrollTop;
        for (const sel of ['.ib-read', '.ib-list', '.ib-tools', '.ib-bar select[name=box]']) {
            const next = doc.querySelector(sel), cur = document.querySelector(sel);
            if (next && cur) cur.replaceWith(document.importNode(next, true));
        }
        document.querySelector('.ib-list').scrollTop = top;
        document.getElementById('ibAct').setAttribute('action', doc.getElementById('ibAct').getAttribute('action'));
        panes.className = doc.querySelector('.ib-panes').className;
        // The bell keeps its element (its refresh timer holds it); take the new count, and the menu badge with it.
        const bell = document.getElementById('bellBtn'), nextBell = doc.getElementById('bellBtn');
        if (bell && nextBell) {
            bell.className = nextBell.className; bell.innerHTML = nextBell.innerHTML;
            bell.setAttribute('aria-label', nextBell.getAttribute('aria-label')); bell.title = nextBell.title;
            const n = parseInt(bell.querySelector('.bell-count')?.textContent, 10) || 0, badge = document.querySelector('.navbar-badge-unread');
            if (badge) { badge.textContent = n; badge.style.display = n > 0 ? 'inline-block' : 'none'; }
        }
        return true;
    }

    async function show(url, push) {
        panes.style.cursor = 'progress';
        try {
            if (!await swapIn(await fetch(url, { credentials: 'same-origin' }))) throw new Error('not the inbox');
            const log = document.getElementById('ibLog'); if (log) log.scrollTop = 0;
            if (push) history.pushState(null, '', url);
            if (window.innerWidth <= 900) window.scrollTo(0, 0);
        } catch (err) {
            location.href = url;   // anything unexpected: an ordinary page load
        } finally {
            panes.style.cursor = '';
        }
    }
    panes.addEventListener('click', e => {
        const a = e.target.closest('a.ib-hit, a.ib-back');
        if (!a || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
        e.preventDefault();
        show(a.href, true);
    });
    window.addEventListener('popstate', () => show(location.href, false));

    // Actions (#ibAct), replies (#ibReply) and new messages (Compose): posted in the background. The server answers
    // with the inbox as it now is (actions may close the open message, a send opens the conversation), and the
    // address follows it.
    document.addEventListener('submit', async e => {
        const form = e.target;
        if (!(form.id === 'ibAct' || form.id === 'ibReply' || form.closest('#ibCompose')) || form.dataset.native) return;
        e.preventDefault();
        const submitter = e.submitter;
        const body = new FormData(form, submitter);   // before disabling the button, or its name=value is left out
        panes.style.cursor = 'progress';
        if (submitter) submitter.disabled = true;
        try {
            // getAttribute, not form.action: the toolbar's buttons are named "action", and form.action returns them.
            const res = await fetch(new URL(form.getAttribute('action') || location.href, location.href), { method: 'POST', body, credentials: 'same-origin' });
            if (!await swapIn(res)) throw new Error('not the inbox');
            history.replaceState(null, '', res.url);
            document.getElementById('ibCompose').open && document.getElementById('ibCompose').close();
            if (form.closest('#ibCompose')) form.reset();
            if (submitter && submitter.isConnected) submitter.disabled = false;
        } catch (err) {
            form.dataset.native = '1';   // anything unexpected: send it the ordinary way
            if (submitter) submitter.disabled = false;
            form.requestSubmit(submitter);
        } finally {
            panes.style.cursor = '';
        }
    });

    const dlg = document.getElementById('ibCompose');
    document.getElementById('ibComposeBtn').addEventListener('click', () => dlg.showModal());
    dlg.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => dlg.close()));
    <?php if (isset($_GET['compose'])): ?>dlg.showModal();<?php endif; ?>

    // Close the More menu when clicking elsewhere.
    document.addEventListener('click', e => document.querySelectorAll('.ib-more[open]').forEach(d => { if (!d.contains(e.target)) d.removeAttribute('open'); }));
})();
</script>
</body>
</html>
