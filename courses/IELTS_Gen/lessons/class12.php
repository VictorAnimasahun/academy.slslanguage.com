<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../../../registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('advanced')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 12</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 12: Listening — Note, Table &amp; Flowchart Completion'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 12: Listening Completion Tasks</title>
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
                <li class="breadcrumb-item active">Class 12</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#6366f1;"></i>
                Class 12: Listening — Note Completion, Table &amp; Flowchart &nbsp;·&nbsp; Bonus: Form &amp; Gap Fill Flow
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 2 — Skill Development</span>
                <span class="badge-custom">Class 12 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 3 — Listening</span>
            </div>

            <div class="content-section">
                <h2>Listening — Note Completion</h2>
                <p>
                    Notes follow the structure of a lecture or talk. The gaps appear in the <strong>order the speaker covers the information</strong>. Before the recording starts, read all notes to understand the topic and predict the answer type for each gap.
                </p>
                <ul class="custom-list">
                    <li>Identify what TYPE of information fills each gap: noun, verb, adjective, number, date, name.</li>
                    <li>Read the surrounding words for grammatical clues: "the ___ of the building" → a noun. "the project was ___" → an adjective.</li>
                    <li>If you miss a gap, do not go back. Move immediately to the next one — the recording does not wait.</li>
                    <li>Check word limits: "no more than TWO words." Writing three words means zero marks.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Listening — Table &amp; Flowchart Completion</h2>
                <h3>Table Completion</h3>
                <p>
                    Tables organise information into categories (columns) and examples (rows).
                    Before listening, read the <strong>column headings</strong> — they tell you exactly what type of information each column contains. This means you know what type of answer to expect before the recording even starts.
                </p>
                <h3>Flowchart Completion</h3>
                <p>
                    Flowcharts show a sequence of steps or a process. Read the completed steps first — they give you context for the gaps. The arrows show you the order of information. In a process description, the speaker uses sequence language: "first," "then," "after that," "once X is complete," "finally." These words signal when the next gap is approaching.
                </p>
            </div>

            <div class="content-section">
                <h2>Bonus — Gap Fill Flow &amp; Synonym Substitution</h2>
                <p>
                    When a long recording contains many gap-fill questions, IELTS sometimes clusters several answers in one dense section.
                    If you find yourself writing multiple answers in quick succession, slow down and listen carefully — the next few gaps may follow immediately.
                </p>
                <p>
                    <strong>Synonym substitution</strong> — after every practice session, note the paraphrase IELTS used. For each gap, compare: what word was in the question vs what word the speaker actually said. Building this paraphrase bank is one of the most effective study habits for Listening.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 3 — Listening section</strong> (Parts 1–4, full timed)</p>
                    <p class="mb-0 text-muted small">Complete without pausing. After self-marking, identify every answer where the speaker paraphrased the question — note the synonym pairs.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 12 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R6 Underlining and Keywords Class 2</h4>
                        <p class="text-muted mb-2">Advanced keyword technique applied to longer passages and trickier question types.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R4 Paraphrasing Class 2</h4>
                        <p class="text-muted mb-2">Advanced paraphrasing — how IELTS disguises answers in both Reading and Listening.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Flowchart Completion Prediction Drill</h4>
                    <p class="mb-2">Given a flowchart with 8 gaps, predict the answer type for each gap (noun / verb / number / adjective / name) based on the surrounding text — before listening to anything.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 13</h4>
                    <p class="mb-0">Complete a full note/table/flowchart practice set from a past paper. For each incorrect answer, write down: (1) the exact words the speaker used, and (2) the paraphrase that appeared in the question. Build your personal synonym bank from these pairs.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class11.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 11</a>
                <a href="class13.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 13</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class11.php" class="btn btn-outline-light btn-sm">← Class 11</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 12 — Here</span>
                <a href="class13.php" class="btn btn-outline-light btn-sm">Class 13 →</a>
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
