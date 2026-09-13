<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Final Speaking Simulation &amp; Listening Sprints</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Final Speaking Simulation & Listening Sprints'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Final Speaking Simulation &amp; Listening Sprints</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Final Speaking Simulation &amp; Listening Sprints</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-mic-fill me-2" style="color:#0b77ff;"></i>Final Speaking Simulation &amp; Listening Sprints</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 12 — Final Prep &nbsp;|&nbsp; <strong>Class:</strong> 23 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Priority-Driven</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag me-2" style="color:#0b77ff;"></i>Built From Your Week 11 Priority</h2>
                <p>If Speaking or Listening came out of the Final Performance Review as your priority skill, this class is entirely dedicated to it. If your priority is Reading or Writing instead, use this time for one final self-directed timed practice test in that skill.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color:#0b77ff;"></i>Final Speaking Simulation</h2>
                <p>Sit all 8 tasks from any practice test under full, uninterrupted timing — no pausing, no notes, exactly as the real exam runs. This is about consistency under pressure, not learning anything new.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-lightning-charge me-2" style="color:#0b77ff;"></i>Listening Sprints</h2>
                <p>Short, high-intensity listening drills: play a 2-3 minute clip once, answer 3-4 self-generated comprehension questions immediately after, with zero replay. Repeat 3-4 times with different clips. This builds the exact single-pass processing speed the real exam demands.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Test-Day Coaching</h2>
                <div class="highlight-box"><p class="mb-0">No homework — rest is part of final prep too. Come to the last class ready to focus purely on logistics.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week11_final_review.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="week12_test_day_coaching.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Test-Day Coaching <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
