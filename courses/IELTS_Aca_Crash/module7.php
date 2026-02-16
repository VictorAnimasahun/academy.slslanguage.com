<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Crash Course - Module 7 (Speaking Part 3 & Mocks)</title>
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
                    <li class="breadcrumb-item active">Module 7 – Speaking Part 3 & Mock Tests</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-chat-square-quote-fill me-2" style="color:#8b5cf6;"></i>
                Module 7 – Speaking Part 3 & Full Mock Tests
            </h1>

            <p class="lead">
                Part 3 is where Band 7 becomes possible.  
                Abstract questions, complex grammar, and real opinions — this is your moment to shine.
            </p>

            <div class="content-section">
                <h2>Abstract Discussion & Advanced Expressions</h2>
                <p>Common topics: education, environment, technology, society, health, government.</p>
                <p>Learn to answer with:</p>
                <ul class="custom-list">
                    <li>Conditional sentences (“If everyone recycled, we would…”)</li>
                    <li>Passive voice (“Education is considered…”)</li>
                    <li>Balanced opinions (“While some argue…, others believe…”)</li>
                    <li>Speculation (“In the future, it is likely that…”)</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>2 Full Mock Speaking Tests + Pronunciation Tips</h2>
                <p>We simulate the real exam twice:</p>
                <ul class="custom-list">
                    <li>Full 11–14 minute interview (recorded)</li>
                    <li>Detailed feedback on all 4 criteria</li>
                    <li>Pronunciation clinic: word stress, intonation, chunking</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="module6.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 6</a>
                <a href="module8.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 8</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); color: white;">
            <h6 class="mb-2">Mock Speaking</h6>
            <div class="d-grid gap-2">
                <a href="speaking_mock.php" class="btn btn-light btn-sm"><i class="bi bi-camera-video me-2"></i>Book Mock Test</a>
                <a href="course_overview.php" class="btn btn-outline-light btn-sm">Course Overview</a>
            </div>
        </div>
        <!-- ads -->
        <h6 class="mb-3 text-muted"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>