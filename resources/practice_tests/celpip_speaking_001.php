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
        .celpip-shell { display: flex; flex-direction: column; flex: 1; min-height: 0; }
        .celpip-screen { flex: 1; min-height: 0; display: flex; flex-direction: column; }
        .celpip-body.speaking {
            flex: 1; min-height: 0; display: flex; flex-direction: column;
            padding: 1rem 1.25rem; background: #fff; gap: .75rem; overflow: hidden;
        }
        /* Text always sits full-width at the top. Below it: if the task has
           an image, image (big, left) + timer (small, fixed-width, right)
           sit side by side; if not, the timer sits alone, centered, in the
           remaining space -- there's nothing to split it against. */
        /* Capped + internally scrollable so one long question's text (e.g. a
           two-option "choose one" scenario) can never eat into the row below
           and shrink it -- every task keeps the same size image/timer area
           regardless of how long its instructions are. */
        /* Fixed (not max-) height: every task's text box is the same size
           regardless of how long its prompt is, so the grey timer area below
           it is always the same size too -- Task 6's longer text made that
           combination look best, so its rendered proportions are now the
           standard, not just its ceiling. Longer prompts scroll internally. */
        .speaking-instructions { color: #1f2937; font-size: .92rem; line-height: 1.6; white-space: pre-line; flex-shrink: 0; height: 19vh; overflow-y: auto; padding-right: .4rem; }
        /* Fixed-height container (in vh, not flex:1-derived from leftover
           space) so it's identical regardless of instructions length. The
           image gets its own fixed-size box to shrink INTO (max-width/
           max-height + width/height:auto means it only ever shrinks to fit,
           never stretches up to fill the box) instead of expanding to claim
           all available space. The timer stays a small, fixed, top-aligned
           box -- it does not stretch to match the image's height. */
        .speaking-content-row { flex: 0 0 auto; height: 30vh; display: flex; flex-direction: row; align-items: flex-start; gap: 1.25rem; }
        .speaking-image-wrap { flex: 1; min-width: 0; height: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .speaking-task-image { max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        /* Grey band fills the whole remaining area right under the text (like
           real CELPIP screens), with the timer sitting near its top instead
           of vertically centered in a sea of white. */
        .speaking-status-bar-wrap { flex: 0 0 auto; height: 30vh; background: #eef0f2; border-radius: 8px; display: flex; align-items: flex-start; justify-content: center; padding-top: 1.25rem; }

        .speaking-status-bar {
            flex-shrink: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .5rem;
            background: #f1f3f5; border-radius: 12px; padding: .9rem 1.1rem;
        }
        .speaking-content-row .speaking-status-bar { flex: 0 0 220px; align-self: flex-start; }
        .speaking-status-bar-wrap .speaking-status-bar { width: min(420px, 70%); background: transparent; padding: 0; }
        .status-header { display: flex; align-items: center; gap: .6rem; }
        .status-icon { width: 34px; height: 34px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #9c1f2e; flex-shrink: 0; }
        .speaking-status-bar.recording .status-icon { color: #dc3545; }
        .status-label { font-size: .78rem; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        .status-count { font-size: 2.1rem; font-weight: 800; color: #7a1824; font-family: monospace; line-height: 1; }
        .speaking-status-bar.recording .status-count { color: #dc3545; }
        .status-bar-track { width: min(360px, 80%); background: #e5e7eb; border-radius: 4px; height: 8px; overflow: hidden; }
        .status-bar-fill { background: #9c1f2e; height: 100%; width: 100%; transition: width 1s linear; }
        .speaking-status-bar.recording .status-bar-fill { background: #dc3545; }

        .transcript-toggle { font-size: .78rem; color: #6b7280; cursor: pointer; flex-shrink: 0; }
        .transcript-box { display: none; flex-shrink: 0; }
        .transcript-box textarea { resize: none; }
        .celpip-progress .dot.done { background: #9c1f2e; }

        @media (max-height: 700px), (max-width: 767px) {
            .main-wrapper { height: auto; overflow: visible; }
            .speaking-image-wrap { min-height: 160px; }
        }
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
            <div class="celpip-body speaking">
                <div class="celpip-panel-label"><i class="bi bi-info-circle-fill"></i> Instructions</div>
                <div class="speaking-instructions"><?= htmlspecialchars($task['prompt']) ?></div>

                <?php $statusBar = '
                    <div class="speaking-status-bar" id="statusBar-' . $tNum . '">
                        <div class="status-header">
                            <div class="status-icon" id="statusIcon-' . $tNum . '"><i class="bi bi-clock-history"></i></div>
                            <div class="status-label" id="statusLabel-' . $tNum . '">Preparation Time</div>
                        </div>
                        <div class="status-count" id="statusCount-' . $tNum . '">' . $task['prep'] . '</div>
                        <div class="status-bar-track"><div class="status-bar-fill" id="statusBarFill-' . $tNum . '"></div></div>
                    </div>
                '; ?>

                <?php if (!empty($task['image'])): ?>
                    <div class="speaking-content-row">
                        <div class="speaking-image-wrap">
                            <img class="speaking-task-image" src="<?= ACADEMY_URL ?>assets/img/practice_tests/CELPIP_PT_S_001/<?= $task['image'] ?>" alt="Task <?= $tNum ?> prompt image">
                        </div>
                        <?= $statusBar ?>
                    </div>
                <?php else: ?>
                    <div class="speaking-status-bar-wrap"><?= $statusBar ?></div>
                <?php endif; ?>

                <span class="transcript-toggle" onclick="document.getElementById('transcriptBox-<?= $tNum ?>').style.display = document.getElementById('transcriptBox-<?= $tNum ?>').style.display === 'block' ? 'none' : 'block';">
                    <i class="bi bi-pencil-square me-1"></i>View / edit your captured transcript
                </span>
                <div class="transcript-box" id="transcriptBox-<?= $tNum ?>">
                    <textarea id="transcript-<?= $tNum ?>" class="form-control form-control-sm" rows="2"
                        placeholder="Your speech is transcribed here automatically as you speak. You can edit it directly if needed."></textarea>
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
const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

let currentTask = 1;
let prepInterval = null, recInterval = null, recognition = null, recognitionActive = false;

const TASK_PREP  = <?= json_encode(array_map(fn($t) => $t['prep'], $tasks)) ?>;
const TASK_SPEAK = <?= json_encode(array_map(fn($t) => $t['speak'], $tasks)) ?>;

function startTaskFlow(tNum) {
    const prepSecs0 = TASK_PREP[tNum];
    const countEl = document.getElementById('statusCount-' + tNum);
    const fillEl = document.getElementById('statusBarFill-' + tNum);
    let prepSecs = prepSecs0;

    prepInterval = setInterval(() => {
        prepSecs--;
        countEl.textContent = Math.max(prepSecs, 0);
        fillEl.style.width = Math.max(0, (prepSecs / prepSecs0) * 100) + '%';
        if (prepSecs <= 0) {
            clearInterval(prepInterval);
            beginRecording(tNum);
        }
    }, 1000);
}

function beginRecording(tNum) {
    const bar = document.getElementById('statusBar-' + tNum);
    const icon = document.getElementById('statusIcon-' + tNum);
    const label = document.getElementById('statusLabel-' + tNum);
    const countEl = document.getElementById('statusCount-' + tNum);
    const fillEl = document.getElementById('statusBarFill-' + tNum);

    bar.classList.add('recording');
    icon.innerHTML = '<i class="bi bi-mic-fill"></i>';
    label.textContent = 'Speak Now';

    const speakSecs0 = TASK_SPEAK[tNum];
    let speakSecs = speakSecs0;
    countEl.textContent = speakSecs;
    fillEl.style.width = '100%';

    startTranscription(tNum);
    recInterval = setInterval(() => {
        speakSecs--;
        countEl.textContent = Math.max(speakSecs, 0);
        fillEl.style.width = Math.max(0, (speakSecs / speakSecs0) * 100) + '%';
        if (speakSecs <= 0) {
            clearInterval(recInterval);
            stopTranscription();
        }
    }, 1000);
}

function startTranscription(tNum) {
    if (!SpeechRecognition) return;
    const textarea = document.getElementById('transcript-' + tNum);
    recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';
    let savedText = textarea.value;
    recognition.onresult = e => {
        let interim = '', final = '';
        for (let i = e.resultIndex; i < e.results.length; i++) {
            if (e.results[i].isFinal) final += e.results[i][0].transcript + ' ';
            else interim += e.results[i][0].transcript;
        }
        savedText += final;
        textarea.value = savedText + interim;
        if (final) savedText = textarea.value.replace(interim, '');
    };
    recognition.onend = () => { if (recognitionActive) recognition.start(); };
    recognition.start();
    recognitionActive = true;
}

function stopTranscription() {
    recognitionActive = false;
    if (recognition) recognition.stop();
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
    stopTranscription();
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

async function submitAllTasks() {
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
