<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) { header("Location: edu_hub_registration.php"); exit(); }
$studentId = (int) $_SESSION['user_id'];

if (!function_exists('e')) {
    function e($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }
}

$mentorId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$mentor = $mentorId ? executeQuery($db, "SELECT * FROM mentors WHERE id = ? AND is_active = 1", [$mentorId])->fetch(PDO::FETCH_ASSOC) : null;
if (!$mentor) { header("Location: mentors.php"); exit(); }

$notice = null;
$error = null;

// MBK-02/MBK-04: send a request, or cancel the student's own open one. Same-page POST, like the
// rest of this site's small write actions (e.g. mark_read.php), rather than a separate API file.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'cancel') {
        $requestId = (int) ($_POST['request_id'] ?? 0);
        executeQuery($db, "UPDATE mentor_requests SET status = 'cancelled' WHERE id = ? AND student_id = ? AND mentor_id = ?", [$requestId, $studentId, $mentorId]);
        header("Location: mentor_book.php?id=$mentorId&cancelled=1");
        exit();
    }

    if ($action === 'request') {
        $slot = trim((string) ($_POST['slot'] ?? '')); // "YYYY-MM-DD HH:MM:SS"
        $note = trim((string) ($_POST['note'] ?? ''));
        $parsed = DateTime::createFromFormat('Y-m-d H:i:s', $slot);

        if (!$parsed) {
            $error = 'Choose a day and time.';
        } else {
            // Re-validate the slot is still genuinely open (availability window + not already taken),
            // rather than trusting the posted value -- the picker only ever renders open slots, but
            // two students could race for the same one.
            $weekday = (int) $parsed->format('w');
            $time = $parsed->format('H:i:s');
            $inWindow = executeQuery($db, "
                SELECT 1 FROM mentor_availability
                WHERE mentor_id = ? AND weekday = ? AND ? >= start_time AND ? < end_time
                LIMIT 1
            ", [$mentorId, $weekday, $time, $time])->fetch();

            $taken = executeQuery($db, "
                SELECT 1 FROM mentor_requests
                WHERE mentor_id = ? AND preferred_at = ? AND status IN ('requested','confirmed')
                LIMIT 1
            ", [$mentorId, $parsed->format('Y-m-d H:i:s')])->fetch();

            $alreadyOpen = executeQuery($db, "
                SELECT 1 FROM mentor_requests
                WHERE mentor_id = ? AND student_id = ? AND status IN ('requested','confirmed')
                LIMIT 1
            ", [$mentorId, $studentId])->fetch();

            if ($alreadyOpen) {
                $error = 'You already have an open request with this mentor.';
            } elseif (!$inWindow) {
                $error = 'That time is outside this mentor\'s hours.';
            } elseif ($taken) {
                $error = 'That time was just taken. Choose another.';
            } else {
                executeQuery($db, "INSERT INTO mentor_requests (mentor_id, student_id, preferred_at, note, status) VALUES (?, ?, ?, ?, 'requested')", [
                    $mentorId, $studentId, $parsed->format('Y-m-d H:i:s'), $note !== '' ? $note : null,
                ]);
                header("Location: mentor_book.php?id=$mentorId&sent=1");
                exit();
            }
        }
    }
}

if (isset($_GET['sent'])) $notice = 'Request sent. ' . e($mentor['display_name']) . ' will confirm the time.';
if (isset($_GET['cancelled'])) $notice = 'Request cancelled.';

$openRequest = executeQuery($db, "
    SELECT * FROM mentor_requests WHERE mentor_id = ? AND student_id = ? AND status IN ('requested','confirmed') ORDER BY id DESC LIMIT 1
", [$mentorId, $studentId])->fetch(PDO::FETCH_ASSOC);

// Build the next 5 days that have an availability window, with open/taken slots for each.
$availability = executeQuery($db, "SELECT * FROM mentor_availability WHERE mentor_id = ? ORDER BY weekday", [$mentorId])->fetchAll(PDO::FETCH_ASSOC);
$byWeekday = [];
foreach ($availability as $a) $byWeekday[(int) $a['weekday']][] = $a;

$takenSlots = array_column(executeQuery($db, "
    SELECT preferred_at FROM mentor_requests WHERE mentor_id = ? AND status IN ('requested','confirmed') AND preferred_at >= NOW()
", [$mentorId])->fetchAll(PDO::FETCH_ASSOC), 'preferred_at');
$takenSlots = array_flip($takenSlots);

$days = [];
$cursor = new DateTime('tomorrow');
for ($i = 0; count($days) < 5 && $i < 21; $i++, $cursor->modify('+1 day')) {
    $weekday = (int) $cursor->format('w');
    if (empty($byWeekday[$weekday])) continue;
    $slots = [];
    foreach ($byWeekday[$weekday] as $win) {
        $t = DateTime::createFromFormat('Y-m-d H:i:s', $cursor->format('Y-m-d') . ' ' . $win['start_time']);
        $end = DateTime::createFromFormat('Y-m-d H:i:s', $cursor->format('Y-m-d') . ' ' . $win['end_time']);
        while ($t < $end) {
            $key = $t->format('Y-m-d H:i:s');
            $slots[] = ['value' => $key, 'label' => $t->format('g:i A'), 'taken' => isset($takenSlots[$key])];
            $t->modify('+' . (int) $win['slot_minutes'] . ' minutes');
        }
    }
    $days[] = ['date' => $cursor->format('Y-m-d'), 'weekday_label' => $cursor->format('D'), 'day_label' => $cursor->format('j M'), 'slots' => $slots];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= e($mentor['display_name']) ?> - Mentors - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        .mentor-avatar { width:64px; height:64px; border-radius:50%;
            background:linear-gradient(135deg,#0b77ff,#7c3aed); display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:1.3rem; font-weight:700; }
        .day-chip { border:1px solid #e2e8f0; border-radius:12px; padding:.6rem .9rem; text-align:center; cursor:pointer; background:#fff; }
        .day-chip.active { background:#0b77ff; color:#fff; border-color:#0b77ff; }
        body.dark .day-chip { background:#1f1f1f; border-color:#333; }
        .slot-chip { border:1px solid #e2e8f0; border-radius:10px; padding:.5rem; text-align:center; cursor:pointer; background:#fff; font-size:.9rem; }
        .slot-chip.active { background:#0b77ff; color:#fff; border-color:#0b77ff; }
        .slot-chip.taken { opacity:.4; text-decoration:line-through; cursor:not-allowed; background:#f3f4f6; }
        body.dark .slot-chip { background:#1f1f1f; border-color:#333; }
        .slots-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:.6rem; }
        @media(min-width:1400px){.content{max-width:calc(100vw - 500px);}}
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4" style="max-width:720px;">
            <a href="mentors.php" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3">
                <i class="bi bi-arrow-left"></i> Mentors
            </a>

            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="mentor-avatar"><?= e(mb_substr($mentor['display_name'], 0, 1)) ?></div>
                <div>
                    <h3 class="mb-0"><?= e($mentor['display_name']) ?></h3>
                    <p class="text-muted mb-0"><?= e($mentor['focus_area']) ?></p>
                </div>
            </div>
            <?php if ($mentor['bio']): ?><p class="text-muted"><?= nl2br(e($mentor['bio'])) ?></p><?php endif; ?>

            <?php if ($notice): ?><div class="alert alert-success"><?= $notice ?></div><?php endif; ?>
            <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>

            <?php if ($openRequest): ?>
                <div class="stat-card p-4" style="border:2px solid #16a34a;border-radius:16px;">
                    <h5 class="text-success"><i class="bi bi-check-circle-fill"></i> Request sent</h5>
                    <p class="mb-3">
                        <?= e((new DateTime($openRequest['preferred_at']))->format('D j M, g:i A')) ?>.
                        <?= e($mentor['display_name']) ?> will confirm the time in Messages.
                    </p>
                    <form method="post" onsubmit="return confirm('Cancel this request?');">
                        <input type="hidden" name="action" value="cancel">
                        <input type="hidden" name="request_id" value="<?= (int) $openRequest['id'] ?>">
                        <button type="submit" class="btn btn-outline-danger">Cancel request</button>
                    </form>
                </div>
            <?php else: ?>
                <form method="post" id="bookForm">
                    <input type="hidden" name="action" value="request">
                    <input type="hidden" name="slot" id="slotInput" value="">

                    <p class="fw-semibold mb-2">Pick a day</p>
                    <div class="d-flex gap-2 flex-wrap mb-3">
                        <?php foreach ($days as $i => $d): ?>
                            <div class="day-chip <?= $i === 0 ? 'active' : '' ?>" data-day="<?= $i ?>">
                                <div class="small"><?= e($d['weekday_label']) ?></div>
                                <div class="fw-bold"><?= e($d['day_label']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p class="fw-semibold mb-2">Pick a time</p>
                    <?php foreach ($days as $i => $d): ?>
                        <div class="slots-grid day-slots" data-day="<?= $i ?>" style="<?= $i === 0 ? '' : 'display:none' ?>">
                            <?php foreach ($d['slots'] as $s): ?>
                                <div class="slot-chip <?= $s['taken'] ? 'taken' : '' ?>" data-value="<?= e($s['value']) ?>">
                                    <?= e($s['label']) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <p class="text-muted small mt-3">Times are in the server's local time zone.</p>

                    <div class="mb-3">
                        <label class="form-label">What do you want to work on? (optional)</label>
                        <textarea class="form-control" name="note" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Choose a day and time</button>
                </form>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
document.querySelectorAll('.day-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        document.querySelectorAll('.day-chip').forEach(c => c.classList.remove('active'));
        chip.classList.add('active');
        document.querySelectorAll('.day-slots').forEach(s => s.style.display = s.dataset.day === chip.dataset.day ? '' : 'none');
        document.querySelectorAll('.slot-chip').forEach(s => s.classList.remove('active'));
        document.getElementById('slotInput').value = '';
        updateButton();
    });
});
document.querySelectorAll('.slot-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        if (chip.classList.contains('taken')) return;
        document.querySelectorAll('.slot-chip').forEach(s => s.classList.remove('active'));
        chip.classList.add('active');
        document.getElementById('slotInput').value = chip.dataset.value;
        updateButton();
    });
});
function updateButton() {
    const btn = document.getElementById('submitBtn');
    const val = document.getElementById('slotInput').value;
    btn.disabled = !val;
    btn.textContent = val ? 'Request this time' : 'Choose a day and time';
}
</script>
</body>
</html>
