<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Mock 1 Review &amp; Band Estimate</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Mock 1 Review & Band Estimate'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Mock 1 Review &amp; Band Estimate</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Mock 1 Review &amp; Band Estimate</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-clipboard-data me-2" style="color:#0b77ff;"></i>Mock 1 Review &amp; Band Estimate</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 8 — Mock 1 &nbsp;|&nbsp; <strong>Class:</strong> 16 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Full 4-Skill Review</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-award me-2" style="color:#0b77ff;"></i>Your First Real Full Simulation Is Done</h2>
                <p>Mock Test 1 (last class) was your first genuinely complete, uninterrupted, all-4-skills simulation — closer to real test-day conditions than any single checkpoint so far, since checkpoints test skills in pairs, not all four back to back.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-graph-up me-2" style="color:#0b77ff;"></i>Comparing Mock 1 Against Your Checkpoints</h2>
                <p>Build a simple comparison for each skill:</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Skill</th><th>Checkpoint 1</th><th>Checkpoint 2</th><th>Mock 1</th><th>Trend</th></tr></thead>
                    <tbody>
                        <tr><td>Listening</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Reading</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Writing</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Speaking</td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Reading Fatigue Into the Picture</h5>
                    <p class="mb-0">If a skill dropped in Mock 1 versus your checkpoints, consider whether it's a genuine gap or simply mental fatigue from sitting all four skills back to back for the first time — that distinction changes whether Week 9-10's checkpoints should target content or stamina.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-bullseye me-2" style="color:#0b77ff;"></i>Setting a Realistic Band Estimate</h2>
                <p>Using Mock 1's results, write down a realistic current CLB estimate per skill and compare it honestly against your Week 1 target. Two checkpoints and one full mock remain to close any gap.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Checkpoint 3</h2>
                <div class="highlight-box"><p class="mb-0">Finalize your comparison table and identify one specific priority per skill to focus on across Weeks 9-10.</p></div>
            </div>

            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_a.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous: Mock Test 1</a>
                <a href="checkpoint_hub.php?slot=c17" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Checkpoint 3 <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
