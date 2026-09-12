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
        <title>CELPIP Masterclass — Precision Listening: Accent Variants &amp; Fast Speech</title>
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
                <?php render_upgrade_prompt('intermediate', 'Precision Listening — Accent Variants & Fast Speech'); ?>
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
    <title>CELPIP Masterclass — Precision Listening: Accent Variants &amp; Fast Speech</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Precision Listening — Accent Variants &amp; Fast Speech</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-soundwave me-2" style="color: #0b77ff;"></i>
                Precision Listening — Accent Variants &amp; Fast Speech
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 3 — Masterclass Refinement &nbsp;|&nbsp;
                            <strong>Focus:</strong> Accent exposure, faster natural speech &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Canadian English</span>
                        <span class="badge-custom">Connected Speech</span>
                        <span class="badge-custom">CLB 10-12</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-globe-americas me-2" style="color: #0b77ff;"></i>Why Accent Exposure Matters at This Level</h2>
                <p>
                    CELPIP audio uses Canadian English speakers, but by design includes a realistic range of
                    natural speaking speeds and regional pronunciation patterns — reflecting Canada's genuinely
                    diverse population. Candidates who've only practiced with slow, clearly-enunciated textbook
                    audio are frequently surprised by how much faster and more "run-together" real speech sounds.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-link-45deg me-2" style="color: #0b77ff;"></i>Connected Speech Patterns</h2>
                <p>Native speakers routinely blend words together in ways that don't match how they're written. Recognizing these patterns — not just producing them — is what this class targets.</p>
                <table class="table table-bordered mt-2 mb-3">
                    <thead style="background:#f1f5f9;"><tr><th>Written</th><th>Often sounds like</th></tr></thead>
                    <tbody>
                        <tr><td>"What are you doing?"</td><td>"Whaddaya doin'?"</td></tr>
                        <tr><td>"I don't know"</td><td>"I dunno"</td></tr>
                        <tr><td>"Going to"</td><td>"Gonna"</td></tr>
                        <tr><td>"Did you eat?"</td><td>"Didja eat?"</td></tr>
                    </tbody>
                </table>
                <p>These aren't "incorrect" speech — they're how fluent English is actually spoken. Recognizing them prevents a split-second confusion that can cost you the next few seconds of audio.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-ear me-2" style="color: #0b77ff;"></i>Training Method: Shadowing</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-lightbulb me-2"></i>How to Shadow Audio</h5>
                    <p class="mb-0">
                        Play a short clip of natural Canadian English (a CBC podcast segment works well). Speak
                        along with it half a second behind the speaker, matching their pace and rhythm as closely
                        as you can — not worrying about perfect pronunciation. This trains your ear to process
                        speech at a genuinely native pace, which is the single most effective way to raise your
                        real-time comprehension speed for Listening Parts 4-6.
                    </p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-lightning-charge me-2" style="color: #0b77ff;"></i>Handling Fast Passages Under Test Conditions</h2>
                <p>
                    When a passage feels too fast to fully process in real time, don't try to catch every word —
                    switch to catching <strong>content words</strong> (nouns, verbs, numbers) and let function
                    words (a, the, of, that) go. Content words carry almost all the testable information; missing
                    a few function words rarely costs you a question.
                </p>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Connected Speech Recognition</h5>
                    <p class="mb-2">Match casual spoken contractions ("whaddaya," "gonna," "dunno") to their written-out equivalents.</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Matching</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M3_LISTEN_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month3_listening.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Practice shadowing for 10 minutes daily this week using any Canadian news or interview
                        audio. Note whether your comprehension of fast speech feels different by the end of the week.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="../../CELPIP_Gen_2Mo/course_overview.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt4" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Mastery Reading <i class="bi bi-arrow-right ms-1"></i>
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
