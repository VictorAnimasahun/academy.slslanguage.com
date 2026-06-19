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
<nav aria-label="breadcrumb" class="mb-3"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li><li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li><li class="breadcrumb-item active">Class 9</li></ol></nav>
<?php render_upgrade_prompt('advanced','Class 9: Reading — Matching Headings · Listening — Plan Labelling Parts 3 & 4'); ?>
<div class="mt-3"><a href="intro.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left-circle me-1"></i>Back to Class 1 (Free)</a></div>
</div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const m=document.getElementById('menuToggle'),s=document.querySelector('.sidebar'),o=document.getElementById('mobileOverlay');function t(){s.classList.toggle('active');o.classList.toggle('active');m.querySelector('i').className=s.classList.contains('active')?'bi bi-x-lg':'bi bi-list';}m.addEventListener('click',t);o.addEventListener('click',t);</script>
</body></html><?php exit(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Masterclass — Class 9: Reading — Matching Headings · Listening — Plan Labelling</title>
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
                <li class="breadcrumb-item active">Class 9 — Reading &amp; Listening</li>
            </ol></nav>

            <h1 class="mb-3">
                <i class="bi bi-book me-2" style="color:#6366f1;"></i>
                Class 9: Reading — Matching Headings &amp; Listening — Plan Labelling (Parts 3 &amp; 4)
            </h1>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="badge-custom">Month 2 — Skill Development</span>
                        <span class="badge-custom">Class 9 of 24</span>
                        <span class="badge-custom"><i class="bi bi-clock me-1"></i>90 min</span>
                    </div>
                </div>
            </div>

            <!-- Practice Test -->
            <div class="highlight-box" style="background:#eff6ff;border-color:#3b82f6;">
                <h4 style="color:#1d4ed8;margin-top:0;"><i class="bi bi-stopwatch me-2"></i>Practice Test — Reading Set 2</h4>
                <p class="mb-1"><strong>Practice Test Set 2 — Reading section</strong> | All 3 sections | 60 minutes timed</p>
                <p class="mb-0 text-muted small">Complete this under timed conditions before reviewing the teaching content below. Use today's strategies to guide your approach.</p>
            </div>

            <!-- Content Section 1 -->
            <div class="content-section">
                <h2><i class="bi bi-journals me-2"></i>Reading — Matching Paragraph Headings</h2>
                <p class="lead">The heading must reflect the <strong>main idea</strong> of the paragraph — not a detail, not an example, not a single fact mentioned in passing.</p>
                <p>
                    This is the most common error students make with this question type: selecting a heading because it contains a word that appears in the paragraph. IELTS deliberately constructs distractor headings around words lifted directly from the text. The word matches — but the heading only captures a minor point or supporting detail, not the central argument of the paragraph.
                </p>
                <h4 class="mt-4">Two Common Traps</h4>
                <ul class="custom-list">
                    <li><strong>Trap 1 — Word matching:</strong> A heading contains a word from the paragraph but captures only a minor point. The test-setter puts it there to catch students who scan for matching words rather than reading for meaning.</li>
                    <li><strong>Trap 2 — Cross-paragraph plausibility:</strong> A heading sounds entirely plausible but actually describes a different paragraph. This is especially dangerous when two consecutive paragraphs address related topics.</li>
                </ul>
                <h4 class="mt-4">The Strategy</h4>
                <ol class="custom-list">
                    <li><strong>Read the first and last sentence of each paragraph first.</strong> In academic and journalistic writing, the topic sentence is almost always the first sentence. The last sentence often restates or concludes the main idea. Between these two, you have the paragraph's main idea without reading every word.</li>
                    <li><strong>Underline the topic.</strong> In one or two words, what is this paragraph actually about? Write it in the margin.</li>
                    <li><strong>Match to headings — not the other way around.</strong> Students who read the headings first get anchored to them and start looking for evidence to confirm. Instead, know what the paragraph is about, then find the heading that fits.</li>
                    <li><strong>Eliminate as you go.</strong> Once a heading is used, cross it off your list. The list shrinks with each correct match, making later questions easier.</li>
                </ol>
                <div class="p-3 mt-3" style="background:#f8f9fa;border-left:4px solid #6366f1;border-radius:4px;">
                    <strong>Key principle:</strong> If you are unsure between two headings, ask yourself: "Does this heading describe what the <em>whole</em> paragraph is about, or just one part of it?" A heading that describes only one part is almost certainly wrong.
                </div>
            </div>

            <!-- Content Section 2 -->
            <div class="content-section">
                <h2><i class="bi bi-search me-2"></i>Reading — Matching Paragraph Information</h2>
                <p class="lead">This question type is different from heading matching. You are given a list of statements and asked to identify which paragraph contains that information.</p>
                <p>
                    The critical distinction: you are not looking for the main idea of the paragraph — you are looking for the paragraph that contains a specific piece of information. That information may be a supporting detail, an example, a statistic, or a claim made in passing. The statement in the question is almost always a paraphrase of what appears in the passage.
                </p>
                <h4 class="mt-4">How to Approach It</h4>
                <ol class="custom-list">
                    <li><strong>Read the statement carefully.</strong> Identify the key concept — the specific claim, fact, or idea you need to locate. Underline the most distinctive word or phrase in the statement.</li>
                    <li><strong>Scan all paragraphs for the key concept.</strong> Do not read from the beginning — scan for the conceptual area the statement relates to. You are looking for the semantic neighbourhood of the idea, not necessarily the exact words.</li>
                    <li><strong>Read carefully to confirm.</strong> Once you have found a likely paragraph, read the relevant sentences carefully to verify the statement matches. One word can change the meaning entirely — watch for negation ("not," "rarely," "failed to").</li>
                </ol>
                <p class="mt-3">
                    Unlike heading questions, paragraphs can be used more than once — the same paragraph may contain information that answers multiple questions. Do not assume you have finished with a paragraph once you have matched one statement to it.
                </p>
                <div class="p-3 mt-3" style="background:#f8f9fa;border-left:4px solid #6366f1;border-radius:4px;">
                    <strong>Paraphrase awareness:</strong> Build a habit of noting paraphrase patterns as you practise. "Scientists have demonstrated" becomes "research shows." "A growing number of people" becomes "an increasing proportion of the population." The passage and the question will never say the same thing in the same words.
                </div>
            </div>

            <!-- Content Section 3 -->
            <div class="content-section">
                <h2><i class="bi bi-headphones me-2"></i>Listening Parts 3 &amp; 4 — Plan Labelling</h2>
                <p class="lead">Parts 3 and 4 are the most academically demanding sections of the Listening test — and the most predictable in their structure once you know what to expect.</p>

                <h4 class="mt-3">Part 3 — Academic Discussion</h4>
                <p>
                    Part 3 features two or three speakers in an academic context: students discussing an assignment, a student and a tutor reviewing research, or a group planning a project. The conversation is longer and more complex than Parts 1 and 2. Plan labelling in Part 3 requires you to follow spatial directions on a floor plan, site map, or diagram while the speakers discuss it.
                </p>
                <ul class="custom-list">
                    <li><strong>Before listening:</strong> Study the plan carefully. Note fixed reference points — the entrance, any labelled areas, cardinal directions (North/South) if shown. Predict the type of location for each gap (a room name, a facility, a department).</li>
                    <li><strong>During listening:</strong> Speakers will use directional language — "on your left," "directly opposite," "next to the entrance," "at the far end." Track your position on the diagram as the speakers move through it.</li>
                    <li><strong>Watch for distractors:</strong> Speakers may mention a location and then correct themselves. Always write the final version of what is said.</li>
                </ul>

                <h4 class="mt-4">Part 4 — Academic Lecture</h4>
                <p>
                    Part 4 is a monologue — one speaker delivering an academic lecture. There are no conversational exchanges to help you track position. This is the most demanding section.
                </p>
                <p>The standard academic lecture structure:</p>
                <ol class="custom-list">
                    <li><strong>Introduction:</strong> The speaker states the topic and may outline the structure of the lecture.</li>
                    <li><strong>Three to four main points:</strong> Each main point is introduced with a signpost phrase.</li>
                    <li><strong>Conclusion:</strong> The speaker summarises and may point forward to the next topic or session.</li>
                </ol>
                <div class="p-3 mt-3" style="background:#f8f9fa;border-left:4px solid #6366f1;border-radius:4px;">
                    <strong>Signpost language — your map through the lecture:</strong><br>
                    <span class="badge bg-secondary me-1 mt-1">Firstly / To begin with</span>
                    <span class="badge bg-secondary me-1 mt-1">Moving on to</span>
                    <span class="badge bg-secondary me-1 mt-1">In contrast / On the other hand</span>
                    <span class="badge bg-secondary me-1 mt-1">Furthermore / In addition</span>
                    <span class="badge bg-secondary me-1 mt-1">To summarise / In conclusion</span>
                    <p class="mt-2 mb-0 small">When you hear a signpost, you know where you are in the lecture. If you miss a gap answer, hearing the next signpost tells you where the next question will be.</p>
                </div>
            </div>

            <!-- Videos -->
            <div class="content-section">
                <h2><i class="bi bi-camera-video me-2"></i>Class Videos</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>L4 Part 4 Listening Class 1</h4>
                        <p class="text-muted mb-2">Academic lecture structure, signpost language, and gap-fill strategies for Listening Part 4.</p>
                        <div class="p-3 text-center" style="background:#f1f5f9;border-radius:8px;">
                            <i class="bi bi-play-circle-fill" style="font-size:2rem;color:#6366f1;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>R3 Detailed Reading Class 1</h4>
                        <p class="text-muted mb-2">Heading matching technique — topic sentence analysis, elimination, and main idea identification.</p>
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
                    <p class="mb-1"><strong>Heading matching strategy drill</strong></p>
                    <p class="mb-0">8 headings, 6 paragraphs from a sample passage. Match each paragraph to the correct heading and explain your reasoning in one sentence — stating what the main idea of the paragraph is and why that heading fits better than the distractors.</p>
                </div>
                <p class="mt-2 mb-0 small text-muted">Quiz content coming soon — your tutor will provide the passage and heading list in class.</p>
            </div>

            <!-- Take-home -->
            <div class="highlight-box">
                <h4 style="margin-top:0;"><i class="bi bi-house me-2"></i>Take-Home Tasks</h4>
                <ul class="mb-0">
                    <li>Complete 2 map/plan labelling exercises from a practice paper under timed conditions.</li>
                    <li>Write a one-paragraph summary of each of the 3 reading passages from Practice Test Set 2 (approximately 50 words each). Focus on capturing the main idea of the whole passage — not individual paragraphs.</li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="class08.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>Class 8</a>
                <a href="class10.php" class="btn btn-primary btn-lg" style="background:#6366f1;border-color:#6366f1;"><i class="bi bi-play-circle me-2"></i>Class 10</a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);color:white;">
            <h6 class="mb-2">Navigation</h6>
            <div class="d-grid gap-1">
                <a href="class08.php" class="btn btn-outline-light btn-sm">← Class 8 (Mock 1)</a>
                <span class="btn btn-warning btn-sm disabled fw-bold text-dark">Class 9 — Here</span>
                <a href="class10.php" class="btn btn-outline-light btn-sm">Class 10 →</a>
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
