<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General 3-Month Masterclass — Class 1: Course Orientation</title>
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

            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">IELTS General 3-Month Masterclass</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mortarboard-fill me-2" style="color: #0b77ff;"></i>
                Class 1: Course Orientation &amp; IELTS General Overview
            </h1>

            <p class="lead">
                Welcome to the IELTS General 3-Month Masterclass. Today we cover how the programme works,
                what IELTS General tests across all four skills, and how to get the most from every class
                over the next 12 weeks.
            </p>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h4 class="mb-2" style="color: var(--accent);">Programme at a Glance</h4>
                        <p class="mb-0">
                            <strong>Duration:</strong> 12 Weeks &nbsp;|&nbsp;
                            <strong>Classes:</strong> 24 Sessions &nbsp;|&nbsp;
                            <strong>Schedule:</strong> 2 per Week &nbsp;|&nbsp;
                            <strong>Skills:</strong> All 4 IELTS Skills
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge-custom">24 Classes</span>
                        <span class="badge-custom">90 min each</span>
                        <span class="badge-custom">3 Mock Exams</span>
                        <span class="badge-custom">4 Practice Test Sets</span>
                    </div>
                </div>
            </div>

            <!-- IELTS General vs Academic -->
            <div class="content-section">
                <h2>IELTS General Training vs IELTS Academic</h2>
                <p>
                    Both versions share the same Listening and Speaking papers — the strategies you learn here
                    apply equally to both. The key differences are in Reading and Writing:
                </p>

                <div class="info-grid">
                    <div class="info-card" style="border-color: #0b77ff;">
                        <h4 style="color: #0b77ff;"><i class="bi bi-person-check me-2"></i>IELTS General Training</h4>
                        <p class="mb-1"><strong>Who takes it:</strong> Migration, secondary education, work experience</p>
                        <p class="mb-1"><strong>Reading:</strong> Everyday notices, advertisements, workplace documents, plus a longer general-interest passage</p>
                        <p class="mb-1"><strong>Writing Task 1:</strong> A letter — formal, semi-formal, or informal</p>
                        <p class="mb-0"><strong>Writing Task 2:</strong> An essay (same as Academic)</p>
                    </div>
                    <div class="info-card" style="border-color: #6366f1;">
                        <h4 style="color: #6366f1;"><i class="bi bi-mortarboard me-2"></i>IELTS Academic</h4>
                        <p class="mb-1"><strong>Who takes it:</strong> University admissions, professional registration</p>
                        <p class="mb-1"><strong>Reading:</strong> Three long academic passages with more complex vocabulary</p>
                        <p class="mb-1"><strong>Writing Task 1:</strong> Describe a graph, chart, map, or process diagram</p>
                        <p class="mb-0"><strong>Writing Task 2:</strong> An essay</p>
                    </div>
                </div>
            </div>

            <!-- Test Format -->
            <div class="content-section">
                <h2>The Four Skills — Test Format</h2>

                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-headphones me-2" style="color:#1e3a8a;"></i>Listening</h4>
                        <p class="mb-1"><strong>Duration:</strong> 30 min + 10 min answer transfer</p>
                        <p class="mb-1"><strong>Questions:</strong> 40 across 4 parts</p>
                        <p class="mb-0"><strong>Parts:</strong> Increasing difficulty — Part 1 (everyday dialogue) through Part 4 (academic lecture)</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-book me-2" style="color:#16a34a;"></i>Reading</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Questions:</strong> 40 across 3 sections</p>
                        <p class="mb-0"><strong>Sections:</strong> Short everyday texts, workplace documents, one longer general-interest passage</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-pencil me-2" style="color:#ea580c;"></i>Writing</h4>
                        <p class="mb-1"><strong>Duration:</strong> 60 minutes</p>
                        <p class="mb-1"><strong>Task 1 (20 min):</strong> Letter, minimum 150 words</p>
                        <p class="mb-0"><strong>Task 2 (40 min):</strong> Essay, minimum 250 words — worth twice Task 1</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-mic me-2" style="color:#9333ea;"></i>Speaking</h4>
                        <p class="mb-1"><strong>Duration:</strong> 11–14 minutes (face-to-face with examiner)</p>
                        <p class="mb-1"><strong>Part 1:</strong> Familiar questions — home, work, hobbies (4–5 min)</p>
                        <p class="mb-1"><strong>Part 2:</strong> Long turn on a cue card (3–4 min, 1 min prep)</p>
                        <p class="mb-0"><strong>Part 3:</strong> Abstract discussion on the Part 2 topic (4–5 min)</p>
                    </div>
                </div>
            </div>

            <!-- Band Scores -->
            <div class="content-section">
                <h2>How Band Scores Work</h2>
                <p>
                    Each skill is scored from 1 to 9. Your overall band score is the average of all four,
                    rounded to the nearest 0.5.
                </p>
                <ul class="custom-list">
                    <li><strong>Band 5.0–5.5:</strong> Modest user — basic communication, frequent inaccuracies</li>
                    <li><strong>Band 6.0–6.5:</strong> Competent user — generally effective, some inaccuracies</li>
                    <li><strong>Band 7.0–7.5:</strong> Good user — handles complex language well, occasional errors</li>
                    <li><strong>Band 8.0+:</strong> Very good / Expert user — fully operational command</li>
                </ul>
                <p>
                    Common requirements: <strong>5.5–6.0</strong> for skilled migration pathways,
                    <strong>6.0–7.0</strong> for occupational registration, <strong>7.0+</strong> for
                    competitive applications and professional licensing.
                </p>
            </div>

            <!-- How the Programme Works -->
            <div class="content-section">
                <h2>How This Programme Works</h2>

                <h3>Class Structure</h3>
                <ul class="custom-list">
                    <li><strong>24 classes</strong> over 12 weeks — twice per week</li>
                    <li><strong>Regular classes:</strong> 90 minutes — two complementary skills per session</li>
                    <li><strong>Mock test classes (8, 16, 24):</strong> 3 hours — full timed exam under real conditions</li>
                </ul>

                <h3>What You Receive Each Class</h3>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-play-circle me-2"></i>Lesson Videos</h4>
                        <p class="mb-0">Curated video lessons directly matched to that session's skill topics. Watch alongside the live class or as a review.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-question-circle me-2"></i>Class Quiz</h4>
                        <p class="mb-0">A short consolidation quiz — typically 10 questions — at the end of each class to lock in the strategies covered.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h4>
                        <p class="mb-0">One practical task to complete between sessions. Reinforces that class's content and builds consistent study habits.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-clipboard-check me-2"></i>Practice Test Sections</h4>
                        <p class="mb-0">16 of the 24 classes include a timed practice test section — 4 complete sets spread across the programme (4 sets × 4 sections = 16 total).</p>
                    </div>
                </div>

                <h3 class="mt-4">Three Full Mock Exams</h3>
                <p>
                    At the end of each month (Classes 8, 16, and 24), you sit a complete timed mock exam
                    covering all four skills under real exam conditions. Written feedback and band score
                    estimates are provided after each mock — along with a targeted improvement plan for
                    the following month.
                </p>
            </div>

            <!-- 24-Class Programme Schedule -->
            <div class="content-section">
                <h2>24-Class Programme Schedule</h2>

                <h3 class="mb-3" style="color: #0b77ff;">
                    <i class="bi bi-calendar3 me-2"></i>Month 1 — Foundations
                </h3>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-primary">
                        <tr><th width="40">#</th><th>Class</th><th width="90">Duration</th></tr>
                    </thead>
                    <tbody>
                        <tr class="table-light">
                            <td>1</td>
                            <td>Course Orientation &amp; IELTS General Overview <span class="badge bg-success ms-1">Free</span></td>
                            <td>90 min</td>
                        </tr>
                        <tr><td>2</td><td>Diagnostic Mini Mock &amp; Band Descriptor Review</td><td>90 min</td></tr>
                        <tr><td>3</td><td>Listening: Form Completion &amp; Gap-fill &nbsp;·&nbsp; Speaking: Introduction &amp; Fluency</td><td>90 min</td></tr>
                        <tr><td>4</td><td>Reading: Skimming &amp; Scanning &nbsp;·&nbsp; Writing Task 1: Vocabulary &amp; Registers</td><td>90 min</td></tr>
                        <tr><td>5</td><td>Listening: Multiple Choice &nbsp;·&nbsp; Speaking Part 1: Common Topics</td><td>90 min</td></tr>
                        <tr><td>6</td><td>Reading: True/False/Not Given &nbsp;·&nbsp; Writing Task 1: Formal Letters</td><td>90 min</td></tr>
                        <tr><td>7</td><td>Listening: Map Listening &nbsp;·&nbsp; Speaking Part 2: Topic Development</td><td>90 min</td></tr>
                        <tr class="table-warning fw-bold">
                            <td>8</td>
                            <td><i class="bi bi-journal-richtext me-1"></i>MOCK TEST 1 — Full Timed Exam (End of Month 1)</td>
                            <td>3 hrs</td>
                        </tr>
                    </tbody>
                </table>

                <h3 class="mb-3" style="color: #6366f1;">
                    <i class="bi bi-calendar3 me-2"></i>Month 2 — Skill Development
                </h3>
                <table class="table table-bordered table-sm mb-4">
                    <thead style="background-color: #6366f1; color: white;">
                        <tr><th width="40">#</th><th>Class</th><th width="90">Duration</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>9</td><td>Reading: Matching Headings &nbsp;·&nbsp; Listening: Plan Labelling Parts 3 &amp; 4</td><td>90 min</td></tr>
                        <tr><td>10</td><td>Speaking Part 2: Long Turn &nbsp;·&nbsp; Writing Task 1: Informal Letters</td><td>90 min</td></tr>
                        <tr><td>11</td><td>Reading: Multiple Choice &nbsp;·&nbsp; Writing Task 1: Semi-Formal Letters</td><td>90 min</td></tr>
                        <tr><td>12</td><td>Listening: Note Completion, Table &amp; Flowchart</td><td>90 min</td></tr>
                        <tr><td>13</td><td>Speaking Part 3: A Complete Guide &nbsp;·&nbsp; Critical Thinking &amp; Extended Responses</td><td>90 min</td></tr>
                        <tr><td>14</td><td>Reading: Sentence Completion &nbsp;·&nbsp; Writing Task 2: Effective Introduction</td><td>90 min</td></tr>
                        <tr><td>15</td><td>Listening: Distractors &amp; Synonyms &nbsp;·&nbsp; Cohesive Devices in Letters</td><td>90 min</td></tr>
                        <tr class="table-warning fw-bold">
                            <td>16</td>
                            <td><i class="bi bi-journal-richtext me-1"></i>MOCK TEST 2 — Full Timed Exam (End of Month 2)</td>
                            <td>3 hrs</td>
                        </tr>
                    </tbody>
                </table>

                <h3 class="mb-3" style="color: #16a34a;">
                    <i class="bi bi-calendar3 me-2"></i>Month 3 — Mastery
                </h3>
                <table class="table table-bordered table-sm mb-4">
                    <thead class="table-success">
                        <tr><th width="40">#</th><th>Class</th><th width="90">Duration</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>17</td><td>Speaking Part 3: Opinions &amp; Abstract Topics &nbsp;·&nbsp; Reading: Summary/Note/Table Completion</td><td>90 min</td></tr>
                        <tr><td>18</td><td>Writing Task 2: Body Paragraphs &nbsp;·&nbsp; Opinion Essays</td><td>90 min</td></tr>
                        <tr><td>19</td><td>Writing Task 2: Balanced Conclusions &nbsp;·&nbsp; Discussion Essays</td><td>90 min</td></tr>
                        <tr><td>20</td><td>Writing Task 2 Masterclass — All Five Essay Types</td><td>90 min</td></tr>
                        <tr><td>21</td><td>Reading Mastery — All Question Types Rapid Review</td><td>90 min</td></tr>
                        <tr><td>22</td><td>Speaking Mastery — Parts 1, 2 &amp; 3 End-to-End</td><td>90 min</td></tr>
                        <tr><td>23</td><td>Final Preparation — Listening Masterclass &amp; Test-Day Strategy</td><td>90 min</td></tr>
                        <tr class="table-warning fw-bold">
                            <td>24</td>
                            <td><i class="bi bi-journal-richtext me-1"></i>MOCK TEST 3 — Final Full Exam Simulation (End of Month 3)</td>
                            <td>3 hrs</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Class 1 Videos -->
            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 1 Videos</h2>
                <p>Three orientation videos to watch as part of today's session:</p>

                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Alessandra — Welcome &amp; Introduction</h4>
                        <p class="text-muted mb-2">Programme introduction from your lead tutor — what to expect over 12 weeks.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Seda — How to Use the Platform</h4>
                        <p class="text-muted mb-2">Walkthrough of the course structure, quizzes, take-home exercises, and how to navigate your classes.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Speaking Test — Three Candidates</h4>
                        <p class="text-muted mb-2">Watch three real speaking test extracts and learn exactly what the examiner is assessing.</p>
                        <div class="p-3 text-center" style="background: #f1f5f9; border-radius: 8px;">
                            <i class="bi bi-play-circle-fill" style="font-size: 2rem; color: #0b77ff;"></i>
                            <p class="mt-1 mb-0 small text-muted">Video coming soon</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Take-Home -->
            <div class="content-section">
                <h2><i class="bi bi-house-heart me-2"></i>Take-Home Exercise</h2>
                <div class="highlight-box">
                    <h4 style="color: var(--accent); margin-top: 0;">Self-Assessment Grid</h4>
                    <p>
                        Rate your current ability in each of the four IELTS skills on a scale of 1 to 5.
                        Be honest — this is your personal baseline, not a test:
                    </p>
                    <ul class="mb-3">
                        <li><strong>1 — Beginner:</strong> I struggle to understand or produce this skill reliably</li>
                        <li><strong>2 — Elementary:</strong> I can manage basic tasks but make frequent errors</li>
                        <li><strong>3 — Intermediate:</strong> I can perform this skill but not consistently</li>
                        <li><strong>4 — Upper-Intermediate:</strong> I perform well but have identifiable weak areas</li>
                        <li><strong>5 — Advanced:</strong> I am confident and accurate in this skill</li>
                    </ul>
                    <p class="mb-0">
                        Rate all four skills: <strong>Listening, Reading, Writing, Speaking</strong>.
                        Bring your grid to Class 2 — we will use your self-ratings to shape your Month 1 focus.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="../../courses_catalogue.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Courses
                </a>
                <a href="lesson.php?n=2" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Next: Class 2
                </a>
                <a href="../../../learning_dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </div>

        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background: linear-gradient(135deg, #0b77ff 0%, #6366f1 100%); color: white;">
            <h6 class="mb-3">Course Navigation</h6>
            <div class="d-grid gap-1">
                <span class="btn btn-light btn-sm disabled fw-bold">Class 1 — You are here</span>
                <a href="lesson.php?n=2" class="btn btn-outline-light btn-sm">Class 2</a>
                <a href="lesson.php?n=3" class="btn btn-outline-light btn-sm">Class 3</a>
                <a href="lesson.php?n=4" class="btn btn-outline-light btn-sm">Class 4</a>
                <a href="lesson.php?n=5" class="btn btn-outline-light btn-sm">Class 5</a>
                <a href="lesson.php?n=6" class="btn btn-outline-light btn-sm">Class 6</a>
                <a href="lesson.php?n=7" class="btn btn-outline-light btn-sm">Class 7</a>
                <a href="lesson.php?n=8" class="btn btn-outline-light btn-sm">Mock Test 1 (Class 8)</a>
            </div>
            <hr style="border-color: rgba(255,255,255,0.3);">
            <a href="../../courses_catalogue.php" class="btn btn-outline-light btn-sm w-100">All Courses</a>
        </div>

        <h6 class="mb-3 text-muted mt-3">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>
        <div class="ad-container">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>
        <div class="ad-container">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar    = document.querySelector('.sidebar');
        const overlay    = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            icon.className = sidebar.classList.contains('active') ? 'bi bi-x-lg' : 'bi bi-list';
        }

        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200) toggleMenu();
            });
        });
    </script>
</body>
</html>
