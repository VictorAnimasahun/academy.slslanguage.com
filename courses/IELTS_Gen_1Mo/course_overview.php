<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM courses WHERE folder_name = 'IELTS_Gen_1Mo' LIMIT 1");
$stmt->execute();
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header("Location: ../courses_catalogue.php?message=Course+not+found");
    exit();
}

$course_id = (int) $course['id'];

$stmt = $db->prepare("
    SELECT m.id AS module_id, m.module_title, m.module_order, m.min_tier AS module_min_tier,
           l.id AS lesson_id, l.title, l.lesson_order, l.duration_minutes,
           l.min_tier, l.icon, l.file_path
    FROM modules m
    JOIN lessons l ON l.module_id = m.id
    WHERE m.course_id = ?
    ORDER BY m.module_order, l.lesson_order
");
$stmt->execute([$course_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$modules = [];
foreach ($rows as $row) {
    $mi = $row['module_order'];
    if (!isset($modules[$mi])) {
        $modules[$mi] = [
            'title'    => $row['module_title'],
            'min_tier' => $row['module_min_tier'],
            'lessons'  => [],
        ];
    }
    $modules[$mi]['lessons'][] = $row;
}

$student_tier_level = get_student_tier_level();
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
                        <a href="../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($course['title']) ?>
                    </li>
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
                            <a href="../../upgrade.php?required=intermediate"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-lightning-charge me-1"></i>Upgrade to Access
                            </a>
                        <?php endif; ?>
                        <a href="<?= ACADEMY_URL ?>courses/IELTS_Gen/lessons/intro.php?from=IELTS_Gen_1Mo" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-play-circle me-1"></i>Class 1 (Free Preview)
                        </a>
                    </div>
                </div>
            </div>

            <div class="content-section">
                <h2>Course Content</h2>
                <p class="text-muted small mb-3">
                    <i class="bi bi-lock-fill me-1 text-warning"></i>Locked classes require the
                    <strong>Intermediate</strong> plan.
                    <i class="bi bi-unlock-fill ms-3 me-1 text-success"></i>Class 1 is free to preview.
                </p>

                <div class="accordion" id="courseAccordion">
                <?php foreach ($modules as $month_num => $module): ?>
                    <?php
                    $color       = $month_colors[$month_num] ?? '#0b77ff';
                    $collapse_id = 'month' . $month_num;
                    ?>
                    <div class="accordion-item mb-2" style="border-radius:10px;overflow:hidden;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                        <h2 class="accordion-header">
                            <button class="accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#<?= $collapse_id ?>"
                                    style="background:<?= $color ?>18;font-weight:700;color:<?= $color ?>;">
                                <i class="bi bi-calendar3 me-2"></i>
                                <?= htmlspecialchars($module['title']) ?>
                                <span class="badge ms-auto me-2" style="background:<?= $color ?>;color:white;">
                                    <?= count($module['lessons']) ?> classes
                                </span>
                            </button>
                        </h2>
                        <div id="<?= $collapse_id ?>" class="accordion-collapse collapse show" data-bs-parent="#courseAccordion">
                            <div class="accordion-body p-0">
                                <ul class="list-unstyled mb-0">
                                <?php foreach ($module['lessons'] as $idx => $lesson):
                                    $global_class   = (int) $lesson['lesson_order'];
                                    $required_level = ['beginner'=>1,'intermediate'=>2,'advanced'=>3,'fluent'=>4][$lesson['min_tier']] ?? 1;
                                    $can_access     = $student_tier_level >= $required_level;
                                    $is_mock        = ($global_class === 8);
                                    $file_path      = $lesson['file_path'] ?? '';
                                ?>
                                <li class="d-flex align-items-center justify-content-between px-3 py-2 <?= $idx < count($module['lessons'])-1 ? 'border-bottom' : '' ?>"
                                    style="<?= $is_mock ? 'background:#fffbeb;' : '' ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($can_access && $file_path): ?>
                                            <a href="<?= ACADEMY_URL . htmlspecialchars($file_path) ?>?from=IELTS_Gen_1Mo"
                                               class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                                <i class="bi <?= htmlspecialchars($lesson['icon'] ?? 'bi-play-circle') ?>"
                                                   style="color:<?= $color ?>;font-size:1.1rem;min-width:20px;"></i>
                                                <span>
                                                    <strong>Class <?= $global_class ?>:</strong>
                                                    <?= htmlspecialchars($lesson['title']) ?>
                                                    <?php if ($global_class === 1): ?>
                                                        <span class="badge bg-success ms-1">Free</span>
                                                    <?php endif; ?>
                                                </span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted d-flex align-items-center gap-2">
                                                <i class="bi bi-lock-fill" style="color:#94a3b8;font-size:1rem;min-width:20px;"></i>
                                                <span>
                                                    <strong>Class <?= $global_class ?>:</strong>
                                                    <?= htmlspecialchars($lesson['title']) ?>
                                                </span>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 text-muted small ms-2" style="white-space:nowrap;">
                                        <?php if ($is_mock): ?>
                                            <span class="badge bg-warning text-dark">Mock Exam</span>
                                        <?php endif; ?>
                                        <i class="bi bi-clock"></i> <?= (int) $lesson['duration_minutes'] ?> min
                                    </div>
                                </li>
                                <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>

            <div class="content-section">
                <h2>What's Included</h2>
                <div class="info-grid">
                    <div class="info-card">
                        <h4><i class="bi bi-play-circle me-2"></i>Lesson Videos</h4>
                        <p class="mb-0">Curated video lessons for every class, mapped to the skills covered that session.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-question-circle me-2"></i>Class Quizzes</h4>
                        <p class="mb-0">A consolidation quiz after every class to lock in the strategies.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-house-heart me-2"></i>Take-Home Exercises</h4>
                        <p class="mb-0">One practical task per class to reinforce the session between live classes.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-clipboard-check me-2"></i>1 Practice Test Set</h4>
                        <p class="mb-0">Practice Test Set 1 — all four skills — spread across the teaching classes.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-journal-richtext me-2"></i>1 Full Mock Exam</h4>
                        <p class="mb-0">End-of-month full timed mock exam with written feedback and band score estimate.</p>
                    </div>
                    <div class="info-card">
                        <h4><i class="bi bi-arrow-up-circle me-2"></i>Upgrade Anytime</h4>
                        <p class="mb-0">Upgrade to the 2-Month or 3-Month plan at any time — your progress carries over.</p>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="<?= ACADEMY_URL ?>courses/IELTS_Gen/lessons/intro.php?from=IELTS_Gen_1Mo" class="btn btn-primary btn-lg">
                    <i class="bi bi-play-circle me-2"></i>Start with Class 1 (Free Preview)
                </a>
                <a href="../courses_catalogue.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>All Courses
                </a>
                <a href="../../learning_dashboard.php" class="btn btn-outline-secondary">
                    <i class="bi bi-speedometer2 me-2"></i>Dashboard
                </a>
            </div>

        </div>
    </main>

    <aside class="advert-sidebar">
        <div class="course-card" style="background:linear-gradient(135deg,#0b77ff 0%,#6366f1 100%);color:white;">
            <h6 class="mb-2">Quick Access</h6>
            <div class="d-grid gap-1">
                <a href="<?= ACADEMY_URL ?>courses/IELTS_Gen/lessons/intro.php?from=IELTS_Gen_1Mo" class="btn btn-light btn-sm">Class 1 — Free Preview</a>
                <?php if ($student_tier_level >= 2): ?>
                <a href="<?= ACADEMY_URL ?>courses/IELTS_Gen/lessons/class02.php?from=IELTS_Gen_1Mo" class="btn btn-outline-light btn-sm">Class 2</a>
                <a href="<?= ACADEMY_URL ?>courses/IELTS_Gen/lessons/class08.php?from=IELTS_Gen_1Mo" class="btn btn-outline-light btn-sm">Mock Test 1</a>
                <?php else: ?>
                <a href="../../upgrade.php?required=intermediate" class="btn btn-warning btn-sm">
                    <i class="bi bi-lightning-charge me-1"></i>Upgrade to Unlock
                </a>
                <?php endif; ?>
            </div>
        </div>
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
