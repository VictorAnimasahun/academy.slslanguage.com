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
        <title>CELPIP Masterclass — Advanced Speaking: Fluency &amp; Coherence</title>
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
                <?php render_upgrade_prompt('intermediate', 'Advanced Speaking — Fluency & Coherence'); ?>
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
    <title>CELPIP Masterclass — Advanced Speaking: Fluency &amp; Coherence</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Advanced Speaking — Fluency &amp; Coherence</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color: #0b77ff;"></i>
                Advanced Speaking — Fluency &amp; Coherence
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> All 8 Speaking Tasks — Delivery &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Fluency</span>
                        <span class="badge-custom">Filler Reduction</span>
                        <span class="badge-custom">Discourse Markers</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: shift from content to delivery -->
            <div class="content-section">
                <h2><i class="bi bi-arrow-repeat me-2" style="color: #0b77ff;"></i>From "What to Say" to "How to Say It"</h2>
                <p>
                    Month 1 built your content strategies for all 8 speaking tasks. Now that you can reliably
                    generate a relevant answer for each task, Month 2 shifts focus to <strong>delivery</strong> —
                    the Listenability criterion, which many CLB 8-9 candidates leave un-optimized because it
                    feels less "teachable" than content strategy. It isn't — it responds directly to practice.
                </p>
            </div>

            <!-- Section 2: filler words -->
            <div class="content-section">
                <h2><i class="bi bi-mute me-2" style="color: #0b77ff;"></i>Replacing Filler Words with Silence</h2>
                <p>
                    "Um," "uh," and "like" don't just sound unpolished — repeated often enough, they interrupt
                    the listener's comprehension. The fix isn't to eliminate all pausing (natural pausing is
                    fine and even expected) — it's to replace <em>filled</em> pauses with <em>silent</em> ones.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Drill: The Silent Pause Swap</h5>
                    <p class="mb-0">
                        Record yourself answering a Task 2 or Task 7 prompt. Every time you notice a filler word
                        in the playback, note its timestamp. Re-record the same response, and consciously replace
                        each filler with a half-second silent pause instead. Most candidates find this immediately
                        makes them sound more confident, even though the content hasn't changed at all.
                    </p>
                </div>
            </div>

            <!-- Section 3: discourse markers -->
            <div class="content-section">
                <h2><i class="bi bi-signpost-split me-2" style="color: #0b77ff;"></i>Discourse Markers That Signal Organization</h2>
                <p>
                    Fluency &amp; Coherence rewards a listener being able to follow your structure without
                    effort. A small set of discourse markers does most of this work:
                </p>
                <div class="info-grid">
                    <div class="info-card"><h4>Sequencing</h4><p class="mb-0">"First of all... / After that... / Finally..."</p></div>
                    <div class="info-card"><h4>Contrasting</h4><p class="mb-0">"That said... / On the other hand... / Even so..."</p></div>
                    <div class="info-card"><h4>Adding a Reason</h4><p class="mb-0">"This is mainly because... / The main reason is..."</p></div>
                    <div class="info-card"><h4>Concluding</h4><p class="mb-0">"Overall... / In the end... / So ultimately..."</p></div>
                </div>
                <p class="mt-3">
                    Using 2-3 of these naturally across a response signals organization far more effectively than
                    hoping the listener infers your structure from content alone.
                </p>
            </div>

            <!-- Section 4: pacing -->
            <div class="content-section">
                <h2><i class="bi bi-speedometer2 me-2" style="color: #0b77ff;"></i>Pacing: Neither Rushed Nor Padded</h2>
                <p>
                    A common CLB 8 pattern is finishing a response in 40 seconds when 90 were available, or the
                    opposite — running out of ideas and repeating a point to fill time. Both signal weaker
                    Content/Coherence than a response that's naturally paced to use most of the available time
                    with genuine content.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Fix for Finishing Early</h5>
                    <p class="mb-0">
                        If you consistently finish early, your content plan needs one more layer — usually a
                        brief example or a "why this matters" sentence added after your main point, rather than
                        an entirely new idea.
                    </p>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Discourse Marker Matching</h5>
                    <p class="mb-2">Match discourse markers to their function (sequencing, contrasting, reasoning, concluding).</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Matching</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M2_SPEAK_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month2_speaking.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Re-record your Task 7 (Expressing Opinions) response from Month 1. This time, use at
                        least one discourse marker from each category above, and do the silent-pause-swap drill.
                        Compare the two recordings.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month2_writing.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Writing
                </a>
                <a href="month2_timed_reading_listening.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Timed Practice <i class="bi bi-arrow-right ms-1"></i>
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
