<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'IELTS_Aca_2Mo' LIMIT 1");
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) { header("Location: ../courses_catalogue.php?message=Course+not+found"); exit(); }

$minTier = 'intermediate';
$canAccess = can_access($minTier);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Writing Test 1 (Timed) + Listening Formats — <?= htmlspecialchars($course['title']) ?></title>
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
                    <li class="breadcrumb-item active">Writing Test 1 (Timed) + Listening Formats</li>
                </ol>
            </nav>

            <p class="text-muted small mb-1">Week 3 — Writing Test 1 & Listening Test 2 &nbsp;·&nbsp; Class 5 of 16</p>
            <h1 class="mb-3">
                <i class="bi bi-pencil-square me-2" style="color:#0b77ff;"></i>
                Writing Test 1 (Timed) + Listening Formats
            </h1>

            <?php if (!$canAccess): ?>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);"><i class="bi bi-lock-fill me-2"></i>Locked</h4>
                    <p class="mb-2">This class requires the <strong><?= htmlspecialchars(ucfirst($minTier)) ?></strong> plan.</p>
                    <a href="../../upgrade.php?required=<?= htmlspecialchars($minTier) ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                    </a>
                </div>
            <?php else: ?>

                <div class="lesson-content mb-4"><h5>Writing</h5><ul><li>Complete Writing Test 1: Task 1 (20 minutes, timed) and Task 2 (40 minutes, timed)</li><li>Self-assessment against the band descriptors</li></ul><h5>Listening</h5><ul><li>Section formats and question types (Sections 1-4)</li><li>Common distractor patterns</li><li>Sample question set</li></ul></div>
                <a href="<?= ACADEMY_URL ?>resources/practice_tests/ielts_writing_academic_001.php" class="btn btn-primary btn-lg" target="_blank" rel="noopener">
                    <i class="bi bi-box-arrow-up-right me-2"></i>Open Writing Test 1
                </a>

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