<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Reading/Writing Weak-Point Drilling</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Reading/Writing Weak-Point Drilling'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Reading/Writing Weak-Point Drilling</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Reading/Writing Weak-Point Drilling</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-crosshair me-2" style="color:#0b77ff;"></i>Reading/Writing Weak-Point Drilling</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 5 — Targeted Correction &nbsp;|&nbsp; <strong>Class:</strong> 10 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Built from Checkpoint 1</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2" style="color:#0b77ff;"></i>Step 1 — Classify Your Checkpoint 1 Reading Errors</h2>
                <p>For every Reading question you missed at Checkpoint 1, sort it by part (Correspondence, Diagram, Information, Viewpoints) — a pattern concentrated in one part points to a specific skill gap, not general weakness.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Mostly Part 1-2 errors</h4><p class="mb-0">Cross-referencing and gap-fill prediction need drilling — re-read the Week 2 strategy notes.</p></div>
                    <div class="info-card"><h4>Mostly Part 3-4 errors</h4><p class="mb-0">Longer-passage comprehension and viewpoint-tracking need drilling — re-read the Week 3 strategy notes.</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-file-earmark-text me-2" style="color:#0b77ff;"></i>Step 2 — Writing: Score Your Own Checkpoint 1 Responses</h2>
                <p>Re-read your Checkpoint 1 Task 1 and Task 2 responses against the four CELPIP Writing criteria (Content/Coherence, Vocabulary, Readability, Task Fulfillment). Identify your single lowest criterion — that's today's drilling focus.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Most Common Gap at This Stage</h5>
                    <p class="mb-0">Task Fulfillment is the most common weak criterion this early — usually one bullet point in the prompt gets addressed thinly or missed entirely under time pressure. Practice explicitly underlining every task requirement before writing, not after.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box"><p class="mb-0">Rewrite your weakest Checkpoint 1 writing task, targeting the criterion you identified. Compare it side by side with the original.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week5_listening_speaking_drilling.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="checkpoint_hub.php?slot=c11" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Checkpoint 2 <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
