<?php
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

$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
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

// Group by part (passage)
$parts = [];
foreach ($questions as $q) {
    $parts[(int)($q['part_number'] ?? 1)][] = $q;
}
ksort($parts);

$DURATION_SECS = 20 * 60; // 20 minutes — one short passage

// ── Passage renderer — converts plain-text passage to styled HTML ──────────
function renderPassage(string $text): string {
    $lines  = explode("\n", trim($text));
    $html   = '';
    $first  = true;
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') continue;

        // Title line (first non-empty line of the passage)
        if ($first) {
            $html .= '<p class="passage-title-main">' . htmlspecialchars($line) . '</p>';
            $first = false;
            continue;
        }

        // Pupil label: single letter + two spaces + name  e.g. "A  Krishnan"
        if (preg_match('/^([A-G])\s{2,}(.+)$/', $line, $m)) {
            $html .= '<p class="pupil-name"><span class="para-label">' . htmlspecialchars($m[1]) . '</span>' . htmlspecialchars($m[2]) . '</p>';
            continue;
        }

        // Paragraph label: e.g. "A  In 2017..."
        if (preg_match('/^([A-G])\s{2}(.+)$/', $line, $m)) {
            $html .= '<p><span class="para-label">' . htmlspecialchars($m[1]) . '</span>' . htmlspecialchars($m[2]) . '</p>';
            continue;
        }

        $html .= '<p>' . htmlspecialchars($line) . '</p>';
    }
    return $html;
}

