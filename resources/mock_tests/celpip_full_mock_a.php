<?php
/**
 * CELPIP Full Mock Test A — session launcher.
 * Routes through the DB-driven mock session system, same pattern as
 * ielts_full_mock_003.php. Content seeded by migration 073.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$student_id = (int)$_SESSION['user_id'];
$action     = $_GET['action'] ?? '';

$stmt = $db->prepare("SELECT id, code FROM tests WHERE code = 'CELPIP_FULL_MOCK_A' AND is_active = 1 LIMIT 1");
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("CELPIP Full Mock Test A is not configured yet. Please run the database migrations for this mock and contact your instructor.");
}

$test_id  = (int)$test['id'];

// Resume an existing in-progress session if one exists
$stmt = $db->prepare("
    SELECT * FROM mock_sessions
    WHERE student_id = ? AND mock_test_id = ? AND status = 'in_progress'
    ORDER BY created_at DESC LIMIT 1
");
$stmt->execute([$student_id, $test_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

function celpipStartFresh(PDO $db, int $test_id, int $student_id): void {
    $stmt = $db->prepare("INSERT INTO mock_sessions (mock_test_id, student_id, status) VALUES (?, ?, 'in_progress')");
    $stmt->execute([$test_id, $student_id]);
    $session_id = (int)$db->lastInsertId();
    header("Location: celpip_full_mock_listening.php?session_id={$session_id}");
    exit();
}

// An existing in-progress attempt exists, and the student hasn't chosen
// yet — show a Resume/Start Over choice instead of silently resuming.
// Silently auto-resuming was confusing: a student clicking "Open Test"
// expecting a fresh attempt would land wherever they'd previously left
// off (e.g. straight into Speaking) with no way back to Listening.
if ($existing && $action === '') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resume or Start Over — CELPIP Full Mock A | EduHub</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    </head>
    <body class="light">
        <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <?php include INCLUDES_PATH . '/navbar.php'; ?>
        <div class="main-wrapper flex-grow-1" style="flex:1;">
            <?php include INCLUDES_PATH . '/topbar.php'; ?>
            <main class="content p-4">
                <div style="max-width:560px;margin:2rem auto;">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-hourglass-split" style="font-size:2.2rem;color:#0b77ff;"></i>
                            <h4 class="fw-bold mt-3 mb-2">You have an attempt in progress</h4>
                            <p class="text-muted mb-4">You started CELPIP Full Mock A earlier but haven't finished it. Would you like to pick up where you left off, or start over from Listening?</p>
                            <div class="d-flex flex-column gap-2">
                                <a href="?action=resume" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Resume where I left off</a>
                                <a href="?action=restart" class="btn btn-outline-danger" onclick="return confirm('Starting over abandons your in-progress attempt (any sections already completed on it will not count). Continue?');"><i class="bi bi-arrow-counterclockwise me-2"></i>Start over from Listening</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
        <?php include INCLUDES_PATH . '/footer.php'; ?>
    </body>
    </html>
    <?php
    exit();
}

if ($existing && $action === 'restart') {
    celpipStartFresh($db, $test_id, $student_id);
}

if ($existing) {
    // $action === 'resume' (or any other value) falls through to resuming
    $sid = $existing['id'];
    if (is_null($existing['listening_attempt_id'])) {
        header("Location: celpip_full_mock_listening.php?session_id={$sid}");
    } elseif (is_null($existing['reading_attempt_id'])) {
        header("Location: celpip_full_mock_reading.php?session_id={$sid}");
    } elseif (is_null($existing['writing_attempt_id'])) {
        header("Location: mock_writing.php?session_id={$sid}");
    } else {
        header("Location: celpip_full_mock_speaking.php?session_id={$sid}");
    }
    exit();
}

// No existing session — create a new one and start with Listening
celpipStartFresh($db, $test_id, $student_id);
