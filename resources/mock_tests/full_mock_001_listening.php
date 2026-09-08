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

// Admins can revisit any section freely; students are forwarded once a section is done
if (!$isAdmin && !is_null($session['listening_attempt_id'])) {
    $map  = require INCLUDES_PATH . '/mock_test_map.php';
    $file = $map[$session['mock_code']]['reading']['file'] ?? 'mock_start.php';
    header("Location: {$file}?session_id={$session_id}");
    exit();
}

// Load questions from DB
$map      = require INCLUDES_PATH . '/mock_test_map.php';
$testCode = $map[$session['mock_code']]['listening']['test_code'] ?? '';

$stmt = $db->prepare("SELECT id FROM tests WHERE code = ? AND is_active = 1 LIMIT 1");
$stmt->execute([$testCode]);
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Listening test not configured. Please contact support.");
}
$test_id = (int)$test['id'];

$stmt = $db->prepare("
    SELECT q.id, q.question_number, q.question_type, q.question_text,
           q.instructions, q.part_number, q.stimulus_text
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

// Compute Q ranges per part
$partRanges = [];
foreach ($parts as $pNum => $pqs) {
    $nums = array_column($pqs, 'question_number');
    $partRanges[$pNum] = [min($nums), max($nums)];
}

$audioBase      = ACADEMY_URL . 'assets/audio/IELTS_FULL_MOCK_001/';
$mapImgUrl      = ACADEMY_URL . 'assets/img/mock_tests/IELTS_FULL_MOCK_001/part2_map.png';
$DURATION_SECS  = 40 * 60; // 30 min audio + 10 min transfer
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listening — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
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
                <a href="full_mock_001_listening.php?session_id=<?= $session_id ?>" style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">🎧 Listening</a>
                <a href="full_mock_001_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                <a href="mock_writing.php?session_id=<?= $session_id ?>"            style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="mock_speaking.php?session_id=<?= $session_id ?>"           style="color:#a5b4fc;text-decoration:none;">🎤 Speaking</a>
            </div>
            <?php endif; ?>
            <div class="d-flex align-items-center justify-content-between">
                <div class="progress-steps">
                    <div class="step current"><div class="step-dot"></div>Listening</div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                    <div class="step"><div class="step-dot"></div>Reading</div>
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

            <!-- Hidden audio element -->
            <audio id="mainAudio" preload="auto">
                <source id="audioSrc" src="<?= htmlspecialchars($audioBase . 'listening_part1.mp3') ?>" type="audio/mpeg">
            </audio>

            <!-- Audio player UI -->
            <div class="audio-box" style="top:<?= $isAdmin ? '110px' : '60px' ?>;">
                <button class="btn-play" onclick="togglePlay()"><i class="bi bi-play-fill" id="playIcon"></i></button>
                <div class="progress-wrap">
                    <input type="range" id="audioBar" min="0" max="100" value="0">
                    <div class="audio-time" id="audioTime">0:00 / 0:00</div>
                </div>
                <div class="vol-wrap">
                    <i class="bi bi-volume-up" id="volIcon" onclick="toggleMute()"></i>
                    <input type="range" min="0" max="1" step="0.05" value="1" oninput="setVol(this.value)">
                </div>
                <div class="preview-pill hidden" id="previewPill"><i class="bi bi-eye me-1"></i>Preview: <strong id="previewNum">30</strong>s</div>
            </div>

            <!-- Part tab bar + timer -->
            <div class="part-tabs-bar">
                <div class="part-tabs-scrollable">
                    <?php foreach ($parts as $pNum => $pqs):
                        [$f, $l] = $partRanges[$pNum];
                        $labels = ['Section 1','Section 2','Section 3','Section 4'];
                    ?>
                    <button class="part-tab-btn <?= $pNum === 1 ? 'active' : '' ?>"
                            id="ptab-<?= $pNum ?>"
                            onclick="switchPart(<?= $pNum ?>, this)">
                        <span class="done-dot"></span>
                        <?= $labels[$pNum - 1] ?? "Part {$pNum}" ?>
                        <span class="tab-qrange">Q<?= $f ?>–<?= $l ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
                <div class="inline-timer" id="inlineTimer"><i class="bi bi-clock-fill"></i> 40:00</div>
            </div>

            <?php if (empty($questions)): ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                No questions loaded yet. Please run database migrations 016 and contact your instructor.
            </div>
            <?php endif; ?>

            <form id="listeningForm">

            <?php foreach ($parts as $partNum => $partQuestions):
                $audioUrls = [
                    1 => $audioBase . 'listening_part1.mp3',
                    2 => $audioBase . 'listening_part2.mp3',
                    3 => $audioBase . 'listening_part3.mp3',
                    4 => $audioBase . 'listening_part4.mp3',
                ];
                [$f, $l] = $partRanges[$partNum];
            ?>
            <div class="part-panel <?= $partNum === 1 ? 'active' : '' ?>"
                 id="panel-<?= $partNum ?>"
                 data-audio="<?= htmlspecialchars($audioUrls[$partNum] ?? '') ?>">

                <div class="audio-notice">
                    <i class="bi bi-info-circle me-1"></i>
                    Recording <?= $partNum ?> — After the instruction audio you will have
                    <strong>30 seconds</strong> to look at questions <?= $f ?>–<?= $l ?>.
                </div>

                <?php if ($partNum === 2): ?>
                <div class="worksheet-img-wrap">
                    <img src="<?= htmlspecialchars($mapImgUrl) ?>" alt="Plan of Stevenson's site">
                    <div class="worksheet-img-caption">Plan of Stevenson's site — use for Questions 15–20</div>
                </div>
                <?php endif; ?>

                <?php
                // Render questions
                $prevInstructions = null;
                $prevStimulus     = null;
                $prevQtype        = null;
                $prevQtext        = null;
                $matchBoxShown    = false;

                foreach ($partQuestions as $q):
                    $qid   = (int)$q['id'];
                    $qnum  = (int)$q['question_number'];
                    $qtype = $q['question_type'];
                    $qopts = $options[$qid] ?? [];

                    // New instruction block when instructions change
                    if ($q['instructions'] !== $prevInstructions):
                        if ($prevInstructions !== null) echo '</div>'; // close prev section-block
                        $prevInstructions = $q['instructions'];
                        $prevStimulus     = null;
                        $matchBoxShown    = false;
                ?>
                <div class="section-block">
                    <p class="q-instructions"><?= $q['instructions'] ?></p>
                <?php endif; ?>

                <?php
                    // Stimulus heading (form title + optional sub-headings). Scoped to
                    // form_note_completion only — matching questions also carry stimulus_text
                    // (it gates the shared A/B/C legend box below) and would otherwise get
                    // this title box too.
                    // A stimulus_text of "Main Title||Sub Heading" renders the first part as
                    // the form's boxed title (only on the first stimulus of the section) and
                    // the rest as left-accented sub-headings — lets one set of notes carry a
                    // top-level title plus grouped sections (e.g. "Tiny Engineers" /
                    // "Junior Engineers") without any further template changes.
                    if ($qtype === 'form_note_completion' && !empty($q['stimulus_text']) && $q['stimulus_text'] !== $prevStimulus):
                        $isFirstStimulusInBlock = ($prevStimulus === null);
                        $prevStimulus = $q['stimulus_text'];
                        $stimParts = explode('||', $q['stimulus_text']);
                ?>
                    <?php if ($isFirstStimulusInBlock): ?>
                        <div class="ff-title"><?= htmlspecialchars($stimParts[0]) ?></div>
                        <?php if (isset($stimParts[1])): ?>
                            <div class="ff-subtitle"><?= htmlspecialchars($stimParts[1]) ?></div>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php foreach ($stimParts as $stimPart): ?>
                            <div class="ff-subtitle"><?= htmlspecialchars($stimPart) ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($qtype === 'form_note_completion'): ?>
                    <div class="ff-sentence">
                        <?php
                        $escaped  = htmlspecialchars($q['question_text']);
                        $badge    = '<span class="q-badge">'.$qnum.'</span>';
                        $input    = '<input type="text" name="answers['.$qnum.']"
                                           class="ff-input answer-field"
                                           data-qnum="'.$qnum.'"
                                           autocomplete="off"
                                           oninput="this.classList.toggle(\'answered\',this.value.trim()!==\'\')">';
                        echo str_replace('___', $badge.$input, $escaped);
                        ?>
                    </div>

                <?php elseif ($qtype === 'diagram_map_labelling'): ?>
                    <div class="map-row">
                        <span class="q-badge"><?= $qnum ?></span>
                        <span class="map-row-label"><?= htmlspecialchars($q['question_text']) ?></span>
                        <input type="text" name="answers[<?= $qnum ?>]"
                               class="ff-input answer-field"
                               data-qnum="<?= $qnum ?>"
                               autocomplete="off"
                               style="width:60px; text-align:center; text-transform:uppercase;"
                               oninput="this.classList.toggle('answered',this.value.trim()!=='')">
                    </div>

                <?php elseif (in_array($qtype, ['multiple_choice_single','multiple_choice_multiple'])): ?>
                    <?php if ($q['question_text'] !== $prevQtext): $prevQtext = $q['question_text']; ?>
                    <div class="mc-question">
                        <div class="mc-q-label">
                            <span class="q-badge"><?= $qnum ?></span>
                            <?= htmlspecialchars($q['question_text']) ?>
                        </div>
                        <?php foreach ($qopts as $opt): ?>
                        <label class="mc-option">
                            <input type="<?= $qtype === 'multiple_choice_multiple' ? 'checkbox' : 'radio' ?>"
                                   name="answers[<?= $qnum ?>]"
                                   value="<?= htmlspecialchars($opt['option_label']) ?>"
                                   class="answer-field"
                                   data-qnum="<?= $qnum ?>">
                            <strong><?= htmlspecialchars($opt['option_label']) ?></strong>&nbsp;
                            <?= htmlspecialchars($opt['option_text']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php else: // same question stem, just add more options ?>
                    <div class="mc-question" style="margin-top:-.5rem;">
                        <div class="mc-q-label"><span class="q-badge"><?= $qnum ?></span> (select another answer)</div>
                        <?php foreach ($qopts as $opt): ?>
                        <label class="mc-option">
                            <input type="checkbox"
                                   name="answers[<?= $qnum ?>]"
                                   value="<?= htmlspecialchars($opt['option_label']) ?>"
                                   class="answer-field"
                                   data-qnum="<?= $qnum ?>">
                            <strong><?= htmlspecialchars($opt['option_label']) ?></strong>&nbsp;
                            <?= htmlspecialchars($opt['option_text']) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                <?php elseif ($qtype === 'matching'): ?>
                    <?php if (!$matchBoxShown && !empty($q['stimulus_text'])):
                        $matchBoxShown = true;
                        // Build meanings list from options of first matching question
                        $firstOpts = $qopts;
                    ?>
                    <div class="match-box">
                        <div class="match-box-title">Personal meanings</div>
                        <div class="match-box-grid">
                            <?php foreach ($firstOpts as $opt): ?>
                            <div><span class="match-key"><?= htmlspecialchars($opt['option_label']) ?></span>
                                 &nbsp;<?= htmlspecialchars($opt['option_text']) ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="match-q">
                        <span class="q-badge"><?= $qnum ?></span>
                        <span class="match-q-text"><?= htmlspecialchars($q['question_text']) ?></span>
                        <input type="text"
                               name="answers[<?= $qnum ?>]"
                               class="match-input answer-field"
                               data-qnum="<?= $qnum ?>"
                               maxlength="1"
                               autocomplete="off"
                               oninput="this.classList.toggle('answered',this.value.trim()!=='')">
                    </div>

                <?php endif; ?>

                <?php endforeach; ?>
                </div><!-- end last section-block -->

            </div><!-- end part-panel -->
            <?php endforeach; ?>

            </form>
        </div><!-- end section-content -->

        <div style="height:80px;"></div>
    </main>
</div><!-- end main-wrapper -->

<?php include INCLUDES_PATH . '/adverts.php'; ?>

<div class="submit-bar">
    <div style="display:flex; align-items:center; gap:.5rem;">
        <i class="bi bi-check2-circle text-success fs-5"></i>
        <span id="answeredCount" style="font-size:.9rem; font-weight:600; color:#374151;">0 of <?= count($questions) ?> answered</span>
    </div>
    <button type="button" class="btn btn-primary fw-bold px-4" id="submitBtn" onclick="submitListening()">
        <i class="bi bi-arrow-right-circle me-2"></i>Submit &amp; Continue to Reading
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

const audio      = document.getElementById('mainAudio');
const audioSrc   = document.getElementById('audioSrc');
const playIcon   = document.getElementById('playIcon');
const audioBar   = document.getElementById('audioBar');
const audioTime  = document.getElementById('audioTime');
const timerEl    = document.getElementById('inlineTimer');

// ── Timer ─────────────────────────────────────────────────
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
        if (rem <= 0) { clearInterval(timerInterval); submitListening(true); }
    }, 1000);
}

