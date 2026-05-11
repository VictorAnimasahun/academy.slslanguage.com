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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 4</li></ol></nav>
<?php render_upgrade_prompt('intermediate','Class 4: Reading — Skimming &amp; Scanning · Writing Task 1 — Vocabulary &amp; Registers'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 4: Reading &amp; Writing Task 1</title>
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
                <li class="breadcrumb-item active">Class 4</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#0b77ff;"></i>
                Class 4: Reading — Skimming &amp; Scanning &nbsp;·&nbsp; Writing Task 1 — Vocabulary &amp; Registers
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 1 — Foundations</span>
                <span class="badge-custom">Class 4 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom bg-info text-dark"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 1 — Reading</span>
            </div>

            <div class="content-section">
                <h2>Reading — Skimming for Gist</h2>
                <p>
                    Skimming means reading quickly to understand the <strong>main idea</strong> of a passage — not every word.
                    In IELTS General, skim the title, the first sentence of each paragraph, and any headings.
                    Target: skim all three sections in under 5 minutes. You are building a map of the text, not absorbing every detail.
                    When you answer questions, you will return to specific areas — so knowing the shape of the text is all you need at this stage.
                </p>
                <ul class="custom-list">
                    <li>Read the title and any subheadings first — they tell you the topic and structure.</li>
                    <li>Read only the first sentence of each paragraph — this is the topic sentence in most IELTS texts.</li>
                    <li>Note any bold or italicised words — these are signalled as important.</li>
                    <li>Do not re-read. Move forward even if you did not understand something.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Reading — Scanning for Specific Information</h2>
                <p>
                    Scanning means moving your eyes quickly across a text looking for a <strong>specific word, name, number, or phrase</strong>.
                    You do not read every word — you hunt. Before scanning, circle the keywords in the question,
                    then run your eyes down the passage until you spot something that matches or paraphrases your keyword.
                </p>
                <ul class="custom-list">
                    <li>Proper nouns (names, places) are easy to scan for — they stand out visually.</li>
                    <li>Numbers, dates, and percentages are also fast to spot.</li>
                    <li>For abstract keywords, scan for the paraphrase — IELTS rarely uses the exact word from the question.</li>
                    <li>Once you find the area, slow down and read carefully to confirm the answer.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Writing Task 1 — Register and Vocabulary</h2>
                <p>
                    IELTS General Writing Task 1 is always a letter. The <strong>register</strong> — formal, semi-formal, or informal —
                    is determined by who you are writing to and why. Getting the register wrong costs Task Achievement marks immediately.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Formal</h4>
                        <p class="mb-1"><strong>Who:</strong> Authority figures, organisations, people you have never met</p>
                        <p class="mb-1"><strong>Example:</strong> Complaint to a manager, application to a company</p>
                        <p class="mb-0"><strong>Salutation:</strong> Dear Sir/Madam &nbsp;·&nbsp; <strong>Sign-off:</strong> Yours faithfully</p>
                    </div>
                    <div class="info-card">
                        <h4>Semi-Formal</h4>
                        <p class="mb-1"><strong>Who:</strong> Neighbours, landlords, colleagues you know by name</p>
                        <p class="mb-1"><strong>Example:</strong> Letter to a neighbour about a problem</p>
                        <p class="mb-0"><strong>Salutation:</strong> Dear Mr Smith &nbsp;·&nbsp; <strong>Sign-off:</strong> Kind regards</p>
                    </div>
                    <div class="info-card">
                        <h4>Informal</h4>
                        <p class="mb-1"><strong>Who:</strong> Friends, family, people you know well</p>
                        <p class="mb-1"><strong>Example:</strong> Letter to a friend describing your new job</p>
                        <p class="mb-0"><strong>Salutation:</strong> Dear John &nbsp;·&nbsp; <strong>Sign-off:</strong> Best wishes / Take care</p>
                    </div>
                </div>
                <p class="mt-3">
                    Key markers: contractions are avoided in formal, natural in informal. Vocabulary choice matters —
                    <em>"I am writing to draw your attention to..."</em> (formal) vs <em>"I just wanted to let you know..."</em> (informal).
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 1 — Reading section</strong> (all 3 sections, 60 min timed)</p>
                    <p class="mb-0 text-muted small">Complete under exam conditions. No pausing. Self-mark using the answer key and note which question types caused the most errors.</p>
                </div>
                <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_reading_001.php"
                   class="btn btn-primary mt-3">
                    <i class="bi bi-play-circle me-2"></i>Start Reading Practice Test 1
                </a>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 4 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R1 GT Skimming &amp; Scanning Class 1</h4>
                        <p class="text-muted mb-2">Skimming and scanning applied to IELTS General Reading passages.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Writing Task 1 Overview</h4>
                        <p class="text-muted mb-2">Full overview of the General Training Task 1 letter — format, requirements, and marking.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Skimming vs Scanning Decision Drill</h4>
                    <p class="mb-2">Given 10 question types (e.g. "Find the opening hours of the library"), decide: does this require skimming for gist or scanning for specific information?</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 5</h4>
                    <p class="mb-0">Write one <strong>formal letter</strong> (150+ words) — a complaint to a hotel manager about a faulty room. Write one <strong>informal letter</strong> (150+ words) — to a friend describing your new job. Focus on register: check your salutation, sign-off, and vocabulary choices. Bring both to Class 5 for peer review.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class03.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 3</a>
                <a href="class05.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 5</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class03.php" class="btn btn-outline-light btn-sm">← Class 3</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 4 — Here</span>
                <a href="class05.php" class="btn btn-outline-light btn-sm">Class 5 →</a>
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
