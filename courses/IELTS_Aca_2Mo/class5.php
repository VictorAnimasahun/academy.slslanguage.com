<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once INCLUDES_PATH . '/lesson_title.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'IELTS_Aca_2Mo' LIMIT 1");
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) { header("Location: ../courses_catalogue.php?message=Course+not+found"); exit(); }

$minTier = lesson_min_tier($db, 'IELTS_Aca_2Mo', 5, 'intermediate');   // from the database (lessons.min_tier), not typed here
$canAccess = can_access($minTier);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Writing Test 1 (Timed) · Listening Formats — <?= htmlspecialchars($course['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .lesson-content { font-size: .95rem; line-height: 1.8; color: #1f2937; }
        .lesson-content ul { padding-left: 1.25rem; }
        .lesson-content li { margin-bottom: .4rem; }
        .lesson-content h5 { margin-top: 1.25rem; font-weight: 700; color: #0b77ff; }
        .lesson-content h5:first-child { margin-top: 0; }
        .lesson-item { padding: 1rem 0; border-bottom: 1px solid #e5e7eb; }
        .lesson-item:last-child { border-bottom: 0; }
        .lesson-item h5 { margin-top: 0; }
        .lesson-item .btn { margin-top: .5rem; }
        .class-head { display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
        .class-head-icon { flex: none; width: 52px; height: 52px; border-radius: 12px; background: #e8f1ff; color: #0b77ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .class-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .55rem; }
        .class-chip { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #f1f5f9; color: #475569; border-radius: 999px; padding: .2rem .7rem; }
        .class-title { font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.3; }
        .class-title-line { display: block; }
        .class-title-line + .class-title-line { margin-top: .2rem; }
        .lesson-nav { display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
        .lesson-nav .btn { max-width: 47%; }
    </style>
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
                    <li class="breadcrumb-item"><a href="course_overview.php" class="text-decoration-none"><?= htmlspecialchars($course['title']) ?></a></li>
                    <li class="breadcrumb-item active">Writing Test 1 (Timed) · Listening Formats</li>
                </ol>
            </nav>

            <div class="class-head">
                <div class="class-head-icon"><i class="bi bi-pencil-square"></i></div>
                <div>
                    <div class="class-chips"><span class="class-chip">Week 3</span><span class="class-chip">Class 5 of 16</span></div>
                    <h1 class="class-title"><span class="class-title-line">Writing Test 1 (Timed)</span><span class="class-title-line">Listening Formats</span></h1>
                </div>
            </div>

            <?php if (!$canAccess): ?>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);"><i class="bi bi-lock-fill me-2"></i>Locked</h4>
                    <p class="mb-2">This class requires the <strong><?= htmlspecialchars(ucfirst($minTier)) ?></strong> plan.</p>
                    <a href="../../upgrade.php?required=<?= htmlspecialchars($minTier) ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                    </a>
                </div>
            <?php else: ?>

                <div class="lesson-content mb-4">
                    <div class="lesson-item">
                        <h5>Writing Test 1 (Timed) <?= lesson_kind_badge($db, 'IELTS_Aca_2Mo', 5, 'Writing Test 1 (Timed)') ?></h5>
                        <ul><li>Complete Writing Test 1: Task 1 (20 minutes, timed) and Task 2 (40 minutes, timed)</li><li>Self-assessment against the band descriptors</li></ul>
                        <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_writing_academic_001.php" class="btn btn-primary btn-lg" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Open Writing Test 1
                </a>
                    </div>
                    <div class="lesson-item">
                        <h5>Listening Formats <?= lesson_kind_badge($db, 'IELTS_Aca_2Mo', 5, 'Listening Formats') ?></h5>
                        <ul><li>Section formats and question types (Sections 1-4)</li><li>Common distractor patterns</li><li>Sample question set</li></ul>
                        <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_listening_formats_sample.php" class="btn btn-outline-primary btn-lg" target="_blank" rel="noopener">
                    <i class="bi bi-headphones me-2"></i>Open Lesson
                </a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="lesson-nav">
                <a href="class4.php" class="btn btn-outline-secondary flex-fill text-truncate"><i class="bi bi-arrow-left me-1"></i>Previous Class</a>
                <a href="class6.php" class="btn btn-primary flex-fill text-truncate">Next Class<i class="bi bi-arrow-right ms-1"></i></a>
            </div>

        </div>
    </main>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>