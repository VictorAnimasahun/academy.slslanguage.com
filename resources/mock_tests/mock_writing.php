<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$student_id = (int)$_SESSION['user_id'];
$session_id = (int)($_GET['session_id'] ?? 0);

if (!$session_id) { header("Location: index.php"); exit(); }

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) { die("Session not found."); }
if ($session['status'] !== 'in_progress') { header("Location: mock_start.php"); exit(); }

$map      = require INCLUDES_PATH . '/mock_test_map.php';
$mockCode = $session['mock_code'];

if (is_null($session['listening_attempt_id'])) {
    $file = $map[$mockCode]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (is_null($session['reading_attempt_id'])) {
    $file = $map[$mockCode]['reading']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (!is_null($session['writing_attempt_id'])) {
    header("Location: mock_speaking.php?session_id={$session_id}"); exit();
}

$timeLimit = 60 * 60;
$t1WordMin = 150;
$t2WordMin = 250;

// Load writing prompts from DB
$testCode = $map[$mockCode]['writing']['test_code'] ?? '';
$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$writingTest = $stmt->fetch(PDO::FETCH_ASSOC);

$task1 = ['question' => '', 'visual' => null];
$task2 = ['question' => ''];

if ($writingTest) {
    $stmt = $db->prepare("
        SELECT question_number, question_text, instructions
        FROM questions
        WHERE test_id = ?
        ORDER BY question_number
        LIMIT 2
    ");
    $stmt->execute([(int)$writingTest['id']]);
    $writingQs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($writingQs as $wq) {
        if ((int)$wq['question_number'] === 1) {
            $task1['question'] = $wq['question_text'] ?? '';
            $task1['visual']   = $wq['instructions'] ?? null;
        } elseif ((int)$wq['question_number'] === 2) {
            $task2['question'] = $wq['question_text'] ?? '';
        }
    }
}

// Fallback if DB not seeded yet
if ($task1['question'] === '') {
    $task1['question'] = "Writing Task 1 prompt not yet configured. Please contact your instructor.";
}
if ($task2['question'] === '') {
    $task2['question'] = "Writing Task 2 prompt not yet configured. Please contact your instructor.";
}
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
        .task-tab { border:none; background:none; padding:.6rem 1.25rem; color:#64748b; font-weight:500; border-bottom:3px solid transparent; cursor:pointer; }
        .task-tab.active { color:#f59e0b; border-bottom-color:#f59e0b; }
        .task-panel { display:none; } .task-panel.active { display:block; }
        .prompt-box { background:#fffbeb; border-left:4px solid #f59e0b; border-radius:8px; padding:1.25rem 1.5rem; font-size:.9rem; line-height:1.75; margin-bottom:1.25rem; white-space:pre-line; }
        .essay-area { width:100%; min-height:340px; padding:1rem; border:2px solid #e5e7eb; border-radius:10px; font-size:.95rem; line-height:1.8; resize:vertical; font-family:system-ui,sans-serif; }
        .essay-area:focus { border-color:#f59e0b; outline:none; }
        .word-count { font-size:1.4rem; font-weight:700; }
        .word-count.below { color:#ef4444; } .word-count.ok { color:#10b981; }
        .chart-placeholder { background:#f3f4f6; border:2px dashed #d1d5db; border-radius:10px; height:200px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#9ca3af; font-size:.88rem; margin-bottom:1rem; }
        .progress-steps { display:flex; gap:.5rem; align-items:center; }
        .step { display:flex; align-items:center; gap:.35rem; font-size:.8rem; color:#94a3b8; }
        .step.done { color:#10b981; } .step.current { color:#f59e0b; font-weight:600; }
        .step-dot { width:8px; height:8px; border-radius:50%; background:currentColor; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-2">
            <div class="d-flex align-items-center justify-content-between mb-3">
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

            <div class="panel">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-badge"><i class="bi bi-pencil-square me-1"></i>Writing</span>
                        <span class="text-muted small">Task 1 + Task 2 · 60 Minutes</span>
                    </div>
                    <div class="timer-display" id="timerEl">60:00</div>
                </div>

                <div class="d-flex border-bottom mb-4">
                    <button class="task-tab active" onclick="switchTask(1)" id="ttab-1">Task 1 <span class="text-muted" style="font-size:.72rem;">150+ words</span></button>
                    <button class="task-tab" onclick="switchTask(2)" id="ttab-2">Task 2 <span class="text-muted" style="font-size:.72rem;">250+ words</span></button>
                </div>

                <!-- Task 1 -->
                <div class="task-panel active" id="task-1">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <p class="small fw-semibold text-uppercase text-muted mb-2">Writing Task 1</p>
                            <div class="prompt-box"><?= htmlspecialchars($task1['question']) ?></div>
                            <?php if ($task1['visual']): ?>
                                <img src="<?= htmlspecialchars($task1['visual']) ?>" alt="Task 1 visual" class="img-fluid rounded">
                            <?php else: ?>
                                <div class="chart-placeholder"><i class="bi bi-bar-chart-fill fs-2 mb-2"></i><span>Chart / diagram will appear here</span></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold small">Your Response</label>
                                <div><span id="wc1" class="word-count below">0</span><span class="text-muted small ms-1">/ <?= $t1WordMin ?>+ words</span></div>
                            </div>
                            <textarea id="essay1" class="essay-area" placeholder="Write your Task 1 response here…"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-warning fw-semibold" onclick="switchTask(2)">Next: Task 2 →</button>
                    </div>
                </div>

                <!-- Task 2 -->
                <div class="task-panel" id="task-2">
                    <div class="row g-3">
                        <div class="col-lg-5">
                            <p class="small fw-semibold text-uppercase text-muted mb-2">Writing Task 2</p>
                            <div class="prompt-box"><?= htmlspecialchars($task2['question']) ?></div>
                        </div>
                        <div class="col-lg-7">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold small">Your Response</label>
                                <div><span id="wc2" class="word-count below">0</span><span class="text-muted small ms-1">/ <?= $t2WordMin ?>+ words</span></div>
                            </div>
                            <textarea id="essay2" class="essay-area" placeholder="Write your Task 2 essay here…"></textarea>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button class="btn btn-outline-secondary" onclick="switchTask(1)">← Back to Task 1</button>
                        <button class="btn btn-success px-4 fw-bold" id="submitBtn" onclick="confirmSubmit()">
                            <i class="bi bi-check-lg me-1"></i>Submit Writing
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const SESSION_ID  = <?= $session_id ?>;
    const T1_Q = <?= json_encode($task1['question']) ?>;
    const T2_Q = <?= json_encode($task2['question']) ?>;
    const T1_MIN = <?= $t1WordMin ?>;
    const T2_MIN = <?= $t2WordMin ?>;

    let timeLeft = <?= $timeLimit ?>;
    let submitted = false;
    const timerEl = document.getElementById('timerEl');

    function fmtTime(s) { return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); }
    function countWords(txt) { return txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length; }

    const ticker = setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) { clearInterval(ticker); doSubmit(); }
    }, 1000);

    function switchTask(n) {
        document.querySelectorAll('.task-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.task-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('ttab-' + n)?.classList.add('active');
        document.getElementById('task-' + n)?.classList.add('active');
    }

    function updateWC(id, elId, min) {
        const n = countWords(document.getElementById(id).value);
        const el = document.getElementById(elId);
        el.textContent = n;
        el.className = 'word-count ' + (n >= min ? 'ok' : 'below');
    }

    document.getElementById('essay1').addEventListener('input', () => updateWC('essay1', 'wc1', T1_MIN));
    document.getElementById('essay2').addEventListener('input', () => updateWC('essay2', 'wc2', T2_MIN));

    function confirmSubmit() {
        const w1 = countWords(document.getElementById('essay1').value);
        const w2 = countWords(document.getElementById('essay2').value);

        let warn = '';
        if (w1 < T1_MIN) warn += `<li>Task 1 is below ${T1_MIN} words (${w1} written)</li>`;
        if (w2 < T2_MIN) warn += `<li>Task 2 is below ${T2_MIN} words (${w2} written)</li>`;

        Swal.fire({
            title: 'Submit Writing Section?',
            html: warn
                ? `<div class="alert alert-warning text-start small"><ul class="mb-0">${warn}</ul></div><p class="text-muted small">You can still submit, but low word counts will affect your score.</p>`
                : `<p>Task 1: <strong>${w1}</strong> words &nbsp; Task 2: <strong>${w2}</strong> words</p><p class="text-muted small">AI grading may take a moment. You cannot return to this section.</p>`,
            icon: warn ? 'warning' : 'question',
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
            html: '<p class="text-muted small">AI is scoring your responses. This may take up to 30 seconds.</p>',
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
                task1_essay:    document.getElementById('essay1').value,
                task2_question: T2_Q,
                task2_essay:    document.getElementById('essay2').value,
                time_spent:     <?= $timeLimit ?> - timeLeft,
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
