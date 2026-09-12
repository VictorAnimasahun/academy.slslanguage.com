<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

if (!can_access('intermediate')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CELPIP Masterclass — Score Analysis &amp; Targeted Improvement Plan</title>
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
                <?php render_upgrade_prompt('intermediate', 'Score Analysis & Targeted Improvement Plan'); ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Score Analysis &amp; Targeted Improvement Plan</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Score Analysis &amp; Targeted Improvement Plan</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-graph-up-arrow me-2" style="color: #0b77ff;"></i>
                Score Analysis &amp; Targeted Improvement Plan
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Consolidating 3 months of data into a final plan &nbsp;|&nbsp;
                            <strong>Duration:</strong> 60 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Progress Review</span>
                        <span class="badge-custom">Final Priorities</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-collection me-2" style="color: #0b77ff;"></i>You Now Have Three Data Points</h2>
                <p>
                    Between Month 1's diagnostic-style early practice, Month 2's full timed practice, and this
                    month's Full Exam Simulation, you have three snapshots of your performance across all four
                    skills. This class is about reading that trend, not just your most recent score in isolation.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-table me-2" style="color: #0b77ff;"></i>Build Your Trend Table</h2>
                <p>For each skill, record your score at each checkpoint:</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Skill</th><th>Month 1</th><th>Month 2</th><th>Month 3</th><th>Trend</th></tr></thead>
                    <tbody>
                        <tr><td>Listening</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Reading</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Writing</td><td></td><td></td><td></td><td></td></tr>
                        <tr><td>Speaking</td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                </table>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Reading the Trend</h5>
                    <p class="mb-0">
                        A skill that's improved steadily each month needs maintenance, not new intervention. A
                        skill that's plateaued or dropped needs your remaining prep time most — even if its
                        absolute score is higher than another skill's.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag-fill me-2" style="color: #0b77ff;"></i>Your Final Priority Plan</h2>
                <p>
                    With whatever time remains before your actual test date, write a short, specific plan: one
                    priority skill, the exact drills from this course you'll repeat (shadowing, self-editing
                    passes, discourse markers, etc.), and how often. A focused plan for the final stretch beats
                    trying to review everything equally.
                </p>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Mock Exam 3</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Finalize your trend table and priority plan. Keep it beside you during Mock Exam 3 as a
                        reminder of exactly what to focus on applying under full test conditions.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month3_full_sim_writing_speaking.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="month3_mock_exam_3.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Mock Exam 3 <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary">
                    <i class="bi bi-grid me-1"></i> Course Overview
                </a>
            </div>

        </div>

        <aside class="advert-sidebar">
            <div class="ad-container">
                <div class="ad-placeholder" style="width:300px; height:250px;">
                    <i class="bi bi-image" style="font-size:2rem; opacity:0.3;"></i>
                    <p class="mb-0 small">Advertisement 300×250</p>
                </div>
            </div>
            <div class="ad-container mt-3">
                <div class="p-3 rounded-3 text-white" style="background: linear-gradient(135deg, #0b77ff, #6366f1);">
                    <h6 class="fw-bold mb-3"><i class="bi bi-map me-2"></i>Course Navigation</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li><a href="<?= htmlspecialchars($back['url']) ?>" class="text-white text-decoration-none"><i class="bi bi-grid me-1"></i>Course Overview</a></li>
                    </ul>
                </div>
            </div>
        </aside>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar    = document.querySelector('.sidebar');
        const overlay    = document.getElementById('mobileOverlay');
        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }
        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 1200) toggleMenu(); });
        });
    </script>
</body>
</html>