// ── Reading passage — hardcoded by first question number, same pattern as the
//    Full Mock tests. Just one short Academic-style passage for the diagnostic. ──
$passages = [

    1 => <<<'PASSAGE'
The Rise of Urban Beekeeping

Urban beekeeping has grown rapidly in cities around the world over the past two decades. Once considered a purely rural activity, keeping honeybee hives is now common on apartment rooftops, in community gardens, and even on the balconies of city hotels.

One major driver of this trend is growing public concern about declining bee populations. Bees play an essential role in pollinating the crops that make up a large share of the human diet, and widespread colony losses linked to disease, pesticide use, and habitat loss have prompted many city dwellers to take direct action by hosting hives of their own.

Urban environments can, somewhat surprisingly, offer bees real advantages over rural settings. Cities typically contain a wider variety of flowering plants across parks, gardens, and street trees, providing pollen throughout more of the year than a single-crop farm might. Urban areas also tend to use fewer large-scale pesticides than intensive agricultural land, reducing one of the biggest threats to hive health.

However, city beekeeping is not without its challenges. Hives must be carefully positioned to avoid disturbing neighbours, and many municipalities require beekeepers to register their hives and follow strict guidelines on hive placement and flight-path direction. Rooftop apiaries also demand regular access for inspection, which can be logistically difficult in dense buildings.

Despite these obstacles, urban beekeeping associations have multiplied, and many now offer training courses for beginners. Enthusiasts argue that the benefits extend beyond honey production: hives raise public awareness of pollinator health, and the practice has become a popular way for city residents to reconnect with nature.
PASSAGE,

];
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
                <a href="diagnostic_aca_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="diagnostic_aca_reading.php?session_id=<?= $session_id ?>"   style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">📖 Reading</a>
                <a href="diagnostic_aca_writing.php?session_id=<?= $session_id ?>"  style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="diagnostic_aca_speaking.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
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

            <!-- Section tab bar + timer -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php
                    $sectionLabels = [1 => 'Section 1', 2 => 'Section 2', 3 => 'Section 3'];
                    foreach ($parts as $pNum => $pqs):
                        $nums = array_column($pqs, 'question_number');
                        $f = min($nums); $l = max($nums);
                    ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchSection(<?= $pNum ?>, this)">
                        <span class="done-dot"></span>
                        <?= $sectionLabels[$pNum] ?? "Section {$pNum}" ?>
                        <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 60:00</div>
            </div>

            <?php if (empty($questions)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No questions loaded yet. Please run database migration 063 and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="readingForm">
            <?php foreach ($parts as $partNum => $partQuestions):
                $firstQ = $partQuestions[0];
                $lastQ  = end($partQuestions);
            ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>" id="panel-<?= $partNum ?>">

                <?php
                $prevInstructions = null;
                $prevQtext        = null;
                foreach ($partQuestions as $q):
                    $qid   = (int)$q['id'];
                    $qnum  = (int)$q['question_number'];
                    $qtype = $q['question_type'];
                    $qopts = $options[$qid] ?? [];
                ?>

                <?php if (isset($passages[$qnum])): ?>
                <div class="passage-box"><?= renderPassage($passages[$qnum]) ?></div>
                <?php endif; ?>

                <?php if ($q['instructions'] !== $prevInstructions):
                    $prevInstructions = $q['instructions'];
                ?>
                <div class="q-instructions"><?= htmlspecialchars($q['instructions']) ?></div>
                <?php endif; ?>

                <div class="q-block" id="qblock-<?= $qnum ?>">
                    <div style="margin-bottom:.35rem;">
                        <span class="q-badge"><?= $qnum ?></span>
                        <?php if ($q['question_text']): ?>
                        <span class="q-text"><?= nl2br(htmlspecialchars($q['question_text'])) ?></span>
                        <?php endif; ?>
                    </div>

                    <?php if (in_array($qtype, ['multiple_choice_single','multiple_choice_multiple'])): ?>
                        <?php foreach ($qopts as $opt): ?>
                        <label class="mc-option">
                            <input type="<?= $qtype === 'multiple_choice_multiple' ? 'checkbox' : 'radio' ?>"
                                   name="answers[<?= $qnum ?>]"
                                   value="<?= htmlspecialchars($opt['option_label']) ?>"
                                   class="answer-field" data-qnum="<?= $qnum ?>">
                            <strong><?= htmlspecialchars($opt['option_label']) ?>.</strong>&nbsp;<?= htmlspecialchars($opt['option_text']) ?>
                        </label>
                        <?php endforeach; ?>

                    <?php elseif (in_array($qtype, ['true_false_not_given','yes_no_not_given'])): ?>
                        <?php $tfOpts = $qtype === 'true_false_not_given' ? ['TRUE','FALSE','NOT GIVEN'] : ['YES','NO','NOT GIVEN']; ?>
                        <div class="tfng-wrap">
                            <?php foreach ($tfOpts as $tfo): ?>
                            <label class="tfng-opt">
                                <input type="radio" name="answers[<?= $qnum ?>]" value="<?= $tfo ?>"
                                       class="answer-field" data-qnum="<?= $qnum ?>">
                                <?= $tfo ?>
                            </label>
                            <?php endforeach; ?>
                        </div>

                    <?php elseif ($qtype === 'matching'): ?>
                        <select name="answers[<?= $qnum ?>]" class="match-select answer-field" data-qnum="<?= $qnum ?>"
                                onchange="this.classList.toggle('answered',this.value!=='')">
                            <option value="">— Select —</option>
                            <?php foreach ($qopts as $opt): ?>
                            <option value="<?= htmlspecialchars($opt['option_label']) ?>">
                                <?= htmlspecialchars($opt['option_label']) ?>. <?= htmlspecialchars($opt['option_text']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>

                    <?php else: ?>
                        <input type="text" name="answers[<?= $qnum ?>]"
                               class="ff-input answer-field" data-qnum="<?= $qnum ?>"
                               autocomplete="off" placeholder="Write your answer"
                               oninput="this.classList.toggle('answered',this.value.trim()!=='')">
                    <?php endif; ?>
                </div>

                <?php endforeach; ?>
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
        if (rem <= 0) { clearInterval(timerInterval); submitReading(true); }
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
        if (el.type === 'radio'    && el.checked)    ans[n] = el.value;
        if (el.type === 'checkbox' && el.checked)    ans[n] = el.value;
        if (el.tagName === 'SELECT' && el.value)     ans[n] = el.value;
        if (el.type === 'text' && el.value.trim())   ans[n] = el.value.trim();
    });
    return ans;
}

function updateProgress() {
    const ans = collectAnswers();
    let count = Object.keys(ans).length;
    <?php foreach ($parts as $pNum => $pqs): ?>
    (function() {
        const pNums = [<?= implode(',', array_column($pqs,'question_number')) ?>];
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

function submitReading(auto = false) {
    if (submitting) return;
    const ans = collectAnswers();
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
            alert(d.error || 'An error occurred.'); submitting = false; btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.'); submitting = false; btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Writing';
    });
}

function confirmExit() {
    if (confirm('Exit the test? Your progress will be lost.')) window.location.href = 'index.php';
}

window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue=''; } });

startTimer();
updateProgress();
</script>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