// ── Audio ─────────────────────────────────────────────────
function togglePlay() {
    if (audio.paused) { audio.play(); playIcon.className = 'bi bi-pause-fill'; }
    else { audio.pause(); playIcon.className = 'bi bi-play-fill'; }
}
function toggleMute() {
    audio.muted = !audio.muted;
    document.getElementById('volIcon').className = audio.muted ? 'bi bi-volume-mute' : 'bi bi-volume-up';
}
function setVol(v) { audio.volume = v; }
audio.addEventListener('timeupdate', () => {
    if (audio.duration) {
        audioBar.value = (audio.currentTime / audio.duration) * 100;
        audioTime.textContent = fmt(Math.floor(audio.currentTime)) + ' / ' + fmt(Math.floor(audio.duration));
    }
});
audio.addEventListener('ended', () => { playIcon.className = 'bi bi-play-fill'; });

// ── Part switching ─────────────────────────────────────────
function switchPart(pNum, btn) {
    document.querySelectorAll('.part-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.part-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + pNum).classList.add('active');
    btn.classList.add('active');

    // Load part audio
    const panel = document.getElementById('panel-' + pNum);
    const src   = panel.dataset.audio;
    if (src && audioSrc.src !== src) {
        audio.pause();
        playIcon.className = 'bi bi-play-fill';
        audioSrc.src = src;
        audio.load();
        audioBar.value = 0;
        audioTime.textContent = '0:00 / 0:00';
    }
}

// ── Progress ───────────────────────────────────────────────
function collectAnswers() {
    const ans = {};
    document.querySelectorAll('.answer-field').forEach(el => {
        const n = el.dataset.qnum;
        if (el.type === 'radio'    && el.checked && el.value.trim()) ans[n] = el.value;
        if (el.type === 'checkbox' && el.checked && el.value.trim()) ans[n] = el.value;
        if (el.type === 'text'     && el.value.trim())               ans[n] = el.value.trim();
    });
    return ans;
}
function updateProgress() {
    const ans = collectAnswers();
    let count = Object.keys(ans).length;
    // Mark tab done-dot
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

// ── Submit ─────────────────────────────────────────────────
function submitListening(auto = false) {
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
        body: JSON.stringify({ session_id: SESSION_ID, section: 'listening', time_spent: elapsed, answers: ans })
    })
    .then(r => r.json())
    .then(d => {
        if (d.redirect) { window.location.href = d.redirect; }
        else {
            alert(d.error || 'An error occurred. Please try again.');
            submitting = false; btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Reading';
        }
    })
    .catch(() => {
        alert('Network error. Please try again.');
        submitting = false; btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-arrow-right-circle me-2"></i>Submit & Continue to Reading';
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
