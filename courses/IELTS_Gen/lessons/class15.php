<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';
if (!isset($_SESSION['user_id'])) { header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course"); exit(); }
if (!can_access('advanced')) { ?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Upgrade Required</title><meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="../../../assets/css/courses.css" rel="stylesheet"><?php include INCLUDES_PATH.'/navbar_styles.php'; ?></head><body>
<?php include INCLUDES_PATH.'/mobile_header.php'; ?><div class="mobile-overlay" id="mobileOverlay"></div><?php include INCLUDES_PATH.'/navbar.php'; ?>
<main class="main-wrapper"><div class="course-card">
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 15</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 15: Listening — Distractors &amp; Synonyms · Writing — Cohesive Devices'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 15: Listening Distractors &amp; Cohesive Devices</title>
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
                <li class="breadcrumb-item active">Class 15</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-headphones me-2" style="color:#6366f1;"></i>
                Class 15: Listening — Distractors &amp; Synonyms &nbsp;·&nbsp; Writing — Cohesive Devices in Letters &amp; Essays
            </h1>
            <div class="highlight-box">
                <span class="badge-custom">Month 2 — Skill Development</span>
                <span class="badge-custom">Class 15 of 24</span>
                <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                <span class="badge-custom" style="background:#fef9c3;color:#854d0e;border-color:#eab308;"><i class="bi bi-arrow-repeat me-1"></i>Revision &amp; Consolidation — No Practice Test</span>
            </div>

            <div class="content-section">
                <h2>Listening — Distractors: The Core IELTS Technique</h2>
                <p>
                    A distractor is information the speaker mentions that sounds like the answer — but isn't.
                    IELTS uses distractors in every section. Understanding <em>how</em> they work is the single
                    most effective way to stop losing marks you should be getting.
                </p>
                <h3>The Three Distractor Types</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>1. The Correction</h4>
                        <p class="mb-0">Speaker says one thing, then corrects it. "The meeting is on Tuesday — sorry, I mean Wednesday." Students who write quickly mark Tuesday. Always wait for confirmation.</p>
                    </div>
                    <div class="info-card">
                        <h4>2. The Rejection</h4>
                        <p class="mb-0">Speaker mentions an option and then explicitly rejects it. "We could use the main hall, but that's being renovated." Students who write "main hall" lose the mark.</p>
                    </div>
                    <div class="info-card">
                        <h4>3. The Near-Miss</h4>
                        <p class="mb-0">A detail is very close to the answer but subtly different — a different number, a different name, a qualifier that changes the meaning. Always listen to the full clause before writing.</p>
                    </div>
                </div>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Rule:</strong> Never write your answer until the speaker has moved clearly on to the next point. If you hear what sounds like the answer, keep listening for 3–5 seconds before committing.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Listening — Synonym Recognition at Speed</h2>
                <p>
                    IELTS Listening almost never uses the exact same words as the question. Building synonym
                    recognition — the ability to instantly map a word in the question to a paraphrase in the
                    audio — is one of the core skills that separates band 7 from band 8 listeners.
                </p>
                <h3>High-Frequency Synonym Groups</h3>
                <ul class="custom-list">
                    <li><strong>Increase:</strong> rise, grow, climb, surge, expand, go up, improve, escalate</li>
                    <li><strong>Decrease:</strong> fall, drop, decline, reduce, shrink, go down, cut, lower</li>
                    <li><strong>Important:</strong> significant, crucial, vital, essential, key, major, critical</li>
                    <li><strong>Problem:</strong> issue, challenge, difficulty, concern, obstacle, barrier, drawback</li>
                    <li><strong>Plan:</strong> proposal, scheme, initiative, project, programme, strategy</li>
                    <li><strong>Show/Indicate:</strong> demonstrate, reveal, suggest, highlight, illustrate, point to</li>
                    <li><strong>People:</strong> residents, inhabitants, locals, community, population, citizens</li>
                </ul>
                <p>
                    After every Listening practice session, compare the question wording against what the speaker
                    said. Add each synonym pair to your personal paraphrase bank.
                </p>
            </div>

            <div class="content-section">
                <h2>Writing — Cohesive Devices: The Right Tool for the Right Job</h2>
                <p>
                    Cohesive devices link ideas within and between sentences. Using them correctly signals
                    organisational control to the examiner. Using them incorrectly — or overusing "however" and
                    "furthermore" — actually reduces your Coherence &amp; Cohesion score.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Adding Information</h4>
                        <ul class="mb-0">
                            <li>Furthermore / Moreover / In addition</li>
                            <li>Also / As well as / Not only... but also</li>
                            <li>What is more / Besides</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Contrasting</h4>
                        <ul class="mb-0">
                            <li>However / Nevertheless / Nonetheless</li>
                            <li>Although / Even though / While / Whereas</li>
                            <li>On the other hand / In contrast / Despite</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Giving Reasons &amp; Results</h4>
                        <ul class="mb-0">
                            <li>Because / Since / As / Due to / Owing to</li>
                            <li>Therefore / Thus / As a result / Consequently</li>
                            <li>This means that / This leads to / This results in</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Exemplifying</h4>
                        <ul class="mb-0">
                            <li>For example / For instance / To illustrate</li>
                            <li>Such as / Including / Particularly / Especially</li>
                            <li>A clear example of this is...</li>
                        </ul>
                    </div>
                </div>
                <div class="highlight-box" style="background:#fef9c3;border-color:#eab308;">
                    <p class="mb-0"><strong>Common error:</strong> Starting every sentence with "However," or "Furthermore," — the examiner marks this down as mechanical overuse. Vary your cohesive devices and also use pronoun reference, synonyms, and sentence structure to create cohesion.</p>
                </div>
            </div>

            <div class="content-section">
                <h2>Cohesive Devices in Letters vs Essays</h2>
                <p>
                    The same cohesive devices appear in both Task 1 letters and Task 2 essays, but their usage differs by register.
                </p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Formal &amp; Semi-Formal Letters</h4>
                        <ul class="mb-0">
                            <li>"I am writing to bring to your attention..."</li>
                            <li>"Furthermore, I would like to highlight..."</li>
                            <li>"I look forward to hearing from you."</li>
                            <li>Use formal connectors; avoid "Also" at the start of a sentence in formal letters.</li>
                        </ul>
                    </div>
                    <div class="info-card">
                        <h4>Informal Letters</h4>
                        <ul class="mb-0">
                            <li>"Anyway, I wanted to tell you..."</li>
                            <li>"So, that's why I think..."</li>
                            <li>"Oh, and another thing..."</li>
                            <li>Informal cohesion sounds conversational — avoid "Furthermore" and "Nevertheless."</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Month 2 — Mid-Point Skills Audit</h2>
                <p>You are halfway through Month 2. Before Mock Test 2 (Class 16), take 10 minutes to self-assess:</p>
                <ul class="custom-list">
                    <li><strong>Listening:</strong> Are you spotting distractors? Are you finishing all 40 questions in time?</li>
                    <li><strong>Reading:</strong> Are you using keyword underlining? Can you distinguish TFNG from YNNG reliably?</li>
                    <li><strong>Writing Task 1:</strong> Can you write a fully structured letter in 20 minutes? Is your register consistent?</li>
                    <li><strong>Writing Task 2:</strong> Can you identify the question type in under 30 seconds? Is your introduction two clear sentences?</li>
                    <li><strong>Speaking:</strong> Are your Part 1 answers 2–3 sentences with the IDEA structure? Can you speak for 2 minutes in Part 2 without stopping?</li>
                </ul>
                <p>Note any weak areas — Mock Test 2 will reveal them under timed conditions tomorrow.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 15 Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>General Reading Test Overview</h4>
                        <p class="text-muted mb-2">A complete walkthrough of the IELTS General Reading test — sections, question types, timing, and common mistakes.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;"><i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i><p class="mt-1 mb-0 small text-muted">Video coming soon</p></div>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2"></i>Class Quiz</h2>
                <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                    <h4 style="color:#15803d;margin-top:0;">Distractor Identification + Cohesive Device Selection</h4>
                    <p class="mb-2">Part A: 6 short audio transcripts — identify whether the underlined word is the correct answer or a distractor, and explain why. Part B: 8 gap-fill sentences — choose the most appropriate cohesive device from a box.</p>
                    <div class="p-3 text-center" style="background:#dcfce7;border-radius:8px;"><i class="bi bi-hourglass-split" style="font-size:1.5rem;color:#16a34a;"></i><p class="mt-1 mb-0 small" style="color:#15803d;">Quiz questions coming soon</p></div>
                </div>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);margin-top:0;">Complete Before Mock Test 2 (Class 16)</h4>
                    <p class="mb-0">Listen to one full IELTS Listening section (Parts 1–4) and flag every point where a distractor appeared — write what the distractor was and what the correct answer was. Then write one Task 2 body paragraph of 80–100 words using at least four different cohesive devices (not all "furthermore/however"). Check that none start two consecutive sentences the same way.</p>
                </div>
            </div>

            <div class="action-buttons">
                <a href="class14.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 14</a>
                <a href="class16.php" class="btn btn-warning btn-lg"><i class="bi bi-journal-richtext me-2"></i>Next: Mock Test 2 (Class 16)</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>
    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#0b77ff 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class14.php" class="btn btn-outline-light btn-sm">← Class 14</a>
                <span class="btn btn-light btn-sm disabled fw-bold">Class 15 — Here</span>
                <a href="class16.php" class="btn btn-warning btn-sm text-dark">Mock Test 2 (Class 16) →</a>
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
