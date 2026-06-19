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
    <title>IELTS Academic Crash Course - Module 5 (Writing Task 2)</title>
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
                    <li class="breadcrumb-item active">Module 5 – Writing Task 2</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-pen-fill me-2" style="color:#f59e0b;"></i>
                Module 5 – Writing Task 2 (The Essay)
            </h1>

            <p class="lead">
                Task 2 is worth twice as many marks as Task 1.  
                This is where Band 7+ students separate themselves from the crowd — with clear ideas, powerful examples, and flawless structure.
            </p>

            <div class="content-section">
                <h2>Essay Structure & The PEEL Method</h2>
                <p>Every great essay follows the same skeleton:</p>
                <ol class="custom-list">
                    <li><strong>Introduction</strong> – Paraphrase + clear answer</li>
                    <li><strong>Body Paragraph 1</strong> – PEEL</li>
                    <li><strong>Body Paragraph 2</strong> – PEEL</li>
                    <li><strong>Conclusion</strong> – Summarise + final thought</li>
                </ol>
                <p><strong>PEEL = Point → Explain → Example → Link</strong></p>
            </div>

            <div class="content-section">
                <h2>Opinion, Discussion, Problem-Solution, Advantages/Disadvantages</h2>
                <ul class="custom-list">
                    <li><strong>Opinion</strong> → “To what extent do you agree?” → Fully agree/disagree + 2 reasons</li>
                    <li><strong>Discussion</strong> → Discuss both views + your opinion (never skip your opinion!)</li>
                    <li><strong>Problem-Solution</strong> → Causes → Solutions (realistic & specific)</li>
                    <li><strong>Adv/Disadv</strong> → Two advantages + two disadvantages (or outweigh)</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Cohesive Devices & Academic Vocabulary</h2>
                <p>Band 7+ vocabulary is not “big words”. It’s precise words used naturally:</p>
                <ul class="custom-list">
                    <li>Moreover, Furthermore, In addition</li>
                    <li>On the other hand, Conversely, Nevertheless</li>
                    <li>This leads to, As a result, Consequently</li>
                    <li>A key benefit, A major drawback, A significant factor</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Full Task 2 Practice Session</h2>
                <p>Two full essays written live with you:</p>
                <ul class="custom-list">
                    <li>Discussion + Opinion – Technology and Family Relationships</li>
                    <li>Problem-Solution – Urban Overcrowding</li>
                </ul>
                <p>Full model answers + examiner comments included.</p>
            </div>

            <div class="action-buttons">
                <a href="module4.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 4</a>
                <a href="module6.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 6</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%); color: white;">
            <h6 class="mb-2">Task 2 Resources</h6>
            <div class="d-grid gap-2">
                <a href="module5_practice.php" class="btn btn-light btn-sm"><i class="bi bi-file-earmark-text me-2"></i>Essay Practice</a>
                <a href="course_overview.php" class="btn btn-outline-light btn-sm">Course Overview</a>
            </div>
        </div>
        <!-- ads -->
        <h6 class="mb-3 text-muted"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>