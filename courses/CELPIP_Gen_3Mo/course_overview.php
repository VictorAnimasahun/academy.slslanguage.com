<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'CELPIP_Gen_3Mo' LIMIT 1");
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) { header("Location: ../courses_catalogue.php?message=Course+not+found"); exit(); }

$course_id = (int) $course['id'];
require_once INCLUDES_PATH . '/week_brief.php';
require_once INCLUDES_PATH . '/course_progress_path.php';
$stmt = $db->prepare("
    SELECT m.id AS module_id, m.module_title, m.module_order, m.min_tier AS module_min_tier,
           l.id AS lesson_id, l.title, l.lesson_order, l.duration_minutes,
           l.min_tier, l.icon, l.file_path
    FROM modules m JOIN lessons l ON l.module_id = m.id
    WHERE m.course_id = ? ORDER BY m.module_order, l.lesson_order
");
$stmt->execute([$course_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$modules = [];
foreach ($rows as $row) {
    $mi = $row['module_order'];
    if (!isset($modules[$mi])) $modules[$mi] = ['id' => (int)$row['module_id'], 'title' => $row['module_title'], 'min_tier' => $row['module_min_tier'], 'lessons' => []];
    $modules[$mi]['lessons'][] = $row;
}

// Quick Access sidebar shows the student's current/next module — the first
// module (in order) with at least one lesson not yet marked complete in
// lesson_progress — instead of a fixed, stale set of links.
$student_id = (int) $_SESSION['user_id'];
$lessonIds  = array_column($rows, 'lesson_id');
$completedLessonIds = [];
if ($lessonIds) {
    $placeholders = implode(',', array_fill(0, count($lessonIds), '?'));
    $stmt = $db->prepare("
        SELECT lesson_id FROM lesson_progress
        WHERE student_id = ? AND completed = 1 AND lesson_id IN ($placeholders)
    ");
    $stmt->execute([$student_id, ...$lessonIds]);
    $completedLessonIds = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}
$currentModule = null;
foreach ($modules as $mi => $mod) {
    $hasIncomplete = false;
    foreach ($mod['lessons'] as $l) {
        if (!in_array((int) $l['lesson_id'], $completedLessonIds, true)) { $hasIncomplete = true; break; }
    }
    if ($hasIncomplete) { $currentModule = $mod; break; }
}
if (!$currentModule) $currentModule = end($modules); // whole course completed — show the last module

$student_tier_level = get_student_tier_level();
// Mock weeks (full 4-skill simulations) are tinted in the progress path.
// Keyed by module_order (1-12), matching the 12-week schedule.
$mock_weeks = [8, 11];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($course['title']) ?> — Course Overview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <link href="../../assets/css/progress_path.css" rel="stylesheet">
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
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($course['title']) ?></li>
                </ol>
            </nav>

            <h1 class="mb-2">
                <i class="bi bi-mortarboard-fill me-2" style="color:#16a34a;"></i>
                <?= htmlspecialchars($course['title']) ?>
            </h1>

            <p class="lead mb-3"><?= htmlspecialchars($course['description'] ?? '') ?></p>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <strong><?= (int) $course['total_lessons'] ?> Classes</strong> &nbsp;·&nbsp;
                        <strong>12 Weeks</strong> &nbsp;·&nbsp;
                        <strong>2 Sessions / Week</strong> &nbsp;·&nbsp;
                        <strong><?= htmlspecialchars($course['instructor_name'] ?? 'SLS') ?></strong>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <?php if ($student_tier_level >= 2): ?>
                            <span class="badge bg-success px-3 py-2"><i class="bi bi-check-circle me-1"></i>Access: Intermediate Plan</span>
                        <?php else: ?>
                            <a href="../../upgrade.php?required=intermediate" class="btn btn-primary btn-sm">
                                <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                            </a>
                        <?php endif; ?>
                        <a href="<?= ACADEMY_URL ?>courses/CELPIP_Gen/lessons/week1_foundational_assessment.php?from=CELPIP_Gen_3Mo" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-play-circle me-1"></i>Class 1 (Free Preview)
                        </a>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Course Content</h2>
                <p class="text-muted small mb-3">
                    <i class="bi bi-unlock-fill me-1 text-success"></i>Classes 1-2 (Week 1) are free.
                    <i class="bi bi-lock-fill ms-3 me-1 text-warning"></i>Classes 3-24 require the <strong>Intermediate</strong> plan.
                </p>
                <?= renderProgressPath($modules, $completedLessonIds, [
                    'folder'       => 'CELPIP_Gen_3Mo',
                    'tier_level'   => $student_tier_level,
                    'mock_weeks'   => $mock_weeks,
                    'mock_classes' => [15, 21],
                    'week_brief'   => fn($week, $color) => weekBriefButton($db, $course_id, $week, $color),
                ]) ?>
            </div>

            <div class="content-section">
                <h2>What's Included</h2>
                <div class="info-grid">
                    <div class="info-card"><h4><i class="bi bi-play-circle me-2"></i>Lesson Content</h4><p class="mb-0">A dedicated class for every session across the full 12-week schedule.</p></div>
                    <div class="info-card"><h4><i class="bi bi-question-circle me-2"></i>Class Quizzes</h4><p class="mb-0">Consolidation quizzes after every class to lock in strategies and CLB band descriptors.</p></div>
                    <div class="info-card"><h4><i class="bi bi-house-heart me-2"></i>Take-Home Tasks</h4><p class="mb-0">One practical task per class — email drafts, listening notes, or speaking recordings.</p></div>
                    <div class="info-card"><h4><i class="bi bi-clipboard-check me-2"></i>4 Complete Test Sittings Per Skill</h4><p class="mb-0">Every skill gets a complete, full-length practice test 4 separate times across the program, one skill per class day (Reading, Writing, Speaking already built; Listening tests are marked as coming soon).</p></div>
                    <div class="info-card"><h4><i class="bi bi-journal-richtext me-2"></i>2 Full Mock Exams</h4><p class="mb-0">Full timed 4-skill mock exams at Weeks 8 and 11, with detailed written feedback and CLB band score reports.</p></div>
                    <div class="info-card"><h4><i class="bi bi-patch-check me-2"></i>Completion Certificate</h4><p class="mb-0">An SLS certificate of completion awarded upon finishing the full Masterclass program.</p></div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>courses/CELPIP_Gen/lessons/week1_foundational_assessment.php?from=CELPIP_Gen_3Mo" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Start with Class 1 (Free Preview)
                </a>
                <a href="../courses_catalogue.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>All Courses</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>

        </div>
    </main>

    <aside class="advert-sidebar">
        <?= renderWeekPanel($db, $course_id, (int) $_SESSION['user_id'], null, $course['folder_name']) ?>
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
