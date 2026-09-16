<?php
// SCAFFOLD — CELPIP Reading Practice 4. No real content yet: celpip_reading_001-003.php
// use a large content-specific $parts array (sections/passages/questions
// transcribed from real source material), which doesn't have a generic
// "missing content" fallback the way Listening's audio does — so unlike
// the Listening scaffolds, this stays a simple placeholder page until a
// real Reading passage set is transcribed and this file is rebuilt to
// match celpip_reading_001.php's structure.
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment([14], 'this CELPIP Reading practice test');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Reading Practice 4 | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .main-wrapper { padding: 2rem 1.5rem; min-height: 100vh; background: #f8f9fa; }
        .stub-card { background: white; border-radius: 16px; padding: 3rem; box-shadow: 0 4px 20px rgba(0,0,0,0.07); max-width: 700px; margin: 2rem auto; text-align: center; }
        .exam-badge { background: #3b82f6; color: white; padding: .5rem 1.5rem; border-radius: 50px; font-weight: 700; font-size: .95rem; display: inline-block; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<main class="main-wrapper">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../resources_home.php">Resources</a></li>
                <li class="breadcrumb-item"><a href="index.php">Practice Tests</a></li>
                <li class="breadcrumb-item active">CELPIP Reading Practice 4</li>
            </ol>
        </nav>
        <div class="stub-card">
            <span class="exam-badge">CELPIP</span>
            <h2 class="mb-2">CELPIP Reading Practice 4</h2>
            <p class="text-muted mb-4">Reading · 4 Parts · 55 min</p>
            <div class="alert alert-warning text-start">
                <i class="bi bi-hammer me-2"></i>
                <strong>Content not added yet.</strong><br>
                This practice test's reading passages and questions haven't been transcribed yet. Check back soon.
            </div>
            <a href="index.php" class="btn btn-outline-secondary mt-2">← Back to Practice Tests</a>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>
