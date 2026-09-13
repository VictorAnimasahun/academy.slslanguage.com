<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Test-Day Coaching</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Test-Day Coaching'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Test-Day Coaching</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper">
        <div class="course-card">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Test-Day Coaching</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-calendar-check me-2" style="color:#0b77ff;"></i>Test-Day Coaching</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 12 — Final Prep &nbsp;|&nbsp; <strong>Class:</strong> 24 of 24 — Final Class &nbsp;|&nbsp; <strong>Duration:</strong> 60 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Logistics</span><span class="badge-custom">Mindset</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-data me-2" style="color:#0b77ff;"></i>Before Test Day — Logistics</h2>
                <ul class="custom-list">
                    <li>Confirm your test date, time, and location at least a week in advance, and know exactly how long it takes to get there.</li>
                    <li>Bring the identification specified in your CELPIP booking confirmation — no exceptions are made at the test centre for incorrect ID.</li>
                    <li>The full CELPIP test (Listening, Reading, Writing, Speaking) runs about 3 hours. Eat beforehand — there isn't a meal break.</li>
                    <li>Arrive early enough to complete check-in without rushing — arriving stressed measurably affects Speaking performance in particular.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-emoji-smile me-2" style="color:#0b77ff;"></i>Managing Nerves During the Test</h2>
                <div class="info-grid">
                    <div class="info-card"><h4><i class="bi bi-wind me-2"></i>Box Breathing</h4><p class="mb-0">Before Speaking begins: inhale 4 seconds, hold 4, exhale 4, hold 4. Two cycles measurably lowers pre-speaking anxiety.</p></div>
                    <div class="info-card"><h4><i class="bi bi-arrow-counterclockwise me-2"></i>The "Next Question" Reset</h4><p class="mb-0">If a Listening or Reading question stumps you, guess and move on immediately — dwelling costs more than one lost question.</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-trophy me-2" style="color:#0b77ff;"></i>Twelve Weeks, Six Full-Skill Checkpoints</h2>
                <p>You've now sat a complete test in every skill at least four separate times, plus two full mock exams. Whatever nerves remain on test day, unfamiliarity with the exam experience itself won't be one of them — that's exactly what this schedule was built to remove.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>You're Ready</h2>
                <div class="highlight-box"><p class="mb-0">This is the final class of the 3-Month Masterclass. Good luck on test day.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week12_final_speaking_listening_sprints.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
