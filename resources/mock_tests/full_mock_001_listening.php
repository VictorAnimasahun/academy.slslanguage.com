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
    <style>
        /* ── Audio player ───────────────────────────────────── */
        .audio-box { background:#1a2236; border-radius:10px; padding:.75rem 1rem; display:flex; align-items:center; gap:.75rem; margin-bottom:1.75rem; }
        .btn-play  { width:34px; height:34px; border-radius:50%; border:none; background:#667eea; color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0; transition:background .2s; }
        .btn-play:hover { background:#764ba2; }
        .progress-wrap { flex:1; }
        .progress-wrap input[type=range] { width:100%; accent-color:#667eea; pointer-events:none; display:block; }
        .audio-time { font-size:.7rem; color:rgba(255,255,255,.5); text-align:right; }
        .vol-wrap { display:flex; align-items:center; gap:.4rem; }
        .vol-wrap i { font-size:.9rem; color:rgba(255,255,255,.6); cursor:pointer; }
        .vol-wrap input[type=range] { width:60px; accent-color:#667eea; }
        .preview-pill { background:#fef3c7; border-radius:20px; padding:.2rem .75rem; font-size:.75rem; color:#92400e; white-space:nowrap; flex-shrink:0; }
        .preview-pill.hidden { display:none; }

        /* ── Part tabs ──────────────────────────────────────── */
        .part-tabs-bar { display:flex; align-items:center; border-bottom:1px solid #e5e7eb; margin-bottom:1.5rem; }
        .part-tabs-scrollable { display:flex; flex:1; overflow-x:auto; }
        .part-tab-btn { display:flex; flex-direction:column; align-items:center; padding:.5rem 1.1rem; border:none; border-bottom:3px solid transparent; background:transparent; cursor:pointer; font-size:.82rem; font-weight:600; color:#6b7280; transition:all .2s; gap:1px; position:relative; white-space:nowrap; }
        .part-tab-btn:hover { color:#667eea; }
        .part-tab-btn.active { color:#667eea; border-bottom-color:#667eea; }
        .tab-qrange { font-size:.67rem; color:#9ca3af; }
        .part-tab-btn.active .tab-qrange { color:#a5b4fc; }
        .done-dot { position:absolute; top:5px; right:6px; width:7px; height:7px; border-radius:50%; background:#10b981; display:none; }
        .part-tab-btn.all-answered .done-dot { display:block; }
        .inline-timer { display:flex; align-items:center; gap:.35rem; font-size:.88rem; font-weight:700; color:#facc15; background:#1a2236; border-radius:8px; padding:.3rem .85rem; margin-left:auto; flex-shrink:0; white-space:nowrap; }
        .inline-timer.warning { color:#ef4444; animation:blink 1s infinite; }
        @keyframes blink { 0%,100%{opacity:1}50%{opacity:.4} }

        /* ── Layout ─────────────────────────────────────────── */
        .section-content { background:#fff; border-radius:12px; padding:2rem; box-shadow:0 4px 20px rgba(0,0,0,.06); }
        .test-header-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; }
        .test-meta { display:flex; align-items:center; gap:.5rem; }
        .test-meta-tag { font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:#9ca3af; }
        .test-meta-name { font-size:.9rem; font-weight:700; color:#1a2236; }
        .btn-exit { background:#f3f4f6; border:1.5px solid #e5e7eb; color:#374151; border-radius:6px; padding:.28rem .85rem; font-size:.8rem; font-weight:600; cursor:pointer; transition:all .2s; }
        .btn-exit:hover { background:#ef4444; border-color:#ef4444; color:#fff; }
        .part-panel { display:none; }
        .part-panel.active { display:block; }

        /* ── Audio notice ───────────────────────────────────── */
        .audio-notice { background:#eff6ff; border-left:4px solid #3b82f6; border-radius:0 8px 8px 0; padding:.65rem 1rem; font-size:.84rem; color:#1e40af; margin-bottom:1.5rem; line-height:1.5; }

        /* ── Worksheet image ────────────────────────────────── */
        .worksheet-img-wrap { margin-bottom:1.75rem; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; background:#f9fafb; text-align:center; }
        .worksheet-img-wrap img { max-height:320px; width:auto; max-width:100%; display:inline-block; }
        .worksheet-img-caption { font-size:.75rem; color:#9ca3af; text-align:center; padding:.4rem .75rem; background:#f3f4f6; border-top:1px solid #e5e7eb; }

        /* ── Question blocks ────────────────────────────────── */
        .section-block { margin-bottom:2.5rem; }
        .q-range-label { font-size:.95rem; font-weight:700; color:#111827; margin-bottom:.2rem; }
        .q-instructions { font-size:.88rem; color:#374151; margin-bottom:1.2rem; line-height:1.55; }

        /* Form / note fill */
        .ff-title { text-align:center; font-weight:700; font-size:.95rem; color:#374151; margin-bottom:.75rem; padding:.5rem 1rem; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; }
        .ff-sentence { font-size:.875rem; color:#374151; margin-bottom:.65rem; line-height:1.75; }
        .q-badge { display:inline-flex; align-items:center; justify-content:center; background:#667eea; color:#fff; font-size:.68rem; font-weight:700; border-radius:4px; min-width:20px; height:18px; padding:0 4px; margin-right:3px; vertical-align:middle; }
        .ff-input { border:none; border-bottom:2px solid #c4b5fd; outline:none; width:150px; font-size:.87rem; padding:2px 4px; background:transparent; color:#111827; transition:border-color .2s; vertical-align:middle; }
        .ff-input:focus { border-bottom-color:#667eea; }
        .ff-input.answered { border-bottom-color:#10b981; }

        /* Diagram map labelling */
        .map-row { display:flex; align-items:center; gap:.6rem; margin-bottom:.55rem; font-size:.875rem; color:#374151; }
        .map-row-label { min-width:180px; font-weight:500; }

        /* Multiple choice */
        .mc-question { margin-bottom:1.25rem; }
        .mc-q-label { font-size:.88rem; font-weight:600; color:#111827; margin-bottom:.5rem; }
        .mc-option { display:flex; align-items:flex-start; gap:.5rem; padding:.38rem .65rem; border-radius:7px; cursor:pointer; font-size:.85rem; color:#374151; transition:background .15s; margin-bottom:.15rem; line-height:1.4; }
        .mc-option:hover { background:#f3f4f6; }
        .mc-option input { accent-color:#667eea; margin-top:3px; flex-shrink:0; }

        /* Matching */
        .match-box { background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; padding:.75rem 1rem; margin-bottom:1.25rem; font-size:.84rem; color:#374151; }
        .match-box-title { font-weight:700; margin-bottom:.5rem; font-size:.85rem; color:#111827; }
        .match-box-grid { display:grid; grid-template-columns:1fr 1fr; gap:.2rem .5rem; }
        .match-key { font-weight:700; color:#667eea; }
        .match-q { display:flex; align-items:center; gap:.75rem; margin-bottom:.55rem; font-size:.875rem; color:#374151; }
        .match-q-text { flex:1; }
        .match-input { border:none; border-bottom:2px solid #c4b5fd; outline:none; width:48px; font-size:.87rem; padding:2px 4px; background:transparent; color:#111827; text-align:center; text-transform:uppercase; transition:border-color .2s; }
        .match-input:focus { border-bottom-color:#667eea; }
        .match-input.answered { border-bottom-color:#10b981; }

        /* Submit bar — fixed to viewport bottom, respects sidebars */
        .submit-bar {
            position: fixed;
            bottom: 0;
            left: var(--sidebar-w, 220px);
            right: 280px; /* advert sidebar */
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: .85rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            z-index: 200;
            box-shadow: 0 -2px 8px rgba(0,0,0,.06);
        }
        @media (max-width: 1399px) { .submit-bar { right: 0; } }
        @media (max-width: 1199px) { .submit-bar { left: 0; right: 0; } }
    </style>
</head>
<body>

<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-3" style="max-width:900px; margin:0 auto;">

        <!-- Header row -->
        <div class="test-header-row">
            <div class="test-meta">
                <span class="test-meta-tag">Mock Test</span>
                <span class="test-meta-name"><?= htmlspecialchars($session['mock_title']) ?> — <i class="bi bi-headphones me-1"></i>Listening</span>
            </div>
            <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
        </div>

        <?php if ($isAdmin): ?>
        <div style="background:#1e1b4b; color:#c7d2fe; padding:.6rem 1.25rem; border-radius:10px; margin-bottom:1rem; display:flex; align-items:center; gap:1.5rem; font-size:.82rem; font-weight:600;">
            <span style="color:#a5b4fc; text-transform:uppercase; letter-spacing:.08em; font-size:.7rem;">Admin Preview</span>
            <a href="full_mock_001_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc; text-decoration:none;">🎧 Listening</a>
            <a href="full_mock_001_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc; text-decoration:none;">📖 Reading</a>
            <a href="mock_writing.php?session_id=<?= $session_id ?>"            style="color:#a5b4fc; text-decoration:none;">✍️ Writing</a>
            <a href="mock_speaking.php?session_id=<?= $session_id ?>"           style="color:#a5b4fc; text-decoration:none;">🎤 Speaking</a>
        </div>
        <?php endif; ?>

        <div class="section-content">

            <!-- Hidden audio element -->
            <audio id="mainAudio" preload="auto">
                <source id="audioSrc" src="<?= htmlspecialchars($audioBase . 'listening_part1.mp3') ?>" type="audio/mpeg">
            </audio>

            <!-- Audio player UI -->
            <div class="audio-box">
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
                    // Stimulus heading (form title, etc.)
                    if (!empty($q['stimulus_text']) && $q['stimulus_text'] !== $prevStimulus):
                        $prevStimulus = $q['stimulus_text'];
                ?>
                    <div class="ff-title"><?= htmlspecialchars($q['stimulus_text']) ?></div>
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
