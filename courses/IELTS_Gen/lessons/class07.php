<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('intermediate')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 7</li></ol></nav>
<?php render_upgrade_prompt('intermediate','Class 7: Listening — Map Listening · Speaking Part 2 — Topic Development'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 7: Map Listening &amp; Speaking Part 2</title>
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
                <li class="breadcrumb-item active">Class 7</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#0b77ff;"></i>
                Class 7: Listening — Map Listening &nbsp;·&nbsp; Speaking Part 2 — Topic Development
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 1 — Foundations</span>
                <span class="badge-custom">Class 7 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 2 — Listening</span>
            </div>

            <div class="content-section">
                <h2>Listening — Map and Plan Labelling</h2>
                <p>
                    Map and plan labelling tasks appear in Listening Parts 2 and 3. You are given a diagram with
                    some locations already labelled and some left blank. The recording describes the layout and gives directions.
                </p>
                <h3>Before the Recording Starts</h3>
                <ul class="custom-list">
                    <li>Find the starting point — usually marked "YOU ARE HERE" or "Entrance."</li>
                    <li>Orient yourself — note north, south, east, west if marked on the map.</li>
                    <li>Read the blank labels and predict what type of places they might be (shop, café, exit, car park).</li>
                    <li>Number the blanks in order if they are not already numbered.</li>
                </ul>
                <h3>During the Recording</h3>
                <ul class="custom-list">
                    <li>Use your pencil to physically trace the route as the speaker describes it.</li>
                    <li>Do not jump ahead to the answer — follow the route step by step.</li>
                    <li>Key direction vocabulary: "opposite," "next to," "turn left/right," "go past," "at the end of," "between A and B," "on your left/right."</li>
                    <li>If you lose your position, look for a named landmark mentioned and reconnect from there.</li>
                </ul>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Teacher's Tip:</strong> The most common error is rushing to write the answer before fully tracing the route. The speaker often mentions a location, then corrects the direction. Trace first, write second.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Speaking Part 2 — The Cue Card &amp; 1-Minute Planning Technique</h2>
                <p>
                    Part 2 gives you a cue card with a topic and 3–4 bullet points. You have exactly <strong>1 minute to prepare</strong>
                    and then you must speak for <strong>2 minutes</strong>. The examiner will stop you at 2 minutes.
                </p>
                <h3>The 1-Minute Planning Technique</h3>
                <ol class="custom-list">
                    <li><strong>Read the card</strong> — all bullet points, not just the first one.</li>
                    <li><strong>Pick your specific topic</strong> — choose a real person, place, event, or object you actually remember. Specificity is fluency.</li>
                    <li><strong>Jot keywords only</strong> — 3–4 words per bullet point. Not full sentences — you will sound scripted if you write full sentences.</li>
                    <li><strong>Note a story or example</strong> — a concrete anecdote gives you 30–40 seconds of fluent speech.</li>
                </ol>
                <h3>Common Part 2 Categories</h3>
                <ul class="custom-list">
                    <li>Describe a <strong>person</strong> who has influenced or inspired you</li>
                    <li>Describe a <strong>place</strong> you have visited and enjoyed</li>
                    <li>Describe an <strong>event</strong> you attended (celebration, festival, competition)</li>
                    <li>Describe an <strong>object</strong> that is important to you</li>
                    <li>Describe a <strong>book, film, or TV programme</strong> that impressed you</li>
                    <li>Describe a <strong>skill</strong> you would like to learn</li>
                </ul>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 2 — Listening section</strong> (Parts 1–4, full timed)</p>
                    <p class="mb-0 text-muted small">Complete under exam conditions. Do not pause the recording. Self-mark using the answer key and identify which part(s) caused the most errors.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 7 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>S4 Part 2 Speaking Class 1</h4>
                        <p class="text-muted mb-2">How to use the 1-minute preparation time effectively and structure a 2-minute Part 2 response.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Speaking — Holidays Vocabulary</h4>
                        <p class="text-muted mb-2">Key vocabulary for the travel and holiday topic — one of the most common Part 2 categories.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Map Orientation Drill + Part 2 Planning</h4>
                    <p class="mb-2">Part A: Study a sample IELTS map for 60 seconds, then answer 5 location questions based on a written description of the route. Part B: Given a cue card, write a 4-keyword outline in exactly 60 seconds.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Mock Test 1 (Class 8)</h4>
                    <p class="mb-0">Prepare keyword outlines (not full answers) for 3 Part 2 cue cards: (1) Describe a person who has influenced you. (2) Describe a memorable journey or trip. (3) Describe a skill you would like to learn and why. Practice speaking from your keyword notes for 2 minutes each — do not write full scripts.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class06.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 6</a>
                <a href="class08.php" class="btn btn-warning btn-lg"><i class="bi bi-journal-richtext me-2"></i>Next: Mock Test 1 (Class 8)</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class06.php" class="btn btn-outline-light btn-sm">← Class 6</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 7 — Here</span>
                <a href="class08.php" class="btn btn-warning btn-sm text-dark">Mock Test 1 (Class 8) →</a>
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
