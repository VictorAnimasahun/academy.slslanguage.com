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

// All lessons in this course, in teaching order, so we can find prev/next
// and confirm the requested lesson actually belongs here.
$stmt = $db->prepare("
    SELECT l.id, l.title, l.content, l.icon, l.duration_minutes, l.min_tier, l.file_path,
           m.id AS module_id, m.module_title, m.module_order, l.lesson_order
    FROM lessons l JOIN modules m ON m.id = l.module_id
    WHERE m.course_id = ? ORDER BY m.module_order, l.lesson_order
");
$stmt->execute([$course_id]);
$allLessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

$lessonId = (int) ($_GET['id'] ?? 0);
$lesson = null;
$prev = null; $next = null;
$globalNum = 0;
foreach ($allLessons as $i => $l) {
    if ((int)$l['id'] === $lessonId) {
        $lesson = $l;
        $globalNum = $i + 1;
        $prev = $allLessons[$i - 1] ?? null;
        $next = $allLessons[$i + 1] ?? null;
        break;
    }
}
if (!$lesson) { header("Location: course_overview.php"); exit(); }

require_once INCLUDES_PATH . '/lesson_title.php';
$canAccess = can_access($lesson['min_tier']);
$titleLines = lesson_title_lines($lesson['title']);   // one lesson per line, never joined with "+"
$weekChip = preg_match('/^(Week|Month)\s+\d+/u', $lesson['module_title'], $wm) ? $wm[0] : $lesson['module_title'];
$isExternal = !empty($lesson['file_path']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars(implode(' · ', $titleLines)) ?> — <?= htmlspecialchars($course['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .lesson-content { font-size: .95rem; line-height: 1.8; color: #1f2937; }
        .lesson-content ul { padding-left: 1.25rem; }
        .lesson-content li { margin-bottom: .4rem; }
        .lesson-nav { display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
        .lesson-nav .btn { max-width: 47%; }
        .class-head { display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
        .class-head-icon { flex: none; width: 52px; height: 52px; border-radius: 12px; background: #fdf2f8; color: #ec4899; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .class-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .55rem; }
        .class-chip { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #f1f5f9; color: #475569; border-radius: 999px; padding: .2rem .7rem; }
        .class-title { font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.3; }
        .class-title-line { display: block; }
        .class-title-line + .class-title-line { margin-top: .2rem; }
        .lesson-item { padding: 1rem 0; border-bottom: 1px solid #e5e7eb; }
        .lesson-item:last-child { border-bottom: 0; }
        .lesson-item h5 { margin-top: 0; font-weight: 700; }
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
                    <li class="breadcrumb-item active">Class <?= $globalNum ?></li>
                </ol>
            </nav>

            <div class="class-head">
                <div class="class-head-icon"><i class="bi <?= htmlspecialchars($lesson['icon'] ?: 'bi-play-circle') ?>"></i></div>
                <div>
                    <div class="class-chips"><span class="class-chip"><?= htmlspecialchars($weekChip) ?></span><span class="class-chip">Class <?= $globalNum ?> of <?= count($allLessons) ?></span></div>
                    <h1 class="class-title"><?php foreach ($titleLines as $line): ?><span class="class-title-line"><?= htmlspecialchars($line) ?></span><?php endforeach; ?></h1>
                </div>
            </div>

            <?php if (!$canAccess): ?>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);"><i class="bi bi-lock-fill me-2"></i>Locked</h4>
                    <p class="mb-2">This class requires the <strong><?= htmlspecialchars(ucfirst($lesson['min_tier'])) ?></strong> plan.</p>
                    <a href="../../upgrade.php?required=<?= htmlspecialchars($lesson['min_tier']) ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                    </a>
                </div>
            <?php else: ?>

                <?php if ($isExternal): ?>
                    <div class="highlight-box pink-highlight mb-4">
                        <p class="mb-3"><?= $lesson['content'] ?></p>
                        <a href="<?= ACADEMY_URL . htmlspecialchars($lesson['file_path']) ?>" class="btn btn-primary btn-lg" target="_blank" rel="noopener">
                            <i class="bi bi-box-arrow-up-right me-2"></i>Open Diagnostic Test
                        </a>
                    </div>
                <?php else: ?>
                    <div class="lesson-content mb-4">
                        <?php if (str_contains((string) $lesson['content'], '<h5>')): ?>
                            <?php foreach (preg_split('/(?=<h5>)/', (string) $lesson['content'], -1, PREG_SPLIT_NO_EMPTY) as $chunk): ?>
                                <div class="lesson-item"><?= $chunk ?></div>
                            <?php endforeach; ?>
                        <?php else: ?><?= $lesson['content'] ?><?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

            <div class="lesson-nav">
                <?php if ($prev): ?>
                    <a href="lesson.php?id=<?= $prev['id'] ?>" class="btn btn-outline-secondary flex-fill text-truncate">
                        <i class="bi bi-arrow-left me-1"></i>Previous Class
                    </a>
                <?php else: ?><span></span><?php endif; ?>
                <?php if ($next): ?>
                    <a href="lesson.php?id=<?= $next['id'] ?>" class="btn btn-primary flex-fill text-truncate">
                        Next Class<i class="bi bi-arrow-right ms-1"></i>
                    </a>
                <?php else: ?>
                    <a href="course_overview.php" class="btn btn-success flex-fill">
                        <i class="bi bi-flag-fill me-1"></i>Finish — Back to Course Overview
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
