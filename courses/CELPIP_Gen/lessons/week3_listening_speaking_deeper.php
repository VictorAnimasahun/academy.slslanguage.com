<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) {
    ?><!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>CELPIP Masterclass — Listening &amp; Speaking, Deeper</title><meta name="viewport" content="width=device-width, initial-scale=1"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH . '/navbar_styles.php'; ?></head><body>
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH . '/navbar.php'; ?>
    <main class="main-wrapper"><div class="course-card"><?php render_upgrade_prompt('intermediate', 'Listening & Speaking, Deeper'); ?></div></main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — Listening &amp; Speaking, Deeper</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Listening &amp; Speaking, Deeper</li>
                </ol>
            </nav>
            <h1 class="mb-3"><i class="bi bi-headphones me-2" style="color:#0b77ff;"></i>Listening &amp; Speaking, Deeper</h1>
            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div><h4 class="mb-2" style="color:var(--accent);">Class Overview</h4>
                        <p class="mb-0"><strong>Week:</strong> 3 — Core Teaching &nbsp;|&nbsp; <strong>Class:</strong> 5 of 24 &nbsp;|&nbsp; <strong>Duration:</strong> 90 min</p></div>
                    <div class="d-flex flex-wrap gap-2"><span class="badge-custom">Inference &amp; Signal Words</span><span class="badge-custom">Speaking Tasks 5-8</span></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-signpost-split me-2" style="color:#0b77ff;"></i>Listening: Inference &amp; Signal Words</h2>
                <p>Last week covered the six part types at surface level. This week goes deeper into the skill that separates CLB 8 from CLB 10+ in Listening: catching what's <em>implied</em>, not just what's stated directly.</p>
                <div class="info-grid">
                    <div class="info-card"><h4>Contrast signals</h4><p class="mb-0">"however," "that said," "on the other hand" — flag that the next statement revises or opposes what came before.</p></div>
                    <div class="info-card"><h4>Emphasis signals</h4><p class="mb-0">"the most important thing is," "what really matters" — the speaker is directly flagging a testable point.</p></div>
                    <div class="info-card"><h4>Sequence signals</h4><p class="mb-0">"first," "after that," "finally" — track order-dependent questions (what happened last?).</p></div>
                    <div class="info-card"><h4>Tone shifts</h4><p class="mb-0">A change in pace or pitch often marks a genuinely important point, even without an explicit signal word.</p></div>
                </div>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Inference vs. Direct Statement</h5>
                    <p class="mb-0">Some Listening questions never have their answer said outright — you have to combine two stated facts to reach a conclusion the speaker never says explicitly. When a question feels impossible to find a "quote" for, that's the signal it's an inference question — look for what two pieces of information combine to answer it.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-mic me-2" style="color:#0b77ff;"></i>Speaking Tasks 5-8, Overview</h2>
                <div class="info-grid">
                    <div class="info-card"><h4>5. Comparing &amp; Persuading (60s/60s)</h4><p class="mb-0">Acknowledge the new option's appeal, then redirect back to your original choice.</p></div>
                    <div class="info-card"><h4>6. Difficult Situation (60s/60s)</h4><p class="mb-0">Commit to one side within the first 10 seconds of prep — don't hedge between both.</p></div>
                    <div class="info-card"><h4>7. Expressing Opinions (30s/90s)</h4><p class="mb-0">One clear position, two developed reasons, one concrete example.</p></div>
                    <div class="info-card"><h4>8. Unusual Situation (30s/60s)</h4><p class="mb-0">Describe concretely — avoid just saying "it's strange," describe exactly what makes it so.</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color:#0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color:#16a34a;background:#f0fdf4;">
                    <h5 style="color:#16a34a;"><i class="bi bi-check2-square me-2"></i>Signal Word Recognition</h5>
                    <p class="mb-2">Identify the function (contrast/emphasis/sequence) of signal words in short audio-style transcripts.</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_3MO_WK3_LS_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/week3_listening_speaking_deeper.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color:#0b77ff;"></i>Before Next Class</h2>
                <div class="highlight-box"><p class="mb-0">Record yourself on Speaking Tasks 5-8. Note which task felt hardest to fill the full response time.</p></div>
            </div>

            <div class="action-buttons">
                <a href="week2_reading_writing_structure.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Previous Class</a>
                <a href="week3_reading_writing_vocab.php" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next Class <i class="bi bi-arrow-right ms-1"></i></a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
