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
        <title>CELPIP Masterclass — Listening: News Item &amp; Conversation</title>
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
                <?php render_upgrade_prompt('intermediate', 'Listening — News Item & Conversation'); ?>
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
    <title>CELPIP Masterclass — Listening: News Item &amp; Conversation</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Listening — News Item &amp; Conversation</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color: #0b77ff;"></i>
                Listening — News Item &amp; Conversation
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Class Overview</h4>
                        <p class="mb-0">
                            <strong>Module:</strong> Month 1 — CELPIP Foundations &nbsp;|&nbsp;
                            <strong>Focus:</strong> Listening Parts 2 &amp; 4 &nbsp;|&nbsp;
                            <strong>Duration:</strong> 75 min
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">Daily Life Conversation</span>
                        <span class="badge-custom">News Item</span>
                        <span class="badge-custom">CLB 7-9</span>
                    </div>
                </div>
            </div>

            <!-- Section 1: CELPIP Listening at a glance -->
            <div class="content-section">
                <h2><i class="bi bi-soundwave me-2" style="color: #0b77ff;"></i>CELPIP Listening at a Glance</h2>
                <p>
                    CELPIP Listening runs about 47–55 minutes and has six parts, each testing a different kind
                    of real-life listening. Every recording plays <strong>only once</strong> — there's no rewind,
                    so your strategy has to be built around a single, focused pass.
                </p>
                <div class="info-grid">
                    <div class="info-card"><h4>Part 1</h4><p class="mb-0">Problem Solving</p></div>
                    <div class="info-card" style="border-color:#0b77ff;"><h4 style="color:#0b77ff;">Part 2</h4><p class="mb-0">Daily Life Conversation</p></div>
                    <div class="info-card"><h4>Part 3</h4><p class="mb-0">Information</p></div>
                    <div class="info-card" style="border-color:#0b77ff;"><h4 style="color:#0b77ff;">Part 4</h4><p class="mb-0">News Item</p></div>
                    <div class="info-card"><h4>Part 5</h4><p class="mb-0">Discussion</p></div>
                    <div class="info-card"><h4>Part 6</h4><p class="mb-0">Viewpoints</p></div>
                </div>
                <p class="mt-3">Today we focus on <strong>Part 2</strong> and <strong>Part 4</strong> — the two parts most CELPIP candidates find deceptively easy on the surface, and then lose marks on because they stop taking notes once the conversation feels "casual."</p>
            </div>

            <!-- Section 2: Part 2 Daily Life Conversation -->
            <div class="content-section">
                <h2><i class="bi bi-chat-dots me-2" style="color: #0b77ff;"></i>Part 2 — Listening to a Daily Life Conversation</h2>
                <p>
                    You'll hear two people talking about an everyday topic — shopping, hobbies, weekend plans, a
                    social event. It sounds relaxed, which is exactly the trap: casual speech still packs in
                    specific, testable details.
                </p>
                <h3>What the questions actually target</h3>
                <ul class="custom-list">
                    <li>What activity are the speakers planning, and has the plan changed during the conversation?</li>
                    <li>Where and when do they agree to meet — listen for a correction ("actually, let's make it 4 instead of 3").</li>
                    <li>What is each speaker's opinion or preference, when the two disagree slightly?</li>
                </ul>
                <div class="highlight-box mt-3">
                    <h5><i class="bi bi-lightbulb me-2"></i>Navigation Strategy</h5>
                    <p class="mb-0">
                        Read the question stems <em>before</em> the audio starts. In Part 2, the correct answer is
                        almost always the <strong>final</strong> version of a piece of information, not the first one
                        mentioned — speakers change their minds mid-conversation ("let's meet at the mall — actually,
                        my car's in the shop, can we do your place instead?"). If you lock in the first answer you
                        hear, you'll frequently pick the version that gets revised a few seconds later.
                    </p>
                </div>
            </div>

            <!-- Section 3: Part 4 News Item -->
            <div class="content-section">
                <h2><i class="bi bi-newspaper me-2" style="color: #0b77ff;"></i>Part 4 — Listening to a News Item</h2>
                <p>
                    A short news report — current events, a local issue, a human-interest story. This is the most
                    "textbook" of the six parts: it has a clear structure, which you can use to predict where
                    answers will appear.
                </p>
                <h3>The structure you can rely on</h3>
                <ol class="custom-list">
                    <li><strong>Headline/lead:</strong> the first 1-2 sentences state the who/what/where — this usually answers a "main topic" question.</li>
                    <li><strong>Detail/evidence:</strong> the middle of the report supplies numbers, quotes, or a second party's reaction.</li>
                    <li><strong>Outcome/next steps:</strong> the closing sentence often states what happens next, or a prediction — a frequent target for the last question in the set.</li>
                </ol>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-1-circle me-2"></i>Listen for the "5 Ws"</h4>
                        <p class="mb-0">Who, what, when, where, why — news reports are built around these. If you catch all five in your notes, you can answer almost any factual question without replaying (which isn't possible anyway).</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-2-circle me-2"></i>Numbers Are High-Value</h4>
                        <p class="mb-0">Statistics, dates, and quantities in news items are almost always tested. Jot down every number you hear in shorthand (e.g. "3M$", "40%", "since 2019") even if you're not sure yet which one matters.</p>
                    </div>
                </div>
            </div>

            <!-- Section 4: Note-taking framework -->
            <div class="content-section">
                <h2><i class="bi bi-journal-text me-2" style="color: #0b77ff;"></i>A Note-Taking Framework for Both Parts</h2>
                <p>Use a simple two-column shorthand while the audio plays — you won't have time for full sentences.</p>
                <table class="table table-bordered mt-2 mb-3" style="max-width:600px;">
                    <thead style="background:#f1f5f9;"><tr><th>Column</th><th>What goes here</th></tr></thead>
                    <tbody>
                        <tr><td><strong>WHO / WHAT</strong></td><td>Speaker names or roles, topic, main event</td></tr>
                        <tr><td><strong>DETAILS</strong></td><td>Numbers, times, places, changes of plan, opinions</td></tr>
                    </tbody>
                </table>
                <div class="highlight-box" style="border-left-color:#16a34a; background:#f0fdf4;">
                    <h5 style="color:#16a34a;"><i class="bi bi-check2-circle me-2"></i>Practice Habit</h5>
                    <p class="mb-0">Every time you listen to a podcast or news clip this week, pause after 60 seconds and write down everything you remember using the two-column method above. This builds the exact muscle CELPIP Part 2/4 requires under real time pressure.</p>
                </div>
            </div>

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>Part 2 &amp; 4 Strategy Check</h5>
                    <p class="mb-2">10 questions on recognizing plan-changes in conversations and identifying the "5 Ws" structure of a news report.</p>
                    <p class="mb-2"><strong>Format:</strong> 10 questions | Multiple choice</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=CELPIP_GM_M1_LISTEN_QUIZ&return=<?= urlencode('../../courses/CELPIP_Gen/lessons/month1_listening.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
                </div>
            </div>

            <!-- Take-home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2" style="color: #0b77ff;"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h5><i class="bi bi-pencil-square me-2"></i>Before Next Class</h5>
                    <p class="mb-0">
                        Find any 2-3 minute news clip online (CBC is a good source — it matches the Canadian
                        English CELPIP uses). Listen once only, take notes using the WHO/WHAT + DETAILS framework,
                        then write a 3-sentence summary from your notes alone, without replaying the clip.
                    </p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>courses/CELPIP_intro/intro.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Previous Class
                </a>
                <a href="month1_writing.php" class="btn btn-primary" style="background-color: #0b77ff; border-color: #0b77ff;">
                    Next: Writing — Email Task <i class="bi bi-arrow-right ms-1"></i>
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
                        <li class="mb-2"><a href="month1_writing.php" class="text-white text-decoration-none"><i class="bi bi-chevron-right me-1"></i>Next Class</a></li>
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
