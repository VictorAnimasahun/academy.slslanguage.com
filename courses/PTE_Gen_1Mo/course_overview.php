<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'PTE_Gen_1Mo' LIMIT 1");
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
    if (!isset($modules[$mi])) $modules[$mi] = ['title' => $row['module_title'], 'min_tier' => $row['module_min_tier'], 'lessons' => []];
    $modules[$mi]['lessons'][] = $row;
}

$student_tier_level = get_student_tier_level();
$student_id = (int) $_SESSION['user_id'];
$completedLessonIds = progressPathLoadCompleted($db, $course_id, $student_id);
$month_colors = [1 => '#0b77ff'];
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
                <i class="bi bi-mortarboard-fill me-2" style="color:#0b77ff;"></i>
                <?= htmlspecialchars($course['title']) ?>
            </h1>

            <p class="lead mb-3"><?= htmlspecialchars($course['description'] ?? '') ?></p>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <strong><?= (int) $course['total_lessons'] ?> Classes</strong> &nbsp;·&nbsp;
                        <strong>4 Weeks</strong> &nbsp;·&nbsp;
                        <strong>2 Sessions / Week</strong> &nbsp;·&nbsp;
                        <strong><?= htmlspecialchars($course['instructor_name'] ?? 'SLS') ?></strong>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <?php if ($student_tier_level >= 2): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Access: Intermediate Plan
                            </span>
                        <?php else: ?>
                            <a href="../../upgrade.php?required=intermediate" class="btn btn-primary btn-sm">
                                <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                            </a>
                        <?php endif; ?>
                        <span class="badge bg-secondary px-3 py-2">
                            <i class="bi bi-clock me-1"></i>Coming Soon
                        </span>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Course Content</h2>
                <p class="text-muted small mb-3">
                    <i class="bi bi-lock-fill me-1 text-warning"></i>Locked classes require the <strong>Intermediate</strong> plan.
                    <i class="bi bi-info-circle ms-3 me-1 text-primary"></i>Lesson content launching soon.
                </p>
                <?= renderProgressPath($modules, $completedLessonIds, [
                    'folder'       => 'PTE_Gen_1Mo',
                    'tier_level'   => $student_tier_level,
                    'mock_classes' => [8],
                    'class_number' => fn($week, $lesson, $i) => (int) $lesson['lesson_order'],
                    'class_url'    => fn($num, $lesson) => null, // content not built yet
                    'parts'        => progressPathLoadParts($db, $course_id, $student_id),
                    'week_brief'   => fn($week, $color) => weekBriefButton($db, $course_id, $week, $color),
                ]) ?>
            </div>

            <div class="content-section">
                <h2>What's Included</h2>
                <div class="info-grid">
                    <div class="info-card"><h4><i class="bi bi-play-circle me-2"></i>Lesson Videos</h4><p class="mb-0">Targeted PTE Academic video lessons for every class, covering Speaking, Writing, Reading, and Listening.</p></div>
                    <div class="info-card"><h4><i class="bi bi-robot me-2"></i>AI-Scored Practice</h4><p class="mb-0">PTE uses computer-based AI scoring — we prepare you to understand and beat the algorithm.</p></div>
                    <div class="info-card"><h4><i class="bi bi-house-heart me-2"></i>Take-Home Tasks</h4><p class="mb-0">One practical task per class — Describe Image recordings, Summarize Written Text, or Repeat Sentence drills.</p></div>
                    <div class="info-card"><h4><i class="bi bi-clipboard-check me-2"></i>1 Practice Test Set</h4><p class="mb-0">A full PTE Academic practice test across all four skills, spread throughout the teaching classes.</p></div>
                    <div class="info-card"><h4><i class="bi bi-journal-richtext me-2"></i>1 Full Mock Exam</h4><p class="mb-0">End-of-month full timed mock exam with scored feedback and a target score estimate.</p></div>
                    <div class="info-card"><h4><i class="bi bi-arrow-up-circle me-2"></i>Upgrade Anytime</h4><p class="mb-0">Upgrade to the 2-Month or 3-Month PTE plan at any time — your progress carries over.</p></div>
                </div>
            </div>

            <div class="action-buttons">
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
