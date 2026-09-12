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
        <title>CELPIP Masterclass — Timed Practice: Writing &amp; Speaking</title>
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
                <?php render_upgrade_prompt('intermediate', 'Timed Practice — Writing & Speaking'); ?>
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
    <title>CELPIP Masterclass — Timed Practice: Writing &amp; Speaking</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Timed Practice — Writing &amp; Speaking</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-stopwatch-fill me-2" style="color: #0b77ff;"></i>
                Timed Practice — Writing &amp; Speaking
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> Full timed Writing + Speaking sections &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Full Timed Section</span>
                        <span class="badge-custom">Self &amp; AI Review</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clock-history me-2" style="color: #0b77ff;"></i>Production Skills Need Different Practice</h2>
                <p>
                    Reading and Listening are receptive skills — the timed-practice goal is pacing and stamina.
                    Writing and Speaking are productive skills, so today's timed practice has an extra step:
                    <strong>structured self-review</strong> immediately after, while the choices you made are
                    still fresh.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-check me-2" style="color: #0b77ff;"></i>Today's Practice Routine</h2>
                <ol class="custom-list">
                    <li>Complete both CELPIP Writing tasks (Email + Survey Response) under strict 53-minute total timing.</li>
                    <li>Submit your writing responses to the AI Essay Analyzer for CLB-level feedback on all four criteria.</li>
                    <li>Complete all 8 CELPIP Speaking tasks on a practice test, recording every response with real timing.</li>
                    <li>Listen back to at least 3 of your recordings and complete the Self-Review Checklist below before moving on.</li>
                </ol>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2" style="color: #0b77ff;"></i>Self-Review Checklist (Speaking)</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-1-circle me-2"></i>Did I use the full time?</h4>
                        <p class="mb-0">Or did I finish 15+ seconds early with nothing more to add?</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-2-circle me-2"></i>How many filler words?</h4>
                        <p class="mb-0">Count "um"/"uh"/"like" across the recording. Aim to reduce this number each week.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-3-circle me-2"></i>Did I answer the actual task?</h4>
                        <p class="mb-0">Re-read the prompt after listening — did your response drift off-topic partway through?</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-4-circle me-2"></i>Did I use a discourse marker?</h4>
                        <p class="mb-0">At least one sequencing or concluding marker from Month 2's Speaking class.</p>
                    </div>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Review your AI Essay Analyzer feedback in detail and note the single lowest-scoring
                        criterion. Bring this to the Test-Day Strategy class, where we'll build a personal
                        priority list for the remainder of Month 2.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month2_timed_reading_listening.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="month2_test_day_strategy.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Test-Day Strategy <i class="bi bi-arrow-right ms-1"></i>
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
