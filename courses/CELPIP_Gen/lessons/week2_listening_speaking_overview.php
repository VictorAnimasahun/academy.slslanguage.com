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
    <!DOCTYPE html><html lang="en"><head><meta charset="utf-8">
        <title>CELPIP Masterclass — Listening &amp; Speaking Overview</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <link href="../../../assets/css/courses.css" rel="stylesheet">
        <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    </head><body>
        <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <?php include INCLUDES_PATH . '/navbar.php'; ?>
        <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Listening & Speaking Overview'); ?></div></main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body></html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Listening &amp; Speaking Overview</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Listening &amp; Speaking Overview</li>
                </ol>
            </nav>

            <h1 class="mb-3"><i class="bi bi-headphones me-2" style="color:#0b77ff;"></i>Listening &amp; Speaking Overview</h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 2 — Core Teaching &nbsp;|&nbsp; <strong>Class:</strong> 3 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">6 Listening Parts</span>
                        <span class="badge-custom">Speaking Tasks 1-4</span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-soundwave me-2" style="color:#0b77ff;"></i>Listening — All 6 Parts, Once Through</h2>
                <p>CELPIP Listening runs 47-55 minutes with six distinct parts. Every recording plays once — no rewinding.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>1. Problem Solving</h4><p class="mb-0">Two people discuss a problem and propose solutions — an appointment, a product issue, an event.</p></div>
                    <div class="info-card"><h4>2. Daily Life Conversation</h4><p class="mb-0">A casual conversation about everyday activities — watch for plans that change mid-conversation.</p></div>
                    <div class="info-card"><h4>3. Information</h4><p class="mb-0">Structured information — instructions, announcements, logistics. The most note-taking-heavy part.</p></div>
                    <div class="info-card"><h4>4. News Item</h4><p class="mb-0">A short news report with a predictable headline → detail → outcome structure.</p></div>
                    <div class="info-card"><h4>5. Discussion</h4><p class="mb-0">Multiple speakers share views — track who said what, not just the topic.</p></div>
                    <div class="info-card"><h4>6. Viewpoints</h4><p class="mb-0">A single speaker argues a position with supporting evidence.</p></div>
                </div>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>The One Habit That Matters Across All Six</h5>
                    <p class="mb-0">Read the question stems before the audio starts, every single time. Knowing what you're listening FOR changes how you process the audio in real time — this is the single biggest score lever in Listening, more than vocabulary.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color:#0b77ff;"></i>Speaking Tasks 1-4</h2>
                <p>CELPIP Speaking has 8 short recorded tasks. Today covers the first four — your foundational speaking habits.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>1. Giving Advice (30s/90s)</h4><p class="mb-0">Give 2-3 distinct suggestions, each with a one-sentence reason.</p></div>
                    <div class="info-card"><h4>2. Personal Experience (30s/60s)</h4><p class="mb-0">Pick the first reasonable memory and sequence it: what happened, then, and how it ended.</p></div>
                    <div class="info-card"><h4>3. Describing a Scene (30s/60s)</h4><p class="mb-0">Foreground → background → detail. The listener can't see the image.</p></div>
                    <div class="info-card"><h4>4. Making Predictions (30s/60s)</h4><p class="mb-0">Same image as Task 3 — ground your prediction in something visible.</p></div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color:#0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color:#16a34a;background:#f0fdf4;">
                    <h5 style="color:#16a34a;"><i class="bi bi-check2-square me-2"></i>Part Recognition Drill</h5>
                    <p class="mb-2">Given a short audio description, identify which of the 6 Listening parts it belongs to.</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_3MO_WK2_LS_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/week2_listening_speaking_overview.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box"><p class="mb-0">Record yourself on Tasks 1-4 from any Speaking practice test. Play it back once, noting one habit to improve.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week1_foundational_assessment.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="week2_reading_writing_structure.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Reading &amp; Writing <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
