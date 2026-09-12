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
        <title>CELPIP Masterclass — Mastery Speaking: Lexical Precision &amp; Delivery</title>
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
                <?php render_upgrade_prompt('intermediate', 'Mastery Speaking — Lexical Precision & Delivery'); ?>
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
    <title>CELPIP Masterclass — Mastery Speaking: Lexical Precision &amp; Delivery</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Mastery Speaking — Lexical Precision &amp; Delivery</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mic-fill me-2" style="color: #0b77ff;"></i>
                Mastery Speaking — Lexical Precision &amp; Delivery
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Vocabulary precision, natural delivery &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Precise Vocabulary</span>
                        <span class="badge-custom">Natural Delivery</span>
                        <span class="badge-custom">CLB 10-12</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-bullseye me-2" style="color: #0b77ff;"></i>Precision Over Range at This Level</h2>
                <p>
                    By Month 3, most candidates already have decent vocabulary range. What separates CLB 10-12
                    speaking is <strong>precision</strong> — choosing the word that most exactly captures your
                    meaning, rather than a vaguer word that's technically correct.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Vaguer</h4>
                        <p class="mb-0">"The picture is very busy and there's a lot going on."</p>
                    </div>
                    <div class="info-card" style="border-color:#0b77ff;">
                        <h4 style="color:#0b77ff;">More Precise</h4>
                        <p class="mb-0">"The classroom is chaotic — students are throwing paper airplanes while the teacher argues with a colleague at the door."</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-emoji-neutral me-2" style="color: #0b77ff;"></i>Natural, Not Performed</h2>
                <p>
                    A subtle but common issue at the CLB 9-10 boundary is delivery that sounds rehearsed or
                    "performed" rather than genuinely spontaneous — memorized phrases dropped in regardless of
                    fit. Examiners (and the AI scoring models trained to mirror them) pick up on this. The goal
                    isn't to sound like you memorized impressive phrases; it's to sound like a genuinely fluent
                    speaker thinking on their feet.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Self-Check</h5>
                    <p class="mb-0">
                        After recording a response, ask: "Would I actually say this out loud to a real person in
                        this situation?" If a phrase feels like it was inserted to sound advanced rather than to
                        communicate something specific, replace it with a simpler, more natural alternative.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-arrow-up-right-circle me-2" style="color: #0b77ff;"></i>Idiomatic Language, Used Sparingly</h2>
                <p>
                    A well-placed idiomatic expression ("that's a tough call," "I'd rather play it safe") can
                    demonstrate genuine fluency — but only when it fits naturally and isn't forced. One or two
                    per response is usually plenty; more than that starts to sound like a vocabulary list rather
                    than natural speech.
                </p>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Precision Rewrite Drill</h5>
                    <p class="mb-2">Rewrite 6 vague sentences to be more precise and specific, without changing the core meaning.</p>
                    <p class="mb-2"><strong>Format:</strong> 6 questions | Sentence rewriting</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M3_SPEAK_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month3_speaking.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Re-record your Task 3 (Describing a Scene) response from Month 1, applying the
                        foreground → precise detail approach above. Compare your Month 1 and Month 3 recordings —
                        the difference in specificity should be noticeable.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month3_writing.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Writing
                </a>
                <a href="month3_full_sim_reading_listening.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Full Exam Simulation <i class="bi bi-arrow-right ms-1"></i>
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
