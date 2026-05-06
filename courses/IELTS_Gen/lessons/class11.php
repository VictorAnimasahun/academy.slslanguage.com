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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 11</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 11: Reading — Multiple Choice · Writing Task 1 — Semi-Formal Letters'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 11: Reading MCQ &amp; Semi-Formal Letters</title>
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
                <li class="breadcrumb-item active">Class 11</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#6366f1;"></i>
                Class 11: Reading — Multiple Choice &nbsp;·&nbsp; Writing Task 1 — Semi-Formal Letters
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 2 — Skill Development</span>
                <span class="badge-custom">Class 11 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 2 — Speaking</span>
            </div>

            <div class="content-section">
                <h2>Reading — Multiple Choice Strategies</h2>
                <p>
                    Reading MCQ differs from Listening MCQ because you can re-read. But the trap is the same:
                    IELTS puts options in the passage that are <em>almost</em> right — partially true but not fully
                    supported. The correct answer must be <strong>completely supported</strong> by the text, not just plausible.
                </p>
                <h3>The Four-Step Method</h3>
                <ol class="custom-list">
                    <li><strong>Read the question stem only</strong> — not the options yet. Understand exactly what you are looking for.</li>
                    <li><strong>Underline the key word(s)</strong> in the question that define your search.</li>
                    <li><strong>Scan the passage</strong> for that key word or its paraphrase. Read that section carefully.</li>
                    <li><strong>Read all options</strong> and eliminate. The wrong answers are usually: (a) true but not what the question asks, (b) a combination of two details that are never linked in the passage, or (c) contradict the text.</li>
                </ol>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Rule:</strong> If you cannot point to the exact sentence in the passage that proves an option is correct, it is wrong — even if it sounds reasonable.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Reading — Keyword Underlining Technique</h2>
                <p>
                    Paraphrasing is IELTS's primary tool for making Reading questions difficult.
                    The passage will almost never use the same words as the question options.
                </p>
                <ul class="custom-list">
                    <li>Before reading any question, underline the specific noun or concept that defines your search.</li>
                    <li>Scan the passage for that word OR any of its common synonyms.</li>
                    <li>Build your paraphrase bank: "increase" → rise, grow, surge, climb, escalate. "Decrease" → fall, drop, decline, reduce. "Important" → significant, crucial, vital, critical.</li>
                    <li>Once you locate the relevant sentence, read the full paragraph before choosing your answer.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Writing Task 1 — Semi-Formal Letters</h2>
                <p>
                    Semi-formal is the most nuanced register. You know the person by name but the relationship
                    is professional or neighbourly — not intimate. Tone: warmer than formal, more careful than informal.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Who Receives It</h4>
                        <p class="mb-0">Landlord, neighbour, local council member, colleague you know by name, manager you have met briefly.</p>
                    </div>
                    <div class="info-card">
                        <h4>Salutation &amp; Sign-off</h4>
                        <p class="mb-0"><strong>Opening:</strong> Dear Mr/Ms [Surname]<br><strong>Closing:</strong> Kind regards / Best regards</p>
                    </div>
                    <div class="info-card">
                        <h4>Vocabulary Markers</h4>
                        <p class="mb-0">"I hope this finds you well." / "I wanted to bring to your attention..." / "I would appreciate it if you could..."</p>
                    </div>
                    <div class="info-card">
                        <h4>Common Error</h4>
                        <p class="mb-0">Slipping into fully informal language mid-letter ("Hey, just wanted to say..."). Stay consistently semi-formal throughout.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 2 — Speaking section</strong> (Parts 1 &amp; 2 timed)</p>
                    <p class="mb-0 text-muted small">Complete with a partner or self-record. Part 1: 4–5 minutes on 3–4 topic areas. Part 2: full cue card with 1-minute prep and 2-minute response.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 11 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R6 Underlining and Keywords Class 1</h4>
                        <p class="text-muted mb-2">How to use keyword underlining to locate answers quickly in IELTS Reading passages.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">MCQ Elimination + Keyword Spotting</h4>
                    <p class="mb-2">10 Reading MCQ questions from a sample passage. For each: (1) underline the keyword in the question, (2) locate the relevant text, (3) eliminate wrong options, (4) select your answer.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 12</h4>
                    <p class="mb-0">Write a <strong>semi-formal letter</strong> (150+ words) to your landlord requesting a repair to a broken heating system. Write a <strong>semi-formal email</strong> (150+ words) to a colleague inviting them to a work social event. Compare the tone of both — is the register consistently semi-formal in each?</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class10.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 10</a>
                <a href="class12.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 12</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class10.php" class="btn btn-outline-light btn-sm">← Class 10</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 11 — Here</span>
                <a href="class12.php" class="btn btn-outline-light btn-sm">Class 12 →</a>
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
