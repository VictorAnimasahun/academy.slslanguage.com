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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 19</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 19: Writing Task 2 — Balanced Conclusions &amp; Discussion Essays'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 19: Writing Task 2 Conclusions &amp; Discussion Essays</title>
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
                <li class="breadcrumb-item active">Class 19</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-pencil-square me-2" style="color:#16a34a;"></i>
                Class 19: Writing Task 2 — Balanced Conclusions &nbsp;·&nbsp; Discussion Essays in Depth
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 19 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 4 — Reading</span>
            </div>

            <div class="content-section">
                <h2>Writing Task 2 — The Conclusion</h2>
                <p>
                    A conclusion is <strong>not a summary of your body paragraphs</strong>. It is a final
                    statement of your position that closes the argument without introducing new ideas.
                    Two to three sentences is the right length — anything longer wastes time better spent on body paragraphs.
                </p>
                <h3>What a Strong Conclusion Does</h3>
                <ul class="custom-list">
                    <li>Restates your thesis in different words — paraphrase, do not copy your introduction.</li>
                    <li>Briefly signals which argument you found most compelling (for opinion and discussion essays).</li>
                    <li>Optionally adds a future outlook or recommendation — one sentence only.</li>
                    <li>Never introduces a brand-new argument or piece of evidence.</li>
                </ul>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Opinion Essay Conclusion</h4>
                        <p class="mb-0">"In conclusion, while there are arguments on both sides, I firmly believe that [position]. The evidence surrounding [key reason] makes this the most convincing case."</p>
                    </div>
                    <div class="info-card">
                        <h4>Discussion Essay Conclusion</h4>
                        <p class="mb-0">"To conclude, both perspectives have merit. However, I would argue that [your view] outweighs the alternative, given [brief reason]."</p>
                    </div>
                    <div class="info-card">
                        <h4>Problem &amp; Solution Conclusion</h4>
                        <p class="mb-0">"In summary, [problem] poses a serious challenge, but [solution 1] and [solution 2] offer realistic paths forward. Coordinated action at both the individual and governmental level will be essential."</p>
                    </div>
                    <div class="info-card">
                        <h4>What to Avoid</h4>
                        <p class="mb-0">Do not start with "In conclusion, I think that..." followed by something you have not argued. Do not use "As I mentioned above" — this is weak. Do not write more than 3 sentences.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Discussion Essays — Structure &amp; Strategy</h2>
                <p>
                    Discussion essays ask you to "discuss both views" before giving your own opinion.
                    The critical discipline: <strong>keep your personal opinion out of the body paragraphs</strong>.
                    Save it for the conclusion only, unless the question explicitly says "and give your opinion."
                </p>
                <div class="week-section">
                    <div class="week-header">Introduction (2 sentences)</div>
                    <ul class="module-list">
                        <li>Background sentence — paraphrase the topic.</li>
                        <li>Thesis — "This essay will discuss both perspectives before presenting a personal view." Do NOT state your opinion here.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Body Paragraph 1 — View A</div>
                    <ul class="module-list">
                        <li>Use PEEL. Present View A fairly — even if you disagree with it.</li>
                        <li>Use third-person distancing: "Those who support this view argue that..." / "Proponents of this position believe..."</li>
                        <li>Do not add "but I disagree" at the end of this paragraph.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Body Paragraph 2 — View B</div>
                    <ul class="module-list">
                        <li>Use PEEL. Present View B with equal depth.</li>
                        <li>"On the other hand, others maintain that..." / "An alternative perspective holds that..."</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Conclusion — Your Opinion</div>
                    <ul class="module-list">
                        <li>Now state your position. "Having considered both perspectives, I believe that [View A/B] is more convincing because..."</li>
                        <li>This is the only place your opinion should appear explicitly.</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <h2>Lexical Resource — Upgrading Your Vocabulary</h2>
                <p>
                    Lexical Resource is one of the four marking criteria. Band 7 requires "sufficient range to allow
                    some flexibility and precision" with "less common items." This does not mean using the longest
                    words possible — it means choosing the most precise word for your meaning.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Topic-Specific Vocabulary</h4>
                        <p class="mb-0">For each essay topic, prepare 6–8 relevant terms. Environment: carbon footprint, emissions, renewable energy, biodiversity, sustainability. Technology: automation, digital literacy, surveillance, artificial intelligence.</p>
                    </div>
                    <div class="info-card">
                        <h4>Academic Collocations</h4>
                        <p class="mb-0">Learn word partnerships, not just individual words: "pose a threat," "address an issue," "implement a policy," "alleviate poverty," "have a detrimental effect on."</p>
                    </div>
                    <div class="info-card">
                        <h4>Avoiding Repetition</h4>
                        <p class="mb-0">If you use a key noun three times, vary it on the third use. "Government" → "authorities," "policymakers," "the state." This shows lexical range without forcing unusual vocabulary.</p>
                    </div>
                    <div class="info-card">
                        <h4>Spelling</h4>
                        <p class="mb-0">One or two spelling errors will not drastically affect your band score, but systematic errors (affect/effect, their/there, its/it's) will. Know your personal spelling weaknesses and fix them.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 4 — Reading section</strong> (all 3 sections, 60 min timed)</p>
                    <p class="mb-0 text-muted small">After marking, focus on any sentence completion or summary completion questions — review whether you copied answers exactly from the text.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 19 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>WT2 Introductions Class 2</h4>
                        <p class="text-muted mb-2">Advanced introduction techniques for discussion and double-question essays — how to signal essay structure without sounding formulaic.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Discussion Essay Structure + Conclusion Writing</h4>
                    <p class="mb-2">Part A: Given 3 sample conclusions, identify which errors each one contains (opinion introduced early, new idea added, too long, copied from introduction). Part B: Write a 2–3 sentence conclusion for a given discussion essay prompt.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 20</h4>
                    <p class="mb-0">Write a full discussion essay (250+ words) in 40 minutes. Self-check after: (1) Is your opinion absent from the introduction and body paragraphs? (2) Does the conclusion state your view for the first time? (3) Are both views presented with equal weight? (4) Can you underline one "less common" vocabulary item per body paragraph? Submit to your tutor for feedback.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class18.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 18</a>
                <a href="class20.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 20</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class18.php" class="btn btn-outline-light btn-sm">← Class 18</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 19 — Here</span>
                <a href="class20.php" class="btn btn-outline-light btn-sm">Class 20 →</a>
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
