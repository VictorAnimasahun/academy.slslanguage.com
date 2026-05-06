<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS Academic Masterclass – 2-Month Program | EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
</head>
<body>
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>

<main class="main-wrapper">
    <div class="course-card">

        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                <li class="breadcrumb-item active">IELTS Academic Masterclass</li>
            </ol>
        </nav>

        <h1 class="mb-3">
            <i class="bi bi-stars me-2" style="color:#ec4899;"></i>
            IELTS Academic Masterclass
        </h1>

        <p class="lead">
            The most comprehensive IELTS preparation program offered by Scholarly Language Services.
            Eight intensive weeks covering every skill, every question type, and every scoring strategy
            — designed for students who want not just a pass, but a high band score.
        </p>

        <div class="highlight-box">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h4 class="mb-2" style="color:var(--accent);">Program Overview</h4>
                    <p class="mb-0"><strong>Duration:</strong> 2 Months (8 Weeks) &nbsp;·&nbsp; <strong>Modules:</strong> 8 &nbsp;·&nbsp; <strong>Format:</strong> Self-Paced + Live Sessions</p>
                </div>
                <div>
                    <span class="badge-custom">📅 8 Weeks</span>
                    <span class="badge-custom">🎯 All 4 IELTS Skills</span>
                    <span class="badge-custom">📊 Band 7+ Focused</span>
                </div>
            </div>
        </div>

        <!-- What You Will Learn -->
        <div class="content-section">
            <h2>What You Will Master</h2>
            <p>
                This isn't a crash course — it's a masterclass. Over eight weeks you will develop
                deep, exam-ready competence in every component of IELTS Academic. By the end
                you won't just know what the test contains; you'll know exactly how to score in
                each section and why.
            </p>

            <div class="info-grid">
                <div class="info-card" style="border-color:#1e3a8a;">
                    <h4 style="color:#1e3a8a;"><i class="bi bi-headphones me-2"></i>Listening</h4>
                    <p class="mb-0">Master all four sections. Learn active prediction, note-taking, and the question type patterns IELTS repeats every exam cycle.</p>
                </div>
                <div class="info-card" style="border-color:#059669;">
                    <h4 style="color:#059669;"><i class="bi bi-book me-2"></i>Reading</h4>
                    <p class="mb-0">Academic passage strategies, True/False/Not Given logic, matching headings, and all 13 question types fully decoded.</p>
                </div>
                <div class="info-card" style="border-color:#ec4899;">
                    <h4 style="color:#ec4899;"><i class="bi bi-pencil me-2"></i>Writing</h4>
                    <p class="mb-0">Task 1: every chart and diagram type. Task 2: every essay type with model answers, PEEL structure, and band descriptor mastery.</p>
                </div>
                <div class="info-card" style="border-color:#f59e0b;">
                    <h4 style="color:#f59e0b;"><i class="bi bi-mic me-2"></i>Speaking</h4>
                    <p class="mb-0">All three parts decoded. Fluency, vocabulary, pronunciation, and cohesion strategies that examiners score on.</p>
                </div>
            </div>
        </div>

        <!-- 8-Week Curriculum -->
        <div class="content-section">
            <h2>8-Week Curriculum</h2>

            <div class="week-section">
                <div class="week-header">Week 1 — Module 1: Course Orientation &amp; IELTS Deep Dive</div>
                <ul class="module-list">
                    <li>Welcome, program structure &amp; study schedule</li>
                    <li>IELTS Academic vs. General Training — key differences</li>
                    <li>Band score calculation and what each band means</li>
                    <li>Examiner's mindset — how your paper is actually marked</li>
                    <li>Diagnostic self-assessment quiz</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 2 — Module 2: Listening Mastery</div>
                <ul class="module-list">
                    <li>Anatomy of the IELTS Listening paper (Sections 1–4)</li>
                    <li>Prediction strategies before the recording plays</li>
                    <li>All question types: form completion, MCQ, map, matching, table</li>
                    <li>Dealing with accents and speed</li>
                    <li>2 full timed listening practice tests</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 3 — Module 3: Academic Reading Mastery</div>
                <ul class="module-list">
                    <li>Speed-reading: skimming and scanning techniques</li>
                    <li>True/False/Not Given vs. Yes/No/Not Given — the critical difference</li>
                    <li>Matching headings, matching information, matching features</li>
                    <li>Summary, sentence, and note completion</li>
                    <li>3 full academic reading passages with full answer explanations</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 4 — Module 4: Academic Vocabulary &amp; Grammar</div>
                <ul class="module-list">
                    <li>High-frequency IELTS academic word list (AWL)</li>
                    <li>Collocations, synonyms, and paraphrasing for all four skills</li>
                    <li>Grammar: complex sentences, conditionals, passive voice</li>
                    <li>Cohesive devices and discourse markers</li>
                    <li>Vocabulary in context practice tasks</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 5 — Module 5: Writing Task 1 Mastery</div>
                <ul class="module-list">
                    <li>Task 1 assessment criteria (TA, CC, LR, GRA) — unpacked</li>
                    <li>Line graphs, bar charts, pie charts, tables — structure and language</li>
                    <li>Process diagrams and maps — step-by-step approach</li>
                    <li>Introductions, overviews, and body paragraphs</li>
                    <li>4 model Task 1 answers with band descriptor annotations</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 6 — Module 6: Writing Task 2 Mastery</div>
                <ul class="module-list">
                    <li>Task 2 assessment criteria — what separates Band 6 from Band 8</li>
                    <li>Opinion essays, discussion essays, problem-solution, advantage-disadvantage</li>
                    <li>PEEL paragraph method and idea development</li>
                    <li>Academic vocabulary and hedging language</li>
                    <li>5 model Task 2 essays with full examiner commentary</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 7 — Module 7: Speaking Mastery</div>
                <ul class="module-list">
                    <li>Part 1: extending short answers naturally</li>
                    <li>Part 2: cue card preparation (1-minute planning, 2-minute delivery)</li>
                    <li>Part 3: abstract discussion — expressing, justifying, comparing</li>
                    <li>Pronunciation, intonation, and self-correction techniques</li>
                    <li>Full mock speaking test with annotated model responses</li>
                </ul>
            </div>

            <div class="week-section">
                <div class="week-header">Week 8 — Module 8: Full Mock Tests &amp; Band Score Optimization</div>
                <ul class="module-list">
                    <li>Full timed mock test — Listening &amp; Reading</li>
                    <li>Full timed mock test — Writing (Task 1 + Task 2)</li>
                    <li>Full Speaking simulation</li>
                    <li>Error pattern analysis and personalized final tips</li>
                    <li>Test-day checklist and mental preparation</li>
                </ul>
            </div>

            <div class="highlight-box pink-highlight mt-4">
                <h4 style="color:#ec4899;margin-top:0;"><i class="bi bi-trophy me-2"></i>The Masterclass Guarantee</h4>
                <p class="mb-0">
                    Complete all 8 modules, submit your practice tasks, and attend the live sessions —
                    and you will walk into your IELTS exam knowing exactly what to expect and exactly
                    how to respond. Our students consistently achieve Band 7 and above.
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="module1.php" class="btn btn-success btn-lg">
                <i class="bi bi-play-circle me-2"></i>Start Week 1 — Module 1
            </a>
            <a href="../courses_catalogue.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>Back to Courses
            </a>
            <a href="../../learning_dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        </div>

    </div>
