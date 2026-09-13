<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Reading &amp; Writing: Argument &amp; Variety</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Reading & Writing: Argument & Variety'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Reading &amp; Writing: Argument &amp; Variety</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Reading &amp; Writing: Argument &amp; Variety</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-book-half me-2" style="color:#0b77ff;"></i>Reading &amp; Writing: Argument &amp; Variety</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 3 — Core Teaching &nbsp;|&nbsp; <strong>Class:</strong> 6 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Reading Parts 3-4</span><span class="badge-custom">Argument Vocabulary</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-list-columns me-2" style="color:#0b77ff;"></i>Reading Part 3 — Information</h2>
                <p>A longer factual passage — a general-interest or workplace topic — followed by multiple-choice and matching questions. Skim for structure first (how many paragraphs, what each is roughly about) before reading in detail.</p>
                <h2 class="mt-4"><i class="bi bi-chat-square-quote me-2" style="color:#0b77ff;"></i>Reading Part 4 — Viewpoints</h2>
                <p>Two short opinion pieces on the same topic, arguing different sides. Questions test whether you can tell which writer holds which position — track each writer's stance explicitly as you read, not just the topic.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-translate me-2" style="color:#0b77ff;"></i>Argument Vocabulary for Writing Task 2</h2>
                <p>A small set of phrases does most of the work in a persuasive Survey Response:</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Stating a position</h4><p class="mb-0">"I would choose... because..." / "In my view, the better option is..."</p></div>
                    <div class="info-card"><h4>Conceding a point</h4><p class="mb-0">"While it's true that... / Admittedly..."</p></div>
                    <div class="info-card"><h4>Rebutting</h4><p class="mb-0">"...this is outweighed by... / Nevertheless..."</p></div>
                    <div class="info-card"><h4>Concluding</h4><p class="mb-0">"For these reasons... / Overall, I believe..."</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-braces me-2" style="color:#0b77ff;"></i>Sentence Variety</h2>
                <p>Repetitive short sentences read as lower-level even when grammatically correct. Combine ideas using subordination: "Although the country home is cheaper, it lacks the space we need," rather than two separate simple sentences saying the same thing.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color:#0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color:#16a34a;background:#f0fdf4;">
                    <h5 style="color:#16a34a;"><i class="bi bi-check2-square me-2"></i>Viewpoint Tracking</h5>
                    <p class="mb-2">Given two short opinion excerpts, identify which statements belong to which writer.</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_3MO_WK3_RW_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/week3_reading_writing_vocab.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Checkpoint 1</h2>
                <div class="highlight-box"><p class="mb-0">Write one Survey Response using at least one concession phrase and one combined sentence. This week's teaching is complete — next week is your first full checkpoint.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week3_listening_speaking_deeper.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="checkpoint_hub.php?slot=c7" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: Checkpoint 1 <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
