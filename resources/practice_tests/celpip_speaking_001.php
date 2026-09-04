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
        'icon'   => 'bi-lightbulb',
        'color'  => '#10b981',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "Your friend is going to have a job interview for the first time. Advise him how it would be and also share your experiences with him.",
    ],
    2 => [
        'title'  => 'Task 2: Talking About a Personal Experience',
        'icon'   => 'bi-person-lines-fill',
        'color'  => '#3b82f6',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Talk about your favorite travel experience. You can talk about a family holiday, a business trip, or any vacation you had with your friends. Where did you go, when and why was it the most memorable travel experience?",
    ],
    3 => [
        'title'  => 'Task 3: Describing a Scene',
        'icon'   => 'bi-image',
        'color'  => '#8b5cf6',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "Describe some things that are happening in the picture below as well as you can. The person with whom you are speaking cannot see the picture.",
        'image'  => 'scene.jpg',
    ],
    4 => [
        'title'  => 'Task 4: Making Predictions',
        'icon'   => 'bi-graph-up-arrow',
        'color'  => '#f59e0b',
        'prep'   => 30,
        'speak'  => 60,
        'prompt' => "In this picture, what do you think will most probably happen next?",
        'image'  => 'scene.jpg',
    ],
    5 => [
        'title'  => 'Task 5: Comparing and Persuading',
        'icon'   => 'bi-arrow-left-right',
        'color'  => '#ef4444',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "You checked in a restaurant. Your partner wants to eat a cheeseburger. However, you want him or her to have a chicken salad. Persuade him or her that your choice is more suitable by comparing the two meals.",
    ],
    6 => [
        'title'  => 'Task 6: Dealing With a Difficult Situation',
        'icon'   => 'bi-exclamation-triangle',
        'color'  => '#ec4899',
        'prep'   => 60,
        'speak'  => 60,
        'prompt' => "You, Ryan and Roger are roommates. Ryan, who is the youngest, has some problems with Roger. Ryan is a messy person. Roger likes the house to be clean and well organized. However, Ryan throws clothes and trash everywhere in the apartment which is making Roger angry.\n\nChoose ONE:\nEITHER talk to Ryan. Explain how Roger is feeling bad about Ryan's tidiness.\nOR talk to Roger. Ask Roger to bear with Ryan's ill manners as he is younger than Roger.",
    ],
    7 => [
        'title'  => 'Task 7: Expressing Opinion',
        'icon'   => 'bi-chat-square-quote',
        'color'  => '#06b6d4',
        'prep'   => 30,
        'speak'  => 90,
        'prompt' => "Do you agree or disagree with the following statement?\n\nPeople are never satisfied with what they have; they always want something more or something different. Use specific reasons to support your answer.",
    ],
    8 => [
        'title'  => 'Task 8: Describing an Unusual Situation',
        'icon'   => 'bi-easel',
        'color'  => '#6366f1',
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
    <title>CELPIP Speaking – Practice 1 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding:1.5rem; min-height:100vh; }
        .test-container { max-width:900px; margin:0 auto; }
        .part-card { background:white; border-radius:16px; padding:2rem; box-shadow:0 4px 18px rgba(0,0,0,0.07); margin-bottom:1.5rem; border-left:5px solid var(--c); }
        .part-header { display:flex; align-items:center; gap:1rem; margin-bottom:1rem; }
        .part-icon { width:46px; height:46px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:white; background:var(--c); flex-shrink:0; }
        .part-title { font-size:1.1rem; font-weight:700; margin:0; }
        .part-duration { font-size:.8rem; color:#9ca3af; }
        .cue-card { background:linear-gradient(135deg,#eff6ff,#dbeafe); border-radius:12px; padding:1.5rem; font-size:1rem; line-height:1.7; white-space:pre-line; border:1px solid #bfdbfe; }
        .task-image { max-width:100%; border-radius:10px; margin-bottom:1rem; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
        .prep-timer { font-size:1.6rem; font-weight:700; font-family:monospace; color:#1e40af; }
        .speak-timer { font-size:1.6rem; font-weight:700; font-family:monospace; color:#b91c1c; }
        .rec-btn { width:64px; height:64px; border-radius:50%; border:none; font-size:1.4rem; color:white; cursor:pointer; background:#ef4444; flex-shrink:0; }
        .rec-btn.recording { animation:pulse 1s infinite; }
        @keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(239,68,68,.4)} 50%{box-shadow:0 0 0 12px rgba(239,68,68,0)} }
        .rec-time { font-size:1.4rem; font-weight:700; font-family:monospace; }
        .section-badge { background:linear-gradient(135deg,#10b981,#34d399); color:white; padding:.45rem 1.4rem; border-radius:50px; font-weight:700; font-size:.85rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <main class="content p-4">
        <div class="test-container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                    <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                    <li class="breadcrumb-item active">CELPIP Speaking – Practice 1</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="section-badge">Speaking</span>
                <span class="text-muted small">CELPIP · 8 Tasks · ~16 minutes</span>
            </div>

            <?php foreach ($tasks as $tNum => $task): ?>
            <div class="part-card" style="--c:<?= $task['color'] ?>;">
                <div class="part-header">
                    <div class="part-icon"><i class="<?= $task['icon'] ?>"></i></div>
                    <div>
                        <p class="part-title"><?= htmlspecialchars($task['title']) ?></p>
                        <span class="part-duration"><i class="bi bi-clock me-1"></i>Prep <?= $task['prep'] ?>s · Speak <?= $task['speak'] ?>s</span>
                    </div>
                </div>

                <?php if (!empty($task['image'])): ?>
                    <img class="task-image" src="<?= ACADEMY_URL ?>assets/img/practice_tests/CELPIP_PT_S_001/<?= $task['image'] ?>" alt="Task <?= $tNum ?> prompt image">
                <?php endif; ?>

                <div class="cue-card mb-3"><?= htmlspecialchars($task['prompt']) ?></div>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="text-muted small">Preparation:</span>
                    <span class="prep-timer" id="prepTimer-<?= $tNum ?>"><?= floor($task['prep']/60) ?>:<?= str_pad($task['prep']%60,2,'0',STR_PAD_LEFT) ?></span>
                    <button class="btn btn-outline-primary btn-sm" onclick="startPrep(<?= $tNum ?>, <?= $task['prep'] ?>, <?= $task['speak'] ?>)">Start Prep</button>
                    <span class="text-muted small ms-2">Speaking:</span>
                    <span class="speak-timer" id="speakTimer-<?= $tNum ?>"><?= floor($task['speak']/60) ?>:<?= str_pad($task['speak']%60,2,'0',STR_PAD_LEFT) ?></span>
                </div>

                <!-- Recording + transcription interface -->
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <button class="rec-btn" id="recBtn-<?= $tNum ?>" onclick="toggleRecording(<?= $tNum ?>)">
                            <i class="bi bi-mic-fill" id="recIcon-<?= $tNum ?>"></i>
                        </button>
                        <div>
                            <div class="rec-time" id="recTime-<?= $tNum ?>">0:00</div>
                            <div class="text-muted small" id="recStatus-<?= $tNum ?>">Press mic to start — your speech will be transcribed automatically</div>
                        </div>
                    </div>
                    <textarea id="transcript-<?= $tNum ?>" class="form-control" rows="4"
                        placeholder="Your transcription appears here as you speak. You can also type or edit it directly."></textarea>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="text-center mt-2 mb-4">
                <button class="btn btn-success btn-lg px-5" onclick="submitAllParts()">
                    <i class="bi bi-stars me-2"></i>Submit All Tasks for AI Feedback
                </button>
            </div>

            <!-- Loading -->
            <div id="loadingSection" class="text-center py-5 d-none">
                <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem;"></div>
                <p class="fw-bold">Analysing your speaking with AI…</p>
                <p class="text-muted small">This may take 20–40 seconds for 8 tasks</p>
            </div>

            <!-- Results -->
            <div id="resultsSection" class="d-none mb-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-success text-white rounded-top-4 py-3">
                        <h5 class="mb-0"><i class="bi bi-trophy-fill me-2"></i>AI Examiner Feedback</h5>
                    </div>
                    <div class="card-body p-4" id="feedbackContent"></div>
                </div>
            </div>
        </div>
    </main>
    </div><!-- /.main-wrapper -->

    <?php include INCLUDES_PATH . '/adverts.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script>
    const recState   = {};
    const prepIntervals  = {};
    const speakIntervals = {};
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    function formatTime(s) {
        return Math.floor(s/60) + ':' + String(s%60).padStart(2,'0');
    }

    function startPrep(tNum, prepSecs, speakSecs) {
        let secs = prepSecs;
        const el = document.getElementById('prepTimer-' + tNum);
        if (prepIntervals[tNum]) clearInterval(prepIntervals[tNum]);
        el.textContent = formatTime(secs);
        prepIntervals[tNum] = setInterval(() => {
            secs--;
            el.textContent = formatTime(Math.max(secs, 0));
            if (secs <= 0) {
                clearInterval(prepIntervals[tNum]);
                startSpeakCountdown(tNum, speakSecs);
                if (!recState[tNum] || !recState[tNum].active) toggleRecording(tNum);
            }
        }, 1000);
    }

    function startSpeakCountdown(tNum, speakSecs) {
        let secs = speakSecs;
        const el = document.getElementById('speakTimer-' + tNum);
        if (speakIntervals[tNum]) clearInterval(speakIntervals[tNum]);
        el.textContent = formatTime(secs);
        speakIntervals[tNum] = setInterval(() => {
            secs--;
            el.textContent = formatTime(Math.max(secs, 0));
            if (secs <= 0) {
                clearInterval(speakIntervals[tNum]);
                if (recState[tNum] && recState[tNum].active) toggleRecording(tNum);
                Swal.fire({ title: 'Time up!', text: 'Task ' + tNum + ' speaking time has ended.', icon: 'info', timer: 2000, showConfirmButton: false });
            }
        }, 1000);
    }

    function toggleRecording(tNum) {
        const btn      = document.getElementById('recBtn-' + tNum);
        const icon     = document.getElementById('recIcon-' + tNum);
        const timeEl   = document.getElementById('recTime-' + tNum);
        const status   = document.getElementById('recStatus-' + tNum);
        const textarea = document.getElementById('transcript-' + tNum);

        if (recState[tNum] && recState[tNum].active) {
            recState[tNum].recognition.stop();
            clearInterval(recState[tNum].timer);
            recState[tNum].active = false;
            btn.classList.remove('recording');
            btn.style.background = '#6b7280';
            icon.className = 'bi bi-mic-fill';
            status.textContent = 'Done — review your transcription above, then edit if needed';
            return;
        }

        if (!SpeechRecognition) {
            Swal.fire({
                title: 'Browser not supported',
                html: 'Speech recognition requires Chrome or Edge. You can <strong>type your response directly</strong> into the text box instead.',
                icon: 'warning',
            });
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.continuous     = true;
        recognition.interimResults = true;
        recognition.lang           = 'en-US';

        let savedText = textarea.value;
        recognition.onresult = e => {
            let interim = '';
            let final   = '';
            for (let i = e.resultIndex; i < e.results.length; i++) {
                if (e.results[i].isFinal) final   += e.results[i][0].transcript + ' ';
                else                       interim += e.results[i][0].transcript;
            }
            savedText += final;
            textarea.value = savedText + interim;
            if (final) savedText = textarea.value.replace(interim, '');
        };
        recognition.onerror = e => {
            if (e.error !== 'no-speech') status.textContent = 'Mic error: ' + e.error + ' — check permissions';
        };
        recognition.onend = () => {
            if (recState[tNum] && recState[tNum].active) recognition.start();
        };

        recognition.start();

        let elapsed = 0;
        const timer = setInterval(() => {
            elapsed++;
            timeEl.textContent = formatTime(elapsed);
        }, 1000);

        recState[tNum] = { active: true, recognition, timer };
        btn.classList.add('recording');
        btn.style.background = '#ef4444';
        icon.className = 'bi bi-stop-fill';
        status.textContent = 'Recording & transcribing — press again to stop';
    }

    function buildTasksPayload() {
        const tasks = <?= json_encode(array_map(fn($t) => [
            'title'  => $t['title'],
            'prompt' => $t['prompt'],
        ], $tasks)) ?>;

        return Object.keys(tasks).map((key) => {
            const tNum        = parseInt(key, 10);
            const transcript  = (document.getElementById('transcript-' + tNum)?.value || '').trim();
            return { part: tNum, title: tasks[key].title, prompt: tasks[key].title + '\n\n' + tasks[key].prompt, transcription: transcript };
        });
    }

    async function submitAllParts() {
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

        document.querySelector('[onclick="submitAllParts()"]').disabled = true;
        document.getElementById('loadingSection').classList.remove('d-none');

        try {
            const res = await fetch('../../api/api_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    action:    'analyze_speaking_batch',
                    exam_type: 'CELPIP',
                    tasks:     tasks,
                }),
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
    </script>
</body>
</html>
