<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'IELTS_Aca_Mst' LIMIT 1");
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

// Global class number runs across the whole course (module sizes vary — Weeks
// 5/6 have 7 classes each, the rest have 2), unlike the fixed "8 per module" plans.
$globalNum = 0;
foreach ($modules as &$m) {
    foreach ($m['lessons'] as &$l) { $l['global'] = ++$globalNum; }
    unset($l);
}
unset($m);

$firstLesson = $rows[0]['lesson_id'] ?? null;
$student_tier_level = get_student_tier_level();
$student_id = (int) $_SESSION['user_id'];
$completedLessonIds = progressPathLoadCompleted($db, $course_id, $student_id);
$week_colors = [1=>'#0b77ff', 2=>'#3b82f6', 3=>'#059669', 4=>'#8b5cf6', 5=>'#ec4899', 6=>'#ec4899', 7=>'#f59e0b', 8=>'#dc2626'];
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
                <i class="bi bi-stars me-2" style="color:#ec4899;"></i>
                <?= htmlspecialchars($course['title']) ?>
            </h1>

            <p class="lead mb-3"><?= htmlspecialchars($course['description'] ?? '') ?></p>

            <div class="highlight-box">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <strong><?= (int) $course['total_lessons'] ?> Classes</strong> &nbsp;·&nbsp;
                        <strong>8 Weeks</strong> &nbsp;·&nbsp;
                        <strong>2 Sessions / Week</strong> &nbsp;·&nbsp;
                        <strong><?= htmlspecialchars($course['instructor_name'] ?? 'SLS') ?></strong>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <?php if ($student_tier_level >= 1 && $firstLesson): ?>
                            <a href="lesson.php?id=<?= $firstLesson ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-play-circle me-1"></i>Start Class 1
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Course Content</h2>
                <p class="text-muted small mb-3">
                    <i class="bi bi-lock-fill me-1 text-warning"></i>Locked classes require a higher subscription tier — each week unlocks progressively.
                </p>
                <?= renderProgressPath($modules, $completedLessonIds, [
                    'folder'       => 'IELTS_Aca_Mst',
                    'tier_level'   => $student_tier_level,
                    'class_number' => fn($week, $lesson, $i) => (int) $lesson['global'],
                    'class_url'    => fn($num, $lesson) => 'lesson.php?id=' . (int) ($lesson['lesson_id'] ?? $lesson['id']),
                    'is_mock'      => fn($week, $lesson, $num, $i) => $week === 8 && $i === 0,
                    'parts'        => progressPathLoadParts($db, $course_id, $student_id),
                    'week_brief'   => fn($week, $color) => weekBriefButton($db, $course_id, $week, $color),
                ]) ?>
            </div>

            <div class="content-section">
                <h2>What's Included</h2>
                <div class="info-grid">
                    <div class="info-card" style="border-color:#1e3a8a;"><h4 style="color:#1e3a8a;"><i class="bi bi-headphones me-2"></i>Listening</h4><p class="mb-0">All four sections, question-type strategy, and timed practice.</p></div>
                    <div class="info-card" style="border-color:#059669;"><h4 style="color:#059669;"><i class="bi bi-book me-2"></i>Reading</h4><p class="mb-0">Academic passage strategy, True/False/Not Given logic, and all major question types.</p></div>
                    <div class="info-card" style="border-color:#ec4899;"><h4 style="color:#ec4899;"><i class="bi bi-pencil me-2"></i>Writing</h4><p class="mb-0">Every Task 1 chart/diagram type and every Task 2 essay type, with band-descriptor feedback.</p></div>
                    <div class="info-card" style="border-color:#f59e0b;"><h4 style="color:#f59e0b;"><i class="bi bi-mic me-2"></i>Speaking</h4><p class="mb-0">All three parts, fluency and cue-card strategy, and a full mock speaking simulation.</p></div>
                </div>
            </div>

            <div class="action-buttons">
                <?php if ($firstLesson): ?>
                <a href="lesson.php?id=<?= $firstLesson ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Start Class 1
                </a>
                <?php endif; ?>
                <a href="../courses_catalogue.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left-circle me-2"></i>All Courses</a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            </div>

        </div>
    </main>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
