<?php
/**
 * CELPIP Full Mock — Speaking (online, recorded).
 * Generic across CELPIP_FULL_MOCK_A / CELPIP_FULL_MOCK_B, same convention as
 * celpip_full_mock_listening.php / celpip_full_mock_reading.php -- task
 * content is looked up by the session's mock_code, never hardcoded to one
 * test. This REPLACES the in-person-instructor notice (mock_speaking.php)
 * for CELPIP only; mock_speaking.php still handles IELTS speaking untouched.
 *
 * Reuses the same record -> upload -> Groq transcribe pipeline as the CELPIP
 * speaking practice tests (api/speaking_upload.php), passing mock_session_id
 * so admins can find these recordings in sls-admin alongside everything else
 * for this student. Final speaking_band/speaking_notes on mock_sessions are
 * still set by an instructor (existing sls-admin mock grading flow) -- this
 * page only captures the responses and flips status to
 * 'awaiting_speaking_grade', identical to mock_speaking.php's own submit step.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once CONFIG_PATH . '/email_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$student_id = (int)$_SESSION['user_id'];
$session_id = (int)($_GET['session_id'] ?? 0);
require_once INCLUDES_PATH . '/admin_check.php';
$isAdmin = is_platform_admin();

if (!$session_id) { header("Location: index.php"); exit(); }

$stmt = $db->prepare("
    SELECT ms.*, t.title AS mock_title, t.code AS mock_code,
           s.firstname, s.email AS student_email
    FROM mock_sessions ms
    JOIN tests t ON t.id = ms.mock_test_id
    JOIN students s ON s.id = ms.student_id
    WHERE ms.id = ? AND ms.student_id = ?
");
$stmt->execute([$session_id, $student_id]);
$session = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$session) { die("Session not found."); }

$map      = require INCLUDES_PATH . '/mock_test_map.php';
$mockCode = $session['mock_code'];

// Students must complete all written sections first; admins can preview freely
if (!$isAdmin && $session['status'] === 'in_progress') {
    $sectionFiles = $map[$mockCode] ?? [];
    if (is_null($session['listening_attempt_id'])) { $f = $sectionFiles['listening']['file'] ?? 'celpip_full_mock_listening.php'; header("Location: {$f}?session_id={$session_id}"); exit(); }
    if (is_null($session['reading_attempt_id']))   { $f = $sectionFiles['reading']['file'] ?? 'celpip_full_mock_reading.php'; header("Location: {$f}?session_id={$session_id}"); exit(); }
    if (is_null($session['writing_attempt_id']))   { header("Location: mock_writing.php?session_id={$session_id}"); exit(); }
}

$submitted = ($session['status'] !== 'in_progress');
$error     = '';

// ── Task content, generic across A/B (word-for-word from the CELPIP Teacher
// Support Pack's Practice Test A/B Speaking Test PDFs) ─────────────────────
$tasksByMock = [
    'CELPIP_FULL_MOCK_A' => [
        1 => [
            'title'  => 'Task 1: Giving Advice',
            'prep'   => 30, 'speak' => 90,
            'prompt' => "A friend is looking for a summer job. Advise him about different ways he can find work for the summer.",
        ],
        2 => [
            'title'  => 'Task 2: Talking about a Personal Experience',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "Talk about a great time you had with a family member or friend. Maybe you can talk about a party, something you did together at school, a time you travelled with a friend, or anything else you can remember. What happened and why was it memorable?",
        ],
        3 => [
            'title'  => 'Task 3: Describing a Scene',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "Describe some things that are happening in the picture below as well as you can. The person with whom you are speaking cannot see the picture.",
            'image'  => 'scene.jpg',
        ],
        4 => [
            'title'  => 'Task 4: Making Predictions',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "In this picture, what do you think will most probably happen next?",
            'image'  => 'scene.jpg',
        ],
        5 => [
            // Same documented simplification as the practice tests: the real
            // exam's two-stage selection timing is combined into one prep+speak
            // stage, with the third (competing) option described in text only.
            'title'  => 'Task 5: Comparing and Persuading',
            'prep'   => 60, 'speak' => 60,
            'prompt' => "Your family is relocating to another area, and you are looking for a new home there. You found two suitable options:\n\nOption A - New Downtown Townhouse: \$250,000, 3 bedrooms and 1 bathroom, 1,850 square feet, close to public transportation and shopping malls.\nOption B - Detached Home in Quiet Neighbourhood: \$300,000, 3 bedrooms and 3 bathrooms, 2,800 square feet, 30 minutes from downtown, 5 minutes from shops.\n\nChoose the option you prefer.\n\nYour family is now suggesting another house - a Charming Detached Home in the Country: \$210,000, 2 bedrooms and 1 bathroom, 1,000 square feet, 45 minutes from city, 25 minutes from shops. Persuade your family member that what you chose is more suitable by comparing the two.",
            'images' => ['task5a_option1.jpg', 'task5a_option2.jpg'],
        ],
        6 => [
            'title'  => 'Task 6: Dealing with a Difficult Situation',
            'prep'   => 60, 'speak' => 60,
            'prompt' => "A close cousin who lives in another country is coming to visit for a year. She tells you that she would like to stay at your place to explore your country's culture and to spend some time with you. Your roommate does not agree and says you will have to move out if she comes.\n\nChoose ONE:\nEITHER talk to your cousin. Explain why she cannot move in for a year.\nOR talk to your roommate. Explain why your cousin should be allowed to move in with you for a year.",
        ],
        7 => [
            'title'  => 'Task 7: Expressing Opinions',
            'prep'   => 30, 'speak' => 90,
            'prompt' => "Do you think that young adults should pay rent to their parents if they do not move out by the age of 21? Explain your reasons.",
        ],
        8 => [
            'title'  => 'Task 8: Describing an Unusual Situation',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "You are in a furniture store and you see a table you would like to buy, but the store clerk won't let you take a photo. Phone a member of your family. Provide a full and clear description of the table and ask if you can buy the table.",
            'image'  => 'painting.jpg',
        ],
    ],
    'CELPIP_FULL_MOCK_B' => [
        1 => [
            'title'  => 'Task 1: Giving Advice',
            'prep'   => 30, 'speak' => 90,
            'prompt' => "A classmate is not doing well in math class and has received several low grades. If this continues, he will not be able to pass the final exam. Give your classmate advice on what he should or should not do if he would like to improve his grades.",
        ],
        2 => [
            'title'  => 'Task 2: Talking about a Personal Experience',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "Talk about a time when something did not happen the way you first expected. Maybe you can talk about something that did not happen according to plan when you were working on a project, or when you were trying to accomplish something. At first, how did you expect things to happen, how did they actually happen, and what was your response?",
        ],
        3 => [
            'title'  => 'Task 3: Describing a Scene',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "Describe some things that are happening in the picture below as well as you can. The person with whom you are speaking cannot see the picture.",
            'image'  => 'scene.jpg',
        ],
        4 => [
            'title'  => 'Task 4: Making Predictions',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "In this picture, what do you think will most probably happen next?",
            'image'  => 'scene.jpg',
        ],
        5 => [
            'title'  => 'Task 5: Comparing and Persuading',
            'prep'   => 60, 'speak' => 60,
            'prompt' => "Your company is planning to move to a new office soon, and your boss has asked you to help find a new location. You find two suitable options:\n\nOption A - Industrial District: built in 1910, 10,000 square feet, rent \$10,000 per month, free parking for employees.\nOption B - Downtown Business Centre: built in 2012, 12,000 square feet, rent \$15,000 per month, close to bus and train stations.\n\nChoose the option you prefer.\n\nYour boss is now suggesting another office - Outside the City: built in 1930, 6,000 square feet, rent \$8,000 per month, 2 hours drive from the city. Persuade your boss that the office you chose is more suitable by comparing the two.",
            'images' => ['task5a_option1.jpg', 'task5a_option2.jpg'],
        ],
        6 => [
            'title'  => 'Task 6: Dealing with a Difficult Situation',
            'prep'   => 60, 'speak' => 60,
            'prompt' => "Your child's school has asked you to help with a class field trip. Your friend bought you tickets to a baseball game for that same day. The school will cancel the field trip if there are not enough parents, but your friend will be upset if you cannot go to the baseball game because the tickets were expensive.\n\nChoose ONE:\nEITHER talk to the school. Explain why you cannot help with the field trip.\nOR talk to your friend. Explain why you cannot attend the game.",
        ],
        7 => [
            'title'  => 'Task 7: Expressing Opinions',
            'prep'   => 30, 'speak' => 90,
            'prompt' => "Do you think all university students should study abroad for a year, if there is sufficient funding? Explain your reasons.",
        ],
        8 => [
            'title'  => 'Task 8: Describing an Unusual Situation',
            'prep'   => 30, 'speak' => 60,
            'prompt' => "You see a group of people playing a sport at a recreation centre. Call your friend Betty and describe in detail what the sport is like and what each player is doing. Ask her if she would be interested in trying this sport sometime.",
            'image'  => 'painting.jpg',
        ],
    ],
];

$tasks = $tasksByMock[$mockCode] ?? [];
if (!$tasks) {
    die("Speaking content not configured for this mock test. Please contact support.");
}
$imagesBase = ACADEMY_URL . 'assets/img/mock_tests/' . $mockCode . '/speaking/';

// ── Final submit: student has recorded & uploaded all 8 tasks ─────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $session['status'] === 'in_progress') {
    try {
        $db->prepare("UPDATE mock_sessions SET status = 'awaiting_speaking_grade', updated_at = NOW() WHERE id = ?")
           ->execute([$session_id]);

        $adminUrl = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
            ? 'http://localhost:8888/slslanguage.com/sls-admin/mock_sessions.php'
            : 'https://slslanguage.com/sls-admin/mock_sessions.php';

        $studentName = htmlspecialchars($session['firstname']);
        $mockTitle   = htmlspecialchars($session['mock_title']);

        send_email(
            SMTP_REPLY_TO,
            'SLS Admin',
            "Speaking Test Recorded — {$session['mock_title']}",
            "<p>Hello,</p>
             <p><strong>{$studentName}</strong> has completed and recorded the Speaking section of <strong>{$mockTitle}</strong>. Recordings and transcripts are ready for review in the student's profile.</p>
             <p><a href='{$adminUrl}'>Open Admin Panel to grade speaking →</a></p>
             <p style='color:#6b7280;font-size:.85em;'>Session ID: {$session_id}</p>",
        );

        $session['status'] = 'awaiting_speaking_grade';
        $submitted = true;

    } catch (PDOException $e) {
        error_log('celpip_full_mock_speaking.php: ' . $e->getMessage());
        $error = "Something went wrong submitting your speaking test. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Speaking — <?= htmlspecialchars($session['mock_title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link rel="stylesheet" href="<?= ACADEMY_URL ?>assets/css/exam_theme.css">
    <style>
        .speaking-task-card { background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); padding: 2rem; max-width: 760px; margin: 0 auto; }
        .speaking-progress-dots { display: flex; gap: .4rem; justify-content: center; margin-bottom: 1.5rem; }
        .speaking-progress-dots .dot { width: 10px; height: 10px; border-radius: 50%; background: var(--exam-line); }
        .speaking-progress-dots .dot.done { background: var(--exam-good); }
        .speaking-progress-dots .dot.current { background: var(--exam-accent); }
        .speaking-phase-pill { display: inline-block; padding: .3rem .9rem; border-radius: 20px; font-size: .8rem; font-weight: 700; }
        .speaking-phase-pill.is-prep { background: var(--exam-warn-soft); color: var(--exam-warn); }
        .speaking-phase-pill.is-speak { background: #fee2e2; color: #b91c1c; }
        .speaking-phase-pill.is-done { background: var(--exam-good-soft); color: var(--exam-good); }
        .speaking-timer { font-size: 2.5rem; font-weight: 700; text-align: center; font-variant-numeric: tabular-nums; margin: 1rem 0; }
        .speaking-prompt { font-size: 1.05rem; line-height: 1.6; white-space: pre-line; margin: 1.25rem 0; }
        .speaking-images { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-bottom: 1.25rem; }
        .speaking-images img { max-width: 320px; border-radius: 8px; border: 1px solid var(--exam-line); }
        .mic-indicator { display: flex; align-items: center; gap: .5rem; justify-content: center; color: var(--exam-ink-muted); font-size: .85rem; margin-top: 1rem; }
        .mic-indicator.recording { color: #b91c1c; font-weight: 600; }
        .mic-indicator .dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
        .mic-indicator.recording .dot { animation: pulse 1s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: .3; } }
        .rec-review-row { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; padding: .6rem 0; border-bottom: 1px solid var(--exam-line); }
        .rec-review-row:last-child { border-bottom: none; }
    </style>
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
                    <a href="<?= $map[$mockCode]['listening']['file'] ?? 'celpip_full_mock_listening.php' ?>?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                    <a href="<?= $map[$mockCode]['reading']['file'] ?? 'celpip_full_mock_reading.php' ?>?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                    <a href="mock_writing.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                    <a href="celpip_full_mock_speaking.php?session_id=<?= $session_id ?>" style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">🎤 Speaking</a>
                </div>
                <?php endif; ?>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="progress-steps">
                        <div class="step done"><div class="step-dot"></div>Listening</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step done"><div class="step-dot"></div>Reading</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step done"><div class="step-dot"></div>Writing</div>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                        <div class="step current"><div class="step-dot"></div>Speaking</div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <small class="text-muted"><?= htmlspecialchars($session['mock_title']) ?></small>
                        <button class="btn-exit" onclick="confirmExit()"><i class="bi bi-box-arrow-right me-1"></i> Exit</button>
                    </div>
                </div>
            </div>

            <div class="section-content" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($submitted): ?>
            <!-- Confirmation screen -->
            <div class="panel text-center">
                <div class="icon-circle" style="background:#dcfce7;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
                <span class="section-badge mb-3 d-inline-block">All Sections Complete</span>
                <h2 class="h4 fw-bold mt-3">You're all done!</h2>
                <p class="text-muted mt-2 mb-4">
                    Your Speaking responses have been recorded and submitted for grading, alongside your Listening,
                    Reading, and Writing sections. Your full results will be released on your dashboard once an
                    instructor has reviewed your Speaking test.
                </p>
                <div style="background:#f0fdf4;border-radius:10px;padding:1rem 1.5rem;margin-bottom:1.5rem;font-size:.88rem;color:#166534;">
                    <i class="bi bi-info-circle me-2"></i>
                    All four sections have been saved. Results will be available after Speaking is graded.
                </div>
                <a href="<?= ACADEMY_URL ?>learning_dashboard.php" class="btn btn-success px-4 fw-bold">
                    <i class="bi bi-house me-2"></i>Go to Dashboard
                </a>
            </div>

            <?php else: ?>
            <!-- Recording flow -->
            <div class="speaking-task-card">

                <div class="speaking-progress-dots" id="progressDots">
                    <?php foreach ($tasks as $tNum => $t): ?>
                        <div class="dot <?= $tNum === 1 ? 'current' : '' ?>" id="dot-<?= $tNum ?>"></div>
                    <?php endforeach; ?>
                </div>

                <div id="taskArea">
                    <?php foreach ($tasks as $tNum => $t): ?>
                    <div class="task-screen" data-task="<?= $tNum ?>" style="<?= $tNum === 1 ? '' : 'display:none;' ?>">
                        <div class="text-center mb-2">
                            <span class="speaking-phase-pill is-prep" id="phasePill-<?= $tNum ?>">Preparing</span>
                        </div>
                        <h5 class="text-center fw-bold mb-3"><?= htmlspecialchars($t['title']) ?></h5>
                        <div class="speaking-timer" id="timerDigits-<?= $tNum ?>"><?= $t['prep'] ?>s</div>
                        <div class="progress mb-3" style="height:6px;">
                            <div class="progress-bar" id="progressFill-<?= $tNum ?>" style="width:100%;"></div>
                        </div>
                        <div class="speaking-prompt"><?= htmlspecialchars($t['prompt']) ?></div>
                        <?php
                        $images = !empty($t['images']) ? $t['images'] : (!empty($t['image']) ? [$t['image']] : []);
                        if ($images):
                        ?>
                        <div class="speaking-images">
                            <?php foreach ($images as $img): ?>
                                <img src="<?= $imagesBase . htmlspecialchars($img) ?>" alt="Task image">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="mic-indicator" id="micIndicator-<?= $tNum ?>">
                            <span class="dot"></span> <span id="micText-<?= $tNum ?>">Microphone will start after preparation time</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div id="reviewArea" style="display:none;">
                    <h5 class="fw-bold mb-3 text-center">Review Your Recordings</h5>
                    <p class="text-muted text-center small mb-3">Listen back to any response before submitting. Once you submit, your instructor will be notified to grade your Speaking test.</p>
                    <div id="recordingsList" class="mb-4"></div>
                    <form method="POST" id="finalSubmitForm">
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold" id="finalSubmitBtn">
                            <i class="bi bi-send me-2"></i>Submit &amp; Notify Instructor
                        </button>
                    </form>
                </div>

            </div>
            <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (!$submitted): ?>
    <script>
    const SESSION_ID   = <?= json_encode($session_id) ?>;
    const MOCK_CODE    = <?= json_encode($mockCode) ?>;
    const TASK_PROMPTS = <?= json_encode(array_map(fn($t) => ['title' => $t['title'], 'prompt' => $t['prompt']], $tasks)) ?>;
    const TASK_PREP    = <?= json_encode(array_map(fn($t) => $t['prep'], $tasks)) ?>;
    const TASK_SPEAK   = <?= json_encode(array_map(fn($t) => $t['speak'], $tasks)) ?>;
    const TOTAL_TASKS  = <?= count($tasks) ?>;

    let currentTask = 1;
    let prepInterval = null, recInterval = null;
    let mediaStream = null, mediaRecorder = null, audioChunks = [];
    const recordedBlobs = {};
    const uploadPromises = [];
    let submitting = false;

    function setPhase(tNum, phase, text) {
        const pill = document.getElementById('phasePill-' + tNum);
        pill.classList.remove('is-prep', 'is-speak', 'is-done');
        pill.classList.add('is-' + phase);
        pill.textContent = text;
    }

    function startTaskFlow(tNum) {
        const prepSecs0 = TASK_PREP[tNum];
        const digitsEl = document.getElementById('timerDigits-' + tNum);
        const fillEl = document.getElementById('progressFill-' + tNum);
        let prepSecs = prepSecs0;

        setPhase(tNum, 'prep', 'Preparing');
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
        const digitsEl = document.getElementById('timerDigits-' + tNum);
        const fillEl = document.getElementById('progressFill-' + tNum);
        const micText = document.getElementById('micText-' + tNum);
        const micIndicator = document.getElementById('micIndicator-' + tNum);

        setPhase(tNum, 'speak', 'Speaking');
        micIndicator.classList.add('recording');
        micText.textContent = 'Recording your answer...';

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
                setPhase(tNum, 'done', "Time's up");
                micIndicator.classList.remove('recording');
                micText.textContent = 'Response recorded';
                advanceToNext(tNum);
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
        fd.append('test_code', MOCK_CODE);
        fd.append('mock_session_id', SESSION_ID);
        fd.append('task_number', tNum);
        fd.append('task_title', TASK_PROMPTS[tNum].title);
        fd.append('prompt', TASK_PROMPTS[tNum].prompt);
        try {
            const res = await fetch('<?php echo ACADEMY_URL; ?>api/speaking_upload.php', { method: 'POST', body: fd });
            return await res.json();
        } catch (err) {
            console.error('Upload failed for task ' + tNum + ':', err);
            return null;
        }
    }

    function advanceToNext(tNum) {
        document.getElementById('dot-' + tNum).classList.add('done');
        document.getElementById('dot-' + tNum).classList.remove('current');
        setTimeout(() => {
            if (tNum < TOTAL_TASKS) {
                document.querySelector('.task-screen[data-task="' + tNum + '"]').style.display = 'none';
                document.querySelector('.task-screen[data-task="' + (tNum + 1) + '"]').style.display = '';
                const nextDot = document.getElementById('dot-' + (tNum + 1));
                if (nextDot) nextDot.classList.add('current');
                currentTask = tNum + 1;
                startTaskFlow(currentTask);
            } else {
                finishRecording();
            }
        }, 1200);
    }

    async function finishRecording() {
        document.getElementById('taskArea').style.display = 'none';
        await Promise.allSettled(uploadPromises);
        renderReview();
        document.getElementById('reviewArea').style.display = '';
    }

    function renderReview() {
        const list = document.getElementById('recordingsList');
        const entries = Object.keys(recordedBlobs).map(Number).sort((a, b) => a - b);
        if (entries.length === 0) {
            list.innerHTML = '<p class="text-muted">No recordings were captured (microphone access may have been denied). You can still submit -- please contact your instructor.</p>';
            return;
        }
        list.innerHTML = entries.map(tNum => {
            const url = URL.createObjectURL(recordedBlobs[tNum]);
            const title = (TASK_PROMPTS[tNum] && TASK_PROMPTS[tNum].title) || ('Task ' + tNum);
            return `<div class="rec-review-row">
                <span class="fw-semibold" style="min-width:220px;">${title}</span>
                <audio controls src="${url}" style="height:32px;flex:1;min-width:200px;"></audio>
            </div>`;
        }).join('');
    }

    document.getElementById('finalSubmitForm').addEventListener('submit', () => {
        submitting = true;
    });

    function confirmExit() {
        if (confirm('Exit the speaking test? Your progress on this section will be lost.')) window.location.href = '<?= ACADEMY_URL ?>learning_dashboard.php';
    }
    window.addEventListener('beforeunload', e => { if (!submitting) { e.preventDefault(); e.returnValue = ''; } });

    startTaskFlow(1);
    </script>
    <?php endif; ?>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
