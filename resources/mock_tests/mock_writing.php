<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$student_id  = (int)$_SESSION['user_id'];
$session_id  = (int)($_GET['session_id'] ?? 0);
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin     = is_platform_admin();

if (!$session_id) { header("Location: index.php"); exit(); }

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code, t.test_type AS mock_test_type
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

if (!$isAdmin && is_null($session['listening_attempt_id'])) {
    $file = $map[$mockCode]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (!$isAdmin && is_null($session['reading_attempt_id'])) {
    $file = $map[$mockCode]['reading']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}
if (!$isAdmin && !is_null($session['writing_attempt_id'])) {
    $file = $map[$mockCode]['speaking']['file'] ?? 'mock_speaking.php';
    header("Location: {$file}?session_id={$session_id}"); exit();
}

// CELPIP Writing Task 1 & 2 both require ~150-200 words and total 53 minutes;
// IELTS GT Task 1 needs 150+/Task 2 needs 250+ over 60 minutes -- these differ
// enough that a shared default is wrong.
$isCelpip  = str_starts_with((string)$session['mock_test_type'], 'CELPIP');
// Neither CELPIP nor IELTS General Training Writing Task 1 ever has a
// chart/diagram (only IELTS Academic Task 1 does) — this file currently only
// ever routes Academic full mocks + CELPIP through mock_test_map.php, but
// gate on the real signal rather than "not CELPIP" so a future GT full mock
// added to that map doesn't silently regain the diagram slot.
$isGT         = !$isCelpip && stripos((string)$session['mock_test_type'], 'General') !== false;
$showDiagram  = !$isCelpip && !$isGT;
$t1WordMin = 150;
$t2WordMin = $isCelpip ? 150 : 250;
$timeLimitMinutes = $isCelpip ? 53 : 60;
$timeLimit = $timeLimitMinutes * 60;
// CELPIP only: each task has its own independent countdown (27 + 26 = 53
// minutes total), not one shared pool — matches the real exam and the
// practice runner (celpip_writing_runner.php uses the same 27/26 split).
$celpipT1Seconds = 27 * 60;
$celpipT2Seconds = 26 * 60;

// Load writing prompts from DB
$testCode = $map[$mockCode]['writing']['test_code'] ?? '';
$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$writingTest = $stmt->fetch(PDO::FETCH_ASSOC);

$task1 = ['question' => '', 'visual' => null];
$task2 = ['question' => ''];
// CELPIP only: {"lead":"...","bullets":[...]} for Task 1 (email) or
// {"lead":"...","options":{"A":"...","B":"..."}} for Task 2 (survey) —
// see migration 097. Drives the right-hand instructions pane.
$task1Instr = [];
$task2Instr = [];

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
            // `instructions` doubles as plain task notes for GT letter tasks
            // (e.g. "Write at least 150 words...") and as an image path for
            // Academic chart tasks (charts are static uploaded files, never
            // rendered live) -- only treat it as a visual if it actually
            // ends in an image extension. Uploaded filenames in this project
            // often contain spaces (see the CELPIP PPTX imports), so "no
            // spaces" is not a safe way to detect a path -- the extension is.
            $rawInstructions = $wq['instructions'] ?? null;
            $task1['visual'] = ($rawInstructions && preg_match('/\.(png|jpe?g|gif|webp|svg)$/i', trim($rawInstructions)))
                                ? $rawInstructions : null;
            if ($isCelpip && $rawInstructions) {
                $task1Instr = json_decode($rawInstructions, true) ?: [];
            }
        } elseif ((int)$wq['question_number'] === 2) {
            $task2['question'] = $wq['question_text'] ?? '';
            if ($isCelpip && !empty($wq['instructions'])) {
                $task2Instr = json_decode($wq['instructions'], true) ?: [];
            }
        }
    }
}

