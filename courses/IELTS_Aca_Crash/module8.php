<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Crash Course - Module 8 (Full L&R Test)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
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
                    <li class="breadcrumb-item"><a href="../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="course_overview.php" class="text-decoration-none">IELTS Academic</a></li>
                    <li class="breadcrumb-item active">Module 8 – Full Practice Test (L&R)</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-file-earmark-check-fill me-2" style="color:#06b6d4;"></i>
                Module 8 – Full Practice Test: Listening & Reading
            </h1>

            <p class="lead">
                Today is exam simulation day. No pauses. No mercy. Exactly like test day.
            </p>

            <div class="content-section">
                <h2>Full Timed Listening Test + Review</h2>
                <p>40 questions • 30 minutes + 10 minutes transfer time</p>
            </div>

            <div class="content-section">
                <h2>Full Timed Reading Test + Review</h2>
                <p>3 passages • 60 minutes • No extra time</p>
            </div>

            <div class="content-section">
                <h2>Error Pattern Analysis</h2>
                <p>We go through every mistake together and build your personal “Error Prevention Checklist”.</p>
            </div>

            <div class="action-buttons">
                <a href="module7.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 7</a>
                <a href="module9.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 9</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #06b6d4 0%, #0ea5e9 100%); color: white;">
            <h6 class="mb-2">Full Test</h6>
            <div class="d-grid gap-2">
                <a href="full_test_lr.php" class="btn btn-light btn-sm"><i class="bi bi-stopwatch me-2"></i>Start Test Now</a>
            </div>
        </div>
        <!-- ads -->
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>