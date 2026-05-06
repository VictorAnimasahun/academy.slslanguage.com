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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 14</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 14: Reading — Sentence Completion · Writing Task 2 — Effective Introductions'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 14: Sentence Completion &amp; Writing Task 2 Introductions</title>
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
                <li class="breadcrumb-item active">Class 14</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-pencil me-2" style="color:#6366f1;"></i>
                Class 14: Reading — Sentence Completion &nbsp;·&nbsp; Writing Task 2 — Effective Introductions
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 2 — Skill Development</span>
                <span class="badge-custom">Class 14 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 3 — Writing Task 1</span>
            </div>

            <div class="content-section">
                <h2>Reading — Sentence Completion</h2>
                <p>
                    Sentence completion questions give you the beginning of a sentence and ask you to complete it
                    using words taken directly from the passage. Unlike short-answer questions, the completed sentence
                    must be <strong>grammatically correct</strong> as well as factually accurate.
                </p>
                <h3>The Three Rules</h3>
                <ul class="custom-list">
                    <li><strong>Use the exact words from the passage.</strong> Do not paraphrase — copy the words as they appear. If you write a synonym, you lose the mark.</li>
                    <li><strong>Respect the word limit.</strong> "No more than three words" means one, two, or three words — but never four. Articles (a, an, the) and prepositions count as words.</li>
                    <li><strong>Check grammar.</strong> The completed sentence must read naturally. If your answer breaks the grammar, it is likely wrong — re-read the passage section.</li>
                </ul>
                <h3>Locating the Answer</h3>
                <p>
                    The questions follow the order of the passage. Use the words before and after the gap to pinpoint
                    the exact paragraph. Look for the paraphrase of those surrounding words in the text — the answer
                    is almost always in the same sentence or the one immediately following.
                </p>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Common trap:</strong> The passage contains the correct phrase, but students paraphrase it in their own words. Always lift the answer verbatim from the text.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Writing Task 2 — Understanding the Five Question Types</h2>
                <p>
                    Before writing a single word, identify the exact question type. Each type requires a different
                    structure and position. Writing the wrong structure for the question type is one of the most
                    costly errors on Task 2.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>1. Opinion / Agree or Disagree</h4>
                        <p class="mb-0">"To what extent do you agree or disagree?" → Give <strong>your opinion</strong> clearly. Agree fully, disagree fully, or partially agree — but be consistent throughout.</p>
                    </div>
                    <div class="info-card">
                        <h4>2. Discussion</h4>
                        <p class="mb-0">"Discuss both views and give your opinion." → Present both sides fairly, then state your view in the conclusion. Do not give your opinion in the introduction body.</p>
                    </div>
                    <div class="info-card">
                        <h4>3. Problem &amp; Solution</h4>
                        <p class="mb-0">"What are the causes/problems and what solutions can be suggested?" → Dedicate one body paragraph to causes/problems and one to solutions.</p>
                    </div>
                    <div class="info-card">
                        <h4>4. Advantages &amp; Disadvantages</h4>
                        <p class="mb-0">"Discuss the advantages and disadvantages." → Balanced treatment of both sides. May or may not ask for your opinion — read carefully.</p>
                    </div>
                    <div class="info-card">
                        <h4>5. Double Question</h4>
                        <p class="mb-0">"Why is this the case? What can be done about it?" → Answer both questions explicitly. Each body paragraph answers one question.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Writing Task 2 — The Two-Sentence Introduction</h2>
                <p>
                    A Task 2 introduction should be exactly <strong>two sentences</strong>: a background sentence
                    that paraphrases the topic, and a thesis sentence that signals your position and/or essay structure.
                    Never use more than 40–50 words for the introduction.
                </p>
                <div class="week-section">
                    <div class="week-header">Sentence 1 — Background (Paraphrase the topic)</div>
                    <ul class="module-list">
                        <li>Restate the topic in your own words. Do not copy the question directly.</li>
                        <li>Change the sentence structure, not just individual words.</li>
                        <li>Example: "It is argued that governments should ban the use of private cars in city centres." → "Some people believe that city authorities ought to prohibit privately owned vehicles from urban areas."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Sentence 2 — Thesis (Your position or essay map)</div>
                    <ul class="module-list">
                        <li>For opinion essays: state your view directly. "This essay will fully agree with this position..."</li>
                        <li>For discussion essays: preview both sides. "This essay will examine both perspectives before reaching a conclusion."</li>
                        <li>For problem/solution: "This essay will identify the main causes and suggest practical solutions."</li>
                    </ul>
                </div>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Never start with "Nowadays," "In today's world," or "In the modern era."</strong> These openings are overused and signal a formulaic response to the examiner. Begin with the specific topic immediately.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Writing Task 2 — Four Marking Criteria</h2>
                <p>Every Task 2 response is marked on four equal criteria, each worth 25% of the Task 2 score:</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Task Achievement</h4>
                        <p class="mb-0">Did you fully answer all parts of the question? Is your position clear? Is the minimum word count met (250 words)?</p>
                    </div>
                    <div class="info-card">
                        <h4>Coherence &amp; Cohesion</h4>
                        <p class="mb-0">Is the essay logically organised? Do paragraphs link smoothly? Are cohesive devices (however, therefore, in addition) used correctly?</p>
                    </div>
                    <div class="info-card">
                        <h4>Lexical Resource</h4>
                        <p class="mb-0">Is there a range of vocabulary? Are words used accurately? Are topic-specific terms included? Are spelling errors minimal?</p>
                    </div>
                    <div class="info-card">
                        <h4>Grammatical Range &amp; Accuracy</h4>
                        <p class="mb-0">Is there a mix of simple and complex sentences? Are verb tenses accurate? Are articles and prepositions used correctly?</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 3 — Writing Task 1</strong> (semi-formal or formal letter, 20 min timed)</p>
                    <p class="mb-0 text-muted small">Set a timer. Check register, structure, and word count after writing. Do not exceed 20 minutes — Task 2 timing discipline starts here.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 14 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>WT2 Marking Criteria Class 1</h4>
                        <p class="text-muted mb-2">A full breakdown of all four IELTS Writing Task 2 marking criteria with band descriptor examples.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Introduction Paraphrase Drill</h4>
                    <p class="mb-2">5 Task 2 question prompts. For each: (1) identify the question type, (2) write a two-sentence introduction — background paraphrase + thesis. No introduction should exceed 50 words.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 15</h4>
                    <p class="mb-0">Write Task 2 introductions (two sentences each) for three different question types: (1) an opinion/agree-disagree prompt, (2) a discussion prompt, (3) a problem-and-solution prompt. For each, state the question type at the top before writing. Self-check: is the background paraphrased (not copied)? Is your thesis clear? Is the total under 50 words?</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class13.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 13</a>
                <a href="class15.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 15</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class13.php" class="btn btn-outline-light btn-sm">← Class 13</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 14 — Here</span>
                <a href="class15.php" class="btn btn-outline-light btn-sm">Class 15 →</a>
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
