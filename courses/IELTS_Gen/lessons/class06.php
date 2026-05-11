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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 6</li></ol></nav>
<?php render_upgrade_prompt('intermediate','Class 6: Reading — True/False/Not Given · Writing Task 1 — Formal Letters'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 6: TFNG &amp; Formal Letters</title>
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
                <li class="breadcrumb-item active">Class 6</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#0b77ff;"></i>
                Class 6: Reading — True/False/Not Given &nbsp;·&nbsp; Writing Task 1 — Formal Letters
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 1 — Foundations</span>
                <span class="badge-custom">Class 6 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 1 — Speaking Part 1</span>
            </div>

            <div class="content-section">
                <h2>Reading — True / False / Not Given</h2>
                <p>
                    True/False/Not Given (TFNG) questions test whether a statement in the question <strong>matches, contradicts,
                    or is not addressed</strong> by the passage. This is one of the most misunderstood question types in IELTS.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4 style="color:#16a34a;">TRUE</h4>
                        <p class="mb-0">The passage <strong>explicitly agrees</strong> with the statement. You must find clear evidence in the text — not assume.</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#dc2626;">FALSE</h4>
                        <p class="mb-0">The passage <strong>explicitly contradicts</strong> the statement. There is direct evidence that the opposite is said.</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;">NOT GIVEN</h4>
                        <p class="mb-0">The information <strong>simply is not there</strong>. Not mentioned at all. Do not confuse this with False — the passage is silent, not contradictory.</p>
                    </div>
                </div>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;margin-top:1rem;">
                    <p class="mb-0"><strong>Critical rule:</strong> Never use your own knowledge. If the passage does not say it, it is NOT GIVEN — even if you personally know the statement to be true or false.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>TFNG vs YES / NO / NOT GIVEN — The Key Difference</h2>
                <p>
                    IELTS uses two similar tasks. Mixing them up is a common and costly mistake.
                </p>
                <ul class="custom-list">
                    <li><strong>True/False/Not Given</strong> — Tests <em>factual claims</em>. "According to the passage, the factory opened in 1942." → Is this fact stated, contradicted, or not mentioned?</li>
                    <li><strong>Yes/No/Not Given</strong> — Tests the <em>writer's opinion or view</em>. "The writer believes the new policy will succeed." → Does the writer express this view, contradict it, or not comment?</li>
                </ul>
                <p>The question stem tells you which applies: <em>"According to the passage..."</em> = TFNG. <em>"The writer believes/argues/suggests..."</em> = YNNG.</p>
            </div>

            <div class="content-section">
                <h2>Writing Task 1 — Formal Letter Structure</h2>
                <p>Formal letters require a precise structure. Every paragraph has a purpose.</p>
                <div class="week-section">
                    <div class="week-header">Paragraph 1 — State Your Purpose</div>
                    <ul class="module-list">
                        <li>Open by telling the reader exactly why you are writing.</li>
                        <li>Example: <em>"I am writing to draw your attention to a serious problem I experienced during my recent stay at your hotel."</em></li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Paragraphs 2–3 — Develop Each Point</div>
                    <ul class="module-list">
                        <li>Cover each bullet point from the task prompt in a separate paragraph.</li>
                        <li>Use formal linking: "Furthermore," "In addition," "As a result of this."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Paragraph 4 — Closing &amp; Expected Action</div>
                    <ul class="module-list">
                        <li>State what you expect to happen next: a refund, a repair, a response.</li>
                        <li>Example: <em>"I look forward to your prompt response and a full refund for my stay."</em></li>
                    </ul>
                </div>
                <p class="mt-3"><strong>Formal vocabulary to know:</strong> "I am writing to...", "I would be grateful if you could...", "I would appreciate it if...", "I look forward to hearing from you."</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 1 — Speaking Part 1</strong> (timed, 4–5 min)</p>
                    <p class="mb-0 text-muted small">Complete under timed conditions with a partner or self-record. Cover 3–4 topic areas. Aim for 2–3 sentences per answer using the IDEA framework.</p>
                </div>
                <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_speaking_001.php"
                   class="btn btn-primary mt-3">
                    <i class="bi bi-play-circle me-2"></i>Start Speaking Practice Test 1
                </a>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 6 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R4 Paraphrasing Class 1</h4>
                        <p class="text-muted mb-2">How IELTS paraphrases statements in TFNG questions — and how to identify the match.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#0b77ff;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">TFNG vs YNNG Decision Drill</h4>
                    <p class="mb-2">Read 10 statements and decide: does this require True/False/Not Given OR Yes/No/Not Given thinking? Then provide the correct answer for each.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 7</h4>
                    <p class="mb-0">Write a <strong>formal complaint letter</strong> (150+ words) to the manager of a sports centre about broken equipment that caused you inconvenience. Use the four-paragraph structure covered today: purpose → details → further point → expected action. Check your salutation (Dear Sir/Madam) and sign-off (Yours faithfully).</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class05.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 5</a>
                <a href="class07.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 7</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class05.php" class="btn btn-outline-light btn-sm">← Class 5</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 6 — Here</span>
                <a href="class07.php" class="btn btn-outline-light btn-sm">Class 7 →</a>
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
