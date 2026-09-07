<?php
/**
 * IELTS Academic Diagnostic — Writing.
 * Self-contained: Task 1 only, 20 minutes. Does NOT share mock_writing.php
 * (that page is shaped for the Full Mock tests — Task 1 + Task 2, 60 minutes —
 * and hardcodes both assumptions in its timer, header label, and submit button).
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$session_id  = (int)($_GET['session_id'] ?? 0);
if (!$session_id) {
    header("Location: mock_start.php");
    exit();
}

$student_id  = (int)$_SESSION['user_id'];
$adminEmails = ['v.animasahun@slslanguage.com', 'animasahunvictor1@gmail.com', 'ashonibarevik@gmail.com'];
$isAdmin     = in_array($_SESSION['user_email'] ?? '', $adminEmails);

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session || $session['status'] !== 'in_progress') {
    header("Location: mock_start.php");
    exit();
}

$map = require INCLUDES_PATH . '/mock_test_map.php';

// Students must complete Listening + Reading first; admins can preview freely
if (!$isAdmin && is_null($session['listening_attempt_id'])) {
    $file = $map[$session['mock_code']]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (!$isAdmin && is_null($session['reading_attempt_id'])) {
    $file = $map[$session['mock_code']]['reading']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (!$isAdmin && !is_null($session['writing_attempt_id'])) {
    $file = $map[$session['mock_code']]['speaking']['file'] ?? 'diagnostic_aca_speaking.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}

// Load the single Task 1 prompt from DB
$testCode = $map[$session['mock_code']]['writing']['test_code'] ?? '';
$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$writingTest = $stmt->fetch(PDO::FETCH_ASSOC);

$task1 = ['question' => ''];

if ($writingTest) {
    $stmt = $db->prepare("SELECT question_text FROM questions WHERE test_id = ? AND question_number = 1 LIMIT 1");
    $stmt->execute([(int)$writingTest['id']]);
    $task1['question'] = $stmt->fetchColumn() ?: '';
}

$DURATION_SECS = 20 * 60; // 20 minutes — Task 1 only
$wordMin       = 150;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writing — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .panel { background:#fff; border-radius:16px; padding:1.75rem; box-shadow:0 4px 20px rgba(0,0,0,.07); }
        .section-badge { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; padding:.45rem 1.25rem; border-radius:50px; font-weight:700; font-size:.88rem; }
        .timer-display { font-size:2rem; font-weight:700; font-family:monospace; color:#d97706; }
        .timer-display.warning { color:#ef4444; }
        .prompt-box { background:#fffbeb; border-left:4px solid #f59e0b; border-radius:8px; padding:1.25rem 1.5rem; font-size:.9rem; line-height:1.75; margin-bottom:1.25rem; white-space:pre-line; }
        .essay-area { width:100%; min-height:340px; padding:1rem; border:2px solid #e5e7eb; border-radius:10px; font-size:.95rem; line-height:1.8; resize:vertical; font-family:system-ui,sans-serif; }
        .essay-area:focus { border-color:#f59e0b; outline:none; }
        .word-count { font-size:1.4rem; font-weight:700; }
        .word-count.below { color:#ef4444; } .word-count.ok { color:#10b981; }
        .progress-steps { display:flex; gap:.5rem; align-items:center; }
        .step { display:flex; align-items:center; gap:.35rem; font-size:.8rem; color:#94a3b8; }
        .step.done { color:#10b981; } .step.current { color:#f59e0b; font-weight:600; }
        .step-dot { width:8px; height:8px; border-radius:50%; background:currentColor; }
        .sticky-header { position:fixed; top:var(--topbar-h,60px); left:var(--sidebar-w,220px); right:280px; z-index:150; background:#f1f5f9; padding:.6rem 1.5rem .5rem; border-bottom:1px solid #e2e8f0; box-shadow:0 2px 6px rgba(0,0,0,.05); }
        @media (max-width:1399px) { .sticky-header { right:0; } }
        @media (max-width:1199px) { .sticky-header { left:0; right:0; } }
        body.sidebar-collapsed .sticky-header { left:0; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-3">
            <div class="sticky-header">
                <?php if ($isAdmin): ?>
                <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:8px;margin-bottom:.5rem;display:flex;align-items:center;gap:1.5rem;font-size:.82rem;font-weight:600;">
                    <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview</span>
                    <a href="diagnostic_aca_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                    <a href="diagnostic_aca_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                    <a href="diagnostic_aca_writing.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">✍️ Writing</a>
                    <a href="diagnostic_aca_speaking.php?session_id=<?= $session_id ?>"  style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
                </div>
                <?php endif; ?>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="progress-steps">
                        <div class="step done"><div class="step-dot"></div>Listening</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step done"><div class="step-dot"></div>Reading</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step current"><div class="step-dot"></div>Writing</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step"><div class="step-dot"></div>Speaking</div>
                    </div>
                    <small class="text-muted"><?= htmlspecialchars($session['mock_title']) ?></small>
                </div>
            </div>

            <div class="panel" style="margin-top:<?= $isAdmin ? '110px' : '60px' ?>;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-badge"><i class="bi bi-pencil-square me-1"></i>Writing</span>
                        <span class="text-muted small">Task 1 · 20 Minutes</span>
                    </div>
                    <div class="timer-display" id="timerEl">20:00</div>
                </div>

                <?php if (empty($task1['question'])): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    No writing task loaded yet. Please run database migration 063 and contact your instructor.
                </div>
                <?php else: ?>
                <div class="row g-3">
                    <div class="col-lg-5">
                        <p class="small fw-semibold text-uppercase text-muted mb-2">Writing Task 1</p>
                        <div class="prompt-box"><?= htmlspecialchars($task1['question']) ?></div>
                    </div>
                    <div class="col-lg-7">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-semibold small">Your Response</label>
                            <div><span id="wc1" class="word-count below">0</span><span class="text-muted small ms-1">/ <?= $wordMin ?>+ words</span></div>
                        </div>
                        <textarea id="essay1" class="essay-area" placeholder="Write your Task 1 response here…"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button class="btn btn-success px-4 fw-bold" id="submitBtn" onclick="confirmSubmit()">
                        <i class="bi bi-check-lg me-1"></i>Submit Writing
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const SESSION_ID = <?= $session_id ?>;
    const T1_Q       = <?= json_encode($task1['question']) ?>;
    const T1_MIN     = <?= $wordMin ?>;

    let timeLeft  = <?= $DURATION_SECS ?>;
    let submitted = false;
    const timerEl = document.getElementById('timerEl');

    function fmtTime(s) { return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); }
    function countWords(txt) { return txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length; }

    const ticker = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 120) timerEl.classList.add('warning');
        if (timeLeft <= 0) { clearInterval(ticker); doSubmit(); }
    }, 1000);

    const essay1 = document.getElementById('essay1');
    essay1?.addEventListener('input', () => {
        const n = countWords(essay1.value);
        const el = document.getElementById('wc1');
        el.textContent = n;
        el.className = 'word-count ' + (n >= T1_MIN ? 'ok' : 'below');
    });

    function confirmSubmit() {
        const w1 = countWords(essay1.value);
        const warn = w1 < T1_MIN ? `<div class="alert alert-warning text-start small">Below ${T1_MIN} words (${w1} written)</div><p class="text-muted small">You can still submit, but a low word count will affect your score.</p>` : `<p>Task 1: <strong>${w1}</strong> words</p><p class="text-muted small">AI grading may take a moment. You cannot return to this section.</p>`;

        Swal.fire({
            title: 'Submit Writing Section?',
            html: warn,
            icon: w1 < T1_MIN ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Keep writing',
            confirmButtonColor: '#10b981',
        }).then(r => { if (r.isConfirmed) doSubmit(); });
    }

    function doSubmit() {
        if (submitted) return;
        submitted = true;
        clearInterval(ticker);
        document.getElementById('submitBtn') && (document.getElementById('submitBtn').disabled = true);

        Swal.fire({
            title: 'Submitting & grading your writing…',
            html: '<p class="text-muted small">AI is scoring your response. This may take up to 30 seconds.</p>',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('mock_save_section.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                session_id:     SESSION_ID,
                section:        'writing',
                task1_question: T1_Q,
                task1_essay:    essay1.value,
                time_spent:     <?= $DURATION_SECS ?> - timeLeft,
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ title: 'Writing submitted!', text: 'Moving to the final step.', icon: 'success', timer: 2000, showConfirmButton: false })
                    .then(() => window.location.href = data.redirect);
            } else {
                Swal.fire('Error', data.error || 'Could not save. Please try again.', 'error');
                submitted = false;
            }
        })
        .catch(() => { Swal.fire('Error', 'Network error. Please try again.', 'error'); submitted = false; });
    }
    </script>
</body>
</html>
