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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 17</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 17: Speaking Part 3 — Opinions & Abstract Topics · Reading — Summary/Note/Table Completion'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 17: Speaking Part 3 — Opinions &amp; Abstract Topics</title>
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
                <li class="breadcrumb-item active">Class 17 — Speaking &amp; Reading</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color:#16a34a;"></i>
                Class 17: Speaking Part 3 — Opinions &amp; Abstract Topics &middot; Reading — Summary/Note/Table Completion
            </h1>

            <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge-custom">Month 3 — Mastery</span>
                        <span class="badge-custom">Class 17 of 24</span>
                        <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                    </div>
                    <span class="badge fw-bold px-3 py-2 fs-6" style="background:#16a34a;color:white;">
                        <i class="bi bi-mic me-1"></i>Speaking &amp; Reading
                    </span>
                </div>
            </div>

            <!-- Practice Test -->
            <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                <h4 style="color:#1d4ed8;margin-top:0;"><i class="bi bi-stopwatch me-2"></i>Practice Test — Speaking Set 3</h4>
                <p class="mb-1"><strong>Practice Test Set 3 — Speaking section</strong> | Parts 1, 2 &amp; 3 — full timed</p>
                <p class="mb-0 text-muted small">Complete the full timed Speaking simulation before reviewing the teaching content below. Record yourself for self-assessment.</p>
            </div>

            <!-- Content Section 1 -->
            <div class="content-section">
                <h2><i class="bi bi-mic me-2"></i>Speaking Part 3 Advanced — Abstract and Global Topics</h2>
                <p class="lead">Month 3 Part 3 work moves beyond opinion-giving to <strong>speculating</strong>, <strong>comparing</strong>, and <strong>hypothesising</strong>. These are the harder question types the examiner introduces at the advanced stage.</p>
                <p>Common advanced Part 3 question types:</p>
                <ul class="custom-list">
                    <li>"How do you think X will change in the future?"</li>
                    <li>"Compare the situation in your country with other countries."</li>
                    <li>"If governments invested more in X, what would happen?"</li>
                </ul>

                <h4 class="mt-4">Key Language for Each Function</h4>
                <div class="info-grid">
                    <div class="info-card">
                        <h5><i class="bi bi-cloud-question me-2" style="color:#16a34a;"></i>Speculation</h5>
                        <ul class="mb-0 small">
                            <li>"It's possible that..."</li>
                            <li>"There's a chance that..."</li>
                            <li>"I imagine that..."</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h5><i class="bi bi-arrow-left-right me-2" style="color:#16a34a;"></i>Comparison</h5>
                        <ul class="mb-0 small">
                            <li>"In contrast to..."</li>
                            <li>"Whereas in [country]..."</li>
                            <li>"Unlike..."</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h5><i class="bi bi-diagram-3 me-2" style="color:#16a34a;"></i>Hypothetical</h5>
                        <ul class="mb-0 small">
                            <li>"If that were the case..."</li>
                            <li>"Were that to happen..."</li>
                        </ul>
                    </div>
                </div>

                <div class="p-3 mt-3" style="background:#f8f9fa;border-left:4px solid #16a34a;border-radius:4px;">
                    <strong>Key principle:</strong> Using these structures does not just improve your Lexical Resource score — it directly raises your Grammatical Range score, because hypothetical and speculative structures require complex grammar (conditionals, modal verbs, inversion). Aim to use at least one from each category in a single Part 3 response.
                </div>
            </div>

            <!-- Content Section 2 -->
            <div class="content-section">
                <h2><i class="bi bi-book me-2"></i>Reading — Summary, Note, and Table Completion</h2>
                <p class="lead">These three completion tasks look similar but require slightly different approaches. All three require you to find exact words from the passage.</p>

                <h4 class="mt-3">Summary Completion</h4>
                <p>
                    Summary completion presents a short paragraph that summarises part of the passage — with gaps. The summary will NOT follow the exact order of the passage, so you must scan across the whole text.
                </p>
                <div class="p-3 mb-3" style="background:#f8f9fa;border-left:4px solid #16a34a;border-radius:4px;">
                    <strong>Key rule:</strong> The answers are usually <em>adjacent</em> in the passage — once you find one answer, the next is typically in the same paragraph. Use this to your advantage: after finding your first answer, read the surrounding sentences carefully before moving to the next gap.
                </div>

                <h4 class="mt-3">Note Completion</h4>
                <p>
                    Note completion (similar in format to Listening notes) follows the passage order. This makes it more predictable than summary completion — work through the passage in sequence. Notes are structured with headings and bullet points, so identify which section of the passage each heading refers to before answering.
                </p>

                <h4 class="mt-3">Table Completion</h4>
                <p>
                    Table completion requires you to understand the structure of the table before locating answers. Read the column headings first to understand what category of information each column holds. Then identify the row category (usually the leftmost column). With both the row and column understood, you know exactly what type of information to locate — and this narrows your scan significantly.
                </p>
                <ul class="custom-list">
                    <li>Always respect the word limit — "NO MORE THAN TWO WORDS AND/OR A NUMBER" means exactly that.</li>
                    <li>Do not rephrase. Use the exact words from the passage.</li>
                    <li>Check that grammatically your answer fits the gap (noun where a noun is expected, etc.).</li>
                </ul>
            </div>

            <!-- Videos -->
            <div class="content-section">
                <h2><i class="bi bi-camera-video me-2"></i>Class Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2" style="color:#16a34a;"></i>R4 Paraphrasing Class 2</h4>
                        <p class="text-muted mb-2">Advanced paraphrase recognition for summary and completion tasks — vocabulary substitution and structural paraphrase.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;">
                            <i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                <h4 style="color:#15803d;margin-top:0;"><i class="bi bi-patch-question me-2"></i>Class Quiz</h4>
                <div class="p-3" style="background:#dcfce7;border-radius:8px;">
                    <p class="mb-1"><strong>Abstract topic opinion framework drill</strong></p>
                    <p class="mb-0">5 Part 3 questions requiring speculating, comparing, or hypothesising. Structure a full 3-part answer for each: view + reason + qualification. Topics include: education funding, healthcare systems, technology and work, environmental policy, and globalisation.</p>
                </div>
                <p class="mt-2 mb-0 small text-muted">Quiz content provided by your tutor in class.</p>
            </div>

            <!-- Take-home -->
            <div class="highlight-box">
                <h4 style="margin-top:0;"><i class="bi bi-house me-2"></i>Take-Home Tasks</h4>
                <ul class="mb-0">
                    <li>Summarise 3 reading passages in 50 words each (no looking at the passage after you start). This builds the same skill tested in summary completion — condensing main ideas without re-reading.</li>
                    <li>Record 5 Part 3 responses on abstract topics: education, healthcare, technology, environment, and globalisation. Each response should use at least one speculating phrase, one comparison, or one hypothetical structure.</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="class16.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 16</a>
                <a href="class18.php" class="btn btn-primary btn-lg" style="background:#16a34a;border-color:#16a34a;"><i class="bi bi-play-circle me-2"></i>Class 18</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#059669 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class16.php" class="btn btn-outline-light btn-sm">&#8592; Class 16 (Mock 2)</a>
                <span class="btn btn-warning btn-sm disabled fw-bold text-dark">Class 17 — Here</span>
                <a href="class18.php" class="btn btn-outline-light btn-sm">Class 18 &#8594;</a>
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
