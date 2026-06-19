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
    <title>IELTS Academic Crash Course - Module 6 (Speaking Parts 1 & 2)</title>
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
                    <li class="breadcrumb-item active">Module 6 – Speaking Parts 1 & 2</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mic-fill me-2" style="color:#10b981;"></i>
                Module 6 – Speaking Parts 1 & 2
            </h1>

            <p class="lead">
                Part 1 is a warm-up chat. Part 2 is your 2-minute monologue.  
                Most students speak too little and repeat ideas. We fix that today.
            </p>

            <div class="content-section">
                <h2>Part 1 – Fluency & Familiar Topics</h2>
                <p>4–5 minutes, 8–12 simple questions about home, studies, hobbies, daily routine.</p>
                <p><strong>Golden Rule:</strong> Never give yes/no answers. Always add 1–2 extra sentences.</p>
                <p><strong>Example:</strong><br>
                    Q: Do you like cooking?<br>
                    Band 6: “Yes.”<br>
                    Band 7: “Yes, I really enjoy cooking, especially Italian dishes like pasta. Last weekend I made homemade pizza with my sister and it turned out delicious.”
                </p>
            </div>

            <div class="content-section">
                <h2>Part 2 – Long Turn (Cue Card) Mastery</h2>
                <p>You get 1 minute to prepare, then speak for 1–2 minutes.</p>
                <p><strong>Structure that guarantees Band 7+:</strong></p>
                <ol>
                    <li>Direct answer (“I’d like to talk about…”)</li>
                    <li>Who/What/When/Where</li>
                    <li>Two details + feelings</li>
                    <li>Explanation (why it was special/important)</li>
                </ol>
                <p>We practice 5 real cue cards with model answers.</p>
            </div>

            <div class="content-section">
                <h2>Extending Answers + Mock Parts 1 & 2</h2>
                <p>Live recording session with me. You speak → I give instant feedback on fluency, vocabulary, grammar, and pronunciation.</p>
            </div>

            <div class="action-buttons">
                <a href="module5.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Back to Module 5</a>
                <a href="module7.php" class="btn btn-success btn-lg"><i class="bi bi-play-circle me-2"></i>Start Module 7</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%); color: white;">
            <h6 class="mb-2">Speaking Practice</h6>
            <div class="d-grid gap-2">
                <a href="speaking_recorder.php" class="btn btn-light btn-sm"><i class="bi bi-mic me-2"></i>Record Yourself</a>
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