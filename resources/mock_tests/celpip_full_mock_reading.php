<?php
/**
 * CELPIP Full Mock — Reading.
 * Generic across CELPIP_FULL_MOCK_A / CELPIP_FULL_MOCK_B (and any future CELPIP
 * full mock added to mock_test_map.php) — the DB test_code and timer duration
 * are derived from the session's mock_code, exactly like full_mock_00N_reading.php
 * does for IELTS. Do not hardcode a specific mock code anywhere in this file.
 *
 * CELPIP Reading has 4 parts, all seeded (migration 073) as multiple_choice_single
 * except Part 3 (part_number=3), which is 'matching' (paragraph A-E matching).
 * Unlike the IELTS templates, passage/table text is NOT hardcoded here — it comes
 * straight from questions.stimulus_text (set on the first question of a block that
 * shares it, NULL after) and is rendered as plain text (nl2br + htmlspecialchars).
 * This also covers Part 2's "diagram" (a travel-options/business-card table), which
 * is authored as structured plain text, not an image.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$session_id = (int)($_GET['session_id'] ?? 0);
if (!$session_id) {
    header("Location: mock_start.php");
    exit();
}

$student_id  = (int)$_SESSION['user_id'];
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin     = is_platform_admin();

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

// Admins can revisit freely; students are forwarded once done
if (!$isAdmin && !is_null($session['reading_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['writing']['file'] ?? 'mock_writing.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Students must complete listening first; admins can skip ahead
if (!$isAdmin && is_null($session['listening_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['listening']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Load questions from DB
$map      = require INCLUDES_PATH . '/mock_test_map.php';
$testCode = $map[$session['mock_code']]['reading']['test_code'] ?? '';

$stmt = $db->prepare("SELECT id, duration_minutes FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Reading test not configured. Please contact support.");
}
$test_id = (int)$test['id'];

$stmt = $db->prepare("
    SELECT q.id, q.question_number, q.question_type, q.question_text, q.instructions, q.part_number, q.stimulus_text
    FROM questions q
    WHERE q.test_id = ?
    ORDER BY q.question_number
");
$stmt->execute([$test_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $db->prepare("
    SELECT qo.question_id, qo.option_label, qo.option_text, qo.display_order
    FROM question_options qo
    JOIN questions q ON q.id = qo.question_id
    WHERE q.test_id = ?
    ORDER BY q.question_number, qo.display_order
");
$stmt->execute([$test_id]);
$optionsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
$options = [];
foreach ($optionsRaw as $o) {
    $options[(int)$o['question_id']][] = $o;
}

// Only render questions that have actual content entered
$questions = array_values(array_filter($questions, fn($q) => trim($q['question_text'] ?? '') !== ''));

// Group by part
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);

$DURATION_SECS = (int)($test['duration_minutes'] ?? 55) * 60;

// A fill-in-the-blank style MC question (e.g. Part 1's reply-letter blanks,
// Part 2's email/diagram blanks, Part 4's reader-comment blanks — all stored
// as plain multiple_choice_single rows per migration 073) renders as a
// dropdown instead of a radio list — same convention as the Listening page.
// Detected generically by a run of 2+ underscores in the question text.
if (!function_exists('celpipIsBlankStyle')) {
    function celpipIsBlankStyle(string $text): bool
    {
        return (bool) preg_match('/_{2,}/', $text);
    }
}

// Custom dropdown widget matching the real CELPIP interface: a small trigger
// field that opens a floating panel of radio-style options, and collapses to
// show the chosen answer as bold inline text once selected (see the
// instructor-provided reference screenshots) -- a native <select> can't be
// styled this way, so this builds the whole thing from a hidden input +
// JS-driven trigger/panel (wired up once for the whole page, see the
// celpip-dd-* delegated listeners in the page script).
function renderCelpipDropdown(int $qnum, array $qopts): void
{
    ?>
    <span class="celpip-dd" data-qnum="<?= $qnum ?>">
        <button type="button" class="celpip-dd-trigger" data-role="dd-trigger">
            <span data-role="dd-label">— Select —</span> <i class="bi bi-caret-down-fill"></i>
        </button>
        <input type="hidden" name="answers[<?= $qnum ?>]" class="answer-field celpip-dd-value" data-qnum="<?= $qnum ?>" value="">
        <span class="celpip-dd-panel" data-role="dd-panel" hidden>
            <?php foreach ($qopts as $opt): ?>
            <label class="celpip-dd-option">
                <input type="radio" name="celpip_dd_radio_<?= $qnum ?>" value="<?= htmlspecialchars($opt['option_label']) ?>" data-text="<?= htmlspecialchars($opt['option_label'] . '. ' . $opt['option_text']) ?>">
                <?= htmlspecialchars($opt['option_label']) ?>.&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
            </label>
            <?php endforeach; ?>
        </span>
    </span>
    <?php
}

// Renders one Reading question: a dropdown for 'matching' rows and
// blank-style MC rows, otherwise a standard radio-button MC block.
function renderCelpipReadingQuestion(array $q, array $options): void
{
    $qid     = (int)$q['id'];
    $qnum    = (int)$q['question_number'];
    $qtype   = $q['question_type'];
    $qopts   = $options[$qid] ?? [];
    $isBlank = in_array($qtype, ['multiple_choice_single', 'multiple_choice_multiple'], true)
             && celpipIsBlankStyle($q['question_text'] ?? '');
    ?>
    <?php if ($isBlank): ?>
        <!-- Blank-style: the dropdown is embedded inline where the "___" was,
             matching the real interface (e.g. "1. Adam now lives [dropdown]"),
             not shown as literal underscores with a separate control below. -->
        <div class="q-block" id="qblock-<?= $qnum ?>">
            <div class="celpip-inline-q">
                <span class="q-badge"><?= $qnum ?></span>
                <?php
                $escaped = htmlspecialchars($q['question_text']);
                ob_start();
                renderCelpipDropdown($qnum, $qopts);
                $ddHtml = ob_get_clean();
                echo preg_replace('/_{2,}/', $ddHtml, $escaped, 1);
                ?>
            </div>
        </div>
    <?php else: ?>
    <div class="q-block" id="qblock-<?= $qnum ?>">
        <div style="margin-bottom:.3rem;">
            <span class="q-badge"><?= $qnum ?></span>
            <?php if (trim($q['question_text'] ?? '')): ?>
            <span class="q-text"><?= nl2br(htmlspecialchars($q['question_text'])) ?></span>
            <?php endif; ?>
        </div>

        <?php if ($qtype === 'matching'): ?>
            <?php renderCelpipDropdown($qnum, $qopts); ?>

        <?php elseif (in_array($qtype, ['multiple_choice_single', 'multiple_choice_multiple'], true)): ?>
            <?php foreach ($qopts as $opt): ?>
            <label class="mc-option">
                <input type="<?= $qtype === 'multiple_choice_multiple' ? 'checkbox' : 'radio' ?>"
                       name="answers[<?= $qnum ?>]"
                       value="<?= htmlspecialchars($opt['option_label']) ?>"
                       class="answer-field" data-qnum="<?= $qnum ?>">
                <strong><?= htmlspecialchars($opt['option_label']) ?>.</strong>&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
            </label>
            <?php endforeach; ?>

        <?php else: // fallback for any future non-MC/matching type ?>
            <input type="text"
                   name="answers[<?= $qnum ?>]"
                   class="ff-input answer-field"
                   data-qnum="<?= $qnum ?>"
                   autocomplete="off"
                   placeholder="Your answer"
                   oninput="this.classList.toggle('answered',this.value.trim()!=='')">
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <style>
        /* Real CELPIP Reading layout: passage left, questions right, each
           scrolling independently within a fixed-height split view. */
        .celpip-reading-split {
            display: flex; gap: 1.25rem; align-items: stretch;
            height: calc(100vh - 260px); min-height: 420px;
        }
        .celpip-reading-pane {
            flex: 1; min-width: 0; overflow-y: auto;
            background: var(--exam-surface); border: 1px solid var(--exam-line);
            border-radius: var(--exam-radius-lg); padding: 1.25rem 1.5rem;
        }
        /* The pane itself already provides the border/background/scroll —
           strip passage-box's own copy of those so passages don't render as
           a nested box-within-a-box. */
        .celpip-passage-pane .passage-box {
            background: none; border: none; padding: 0; max-height: none; overflow: visible;
            margin-bottom: 1.5rem;
        }
        .celpip-passage-pane .passage-box:last-child { margin-bottom: 0; }
        @media (max-width: 900px) {
            .celpip-reading-split { flex-direction: column; height: auto; }
            .celpip-reading-pane { height: 50vh; }
        }

        /* Custom dropdown widget matching the real CELPIP interface: a small
           trigger that opens a floating panel of radio-style options, then
           collapses to show the chosen answer as bold inline text. */
        .celpip-inline-q { line-height: 2.4; }
        .celpip-dd { position: relative; display: inline-block; margin: 0 .25rem; }
        .celpip-dd-trigger {
            background: var(--exam-surface); border: 1px solid var(--exam-accent);
            border-radius: var(--exam-radius); padding: .25rem .7rem; font-size: .85rem;
            color: var(--exam-ink-muted); cursor: pointer; display: inline-flex;
            align-items: center; gap: .4rem; min-width: 90px;
        }
        .celpip-dd-trigger.answered { color: var(--exam-ink); font-weight: 700; border-color: var(--exam-good); }
        .celpip-dd-trigger .bi { font-size: .65rem; color: var(--exam-ink-muted); }
        .celpip-dd-panel {
            position: absolute; z-index: 50; top: calc(100% + 4px); left: 0; min-width: 260px;
            background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg);
            box-shadow: 0 8px 24px rgba(0,0,0,.12); padding: .5rem 0;
        }
        .celpip-dd-option {
            display: flex; align-items: flex-start; gap: .5rem; padding: .5rem .9rem;
            font-size: .85rem; font-weight: 400; cursor: pointer; white-space: normal;
        }
        .celpip-dd-option:hover { background: var(--exam-bg); }
        .celpip-dd-option input { accent-color: var(--exam-accent); margin-top: 3px; flex-shrink: 0; }
        /* Standalone dropdowns (Part 3 matching, not embedded in a sentence)
           should block-stack like the old select did. */
        .q-block > .celpip-dd { display: block; margin: 0; }
        .q-block > .celpip-dd .celpip-dd-trigger { min-width: 220px; }
    </style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3">

        <div class="sticky-header">
            <?php if ($isAdmin): ?>
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:8px;margin-bottom:.5rem;display:flex;align-items:center;gap:1.5rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview</span>
                <a href="celpip_full_mock_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="celpip_full_mock_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
                <a href="mock_writing.php?session_id=<?= $session_id ?>"               style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="mock_speaking.php?session_id=<?= $session_id ?>"              style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <div class="progress-steps">
                    <div class="step done"><div class="step-dot"></div>Listening</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step current"><div class="step-dot"></div>Reading</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Writing</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Speaking</div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted"><?= htmlspecialchars($session['mock_title']) ?></small>
                    <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
                </div>
            </div>
        </div>

        <div class="section-content" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <!-- Part tab bar + timer -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php foreach ($parts as $pNum => $pqs):
                        $nums = array_column($pqs, 'question_number');
                        $f = min($nums); $l = max($nums);
                    ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchSection(<?= $pNum ?>, this)">
                        <span class="done-dot"></span>
                        Part <?= $pNum ?>
                        <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 00:00</div>
            </div>

            <?php if (empty($questions)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No questions loaded yet. Please run the database migration for this test and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="readingForm">
            <?php foreach ($parts as $partNum => $partQuestions): ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>">
                <!-- Real CELPIP Reading layout: passage on the left, questions on the
                     right, each scrolling independently — not a single top-to-bottom
                     column. See "CELPIP General Complete Guide" reference screenshots. -->
                <div class="celpip-reading-split">
                    <div class="celpip-reading-pane celpip-passage-pane">
                        <?php
                        $prevStim = null;
                        foreach ($partQuestions as $q):
                            if (!empty($q['stimulus_text']) && $q['stimulus_text'] !== $prevStim):
                                $prevStim = $q['stimulus_text'];
                                echo '<div class="passage-box">' . nl2br(htmlspecialchars($q['stimulus_text'])) . '</div>';
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <div class="celpip-reading-pane celpip-questions-pane">
                        <?php
                        $prevInstr = null;
                        foreach ($partQuestions as $q):
                            // Instructions bar — set on the first question of a block that
                            // shares it, NULL after. Render once per block, deduped.
                            if (!empty($q['instructions']) && $q['instructions'] !== $prevInstr):
                                $prevInstr = $q['instructions'];
                                echo '<div class="q-instructions">' . htmlspecialchars($q['instructions']) . '</div>';
                            endif;

                            renderCelpipReadingQuestion($q, $options);
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            </form>

            <div style="height:80px;"></div>
        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<div class="submit-bar">
    <div style="display:flex;align-items:center;gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem;font-weight:600;color:#374151;">0 of <?= count($questions) ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitReading()">
        <i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>

<script>
const DURATION   = <?= $DURATION_SECS ?>;
const SESSION_ID = <?= $session_id ?>;
const totalQs    = <?= count($questions) ?>;
let elapsed      = 0;
let timerInterval;
let submitting   = false;

const timerEl = document.getElementById('inlineTimer');

function fmt(sec) {
    return String(Math.floor(sec/60)).padStart(2,'0') + ':' + String(sec%60).padStart(2,'0');
}
function startTimer() {
    timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(DURATION);
    timerInterval = setInterval(() => {
        elapsed++;
        const rem = DURATION - elapsed;
        timerEl.querySelector('i').nextSibling.textContent = ' ' + fmt(Math.max(0, rem));
        if (rem <= 300) timerEl.classList.add('warning');
        if (rem <= 0)   { clearInterval(timerInterval); submitReading(true); }
    }, 1000);
}

function switchSection(pNum, btn) {
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    btn.classList.add('active');
}

function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (!n) return;
        if (el.type === 'radio'    && el.checked)          ans[n] = el.value;
        if (el.type === 'checkbox' && el.checked)          ans[n] = el.value;
        if (el.tagName === 'SELECT' && el.value)           ans[n] = el.value;
        if (el.type === 'text'     && el.value.trim())     ans[n] = el.value.trim();
        if (el.type === 'hidden'   && el.value)            ans[n] = el.value;
    });
    return ans;
}

