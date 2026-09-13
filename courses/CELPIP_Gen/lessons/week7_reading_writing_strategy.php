<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Reading/Writing Timing &amp; Strategy</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Reading/Writing Timing & Strategy'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Reading/Writing Timing &amp; Strategy</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Reading/Writing Timing &amp; Strategy</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-stopwatch me-2" style="color:#0b77ff;"></i>Reading/Writing Timing &amp; Strategy</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 7 — Strategy Refinement (pre-Mock 1) &nbsp;|&nbsp; <strong>Class:</strong> 14 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Pacing</span><span class="badge-custom">Pre-Mock 1</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clock-history me-2" style="color:#0b77ff;"></i>Reading: Budgeting Time Across 4 Parts in 55-60 Minutes</h2>
                <p>Reading has no per-part timer — you control the pace yourself, which is a strategic advantage if you plan it and a risk if you don't. A rough budget: roughly equal time per part, but bank extra time for Part 3 (the longest passage) by moving faster through Part 1's shorter gap-fill items.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>The "Answer, Don't Perfect" Rule</h5>
                    <p class="mb-0">If a question is taking more than 90 seconds, mark your best guess and move on — you can return if time remains at the end. Perfectionism on one hard question costs you two easy ones elsewhere.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clock-history me-2" style="color:#0b77ff;"></i>Writing: The 3-Minute Self-Edit Habit</h2>
                <p>With 53 minutes for two tasks, build in 2-3 minutes of self-editing per task rather than writing until the timer runs out. A quick 3-pass check (task coverage → grammar scan → register consistency) catches more score-relevant errors than extra content would.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Mock 1</h2>
                <div class="highlight-box"><p class="mb-0">Do one final timed Reading practice test applying the time-budget above, and one Writing task applying the 3-pass self-edit.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week7_listening_speaking_strategy.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_a.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Mock Test 1 <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
