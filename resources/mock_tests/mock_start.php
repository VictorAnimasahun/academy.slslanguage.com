<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+mock+tests");
    exit();
}

$student_id = (int)$_SESSION['user_id'];
$test_id    = (int)($_GET['test_id'] ?? $_POST['test_id'] ?? 0);
$error      = '';

if (!$test_id) {
    header("Location: index.php");
    exit();
}

$stmt = $db->prepare("SELECT * FROM tests WHERE id = ? AND is_active = 1 AND category = 'Full' LIMIT 1");
$stmt->execute([$test_id]);
$mockTest = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$mockTest) {
    die("Mock test not found or is not active.");
}

// Check for an existing in-progress session
$stmt = $db->prepare("SELECT * FROM mock_sessions WHERE student_id = ? AND mock_test_id = ? AND status = 'in_progress' ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$student_id, $test_id]);
$existingSession = $stmt->fetch(PDO::FETCH_ASSOC);

$map      = require INCLUDES_PATH . '/mock_test_map.php';
$mockCode = $mockTest['code'];

if ($existingSession) {
    // Resume where they left off
    $sid = $existingSession['id'];
    if (is_null($existingSession['listening_attempt_id'])) {
        $file = $map[$mockCode]['listening']['file'] ?? 'mock_start.php';
        header("Location: {$file}?session_id={$sid}");
    } elseif (is_null($existingSession['reading_attempt_id'])) {
        $file = $map[$mockCode]['reading']['file'] ?? 'mock_start.php';
        header("Location: {$file}?session_id={$sid}");
    } elseif (is_null($existingSession['writing_attempt_id'])) {
        $file = $map[$mockCode]['writing']['file'] ?? 'mock_writing.php';
        header("Location: {$file}?session_id={$sid}");
    } else {
        header("Location: mock_speaking.php?session_id={$sid}");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $db->prepare("INSERT INTO mock_sessions (mock_test_id, student_id, status) VALUES (?, ?, 'in_progress')");
        $stmt->execute([$test_id, $student_id]);
        $session_id = (int)$db->lastInsertId();
        $file = $map[$mockCode]['listening']['file'] ?? 'mock_start.php';
        header("Location: {$file}?session_id={$session_id}");
        exit();
    } catch (PDOException $e) {
        error_log('mock_start.php: ' . $e->getMessage());
        $error = "Could not start the mock test. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($mockTest['title']) ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .mock-card { background:#fff; border-radius:16px; padding:2.5rem; box-shadow:0 4px 20px rgba(0,0,0,.08); max-width:760px; margin:2rem auto; }
        .section-row { display:flex; align-items:center; gap:1rem; padding:1rem 0; border-bottom:1px solid #f1f5f9; }
        .section-row:last-child { border-bottom:none; }
        .section-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; flex-shrink:0; }
        .warning-box { background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; padding:1rem 1.25rem; font-size:.9rem; }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-3">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Mock Tests</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($mockTest['title']) ?></li>
                </ol>
            </nav>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="mock-card">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#10b981,#34d399);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clipboard-check text-white fs-4"></i>
                    </div>
                    <div>
                        <h1 class="h4 mb-0 fw-bold"><?= htmlspecialchars($mockTest['title']) ?></h1>
                        <span class="text-muted small">Full IELTS Mock Exam · ~3 hours</span>
                    </div>
                </div>

                <p class="text-muted mb-4"><?= htmlspecialchars($mockTest['description'] ?? '') ?></p>

                <h6 class="fw-bold mb-0">Exam Sections</h6>
                <div class="mb-4">
                    <div class="section-row">
                        <div class="section-icon" style="background:linear-gradient(135deg,#3b82f6,#60a5fa);">
                            <i class="bi bi-headphones"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Section 1 — Listening</div>
                            <div class="text-muted small">40 questions · 30 minutes</div>
                        </div>
                        <span class="badge bg-light text-dark">Auto-scored</span>
                    </div>
                    <div class="section-row">
                        <div class="section-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);">
                            <i class="bi bi-book"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Section 2 — Reading</div>
                            <div class="text-muted small">40 questions · 60 minutes</div>
                        </div>
                        <span class="badge bg-light text-dark">Auto-scored</span>
                    </div>
                    <div class="section-row">
                        <div class="section-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Section 3 — Writing</div>
                            <div class="text-muted small">Task 1 + Task 2 · 60 minutes</div>
                        </div>
                        <span class="badge bg-light text-dark">AI-scored</span>
                    </div>
                    <div class="section-row">
                        <div class="section-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                            <i class="bi bi-mic"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Section 4 — Speaking</div>
                            <div class="text-muted small">~15 minutes · Instructor-administered</div>
                        </div>
                        <span class="badge bg-light text-dark">Instructor-scored</span>
                    </div>
                </div>

                <div class="warning-box mb-4">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                    <strong>Before you begin:</strong> Ensure you are in a quiet environment with a stable internet connection. Each section is timed and must be completed in order. Do not refresh or close the browser during the test.
                </div>

                <form method="POST">
                    <input type="hidden" name="test_id" value="<?= $test_id ?>">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                        <i class="bi bi-play-fill me-2"></i>Begin Mock Test
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
