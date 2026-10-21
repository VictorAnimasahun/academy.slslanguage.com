<?php
// Class 2, item 1 of the CELPIP General 2/3-month courses: the CELPIP exam introduction. Class 1 introduces
// the COURSE; this introduces the EXAM, then a quiz locks it in, then the CELPIP Diagnostic Test (founder,
// 2026-10-19). OUTLINE ONLY: the headings are set; each section is written later. Its item stays Coming Soon
// (lesson_parts.status) until the content is in.
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

$next = next_course_item($db, $ctxCourse, 'courses/CELPIP_Gen/lessons/exam_introduction.php');
// Class 2 of the 2/3-month courses, Class 1 of the crash course: read from the course, not typed in.
$pos = course_item_position($db, $ctxCourse, 'courses/CELPIP_Gen/lessons/exam_introduction.php') ?? ['week' => 1, 'class' => 2];
$sections = [
    ['bi-patch-question', 'What CELPIP Is', 'Who accepts CELPIP General, what it is used for, and how it differs from other English tests.'],
    ['bi-headphones', 'The Listening Test', 'Its parts, timing and question types.'],
    ['bi-book', 'The Reading Test', 'Its parts, timing and question types.'],
    ['bi-pencil', 'The Writing Test', 'The two tasks, timing and what is marked.'],
    ['bi-mic', 'The Speaking Test', 'The eight tasks, preparation and speaking time, and what is marked.'],
    ['bi-bar-chart-line', 'How CELPIP Is Scored', 'CELPIP levels, CLB levels, and how to read a score report against your target.'],
    ['bi-calendar-check', 'Test Day', 'What happens on the day, from check-in to results.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($ctxCourse['title']) ?> — CELPIP Introduction</title>
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
                    <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">CELPIP Introduction</li>
                </ol>
            </nav>

            <h1 class="mb-3"><i class="bi bi-compass me-2" style="color:#0b77ff;"></i>CELPIP Introduction</h1>

            <div class="highlight-box">
                <p class="mb-0"><strong>Week:</strong> <?= (int) $pos['week'] ?> &nbsp;|&nbsp; <strong>Class:</strong> <?= (int) $pos['class'] ?> of <?= (int) $ctxCourse['classes'] ?></p>
            </div>

            <p class="lead">This lesson introduces the CELPIP exam itself. A short quiz follows to lock it in, and then the CELPIP Diagnostic Test.</p>

            <?php foreach ($sections as [$icon, $heading, $about]): ?>
                <div class="content-section">
                    <h2><i class="bi <?= $icon ?> me-2" style="color:#0b77ff;"></i><?= htmlspecialchars($heading) ?></h2>
                    <p class="text-muted mb-0"><?= htmlspecialchars($about) ?> <em>Coming Soon.</em></p>
                </div>
            <?php endforeach; ?>

            <div class="action-buttons">
                <?php if ($next): ?>
                    <a href="<?= htmlspecialchars($next['url']) ?>" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: <?= htmlspecialchars($next['title']) ?> <i class="bi bi-arrow-right ms-1"></i></a>
                <?php endif; ?>
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Back to course</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
