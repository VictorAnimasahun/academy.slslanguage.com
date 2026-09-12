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
        <title>CELPIP Masterclass — Test-Day Strategy &amp; Mental Preparation</title>
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
                <?php render_upgrade_prompt('intermediate', 'Test-Day Strategy & Mental Preparation'); ?>
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
    <title>CELPIP Masterclass — Test-Day Strategy &amp; Mental Preparation</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Test-Day Strategy &amp; Mental Preparation</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-calendar-check me-2" style="color: #0b77ff;"></i>
                Test-Day Strategy &amp; Mental Preparation
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> Logistics, mindset, final Month 2 review &nbsp;|&nbsp;
                            <strong>Duration:</strong> 60 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Logistics</span>
                        <span class="badge-custom">Mindset</span>
                        <span class="badge-custom">Priority Plan</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-data me-2" style="color: #0b77ff;"></i>Before Test Day — Logistics</h2>
                <ul class="custom-list">
                    <li>Confirm your test date, time, and location at least a week in advance, and know exactly how long it takes to get there.</li>
                    <li>Bring the identification specified in your CELPIP booking confirmation — no exceptions are made at the test centre for incorrect ID.</li>
                    <li>The CELPIP test (Listening, Reading, Writing, Speaking) typically runs about 3 hours in total. Eat beforehand — there isn't a meal break.</li>
                    <li>Arrive early enough to complete check-in without rushing; arriving stressed measurably affects Speaking performance in particular.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-emoji-smile me-2" style="color: #0b77ff;"></i>Managing Nerves During the Test</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-wind me-2"></i>Box Breathing</h4>
                        <p class="mb-0">Before Speaking begins: inhale 4 seconds, hold 4, exhale 4, hold 4. Two cycles measurably lowers pre-speaking anxiety without taking meaningful time.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-arrow-counterclockwise me-2"></i>The "Next Question" Reset</h4>
                        <p class="mb-0">If a Listening or Reading question stumps you, guess and move on immediately. Dwelling costs you time on questions you could otherwise get right.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag me-2" style="color: #0b77ff;"></i>Building Your Personal Priority List</h2>
                <p>
                    Using your Error Pattern Log from the Timed Practice classes and your AI feedback from
                    Writing/Speaking, build a ranked list of your top 3 priorities for Month 3 (or for final
                    review, if this is your last month). Be specific — "improve writing" is not actionable;
                    "use a buffer sentence before every complaint email" is.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Template</h5>
                    <p class="mb-0">
                        Priority 1: ___ (your most frequent error category) → Action: ___ (a specific drill or habit)<br>
                        Priority 2: ___ → Action: ___<br>
                        Priority 3: ___ → Action: ___
                    </p>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Mock Exam 2</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Complete your Personal Priority List above and keep it visible while you take Mock Exam
                        2. Afterward, compare your mock results against your stated priorities — did you actually
                        apply them under test conditions?
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month2_timed_writing_speaking.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_b.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Mock Exam 2 <i class="bi bi-arrow-right ms-1"></i>
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
