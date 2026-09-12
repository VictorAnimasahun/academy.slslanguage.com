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
        <title>CELPIP Masterclass — Mock Exam 3: Final Full Timed Exam</title>
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
                <?php render_upgrade_prompt('intermediate', 'Mock Exam 3 — Final Full Timed Exam'); ?>
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
    <title>CELPIP Masterclass — Mock Exam 3: Final Full Timed Exam</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Mock Exam 3 — Final Full Timed Exam</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-trophy me-2" style="color: #0b77ff;"></i>
                Mock Exam 3 — Final Full Timed Exam
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Final complete exam simulation &nbsp;|&nbsp;
                            <strong>Duration:</strong> ~3 hours
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Full 4-Skill Exam</span>
                        <span class="badge-custom">Final Checkpoint</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-info-circle me-2" style="color: #0b77ff;"></i>Which Mock Exam to Take</h2>
                <p>
                    This course includes two full-length mock exams — Mock Exam A and Mock Exam B — which you've
                    already used at the end of Month 1 and Month 2. For this final checkpoint, retake
                    <strong>whichever one you completed longer ago</strong>, under full exam conditions, as your
                    last full-length simulation before test day. Your familiarity with the specific questions
                    will be low enough after several weeks that this remains a genuine test of your current
                    level, and running the complete four-skill sequence back to back is what matters most here —
                    stamina and consistency across all four skills in one sitting, not new content.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Getting the Most Out of a Repeat Mock</h5>
                    <p class="mb-0">
                        Don't try to recall your previous answers — respond fresh, as if seeing the material for
                        the first time. What you're really testing is whether the habits built across this
                        course (pacing, self-editing, discourse markers, precision) now happen automatically under
                        full exam pressure, across all four skills in sequence.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-check me-2" style="color: #0b77ff;"></i>Full Exam Protocol</h2>
                <ol class="custom-list">
                    <li>Complete Listening, Reading, Writing, and Speaking in one sitting, in that order, with only the standard breaks the real test allows.</li>
                    <li>Score every section using the same rubrics you've used all course.</li>
                    <li>Submit your Writing and Speaking responses for AI feedback.</li>
                    <li>Compare your four-skill results against your Trend Table from the Score Analysis class — this is your true final checkpoint before the real exam.</li>
                </ol>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month3_score_analysis.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_a.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Retake Mock A <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_b.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Retake Mock B <i class="bi bi-arrow-right ms-1"></i>
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