// No fallback — empty = not configured. Tasks without content simply won't render.
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
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <?php if ($isCelpip): ?>
    <style>
        /* CELPIP Writing — official two-pane layout: scenario left, task
           instructions + response right, one task on screen at a time with
           its own independent timer + NEXT/SUBMIT — matches the real exam
           screen exactly (see reference screenshots, 2026-09-17). Built
           entirely from exam_theme.css's shared design tokens (muted
           blue-gray palette, 4-8px radii, flat/no-shadow, hairline borders)
           rather than a one-off palette, so it stays visually consistent
           with every other test screen on the platform. */
        .celpip-w-shell { border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); overflow: hidden; }
        .celpip-w-task { display: flex; flex-direction: column; }
        .celpip-w-header {
            display: flex; align-items: center; justify-content: space-between;
            background: var(--exam-bg); padding: .7rem 1.25rem; border-bottom: 1px solid var(--exam-line);
            font-size: .95rem; font-weight: 700; color: var(--exam-ink);
        }
        .celpip-w-timerwrap { display: flex; align-items: center; gap: .9rem; font-weight: 400; }
        .celpip-w-timer { font-size: .88rem; color: var(--exam-ink-muted); }
        .celpip-w-timer strong { color: var(--exam-ink); font-weight: 700; }
        .celpip-w-next {
            background: var(--exam-accent); color: #fff; border: none; border-radius: var(--exam-radius);
            padding: .5rem 1.3rem; font-weight: 700; font-size: .85rem; cursor: pointer;
        }
        .celpip-w-next:hover { opacity: .88; }
        .celpip-w-split { display: grid; grid-template-columns: 1fr 1fr; min-height: 480px; }
        .celpip-w-pane { padding: 1.5rem 1.75rem; }
        .celpip-w-pane.left { background: var(--exam-surface); border-right: 1px solid var(--exam-line); }
        .celpip-w-pane.right { background: var(--exam-accent-soft); display: flex; flex-direction: column; }
        .celpip-w-heading { display: flex; align-items: flex-start; gap: .5rem; font-weight: 700; color: var(--exam-accent); font-size: .95rem; margin-bottom: 1rem; }
        .celpip-w-heading .bi { margin-top: .15rem; flex-shrink: 0; }
        .celpip-w-scenario { color: var(--exam-ink); font-size: .92rem; line-height: 1.7; white-space: pre-line; }
        .celpip-w-bullets { color: var(--exam-ink); font-size: .92rem; line-height: 1.6; padding-left: 1.25rem; margin-bottom: 1rem; }
        .celpip-w-bullets li { margin-bottom: .4rem; }
        .celpip-w-options { display: flex; flex-direction: column; gap: .9rem; margin-bottom: 1.25rem; }
        .celpip-w-option { display: flex; align-items: flex-start; gap: .6rem; font-size: .92rem; color: var(--exam-ink); cursor: pointer; }
        .celpip-w-option input { margin-top: .25rem; flex-shrink: 0; }
        /* resize: vertical + overflow: auto -- draggable corner handle so the
           student can pull the box taller than the default 260px. */
        .celpip-w-textarea {
            flex: 1; min-height: 260px; width: 100%; border: 1px solid var(--exam-line); border-radius: var(--exam-radius);
            padding: 1rem; font-size: .95rem; line-height: 1.6; resize: vertical; overflow: auto; font-family: inherit;
            background: var(--exam-surface);
        }
        .celpip-w-textarea:focus { outline: none; border-color: var(--exam-accent); }
        .celpip-w-wc { text-align: center; margin-top: .75rem; font-size: .85rem; color: var(--exam-ink-muted); font-weight: 600; }
        @media (max-width: 900px) {
            .celpip-w-split { grid-template-columns: 1fr; }
            .celpip-w-pane.left { border-right: none; border-bottom: 1px solid var(--exam-line); }
        }
    </style>
    <?php endif; ?>
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
                    <a href="full_mock_001_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                    <a href="full_mock_001_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                    <a href="mock_writing.php?session_id=<?= $session_id ?>"            style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">✍️ Writing</a>
                    <a href="<?= $map[$mockCode]['speaking']['file'] ?? 'mock_speaking.php' ?>?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
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
                <?php if ($isCelpip): ?>
                <!-- CELPIP: official two-pane layout, one task at a time, matching
                     the real exam screen exactly (scenario left, instructions +
                     response right, independent per-task timer + NEXT/SUBMIT). -->
                <div class="celpip-w-shell">
                    <?php if (!empty($task1['question'])): ?>
                    <div class="celpip-w-task active" id="celpip-w-task-1">
                        <div class="celpip-w-header">
                            <span>Writing Task 1: Writing an Email</span>
                            <span class="celpip-w-timerwrap">
                                <span class="celpip-w-timer">Time remaining: <strong id="celpipWTimer1"></strong></span>
                                <button type="button" class="celpip-w-next" onclick="celpipWAdvance()">NEXT</button>
                            </span>
                        </div>
                        <div class="celpip-w-split">
                            <div class="celpip-w-pane left">
                                <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> Read the following information.</p>
                                <div class="celpip-w-scenario"><?= nl2br(htmlspecialchars($task1['question'])) ?></div>
                            </div>
                            <div class="celpip-w-pane right">
                                <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($task1Instr['lead'] ?? '') ?></p>
                                <?php if (!empty($task1Instr['bullets'])): ?>
                                <ul class="celpip-w-bullets">
                                    <?php foreach ($task1Instr['bullets'] as $b): ?><li><?= htmlspecialchars($b) ?></li><?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                                <textarea id="essay1" class="celpip-w-textarea" placeholder="Begin writing your response here…"></textarea>
                                <div class="celpip-w-wc"><span id="wc1">0</span> words</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($task2['question'])): ?>
                    <div class="celpip-w-task" id="celpip-w-task-2" style="display:none;">
                        <div class="celpip-w-header">
                            <span>Writing Task 2: Responding to Survey Questions</span>
                            <span class="celpip-w-timerwrap">
                                <span class="celpip-w-timer">Time remaining: <strong id="celpipWTimer2"></strong></span>
                                <button type="button" class="celpip-w-next" id="submitBtn" onclick="confirmSubmit()">SUBMIT</button>
                            </span>
                        </div>
                        <div class="celpip-w-split">
                            <div class="celpip-w-pane left">
                                <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> Read the following information.</p>
                                <div class="celpip-w-scenario"><?= nl2br(htmlspecialchars($task2['question'])) ?></div>
                            </div>
                            <div class="celpip-w-pane right">
                                <p class="celpip-w-heading"><i class="bi bi-info-circle-fill"></i> <?= htmlspecialchars($task2Instr['lead'] ?? '') ?></p>
                                <?php if (!empty($task2Instr['options'])): ?>
                                <div class="celpip-w-options">
                                    <?php foreach ($task2Instr['options'] as $label => $text): ?>
                                    <label class="celpip-w-option">
                                        <input type="radio" name="celpipSurveyChoice">
                                        <span><strong>Option <?= htmlspecialchars($label) ?>:</strong> <?= htmlspecialchars($text) ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php elseif (!empty($task2Instr['bullets'])): ?>
                                <ul class="celpip-w-bullets">
                                    <?php foreach ($task2Instr['bullets'] as $b): ?><li><?= htmlspecialchars($b) ?></li><?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                                <textarea id="essay2" class="celpip-w-textarea" placeholder="Begin writing your response here…"></textarea>
                                <div class="celpip-w-wc"><span id="wc2">0</span> words</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="section-badge"><i class="bi bi-pencil-square me-1"></i>Writing</span>
                        <span class="text-muted small">Task 1 + Task 2 · <?= $timeLimitMinutes ?> Minutes</span>
                    </div>
                    <div class="timer-display" id="timerEl"><?= sprintf('%02d:00', $timeLimitMinutes) ?></div>
                </div>

                <div class="d-flex border-bottom mb-4">
                    <?php if (!empty($task1['question'])): ?>
                    <button class="task-tab active" onclick="switchTask(1)" id="ttab-1">Task 1 <span class="text-muted" style="font-size:.72rem;"><?= $t1WordMin ?>+ words</span></button>
                    <?php endif; ?>
                    <?php if (!empty($task2['question'])): ?>
                    <button class="task-tab <?= empty($task1['question']) ? 'active' : '' ?>" onclick="switchTask(2)" id="ttab-2">Task 2 <span class="text-muted" style="font-size:.72rem;"><?= $t2WordMin ?>+ words</span></button>
                    <?php endif; ?>
                </div>

                <!-- Task 1 -->
                <?php if (!empty($task1['question'])): ?>
                <div class="task-panel active" id="task-1">
                    <div class="wt-switcher">
                        <button class="active" id="wt-switch-a" onclick="wtShowView('a')">Split view</button>
                        <button id="wt-switch-b" onclick="wtShowView('b')">Stacked view</button>
                    </div>
                    <div class="wt-shell">

                        <!-- Layout A: split view -->
                        <div class="wt-view active" id="wt-view-a">
                            <div class="wt-split" id="wtSplit">
                                <div class="wt-pane left">
                                    <p class="small fw-semibold text-uppercase text-muted mb-2" style="font-size:.72rem;">Writing Task 1</p>
                                    <div class="prompt-box"><?= htmlspecialchars($task1['question']) ?></div>
                                    <?php // IELTS GT Writing Task 1 is always plain text — never a
                                    // chart/diagram, so it gets neither an image nor the "will appear
                                    // here" placeholder (only IELTS Academic does). ?>
                                    <?php if ($showDiagram): ?>
                                    <?php if ($task1['visual']): ?>
                                        <img src="<?= htmlspecialchars($task1['visual']) ?>" alt="Task 1 visual" class="img-fluid" style="border:1px solid var(--exam-line);border-radius:4px;">
                                    <?php else: ?>
                                        <div class="chart-placeholder"><i class="bi bi-bar-chart-fill fs-2 mb-2"></i><span>Chart / diagram will appear here</span></div>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="wt-divider" id="wtDivider"></div>
                                <div class="wt-pane right">
                                    <div class="wt-right-head">Your answer</div>
                                    <div id="wtEssaySlotA" class="answer-area" style="flex:1;min-height:0;display:flex;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Layout B: stacked view -->
                        <div class="wt-view" id="wt-view-b">
                            <div class="wt-stack">
                                <div class="wt-accordion">
                                    <div class="wt-accordion-head open" onclick="wtToggleAccordion(this)">
                                        <span class="wt-chev">▸</span>
                                        <span class="wt-label">Task instructions</span>
                                    </div>
                                    <div class="wt-accordion-body open">
                                        <div class="prompt-box" style="margin-bottom:0;"><?= htmlspecialchars($task1['question']) ?></div>
                                    </div>
                                </div>
                                <?php if ($showDiagram && $task1['visual']): ?>
                                <div class="wt-graph-strip">
                                    <div class="wt-thumb"><img src="<?= htmlspecialchars($task1['visual']) ?>" alt="Task 1 visual"></div>
                                    <div class="wt-meta">Task 1 chart / diagram</div>
                                    <button class="wt-expand-link" onclick="wtOpenModal()">Enlarge ⤢</button>
                                </div>
                                <?php endif; ?>
                                <div class="wt-write-zone" id="wtEssaySlotB"></div>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div><span id="wc1" class="word-count below">0</span><span class="text-muted small ms-1">/ <?= $t1WordMin ?>+ words</span></div>
                        <button class="btn btn-warning fw-semibold" onclick="switchTask(2)">Next: Task 2 →</button>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Task 2 -->
                <?php if (!empty($task2['question'])): ?>
                <div class="task-panel <?= empty($task1['question']) ? 'active' : '' ?>" id="task-2">
                    <p class="small fw-semibold text-uppercase text-muted mb-2" style="font-size:.72rem;">Writing Task 2</p>
                    <div class="prompt-box"><?= htmlspecialchars($task2['question']) ?></div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-semibold small">Your Response</label>
                        <div><span id="wc2" class="word-count below">0</span><span class="text-muted small ms-1">/ <?= $t2WordMin ?>+ words</span></div>
                    </div>
                    <textarea id="essay2" class="essay-area" style="min-height:420px;" placeholder="Write your Task 2 essay here…"></textarea>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button class="btn btn-outline-secondary" onclick="switchTask(1)">← Back to Task 1</button>
                        <button class="btn btn-success px-4 fw-bold" id="submitBtn" onclick="confirmSubmit()">
                            <i class="bi bi-check-lg me-1"></i>Submit Writing
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if ($showDiagram && $task1['visual']): ?>
                <div class="wt-modal-backdrop" id="wtModal" onclick="if(event.target===this) wtCloseModal()">
                    <div class="wt-modal">
                        <button class="wt-close" onclick="wtCloseModal()">Close ✕</button>
                        <img src="<?= htmlspecialchars($task1['visual']) ?>" alt="Task 1 visual, enlarged">
                    </div>
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
    const SESSION_ID  = <?= $session_id ?>;
    const IS_CELPIP   = <?= $isCelpip ? 'true' : 'false' ?>;
    const T1_Q = <?= json_encode($task1['question']) ?>;
    const T2_Q = <?= json_encode($task2['question']) ?>;
    const T1_MIN = <?= $t1WordMin ?>;
    const T2_MIN = <?= $t2WordMin ?>;

    let timeLeft = <?= $timeLimit ?>;
    let submitted = false;
    const timerEl = document.getElementById('timerEl');

    function fmtTime(s) { return String(Math.floor(s/60)).padStart(2,'0') + ':' + String(s%60).padStart(2,'0'); }
    function countWords(txt) { return txt.trim() === '' ? 0 : txt.trim().split(/\s+/).length; }

    // IELTS: one shared countdown across both tasks (unchanged behavior).
    const ticker = IS_CELPIP ? null : setInterval(() => {
        timeLeft--;
        timerEl.textContent = fmtTime(timeLeft);
        if (timeLeft <= 300) timerEl.classList.add('warning');
        if (timeLeft <= 0) { clearInterval(ticker); doSubmit(); }
    }, 1000);

    // CELPIP: each task has its own independent countdown, matching the
    // real exam (27 min Task 1, 26 min Task 2) — starting Task 2's clock
    // only once you actually reach it, not counting down in the background.
    let celpipT1Left = <?= $celpipT1Seconds ?>;
    let celpipT2Left = <?= $celpipT2Seconds ?>;
    let celpipTicker = null;

    function celpipFmtMinutes(s) {
        const m = Math.floor(s / 60), sec = s % 60;
        return sec === 0 ? `${m} minute${m === 1 ? '' : 's'}` : `${m} minute${m === 1 ? '' : 's'} ${sec} second${sec === 1 ? '' : 's'}`;
    }

    function celpipStartTaskTimer(n) {
        if (celpipTicker) clearInterval(celpipTicker);
        const el = document.getElementById('celpipWTimer' + n);
        const tick = () => {
            const left = n === 1 ? celpipT1Left : celpipT2Left;
            if (el) el.textContent = celpipFmtMinutes(Math.max(0, left));
        };
        tick();
        celpipTicker = setInterval(() => {
            if (n === 1) celpipT1Left--; else celpipT2Left--;
            tick();
            const left = n === 1 ? celpipT1Left : celpipT2Left;
            if (left <= 0) {
                clearInterval(celpipTicker);
                if (n === 1) celpipWAdvance(); else doSubmit();
            }
        }, 1000);
    }

    function celpipWAdvance() {
        if (celpipTicker) clearInterval(celpipTicker);
        document.getElementById('celpip-w-task-1').style.display = 'none';
        const t2 = document.getElementById('celpip-w-task-2');
        if (t2) { t2.style.display = ''; celpipStartTaskTimer(2); }
        else { doSubmit(); }
    }

    if (IS_CELPIP) celpipStartTaskTimer(1);

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

    // --- Task 1: one real <textarea>, moved between the split/stacked views
    // on switch so both layouts edit the same content (no sync needed). ---
    const wtEssaySlotA = document.getElementById('wtEssaySlotA');
    let essay1;
    if (wtEssaySlotA) {
        essay1 = document.createElement('textarea');
        essay1.id = 'essay1';
        essay1.className = 'essay-area';
        essay1.style.border = 'none';
        essay1.placeholder = 'Begin writing your response here…';
        essay1.addEventListener('input', () => updateWC('essay1', 'wc1', T1_MIN));
        wtEssaySlotA.appendChild(essay1);
    }
    document.getElementById('essay2')?.addEventListener('input', () => updateWC('essay2', 'wc2', T2_MIN));
    // CELPIP: essay1 is a static textarea (no split/stacked switcher, so no
    // dynamic-creation step above ever runs) — attach its listener directly.
    if (IS_CELPIP) document.getElementById('essay1')?.addEventListener('input', () => updateWC('essay1', 'wc1', T1_MIN));

    function wtShowView(which) {
        document.querySelectorAll('.wt-switcher button').forEach(b => b.classList.remove('active'));
        document.getElementById('wt-switch-' + which)?.classList.add('active');
        document.querySelectorAll('.wt-view').forEach(v => v.classList.remove('active'));
        document.getElementById('wt-view-' + which)?.classList.add('active');

        if (!essay1) return;
        const slot = which === 'a' ? document.getElementById('wtEssaySlotA') : document.getElementById('wtEssaySlotB');
        if (slot && essay1.parentElement !== slot) slot.appendChild(essay1);
    }

    function wtToggleAccordion(head) {
        head.classList.toggle('open');
        head.nextElementSibling.classList.toggle('open');
    }

    function wtOpenModal() { document.getElementById('wtModal')?.classList.add('open'); }
    function wtCloseModal() { document.getElementById('wtModal')?.classList.remove('open'); }

    // Draggable divider between the prompt/chart pane and the answer pane.
    (function initWtDivider() {
        const divider = document.getElementById('wtDivider');
        const split = document.getElementById('wtSplit');
        if (!divider || !split) return;
        let dragging = false;

        divider.addEventListener('mousedown', () => { dragging = true; document.body.style.userSelect = 'none'; });
        document.addEventListener('mouseup', () => { dragging = false; document.body.style.userSelect = ''; });
        document.addEventListener('mousemove', (e) => {
            if (!dragging) return;
            const rect = split.getBoundingClientRect();
            let pct = ((e.clientX - rect.left) / rect.width) * 100;
            pct = Math.min(75, Math.max(25, pct));
            split.style.gridTemplateColumns = pct + '% 6px ' + (100 - pct) + '%';
        });
    })();

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
