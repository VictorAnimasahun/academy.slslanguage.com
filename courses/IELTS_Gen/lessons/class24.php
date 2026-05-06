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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 24</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 24: Mock Test 3 — Final Exam'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 24: Mock Test 3 — Final Exam</title>
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
                <li class="breadcrumb-item active">Class 24</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-journal-richtext me-2" style="color:#16a34a;"></i>
                Class 24: MOCK TEST 3 — Final Full Exam Simulation (End of Month 3)
            </h1>
            <div class="highlight-box" style="background:#fffbeb;border-color:#f59e0b;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                        <span class="badge-custom">Class 24 of 24</span>
                        <span class="badge-custom"><i class="bi bi-clock me-1"></i>3 Hours</span>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-2 fs-6">
                        <i class="bi bi-journal-richtext me-1"></i>Final Mock Exam
                    </span>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-journal-richtext me-2"></i>Final Exam — What Happens Today</h2>
                <p class="lead">
                    This is Mock Test 3 — your final full timed exam simulation. This is the closest possible
                    replica of the real IELTS General test. Treat it exactly as you would the real exam:
                    no pausing, no mid-test checking, no interruptions.
                </p>
                <p>
                    After three months and 21 teaching classes, this mock measures your true readiness.
                    Your band score prediction from today will be the most accurate forecast of your
                    performance in the real test.
                </p>
            </div>

            <div class="content-section">
                <h2>Today's Four Skills</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-headphones me-2"></i>Listening</h4>
                        <p class="mb-1"><strong>Duration:</strong> 30 min + 10 min transfer</p>
                        <p class="mb-1"><strong>Format:</strong> Parts 1–4, 40 questions</p>
                        <p class="mb-0 text-muted small">Apply every distractor strategy from Class 23. Write after the speaker confirms.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-book me-2"></i>Reading</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> 3 sections, 40 questions</p>
                        <p class="mb-0 text-muted small">Write directly on the answer sheet as you go. Never leave a blank.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-pencil me-2"></i>Writing</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> Task 1 (20 min) + Task 2 (40 min)</p>
                        <p class="mb-0 text-muted small">Identify the Task 2 question type before writing a word. Apply Universal Checklist.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-mic me-2"></i>Speaking</h4>
                        <p class="mb-1"><strong>Duration:</strong> 11–14 minutes</p>
                        <p class="mb-1"><strong>Format:</strong> Parts 1, 2 &amp; 3 simulation</p>
                        <p class="mb-0 text-muted small">Record yourself. State → Reason → Example in every Part 3 answer.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Mock Exam Rules</h2>
                <ul class="custom-list">
                    <li>Find a quiet space with no interruptions for the full 3 hours.</li>
                    <li>Use a timer — replicate real exam conditions as closely as possible.</li>
                    <li>Do not pause the Listening recording mid-section.</li>
                    <li>Do not check answers or look anything up during the test.</li>
                    <li>Complete all four skills in order: Listening → Reading → Writing → Speaking.</li>
                    <li>Submit your Writing responses and Speaking recording to your tutor after the exam.</li>
                    <li>Never leave a question blank — always make your best guess.</li>
                </ul>
            </div>

            <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                <h4 style="color:#1d4ed8;margin-top:0;"><i class="bi bi-chat-left-text me-2"></i>After the Mock</h4>
                <ul class="mb-0">
                    <li>Comprehensive written feedback on all four skills from your tutor.</li>
                    <li>Final band score prediction — compared against Mock 1 and Mock 2 to show your full progress arc.</li>
                    <li>Personalised post-programme study plan — what to focus on between now and your real test date.</li>
                    <li>Certificate of Completion is awarded on finishing the full 3-month programme.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Full Programme Recap — 3 Months, 24 Classes</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4 style="color:#0b77ff;">Month 1 — Foundations</h4>
                        <ul class="mb-0 small">
                            <li>Listening: form completion, MCQ, map labelling</li>
                            <li>Reading: skimming, scanning, TFNG/YNNG</li>
                            <li>Writing Task 1: register, formal letters</li>
                            <li>Speaking: IDEA (Part 1), cue card (Part 2)</li>
                            <li>Mock Test 1</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;">Month 2 — Skill Development</h4>
                        <ul class="mb-0 small">
                            <li>Listening: note/table/flowchart, distractors, synonyms</li>
                            <li>Reading: matching headings, MCQ, sentence completion</li>
                            <li>Writing Task 1: all three registers</li>
                            <li>Writing Task 2: introductions, five question types</li>
                            <li>Speaking Part 3: hedging, critical thinking framework</li>
                            <li>Mock Test 2</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#16a34a;">Month 3 — Mastery</h4>
                        <ul class="mb-0 small">
                            <li>Speaking Part 3: abstract discussion mastery</li>
                            <li>Writing Task 2: PEEL, all 5 essay types, vocabulary banks</li>
                            <li>Reading: all question types rapid review</li>
                            <li>Listening: full consolidation + distractor mastery</li>
                            <li>Test-day strategy</li>
                            <li>Mock Test 3 (today)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;text-align:center;">
                    <i class="bi bi-award-fill" style="font-size:3rem;color:#16a34a;display:block;margin-bottom:1rem;"></i>
                    <h3 style="color:#15803d;margin-top:0;">Congratulations on Completing the Programme</h3>
                    <p class="mb-2">You have completed 24 classes, 3 mock exams, 4 practice test sets, and a full suite of quizzes and take-home exercises. Your certificate of completion will be issued by your tutor after Mock Test 3 feedback is delivered.</p>
                    <p class="mb-0 text-muted small">Next step: book your real IELTS General test date. Use your Mock 3 band score prediction to gauge your readiness.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class23.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 23</a>
                <span class="btn btn-success btn-lg disabled"><i class="bi bi-check-circle me-2"></i>Course Complete</span>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-secondary"><i class="bi bi-mortarboard me-2"></i>Course Overview</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class23.php" class="btn btn-outline-light btn-sm">← Class 23</a>
                <span class="btn btn-warning btn-sm disabled fw-bold text-dark">Final Mock — Here</span>
                <span class="btn btn-success btn-sm disabled fw-bold">Course Complete</span>
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
