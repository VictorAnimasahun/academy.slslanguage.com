<?php
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once __DIR__ . '/course_context.php';

// Shared by the IELTS General 1-, 2- and 3-month courses: name, length, mocks and timetable come from
// the database for the course the student is in (founder, 2026-10-19), never typed in for one course.
$schedule = course_schedule($db, $ctxCourse['id']);
$mockClasses = [];
foreach ($schedule as $w) foreach ($w['classes'] as $c) foreach ($c['items'] as $i) {
    if ($i['kind'] === 'mock_test' && stripos($i['title'], 'diagnostic') === false) $mockClasses[] = $c['number'];
}
$mockClasses = array_values(array_unique($mockClasses));
$isTest = fn (array $i) => in_array($i['kind'], ['practice_test', 'mock_test'], true);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($ctxCourse['title']) ?> — Class 1: Course Orientation</title>
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
                    <li class="breadcrumb-item">
                        <a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Class 1: Course Orientation</li>
                </ol>
            </nav>

            <h1 class="mb-3">
                <i class="bi bi-mortarboard-fill me-2" style="color: #0b77ff;"></i>
                Class 1: Course Orientation &amp; IELTS General Overview
            </h1>

            <p class="lead">
                Welcome to the <?= htmlspecialchars($ctxCourse['title']) ?>. Today we cover how the programme works,
                what IELTS General tests across all four skills, and how to get the most from every class
                over the next <?= (int) $ctxCourse['weeks'] ?> weeks.
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
                    <li><strong><?= (int) $ctxCourse['classes'] ?> classes</strong> over <?= (int) $ctxCourse['weeks'] ?> weeks — twice per week</li>
                    <li><strong>Regular classes:</strong> 90 minutes — two complementary skills per session</li>
                    <?php if ($mockClasses): ?><li><strong>Mock test <?= count($mockClasses) === 1 ? 'class' : 'classes' ?> (<?= implode(', ', $mockClasses) ?>):</strong> 3 hours — full timed exam under real conditions</li><?php endif; ?>
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
                        <p class="mb-0">Many classes include a timed practice test, taken on a computer. Your timetable below marks each one.</p>
                    </div>
                </div>

                <?php if ($mockClasses): ?>
                <h3 class="mt-4"><?= count($mockClasses) === 1 ? 'A Full Mock Exam' : count($mockClasses) . ' Full Mock Exams' ?></h3>
                <p>
                    At the end of each month (<?= count($mockClasses) === 1 ? 'Class' : 'Classes' ?> <?= implode(', ', $mockClasses) ?>), you sit a complete timed mock exam
                    covering all four skills under real exam conditions. Written feedback and band score
                    estimates are provided after each mock — along with a targeted improvement plan for
                    the following month.
                </p>
                <?php endif; ?>
            </div>

            <!-- Timetable: read from the database for this course -->
            <div class="content-section">
                <h2><?= (int) $ctxCourse['classes'] ?>-Class Programme Schedule</h2>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead><tr><th style="width:5.5rem">Week</th><th style="width:4.5rem">Class</th><th>What's in it</th></tr></thead>
                        <tbody>
                        <?php foreach ($schedule as $week): foreach ($week['classes'] as $ci => $cls): ?>
                            <tr>
                                <td><?= $ci === 0 ? 'Week ' . (int) $week['number'] : '' ?></td>
                                <td><?= (int) $cls['number'] ?></td>
                                <td>
                                    <?php foreach ($cls['items'] as $ii => $item): ?>
                                        <div class="<?= $ii ? 'border-top pt-1 mt-1' : '' ?>">
                                            <?= htmlspecialchars($item['title']) ?>
                                            <?php if ($isTest($item)): ?><span class="badge text-bg-light border ms-1">Test</span><?php endif; ?>
                                            <?php if (!$item['ready']): ?><span class="badge text-bg-light border ms-1">Coming Soon</span><?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Class 1 Videos -->
            <div class="content-section">
                <h2><i class="bi bi-play-circle me-2"></i>Class 1 Videos</h2>
                <p>Three orientation videos to watch as part of today's session:</p>

                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-camera-video me-2"></i>Alessandra — Welcome &amp; Introduction</h4>
                        <p class="text-muted mb-2">Programme introduction from your lead tutor — what to expect over the next <?= (int) $ctxCourse['weeks'] ?> weeks.</p>
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

            <!-- Quiz -->
            <div class="content-section">
                <h2><i class="bi bi-question-circle me-2" style="color: #0b77ff;"></i>Class 1 Quiz</h2>
                <div class="highlight-box" style="border-left-color: #16a34a; background: #f0fdf4;">
                    <h5 style="color: #16a34a;"><i class="bi bi-check2-square me-2"></i>IELTS Format Knowledge Quiz</h5>
                    <p class="mb-2">
                        10 quick questions on the format of the test itself — section timings, question counts,
                        and band ranges — covered in today's orientation.
                    </p>
                    <p class="mb-2"><strong>Format:</strong> 10 questions | Multiple choice</p>
                    <a href="<?= ACADEMY_URL ?>resources/quizzes/class_quiz.php?test_code=IELTS_GM_C1_QUIZ&return=<?= urlencode('../../courses/IELTS_Gen/lessons/intro.php') ?>" class="btn btn-success btn-sm"><i class="bi bi-play-fill me-1"></i>Start Quiz</a>
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
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Course Overview
                </a>
                <a href="class02.php" class="btn btn-primary btn-lg">
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
                <a href="class02.php" class="btn btn-outline-light btn-sm">Class 2</a>
                <a href="class03.php" class="btn btn-outline-light btn-sm">Class 3</a>
                <a href="class04.php" class="btn btn-outline-light btn-sm">Class 4</a>
                <a href="class05.php" class="btn btn-outline-light btn-sm">Class 5</a>
                <a href="class06.php" class="btn btn-outline-light btn-sm">Class 6</a>
                <a href="class07.php" class="btn btn-outline-light btn-sm">Class 7</a>
                <a href="class08.php" class="btn btn-outline-light btn-sm">Mock Test 1 (Class 8)</a>
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
