<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

// Real existing threads for this student, newest message first, with the other side's name
// (a coach thread's title is always the mentor's name, looked up here -- MSG-05).
$threads = executeQuery($db, "
    SELECT t.id, t.type, m.display_name AS mentor_name,
           lm.body AS last_body, lm.created_at AS last_at, lm.sender_id AS last_sender_id,
           (SELECT COUNT(*) FROM thread_messages tm2
             WHERE tm2.thread_id = t.id AND tm2.sender_id != ?
               AND tm2.id > COALESCE(tp.last_read_message_id, 0)) AS unread_count
    FROM threads t
    JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
    LEFT JOIN mentors m ON m.id = t.mentor_id
    LEFT JOIN thread_messages lm ON lm.id = (SELECT MAX(id) FROM thread_messages WHERE thread_id = t.id)
    ORDER BY lm.created_at DESC
", [$studentId, $studentId])->fetchAll(PDO::FETCH_ASSOC);

// Mentors the student doesn't have a thread with yet -- offer to start one.
$startable = executeQuery($db, "
    SELECT m.id, m.display_name, m.tint FROM mentors m
    WHERE m.is_active = 1
      AND m.id NOT IN (
          SELECT t.mentor_id FROM threads t
          JOIN thread_participants tp ON tp.thread_id = t.id AND tp.student_id = ?
          WHERE t.mentor_id IS NOT NULL
      )
", [$studentId])->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Messages - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
        .thread-row { display:flex; align-items:center; gap:1rem; background:#fff; border-radius:14px; padding:1rem 1.25rem; margin-bottom:.6rem; box-shadow:0 1px 6px rgba(15,23,42,.06); text-decoration:none; color:inherit; }
        body.dark .thread-row { background:#1f1f1f; }
        .thread-avatar { width:44px; height:44px; border-radius:50%; background:#eae3ff; color:#6d3fe0; display:flex; align-items:center; justify-content:center; font-weight:700; flex-shrink:0; }
        .thread-unread-dot { width:9px; height:9px; border-radius:50%; background:#2f6fe0; flex-shrink:0; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4" style="max-width:680px;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <i class="bi bi-chat-dots-fill fs-2 text-primary"></i>
                <div>
                    <h2 class="mb-0">Messages</h2>
                    <p class="text-muted mb-0">Conversations with your coaches</p>
                </div>
            </div>

            <?php if (!$threads && !$startable): ?>
                <div class="stat-card text-center py-5">
                    <i class="bi bi-chat-dots fs-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">No messages yet</h5>
                </div>
            <?php endif; ?>

            <?php foreach ($threads as $t): ?>
                <a href="thread_view.php?id=<?= (int) $t['id'] ?>" class="thread-row">
                    <div class="thread-avatar"><?= e(mb_substr($t['mentor_name'] ?? '?', 0, 1)) ?></div>
                    <div class="flex-grow-1" style="min-width:0;">
                        <div class="fw-semibold"><?= e($t['mentor_name'] ?? 'Conversation') ?></div>
                        <div class="text-muted small text-truncate">
                            <?= $t['last_sender_id'] == $studentId ? 'You: ' : '' ?><?= e($t['last_body'] ?? '') ?>
                        </div>
                    </div>
                    <?php if ($t['unread_count'] > 0): ?><span class="thread-unread-dot"></span><?php endif; ?>
                    <?php if ($t['last_at']): ?><div class="text-muted small"><?= e((new DateTime($t['last_at']))->format('j M')) ?></div><?php endif; ?>
                </a>
            <?php endforeach; ?>

            <?php foreach ($startable as $m): ?>
                <a href="thread_view.php?mentor=<?= (int) $m['id'] ?>" class="thread-row">
                    <div class="thread-avatar"><?= e(mb_substr($m['display_name'], 0, 1)) ?></div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= e($m['display_name']) ?></div>
                        <div class="text-muted small">Start a conversation</div>
                    </div>
                    <i class="bi bi-chevron-right text-muted"></i>
                </a>
            <?php endforeach; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
