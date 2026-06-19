<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('fluent')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 18</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 18: Writing Task 2 — Body Paragraphs (PEEL) &amp; Opinion Essays'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 18: Writing Task 2 Body Paragraphs &amp; Opinion Essays</title>
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
                <li class="breadcrumb-item active">Class 18</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-pencil-square me-2" style="color:#16a34a;"></i>
                Class 18: Writing Task 2 — Body Paragraphs (PEEL) &nbsp;·&nbsp; Opinion Essays in Depth
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 18 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 4 — Listening</span>
            </div>

            <div class="content-section">
                <h2>The PEEL Paragraph Framework</h2>
                <p>
                    Every Task 2 body paragraph should follow the PEEL structure. A well-built PEEL paragraph
                    scores on Task Achievement (relevant point), Coherence &amp; Cohesion (logical flow),
                    Lexical Resource (example vocabulary), and Grammatical Range (varied sentence structures) — all four criteria in one paragraph.
                </p>
                <div class="week-section">
                    <div class="week-header">P — Point (Topic Sentence)</div>
                    <ul class="module-list">
                        <li>State the main idea of the paragraph in one clear sentence.</li>
                        <li>This sentence must directly answer the question — it is your argument, not background information.</li>
                        <li>Example: "One significant reason why governments should invest in public transport is the environmental benefit."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">E — Explanation (Development)</div>
                    <ul class="module-list">
                        <li>Explain <em>why</em> or <em>how</em> the point is true in 1–2 sentences.</li>
                        <li>Do not introduce a new argument here — deepen the one you have started.</li>
                        <li>Example: "When public transport is efficient and affordable, fewer people choose to drive, which directly reduces carbon emissions and air pollution in urban areas."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">E — Evidence (Example)</div>
                    <ul class="module-list">
                        <li>Support with a specific example — a country, city, statistic, or scenario. It does not need to be perfectly accurate; plausibility is enough.</li>
                        <li>Example: "For instance, cities such as Tokyo and Amsterdam have reported measurable improvements in air quality since expanding their metro and cycling networks."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">L — Link (Conclusion Sentence)</div>
                    <ul class="module-list">
                        <li>Wrap up the paragraph by restating how this point answers the question.</li>
                        <li>Example: "This demonstrates that investment in public infrastructure has clear environmental benefits that private vehicle use cannot replicate."</li>
                    </ul>
                </div>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <p class="mb-0"><strong>Word target:</strong> Each PEEL paragraph should be 80–120 words. Two body paragraphs of this length, plus a 2-sentence introduction and a 2–3 sentence conclusion, will comfortably reach the 250-word minimum.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Opinion Essays — Structure &amp; Strategy</h2>
                <p>
                    Opinion essays ("To what extent do you agree or disagree?") require you to take a clear
                    position. Sitting on the fence — "there are advantages and disadvantages on both sides" —
                    without a final stated opinion is penalised under Task Achievement.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Fully Agree</h4>
                        <p class="mb-0">Two body paragraphs, both supporting your view. Each paragraph gives a different reason. Simpler to write; easier to develop in depth.</p>
                    </div>
                    <div class="info-card">
                        <h4>Partially Agree</h4>
                        <p class="mb-0">Body paragraph 1 presents the concession (where the other view has some merit). Body paragraph 2 presents your stronger counter-argument. Conclude with your position.</p>
                    </div>
                    <div class="info-card">
                        <h4>Fully Disagree</h4>
                        <p class="mb-0">Two body paragraphs, both arguing against the statement. Mirror of "fully agree." State clearly in the thesis that you disagree and why.</p>
                    </div>
                    <div class="info-card">
                        <h4>Common Error</h4>
                        <p class="mb-0">Changing your opinion between the introduction and conclusion. Decide your position before writing and stay consistent throughout.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Opinion Essay — Sample Full Structure</h2>
                <p><em>Question: "Some people think that zoos are cruel and should be closed. To what extent do you agree?"</em></p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Introduction (2 sentences)</h4>
                        <p class="mb-0">Background: paraphrase the debate. Thesis: state your position — e.g., "This essay will partially agree, arguing that while some zoos do cause suffering, responsible zoos serve a vital conservation role."</p>
                    </div>
                    <div class="info-card">
                        <h4>Body 1 — Concession</h4>
                        <p class="mb-0">PEEL: Point — poorly regulated zoos cause distress. Explain — small enclosures, isolation. Example — reports of stereotypic behaviour in captive elephants. Link — this aspect is rightly criticised.</p>
                    </div>
                    <div class="info-card">
                        <h4>Body 2 — Main Argument</h4>
                        <p class="mb-0">PEEL: Point — accredited zoos contribute to species survival. Explain — breeding programmes, research funding. Example — Arabian oryx reintroduced from zoo stock. Link — closure would harm conservation.</p>
                    </div>
                    <div class="info-card">
                        <h4>Conclusion (2–3 sentences)</h4>
                        <p class="mb-0">Restate your position without copying the introduction. "In conclusion, while poorly managed zoos should face stricter regulation, the role of accredited facilities in conservation means a blanket closure would be counterproductive."</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 4 — Listening section</strong> (Parts 1–4, full timed)</p>
                    <p class="mb-0 text-muted small">After marking, focus on any gap-fill or completion questions you missed — identify whether a distractor was the cause.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 18 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>WT2 Introductions Class 1</h4>
                        <p class="text-muted mb-2">Step-by-step guide to writing a two-sentence Task 2 introduction — background sentence, thesis, and what to avoid.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">PEEL Paragraph Identification &amp; Build</h4>
                    <p class="mb-2">Part A: Read 3 sample body paragraphs and identify the P, E, E, and L components in each. Part B: Write one complete PEEL paragraph (80–100 words) for a given opinion essay topic.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 19</h4>
                    <p class="mb-0">Write a full opinion essay (250+ words) in 40 minutes. Choose your position before you start and maintain it throughout. After writing: (1) underline your topic sentence in each body paragraph, (2) circle your example in each paragraph, (3) count your total words, (4) check your introduction contains exactly two sentences. Submit to your tutor.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class17.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 17</a>
                <a href="class19.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 19</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class17.php" class="btn btn-outline-light btn-sm">← Class 17</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 18 — Here</span>
                <a href="class19.php" class="btn btn-outline-light btn-sm">Class 19 →</a>
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
