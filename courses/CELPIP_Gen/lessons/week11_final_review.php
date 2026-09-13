<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Final Performance Review</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Final Performance Review'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Final Performance Review</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Final Performance Review</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-graph-up-arrow me-2" style="color:#0b77ff;"></i>Final Performance Review</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 11 — Mock 2 &nbsp;|&nbsp; <strong>Class:</strong> 22 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 75 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">vs. Mock 1 + All Checkpoints</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-collection me-2" style="color:#0b77ff;"></i>Six Data Points, One Trend Line</h2>
                <p>By now you have six full-skill data points per skill: Checkpoints 1-4, Mock 1, and Mock 2. This class is about reading the trend across all six, not just celebrating or worrying about Mock 2's number in isolation.</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Skill</th><th>CP1</th><th>CP2</th><th>Mock 1</th><th>CP3</th><th>CP4</th><th>Mock 2</th></tr></thead>
                    <tbody>
                        <tr><td>Listening</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Reading</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Writing</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Speaking</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag-fill me-2" style="color:#0b77ff;"></i>Reading the Trend Correctly</h2>
                <div class="info-grid">
                    <div class="info-card"><h4>Steadily improving</h4><p class="mb-0">Needs maintenance in Week 12, not new intervention — don't over-drill a skill that's already working.</p></div>
                    <div class="info-card"><h4>Plateaued or dropped</h4><p class="mb-0">This is where Week 12's limited remaining time should go, even if its absolute score is higher than another skill's.</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Final Prep</h2>
                <div class="highlight-box"><p class="mb-0">Finalize your 6-point trend table and identify your single highest-priority skill for the final week.</p></div>
            </div>

            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_b.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous: Mock Test 2</a>
                <a href="week12_final_speaking_listening_sprints.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next Class <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
