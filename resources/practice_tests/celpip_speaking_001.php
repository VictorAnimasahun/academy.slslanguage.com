<?php
// CELPIP Speaking Practice 1 — transcribed from Downloads/CELPIP TASKS/Celpip Speaking/Speaking Test 1
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$testCode = 'CELPIP_PT_S_001';

$tasks = [
    1 => [
        'title'  => 'Task 1: Giving Advice',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "Your friend is going to have a job interview for the first time. Advise him how it would be and also share your experiences with him.",
    ],
    2 => [
        'title'  => 'Task 2: Talking about a Personal Experience',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Talk about your favorite travel experience. You can talk about a family holiday, a business trip, or any vacation you had with your friends. Where did you go, when and why was it the most memorable travel experience?",
    ],
    3 => [
        'title'  => 'Task 3: Describing a Scene',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Describe some things that are happening in the picture below as well as you can. The person with whom you are speaking cannot see the picture.",
        'image'  => 'scene.jpg',
    ],
    4 => [
        'title'  => 'Task 4: Making Predictions',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "In this picture, what do you think will most probably happen next?",
        'image'  => 'scene.jpg',
    ],
    5 => [
        'title'  => 'Task 5: Comparing and Persuading',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "You checked in a restaurant. Your partner wants to eat a cheeseburger. However, you want him or her to have a chicken salad. Persuade him or her that your choice is more suitable by comparing the two meals.",
    ],
    6 => [
        'title'  => 'Task 6: Dealing with a Difficult Situation',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "You, Ryan and Roger are roommates. Ryan, who is the youngest, has some problems with Roger. Ryan is a messy person. Roger likes the house to be clean and well organized. However, Ryan throws clothes and trash everywhere in the apartment which is making Roger angry.\n\nChoose ONE:\nEITHER talk to Ryan. Explain how Roger is feeling bad about Ryan's tidiness.\nOR talk to Roger. Ask Roger to bear with Ryan's ill manners as he is younger than Roger.",
    ],
    7 => [
        'title'  => 'Task 7: Expressing Opinions',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "Do you agree or disagree with the following statement?\n\nPeople are never satisfied with what they have; they always want something more or something different. Use specific reasons to support your answer.",
    ],
    8 => [
        'title'  => 'Task 8: Describing an Unusual Situation',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "You are in an art gallery, and you see this unusual painting. Phone your art teacher. Describe the painting as well as you can and ask him about its meaning.",
        'image'  => 'painting.jpg',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Speaking Practice Test 1 – EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <?php include __DIR__ . '/celpip_screen_styles.php'; ?>
    <style>
        /* Fixed-viewport speaking screen: everything (instructions, image,
           status bar, transcript toggle) fits in one screen with no page
           scroll -- header/progress-dots are fixed height, the body between
           them flexes, and the image is capped + object-fit:contain so every
           task's image renders at the same visual size regardless of its
           source resolution/aspect ratio. */
        html, body { height: 100%; }
        /* .main-wrapper already gets margin-top: var(--topbar-h) from the fixed
           topbar's global CSS -- height must subtract that margin too, or the
           page overflows by exactly the topbar's height. */
        .main-wrapper { padding: 1rem 1.25rem; height: calc(100vh - var(--topbar-h, 60px)); min-height: 0; overflow: hidden; box-sizing: border-box; display: flex; flex-direction: column; }
        main.content { flex: 1; min-height: 0; display: flex; flex-direction: column; }
        .celpip-shell { display: flex; flex-direction: column; flex: 1; min-height: 0; width: 100%; }
        .celpip-screen { flex: 1; min-height: 0; width: 100%; display: flex; flex-direction: column; box-sizing: border-box; }
        /* Grid with fr-proportioned rows instead of hand-tuned vh/px values --
           text top, images 70% of the middle, timer bottom (~15%), text gets
           the remaining ~15%. Because fr units divide whatever height this
           box actually ends up with (itself fixed by the outer flex chain
           above), the proportions hold exactly regardless of viewport size,
           with none of the manual-budget overflow bugs the vh-based version
           had. When a task has no image, the stage row's share folds into
           the text row instead of leaving an empty gap. */
        .celpip-body.speaking {
            flex: 1; min-height: 0; display: grid;
            grid-template-rows: 15fr 70fr 15fr auto;
            gap: .6rem; padding: 1rem 1.25rem; background: #fff; overflow: hidden; box-sizing: border-box;
        }
        .celpip-body.speaking.no-image { grid-template-rows: 85fr 0fr 15fr auto; }
        .celpip-body.speaking.no-image .speaking-stage { display: none; }

        .speaking-prompt { grid-row: 1; min-height: 0; overflow-y: auto; color: #1f2937; font-size: .92rem; line-height: 1.6; white-space: pre-line; padding-right: .4rem; }

        .speaking-stage { grid-row: 2; min-height: 0; display: grid; gap: 3px; background: #e9ebef; border-radius: 8px; overflow: hidden; }
        .speaking-stage.slots-1 { grid-template-columns: 1fr; }
        .speaking-stage.slots-2 { grid-template-columns: 1fr 1fr; }
        .speaking-tile { position: relative; width: 100%; height: 100%; overflow: hidden; background: #e9ebef; display: flex; align-items: center; justify-content: center; }
        .speaking-tile img { width: 100%; height: 100%; object-fit: cover; object-position: center; display: block; }
        .speaking-tile .tile-label {
            position: absolute; top: 6px; left: 6px; font-size: 11px; padding: 2px 7px; border-radius: 4px;
            background: rgba(27,35,48,.72); color: #fff; letter-spacing: .02em;
        }

        /* Timer bar: fixed 3-column layout (phase pill | progress track |
           digits), color-coded by phase -- orange while preparing, teal
           while speaking, red once time's up -- instead of one red/grey
           scheme for every state. */
        :root {
            --prep-fill: #e9932e; --prep-tint: #fdf1e2; --prep-ink: #8a4f0c;
            --speak-fill: #1f9aa0; --speak-tint: #e3f4f4; --speak-ink: #0e5c60;
            --done-fill: #b23b3b; --done-tint: #fbe9e9; --done-ink: #7c2626;
        }
        .speaking-timerbar { grid-row: 3; min-height: 0; display: grid; grid-template-columns: 110px 1fr 100px; align-items: center; gap: 14px; }
        .phase-pill {
            height: 28px; border-radius: 14px; font-size: 12px; font-weight: 700; letter-spacing: .03em;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            background: #f1f3f5; color: #8a92a0; white-space: nowrap; overflow: hidden;
        }
        .phase-pill .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; flex: 0 0 auto; }
        .phase-pill.is-prep { background: var(--prep-tint); color: var(--prep-ink); }
        .phase-pill.is-speak { background: var(--speak-tint); color: var(--speak-ink); }
        .phase-pill.is-speak .dot { animation: speakingPulse 1s infinite; }
        .phase-pill.is-done { background: var(--done-tint); color: var(--done-ink); }
        @keyframes speakingPulse { 0%, 100% { opacity: 1; } 50% { opacity: .25; } }

        .progress-wrap { display: flex; flex-direction: column; gap: 6px; min-width: 0; }
        .progress-label { font-size: 11px; color: #8a92a0; height: 13px; }
        .progress-track { width: 100%; height: 8px; border-radius: 4px; background: #f1f3f5; overflow: hidden; }
        .progress-fill { height: 100%; width: 0%; background: #8a92a0; transition: width 1s linear, background-color .2s ease; }
        .progress-fill.is-prep { background: var(--prep-fill); }
        .progress-fill.is-speak { background: var(--speak-fill); }
        .progress-fill.is-done { background: var(--done-fill); }

        .timer-digits {
            width: 100px; height: 36px; border-radius: 8px; background: #f1f3f5;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Courier New', monospace; font-weight: 700; font-size: 18px;
            font-variant-numeric: tabular-nums; letter-spacing: .02em; color: #1f2937;
        }
        .timer-digits.is-prep { background: var(--prep-tint); color: var(--prep-ink); }
        .timer-digits.is-speak { background: var(--speak-tint); color: var(--speak-ink); }
        .timer-digits.is-done { background: var(--done-tint); color: var(--done-ink); }

        /* Hidden from view -- the underlying textarea still exists and still
           receives live speech-to-text output; submitAllTasks() reads from
           it. Only the visible toggle/edit affordance is removed. */
        .speaking-transcript-wrap { display: none; }
        .transcript-toggle { font-size: .78rem; color: #6b7280; cursor: pointer; flex-shrink: 0; }
        .transcript-box { display: none; flex-shrink: 0; }
        .transcript-box textarea { resize: none; }
        .celpip-progress .dot.done { background: #9c1f2e; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

<main class="content p-4">

    <nav aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-0" style="font-size:.8rem;">
            <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
            <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
            <li class="breadcrumb-item active">CELPIP Speaking – Practice 1</li>
        </ol>
    </nav>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
        <span class="section-badge" style="background:linear-gradient(135deg,#10b981,#34d399); color:#fff; padding:.3rem 1.1rem; border-radius:50px; font-weight:700; font-size:.8rem;">Speaking</span>
        <span class="text-muted small">8 Tasks · ~16 minutes</span>
    </div>

    <div class="celpip-shell" id="celpipShell">
        <?php foreach ($tasks as $tNum => $task): ?>
        <div class="celpip-screen" data-task="<?= $tNum ?>" style="<?= $tNum === 1 ? '' : 'display:none;' ?>">
            <div class="celpip-header">
                <div class="title"><?= htmlspecialchars($task['title']) ?></div>
                <div class="meta">
                    <span>Preparation: <?= $task['prep'] ?> seconds</span>
                    <span>Recording: <?= $task['speak'] ?> seconds</span>
                    <button type="button" class="celpip-next-btn" id="nextBtn-<?= $tNum ?>" onclick="celpipSpeakingNext()"><?= $tNum < count($tasks) ? 'Next' : 'Finish Test' ?></button>
                </div>
            </div>
            <?php
                $images = !empty($task['images']) ? $task['images'] : (!empty($task['image']) ? [$task['image']] : []);
                $slots = count($images);
            ?>
            <div class="celpip-body speaking<?= $slots === 0 ? ' no-image' : '' ?>">
                <div class="speaking-prompt"><?= htmlspecialchars($task['prompt']) ?></div>

                <div class="speaking-stage slots-<?= $slots ?>">
                    <?php foreach ($images as $i => $img): ?>
                        <div class="speaking-tile">
                            <img src="<?= ACADEMY_URL ?>assets/img/practice_tests/CELPIP_PT_S_001/<?= $img ?>" alt="Task <?= $tNum ?> prompt image <?= $i + 1 ?>">
                            <?php if ($slots === 2): ?><div class="tile-label">Option <?= $i === 0 ? 'A' : 'B' ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="speaking-timerbar">
                    <div class="phase-pill" id="phasePill-<?= $tNum ?>"><span class="dot"></span><span id="phaseLabel-<?= $tNum ?>">Preparing</span></div>
                    <div class="progress-wrap">
                        <div class="progress-label" id="progressLabel-<?= $tNum ?>">Preparation time</div>
                        <div class="progress-track"><div class="progress-fill" id="progressFill-<?= $tNum ?>"></div></div>
                    </div>
                    <div class="timer-digits" id="timerDigits-<?= $tNum ?>"><?= $task['prep'] ?>s</div>
                </div>

                <div class="speaking-transcript-wrap">
                    <span class="transcript-toggle" onclick="document.getElementById('transcriptBox-<?= $tNum ?>').style.display = document.getElementById('transcriptBox-<?= $tNum ?>').style.display === 'block' ? 'none' : 'block';">
                        <i class="bi bi-pencil-square me-1"></i>View / edit your captured transcript
                    </span>
                    <div class="transcript-box" id="transcriptBox-<?= $tNum ?>">
                        <textarea id="transcript-<?= $tNum ?>" class="form-control form-control-sm" rows="2"
                            placeholder="Your speech is transcribed here automatically as you speak. You can edit it directly if needed."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="celpip-progress">
            <?php for ($i = 1; $i <= count($tasks); $i++): ?>
            <div class="dot <?= $i === 1 ? 'current' : '' ?>" id="progressDot-<?= $i ?>"></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Loading -->
    <div id="loadingSection" class="text-center py-5 d-none">
        <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
        <p class="fw-bold">Analysing your speaking with AI…</p>
        <p class="text-muted small">This may take 20–40 seconds for 8 tasks</p>
    </div>

    <!-- Results -->
    <div id="resultsSection" class="d-none mb-5 mt-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-dark text-white rounded-top-4 py-3">
                <h5 class="mb-0"><i class="bi bi-mic-fill me-2"></i>Your Recordings</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Listen back to or download any of your responses below. These are only available on this page right now -- once you leave, they're gone from here (an admin can still access a saved copy for grading).</p>
                <div id="recordingsList" class="d-flex flex-column gap-2"></div>
            </div>
        </div>
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-success text-white rounded-top-4 py-3">
                <h5 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>AI Examiner Feedback</h5>
            </div>
            <div class="card-body p-4" id="feedbackContent"></div>
        </div>
    </div>

</main>
</div><!-- /.main-wrapper -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const TASK_PROMPTS = <?= json_encode(array_map(fn($t) => ['title' => $t['title'], 'prompt' => $t['prompt']], $tasks)) ?>;
const TOTAL_TASKS  = <?= count($tasks) ?>;
const TEST_CODE    = <?= json_encode($testCode) ?>;

let currentTask = 1;
let prepInterval = null, recInterval = null;
let mediaStream = null, mediaRecorder = null, audioChunks = [];
const recordedBlobs = {}; // tNum -> Blob, for the end-of-test playback/download panel
const uploadPromises = []; // settled before final submission so transcripts are ready

const TASK_PREP  = <?= json_encode(array_map(fn($t) => $t['prep'], $tasks)) ?>;
const TASK_SPEAK = <?= json_encode(array_map(fn($t) => $t['speak'], $tasks)) ?>;

function setPhase(tNum, phase) {
    const pill = document.getElementById('phasePill-' + tNum);
    const fill = document.getElementById('progressFill-' + tNum);
    const digits = document.getElementById('timerDigits-' + tNum);
    [pill, fill, digits].forEach(el => el.classList.remove('is-prep', 'is-speak', 'is-done'));
    [pill, fill, digits].forEach(el => el.classList.add('is-' + phase));
}

function startTaskFlow(tNum) {
    const prepSecs0 = TASK_PREP[tNum];
    const labelEl = document.getElementById('phaseLabel-' + tNum);
    const progLabelEl = document.getElementById('progressLabel-' + tNum);
    const digitsEl = document.getElementById('timerDigits-' + tNum);
    const fillEl = document.getElementById('progressFill-' + tNum);
    let prepSecs = prepSecs0;

    setPhase(tNum, 'prep');
    labelEl.textContent = 'Preparing';
    progLabelEl.textContent = 'Preparation time';
    digitsEl.textContent = prepSecs + 's';
    fillEl.style.width = '100%';

    prepInterval = setInterval(() => {
        prepSecs--;
        digitsEl.textContent = Math.max(prepSecs, 0) + 's';
        fillEl.style.width = Math.max(0, (prepSecs / prepSecs0) * 100) + '%';
        if (prepSecs <= 0) {
            clearInterval(prepInterval);
            beginRecording(tNum);
        }
    }, 1000);
}

function beginRecording(tNum) {
    const labelEl = document.getElementById('phaseLabel-' + tNum);
    const progLabelEl = document.getElementById('progressLabel-' + tNum);
    const digitsEl = document.getElementById('timerDigits-' + tNum);
    const fillEl = document.getElementById('progressFill-' + tNum);

    setPhase(tNum, 'speak');
    labelEl.textContent = 'Speaking';
    progLabelEl.textContent = 'Recording your answer';

    const speakSecs0 = TASK_SPEAK[tNum];
    let speakSecs = speakSecs0;
    digitsEl.textContent = speakSecs + 's';
    fillEl.style.width = '100%';

    startAudioCapture(tNum);
    recInterval = setInterval(() => {
        speakSecs--;
        digitsEl.textContent = Math.max(speakSecs, 0) + 's';
        fillEl.style.width = Math.max(0, (speakSecs / speakSecs0) * 100) + '%';
        if (speakSecs <= 0) {
            clearInterval(recInterval);
            stopAudioCaptureAndUpload(tNum);
            setPhase(tNum, 'done');
            labelEl.textContent = "Time's up";
            progLabelEl.textContent = 'Response complete';
        }
    }, 1000);
}

async function startAudioCapture(tNum) {
    audioChunks = [];
    try {
        mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(mediaStream);
        mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) audioChunks.push(e.data); };
        mediaRecorder.start();
    } catch (err) {
        console.error('Microphone access failed for task ' + tNum + ':', err);
        mediaRecorder = null;
        // The prep/speak countdown still runs even without mic access, so a
        // student who denies the permission prompt isn't blocked from
        // finishing the test -- they just won't have a recording for this task.
    }
}

function stopAudioCaptureAndUpload(tNum) {
    if (!mediaRecorder || mediaRecorder.state === 'inactive') return;
    const recorder = mediaRecorder;
    const stream = mediaStream;
    recorder.onstop = () => {
        const blob = new Blob(audioChunks, { type: 'audio/webm' });
        if (blob.size > 0) {
            recordedBlobs[tNum] = blob;
            uploadPromises.push(uploadRecording(tNum, blob));
        }
        stream.getTracks().forEach(t => t.stop());
    };
    recorder.stop();
    mediaRecorder = null;
}

async function uploadRecording(tNum, blob) {
    const fd = new FormData();
    fd.append('audio', blob, 'task' + tNum + '.webm');
    fd.append('test_code', TEST_CODE);
    fd.append('task_number', tNum);
    fd.append('task_title', TASK_PROMPTS[tNum].title);
    fd.append('prompt', TASK_PROMPTS[tNum].prompt);
    try {
        const res = await fetch('<?php echo ACADEMY_URL; ?>api/speaking_upload.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (data.success && data.transcript) {
            const textarea = document.getElementById('transcript-' + tNum);
            if (textarea) textarea.value = data.transcript;
        }
        return data;
    } catch (err) {
        console.error('Upload failed for task ' + tNum + ':', err);
        return null;
    }
}

function showTask(n) {
    document.querySelectorAll('.celpip-screen').forEach(s => s.style.display = 'none');
    document.querySelector('.celpip-screen[data-task="' + n + '"]').style.display = '';
    for (let i = 1; i <= TOTAL_TASKS; i++) {
        const dot = document.getElementById('progressDot-' + i);
        if (!dot) continue;
        dot.classList.remove('current', 'done');
        if (i < n) dot.classList.add('done');
        else if (i === n) dot.classList.add('current');
    }
    currentTask = n;
    startTaskFlow(n);
}

function celpipSpeakingNext() {
    clearInterval(prepInterval);
    clearInterval(recInterval);
    stopAudioCaptureAndUpload(currentTask); // no-op if this task's recording already finished naturally
    if (currentTask < TOTAL_TASKS) {
        showTask(currentTask + 1);
    } else {
        submitAllTasks();
    }
}

function buildTasksPayload() {
    return Object.keys(TASK_PROMPTS).map((key) => {
        const tNum = parseInt(key, 10);
        const transcript = (document.getElementById('transcript-' + tNum)?.value || '').trim();
        return { part: tNum, title: TASK_PROMPTS[key].title, prompt: TASK_PROMPTS[key].title + '\n\n' + TASK_PROMPTS[key].prompt, transcription: transcript };
    });
}

function renderRecordingsPanel() {
    const list = document.getElementById('recordingsList');
    if (!list) return;
    const entries = Object.keys(recordedBlobs).map(Number).sort((a, b) => a - b);
    if (entries.length === 0) {
        list.innerHTML = '<p class="text-muted mb-0">No recordings were captured (microphone access may have been denied).</p>';
        return;
    }
    list.innerHTML = entries.map(tNum => {
        const url = URL.createObjectURL(recordedBlobs[tNum]);
        const title = (TASK_PROMPTS[tNum] && TASK_PROMPTS[tNum].title) || ('Task ' + tNum);
        return `<div class="d-flex align-items-center gap-3 flex-wrap p-2 border rounded-3">
            <span class="fw-semibold" style="min-width:180px;">${title}</span>
            <audio controls src="${url}" style="height:32px;flex:1;min-width:200px;"></audio>
            <a href="${url}" download="${TEST_CODE}_task${tNum}.webm" class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i>Download</a>
        </div>`;
    }).join('');
}

async function submitAllTasks() {
    // Let any still-in-flight uploads finish (and populate their transcript
    // textareas from Groq) before reading transcripts for AI analysis.
    await Promise.allSettled(uploadPromises);

    const tasks = buildTasksPayload();
    const empty = tasks.filter(t => t.transcription.length < 10);
    if (empty.length > 0) {
        const partNames = empty.map(t => 'Task ' + t.part).join(', ');
        const r = await Swal.fire({
            title: 'Missing responses',
            html: `${partNames} ${empty.length > 1 ? 'have' : 'has'} no transcription. Submit anyway?`,
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Submit anyway', cancelButtonText: 'Go back',
        });
        if (!r.isConfirmed) return;
    }

    renderRecordingsPanel();
    document.getElementById('celpipShell').style.display = 'none';
    document.getElementById('loadingSection').classList.remove('d-none');

    try {
        const res = await fetch('../../api/api_handler.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'analyze_speaking_batch', exam_type: 'CELPIP', tasks }),
        });
        const data = await res.json();
        document.getElementById('loadingSection').classList.add('d-none');
        document.getElementById('resultsSection').classList.remove('d-none');

        if (data.success && Array.isArray(data.results)) {
            document.getElementById('feedbackContent').innerHTML = data.results.map(r => {
                const body = r.success
                    ? '<pre style="white-space:pre-wrap;font-family:inherit;line-height:1.7;margin:0;">' + r.feedback.replace(/</g,'&lt;') + '</pre>'
                    : '<div class="alert alert-danger mb-0">' + (r.error || 'Analysis failed for this task') + '</div>';
                return '<div class="mb-4"><h6 class="fw-bold">' + (r.task_title || ('Task ' + r.task_number)) + '</h6>' + body + '</div>';
            }).join('<hr>');
            document.getElementById('resultsSection').scrollIntoView({ behavior: 'smooth' });
        } else {
            document.getElementById('feedbackContent').innerHTML =
                '<div class="alert alert-danger">' + (data.error || 'Unknown error') + '</div>';
        }
    } catch (err) {
        document.getElementById('loadingSection').classList.add('d-none');
        Swal.fire({ title: 'Error', text: err.message, icon: 'error' });
    }
}

document.addEventListener('DOMContentLoaded', () => startTaskFlow(1));
</script>
</body>
</html>
