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
        <title>CELPIP Masterclass — Full Exam Simulation: Writing &amp; Speaking</title>
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
                <?php render_upgrade_prompt('intermediate', 'Full Exam Simulation — Writing & Speaking'); ?>
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
    <title>CELPIP Masterclass — Full Exam Simulation: Writing &amp; Speaking</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Full Exam Simulation — Writing &amp; Speaking</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-clipboard-data-fill me-2" style="color: #0b77ff;"></i>
                Full Exam Simulation — Writing &amp; Speaking
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Final full-length productive skills simulation &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Exam Conditions</span>
                        <span class="badge-custom">Full AI Feedback</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-flag-fill me-2" style="color: #0b77ff;"></i>Your Last Timed Rehearsal</h2>
                <p>
                    This is the final dedicated Writing and Speaking practice before Mock Exam 3. Everything
                    you've built this course — the 3-paragraph email structure, buffer sentences, discourse
                    markers, precise vocabulary, natural delivery — should now come together without conscious
                    effort. If you still have to actively think through a technique step by step, that's useful
                    information for your remaining prep time.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-check me-2" style="color: #0b77ff;"></i>Today's Protocol</h2>
                <ol class="custom-list">
                    <li>Complete both Writing tasks under strict 53-minute timing, using the 3-pass self-edit routine.</li>
                    <li>Submit to the AI Essay Analyzer for full CLB-level feedback.</li>
                    <li>Complete all 8 Speaking tasks under strict timing, recording every response.</li>
                    <li>Submit your Speaking transcripts to the AI Speaking Analyzer for feedback across all four criteria.</li>
                </ol>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2" style="color: #0b77ff;"></i>What "Ready" Looks Like</h2>
                <div class="info-grid">
                    <div class="info-card"><h4>Writing</h4><p class="mb-0">Every prompt bullet addressed, consistent register throughout, minor errors only, on-time completion of both tasks.</p></div>
                    <div class="info-card"><h4>Speaking</h4><p class="mb-0">Full use of allotted time on each task, minimal filler words, at least one discourse marker used naturally per response, clear task fulfillment.</p></div>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Mock Exam 3</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Review both sets of AI feedback carefully. Bring your lowest-scoring criterion from each
                        skill to the Score Analysis class for one final targeted review before your last mock exam.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month3_full_sim_reading_listening.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="month3_score_analysis.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Score Analysis <i class="bi bi-arrow-right ms-1"></i>
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
