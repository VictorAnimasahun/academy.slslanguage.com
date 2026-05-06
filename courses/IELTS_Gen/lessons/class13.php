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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 13</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 13: Speaking Part 3 — A Complete Guide'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 13: Speaking Part 3</title>
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
                <li class="breadcrumb-item active">Class 13</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color:#6366f1;"></i>
                Class 13: Speaking Part 3 — A Complete Guide &nbsp;·&nbsp; Critical Thinking &amp; Extended Responses
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 2 — Skill Development</span>
                <span class="badge-custom">Class 13 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 3 — Reading</span>
            </div>

            <div class="content-section">
                <h2>Speaking Part 3 — What It Tests</h2>
                <p>
                    Part 3 shifts from your personal experience (Part 2) to broader, abstract discussion. The examiner tests your ability to discuss <strong>society, trends, and ideas</strong> — not just your own life. This is the highest-difficulty part of the Speaking test, and it is where band 7+ candidates distinguish themselves.
                </p>
                <h3>Typical Part 3 Question Stems</h3>
                <ul class="custom-list">
                    <li>"Why do you think...?"</li>
                    <li>"How has [topic] changed in recent years?"</li>
                    <li>"Do you think [X] will continue in the future?"</li>
                    <li>"What are the advantages/disadvantages of...?"</li>
                    <li>"In what ways could [X] be improved?"</li>
                    <li>"How does [X] affect society in general?"</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Expressing and Hedging Opinions</h2>
                <p>
                    You do not need to give definitive answers in Part 3. In fact, hedging — presenting ideas as possibilities rather than certainties — is a sign of academic sophistication.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Strong Opinion</h4>
                        <ul class="mb-0">
                            <li>"I firmly believe that..."</li>
                            <li>"I am convinced that..."</li>
                            <li>"It is clear to me that..."</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Hedged Opinion</h4>
                        <ul class="mb-0">
                            <li>"I suppose it depends on..."</li>
                            <li>"On balance, I think..."</li>
                            <li>"It's difficult to say, but I'd argue..."</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Adding Nuance</h4>
                        <ul class="mb-0">
                            <li>"...although there are exceptions."</li>
                            <li>"...in most cases, at least."</li>
                            <li>"...though it varies by context."</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Never Start With</h4>
                        <p class="mb-0">"I think because..." without a reason. Always support your view: state → reason → example → qualification.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Critical Thinking Framework — Extending Your Responses</h2>
                <p>The examiner expects at least 3–5 sentences per Part 3 answer. Use this five-step framework:</p>
                <div class="week-section">
                    <div class="week-header">1. State your view</div>
                    <ul class="module-list"><li>"I think governments should invest more in public transport."</li></ul>
                </div>
                <div class="week-section">
                    <div class="week-header">2. Give a reason</div>
                    <ul class="module-list"><li>"The reason is that traffic congestion in major cities is becoming unsustainable."</li></ul>
                </div>
                <div class="week-section">
                    <div class="week-header">3. Give an example</div>
                    <ul class="module-list"><li>"For example, investment in metro systems across European cities has dramatically reduced car use."</li></ul>
                </div>
                <div class="week-section">
                    <div class="week-header">4. Consider the other side</div>
                    <ul class="module-list"><li>"However, in rural areas this approach isn't always practical."</li></ul>
                </div>
                <div class="week-section">
                    <div class="week-header">5. Conclude or qualify</div>
                    <ul class="module-list"><li>"So I'd say the approach needs to be tailored to local conditions."</li></ul>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 3 — Reading section</strong> (all 3 sections, 60 min timed)</p>
                    <p class="mb-0 text-muted small">Complete under exam conditions. Self-mark and note which question types caused the most errors.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 13 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>IELTS Speaking Test Overview</h4>
                        <p class="text-muted mb-2">Full walkthrough of all three Speaking parts — what the examiner assesses at each stage.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>S3 Part 1 Speaking Class 2</h4>
                        <p class="text-muted mb-2">Advanced Part 1 fluency — varying response length and using lexical range naturally.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Part 3 Opinion Extension Framework</h4>
                    <p class="mb-2">5 abstract Part 3 questions. For each, write a 3-sentence extended answer using the framework: view → reason → example. Topics: education, technology, environment, health, and work.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 14</h4>
                    <p class="mb-0">Answer 8 Part 3 abstract questions in writing (3–5 sentences each). Then practise speaking those answers without reading them. Record yourself and listen back — check for: (1) clear opinion, (2) reason given, (3) example used, (4) no long pauses over 3 seconds.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class12.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 12</a>
                <a href="class14.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 14</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class12.php" class="btn btn-outline-light btn-sm">← Class 12</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 13 — Here</span>
                <a href="class14.php" class="btn btn-outline-light btn-sm">Class 14 →</a>
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
