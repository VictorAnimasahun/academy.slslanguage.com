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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 10</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 10: Speaking Part 2 — Long Turn · Writing Task 1 — Informal Letters'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 10: Speaking Part 2 — Long Turn · Writing Task 1 — Informal Letters</title>
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
                <li class="breadcrumb-item active">Class 10 — Speaking &amp; Writing</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-mic me-2" style="color:#6366f1;"></i>
                Class 10: Speaking Part 2 — Long Turn &amp; Writing Task 1 — Informal Letters
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge-custom">Month 2 — Skill Development</span>
                        <span class="badge-custom">Class 10 of 24</span>
                        <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                    </div>
                </div>
            </div>

            <!-- Practice Test -->
            <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                <h4 style="color:#1d4ed8;margin-top:0;"><i class="bi bi-stopwatch me-2"></i>Practice Test — Writing Task 1 Set 2</h4>
                <p class="mb-1"><strong>Practice Test Set 2 — Writing Task 1</strong> | Informal letter | 20 minutes timed</p>
                <p class="mb-0 text-muted small">Complete this under timed conditions before reviewing the teaching content below. Apply the informal register principles from today's lesson.</p>
            </div>

            <!-- Content Section 1 -->
            <div class="content-section">
                <h2><i class="bi bi-mic me-2"></i>Speaking Part 2 — Fluency Drills for the Long Turn</h2>
                <p class="lead">The biggest fluency killer in Part 2 is running out of things to say. Students who finish in under 90 seconds almost always lose marks in Fluency &amp; Coherence — and often in Lexical Resource too, because they haven't had the opportunity to demonstrate enough vocabulary.</p>
                <p>
                    The solution is not to memorise answers — it is to have a reliable content structure that generates material naturally. When you know what to say next, you don't pause. Pauses are usually a content problem, not a language problem.
                </p>

                <h4 class="mt-4">The STARE Framework</h4>
                <p>Use this five-part structure for every Part 2 cue card. It gives you a natural story arc that sustains 1.5–2 minutes without running dry.</p>
                <div class="info-grid">
                    <div class="info-card">
                        <h4 style="color:#6366f1;"><strong>S</strong> — Situation</h4>
                        <p class="mb-0">Set the scene. Where were you? Who was involved? What was the context? This is your opening — establish the setting in 2–3 sentences before anything else.</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;"><strong>T</strong> — Time</h4>
                        <p class="mb-0">When did this happen? How long did it last? How often does/did it occur? Time references ground your story and demonstrate tense control naturally.</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;"><strong>A</strong> — Action</h4>
                        <p class="mb-0">What happened? What did you do? Describe the events or activities in sequence. This is the core of your answer — give 3–4 specific details rather than 1 vague one.</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;"><strong>R</strong> — Reaction</h4>
                        <p class="mb-0">How did you feel? What was your emotional or personal response? Feelings extend naturally and give you an opportunity to use sophisticated vocabulary (thrilled, apprehensive, overwhelmed, nostalgic).</p>
                    </div>
                    <div class="info-card">
                        <h4 style="color:#6366f1;"><strong>E</strong> — Effect</h4>
                        <p class="mb-0">What did you learn? How did it change you or your perspective? What impact did it have? This is your conclusion — and if you are running short on time, land here cleanly.</p>
                    </div>
                </div>
                <div class="p-3 mt-3" style="background:#f8f9fa;border-left:4px solid #6366f1;border-radius:4px;">
                    <strong>How to use STARE:</strong> During your 1-minute planning time, jot down one word or phrase for each letter. You now have 5 content blocks. Each block takes 15–25 seconds to speak. That is 75–125 seconds of structured, coherent content — well within the required 2 minutes.
                </div>
            </div>

            <!-- Content Section 2 -->
            <div class="content-section">
                <h2><i class="bi bi-pause-circle me-2"></i>Speaking Part 2 — Avoiding Long Pauses</h2>
                <p class="lead">Even with a strong content structure, pauses happen. The key is knowing which pauses are acceptable and which ones damage your Fluency &amp; Coherence score.</p>

                <h4 class="mt-3">Acceptable Fillers (Used Sparingly)</h4>
                <ul class="custom-list">
                    <li><em>"That's actually a really interesting topic to talk about..."</em> — buys you 2 seconds while signalling engagement.</li>
                    <li><em>"Let me think about that for a second..."</em> — acceptable once. Using it repeatedly signals difficulty, not thoughtfulness.</li>
                    <li><em>"What I find particularly interesting about this is..."</em> — extends by re-focusing, not by stalling.</li>
                </ul>

                <h4 class="mt-4">Natural Self-Correction</h4>
                <p>
                    Students sometimes lose fluency by stopping completely when they make a mistake. Natural English speakers self-correct without fully stopping. Model this pattern:
                </p>
                <ul class="custom-list">
                    <li><em>"I went there with my... I mean, it was actually my sister who organised it..."</em></li>
                    <li><em>"It was... what's the word... it felt overwhelming, actually."</em></li>
                    <li><em>"I suppose I felt quite... relieved is probably the best word for it."</em></li>
                </ul>
                <p>These sound natural — because native speakers use them. They do not signal language failure; they signal thoughtful, real-time language production.</p>

                <h4 class="mt-4">If You Finish Early</h4>
                <p>
                    If you reach the end of your content before the examiner says "stop," do not fall silent. Extend using the <strong>Reaction</strong> and <strong>Effect</strong> sections of STARE — they are the most expandable. Ask yourself:
                </p>
                <ul class="custom-list">
                    <li>What specifically made you feel that way?</li>
                    <li>Did your feelings change during the experience?</li>
                    <li>Is there anything you would do differently?</li>
                    <li>Has this experience influenced anything since?</li>
                </ul>
                <p>Reflection and feelings naturally extend any answer without requiring new factual information.</p>
            </div>

            <!-- Content Section 3 -->
            <div class="content-section">
                <h2><i class="bi bi-envelope me-2"></i>Writing Task 1 — Informal Letters</h2>
                <p class="lead">An informal letter is the register most students find most natural to write — but it still has strict IELTS requirements that students frequently overlook in the rush of feeling comfortable.</p>

                <h4 class="mt-3">The Three Non-Negotiable Requirements</h4>
                <ul class="custom-list">
                    <li><strong>Address all three bullet points</strong> from the task prompt. Even in informal letters, the examiner is checking task achievement. A warm, well-written letter that misses a bullet point fails on Task Achievement.</li>
                    <li><strong>Write at least 150 words.</strong> Fewer than 150 words is an automatic band reduction regardless of quality.</li>
                    <li><strong>Maintain a consistently informal register throughout.</strong> This is where students most commonly fail — they begin informally and then slip into formal or semi-formal vocabulary mid-letter.</li>
                </ul>

                <h4 class="mt-4">Key Features of Informal Register</h4>
                <div class="info-grid">
                    <div class="info-card">
                        <h4>Contractions</h4>
                        <p class="mb-0">Use contractions freely — they are natural in informal writing. <em>I'm, it's, you'll, they're, we've, I'd, you've.</em> Avoiding contractions makes informal writing feel stiff and unnatural.</p>
                    </div>
                    <div class="info-card">
                        <h4>First Names</h4>
                        <p class="mb-0">Address the recipient by first name: <em>Dear Sarah, Dear James.</em> Never <em>Dear Ms Smith</em> or <em>Dear Sir/Madam</em> — these are formal registers.</p>
                    </div>
                    <div class="info-card">
                        <h4>Conversational Openings</h4>
                        <p class="mb-0"><em>"Just a quick note to let you know...", "I thought I'd drop you a line about...", "I hope you're doing well — it feels like ages since we caught up!"</em></p>
                    </div>
                    <div class="info-card">
                        <h4>Sign-offs</h4>
                        <p class="mb-0">Informal sign-offs: <em>Best wishes, Take care, Lots of love, Speak soon, Warm regards.</em> Never <em>Yours faithfully</em> or <em>Yours sincerely</em> — these are formal.</p>
                    </div>
                </div>

                <h4 class="mt-4">The Register Slip — Most Common Error</h4>
                <p>
                    Students frequently open with perfect informal language and then slip into formal vocabulary as they address the more task-specific bullet points. Watch for these register slips:
                </p>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="p-3" style="background:#fef2f2;border-radius:8px;border:1px solid #fca5a5;">
                            <strong class="text-danger">Too formal for informal letter:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>"I am writing to inform you that..."</li>
                                <li>"I would be grateful if you could..."</li>
                                <li>"Please do not hesitate to contact me."</li>
                                <li>"I look forward to hearing from you."</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f0fdf4;border-radius:8px;border:1px solid #86efac;">
                            <strong class="text-success">Natural informal alternatives:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>"I just wanted to let you know..."</li>
                                <li>"It'd be great if you could..."</li>
                                <li>"Feel free to give me a call!"</li>
                                <li>"Can't wait to hear back from you!"</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Videos -->
            <div class="content-section">
                <h2><i class="bi bi-camera-video me-2"></i>Class Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>S4 Part 2 Speaking Class 2</h4>
                        <p class="text-muted mb-2">Extended STARE framework drills with model answers and fluency techniques for the long turn.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;">
                            <i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Band 9 Part 2 FC1 LR1-5</h4>
                        <p class="text-muted mb-2">Annotated Band 9 Part 2 responses with Fluency &amp; Coherence and Lexical Resource commentary.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;">
                            <i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz -->
            <div class="highlight-box" style="background:#f0fdf4;border-color:#16a34a;">
                <h4 style="color:#15803d;margin-top:0;"><i class="bi bi-patch-question me-2"></i>Class Quiz</h4>
                <div class="p-3" style="background:#dcfce7;border-radius:8px;">
                    <p class="mb-1"><strong>Long turn coherence self-assessment</strong></p>
                    <p class="mb-0">Record a 2-minute Part 2 response on a given cue card, then rate yourself on the 4 Speaking criteria: Fluency &amp; Coherence, Lexical Resource, Grammatical Range &amp; Accuracy, Pronunciation (score each 1–9). Write one specific thing you would improve in each criterion.</p>
                </div>
                <p class="mt-2 mb-0 small text-muted">Your tutor will provide the cue card in class and give feedback on your self-assessment accuracy.</p>
            </div>

            <!-- Take-home -->
            <div class="highlight-box">
                <h4 style="margin-top:0;"><i class="bi bi-house me-2"></i>Take-Home Tasks</h4>
                <ul class="mb-0">
                    <li>Record a full Part 2 response (2 minutes, no stopping) on the topic: <strong>"Describe a person you admire."</strong> Use the STARE framework. Listen back and note any long pauses or register inconsistencies.</li>
                    <li>Write an informal letter (150+ words) to a relative telling them about a recent experience. Check: consistent register throughout, all contractions in place, conversational opening and sign-off.</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="class09.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 9</a>
                <a href="class11.php" class="btn btn-primary btn-lg" style="background:#6366f1;border-color:#6366f1;"><i class="bi bi-play-circle me-2"></i>Class 11</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class09.php" class="btn btn-outline-light btn-sm">← Class 9</a>
                <span class="btn btn-warning btn-sm disabled fw-bold text-dark">Class 10 — Here</span>
                <a href="class11.php" class="btn btn-outline-light btn-sm">Class 11 →</a>
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
