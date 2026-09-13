<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Reading &amp; Writing Structure</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Reading & Writing Structure'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Reading &amp; Writing Structure</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Reading &amp; Writing Structure</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-book me-2" style="color:#0b77ff;"></i>Reading &amp; Writing Structure</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 2 — Core Teaching &nbsp;|&nbsp; <strong>Class:</strong> 4 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Reading Parts 1-2</span><span class="badge-custom">Writing Task 1&amp;2 Structure</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-envelope me-2" style="color:#0b77ff;"></i>Reading Part 1 — Correspondence</h2>
                <p>A letter or email with reply-style gap-fill questions. The "sender" and "recipient" relationship (formal vs. informal) sets the register clues you can use to predict missing words.</p>
                <h2 class="mt-4"><i class="bi bi-diagram-3 me-2" style="color:#0b77ff;"></i>Reading Part 2 — Diagram</h2>
                <p>A table, chart, or set of listings (e.g. classified ads) plus a related email or note with blanks. The key skill: cross-referencing two sources of information at once, not just reading top to bottom.</p>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Answer From the Table First</h5>
                    <p class="mb-0">In Part 2, locate the relevant row/column in the diagram before reading the blank's surrounding sentence closely — most answers are lifted near-verbatim from the diagram, so knowing which row matters saves real time.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-envelope-paper me-2" style="color:#0b77ff;"></i>Writing Task 1 — Email, Structure Review</h2>
                <p>Three-paragraph shape: opening (why you're writing), body (every bullet point the prompt gives you, addressed clearly), closing (what you want to happen next).</p>
                <h2 class="mt-4"><i class="bi bi-bar-chart-steps me-2" style="color:#0b77ff;"></i>Writing Task 2 — Survey Response, Structure Review</h2>
                <p>State your choice immediately, give two developed reasons each with a brief example, then acknowledge the alternative briefly before restating your position.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color:#0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color:#16a34a;background:#f0fdf4;">
                    <h5 style="color:#16a34a;"><i class="bi bi-check2-square me-2"></i>Structure Matching</h5>
                    <p class="mb-2">Put a scrambled Writing Task 1 email back into the correct 3-paragraph order.</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_3MO_WK2_RW_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/week2_reading_writing_structure.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box"><p class="mb-0">Write one Task 1 email (150+ words) using the 3-paragraph structure, timed to 27 minutes.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week2_listening_speaking_overview.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="week3_listening_speaking_deeper.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next Class <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