function updateProgress() {
    const ans   = collectAnswers();
    const count = Object.keys(ans).length;
    <?php foreach ($parts as $pNum => $pqs): ?>
    (function() {
        const pNums   = [<?= implode(',', array_column($pqs,'question_number')) ?>];
        const allDone = pNums.every(n => ans[n]);
        document.getElementById('ptab-<?= $pNum ?>').classList.toggle('all-answered', allDone);
    })();
    <?php endforeach; ?>
    document.getElementById('answeredCount').textContent = count + ' of ' + totalQs + ' answered';
}
document.querySelectorAll('.answer-field').forEach(el => {
    el.addEventListener('input',  updateProgress);
    el.addEventListener('change', updateProgress);
});

// ── Custom dropdown widget (celpip-dd) ──────────────────────────────
// Delegated listeners: opens/closes the floating options panel, and
// collapses to bold inline text once an option is chosen.
function closeAllDropdowns(except = null) {
    document.querySelectorAll('.celpip-dd-panel').forEach(p => { if (p !== except) p.hidden = true; });
}
document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.celpip-dd-trigger');
    if (trigger) {
        const dd = trigger.closest('.celpip-dd');
        const panel = dd.querySelector('[data-role="dd-panel"]');
        const willOpen = panel.hidden;
        closeAllDropdowns();
        panel.hidden = !willOpen;
        return;
    }
    const option = e.target.closest('.celpip-dd-option');
    if (option) {
        const dd = option.closest('.celpip-dd');
        const radio = option.querySelector('input[type=radio]');
        const hidden = dd.querySelector('.celpip-dd-value');
        const label = dd.querySelector('[data-role="dd-label"]');
        radio.checked = true;
        hidden.value = radio.value;
        label.textContent = radio.dataset.text;
        dd.querySelector('.celpip-dd-trigger').classList.add('answered');
        closeAllDropdowns();
        hidden.dispatchEvent(new Event('change', { bubbles: true }));
        return;
    }
    if (!e.target.closest('.celpip-dd-panel')) closeAllDropdowns();
});

function submitReading(auto = false) {
    if (submitting) return;
    const ans     = collectAnswers();
    const missing = totalQs - Object.keys(ans).length;
    if (!auto && missing > 0) {
        if (!confirm(`You have ${missing} unanswered question(s). Submit anyway?`)) return;
    }
    submitting = true;
    clearInterval(timerInterval);
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Submitting…';

    fetch('mock_save_section.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_id: SESSION_ID, section: 'reading', time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.redirect) { window.location.href = d.redirect; }
        else {
            alert(d.error || 'An error occurred.');
            submitting = false;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false;
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Writing';
    });
}

function confirmExit() {
    if (confirm('Exit the test? Your progress will be lost.')) window.location.href = 'index.php';
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

startTimer();
updateProgress();
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
