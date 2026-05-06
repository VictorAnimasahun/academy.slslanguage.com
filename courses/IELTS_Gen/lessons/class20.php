<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../../../registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('fluent')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 20</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 20: Writing Task 2 Masterclass — All 5 Essay Types'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 20: Writing Task 2 Masterclass</title>
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
                <li class="breadcrumb-item active">Class 20</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-trophy me-2" style="color:#16a34a;"></i>
                Class 20: Writing Task 2 Masterclass — All 5 Essay Types
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 20 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#fef9c3;color:#854d0e;border-color:#eab308;"><i class="bi bi-arrow-repeat me-1"></i>Writing Masterclass — No Practice Test</span>
            </div>

            <div class="content-section">
                <h2>All Five Essay Types — Quick Identification Guide</h2>
                <p>
                    This class consolidates everything from Classes 14, 18, and 19 into a single reference session.
                    By the end of this class, you should be able to identify any Task 2 question type in under 30 seconds
                    and know exactly which structure to use.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>1. Opinion / Agree or Disagree</h4>
                        <p class="mb-1"><strong>Signal words:</strong> "To what extent do you agree or disagree?" / "Do you agree or disagree?"</p>
                        <p class="mb-0"><strong>Structure:</strong> Intro (your position) → Body 1 (reason 1) → Body 2 (reason 2 or concession) → Conclusion (restate position)</p>
                    </div>
                    <div class="info-card">
                        <h4>2. Discussion</h4>
                        <p class="mb-1"><strong>Signal words:</strong> "Discuss both views and give your opinion."</p>
                        <p class="mb-0"><strong>Structure:</strong> Intro (neutral preview) → Body 1 (View A) → Body 2 (View B) → Conclusion (your view for the first time)</p>
                    </div>
                    <div class="info-card">
                        <h4>3. Problem &amp; Solution</h4>
                        <p class="mb-1"><strong>Signal words:</strong> "What are the causes/problems... what solutions can you suggest?" / "How can this problem be solved?"</p>
                        <p class="mb-0"><strong>Structure:</strong> Intro → Body 1 (problems/causes) → Body 2 (solutions) → Conclusion</p>
                    </div>
                    <div class="info-card">
                        <h4>4. Advantages &amp; Disadvantages</h4>
                        <p class="mb-1"><strong>Signal words:</strong> "Discuss the advantages and disadvantages." / "Do the advantages outweigh the disadvantages?"</p>
                        <p class="mb-0"><strong>Structure:</strong> Intro → Body 1 (advantages) → Body 2 (disadvantages) → Conclusion (your verdict if asked)</p>
                    </div>
                    <div class="info-card">
                        <h4>5. Double Question</h4>
                        <p class="mb-1"><strong>Signal words:</strong> Two separate question marks in the task. "Why is this happening? What can be done about it?"</p>
                        <p class="mb-0"><strong>Structure:</strong> Intro → Body 1 (answers Question 1) → Body 2 (answers Question 2) → Conclusion</p>
                    </div>
                </div>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Trap:</strong> The most commonly confused pair is Opinion vs Discussion. If the question says "discuss both views" it is a Discussion essay — even if it also says "give your opinion." Hold your opinion for the conclusion.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Universal Task 2 Checklist</h2>
                <p>Apply this to every Task 2 essay before you stop writing:</p>
                <ul class="custom-list">
                    <li><strong>Word count:</strong> 250 words minimum. Under 249 = automatic band score reduction.</li>
                    <li><strong>Question type identified:</strong> Did you use the right structure?</li>
                    <li><strong>All parts answered:</strong> If the question has two questions, have you answered both?</li>
                    <li><strong>Introduction:</strong> Two sentences — background + thesis. No "Nowadays."</li>
                    <li><strong>Body paragraphs:</strong> Each one has a topic sentence, explanation, example, and link.</li>
                    <li><strong>Conclusion:</strong> Restates position, no new ideas, 2–3 sentences.</li>
                    <li><strong>Cohesive devices:</strong> Varied — not all "however" and "furthermore."</li>
                    <li><strong>Vocabulary:</strong> At least one "less common" item per body paragraph.</li>
                    <li><strong>Grammar:</strong> Mix of simple and complex sentences. No repeated grammatical error.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>High-Frequency Task 2 Topics — Vocabulary Banks</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Environment</h4>
                        <p class="mb-0">carbon emissions, fossil fuels, renewable energy, deforestation, biodiversity, climate change, sustainable development, ecological impact, greenhouse gases</p>
                    </div>
                    <div class="info-card">
                        <h4>Technology</h4>
                        <p class="mb-0">artificial intelligence, automation, digital literacy, surveillance, cybersecurity, remote working, social media, screen time, technological dependency</p>
                    </div>
                    <div class="info-card">
                        <h4>Education</h4>
                        <p class="mb-0">academic achievement, vocational training, higher education, curriculum, critical thinking, lifelong learning, tuition fees, distance learning, standardised testing</p>
                    </div>
                    <div class="info-card">
                        <h4>Health</h4>
                        <p class="mb-0">sedentary lifestyle, mental health, obesity, preventive care, healthcare provision, well-being, physical activity, nutritional awareness, public health campaigns</p>
                    </div>
                    <div class="info-card">
                        <h4>Work &amp; Economy</h4>
                        <p class="mb-0">unemployment, job security, gig economy, remote work, work-life balance, economic inequality, minimum wage, globalisation, outsourcing</p>
                    </div>
                    <div class="info-card">
                        <h4>Society &amp; Culture</h4>
                        <p class="mb-0">multiculturalism, social cohesion, gender equality, ageing population, urbanisation, immigration, community engagement, cultural identity, social media influence</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Timed Writing Sprint</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>In-class task:</strong> Choose one prompt from each essay type (5 in total). For each, write only the introduction and one body paragraph — not the full essay. Time each at 12 minutes. Focus: correct structure for the question type and one PEEL paragraph with a clear example.</p>
                    <p class="mb-0 text-muted small">Total time: approximately 60 minutes. This builds the muscle memory of switching structures quickly — a critical exam skill.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 20 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Writing Task 2 Overview</h4>
                        <p class="text-muted mb-2">Complete overview of Task 2 — question types, marking criteria, timing, common errors, and what examiners look for at band 7 and above.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Rosiane — Writing Task 2 Demo</h4>
                        <p class="text-muted mb-2">Live essay writing demonstration — watch a full Task 2 essay written from scratch with tutor commentary on structure and vocabulary choices.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Essay Type Identification Speed Round</h4>
                    <p class="mb-2">10 Task 2 prompts. For each: (1) identify the essay type in under 10 seconds, (2) write the one-sentence thesis you would use. Topics span all five types across environment, education, technology, health, and society.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 21</h4>
                    <p class="mb-0">Write two full Task 2 essays (250+ words each) in 40 minutes each — one opinion essay and one problem-and-solution essay. After each, apply the Universal Checklist above. Compare your two essays: which question type felt more comfortable? Which vocabulary bank did you use most naturally? Bring both essays to Class 21 for discussion.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class19.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 19</a>
                <a href="class21.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 21</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class19.php" class="btn btn-outline-light btn-sm">← Class 19</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 20 — Here</span>
                <a href="class21.php" class="btn btn-outline-light btn-sm">Class 21 →</a>
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
