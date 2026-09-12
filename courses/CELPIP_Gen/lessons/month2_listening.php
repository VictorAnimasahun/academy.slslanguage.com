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
        <title>CELPIP Masterclass — Advanced Listening: Discussion &amp; Workplace</title>
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
                <?php render_upgrade_prompt('intermediate', 'Advanced Listening — Discussion & Workplace'); ?>
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
    <title>CELPIP Masterclass — Advanced Listening: Discussion &amp; Workplace</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Advanced Listening — Discussion &amp; Workplace</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color: #0b77ff;"></i>
                Advanced Listening — Discussion &amp; Workplace
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 2 — Advanced CELPIP Strategies &nbsp;|&nbsp;
                            <strong>Focus:</strong> Listening Parts 1, 3 &amp; 5 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Discussion</span>
                        <span class="badge-custom">Problem Solving</span>
                        <span class="badge-custom">Information</span>
                        <span class="badge-custom">CLB 9-11</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: Why these three parts together -->
            <div class="content-section">
                <h2><i class="bi bi-diagram-3 me-2" style="color: #0b77ff;"></i>The Workplace Cluster</h2>
                <p>
                    Month 1 covered the two most accessible listening parts. Now we move to the three parts
                    most often set in a <strong>workplace or organizational</strong> context — Problem Solving
                    (Part 1), Information (Part 3), and Discussion (Part 5). Each involves more speakers or more
                    structured content than the daily-life material from Month 1, and CLB 9+ scores depend on
                    handling this complexity confidently.
                </p>
            </div>

            <!-- Part 1 review at advanced level -->
            <div class="content-section">
                <h2><i class="bi bi-1-circle me-2" style="color: #0b77ff;"></i>Part 1 — Problem Solving (Workplace Version)</h2>
                <p>
                    At this level, the "problem" is often a scheduling conflict, a budget issue, or a staffing
                    decision between two colleagues or a manager and an employee. The vocabulary shifts from
                    "let's meet for coffee" to "can we push the deadline" or "we're over budget on this line item."
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Track Whose Solution Wins</h5>
                    <p class="mb-0">
                        Workplace Problem Solving conversations often propose 2-3 solutions before settling on one
                        — frequently a compromise between the two speakers' original ideas. Note each proposal as
                        it's mentioned (P1: extend deadline, P2: add staff, FINAL: partial extension + 1 extra
                        person) so you're not caught out when the question asks about the <em>agreed</em> solution.
                    </p>
                </div>
            </div>

            <!-- Part 3 -->
            <div class="content-section">
                <h2><i class="bi bi-3-circle me-2" style="color: #0b77ff;"></i>Part 3 — Listening for Information (Organizational Detail)</h2>
                <p>
                    A person delivers structured information — an orientation briefing, event logistics, a
                    policy explanation. This is the most note-taking-heavy part of the whole Listening test.
                </p>
                <div class="highlight-box mt-2">
                    <h5><i class="bi bi-lightbulb me-2"></i>Strategy: Anticipate the Sequence</h5>
                    <p class="mb-0">
                        Organizational information is almost always delivered in a logical order: overview →
                        details → logistics (date/time/location) → what to do next. If you lose track of a
                        detail, don't panic — anticipate which "slot" in this sequence is coming next and refocus
                        there, rather than mentally replaying what you missed (which you can't do anyway).
                    </p>
                </div>
            </div>

            <!-- Part 5 -->
            <div class="content-section">
                <h2><i class="bi bi-5-circle me-2" style="color: #0b77ff;"></i>Part 5 — Listening to a Discussion</h2>
                <p>
                    Multiple speakers (often 3) share views on a topic — a team meeting, a community panel. This
                    is the hardest part for many candidates because you must track <em>who said what</em>, not
                    just the general topic.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-person-badge me-2"></i>Speaker Tagging</h4>
                        <p class="mb-0">The moment each speaker starts talking, jot their initial or role (M = manager, J = Jamal, etc.) next to their first point. Update it if their position shifts.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-arrow-left-right me-2"></i>Agreement vs. Disagreement</h4>
                        <p class="mb-0">Mark a "+" when a speaker agrees with a previous point and a "−" when they push back. Discussion questions frequently ask who agreed/disagreed with whom.</p>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Speaker-Tracking Drill</h5>
                    <p class="mb-2">Given a short discussion transcript, identify which speaker made each statement and whether they agreed or disagreed with the previous speaker.</p>
                    <p class="mb-2"><strong>Format:</strong> 10 questions | Matching</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M2_LISTEN_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month2_listening.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Find a 3-person panel discussion or podcast segment. Practice the speaker-tagging method
                        above for 3 minutes of audio, then write one sentence summarizing each speaker's position.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="month1_speaking_5to8_writing.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="<?= ACADEMY_URL ?>resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt3" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Advanced Reading <i class="bi bi-arrow-right ms-1"></i>
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
