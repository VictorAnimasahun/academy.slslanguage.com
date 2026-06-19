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
    <title>IELTS Academic Crash Course - Module 9 (Final Test & Wrap-Up)</title>
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
                    <li class="breadcrumb-item active">Module 9 – Final Practice Test + Wrap-Up</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-trophy-fill me-2" style="color:#f59e0b;"></i>
                Module 9 – Final Full Test & Graduation
            </h1>

            <p class="lead">
                This is it. One complete IELTS Academic test under real conditions — Writing + Speaking included.
            </p>

            <div class="content-section">
                <h2>Full Writing Test (Task 1 + Task 2)</h2>
                <p>60 minutes • Real past paper</p>
            </div>

            <div class="content-section">
                <h2>Full Speaking Test Simulation</h2>
                <p>11–14 minutes with a live examiner (recorded)</p>
            </div>

            <div class="content-section">
                <h2>Common Mistakes & Last-Minute Strategies</h2>
                <ul class="custom-list">
                    <li>What to do the night before</li>
                    <li>What to bring (and not bring)</li>
                    <li>How to handle nerves</li>
                    <li>Test day checklist</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Congratulations!</h2>
                <p>You’ve completed the full crash course. You are ready.</p>
                <p class="fs-4 fw-bold text-success">Go get that Band 7+!</p>
            </div>

            <div class="action-buttons">
                <a href="module8.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 8</a>
                <a href="../../learning_dashboard.php" class="btn btn-success btn-lg"><i class="bi bi-house-door me-2"></i>Back to Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: white;">
            <h6 class="mb-2">Final Test</h6>
            <div class="d-grid gap-2">
                <a href="full_test_complete.php" class="btn btn-light btn-sm"><i class="bi bi-flag me-2"></i>Start Final Test</a>
            </div>
        </div>
        <!-- ads -->
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>