</main>

<aside class="advert-sidebar">
    <div class="course-card" style="background:linear-gradient(135deg,#ec4899 0%,#f472b6 100%);color:white;">
        <h6 class="mb-2">🎯 Quick Navigation</h6>
        <div class="d-grid gap-2">
            <a href="module1.php" class="btn btn-light btn-sm"><i class="bi bi-1-circle me-1"></i>Module 1</a>
            <a href="module2.php" class="btn btn-light btn-sm"><i class="bi bi-2-circle me-1"></i>Module 2</a>
            <a href="module3.php" class="btn btn-light btn-sm"><i class="bi bi-3-circle me-1"></i>Module 3</a>
            <a href="module4.php" class="btn btn-light btn-sm"><i class="bi bi-4-circle me-1"></i>Module 4</a>
            <a href="module5.php" class="btn btn-light btn-sm"><i class="bi bi-5-circle me-1"></i>Module 5</a>
            <a href="module6.php" class="btn btn-light btn-sm"><i class="bi bi-6-circle me-1"></i>Module 6</a>
            <a href="module7.php" class="btn btn-light btn-sm"><i class="bi bi-7-circle me-1"></i>Module 7</a>
            <a href="module8.php" class="btn btn-light btn-sm"><i class="bi bi-8-circle me-1"></i>Module 8</a>
        </div>
    </div>
    <h6 class="mb-3 text-muted mt-3"><i class="bi bi-megaphone me-2"></i>Sponsored</h6>
    <div class="ad-container">
        <div class="ad-placeholder">
            <i class="bi bi-badge-ad" style="font-size:1.5rem;opacity:0.3;"></i>
            <p class="mt-2 mb-0">Advertisement Space</p>
            <small>300x250</small>
        </div>
    </div>
    <div class="ad-container">
        <div class="ad-placeholder">
            <i class="bi bi-badge-ad" style="font-size:1.5rem;opacity:0.3;"></i>
            <p class="mt-2 mb-0">Advertisement Space</p>
            <small>300x250</small>
        </div>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');
const mobileOverlay = document.getElementById('mobileOverlay');
function toggleMenu() {
    sidebar.classList.toggle('active');
    mobileOverlay.classList.toggle('active');
    const icon = menuToggle.querySelector('i');
    icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
}
menuToggle.addEventListener('click', toggleMenu);
mobileOverlay.addEventListener('click', toggleMenu);
document.querySelectorAll('.sidebar .nav-link').forEach(link => {
    link.addEventListener('click', () => { if (window.innerWidth < 1200) toggleMenu(); });
});
</script>
</body>
</html>
