<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'IELTS_Aca_3Mo' LIMIT 1");
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) { header("Location: ../courses_catalogue.php?message=Course+not+found"); exit(); }

$minTier = 'fluent';
$canAccess = can_access($minTier);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Consolidation — Targeted Weak-Area Drilling — <?= htmlspecialchars($course['title']) ?></title>
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
                    <li class="breadcrumb-item active">Consolidation — Targeted Weak-Area Drilling</li>
                </ol>
            </nav>

            <p class="text-muted small mb-1">Week 11 — Writing Test 4 & Weak-Area Drilling &nbsp;·&nbsp; Class 22 of 24</p>
            <h1 class="mb-3">
                <i class="bi bi-stopwatch me-2" style="color:#0b77ff;"></i>
                Consolidation — Targeted Weak-Area Drilling
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

                <div class="highlight-box">
                    <h4 style="color:var(--accent);"><i class="bi bi-hourglass-split me-2"></i>Coming Soon</h4>
                    <p class="mb-0">This class's lesson content is still being finalized. Please register for the course (or check back soon) to get full access as it's added.</p>
                </div>

            <?php endif; ?>

            <div class="lesson-nav">
                <a href="class21.php" class="btn btn-outline-secondary flex-fill text-truncate"><i class="bi bi-arrow-left me-1"></i>Previous Class</a>
                <a href="class23.php" class="btn btn-primary flex-fill text-truncate">Next Class<i class="bi bi-arrow-right ms-1"></i></a>
            </div>

        </div>
    </main>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>