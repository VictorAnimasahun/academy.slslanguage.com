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
        <title>CELPIP Masterclass — Mastery Writing: CLB 10+ Email &amp; Survey</title>
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
                <?php render_upgrade_prompt('intermediate', 'Mastery Writing — CLB 10+ Email & Survey'); ?>
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
    <title>CELPIP Masterclass — Mastery Writing: CLB 10+ Email &amp; Survey</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Mastery Writing — CLB 10+ Email &amp; Survey</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-award me-2" style="color: #0b77ff;"></i>
                Mastery Writing — CLB 10+ Email &amp; Survey
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Consolidating both Writing tasks at CLB 10+ &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Both Tasks</span>
                        <span class="badge-custom">Self-Editing</span>
                        <span class="badge-custom">CLB 10-12</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-arrow-repeat me-2" style="color: #0b77ff;"></i>From Two Separate Skills to One Habit</h2>
                <p>
                    Months 1 and 2 built the Email task and the Survey Response task as separate skills. Month 3
                    consolidates them: the buffer sentences, sentence combining, and register control you
                    practiced for Email apply directly to Survey Response too — a persuasive survey answer often
                    needs the same tone management as a diplomatic complaint email.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-search me-2" style="color: #0b77ff;"></i>Self-Editing Pass: A 3-Minute Routine</h2>
                <p>With 53 minutes for two tasks, most candidates don't budget any time to re-read. Building in a 2-3 minute self-edit per task consistently raises scores more than writing faster to "get more content in."</p>
                <ol class="custom-list">
                    <li><strong>Pass 1 — Task check (30s):</strong> Re-read the prompt. Did you cover every bullet point?</li>
                    <li><strong>Pass 2 — Grammar scan (60s):</strong> Check subject-verb agreement and verb tense consistency — the two highest-frequency error types even at CLB 10+.</li>
                    <li><strong>Pass 3 — Register check (30s):</strong> Does the tone stay consistent throughout, or does it drift from formal to casual partway through?</li>
                </ol>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-graph-up me-2" style="color: #0b77ff;"></i>The Ceiling Effect at CLB 11-12</h2>
                <p>
                    Very few candidates score CLB 12 — and chasing it isn't usually the efficient goal. The
                    difference between a strong CLB 10 and a CLB 11-12 is often subtle: near-flawless grammar
                    across an entire response, genuinely sophisticated (not just longer) vocabulary, and writing
                    that reads as effortless rather than constructed. If your writing already reliably hits CLB
                    9-10, your time is usually better spent stabilizing that consistently than chasing 11-12.
                </p>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Error-Spotting Drill</h5>
                    <p class="mb-2">Find the subject-verb agreement and tense-consistency errors in 8 short sample sentences.</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Error identification</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M3_WRITING_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month3_writing.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Write one full Email task and one full Survey Response under real timing, applying the
                        3-pass self-edit routine to both. Submit to the AI Essay Analyzer and compare the feedback
                        to your Month 2 submissions — look specifically for reduced grammar errors.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt4" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Reading
                </a>
                <a href="month3_speaking.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Mastery Speaking <i class="bi bi-arrow-right ms-1"></i>
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
