<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$threadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mentorId = isset($_GET['mentor']) ? (int) $_GET['mentor'] : 0;

// Opening by ?mentor= with no existing thread yet: don't create anything until the student
// actually sends a message (GET must not write). The empty-thread composer still works below.
if (!$threadId && $mentorId) {
    $existing = executeQuery($db, "
        SELECT t.id FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        WHERE t.mentor_id = ?
    ", [$studentId, $mentorId])->fetch(PDO::FETCH_ASSOC);
    if ($existing) { header("Location: thread_view.php?id=" . (int) $existing['id']); exit(); }
}

$mentor = $mentorId ? executeQuery($db, "SELECT * FROM mentors WHERE id = ? AND is_active = 1", [$mentorId])->fetch(PDO::FETCH_ASSOC) : null;

if ($threadId) {
    $thread = executeQuery($db, "
        SELECT t.*, m.display_name AS mentor_name FROM threads t
        JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
        LEFT JOIN mentors m ON m.id = t.mentor_id
        WHERE t.id = ?
    ", [$studentId, $threadId])->fetch(PDO::FETCH_ASSOC);
    if (!$thread) { header("Location: threads.php"); exit(); }
    $mentor = ['id' => $thread['mentor_id'], 'display_name' => $thread['mentor_name']];
} elseif (!$mentor) {
    header("Location: threads.php"); exit();
}

// MSG-02/03: send a message. Creates the thread lazily on the first message if it doesn't exist.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = trim((string) ($_POST['body'] ?? ''));
    if ($body !== '') {
        if (!$threadId) {
            $db->beginTransaction();
            $db->prepare("INSERT INTO threads (type, mentor_id) VALUES ('coach', ?)")->execute([$mentorId]);
            $threadId = (int) $db->lastInsertId();
            $db->prepare("INSERT INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $studentId]);
            // The mentor's own staff login (if any) is a participant too, so they see it on their side.
            $mentorStaffStudentId = executeQuery($db, "
                SELECT sa.student_id FROM mentors m JOIN staff_accounts sa ON sa.id = m.staff_account_id WHERE m.id = ?
            ", [$mentorId])->fetchColumn();
            if ($mentorStaffStudentId) {
                $db->prepare("INSERT IGNORE INTO thread_participants (thread_id, student_id) VALUES (?, ?)")->execute([$threadId, $mentorStaffStudentId]);
            }
            $db->commit();
        }
        executeQuery($db, "INSERT INTO thread_messages (thread_id, sender_id, body) VALUES (?, ?, ?)", [$threadId, $studentId, $body]);
        // MSG-01 equivalent for the sender's own view: sending counts as having read up to now.
        $lastId = (int) executeQuery($db, "SELECT MAX(id) FROM thread_messages WHERE thread_id = ?", [$threadId])->fetchColumn();
        executeQuery($db, "UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?", [$lastId, $threadId, $studentId]);
    }
    header("Location: thread_view.php?id=$threadId");
    exit();
}

$messages = $threadId
    ? executeQuery($db, "SELECT * FROM thread_messages WHERE thread_id = ? ORDER BY id ASC", [$threadId])->fetchAll(PDO::FETCH_ASSOC)
    : [];

// MSG-01: opening the conversation marks it read.
if ($threadId && $messages) {
    $lastId = end($messages)['id'];
    executeQuery($db, "UPDATE thread_participants SET last_read_message_id = ? WHERE thread_id = ? AND student_id = ?", [$lastId, $threadId, $studentId]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= e($mentor['display_name'] ?? 'Conversation') ?> - Messages - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
        .thread-wrap { max-width:620px; display:flex; flex-direction:column; height:calc(100vh - 160px); }
        .thread-log { flex:1; overflow-y:auto; padding:.5rem 0; display:flex; flex-direction:column; gap:.5rem; }
        .bubble { max-width:75%; padding:.6rem .9rem; border-radius:16px; font-size:.93rem; }
        .bubble.mine { align-self:flex-end; background:#2f5bd8; color:#fff; border-bottom-right-radius:4px; }
        .bubble.theirs { align-self:flex-start; background:#fff; border:1px solid #e2e8f0; border-bottom-left-radius:4px; }
        body.dark .bubble.theirs { background:#1f1f1f; border-color:#333; }
        .composer-row { display:flex; gap:.6rem; padding-top:.75rem; border-top:1px solid #e2e8f0; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <a href="threads.php" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
                <i class="bi bi-arrow-left"></i> Messages
            </a>
            <h4 class="mb-3"><?= e($mentor['display_name'] ?? 'Conversation') ?></h4>

            <div class="thread-wrap">
                <div class="thread-log" id="threadLog">
                    <?php if (!$messages): ?>
                        <p class="text-muted text-center mt-4">Say hello to get started.</p>
                    <?php endif; ?>
                    <?php foreach ($messages as $m): ?>
                        <div class="bubble <?= $m['sender_id'] == $studentId ? 'mine' : 'theirs' ?>"><?= nl2br(e($m['body'])) ?></div>
                    <?php endforeach; ?>
                </div>
                <form method="post" class="composer-row">
                    <input type="text" name="body" class="form-control" placeholder="Message" autocomplete="off" required>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                </form>
            </div>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const log = document.getElementById('threadLog');
if (log) log.scrollTop = log.scrollHeight;
</script>
</body>
</html>
