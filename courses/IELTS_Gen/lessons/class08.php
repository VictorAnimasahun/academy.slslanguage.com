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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 8 — Mock Test 1</li></ol></nav>
<?php render_upgrade_prompt('intermediate','Class 8: Mock Test 1 — Full Timed Exam'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 8: Mock Test 1</title>
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
                <li class="breadcrumb-item active">Class 8 — Mock Test 1</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-journal-richtext me-2" style="color:#0b77ff;"></i>
                Class 8: MOCK TEST 1 — Full Timed Exam (End of Month 1)
            </h1>
            <div class="highlight-box" style="background:#fffbeb;border-color:#f59e0b;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge-custom">Month 1 — Foundations</span>
                        <span class="badge-custom">Class 8 of 24</span>
                        <span class="badge-custom"><i class="bi bi-clock me-1"></i>3 Hours</span>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 fs-6">
                        <i class="bi bi-journal-richtext me-1"></i>Full Mock Exam
                    </span>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-journal-richtext me-2"></i>Full Timed Mock Exam — What Happens Today</h2>
                <p class="lead">
                    This class is a full timed mock examination covering all four IELTS skills under real exam conditions.
                    No pausing, no stopping mid-test, no checking answers until all four skills are complete.
                </p>
                <p>
                    The mock is not about scoring high — it is about identifying exactly where your marks are
                    going in a pressured, timed environment. Many students perform differently under exam conditions
                    compared to practice. This is your first opportunity to find out.
                </p>
            </div>

            <div class="content-section">
                <h2>Today's Four Skills</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-headphones me-2"></i>Listening</h4>
                        <p class="mb-1"><strong>Duration:</strong> 30 min + 10 min transfer</p>
                        <p class="mb-1"><strong>Format:</strong> Parts 1–4, 40 questions</p>
                        <p class="mb-0 text-muted small">Do not pause the recording. Complete Parts 1–4 in one sitting.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-book me-2"></i>Reading</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> 3 sections, 40 questions</p>
                        <p class="mb-0 text-muted small">Skim first, then answer. Move on if stuck — no blank answers.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-pencil me-2"></i>Writing</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> Task 1 (20 min) + Task 2 (40 min)</p>
                        <p class="mb-0 text-muted small">Stick to the time split. Task 2 is worth twice Task 1.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-mic me-2"></i>Speaking</h4>
                        <p class="mb-1"><strong>Duration:</strong> 11–14 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> Parts 1, 2 &amp; 3 simulation</p>
                        <p class="mb-0 text-muted small">Record yourself or complete live with your tutor.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Mock Exam Rules</h2>
                <ul class="custom-list">
                    <li>Find a quiet space with no interruptions for the full 3 hours.</li>
                    <li>Use a timer — replicate exam conditions as closely as possible.</li>
                    <li>Do not pause the Listening recording mid-section.</li>
                    <li>Do not check answers or look anything up during the test.</li>
                    <li>Complete all four skills in order: Listening → Reading → Writing → Speaking.</li>
                    <li>Submit your Writing responses and Speaking recording to your tutor after class.</li>
                    <li>Never leave a question blank — make your best guess if unsure.</li>
                </ul>
            </div>

            <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                <h4 style="color:#1d4ed8;margin-top:0;"><i class="bi bi-chat-left-text me-2"></i>After the Mock</h4>
                <ul class="mb-0">
                    <li>Written feedback on all four skills will be provided by your tutor.</li>
                    <li>Band score estimates will be given for each skill and overall.</li>
                    <li>Your Month 2 targeted improvement plan will be shared — focusing on the specific areas where you are losing the most marks.</li>
                    <li>Compare your results with your Month 1 self-assessment grid from Class 1.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Month 1 — What We Covered</h2>
                <p>Before the mock, take 5 minutes to review everything from Month 1:</p>
                <ul class="custom-list">
                    <li><strong>Listening:</strong> Form completion &amp; gap-fill strategies, MCQ elimination method, map and plan labelling</li>
                    <li><strong>Reading:</strong> Skimming for gist, scanning for specific information, True/False/Not Given vs Yes/No/Not Given</li>
                    <li><strong>Writing Task 1:</strong> Register (formal, semi-formal, informal), formal letter structure, vocabulary markers</li>
                    <li><strong>Speaking:</strong> IDEA framework for Part 1, cue card 1-minute planning technique for Part 2</li>
                    <li><strong>Band Descriptors:</strong> The four criteria for Writing and Speaking that examiners use</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="class07.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 7</a>
                <a href="class09.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Start Month 2 — Class 9</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class07.php" class="btn btn-outline-light btn-sm">← Class 7</a>
                <span class="btn btn-warning btn-sm disabled fw-bold text-dark">Mock Test 1 — Here</span>
                <a href="class09.php" class="btn btn-outline-light btn-sm">Month 2: Class 9 →</a>
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
