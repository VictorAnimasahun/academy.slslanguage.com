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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 23</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 23: Final Prep — Listening Masterclass &amp; Test-Day Strategy'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 23: Final Prep &amp; Test-Day Strategy</title>
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
                <li class="breadcrumb-item active">Class 23</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#16a34a;"></i>
                Class 23: Final Prep — Listening Masterclass &amp; Test-Day Strategy
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 23 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#fef9c3;color:#854d0e;border-color:#eab308;"><i class="bi bi-shield-check me-1"></i>Final Prep — No Practice Test</span>
            </div>

            <div class="content-section">
                <h2>Listening Masterclass — All Question Types Consolidated</h2>
                <p>
                    This is your final Listening revision before Mock Test 3. The goal is not to learn new material
                    but to consolidate every strategy into a single, consistent approach that you can apply on exam day without thinking.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Form / Note / Table Completion</h4>
                        <p class="mb-0">Predict the answer type before the recording. Write the exact words you hear. Respect the word limit. Do not paraphrase.</p>
                    </div>
                    <div class="info-card">
                        <h4>Multiple Choice</h4>
                        <p class="mb-0">Read all options before the recording. Eliminate distractors — wait for the speaker to confirm before writing. The answer often comes after a distractor.</p>
                    </div>
                    <div class="info-card">
                        <h4>Map / Plan Labelling</h4>
                        <p class="mb-0">Orient using the starting point. Trace the route physically with your pencil. Write after completing the trace, not during it.</p>
                    </div>
                    <div class="info-card">
                        <h4>Flowchart Completion</h4>
                        <p class="mb-0">Read the completed steps first for context. Follow the sequence language (first, then, after that, finally). Gaps appear in order.</p>
                    </div>
                    <div class="info-card">
                        <h4>Matching</h4>
                        <p class="mb-0">Read all options before the recording. The recording uses synonyms — not the exact option words. Cross off options as they are used (unless "may be used more than once").</p>
                    </div>
                    <div class="info-card">
                        <h4>Distractor Management</h4>
                        <p class="mb-0">If you hear the answer, keep listening for 3–5 more seconds. Corrections and rejections often follow. Never write during a correction — wait for the revised answer.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Test-Day Strategy — Listening</h2>
                <ul class="custom-list">
                    <li>Use the preview time between sections to read ahead — every second counts.</li>
                    <li>Write your answers on the question paper first, then transfer during the 10 minutes at the end.</li>
                    <li>If you miss an answer, move immediately to the next question. Do not dwell.</li>
                    <li>Spell carefully during transfer — spelling errors cost marks in completion tasks.</li>
                    <li>Never leave a blank answer. A guess has a chance; a blank has none.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Test-Day Strategy — Reading</h2>
                <ul class="custom-list">
                    <li>Write answers directly on the answer sheet as you go — there is no separate transfer time.</li>
                    <li>Spend no more than 20 minutes on each section. Move on even if unfinished.</li>
                    <li>Flag difficult questions with a small mark and return at the end.</li>
                    <li>Section 3 is the hardest — approach it fresh by doing Sections 1 and 2 efficiently.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Test-Day Strategy — Writing</h2>
                <ul class="custom-list">
                    <li>Read both tasks before writing anything. Decide the question type for Task 2 immediately.</li>
                    <li>Task 1: 20 minutes maximum. Start your timer. Stop at 20 minutes even if Task 1 is incomplete — Task 2 is worth more.</li>
                    <li>Task 2: 40 minutes. Spend the first 3 minutes planning your structure, not writing.</li>
                    <li>Save 3–4 minutes at the end to check for consistent register (Task 1) and consistent position (Task 2).</li>
                    <li>Count your words — do not guess. Undercount means a penalty.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Test-Day Strategy — Speaking</h2>
                <ul class="custom-list">
                    <li>The speaking test may be on a different day from the other three skills — check your test schedule.</li>
                    <li>Speak to the examiner as if having a conversation — not as if reciting memorised answers. Examiners are trained to spot scripted responses.</li>
                    <li>If you do not understand a Part 1 question, ask politely: "Sorry, could you repeat that?" You will not be penalised.</li>
                    <li>In Part 2, do not stop before 2 minutes. Fill the time — add feelings, comparisons, outcomes.</li>
                    <li>In Part 3, take a breath before answering. A 2-second pause to think is fine. A 5-second silence is not.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>The Night Before and Morning Of</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>The Night Before</h4>
                        <p class="mb-0">Do not study the night before. Review your personal checklist (top 3 errors per skill) once, then stop. Sleep at least 7–8 hours. Prepare your ID, test confirmation, and any required stationery.</p>
                    </div>
                    <div class="info-card">
                        <h4>The Morning Of</h4>
                        <p class="mb-0">Eat a proper meal. Arrive early — at least 30 minutes before the test start time. Warm up your voice before Speaking (read something aloud, have a conversation). Do not cram.</p>
                    </div>
                    <div class="info-card">
                        <h4>During the Test</h4>
                        <p class="mb-0">If one question goes badly, reset immediately. Every question is independent. A bad Section 3 in Reading does not affect your Section 1 score — stay composed and move forward.</p>
                    </div>
                    <div class="info-card">
                        <h4>After the Test</h4>
                        <p class="mb-0">Do not compare answers with other candidates immediately after — it serves no purpose. Results take 3–13 days depending on your test type (paper-based or computer-based).</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 23 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>L1 Part 1 Listening — Revision</h4>
                        <p class="text-muted mb-2">Full revision of Part 1 strategies — form completion, gap-fill, and distractor management under exam conditions.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Listening Strategy Audit</h4>
                    <p class="mb-2">12 Listening questions covering 6 question types (2 each). For each question, state: (1) what question type it is, (2) what your strategy is before the recording starts, (3) your answer. Review your accuracy rate by question type — your weakest type needs the most attention in Mock Test 3.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Preparation for Mock Test 3</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Your Final Checklist</h4>
                    <p class="mb-2">Before Mock Test 3 (Class 24), review these four things — one per skill:</p>
                    <ul class="mb-0">
                        <li><strong>Listening:</strong> Your top 2 distractor moments from the Class 22 recording — what triggered the error?</li>
                        <li><strong>Reading:</strong> Your Not Given accuracy rate — what was your false True/False count in Class 21?</li>
                        <li><strong>Writing:</strong> Your Task 2 word count and question type identification speed — both consistent?</li>
                        <li><strong>Speaking:</strong> Your longest pause in the Class 22 recording — was it under 3 seconds?</li>
                    </ul>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class22.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 22</a>
                <a href="class24.php" class="btn btn-warning btn-lg"><i class="bi bi-journal-richtext me-2"></i>Next: Final Mock Test 3 (Class 24)</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class22.php" class="btn btn-outline-light btn-sm">← Class 22</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 23 — Here</span>
                <a href="class24.php" class="btn btn-warning btn-sm text-dark">Final Mock (Class 24) →</a>
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
