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
            // Real CELPIP structure (per official reference): a silent 60s
            // selection stage (no recording at all — pick a card) followed
            // by a SEPARATE persuasion stage that's the one actually
            // recorded, comparing your pick against a newly-suggested third
            // option. Previously these were wrongly combined into one
            // prep+speak stage with all three options described in text.
            'title'  => 'Task 5: Comparing and Persuading',
            'select' => [
                'intro' => "Your family is relocating to another area, and you are looking for a new home there. You found two suitable options. Using the pictures and information below, choose the option that you prefer. In the next section, you will need to persuade a family member that your choice is the better choice.",
                'prep'  => 60,
                'options' => [
                    ['title' => 'New Downtown Townhouse', 'image' => 'task5a_option1.jpg', 'bullets' => ['$250,000', '3 bedrooms and 1 bathroom', '1,850 square feet', 'Close to public transportation and shopping malls']],
                    ['title' => 'Detached Home in Quiet Neighbourhood', 'image' => 'task5a_option2.jpg', 'bullets' => ['$300,000', '3 bedrooms and 3 bathrooms', '2,800 square feet', '30 minutes from downtown; 5 minutes from shops']],
                ],
            ],
            'prep'   => 60, 'speak' => 60,
            'prompt' => "Your family is suggesting another house. Persuade your family member that what you chose is more suitable by comparing the two.",
            'compare' => [
                'heading' => "Your Family's Choice",
                'image'   => 'task5b_third.jpg',
                'title'   => 'Charming Detached Home in the Country',
                'bullets' => ['$210,000', '2 bedrooms and 1 bathroom', '1,000 square feet', '45 minutes from city; 25 minutes from shops'],
            ],
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
            'select' => [
                'intro' => "Your company is planning to move to a new office soon, and your boss has asked you to help find a new location. You found two suitable options. Using the pictures and information below, choose the option that you prefer. In the next section, you will need to persuade your boss that your choice is the better choice.",
                'prep'  => 60,
                'options' => [
                    ['title' => 'Industrial District', 'image' => 'task5a_option1.jpg', 'bullets' => ['Built in 1910', '10,000 square feet', 'Rent $10,000 per month', 'Free parking for employees']],
                    ['title' => 'Downtown Business Centre', 'image' => 'task5a_option2.jpg', 'bullets' => ['Built in 2012', '12,000 square feet', 'Rent $15,000 per month', 'Close to bus and train stations']],
                ],
            ],
            'prep'   => 60, 'speak' => 60,
            'prompt' => "Your boss is suggesting another office. Persuade your boss that what you chose is more suitable by comparing the two.",
            'compare' => [
                'heading' => "Your Boss's Suggestion",
                'image'   => 'task5b_third.jpg',
                'title'   => 'Office Outside the City',
                'bullets' => ['Built in 1930', '6,000 square feet', 'Rent $8,000 per month', '2 hours drive from the city'],
            ],
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
        /* Fills a lot more of the available box (was capped at 760px and
           left with just its natural content height) — same complaint as
           the Listening redesign: a narrow card floating in a sea of
           unused white space. Vertically centers whichever single
           task-screen (or the review/confirmation screen) is currently
           visible within a tall box instead. */
        #sectionContent { padding: 10px; }
        .speaking-task-card { background: var(--exam-surface); border: 1px solid var(--exam-line); border-radius: var(--exam-radius-lg); padding: 2rem; max-width: 900px; margin: 0 auto; min-height: calc(100vh - 260px); display: flex; flex-direction: column; justify-content: center; }
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
        /* Task 5's "Comparing and Persuading" cards — two side by side on
           the silent selection screen, one alone on the persuasion screen. */
        .celpip-compare-cards { display: flex; gap: 1.25rem; justify-content: center; flex-wrap: wrap; margin-bottom: 1.25rem; }
        .celpip-compare-card { border: 1px solid var(--exam-ink); border-radius: 10px; padding: 1rem; width: 100%; max-width: 320px; background: var(--exam-surface); }
        .celpip-compare-card img { width: 100%; height: 180px; object-fit: cover; border-radius: 6px; margin-bottom: .85rem; }
        .celpip-compare-card h6 { font-weight: 700; margin-bottom: .6rem; }
        .celpip-compare-heading { text-align: center; }
        .celpip-compare-card ul { padding-left: 1.1rem; margin-bottom: 0; font-size: .9rem; color: var(--exam-ink-muted); }
        .celpip-compare-card li { margin-bottom: .35rem; }
        .celpip-compare-check { display: flex; align-items: center; gap: .5rem; margin-top: .9rem; padding-top: .8rem; border-top: 1px solid var(--exam-line); font-size: .88rem; cursor: pointer; }
        .celpip-compare-check input { width: 18px; height: 18px; accent-color: var(--exam-accent); }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-3">
            <div class="sticky-header" id="stickyHeader">
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

            <div class="section-content" id="sectionContent" style="padding-top:<?= $isAdmin ? '110px' : '60px' ?>;">

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

                <!-- Mic permission gate: the browser's own permission prompt is
                     easy to miss (a small address-bar popup, not a page
                     element) if it only appears mid-task the first time
                     getUserMedia() is called during Task 1's recording phase.
                     A student who missed or dismissed it had no way back in
                     -- the test just silently recorded nothing for every task.
                     Requesting it upfront, with a clear button and explicit
                     retry/troubleshooting on denial, fixes that. -->
                <div id="micGate" style="text-align:center;padding:2.5rem 1.5rem;">
                    <i class="bi bi-mic-fill" style="font-size:2.5rem;color:#0b77ff;"></i>
                    <h4 class="fw-bold mt-3 mb-2">Microphone Access Required</h4>
                    <p class="text-muted mb-4" style="max-width:480px;margin:0 auto 1.5rem;">This test records your spoken responses for all 8 tasks. Click below and select <strong>"Allow"</strong> when your browser asks for microphone access.</p>
                    <button type="button" class="btn btn-primary btn-lg" id="micGateBtn" onclick="requestMicAccess()"><i class="bi bi-mic-fill me-2"></i>Enable Microphone &amp; Start Test</button>
                    <div id="micGateError" class="alert alert-danger mt-3" style="display:none;max-width:480px;margin:1rem auto 0;text-align:left;"></div>
                    <?php if ($isAdmin): ?>
                    <div class="mt-3"><a href="#" onclick="skipMicGate(); return false;" class="text-muted small">Skip (admin) — continue without microphone</a></div>
                    <?php endif; ?>
                </div>

                <div id="speakingMain" style="display:none;">
                <div class="speaking-progress-dots" id="progressDots">
                    <?php foreach ($tasks as $tNum => $t): ?>
                        <div class="dot <?= $tNum === 1 ? 'current' : '' ?>" id="dot-<?= $tNum ?>"></div>
                    <?php endforeach; ?>
                </div>

                <div id="taskArea">
                    <?php foreach ($tasks as $tNum => $t): ?>
                    <?php if (!empty($t['select'])): $sel = $t['select']; ?>
                    <!-- Selection stage: silent, timed, pick-a-card — no
                         recording happens here at all (see reference
                         screenshot: "You do not need to speak for this
                         part."). Shown before the task's normal prep+speak
                         screen. -->
                    <div class="task-select-screen" data-task="<?= $tNum ?>" style="display:none;">
                        <div class="text-center mb-2">
                            <span class="speaking-phase-pill is-prep" id="selectPhasePill-<?= $tNum ?>">Preparing</span>
                        </div>
                        <h5 class="text-center fw-bold mb-3"><?= htmlspecialchars($t['title']) ?></h5>
                        <div class="speaking-timer" id="selectTimerDigits-<?= $tNum ?>"><?= $sel['prep'] ?>s</div>
                        <div class="progress mb-3" style="height:6px;">
                            <div class="progress-bar" id="selectProgressFill-<?= $tNum ?>" style="width:100%;"></div>
                        </div>
                        <div class="speaking-prompt"><?= htmlspecialchars($sel['intro']) ?></div>
                        <p class="text-center fw-semibold text-muted mb-3">You do not need to speak for this part.</p>
                        <div class="celpip-compare-cards">
                            <?php foreach ($sel['options'] as $i => $opt): ?>
                            <div class="celpip-compare-card">
                                <img src="<?= $imagesBase . htmlspecialchars($opt['image']) ?>" alt="<?= htmlspecialchars($opt['title']) ?>">
                                <h6><?= htmlspecialchars($opt['title']) ?></h6>
                                <ul><?php foreach ($opt['bullets'] as $b): ?><li><?= htmlspecialchars($b) ?></li><?php endforeach; ?></ul>
                                <label class="celpip-compare-check">
                                    <input type="checkbox" onchange="selectCompareOption(<?= $tNum ?>, <?= $i ?>, this)">
                                    Check if this is your choice.
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($isAdmin): ?>
                        <div class="text-center mt-3 d-flex gap-2 justify-content-center">
                            <?php if ($tNum > 1): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="adminPrevTask(<?= $tNum ?>)">
                                <i class="bi bi-skip-backward-fill me-1"></i>Previous (admin)
                            </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="adminSkipTask(<?= $tNum ?>)">
                                <i class="bi bi-skip-forward-fill me-1"></i>Next (admin)
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="task-screen" data-task="<?= $tNum ?>" style="<?= ($tNum === 1 && empty($t['select'])) ? '' : 'display:none;' ?>">
                        <div class="text-center mb-2">
                            <span class="speaking-phase-pill is-prep" id="phasePill-<?= $tNum ?>">Preparing</span>
                        </div>
                        <h5 class="text-center fw-bold mb-3"><?= htmlspecialchars($t['title']) ?></h5>
                        <div class="speaking-timer" id="timerDigits-<?= $tNum ?>"><?= $t['prep'] ?>s</div>
                        <div class="progress mb-3" style="height:6px;">
                            <div class="progress-bar" id="progressFill-<?= $tNum ?>" style="width:100%;"></div>
                        </div>
                        <div class="speaking-prompt"><?= htmlspecialchars($t['prompt']) ?></div>
                        <?php if (!empty($t['compare'])): $cmp = $t['compare']; ?>
                        <!-- The student's own pick from the silent selection stage is
                             filled in here by JS (renderYourChoiceCard) once we know
                             which checkbox they ticked -- shown side by side with the
                             newly-suggested option, since the task asks them to compare
                             the two out loud and they can't do that from memory alone. -->
                        <div class="celpip-compare-cards">
                            <div class="celpip-compare-card" id="yourChoiceCard-<?= $tNum ?>" style="display:none;"></div>
                            <div class="celpip-compare-card">
                                <?php if (!empty($cmp['heading'])): ?><h6 class="celpip-compare-heading"><?= htmlspecialchars($cmp['heading']) ?></h6><?php endif; ?>
                                <img src="<?= $imagesBase . htmlspecialchars($cmp['image']) ?>" alt="<?= htmlspecialchars($cmp['title']) ?>">
                                <h6><?= htmlspecialchars($cmp['title']) ?></h6>
                                <ul><?php foreach ($cmp['bullets'] as $b): ?><li><?= htmlspecialchars($b) ?></li><?php endforeach; ?></ul>
                            </div>
                        </div>
                        <?php else:
                        $images = !empty($t['images']) ? $t['images'] : (!empty($t['image']) ? [$t['image']] : []);
                        if ($images):
                        ?>
                        <div class="speaking-images">
                            <?php foreach ($images as $img): ?>
                                <img src="<?= $imagesBase . htmlspecialchars($img) ?>" alt="Task image">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; endif; ?>
                        <div class="mic-indicator" id="micIndicator-<?= $tNum ?>">
                            <span class="dot"></span> <span id="micText-<?= $tNum ?>">Microphone will start after preparation time</span>
                        </div>
                        <?php if ($isAdmin): ?>
                        <div class="text-center mt-3 d-flex gap-2 justify-content-center">
                            <?php if ($tNum > 1): ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="adminPrevTask(<?= $tNum ?>)">
                                <i class="bi bi-skip-backward-fill me-1"></i>Previous (admin)
                            </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="adminSkipTask(<?= $tNum ?>)">
                                <i class="bi bi-skip-forward-fill me-1"></i>Next (admin)
                            </button>
                        </div>
                        <?php endif; ?>
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
                </div><!-- /#speakingMain -->

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
    const IS_ADMIN     = <?= $isAdmin ? 'true' : 'false' ?>;
    // Only tasks with a silent selection stage (Task 5) get an entry here.
    const TASK_SELECT_PREP = <?= json_encode(array_map(fn($t) => $t['select']['prep'] ?? null, $tasks)) ?>;
    const TASK_SELECT_OPTIONS = <?= json_encode(array_map(fn($t) => $t['select']['options'] ?? null, $tasks)) ?>;
    const IMAGES_BASE = <?= json_encode($imagesBase) ?>;

    let currentTask = 1;
    let prepInterval = null, recInterval = null;
    let mediaStream = null, mediaRecorder = null;
    const recordedBlobs = {};
    const uploadPromises = [];
    const taskPhase = {}; // tNum -> 'prep' | 'speak' | 'done', for admin Next/Previous
    const recordingStartedAt = {}; // tNum -> Date.now() when beginRecording() ran, for the grace-window guard below
    const selectedChoice = {}; // tNum -> index into TASK_SELECT_OPTIONS[tNum], whichever card the student checked in the silent selection stage
    let transitioning = false; // true while a "stop + advance" is in flight, so a stray click on the still-visible button during advanceToNext's delay can't re-trigger it for the same task
    let submitting = false;

    function setPhase(tNum, phase, text) {
        const pill = document.getElementById('phasePill-' + tNum);
        pill.classList.remove('is-prep', 'is-speak', 'is-done');
        pill.classList.add('is-' + phase);
        pill.textContent = text;
    }

    // Entry point for a task: Task 5 (and any future task with a silent
    // selection stage) shows that stage first; everything else goes
    // straight into the normal prep/speak flow, unchanged.
    function beginTask(tNum) {
        transitioning = false; // this task's own flow is starting -- admin Next/Previous clicks for it are safe again
        if (TASK_SELECT_PREP[tNum]) {
            startSelectStage(tNum);
        } else {
            document.querySelector('.task-screen[data-task="' + tNum + '"]').style.display = '';
            startTaskFlow(tNum);
        }
    }

    function startSelectStage(tNum) {
        document.querySelector('.task-select-screen[data-task="' + tNum + '"]').style.display = '';
        taskPhase[tNum] = 'select';
        const digitsEl = document.getElementById('selectTimerDigits-' + tNum);
        const fillEl = document.getElementById('selectProgressFill-' + tNum);
        const prepSecs0 = TASK_SELECT_PREP[tNum];
        let prepSecs = prepSecs0;
        fillEl.style.width = '100%';

        if (IS_ADMIN) {
            digitsEl.textContent = '';
            return;
        }

        digitsEl.textContent = prepSecs + 's';
        prepInterval = setInterval(() => {
            prepSecs--;
            digitsEl.textContent = Math.max(prepSecs, 0) + 's';
            fillEl.style.width = Math.max(0, (prepSecs / prepSecs0) * 100) + '%';
            if (prepSecs <= 0) {
                clearInterval(prepInterval);
                finishSelectStage(tNum);
            }
        }, 1000);
    }

    function finishSelectStage(tNum) {
        document.querySelector('.task-select-screen[data-task="' + tNum + '"]').style.display = 'none';
        document.querySelector('.task-screen[data-task="' + tNum + '"]').style.display = '';
        renderYourChoiceCard(tNum);
        startTaskFlow(tNum);
    }

    // The student's pick now DOES need to carry over — the persuasion stage
    // asks them to compare their choice against the new suggestion, so it
    // has to stay visible alongside it instead of relying on their memory.
    // Checkboxes behave as a mutually-exclusive pair (only one "choice"
    // makes sense).
    function selectCompareOption(tNum, idx, el) {
        document.querySelectorAll('.task-select-screen[data-task="' + tNum + '"] .celpip-compare-check input')
            .forEach((cb, i) => { if (i !== idx) cb.checked = false; });
        selectedChoice[tNum] = el.checked ? idx : undefined;
    }

    // Fills the "your choice" card on the persuasion screen with whichever
    // option the student actually checked during the silent selection
    // stage, so both options are visible side by side while they speak.
    function renderYourChoiceCard(tNum) {
        const card = document.getElementById('yourChoiceCard-' + tNum);
        const options = TASK_SELECT_OPTIONS[tNum];
        if (!card || !options) return;
        const idx = selectedChoice[tNum];
        const opt = (idx !== undefined && idx !== null) ? options[idx] : null;
        if (!opt) {
            // Student never checked either box during the silent stage --
            // nothing to show here, leave it hidden rather than guessing.
            card.style.display = 'none';
            return;
        }
        card.innerHTML = '<h6 class="celpip-compare-heading">Your Choice</h6>' +
            '<img src="' + IMAGES_BASE + opt.image + '" alt="' + opt.title + '">' +
            '<h6>' + opt.title + '</h6>' +
            '<ul>' + opt.bullets.map(b => '<li>' + b + '</li>').join('') + '</ul>';
        card.style.display = '';
    }

    function startTaskFlow(tNum) {
        const prepSecs0 = TASK_PREP[tNum];
        const digitsEl = document.getElementById('timerDigits-' + tNum);
        const fillEl = document.getElementById('progressFill-' + tNum);
        let prepSecs = prepSecs0;

        taskPhase[tNum] = 'prep';
        setPhase(tNum, 'prep', 'Preparing');
        fillEl.style.width = '100%';

        // Admins are untimed, full stop — no countdown ever runs, not just
        // a skip button layered on top of one. Real timed testing only
        // happens on a genuine student account; admin preview is
        // self-paced via the Next/Previous buttons only.
        if (IS_ADMIN) {
            digitsEl.textContent = '';
            return;
        }

        digitsEl.textContent = prepSecs + 's';
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

        taskPhase[tNum] = 'speak';
        recordingStartedAt[tNum] = Date.now();
        setPhase(tNum, 'speak', 'Speaking');
        micIndicator.classList.add('recording');
        micText.textContent = 'Recording your answer...';

        const speakSecs0 = TASK_SPEAK[tNum];
        let speakSecs = speakSecs0;
        fillEl.style.width = '100%';
        startAudioCapture(tNum);

        // Same untimed rule as prep — recording still starts (that's the
        // whole point, verifying the mic), it just never auto-stops on a
        // clock. Admin stops it manually via the Next button.
        if (IS_ADMIN) {
            digitsEl.textContent = '';
            return;
        }

        digitsEl.textContent = speakSecs + 's';
        recInterval = setInterval(() => {
            speakSecs--;
            digitsEl.textContent = Math.max(speakSecs, 0) + 's';
            fillEl.style.width = Math.max(0, (speakSecs / speakSecs0) * 100) + '%';
            if (speakSecs <= 0) {
                clearInterval(recInterval);
                setPhase(tNum, 'done', "Time's up");
                // micText is set honestly inside stopAudioCaptureAndUpload's
                // onstop callback once we actually know whether audio was
                // captured — it used to be hardcoded to "Response recorded"
                // right here regardless of outcome, which lied about failed
                // mic permissions.
                stopAudioCaptureAndUpload(tNum);
                advanceToNext(tNum);
            }
        }, 1000);
    }

    async function startAudioCapture(tNum) {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            // A recording is already active (e.g. a duplicate beginRecording
            // call raced in) -- never start a second one on top of it, that
            // was the root cause of a task's real recording being silently
            // swapped for a near-empty one: the shared audioChunks array got
            // reset out from under the first recorder before its own onstop
            // had a chance to read it.
            console.warn('startAudioCapture(' + tNum + ') ignored -- a recording is already in progress.');
            return;
        }
        // Each recording gets its OWN chunks array, stashed on the recorder
        // instance itself (not a shared outer variable) -- so even if two
        // recordings ever do overlap, one can never clobber the other's data.
        const chunks = [];
        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            const recorder = new MediaRecorder(mediaStream);
            recorder._chunks = chunks;
            recorder.ondataavailable = (e) => { if (e.data.size > 0) chunks.push(e.data); };
            recorder.start();
            mediaRecorder = recorder;
        } catch (err) {
            console.error('Microphone access failed for task ' + tNum + ':', err);
            mediaRecorder = null;
        }
    }

    function stopAudioCaptureAndUpload(tNum) {
        const micIndicator = document.getElementById('micIndicator-' + tNum);
        const micText = document.getElementById('micText-' + tNum);
        if (micIndicator) micIndicator.classList.remove('recording');

        if (!mediaRecorder || mediaRecorder.state === 'inactive') {
            // getUserMedia/MediaRecorder never started (mic blocked/unavailable)
            // — say so immediately instead of silently doing nothing, so a
            // broken mic is obvious on task 1 rather than only discovered at
            // the very end of the section.
            if (micText) micText.textContent = 'No recording captured — check your microphone permissions';
            return;
        }
        const recorder = mediaRecorder;
        const stream = mediaStream;
        const chunks = recorder._chunks || [];
        recorder.onstop = () => {
            const blob = new Blob(chunks, { type: 'audio/webm' });
            if (blob.size > 0) {
                recordedBlobs[tNum] = blob;
                uploadPromises.push(uploadRecording(tNum, blob));
                if (micText) micText.textContent = 'Response recorded ✓ — play it back below';
                showInlinePreview(tNum, blob);
            } else if (micText) {
                micText.textContent = 'No audio captured — check your microphone';
            }
            stream.getTracks().forEach(t => t.stop());
        };
        recorder.stop();
        mediaRecorder = null;
    }

    // Immediate, verifiable proof the recording worked — a playable clip
    // right under the task, not just a text label. Previously the only
    // playback existed in the end-of-section review screen, so a broken mic
    // on task 1 went unnoticed until all 8 tasks were already done.
    function showInlinePreview(tNum, blob) {
        const micIndicator = document.getElementById('micIndicator-' + tNum);
        if (!micIndicator) return;
        let audioEl = document.getElementById('micPreview-' + tNum);
        if (!audioEl) {
            audioEl = document.createElement('audio');
            audioEl.id = 'micPreview-' + tNum;
            audioEl.controls = true;
            audioEl.style.cssText = 'height:30px;margin-top:.6rem;display:block;width:100%;max-width:360px;margin-left:auto;margin-right:auto;';
            micIndicator.insertAdjacentElement('afterend', audioEl);
        }
        audioEl.src = URL.createObjectURL(blob);
    }

    function adminSkipTask(tNum) {
        if (!IS_ADMIN || transitioning) return;
        if (taskPhase[tNum] === 'select') {
            clearInterval(prepInterval);
            finishSelectStage(tNum);
            return;
        }
        if (taskPhase[tNum] === 'prep') {
            // Skip the WAIT, not the recording itself — jumping straight to
            // the next task from here would mean the mic never actually
            // runs, which is why the recorder looked broken: Next was
            // always clicked during prep, before recording ever started.
            clearInterval(prepInterval);
            beginRecording(tNum);
            return;
        }
        if (Date.now() - (recordingStartedAt[tNum] || 0) < 1200) {
            // Ignore a second click landing within ~1s of recording
            // starting -- e.g. clicking Next again out of habit, thinking
            // the first click (which only started the recording) didn't
            // register. Without this guard that second click stopped the
            // recording almost immediately, uploading a real but useless
            // ~1-second clip instead of the actual response.
            return;
        }
        // advanceToNext() below leaves this task's screen (and this same
        // button) visible for ~1.2s before switching to the next task, so a
        // stray/impatient second click in that window used to re-enter this
        // function for the SAME tNum -- taskPhase[tNum] was never flipped to
        // 'done', so it looked identical to a fresh "stop" request. Blocking
        // re-entry here until the next task's flow actually starts closes
        // that window.
        transitioning = true;
        taskPhase[tNum] = 'done';
        clearInterval(recInterval);
        setPhase(tNum, 'done', 'Skipped (admin)');
        stopAudioCaptureAndUpload(tNum);
        advanceToNext(tNum);
    }

    function adminPrevTask(tNum) {
        if (!IS_ADMIN || tNum <= 1) return;
        clearInterval(prepInterval);
        clearInterval(recInterval);
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            try { mediaRecorder.stop(); } catch (e) {}
            mediaRecorder = null;
        }
        if (mediaStream) { mediaStream.getTracks().forEach(t => t.stop()); mediaStream = null; }

        // Hide whichever screen is currently showing for this task — the
        // select stage (Task 5) or the normal prep/speak screen.
        const selectScreen = document.querySelector('.task-select-screen[data-task="' + tNum + '"]');
        if (selectScreen) selectScreen.style.display = 'none';
        document.querySelector('.task-screen[data-task="' + tNum + '"]').style.display = 'none';

        document.getElementById('dot-' + tNum).classList.remove('current');
        document.getElementById('dot-' + (tNum - 1)).classList.remove('done');
        document.getElementById('dot-' + (tNum - 1)).classList.add('current');
        currentTask = tNum - 1;
        beginTask(currentTask);
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
                const nextDot = document.getElementById('dot-' + (tNum + 1));
                if (nextDot) nextDot.classList.add('current');
                currentTask = tNum + 1;
                // beginTask (not startTaskFlow) — the next task may have its
                // own silent selection stage (Task 5) that must show first;
                // beginTask() shows the right screen and starts its timer.
                beginTask(currentTask);
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

    // The sticky header's real height varies (admin banner adds a row) — a
    // hardcoded padding-top guess either leaves a gap or lets the header
    // overlap the content. Measure it for real instead, with a small
    // breathing gap, and keep it in sync on resize.
    function syncContentOffset() {
        const header = document.getElementById('stickyHeader');
        const content = document.getElementById('sectionContent');
        if (!header || !content) return;
        content.style.paddingTop = (header.getBoundingClientRect().height + 10) + 'px';
    }
    window.addEventListener('resize', syncContentOffset);
    syncContentOffset();

    // Ask for microphone access upfront, with a real page element the
    // student can't miss the way they could miss the browser's own
    // permission popup — and a clear retry path if they deny it by mistake.
    async function requestMicAccess() {
        const btn = document.getElementById('micGateBtn');
        const errEl = document.getElementById('micGateError');
        if (!btn) return;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Requesting access…';
        errEl.style.display = 'none';
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            // Only checking permission here -- stop it immediately, the real
            // recording starts fresh per-task later.
            stream.getTracks().forEach(t => t.stop());
            document.getElementById('micGate').style.display = 'none';
            document.getElementById('speakingMain').style.display = '';
            syncContentOffset();
            beginTask(1);
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-mic-fill me-2"></i>Try Again';
            let msg = 'Could not access your microphone: ' + (err.message || err.name || 'unknown error') + '.';
            if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                msg = 'Microphone access was denied. Click the padlock or info icon in your browser\'s address bar, allow microphone access for this site, then click "Try Again" below.';
            } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                msg = 'No microphone was found on this device. Please connect a microphone and click "Try Again".';
            }
            errEl.textContent = msg;
            errEl.style.display = '';
        }
    }

    function skipMicGate() {
        document.getElementById('micGate').style.display = 'none';
        document.getElementById('speakingMain').style.display = '';
        syncContentOffset();
        beginTask(1);
    }
    </script>
    <?php endif; ?>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
