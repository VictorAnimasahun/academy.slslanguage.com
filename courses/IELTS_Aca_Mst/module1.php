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
    <title>Module 1 – Course Orientation &amp; IELTS Deep Dive | IELTS Academic Masterclass</title>
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
                <li class="breadcrumb-item"><a href="intro.php" class="text-decoration-none">IELTS Academic Masterclass</a></li>
                <li class="breadcrumb-item active">Module 1</li>
            </ol>
        </nav>

        <h1 class="mb-3">
            <i class="bi bi-mortarboard-fill me-2" style="color:#1e3a8a;"></i>
            Module 1 – Course Orientation &amp; IELTS Deep Dive
        </h1>
        <p class="lead">
            Week 1. Before we sharpen any skill, we build the map. This module gives you a complete
            understanding of what IELTS Academic is, how it is scored, and — most importantly —
            how your examiner thinks. That knowledge alone is worth a full band point.
        </p>

        <!-- SECTION 1 -->
        <div class="content-section">
            <h2>Welcome to the Masterclass</h2>
            <p>
                You've made an important decision — and not just to take the IELTS. You've decided to
                prepare seriously. That changes everything. Most candidates walk into the exam knowing
                what to expect. Masterclass students walk in knowing exactly how to respond, and why.
            </p>
            <p>
                Over the next eight weeks you will work through every component of IELTS Academic at
                depth. This first module is your foundation. We won't rush into question types yet —
                first, you need to understand the full picture.
            </p>

            <div class="highlight-box">
                <h4 style="color:var(--accent);">Your 8-Week Commitment</h4>
                <ul class="mb-0">
                    <li><strong>2–3 hours per week</strong> of module study</li>
                    <li><strong>30 minutes per day</strong> of English exposure (podcasts, articles, essays)</li>
                    <li><strong>Timed practice tests</strong> — at least one full test per fortnight</li>
                    <li>Honest self-assessment after every exercise</li>
                </ul>
            </div>
        </div>

        <!-- SECTION 2 -->
        <div class="content-section">
            <h2>IELTS Academic vs. General Training</h2>
            <p>
                Both versions test the same four skills — Listening, Reading, Writing, Speaking — but
                they serve different purposes and the Reading and Writing papers are completely different.
            </p>

            <div class="info-grid">
                <div class="info-card" style="border-color:#1e3a8a;">
                    <h4 style="color:#1e3a8a;"><i class="bi bi-mortarboard me-2"></i>Academic</h4>
                    <p>For university admission, professional registration (doctors, nurses, engineers), or skilled migration. Passages are taken from books, journals, and research publications. Writing Task 1 requires describing visual data.</p>
                    <p class="mb-0"><strong>This is the version you are preparing for.</strong></p>
                </div>
                <div class="info-card" style="border-color:#64748b;">
                    <h4 style="color:#64748b;"><i class="bi bi-briefcase me-2"></i>General Training</h4>
                    <p>For work visas, immigration, or secondary education. Reading passages come from advertisements, workplace notices, and everyday texts. Writing Task 1 requires writing a letter.</p>
                    <p class="mb-0"><em>Not our focus in this masterclass.</em></p>
                </div>
            </div>
        </div>

        <!-- SECTION 3 -->
        <div class="content-section">
            <h2>The Four Papers — Format &amp; Timing</h2>
            <p>
                IELTS tests four skills in a single day. Listening, Reading, and Writing are taken
                back-to-back. Speaking is typically on the same day or within a week.
            </p>

            <div class="info-grid">
                <div class="info-card" style="border-color:#1e3a8a;">
                    <h4 style="color:#1e3a8a;"><i class="bi bi-headphones me-2"></i>Listening — 40 min</h4>
                    <ul class="mb-0">
                        <li>4 sections, 40 questions</li>
                        <li>30 min recording + 10 min to transfer answers</li>
                        <li>Played once only</li>
                        <li>British, Australian, American, Canadian accents</li>
                    </ul>
                </div>
                <div class="info-card" style="border-color:#059669;">
                    <h4 style="color:#059669;"><i class="bi bi-book me-2"></i>Reading — 60 min</h4>
                    <ul class="mb-0">
                        <li>3 academic passages, 40 questions</li>
                        <li>No extra transfer time</li>
                        <li>Increasing complexity (Passage 3 is hardest)</li>
                        <li>Sourced from academic publications</li>
                    </ul>
                </div>
                <div class="info-card" style="border-color:#ec4899;">
                    <h4 style="color:#ec4899;"><i class="bi bi-pencil me-2"></i>Writing — 60 min</h4>
                    <ul class="mb-0">
                        <li>Task 1: 20 min, minimum 150 words</li>
                        <li>Task 2: 40 min, minimum 250 words</li>
                        <li>Task 2 is worth twice as many marks as Task 1</li>
                        <li>Must write in formal, academic English</li>
                    </ul>
                </div>
                <div class="info-card" style="border-color:#f59e0b;">
                    <h4 style="color:#f59e0b;"><i class="bi bi-mic me-2"></i>Speaking — 11–14 min</h4>
                    <ul class="mb-0">
                        <li>3 parts: introduction, long turn, discussion</li>
                        <li>Face-to-face with a certified examiner</li>
                        <li>Recorded for quality control</li>
                        <li>Same for Academic and General Training</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SECTION 4 -->
        <div class="content-section">
            <h2>Band Scores — How They Really Work</h2>
            <p>
                Each of the four papers is scored on a Band 1–9 scale. Your overall score is the
                average of all four, rounded to the nearest 0.5.
            </p>

            <h3>What Each Band Means</h3>
            <ul class="custom-list">
                <li><strong>Band 9 — Expert:</strong> Full command of English. Rare. Less than 1% of test takers.</li>
                <li><strong>Band 8 — Very Good:</strong> Occasional inaccuracies. Common in professional contexts.</li>
                <li><strong>Band 7 — Good:</strong> Handles complex language. Misunderstandings in unfamiliar situations. <strong>This is our primary target.</strong></li>
                <li><strong>Band 6.5 — Competent:</strong> Generally effective, noticeable errors. Accepted by most universities.</li>
                <li><strong>Band 6 — Modest:</strong> Frequent errors in complex situations. Minimum requirement for many programmes.</li>
                <li><strong>Band 5 and below:</strong> Partial command only. Not sufficient for most academic admission.</li>
            </ul>

            <h3>Common Requirements by Destination</h3>
            <div class="info-grid">
                <div class="info-card">
                    <h4>🇬🇧 UK Universities</h4>
                    <p class="mb-0">Undergraduate: 6.0–6.5<br>Postgraduate: 6.5–7.0<br>Medical/Law: 7.0–7.5</p>
                </div>
                <div class="info-card">
                    <h4>🇨🇦 Canada (Immigration)</h4>
                    <p class="mb-0">Express Entry: 6.0+<br>Federal Skilled Worker: 6.0 each band<br>Provincial Programs: 5.5–6.5</p>
                </div>
                <div class="info-card">
                    <h4>🇦🇺 Australia</h4>
                    <p class="mb-0">Skilled Visa: 6.0–7.0<br>Student Visa: 5.5–6.5<br>Nurses/Doctors: 7.0+</p>
                </div>
                <div class="info-card">
                    <h4>🇺🇸 USA Universities</h4>
                    <p class="mb-0">Graduate Programs: 6.5–7.0<br>MBA Programs: 7.0+<br>Some accept TOEFL instead</p>
                </div>
            </div>
        </div>

        <!-- SECTION 5 -->
        <div class="content-section">
            <h2>The Examiner's Mindset</h2>
            <p>
                This is the insight most candidates never get. Your examiner is not looking for
                perfection. They are looking for evidence of a specific skill level.
            </p>
            <p>
                In Writing and Speaking, four criteria are assessed with equal weight:
            </p>

            <h3>Writing Assessment Criteria</h3>
            <ul class="custom-list">
                <li><strong>Task Achievement / Task Response (25%):</strong> Did you actually answer the question? All parts of it? With relevant ideas and examples?</li>
                <li><strong>Coherence &amp; Cohesion (25%):</strong> Is your writing logically organised? Do ideas flow smoothly using linking devices?</li>
                <li><strong>Lexical Resource (25%):</strong> Do you use a wide range of vocabulary accurately? Can you paraphrase naturally?</li>
                <li><strong>Grammatical Range &amp; Accuracy (25%):</strong> Do you use a variety of sentence structures? With few errors?</li>
            </ul>

            <h3>Speaking Assessment Criteria</h3>
            <ul class="custom-list">
                <li><strong>Fluency &amp; Coherence (25%):</strong> Can you speak at length without long pauses? Do your ideas connect logically?</li>
                <li><strong>Lexical Resource (25%):</strong> Do you choose words precisely? Can you discuss topics using appropriate vocabulary?</li>
                <li><strong>Grammatical Range &amp; Accuracy (25%):</strong> Do you produce complex sentences naturally?</li>
                <li><strong>Pronunciation (25%):</strong> Can you be understood consistently? Note: accent does not affect the score.</li>
            </ul>

            <div class="highlight-box pink-highlight">
                <h4 style="color:#ec4899;margin-top:0;"><i class="bi bi-lightbulb me-2"></i>Key Insight</h4>
                <p class="mb-0">
                    A Band 7 student writes clearly structured paragraphs, uses a range of vocabulary
                    with only occasional errors, and demonstrates grammatical variety. You do not need
                    to be perfect — you need to be consistently competent and occasionally impressive.
                </p>
            </div>
        </div>

        <!-- SECTION 6 — Diagnostic -->
        <div class="content-section">
            <h2>Diagnostic Self-Assessment</h2>
            <p>
                Before you proceed, rate yourself honestly from 1 (very weak) to 5 (very strong) in each area.
                Keep this note — at the end of the 8 weeks, you will reassess and see how far you've come.
            </p>

            <div class="info-grid">
                <div class="info-card">
                    <h4><i class="bi bi-headphones me-2 text-primary"></i>Listening</h4>
                    <p class="mb-1">Do you catch details in fast speech?</p>
                    <p class="mb-1">Can you follow academic lectures?</p>
                    <p class="mb-0">Are you comfortable with non-British accents?</p>
                </div>
                <div class="info-card">
                    <h4><i class="bi bi-book me-2 text-success"></i>Reading</h4>
                    <p class="mb-1">Can you read 2,500 words in 20 minutes?</p>
                    <p class="mb-1">Do you find academic vocabulary challenging?</p>
                    <p class="mb-0">Do you often run out of time?</p>
                </div>
                <div class="info-card">
                    <h4><i class="bi bi-pencil me-2" style="color:#ec4899;"></i>Writing</h4>
                    <p class="mb-1">Can you write 250 words in 40 minutes?</p>
                    <p class="mb-1">Do you know how to describe a bar chart?</p>
                    <p class="mb-0">Can you write a discursive essay with clear structure?</p>
                </div>
                <div class="info-card">
                    <h4><i class="bi bi-mic me-2" style="color:#f59e0b;"></i>Speaking</h4>
                    <p class="mb-1">Can you speak for 2 minutes on a topic?</p>
                    <p class="mb-1">Do you use a variety of tenses naturally?</p>
                    <p class="mb-0">Do you hesitate frequently or repeat yourself?</p>
                </div>
            </div>

            <p class="mt-3">
                <strong>Write down your honest scores.</strong> Then move to Module 2. Your weakest area
                will need the most deliberate practice — but we will cover all four.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="intro.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>Course Overview
            </a>
            <a href="module2.php" class="btn btn-success btn-lg">
                <i class="bi bi-play-circle me-2"></i>Continue to Module 2 — Listening
            </a>
            <a href="../../learning_dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>
        </div>

    </div>
</main>

<aside class="advert-sidebar">
    <div class="course-card" style="background:linear-gradient(135deg,#1e3a8a 0%,#3b5998 100%);color:white;">
        <h6 class="mb-2">📚 Course Modules</h6>
        <div class="d-grid gap-2">
            <a href="module1.php" class="btn btn-light btn-sm active">Module 1 — Orientation</a>
            <a href="module2.php" class="btn btn-outline-light btn-sm">Module 2 — Listening</a>
            <a href="module3.php" class="btn btn-outline-light btn-sm">Module 3 — Reading</a>
            <a href="module4.php" class="btn btn-outline-light btn-sm">Module 4 — Vocabulary</a>
            <a href="module5.php" class="btn btn-outline-light btn-sm">Module 5 — Writing T1</a>
            <a href="module6.php" class="btn btn-outline-light btn-sm">Module 6 — Writing T2</a>
            <a href="module7.php" class="btn btn-outline-light btn-sm">Module 7 — Speaking</a>
            <a href="module8.php" class="btn btn-outline-light btn-sm">Module 8 — Mock Tests</a>
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
