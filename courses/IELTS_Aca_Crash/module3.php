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
    <title>IELTS Academic Crash Course - Module 3 (Reading Skills)</title>
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
                    <li class="breadcrumb-item active">Module 3 – Reading Skills</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#7c3aed;"></i>
                Module 3 – Reading Skills
            </h1>

            <p class="lead">
                The IELTS Reading section is not a literature exam. It’s a treasure hunt.  
                You are given 60 minutes to find 40 precise pieces of information hidden inside three long, academic passages. Speed + accuracy = your superpower.
            </p>

            <!-- SECTION 1 -->
            <div class="content-section">
                <h2>Skimming, Scanning & Question Types</h2>
                <p>
                    Successful IELTS readers don’t read like normal humans. They use two superpowers:
                </p>
                <ul class="custom-list">
                    <li><strong>Skimming</strong> → Read fast (30–40 seconds) just to understand the main idea and structure.</li>
                    <li><strong>Scanning</strong> → Hunt for specific keywords, numbers, names, or dates.</li>
                </ul>
                <p>
                    Never read the whole passage first. That’s how Band 6 candidates waste 20 precious minutes.
                </p>

                <h4>Wise Rule:</h4>
                <p class="mb-3">
                    “Read the questions first → locate answers → read only the relevant sentences deeply.”
                </p>
            </div>

            <!-- SECTION 2 -->
            <div class="content-section">
                <h2>True/False/Not Given, Matching, Completion</h2>

                <h3>True / False / Not Given (The Most Hated Question)</h3>
                <p>
                    This is not about opinion. It’s about evidence.
                </p>
                <ul class="custom-list">
                    <li><strong>TRUE</strong> → The passage says exactly this (same meaning, possibly different words).</li>
                    <li><strong>FALSE</strong> → The passage says the opposite.</li>
                    <li><strong>NOT GIVEN</strong> → There is no information (don’t guess!).</li>
                </ul>
                <p><strong>Example:</strong><br>
                    Statement: “All students must wear uniforms.”<br>
                    Passage: “The school recommends uniforms but does not enforce them.” → <strong>FALSE</strong>
                </p>

                <h3>Matching Headings / Features / Information</h3>
                <p>
                    Always eliminate wrong options first. There are usually 2–3 extra headings you won’t use.
                </p>

                <h3>Sentence / Summary / Table Completion</h3>
                <p>
                    Pay attention to word limits: “NO MORE THAN TWO WORDS AND/OR A NUMBER”.
                    “The research” ≠ “research” → you lose the point.
                </p>
            </div>

            <!-- SECTION 3 -->
            <div class="content-section">
                <h2>Full Academic Reading Practice</h2>
                <p>
                    Two complete Academic Reading passages with all question types, timed exactly like the real exam.
                </p>
                <ul class="custom-list">
                    <li>Passage 1 – Topic: Urban Farming Revolution</li>
                    <li>Passage 2 – Topic: The Psychology of Colour</li>
                </ul>
                <p>
                    After each passage, we review every tricky question together. You’ll see exactly why “Not Given” was the correct answer when your heart wanted “False”.
                </p>
            </div>

            <div class="action-buttons">
                <a href="module2.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 2</a>
                <a href="module4.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 4</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #7c3aed 0%, #9333ea 100%); color: white;">
            <h6 class="mb-2">Reading Tools</h6>
            <div class="d-grid gap-2">
                <a href="module3_practice.php" class="btn btn-light btn-sm"><i class="bi bi-journal-text me-2"></i>Reading Practice</a>
                <a href="course_overview.php" class="btn btn-outline-light btn-sm">Course Overview</a>
            </div>
        </div>
        <!-- ads placeholders -->
        <h6 class="mb-3 text-muted"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>
</html>