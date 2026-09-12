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
        <title>CELPIP Masterclass — Full Exam Simulation: Reading &amp; Listening</title>
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
                <?php render_upgrade_prompt('intermediate', 'Full Exam Simulation — Reading & Listening'); ?>
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
    <title>CELPIP Masterclass — Full Exam Simulation: Reading &amp; Listening</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Full Exam Simulation — Reading &amp; Listening</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-clipboard-data me-2" style="color: #0b77ff;"></i>
                Full Exam Simulation — Reading &amp; Listening
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Final full-length receptive skills simulation &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Exam Conditions</span>
                        <span class="badge-custom">No Breaks Mid-Section</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag-fill me-2" style="color: #0b77ff;"></i>Simulating the Real Thing, Fully</h2>
                <p>
                    This is the last full timed Reading and Listening practice before your final mock exam.
                    Treat it as identically to test day as possible: same environment, phone away, no pausing
                    between parts within a section, and — critically — no looking anything up mid-test, even if
                    you're unsure of a word.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-check me-2" style="color: #0b77ff;"></i>Today's Protocol</h2>
                <ol class="custom-list">
                    <li>Complete a full Reading practice test under strict timing, in one sitting.</li>
                    <li>Take exactly a 5-minute break — no more, no less, matching real test-day timing.</li>
                    <li>Complete a full Listening practice test under strict timing, in one sitting.</li>
                    <li>Score both sections immediately, but save detailed error analysis for the next class — today's goal is pure exam-condition stamina.</li>
                </ol>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-graph-up me-2" style="color: #0b77ff;"></i>What to Notice This Time</h2>
                <p>
                    By your third full-length timed run this course, your scores should be stabilizing. If
                    today's scores are meaningfully lower than your Month 2 timed practice, that's valuable
                    information for the Score Analysis class — it usually points to fatigue management rather
                    than a content gap, since you've already covered the same material successfully before.
                </p>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Keep both scored tests handy — you'll do a full Error Pattern Log comparison against your
                        Month 2 results in the Score Analysis class.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month3_speaking.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="month3_full_sim_writing_speaking.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Writing &amp; Speaking Simulation <i class="bi bi-arrow-right ms-1"></i>
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
