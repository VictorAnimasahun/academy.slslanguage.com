<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Listening/Speaking Timing &amp; Strategy</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Listening/Speaking Timing & Strategy'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Listening/Speaking Timing &amp; Strategy</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Listening/Speaking Timing &amp; Strategy</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-stopwatch me-2" style="color:#0b77ff;"></i>Listening/Speaking Timing &amp; Strategy</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 7 — Strategy Refinement (pre-Mock 1) &nbsp;|&nbsp; <strong>Class:</strong> 13 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Pacing</span><span class="badge-custom">Pre-Mock 1</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag me-2" style="color:#0b77ff;"></i>Two Checkpoints Down — Now It's About the Clock</h2>
                <p>By this point you've sat two full Checkpoints. Content strategy should feel familiar; this class shifts entirely to pacing and stamina, since Mock 1 next week is your first genuinely full-length, uninterrupted simulation.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-soundwave me-2" style="color:#0b77ff;"></i>Listening: Recovering From a Missed Question</h2>
                <p>The single biggest timing risk in Listening is dwelling on a missed question while the audio moves on. Practice a hard rule: if you don't have an answer within 2-3 seconds of the audio moving to the next point, guess and refocus immediately — the next question is always more winnable than replaying a lost one in your head.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color:#0b77ff;"></i>Speaking: Using Every Second of Prep Time</h2>
                <p>Under real exam pressure, many candidates start speaking early out of nervousness, wasting prep time. Practice using the <em>full</em> prep window every time — even a fully-formed idea benefits from a few seconds mentally rehearsing the opening sentence.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>The "Opening Sentence Rehearsal" Habit</h5>
                    <p class="mb-0">Use the last 5 seconds of every prep window to silently rehearse your exact opening sentence. Starting confidently sets the pace for the whole response — stumbling in the first 5 seconds rattles the rest.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Mock 1</h2>
                <div class="highlight-box"><p class="mb-0">Do one final timed run of any Speaking practice test, applying the opening-sentence rehearsal habit to all 8 tasks.</p></div>
            </div>

            <div class="action-buttons">
                <a href="checkpoint_hub.php?slot=c11" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous: Checkpoint 2</a>
                <a href="week7_reading_writing_strategy.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next Class <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
