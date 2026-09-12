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
        <title>CELPIP Masterclass — Speaking Tasks 5-8 &amp; Writing Survey Response</title>
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
                <?php render_upgrade_prompt('intermediate', 'Speaking Tasks 5-8 & Writing Survey Response'); ?>
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
    <title>CELPIP Masterclass — Speaking Tasks 5-8 &amp; Writing Survey Response</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Speaking Tasks 5-8 &amp; Writing Survey Response</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mic-fill me-2" style="color: #0b77ff;"></i>
                Speaking Tasks 5-8 &amp; Writing Survey Response
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — CELPIP Foundations &nbsp;|&nbsp;
                            <strong>Focus:</strong> Speaking Tasks 5-8, Writing Task 2 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 90 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Comparing &amp; Persuading</span>
                        <span class="badge-custom">Difficult Situation</span>
                        <span class="badge-custom">Opinions</span>
                        <span class="badge-custom">Unusual Situation</span>
                        <span class="badge-custom">Survey Response</span>
                    </div>
                </div>
            </div>

            <!-- Task 5 -->
            <div class="content-section">
                <h2><i class="bi bi-5-circle me-2" style="color: #0b77ff;"></i>Task 5 — Comparing and Persuading</h2>
                <p><strong>Prep: 60s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>
                    You're shown two options (homes, offices, plans) and choose one, then a third option is
                    introduced and you must persuade a listener that your original choice is still the best —
                    while directly acknowledging why the new option is tempting.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Acknowledge, Then Redirect</h5>
                    <p class="mb-0">
                        Don't ignore the third option — name one genuine advantage it has, then pivot: "I understand
                        the country home is cheaper, but for our family, being 45 minutes from the city means..."
                        Acknowledging the alternative before refuting it sounds more persuasive than pretending it
                        has no merit at all.
                    </p>
                </div>
            </div>

            <!-- Task 6 -->
            <div class="content-section">
                <h2><i class="bi bi-6-circle me-2" style="color: #0b77ff;"></i>Task 6 — Dealing with a Difficult Situation</h2>
                <p><strong>Prep: 60s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>You're given a scenario with two people in conflict and told to choose ONE side to speak to, explaining your reasoning to that person.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Commit Immediately, Then Justify</h5>
                    <p class="mb-0">
                        Choose your side within the first 10 seconds of prep and don't revisit the decision — a
                        response that clearly commits to one direction and explains it well outperforms one that
                        tries to satisfy both sides. Use empathetic language ("I know this isn't what you were
                        hoping to hear, but...") before your explanation.
                    </p>
                </div>
            </div>

            <!-- Task 7 -->
            <div class="content-section">
                <h2><i class="bi bi-7-circle me-2" style="color: #0b77ff;"></i>Task 7 — Expressing Opinions</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 90s</strong></p>
                <p>A yes/no opinion question on an everyday social topic (renting, studying abroad, technology use). You state a position and support it.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: One Clear Position, Two Reasons, One Example</h5>
                    <p class="mb-0">
                        Pick a side immediately — even if you're genuinely torn, CELPIP rewards a clear, developed
                        position over a hedged one. State your opinion in your first sentence, give two supporting
                        reasons, and illustrate one of them with a brief concrete example. This fills 90 seconds
                        with substance rather than repetition.
                    </p>
                </div>
            </div>

            <!-- Task 8 -->
            <div class="content-section">
                <h2><i class="bi bi-8-circle me-2" style="color: #0b77ff;"></i>Task 8 — Describing an Unusual Situation</h2>
                <p><strong>Prep: 30s &nbsp;|&nbsp; Speak: 60s</strong></p>
                <p>You see an unusual or surreal image and must phone someone to describe it in detail — often asking a follow-up question or request.</p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Describe Concretely, Don't Just Say "Strange"</h5>
                    <p class="mb-0">
                        The temptation is to say "it's a really weird/strange picture" and move on — but that
                        wastes your speaking time on a vague generalization. Instead, describe the unusual object
                        or scene with the same precision as Task 3: size, color, position, and what makes it odd
                        specifically (e.g., "the table has banana-shaped legs instead of normal wooden ones").
                    </p>
                </div>
            </div>

            <!-- Writing Task 2 -->
            <div class="content-section">
                <h2><i class="bi bi-bar-chart-steps me-2" style="color: #0b77ff;"></i>Writing Task 2 — Responding to Survey Questions</h2>
                <p><strong>Time: 26 minutes &nbsp;|&nbsp; Minimum: 150 words</strong></p>
                <p>
                    You're shown a survey question with two options and asked to choose one, then justify your
                    choice with reasons and examples — essentially a short persuasive essay.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-1-circle me-2"></i>State Your Choice Immediately</h4>
                        <p class="mb-0">Your first sentence should name which option you picked. Don't build up to it — CELPIP readers scan for your position early.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-2-circle me-2"></i>Two Reasons, Each Developed</h4>
                        <p class="mb-0">Give two distinct reasons for your choice, each in its own paragraph with a brief supporting example — this is more convincing than four thin, underdeveloped reasons.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-3-circle me-2"></i>Acknowledge the Alternative Briefly</h4>
                        <p class="mb-0">One sentence recognizing the other option's appeal, then a clear restatement of why your choice still wins, strengthens Content/Coherence.</p>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Speaking Tasks 5-8 Strategy Check</h5>
                    <p class="mb-2">Match each strategy (acknowledge-and-redirect, commit-immediately, etc.) to the correct task number.</p>
                    <p class="mb-2"><strong>Format:</strong> 8 questions | Matching</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M1_SPEAK2_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month1_speaking_5to8_writing.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Month 1's Mock Exam</h5>
                    <p class="mb-0">
                        Complete Speaking Tasks 5-8 on a practice test, recording your responses. Then write one
                        full Writing Task 2 survey response (150+ words, 26 minutes) using the state-choice →
                        two-reasons → acknowledge structure above.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt2" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous: Reading
                </a>
                <a href="<?= ACADEMY_URL ?>resources/mock_tests/celpip_full_mock_a.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Mock Exam 1 <i class="bi bi-arrow-right ms-1"></i>
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
