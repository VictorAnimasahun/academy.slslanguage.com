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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 22</li></ol></nav>
<?php render_upgrade_prompt('fluent','Class 22: Speaking Mastery — Parts 1, 2 &amp; 3'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 22: Speaking Mastery</title>
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
                <li class="breadcrumb-item active">Class 22</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color:#16a34a;"></i>
                Class 22: Speaking Mastery — Parts 1, 2 &amp; 3 — Full Revision &amp; Performance Techniques
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 3 — Consolidation &amp; Mastery</span>
                <span class="badge-custom">Class 22 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#eff6ff;color:#1d4ed8;border-color:#3b82f6;"><i class="bi bi-clipboard-check me-1"></i>Practice Test Set 4 — Speaking</span>
            </div>

            <div class="content-section">
                <h2>Speaking — Four Marking Criteria (Final Revision)</h2>
                <p>Every Speaking response is assessed on the same four criteria, each worth 25%:</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Fluency &amp; Coherence</h4>
                        <p class="mb-0">Speaking at a natural pace without long hesitations. Ideas are connected logically. You can self-correct smoothly without losing the thread. Pauses over 3 seconds are penalised.</p>
                    </div>
                    <div class="info-card">
                        <h4>Lexical Resource</h4>
                        <p class="mb-0">Range of vocabulary used naturally and accurately. Using topic-specific words, collocations, and idiomatic expressions — without forcing them. Avoiding single-word repetition.</p>
                    </div>
                    <div class="info-card">
                        <h4>Grammatical Range &amp; Accuracy</h4>
                        <p class="mb-0">Using a variety of tenses and structures — not just present simple. Complex sentences with clauses and subordinators. Errors are minor and do not impede communication.</p>
                    </div>
                    <div class="info-card">
                        <h4>Pronunciation</h4>
                        <p class="mb-0">Clear enough to be understood by a non-native speaker. Stress, intonation, and word linking. An accent is not penalised — unintelligibility is. Do not try to fake a native accent.</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Part 1 — Maximising Your Score in 4–5 Minutes</h2>
                <p>
                    Part 1 feels easy — which is why many candidates underperform. The examiner is assessing
                    your natural fluency and vocabulary range, not just whether you can answer simple questions.
                </p>
                <ul class="custom-list">
                    <li><strong>Length:</strong> 2–3 sentences per answer. One sentence is too short. Five sentences is too long for Part 1.</li>
                    <li><strong>IDEA structure:</strong> Idea → Detail → Example or Alternative. Never just "Yes, I do." Always extend.</li>
                    <li><strong>Vary your openers:</strong> Do not start every answer with "I think." Try: "Personally," / "To be honest," / "I'd say that..." / "What I find interesting is..."</li>
                    <li><strong>Natural corrections:</strong> "I usually go... or actually, more like every other week." This sounds fluent, not hesitant.</li>
                </ul>
            </div>

            <div class="content-section">
                <h2>Part 2 — The 2-Minute Long Turn (Mastery)</h2>
                <p>
                    You have done Part 2 cue cards since Class 7. This is the performance check: can you speak
                    for 2 minutes without stopping, reading, or going off-topic?
                </p>
                <div class="week-section">
                    <div class="week-header">The 1-Minute Planning Technique (Revised)</div>
                    <ul class="module-list">
                        <li>Spend 15 seconds deciding your topic (real and specific beats vague and general).</li>
                        <li>Spend 30 seconds jotting 3–4 keywords per bullet point — not full sentences.</li>
                        <li>Spend 15 seconds thinking of one story or concrete detail that gives you 30–40 seconds of natural speech.</li>
                    </ul>
                </div>
                <div class="week-section">
                    <div class="week-header">Common Part 2 Errors</div>
                    <ul class="module-list">
                        <li>Reading your notes aloud — notes are prompts, not a script.</li>
                        <li>Only covering two of the four bullet points — cover all of them.</li>
                        <li>Stopping before 2 minutes — keep going, even if you have covered the main points. Add detail, feelings, comparisons.</li>
                        <li>Starting before the examiner says "Please speak now."</li>
                    </ul>
                </div>
            </div>

            <div class="content-section">
                <h2>Part 3 — Abstract Discussion (Mastery)</h2>
                <p>
                    Part 3 is where the highest bands are won or lost. The examiner is looking for academic-level
                    spoken discourse — not just answers, but arguments.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Buying Thinking Time</h4>
                        <p class="mb-0">"That's an interesting question..." / "I've never really thought about it that way, but I'd say..." / "It's difficult to generalise, but I think..." These are natural, not suspicious.</p>
                    </div>
                    <div class="info-card">
                        <h4>Speculating About the Future</h4>
                        <p class="mb-0">"I imagine that..." / "It seems likely that..." / "I would expect that within the next decade..." / "There's a possibility that..." These show range and hedging.</p>
                    </div>
                    <div class="info-card">
                        <h4>Discussing Society</h4>
                        <p class="mb-0">"In general, people tend to..." / "Many younger people nowadays..." / "Historically, the trend has been..." / "There's been a noticeable shift towards..." These signal academic register.</p>
                    </div>
                    <div class="info-card">
                        <h4>Disagreeing Politely</h4>
                        <p class="mb-0">"That's a valid point, though I'd argue that..." / "I can see why people think that, but in my view..." / "While that may be true in some cases, I'd suggest that..."</p>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-check me-2"></i>Practice Test — Timed Section</h2>
                <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                    <p class="mb-1"><strong>Practice Test Set 4 — Speaking section</strong> (full simulated test, Parts 1–3)</p>
                    <p class="mb-0 text-muted small">Record yourself. Listen back and score yourself on each of the four criteria: fluency, vocabulary, grammar, pronunciation. Note specific moments where you paused too long or repeated vocabulary.</p>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 22 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>S4 Part 2 Speaking — Revision</h4>
                        <p class="text-muted mb-2">Revisiting the 1-minute planning technique — with full demonstration of a 2-minute Part 2 response at band 7 level.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Speaking — Holidays Vocabulary (Revision)</h4>
                        <p class="text-muted mb-2">Travel and holiday vocabulary bank — revisited for Part 2 and Part 3 topic integration before the final mock.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#16a34a;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Speaking Performance Self-Assessment</h4>
                    <p class="mb-2">Answer 6 Part 3 questions on camera or by recording. Play them back and tick off: (1) Did you give a view? (2) Did you give a reason? (3) Did you give an example? (4) Did you hedge at least once? (5) Was there any pause over 3 seconds? (6) Did you repeat any word more than twice?</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Class 23</h4>
                    <p class="mb-0">Record a full speaking simulation: Part 1 (4 min), Part 2 (2 min cue card, 1 min prep), Part 3 (4 min). Listen to the recording and count: (1) total number of pauses over 2 seconds, (2) number of times you repeated a vocabulary item you used earlier in the same answer, (3) number of Part 3 answers where you gave a reason AND an example. Report these numbers to your tutor in Class 23.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class21.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 21</a>
                <a href="class23.php" class="btn btn-primary btn-lg"><i class="bi bi-play-circle me-2"></i>Next: Class 23</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#16a34a 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class21.php" class="btn btn-outline-light btn-sm">← Class 21</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 22 — Here</span>
                <a href="class23.php" class="btn btn-outline-light btn-sm">Class 23 →</a>
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
