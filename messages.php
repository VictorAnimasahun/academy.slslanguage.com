<?php
// Inbox (founder, 2026-10-27: "This is a good design for messages" -- a Canvas-style inbox). One list of everything
// sent to the student -- announcements, course updates, reminders (broadcast_messages) and conversations with mentors
// (threads) -- newest first, with the open one read in the pane beside it. Filter and search are plain GET parameters,
// so the page works without JavaScript; ?open=a<id> opens an announcement (and marks it read), ?open=t<id> a
// conversation (marks it read; replies post to thread_view.php, which sends them back here). On a phone the list and
// the open message take the screen in turn. The bell in the top bar links here.
require_once __DIR__ . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+messages");
    exit();
}
$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: edu_hub_registration.php");
    exit();
}

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$filter = in_array($_GET['f'] ?? '', ['unread', 'announcements', 'conversations'], true) ? $_GET['f'] : 'all';
$search = trim((string) ($_GET['q'] ?? ''));
$open   = (string) ($_GET['open'] ?? '');

$typeLabels = ['announcement' => 'Announcement', 'course_update' => 'Course update', 'new_material' => 'New material', 'targeted' => 'Personal'];

// Opening an announcement marks it read before the list is built, so the list shows it read.
if (preg_match('/^a(\d+)$/', $open, $mm)) {
    executeQuery($db, "INSERT INTO broadcast_message_reads (message_id, student_id)
                       SELECT ?, ? FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM broadcast_message_reads WHERE message_id = ? AND student_id = ?)",
                 [(int) $mm[1], $user_id, (int) $mm[1], $user_id]);
}
if (preg_match('/^t(\d+)$/', $open, $mm)) {
    executeQuery($db, "UPDATE thread_participants SET last_read_message_id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = ?)
                        WHERE thread_id = ? AND student_id = ?", [(int) $mm[1], (int) $mm[1], $user_id]);
}

$announcements = executeQuery($db, "
    SELECT m.id, m.title, m.content, m.message_type, m.link_url, m.link_text, m.created_at,
           EXISTS (SELECT 1 FROM broadcast_message_reads r WHERE r.message_id = m.id AND r.student_id = ?) AS is_read
    FROM broadcast_messages m
    WHERE m.target_all_students = 1
       OR FIND_IN_SET(?, m.target_student_ids) > 0
       OR EXISTS (SELECT 1 FROM enrollments e WHERE e.student_id = ? AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0)
    ORDER BY m.created_at DESC
", [$user_id, $user_id, $user_id])->fetchAll(PDO::FETCH_ASSOC);

$threads = executeQuery($db, "
    SELECT t.id, m.display_name AS mentor_name, lm.body AS last_body, lm.created_at AS last_at,
           (SELECT COUNT(*) FROM thread_messages tm2
             WHERE tm2.thread_id = t.id AND tm2.sender_id != ? AND tm2.id > COALESCE(tp.last_read_message_id, 0)) AS unread_count
    FROM threads t
    JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
    LEFT JOIN mentors m ON m.id = t.mentor_id
    JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
", [$user_id, $user_id])->fetchAll(PDO::FETCH_ASSOC);

/** Markdown-ish text as one plain line, for the list preview. */
function inbox_preview(string $s): string {
    $s = preg_replace('/[*_#>`]+|\[([^\]]*)\]\([^)]*\)/', '$1', strip_tags($s));
    return mb_substr(trim(preg_replace('/\s+/', ' ', $s)), 0, 110);
}
/** The Markdown messages use (paragraphs, **bold**, - lists, [links](url), # headings) as safe HTML, so a message reads
 *  properly before -- or without -- marked.js, which then renders the full syntax over it. */
function inbox_markdown(string $md): string {
    $inline = function (string $t): string {
        $t = htmlspecialchars($t, ENT_QUOTES, 'UTF-8');
        $t = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $t);
        return preg_replace_callback('/\[([^\]]+)\]\((https?:\/\/[^)\s]+)\)/', fn ($m) => '<a href="' . $m[2] . '">' . $m[1] . '</a>', $t);
    };
    $out = '';
    foreach (preg_split('/\n\s*\n/', str_replace("\r", '', trim($md))) as $block) {
        $lines = explode("\n", trim($block));
        if (preg_match('/^(#{1,6})\s+(.*)$/', $lines[0], $h) && count($lines) === 1) {
            $out .= '<h3 style="font-size:1.05rem;font-weight:700;margin:1rem 0 .4rem;">' . $inline($h[2]) . '</h3>';
        } elseif (count(array_filter($lines, fn ($l) => preg_match('/^\s*[-*]\s+/', $l))) === count($lines)) {
            $out .= '<ul>' . implode('', array_map(fn ($l) => '<li>' . $inline(preg_replace('/^\s*[-*]\s+/', '', $l)) . '</li>', $lines)) . '</ul>';
        } else {
            $out .= '<p>' . implode('<br>', array_map($inline, $lines)) . '</p>';
        }
    }
    return $out;
}
function inbox_initials(string $name): string {
    $parts = preg_split('/\s+/', trim($name)) ?: ['?'];
    return mb_strtoupper(mb_substr($parts[0], 0, 1) . (isset($parts[1]) ? mb_substr($parts[1], 0, 1) : ''));
}

$items = [];
foreach ($announcements as $a) {
    $items[] = ['key' => 'a' . $a['id'], 'kind' => 'announcement', 'sender' => 'SLS Academy', 'subject' => $a['title'],
                'label' => $typeLabels[$a['message_type']] ?? 'Announcement', 'preview' => inbox_preview($a['content']),
                'at' => $a['created_at'], 'unread' => !$a['is_read'], 'row' => $a];
}
foreach ($threads as $t) {
    $name = $t['mentor_name'] ?: 'Conversation';
    $items[] = ['key' => 't' . $t['id'], 'kind' => 'conversation', 'sender' => $name, 'subject' => 'Conversation with ' . $name,
                'label' => 'Conversation', 'preview' => inbox_preview($t['last_body']),
                'at' => $t['last_at'], 'unread' => $t['unread_count'] > 0, 'row' => $t];
}
usort($items, fn ($x, $y) => strcmp($y['at'], $x['at']));
$unreadTotal = count(array_filter($items, fn ($i) => $i['unread']));

$shown = array_values(array_filter($items, function ($i) use ($filter, $search) {
    if ($filter === 'unread' && !$i['unread']) return false;
    if ($filter === 'announcements' && $i['kind'] !== 'announcement') return false;
    if ($filter === 'conversations' && $i['kind'] !== 'conversation') return false;
    if ($search !== '' && mb_stripos($i['sender'] . ' ' . $i['subject'] . ' ' . $i['preview'], $search) === false) return false;
    return true;
}));

$current = null;
foreach ($items as $i) if ($i['key'] === $open) $current = $i;
$threadLog = $current && $current['kind'] === 'conversation'
    ? executeQuery($db, "SELECT * FROM thread_messages WHERE thread_id = ? ORDER BY id ASC", [(int) $current['row']['id']])->fetchAll(PDO::FETCH_ASSOC)
    : [];

$listDate = fn ($at) => date('Y-m-d', strtotime($at)) === date('Y-m-d') ? date('g:ia', strtotime($at)) : date('M j, Y', strtotime($at));
$fullDate = fn ($at) => date('M j, Y \a\t g:ia', strtotime($at));
$keep = fn (array $extra) => '?' . http_build_query(array_filter(['f' => $filter === 'all' ? null : $filter, 'q' => $search ?: null] + $extra));

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
    <script src="https://cdn.jsdelivr.net/npm/marked@12.0.2/marked.min.js"></script>
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}   /* clear of the advert column, as threads.php */
        .ib-head { display:flex; align-items:center; justify-content:space-between; gap:1rem; margin-bottom:1rem; flex-wrap:wrap; }
        .ib-head h1 { font-size:1.9rem; font-weight:700; margin:0; }
        .ib-head .ib-count { font-size:.9rem; color:#64748b; font-weight:500; margin-left:.6rem; }
        .ib-shell { background:#fff; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; }
        .ib-toolbar { display:flex; gap:.6rem; flex-wrap:wrap; padding:.8rem 1rem; background:#f8fafc; border-bottom:1px solid #e2e8f0; }
        .ib-toolbar select, .ib-toolbar input { border:1px solid #cbd5e1; border-radius:6px; padding:.45rem .7rem; font-size:.92rem; background:#fff; color:#0f172a; }
        .ib-toolbar select { min-width:210px; }
        .ib-toolbar .ib-search { flex:1; min-width:200px; position:relative; }
        .ib-toolbar .ib-search input { width:100%; padding-left:2rem; }
        .ib-toolbar .ib-search i { position:absolute; left:.65rem; top:50%; transform:translateY(-50%); color:#64748b; }
        .ib-panes { display:grid; grid-template-columns:minmax(280px, 36%) minmax(0, 1fr); min-height:calc(100vh - 250px); }
        .ib-list, .ib-read, .ib-row > * { min-width:0; }
        .ib-list { border-right:1px solid #e2e8f0; overflow-y:auto; max-height:calc(100vh - 250px); }
        .ib-row { display:block; padding:.85rem 1rem; border-bottom:1px solid #eef2f7; color:inherit; text-decoration:none; }
        .ib-row:hover { background:#f8fafc; }
        .ib-row.active { background:#e8f1ff; }
        .ib-row-top { display:flex; justify-content:space-between; align-items:center; gap:.5rem; margin-bottom:.2rem; }
        .ib-date { font-size:.8rem; color:#0b77ff; white-space:nowrap; }
        .ib-sender { font-size:.92rem; color:#0f172a; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-subject { font-size:.9rem; color:#334155; margin-bottom:.15rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-preview { font-size:.83rem; color:#64748b; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .ib-row.unread .ib-sender, .ib-row.unread .ib-subject { font-weight:700; color:#0f172a; }
        .ib-dot { width:9px; height:9px; border-radius:50%; background:#0b77ff; display:inline-block; margin-right:.4rem; flex-shrink:0; }
        .ib-tag { font-size:.68rem; font-weight:700; letter-spacing:.03em; text-transform:uppercase; color:#475569; border:1px solid #cbd5e1; border-radius:4px; padding:0 .35rem; margin-left:.4rem; }
        .ib-empty { padding:3rem 1.5rem; text-align:center; color:#64748b; }
        .ib-read { display:flex; flex-direction:column; min-width:0; }
        .ib-read-head { display:flex; align-items:center; justify-content:space-between; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid #e2e8f0; }
        .ib-read-head h2 { font-size:1.35rem; font-weight:700; margin:0; }
        .ib-back { display:none; }
        .ib-from { display:flex; gap:.9rem; align-items:center; padding:1.1rem 1.5rem .4rem; }
        .ib-avatar { width:46px; height:46px; border-radius:50%; border:2px solid #cbd5e1; color:#0b77ff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:.95rem; flex-shrink:0; }
        .ib-from .n { font-weight:700; color:#0f172a; }
        .ib-from .m { font-size:.85rem; color:#475569; }
        .ib-body { padding:.6rem 1.5rem 1.5rem; font-size:1rem; line-height:1.65; color:#1f2937; overflow-wrap:anywhere; }
        .ib-body ul { padding-left:1.3rem; }
        .ib-link { display:inline-flex; align-items:center; gap:.4rem; margin:0 1.5rem 1.5rem; background:#0b77ff; color:#fff; padding:.55rem 1.1rem; border-radius:6px; text-decoration:none; font-weight:600; align-self:flex-start; }
        .ib-link:hover { background:#0a66db; color:#fff; }
        .ib-log { flex:1; overflow-y:auto; padding:1rem 1.5rem; display:flex; flex-direction:column; gap:.5rem; max-height:calc(100vh - 420px); }
        .ib-bubble { max-width:75%; padding:.6rem .9rem; border-radius:12px; font-size:.95rem; }
        .ib-bubble.mine { align-self:flex-end; background:#0b77ff; color:#fff; }
        .ib-bubble.theirs { align-self:flex-start; background:#f1f5f9; }
        .ib-bubble .t { display:block; font-size:.72rem; opacity:.75; margin-top:.2rem; }
        .ib-reply { display:flex; gap:.6rem; padding:1rem 1.5rem; border-top:1px solid #e2e8f0; }
        .ib-none { margin:auto; text-align:center; color:#64748b; padding:3rem 1rem; }
        .ib-none i { font-size:2.4rem; color:#cbd5e1; display:block; margin-bottom:.5rem; }
        body.dark .ib-shell, body.dark .ib-toolbar select, body.dark .ib-toolbar input { background:#1e1e1e; color:#e2e8f0; border-color:#334155; }
        body.dark .ib-toolbar { background:#181818; border-color:#334155; }
        body.dark .ib-list, body.dark .ib-row, body.dark .ib-read-head, body.dark .ib-reply { border-color:#2b2b2b; }
        body.dark .ib-row:hover { background:#252525; }
        body.dark .ib-row.active { background:#1e3a5f; }
        body.dark .ib-sender, body.dark .ib-row.unread .ib-sender, body.dark .ib-row.unread .ib-subject, body.dark .ib-from .n, body.dark .ib-read-head h2 { color:#f1f5f9; }
        body.dark .ib-subject, body.dark .ib-body { color:#cbd5e1; }
        body.dark .ib-bubble.theirs { background:#2a2a2a; }
        @media (max-width: 900px) {
            .ib-panes { grid-template-columns:minmax(0, 1fr); }
            .ib-toolbar select { min-width:0; flex:1 1 100%; }
            .ib-list { border-right:none; max-height:none; }
            .ib-panes.has-open .ib-list { display:none; }
            .ib-panes:not(.has-open) .ib-read { display:none; }
            .ib-back { display:inline-flex; align-items:center; gap:.3rem; text-decoration:none; font-size:.9rem; margin-bottom:.3rem; }
            .ib-read-head { flex-direction:column; align-items:flex-start; padding:1rem; }
            .ib-from, .ib-body, .ib-log, .ib-reply { padding-left:1rem; padding-right:1rem; }
            .ib-link { margin-left:1rem; margin-right:1rem; }
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
            <div class="ib-head">
                <h1>Inbox<?php if ($unreadTotal): ?><span class="ib-count"><?= $unreadTotal ?> unread</span><?php endif; ?></h1>
                <a href="threads.php" class="btn btn-primary" style="background:#0b77ff;border-color:#0b77ff;"><i class="bi bi-pencil-square me-1"></i>New message</a>
            </div>

            <div class="ib-shell">
                <form method="get" class="ib-toolbar" role="search">
                    <select name="f" aria-label="Show" onchange="this.form.submit()">
                        <?php foreach (['all' => 'All messages', 'unread' => 'Unread', 'announcements' => 'Announcements & reminders', 'conversations' => 'Conversations'] as $v => $l): ?>
                            <option value="<?= $v ?>" <?= $filter === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="ib-search"><i class="bi bi-search"></i><input type="search" name="q" value="<?= e($search) ?>" placeholder="Search..." aria-label="Search messages"></div>
                    <?php if ($open !== ''): ?><input type="hidden" name="open" value="<?= e($open) ?>"><?php endif; ?>
                </form>

                <div class="ib-panes <?= $current ? 'has-open' : '' ?>">
                    <div class="ib-list">
                        <?php if (!$shown): ?>
                            <div class="ib-empty">
                                <?= $items ? 'Nothing matches.' : 'No messages yet. Announcements, reminders and your conversations with mentors will appear here.' ?>
                            </div>
                        <?php endif; ?>
                        <?php foreach ($shown as $i): ?>
                            <a class="ib-row <?= $i['unread'] ? 'unread' : '' ?> <?= $current && $current['key'] === $i['key'] ? 'active' : '' ?>" href="<?= e($keep(['open' => $i['key']])) ?>">
                                <div class="ib-row-top">
                                    <span class="ib-date"><?= e($listDate($i['at'])) ?></span>
                                    <span class="ib-tag"><?= e($i['label']) ?></span>
                                </div>
                                <div class="ib-sender d-flex align-items-center"><?php if ($i['unread']): ?><span class="ib-dot" aria-label="Unread"></span><?php endif; ?><?= e($i['sender']) ?></div>
                                <?php if ($i['kind'] === 'announcement'): ?><div class="ib-subject"><?= e($i['subject']) ?></div><?php endif; ?>
                                <div class="ib-preview"><?= e($i['preview']) ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <section class="ib-read">
                        <?php if (!$current): ?>
                            <div class="ib-none"><i class="bi bi-envelope-open"></i>No message selected</div>
                        <?php else: ?>
                            <div class="ib-read-head">
                                <div>
                                    <a class="ib-back" href="<?= e($keep([])) ?>"><i class="bi bi-arrow-left"></i> Inbox</a>
                                    <h2><?= e($current['subject']) ?></h2>
                                </div>
                            </div>
                            <?php if ($current['kind'] === 'announcement'): $r = $current['row']; ?>
                                <div class="ib-from">
                                    <div class="ib-avatar">SLS</div>
                                    <div>
                                        <div class="n">SLS Academy</div>
                                        <div class="m"><?= e($current['label']) ?></div>
                                        <div class="m"><?= e($fullDate($r['created_at'])) ?></div>
                                    </div>
                                </div>
                                <div class="ib-body" id="ibBody"><?= inbox_markdown($r['content']) ?></div>
                                <script>
                                    if (window.marked) document.getElementById('ibBody').innerHTML = marked.parse(<?= json_encode($r['content']) ?>);
                                </script>
                                <?php if ($r['link_url']): ?>
                                    <a class="ib-link" href="<?= e($r['link_url']) ?>"><?= e($r['link_text'] ?: 'Open') ?> <i class="bi bi-arrow-right"></i></a>
                                <?php endif; ?>
                            <?php else: $t = $current['row']; ?>
                                <div class="ib-from">
                                    <div class="ib-avatar"><?= e(inbox_initials($current['sender'])) ?></div>
                                    <div>
                                        <div class="n"><?= e($current['sender']) ?></div>
                                        <div class="m">Mentor</div>
                                        <div class="m">Last message <?= e($fullDate($t['last_at'])) ?></div>
                                    </div>
                                </div>
                                <div class="ib-log" id="ibLog">
                                    <?php foreach ($threadLog as $m): ?>
                                        <div class="ib-bubble <?= $m['sender_id'] == $user_id ? 'mine' : 'theirs' ?>"><?= nl2br(e($m['body'])) ?><span class="t"><?= e($fullDate($m['created_at'])) ?></span></div>
                                    <?php endforeach; ?>
                                </div>
                                <form method="post" action="thread_view.php?id=<?= (int) $t['id'] ?>" class="ib-reply">
                                    <input type="hidden" name="back" value="inbox">
                                    <input type="text" name="body" class="form-control" placeholder="Reply to <?= e($current['sender']) ?>" autocomplete="off" required>
                                    <button type="submit" class="btn btn-primary" style="background:#0b77ff;border-color:#0b77ff;" aria-label="Send"><i class="bi bi-send-fill"></i></button>
                                </form>
                                <script>const ibLog = document.getElementById('ibLog'); if (ibLog) ibLog.scrollTop = ibLog.scrollHeight;</script>
                            <?php endif; ?>
                        <?php endif; ?>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
