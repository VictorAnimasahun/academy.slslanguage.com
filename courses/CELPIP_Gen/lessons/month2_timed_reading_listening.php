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
        <title>CELPIP Masterclass — Timed Practice: Reading &amp; Listening</title>
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
                <?php render_upgrade_prompt('intermediate', 'Timed Practice — Reading & Listening'); ?>
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
    <title>CELPIP Masterclass — Timed Practice: Reading &amp; Listening</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Timed Practice — Reading &amp; Listening</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-stopwatch me-2" style="color: #0b77ff;"></i>
                Timed Practice — Reading &amp; Listening
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> Full timed Reading + Listening sections &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Full Timed Section</span>
                        <span class="badge-custom">Pacing</span>
                        <span class="badge-custom">Error Review</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clock-history me-2" style="color: #0b77ff;"></i>Why a Dedicated Timed-Practice Class</h2>
                <p>
                    By Month 2, you know the individual parts of Reading and Listening well. What's missing for
                    most candidates isn't skill — it's <strong>stamina and pacing</strong> across a full
                    58-minute Reading section or a full 47-55 minute Listening section, done back-to-back without
                    a break, exactly as the real test administers them.
                </p>
                <p>Today's class is entirely practice-based. Work through both sections under real timing, then use the review framework below.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-check me-2" style="color: #0b77ff;"></i>Today's Practice Routine</h2>
                <ol class="custom-list">
                    <li>Complete a full CELPIP Reading practice test (all 4 parts) under strict 55-minute timing — no pausing between parts.</li>
                    <li>Take a genuine 5-minute break, as you would between sections on test day.</li>
                    <li>Complete a full CELPIP Listening practice test (all 6 parts), remembering that each recording plays only once.</li>
                    <li>Score both sections and complete the Error Pattern Log below before moving on.</li>
                </ol>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-journal-check me-2" style="color: #0b77ff;"></i>Error Pattern Log</h2>
                <p>For every question you got wrong, classify the error into one of three categories — this is far more useful than just noting "I got #14 wrong."</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Category</th><th>What it means</th></tr></thead>
                    <tbody>
                        <tr><td><strong>Timing error</strong></td><td>You knew the answer but ran out of time to properly consider it, or rushed the final questions of a part.</td></tr>
                        <tr><td><strong>Comprehension error</strong></td><td>You misunderstood the passage/audio itself — a vocabulary gap or missed detail.</td></tr>
                        <tr><td><strong>Question-reading error</strong></td><td>You understood the material but misread what the question was actually asking.</td></tr>
                    </tbody>
                </table>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Why This Matters</h5>
                    <p class="mb-0">
                        If most of your errors are <em>timing</em> errors, more practice with the same content
                        won't help much — you need pacing drills instead. If most are <em>comprehension</em>
                        errors, targeted vocabulary and content review is what will move your score. Two
                        candidates with the same raw score can need completely different next steps.
                    </p>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box">
                    <p class="mb-0">
                        Based on your Error Pattern Log, identify your single most common error category and
                        bring it to the next class — timed writing/speaking practice will include a short
                        discussion of how the same categories apply there too.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month2_speaking.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Speaking
                </a>
                <a href="month2_timed_writing_speaking.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Timed Writing &amp; Speaking <i class="bi bi-arrow-right ms-1"></i>
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
