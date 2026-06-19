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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 21</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 21: Reading Mastery — All Question Types'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 21: Reading Mastery</title>
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
                <li class="breadcrumb-item active">Class 21</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#16a34a;"></i>
                Class 21: Reading Mastery — All Question Types &amp; Speed Strategies
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 21 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 4 — Writing Task 2</span>
            </div>

            <div class="content-section">
                <h2>All Reading Question Types — Quick Reference</h2>
                <p>
                    IELTS General Reading uses up to 10 different question types across its three sections.
                    Knowing what each type requires — before you read the passage — is the difference between
                    a strategic reader and a confused one.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>True / False / Not Given</h4>
                        <p class="mb-0"><strong>True:</strong> passage clearly supports the statement. <strong>False:</strong> passage clearly contradicts it. <strong>Not Given:</strong> the passage neither confirms nor denies it. The statement may sound plausible — but if the passage doesn't address it, it's NG.</p>
                    </div>
                    <div class="info-card">
                        <h4>Yes / No / Not Given</h4>
                        <p class="mb-0">Applies to opinion/claim questions (not factual). <strong>Yes:</strong> the writer agrees. <strong>No:</strong> the writer disagrees. <strong>Not Given:</strong> the writer's position on this is not stated. Confusing TFNG and YNNG is a common error.</p>
                    </div>
                    <div class="info-card">
                        <h4>Multiple Choice</h4>
                        <p class="mb-0">Locate the relevant paragraph using the question keyword. Read the whole paragraph before choosing. Eliminate: (a) options that contradict the text, (b) options that are partially correct but miss the key detail.</p>
                    </div>
                    <div class="info-card">
                        <h4>Matching Headings</h4>
                        <p class="mb-0">Read the first and last sentence of each paragraph for the main idea. Match to the heading that covers the whole paragraph's theme — not just one detail. Some headings will not be used.</p>
                    </div>
                    <div class="info-card">
                        <h4>Matching Information</h4>
                        <p class="mb-0">Different from Matching Headings — you are matching a specific piece of information to the paragraph that contains it. Scan each paragraph for the relevant fact, not the overall theme.</p>
                    </div>
                    <div class="info-card">
                        <h4>Sentence / Summary / Note Completion</h4>
                        <p class="mb-0">Lift exact words from the passage. Respect the word limit. The answers follow the passage order. Grammar of the completed sentence must be correct.</p>
                    </div>
                    <div class="info-card">
                        <h4>Short Answer Questions</h4>
                        <p class="mb-0">Answer with words from the passage. Keep to the word limit. Begin your search with the keyword in the question — the answer will be nearby.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Speed Strategies — Finishing in 60 Minutes</h2>
                <p>
                    Most students do not run out of knowledge in Reading — they run out of time. These strategies
                    are designed to get you through all 40 questions within the 60-minute window.
                </p>
                <div class="week-section">
                    <div class="week-header">Sections 1 &amp; 2 (approximately 20 minutes combined)</div>
                    <ul class="module-list">
                        <li>Section 1 is always shorter and easier — aim to finish it in 8–10 minutes.</li>
                        <li>Section 2 is semi-formal/formal — skim once, then answer. 10–12 minutes.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Section 3 (approximately 25 minutes)</div>
                    <ul class="module-list">
                        <li>The longest and most complex passage. Read the questions first, not the passage.</li>
                        <li>Scan for each answer in order — do not read the passage end-to-end first.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">The 2-Minute Rule</div>
                    <ul class="module-list">
                        <li>If you have been on one question for more than 2 minutes, make your best guess and move on.</li>
                        <li>Come back at the end if time allows. Never leave a question blank.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Transfer Time</div>
                    <ul class="module-list">
                        <li>Unlike Listening, Reading has no separate transfer time. Write your answers directly on the answer sheet as you go — do not leave them on the question paper.</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <h2>The Not Given Trap — Final Mastery</h2>
                <p>
                    "Not Given" is the answer that most students consistently get wrong. The mistake is always the same:
                    the student thinks the statement is reasonable, so they mark "True" when the passage never actually says it.
                </p>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-2"><strong>Test:</strong> Can you point to the exact sentence in the passage that proves the statement? If yes → True or False. If no → Not Given.</p>
                    <p class="mb-0">The statement does not need to be unreasonable to be Not Given. It just needs to be unaddressed by the text.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 4 — Writing Task 2</strong> (40 min timed, opinion or discussion essay)</p>
                    <p class="mb-0 text-muted small">Apply the Universal Checklist from Class 20 after writing. Submit to your tutor.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 21 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R6 Underlining and Keywords — Revision</h4>
                        <p class="text-muted mb-2">Full revision of the keyword underlining technique applied across all question types — from TFNG to Matching Headings to Sentence Completion.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Reading Strategy Drill — Mixed Question Types</h4>
                    <p class="mb-2">One Reading passage with 12 questions spanning six different question types. After completing, identify which question type caused the most difficulty and review that section's strategy.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 22</h4>
                    <p class="mb-0">Complete one full Reading section (all 3 parts, 40 questions) under timed conditions. For every incorrect answer, write: (1) which question type it was, (2) what your wrong answer was, (3) what the correct answer was, and (4) why you were wrong (distractor, misread question type, paraphrase missed, time pressure). This analysis is more valuable than the score.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class20.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 20</a>
                <a href="class22.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 22</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class20.php" class="btn btn-outline-light btn-sm">← Class 20</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 21 — Here</span>
                <a href="class22.php" class="btn btn-outline-light btn-sm">Class 22 →</a>
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
