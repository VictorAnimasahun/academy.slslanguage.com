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
        .celpip-body.speaking { display: block; padding: 1.25rem 1.5rem; background: #fff; }
        .speaking-instructions { color: #1f2937; font-size: .92rem; line-height: 1.7; white-space: pre-line; }
        .speaking-task-image { max-width: 100%; border-radius: 8px; margin-top: .75rem; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
        .speaking-stage { margin-top: 1.25rem; border-top: 1px solid #e5e7eb; padding-top: 1.25rem; display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; }
        .prep-box { background: #f1f3f5; border-radius: 8px; padding: 1rem 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .prep-box .clock-icon { font-size: 1.8rem; color: #9c1f2e; }
        .prep-box .prep-label { font-size: .78rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: .03em; }
        .prep-box .prep-count { font-size: 1.8rem; font-weight: 700; color: #7a1824; font-family: monospace; }
        .rec-stage { display: none; align-items: center; gap: 1rem; flex: 1 1 auto; min-width: 260px; }
        .rec-mic { width: 52px; height: 52px; border-radius: 50%; background: #eef1f3; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #dc3545; flex-shrink: 0; }
        .rec-track { flex: 1 1 auto; }
        .rec-label { font-size: .78rem; color: #6b7280; font-weight: 600; margin-bottom: .3rem; }
        .rec-bar-bg { background: #e5e7eb; border-radius: 4px; height: 10px; overflow: hidden; }
        .rec-bar-fill { background: #9c1f2e; height: 100%; width: 0%; transition: width 1s linear; }
        .transcript-toggle { font-size: .78rem; color: #6b7280; cursor: pointer; margin-top: 1rem; display: inline-block; }
        .transcript-box { display: none; margin-top: .5rem; }
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
            <div class="celpip-body speaking">
                <div class="celpip-panel-label"><i class="bi bi-info-circle-fill"></i> Instructions</div>
                <div class="speaking-instructions"><?= htmlspecialchars($task['prompt']) ?></div>
                <?php if (!empty($task['image'])): ?>
                    <img class="speaking-task-image" src="<?= ACADEMY_URL ?>assets/img/practice_tests/CELPIP_PT_S_001/<?= $task['image'] ?>" alt="Task <?= $tNum ?> prompt image">
                <?php endif; ?>

                <div class="speaking-stage">
                    <div class="prep-box" id="prepBox-<?= $tNum ?>">
                        <i class="bi bi-clock-history clock-icon"></i>
                        <div>
                            <div class="prep-label">Preparation Time</div>
                            <div class="prep-count" id="prepCount-<?= $tNum ?>"><?= $task['prep'] ?></div>
                        </div>
                    </div>
                    <div class="rec-stage" id="recStage-<?= $tNum ?>">
                        <div class="rec-mic"><i class="bi bi-mic-fill"></i></div>
                        <div class="rec-track">
                            <div class="rec-label">Recording …</div>
                            <div class="rec-bar-bg"><div class="rec-bar-fill" id="recBar-<?= $tNum ?>"></div></div>
                        </div>
                    </div>
                </div>

                <span class="transcript-toggle" onclick="document.getElementById('transcriptBox-<?= $tNum ?>').style.display = document.getElementById('transcriptBox-<?= $tNum ?>').style.display === 'block' ? 'none' : 'block';">
                    <i class="bi bi-pencil-square me-1"></i>View / edit your captured transcript
                </span>
                <div class="transcript-box" id="transcriptBox-<?= $tNum ?>">
                    <textarea id="transcript-<?= $tNum ?>" class="form-control form-control-sm" rows="3"
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

function startTaskFlow(tNum) {
    const task = TASK_PROMPTS[tNum];
    // Read prep/speak seconds back out of the DOM (rendered server-side per task)
    const prepEl = document.getElementById('prepCount-' + tNum);
    let prepSecs = parseInt(prepEl.textContent, 10);

    prepInterval = setInterval(() => {
        prepSecs--;
        prepEl.textContent = Math.max(prepSecs, 0);
        if (prepSecs <= 0) {
            clearInterval(prepInterval);
            beginRecording(tNum);
        }
    }, 1000);
}

function beginRecording(tNum) {
    document.getElementById('prepBox-' + tNum).style.display = 'none';
    const stage = document.getElementById('recStage-' + tNum);
    stage.style.display = 'flex';
    const bar = document.getElementById('recBar-' + tNum);

    const speakSecsAttr = <?= json_encode(array_map(fn($t) => $t['speak'], $tasks)) ?>[tNum];
    let elapsed = 0;
    startTranscription(tNum);
    recInterval = setInterval(() => {
        elapsed++;
        bar.style.width = Math.min(100, (elapsed / speakSecsAttr) * 100) + '%';
        if (elapsed >= speakSecsAttr) {
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
