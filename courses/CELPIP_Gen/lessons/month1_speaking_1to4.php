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
        <title>CELPIP Masterclass — Speaking Tasks 1-4</title>
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
                <?php render_upgrade_prompt('intermediate', 'Speaking — Tasks 1-4'); ?>
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
    <title>CELPIP Masterclass — Speaking Tasks 1-4</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Speaking — Tasks 1-4</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color: #0b77ff;"></i>
                Speaking — Tasks 1-4
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — CELPIP Foundations &nbsp;|&nbsp;
                            <strong>Focus:</strong> Speaking Tasks 1-4 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Giving Advice</span>
                        <span class="badge-custom">Personal Experience</span>
                        <span class="badge-custom">Describing a Scene</span>
                        <span class="badge-custom">Making Predictions</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: Overview -->
            <div class="content-section">
                <h2><i class="bi bi-list-ol me-2" style="color: #0b77ff;"></i>CELPIP Speaking — 8 Tasks in 20 Minutes</h2>
                <p>
                    CELPIP Speaking has 8 short tasks, each with its own preparation time (30-60 seconds) and
                    response time (60-90 seconds). There is no examiner in the room — you speak into a
                    microphone, and everything is recorded for scoring. This means your delivery has to carry
                    all the meaning a live listener would normally pick up from your body language.
                </p>
                <p>Today's class covers the first four tasks, which build your foundational speaking habits before the more complex tasks later this month.</p>
            </div>

            <!-- Task 1 -->
            <div class="content-section">
                <h2><i class="bi bi-1-circle me-2" style="color: #0b77ff;"></i>Task 1 — Giving Advice</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 90s</strong></p>
                <p>You're given a situation where someone needs advice (a friend job-hunting, a classmate struggling academically) and asked to advise them on what to do.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: The "3-Option" Structure</h5>
                    <p class="mb-0">
                        Don't give one piece of advice and pad it — give <strong>2-3 distinct suggestions</strong>,
                        each with a one-sentence reason. "First, you could... because... Second, you might try...
                        since... Finally, consider..." This structure alone fills 90 seconds naturally and
                        demonstrates range.
                    </p>
                </div>
            </div>

            <!-- Task 2 -->
            <div class="content-section">
                <h2><i class="bi bi-2-circle me-2" style="color: #0b77ff;"></i>Task 2 — Talking About a Personal Experience</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>You're asked to describe a personal memory related to a prompt — a memorable trip, a change in routine, a time something didn't go as planned.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Pick a Real (or Realistic) Story Fast</h5>
                    <p class="mb-0">
                        Don't spend prep time hunting for the "perfect" memory — pick the first reasonable idea
                        and use your 30 seconds to sequence it: <em>what happened first, then, and how it ended /
                        how you felt</em>. A simple, clearly-told story beats an impressive one told with hesitation.
                    </p>
                </div>
            </div>

            <!-- Task 3 -->
            <div class="content-section">
                <h2><i class="bi bi-3-circle me-2" style="color: #0b77ff;"></i>Task 3 — Describing a Scene</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>You see an image and describe what's happening — the listener cannot see the picture, so your description has to stand on its own.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Foreground → Background → Detail</h5>
                    <p class="mb-0">
                        Describe what's most prominent first, then move outward: main subjects and their actions,
                        then the setting, then smaller details you notice. Use spatial language ("in the
                        foreground," "to the left," "in the background") to help the listener build a mental picture.
                    </p>
                </div>
            </div>

            <!-- Task 4 -->
            <div class="content-section">
                <h2><i class="bi bi-4-circle me-2" style="color: #0b77ff;"></i>Task 4 — Making Predictions</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>Using the <em>same</em> image from Task 3, you predict what will happen next.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Ground Predictions in Visible Evidence</h5>
                    <p class="mb-0">
                        The strongest responses explicitly connect a prediction to something visible in the image
                        ("Since the man is reaching for his coat, he's probably about to leave") rather than
                        inventing an unrelated future. This shows you're reasoning from evidence, which is exactly
                        what raises your Content/Coherence score.
                    </p>
                </div>
            </div>

            <!-- Scoring -->
            <div class="content-section">
                <h2><i class="bi bi-bar-chart-line me-2" style="color: #0b77ff;"></i>How Speaking Is Scored</h2>
                <div class="info-grid">
                    <div class="info-card"><h4>Content/Coherence</h4><p class="mb-0">Relevance, organization, development of ideas.</p></div>
                    <div class="info-card"><h4>Vocabulary</h4><p class="mb-0">Range and accuracy of word choice for the situation.</p></div>
                    <div class="info-card"><h4>Listenability</h4><p class="mb-0">Pronunciation, intonation, fluency — can the listener follow you without effort?</p></div>
                    <div class="info-card"><h4>Task Fulfillment</h4><p class="mb-0">Did you actually do what the task asked, within the time given?</p></div>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Open a CELPIP Speaking practice test from the Practice Tests section and record yourself
                        on Tasks 1-4 only, using the timers provided. Play the recordings back and note one thing
                        you'd change in your prep-time planning for each task.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt1" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Reading
                </a>
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt2" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Reading — Extended Passage <i class="bi bi-arrow-right ms-1"></i>
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
