<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Listening/Speaking Weak-Point Drilling</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Listening/Speaking Weak-Point Drilling'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Listening/Speaking Weak-Point Drilling</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Listening/Speaking Weak-Point Drilling</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-crosshair me-2" style="color:#0b77ff;"></i>Listening/Speaking Weak-Point Drilling</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 5 — Targeted Correction &nbsp;|&nbsp; <strong>Class:</strong> 9 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Built from Checkpoint 1</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-graph-down me-2" style="color:#0b77ff;"></i>This Class Is Different — It's Built From Your Data</h2>
                <p>
                    Checkpoint 1 (last week) gave you a real, honest baseline across Listening and Speaking under
                    exam conditions. Today isn't new content — it's targeted repair, based specifically on where
                    your Checkpoint 1 results showed the biggest gaps.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2" style="color:#0b77ff;"></i>Step 1 — Classify Your Checkpoint 1 Errors</h2>
                <p>For every Listening question you missed, sort it into one bucket:</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Category</th><th>What it means</th><th>Fix</th></tr></thead>
                    <tbody>
                        <tr><td><strong>Timing</strong></td><td>You ran out of time to process before the next question.</td><td>Practice the note-taking framework, not more content.</td></tr>
                        <tr><td><strong>Comprehension</strong></td><td>You misheard or misunderstood the audio itself.</td><td>Vocabulary/accent exposure, slower re-listening drills.</td></tr>
                        <tr><td><strong>Question-reading</strong></td><td>You understood the audio but misread what was asked.</td><td>Practice reading question stems before playback, every time.</td></tr>
                    </tbody>
                </table>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color:#0b77ff;"></i>Step 2 — Speaking: Isolate Your Weakest Task</h2>
                <p>Listen back to your Checkpoint 1 Speaking recordings. Identify the single task (1-8) where you either ran out of things to say early, or felt least in control of the structure — that task gets today's drilling focus, not all eight equally.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why Focus Beats Breadth Here</h5>
                    <p class="mb-0">Five focused minutes rebuilding your weakest task's structure moves your score more than one unfocused pass through all eight. Drill the same task 2-3 times today with different prompts until the structure feels automatic.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box"><p class="mb-0">Re-drill your weakest Listening part type (using any practice test's relevant part) and weakest Speaking task 3 more times each before Checkpoint 2.</p></div>
            </div>

            <div class="action-buttons">
                <a href="checkpoint_hub.php?slot=c7" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous: Checkpoint 1</a>
                <a href="week5_reading_writing_drilling.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next Class <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
