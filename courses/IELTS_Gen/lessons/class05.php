<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../../../registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 5</li></ol></nav>
<?php render_upgrade_prompt('intermediate','Class 5: Listening — Multiple Choice · Speaking Part 1'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 5: Listening MCQ &amp; Speaking Part 1</title>
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
            <nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                <li class="breadcrumb-item active">Class 5</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#0b77ff;"></i>
                Class 5: Listening — Multiple Choice &nbsp;·&nbsp; Speaking Part 1 — Common Topics &amp; Questions
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 1 — Foundations</span>
                <span class="badge-custom">Class 5 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 1 — Writing Task 1</span>
            </div>

            <div class="content-section">
                <h2>Listening — Multiple Choice Elimination Strategy</h2>
                <p>
                    Multiple choice in IELTS Listening is deliberately designed to trap you. The recording mentions
                    <strong>all the options</strong> — but only one is correct. Many students choose the first plausible
                    answer they hear. That is exactly what IELTS expects you to do.
                </p>
                <h3>The Elimination Method</h3>
                <ul class="custom-list">
                    <li><strong>Before the recording:</strong> Read all options and underline keywords. Predict what you might hear for each.</li>
                    <li><strong>During the recording:</strong> Cross out options as you hear them being mentioned but then corrected or contradicted.</li>
                    <li><strong>Signal words for corrections:</strong> "actually," "but," "however," "in fact," "no, I mean," "well, not exactly."</li>
                    <li>The answer almost always comes <em>after</em> a correction signal, not before.</li>
                    <li>If two options sound equally plausible, eliminate the one mentioned first — IELTS plants early distractors.</li>
                </ul>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Teacher's Tip:</strong> Train yourself to slow down whenever you hear "however," "but," or "actually." A change of direction is coming, and that change usually leads to the correct answer.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Speaking Part 1 — Common Topics &amp; The IDEA Framework</h2>
                <p>
                    Part 1 lasts 4–5 minutes and covers 3–4 familiar topics. The examiner wants to hear you speak
                    naturally and fluently — not perfectly. Accuracy matters less than communication in Part 1.
                </p>
                <h3>Common Part 1 Topic Areas</h3>
                <p>Home, family, work or studies, hobbies, food, travel, music, sport, animals, daily routine, reading, technology, weather, shopping, sleep.</p>

                <h3>The IDEA Framework — Extending Your Answers</h3>
                <div class="info-grid">
                    <div class="info-card"><h4>I — Identify</h4><p class="mb-0">Identify what the question is asking. Do not answer a different question.</p></div>
                    <div class="info-card"><h4>D — Direct Answer</h4><p class="mb-0">Give a direct answer in the first sentence. "Yes, I do." / "Not really." / "It depends."</p></div>
                    <div class="info-card"><h4>E — Expand</h4><p class="mb-0">Add a reason, explanation, or contrast. "I find it quite relaxing because..."</p></div>
                    <div class="info-card"><h4>A — Add</h4><p class="mb-0">Add an example, extra detail, or personal story to reach 2–3 sentences naturally.</p></div>
                </div>
                <p class="mt-3">
                    <strong>Example:</strong> "Do you like cooking?" → <em>"Yes, I do, actually. I find cooking quite relaxing — I usually make dinner a few nights a week, mostly simple pasta dishes or stir-fries. It's a good way to unwind after work."</em>
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 1 — Writing Task 1</strong> (informal or formal letter, 20 min timed)</p>
                    <p class="mb-0 text-muted small">Write under timed conditions. Check your register, structure, and word count (150+ words) before reviewing.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 5 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>L1 Part 1 Listening Class 2</h4>
                        <p class="text-muted mb-2">MCQ strategies applied to IELTS Listening — elimination method in practice.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>General Speaking Test Overview</h4>
                        <p class="text-muted mb-2">What happens in each part of the IELTS Speaking test and what the examiner is assessing.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">MCQ Elimination Drill</h4>
                    <p class="mb-2">10 short listening extracts. Use the elimination method to identify the correct answer. Pay attention to signal words ("however," "actually," "but").</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 6</h4>
                    <p class="mb-0">Practise 10 common Part 1 questions out loud using the IDEA framework. Write your answers first (2–3 sentences each), then practise speaking without reading. Topics: cooking, music, reading, travel, sport, pets, shopping, technology, sleep, and weather.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class04.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 4</a>
                <a href="class06.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 6</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class04.php" class="btn btn-outline-light btn-sm">← Class 4</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 5 — Here</span>
                <a href="class06.php" class="btn btn-outline-light btn-sm">Class 6 →</a>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-light btn-sm">Course Overview</a>
            </div>
        </div>
        <h6 class="mb-3 text-muted mt-3"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size:1.5rem;opacity:0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
        <div class="ad-container"><div class="ad-placeholder"><i class="bi bi-badge-ad" style="font-size:1.5rem;opacity:0.3;"></i><p class="mt-2 mb-0">Advertisement Space</p><small>300x250</small></div></div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle=document.getElementById('menuToggle'),sidebar=document.querySelector('.sidebar'),overlay=document.getElementById('mobileOverlay');
        function toggleMenu(){sidebar.classList.toggle('active');overlay.classList.toggle('active');const icon=menuToggle.querySelector('i');icon.className=sidebar.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}
        menuToggle.addEventListener('click',toggleMenu);overlay.addEventListener('click',toggleMenu);
        document.querySelectorAll('.sidebar .nav-link').forEach(l=>{l.addEventListener('click',()=>{if(window.innerWidth<1200)toggleMenu();});});
    </script>
</body>
</html>
