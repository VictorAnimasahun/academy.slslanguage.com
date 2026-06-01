<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
require_once CONFIG_PATH . '/email_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}

$student_id    = (int)$_SESSION['user_id'];
$session_id    = (int)($_GET['session_id'] ?? 0);
$adminEmails   = ['v.animasahun@slslanguage.com', 'animasahunvictor1@gmail.com', 'ashonibarevik@gmail.com'];
$isAdmin       = in_array($_SESSION['user_email'] ?? '', $adminEmails);
$submitted     = false;
$error         = '';

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
$submitted = ($session['status'] !== 'in_progress');

// Students must complete all written sections first; admins can preview freely
if (!$isAdmin && $session['status'] === 'in_progress') {
    if (is_null($session['listening_attempt_id'])) { header("Location: full_mock_001_listening.php?session_id={$session_id}"); exit(); }
    if (is_null($session['reading_attempt_id']))   { header("Location: full_mock_001_reading.php?session_id={$session_id}"); exit(); }
    if (is_null($session['writing_attempt_id']))   { header("Location: mock_writing.php?session_id={$session_id}"); exit(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $session['status'] === 'in_progress') {
    try {
        $db->prepare("UPDATE mock_sessions SET status = 'awaiting_speaking_grade', updated_at = NOW() WHERE id = ?")
           ->execute([$session_id]);

        $adminUrl  = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
            ? 'http://localhost:8888/slslanguage.com/sls-admin/mock_sessions.php'
            : 'https://slslanguage.com/sls-admin/mock_sessions.php';

        $studentName = htmlspecialchars($session['firstname']);
        $mockTitle   = htmlspecialchars($session['mock_title']);

        send_email(
            SMTP_REPLY_TO,
            'SLS Admin',
            "Speaking Test Pending — {$session['mock_title']}",
            "<p>Hello,</p>
             <p><strong>{$studentName}</strong> has completed the written sections of <strong>{$mockTitle}</strong> and is awaiting their speaking assessment.</p>
             <p><a href='{$adminUrl}'>Open Admin Panel to grade speaking →</a></p>
             <p style='color:#6b7280;font-size:.85em;'>Session ID: {$session_id}</p>",
        );

        $session['status'] = 'awaiting_speaking_grade';
        $submitted = true;

    } catch (PDOException $e) {
        error_log('mock_speaking.php: ' . $e->getMessage());
        $error = "Something went wrong. Please try again.";
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
    <style>
        .panel { background:#fff; border-radius:16px; padding:2.5rem; box-shadow:0 4px 20px rgba(0,0,0,.07); max-width:680px; margin:2rem auto; text-align:center; }
        .section-badge { background:linear-gradient(135deg,#10b981,#34d399); color:#fff; padding:.45rem 1.25rem; border-radius:50px; font-weight:700; font-size:.88rem; }
        .icon-circle { width:80px; height:80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:1.5rem auto; font-size:2.2rem; }
        .progress-steps { display:flex; gap:.5rem; align-items:center; justify-content:center; }
        .step { display:flex; align-items:center; gap:.35rem; font-size:.8rem; color:#94a3b8; }
        .step.done { color:#10b981; } .step.current { color:#10b981; font-weight:600; }
        .step-dot { width:8px; height:8px; border-radius:50%; background:currentColor; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-3">
            <?php if ($isAdmin): ?>
            <div style="background:#1e1b4b;color:#c7d2fe;padding:.6rem 1.25rem;border-radius:10px;margin-bottom:1rem;display:flex;align-items:center;gap:1.5rem;font-size:.82rem;font-weight:600;">
                <span style="color:#a5b4fc;text-transform:uppercase;letter-spacing:.08em;font-size:.7rem;">Admin Preview</span>
                <a href="full_mock_001_listening.php?session_id=<?= $session_id ?>" style="color:#a5b4fc;text-decoration:none;">🎧 Listening</a>
                <a href="full_mock_001_reading.php?session_id=<?= $session_id ?>"   style="color:#a5b4fc;text-decoration:none;">📖 Reading</a>
                <a href="mock_writing.php?session_id=<?= $session_id ?>"            style="color:#a5b4fc;text-decoration:none;">✍️ Writing</a>
                <a href="mock_speaking.php?session_id=<?= $session_id ?>"           style="color:#c7d2fe;text-decoration:none;border-bottom:2px solid #6366f1;padding-bottom:2px;">🎤 Speaking</a>
            </div>
            <?php endif; ?>
            <div class="progress-steps mb-4">
                <div class="step done"><div class="step-dot"></div>Listening</div>
                <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                <div class="step done"><div class="step-dot"></div>Reading</div>
                <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                <div class="step done"><div class="step-dot"></div>Writing</div>
                <i class="bi bi-chevron-right text-muted" style="font-size:.7rem;"></i>
                <div class="step current"><div class="step-dot"></div>Speaking</div>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($submitted || $session['status'] !== 'in_progress'): ?>
            <!-- Confirmation screen -->
            <div class="panel">
                <div class="icon-circle" style="background:#dcfce7;">
                    <i class="bi bi-check-circle-fill text-success"></i>
                </div>
                <span class="section-badge mb-3 d-inline-block">Written Sections Complete</span>
                <h2 class="h4 fw-bold mt-3">You're all done with the written test!</h2>
                <p class="text-muted mt-2 mb-4">
                    An instructor will contact you to schedule and administer your <strong>IELTS Speaking</strong> assessment.
                    Please check your email for scheduling details.
                    Once your speaking test is graded, your full results will be released on your dashboard.
                </p>
                <div style="background:#f0fdf4;border-radius:10px;padding:1rem 1.5rem;margin-bottom:1.5rem;font-size:.88rem;color:#166534;">
                    <i class="bi bi-info-circle me-2"></i>
                    Your Listening, Reading, and Writing scores have been saved. Results will be available after speaking is graded.
                </div>
                <a href="<?= ACADEMY_URL ?>learning_dashboard.php" class="btn btn-success px-4 fw-bold">
                    <i class="bi bi-house me-2"></i>Go to Dashboard
                </a>
            </div>

            <?php else: ?>
            <!-- Submit screen -->
            <div class="panel">
                <div class="icon-circle" style="background:#dcfce7;">
                    <i class="bi bi-mic-fill text-success" style="font-size:2.2rem;"></i>
                </div>
                <span class="section-badge mb-3 d-inline-block">Section 4 — Speaking</span>
                <h2 class="h4 fw-bold mt-3">One section remaining</h2>
                <p class="text-muted mt-2 mb-4">
                    The Speaking section of your mock test cannot be completed online. An instructor will contact you
                    to administer the Speaking assessment in person. Click the button below to submit your written
                    sections and notify your instructor.
                </p>
                <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:10px;padding:.9rem 1.25rem;margin-bottom:1.75rem;font-size:.88rem;text-align:left;">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    Once you submit, you will not be able to modify your Listening, Reading, or Writing answers.
                </div>
                <form method="POST">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                        <i class="bi bi-send me-2"></i>Submit &amp; Notify Instructor
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
