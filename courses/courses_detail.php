<?php
require_once dirname(__DIR__) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}

$user_id   = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
$course_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;

if (!$user_id || !$course_id) {
    header("Location: courses_catalogue.php");
    exit();
}

// Course + enrolment status
$stmt = $db->prepare("SELECT c.*,
    CASE WHEN e.student_id IS NOT NULL THEN 1 ELSE 0 END AS is_enrolled,
    COALESCE(e.progress_percentage, 0) AS progress_percentage,
    e.enrolled_at
    FROM courses c
    LEFT JOIN enrollments e ON c.id = e.course_id AND e.student_id = ?
    WHERE c.id = ?");
$stmt->execute([$user_id, $course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    header("Location: courses_catalogue.php?message=Course+not+found");
    exit();
}

// Handle enrolment
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && !$course['is_enrolled']) {
    try {
        $db->prepare("INSERT INTO enrollments (student_id, course_id, enrolled_at, progress_percentage)
                      VALUES (?, ?, NOW(), 0)")->execute([$user_id, $course_id]);
        header("Location: courses_detail.php?id={$course_id}&enrolled=1");
        exit();
    } catch (PDOException $e) {
        $error_message = "Failed to enrol. Please try again.";
    }
}

// Resolve "Continue / Start" link
$courseFolder = $course['folder_name'] ?? '';
$base = __DIR__ . '/' . $courseFolder;
if (file_exists($base . '/intro.php')) {
    $startLink = ACADEMY_URL . "courses/{$courseFolder}/intro.php?id={$course['id']}";
} elseif (file_exists($base . '/course_overview.php')) {
    $startLink = ACADEMY_URL . "courses/{$courseFolder}/course_overview.php?id={$course['id']}";
} else {
    $startLink = ACADEMY_URL . "learning_dashboard.php";
}
$startLabel = $course['progress_percentage'] > 0 ? 'Continue Learning' : 'Start Course';

// Modules + lessons
$modules = [];
try {
    $mStmt = $db->prepare("SELECT * FROM modules WHERE course_id = ? ORDER BY module_order ASC");
    $mStmt->execute([$course_id]);
    $modules = $mStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($modules as &$mod) {
        $lStmt = $db->prepare("SELECT * FROM lessons WHERE course_id = ? AND module_id = ? ORDER BY lesson_order ASC");
        $lStmt->execute([$course_id, $mod['id']]);
        $mod['lessons'] = $lStmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    error_log("Modules/lessons error: " . $e->getMessage());
}

$learningPoints = json_decode($course['learning_points'] ?? '[]', true) ?: [];
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($course['title']) ?> — EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        /* Hero — flush against sidebar, topbar, and advert panel */
        .course-hero {
            background: linear-gradient(135deg, #0b77ff 0%, #6366f1 100%);
            color: #fff;
            padding: 2.5rem 2rem 2rem;
        }
        /* Pull the hero flush to sidebar (220px), topbar, and advert panel.
           main-wrapper has margin-left:260px but sidebar is 220px → 40px extra gap on left. */
        @media (min-width: 1200px) {
            .course-hero { margin: -2rem -2rem 0 calc(-2rem - 40px); }
        }
        .course-hero .breadcrumb-item a { color: rgba(255,255,255,.65); text-decoration: none; }
        .course-hero .breadcrumb-item a:hover { color: #fff; }
        .course-hero .breadcrumb-item.active,
        .course-hero .breadcrumb-separator { color: rgba(255,255,255,.5); }

        .hero-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .35rem .85rem; border-radius: 999px;
            font-size: .8rem; font-weight: 700;
        }
        .hero-badge-free     { background: rgba(16,185,129,.25); color: #6ee7b7; border: 1px solid rgba(16,185,129,.4); }
        .hero-badge-paid     { background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.3); }
        .hero-badge-enrolled { background: rgba(16,185,129,.3); color: #6ee7b7; border: 1px solid rgba(16,185,129,.5); }

        .hero-stat { font-size: .85rem; color: rgba(255,255,255,.85); }
        .hero-stat strong { color: #fff; }

        /* Enrolment card */
        .enrol-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(15,23,42,.12);
            padding: 1.5rem;
        }
        .enrol-card .price { font-size: 2rem; font-weight: 800; color: #0b77ff; line-height: 1; }

        /* Content cards */
        .detail-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(15,23,42,.06);
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .feature-item { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: 1rem; }
        .feature-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: #eef4ff; color: #0b77ff;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 1rem;
        }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="mobile-overlay" id="mobileOverlay"></div>
<?php include INCLUDES_PATH . '/navbar.php'; ?>
<div class="main-wrapper flex-grow-1" style="flex:1;">
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <!-- Hero -->
    <section class="course-hero">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="../learning_dashboard.php">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="courses_catalogue.php">Courses</a></li>
                <li class="breadcrumb-item active"><?= htmlspecialchars($course['title']) ?></li>
            </ol>
        </nav>

        <h1 class="fw-bold mb-2" style="font-size:1.85rem;max-width:680px;"><?= htmlspecialchars($course['title']) ?></h1>
        <p class="mb-3" style="color:rgba(255,255,255,.85);max-width:640px;font-size:.95rem;">
            <?= htmlspecialchars($course['description'] ?? 'A comprehensive course to help you master new skills.') ?>
        </p>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <?php if ($course['is_free']): ?>
                <span class="hero-badge hero-badge-free"><i class="bi bi-gift"></i> FREE</span>
            <?php else: ?>
                <span class="hero-badge hero-badge-paid"><i class="bi bi-tag"></i> $<?= number_format($course['price'], 2) ?></span>
            <?php endif; ?>
            <?php if ($course['is_enrolled']): ?>
                <span class="hero-badge hero-badge-enrolled"><i class="bi bi-check-circle-fill"></i> Enrolled</span>
            <?php endif; ?>
        </div>

        <div class="d-flex flex-wrap gap-4">
            <span class="hero-stat"><i class="bi bi-play-circle me-1"></i><strong><?= (int)($course['total_lessons'] ?? 12) ?></strong> lessons</span>
            <span class="hero-stat"><i class="bi bi-clock me-1"></i><strong><?= (int)($course['total_hours'] ?? 8) ?></strong> hours</span>
            <span class="hero-stat"><i class="bi bi-person me-1"></i><strong><?= htmlspecialchars($course['instructor_name'] ?? 'EduHub Team') ?></strong></span>
        </div>
    </section>

    <!-- Body -->
    <main class="content p-4">
        <?php if (isset($_GET['enrolled'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>You're enrolled! Start learning below.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        <?php if ($error_message): ?>
        <div class="alert alert-danger mb-4"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <div class="row g-4 align-items-start">

            <!-- Left column -->
            <div class="col-lg-8">

                <!-- About -->
                <div class="detail-card">
                    <h4 class="fw-bold mb-3">About This Course</h4>
                    <p class="text-muted mb-4"><?= nl2br(htmlspecialchars($course['description'] ?? '')) ?></p>

                    <h5 class="fw-bold mb-3">What You'll Learn</h5>
                    <div class="row g-2">
                        <?php if (!empty($learningPoints)): ?>
                            <?php foreach ($learningPoints as $pt): ?>
                            <div class="col-md-6">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                                    <span style="font-size:.9rem;"><?= htmlspecialchars($pt) ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach (['Master fundamental concepts','Apply practical skills','Build confidence through practice','Prepare for real-world applications'] as $pt): ?>
                            <div class="col-md-6">
                                <div class="d-flex gap-2">
                                    <i class="bi bi-check-circle-fill text-success mt-1 flex-shrink-0"></i>
                                    <span style="font-size:.9rem;"><?= $pt ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Course Content -->
                <?php if (!empty($modules)): ?>
                <div class="detail-card">
                    <h4 class="fw-bold mb-3">Course Content</h4>
                    <div class="accordion" id="courseAccordion">
                        <?php foreach ($modules as $i => $mod): ?>
                        <div class="accordion-item border-0 mb-2" style="border-radius:10px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06);">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#mod<?= $i ?>"
                                        style="font-size:.9rem;">
                                    <i class="bi bi-folder2 me-2 text-primary"></i>
                                    <?= htmlspecialchars($mod['module_title'] ?? 'Module ' . ($i+1)) ?>
                                    <span class="badge bg-light text-dark ms-auto me-2" style="font-size:.73rem;">
                                        <?= count($mod['lessons']) ?> lessons
                                    </span>
                                </button>
                            </h2>
                            <div id="mod<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>"
                                 data-bs-parent="#courseAccordion">
                                <div class="accordion-body py-0">
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($mod['lessons'] as $lesson): ?>
                                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div class="d-flex align-items-center gap-2" style="min-width:0;">
                                                <?php if ($course['is_enrolled']): ?>
                                                    <?php
                                                    $lPath = ltrim($lesson['file_path'] ?? '', '/');
                                                    $lUrl  = ACADEMY_URL . $lPath . '?id=' . $course['id'];
                                                    ?>
                                                    <i class="bi bi-<?= htmlspecialchars($lesson['icon'] ?? 'play-circle') ?> text-primary flex-shrink-0"></i>
                                                    <a href="<?= $lUrl ?>" class="text-decoration-none text-dark text-truncate" style="font-size:.88rem;">
                                                        <?= htmlspecialchars($lesson['title'] ?? 'Untitled Lesson') ?>
                                                    </a>
                                                <?php else: ?>
                                                    <i class="bi bi-lock-fill text-muted flex-shrink-0"></i>
                                                    <span class="text-muted text-truncate" style="font-size:.88rem;">
                                                        <?= htmlspecialchars($lesson['title'] ?? 'Untitled Lesson') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-muted flex-shrink-0 ms-3" style="font-size:.78rem;">
                                                <?= (int)($lesson['duration_minutes'] ?? 0) ?> min
                                            </span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Right column -->
            <div class="col-lg-4">
                <div class="position-sticky" style="top:calc(var(--topbar-h, 64px) + 1.5rem);">

                    <!-- Enrolment card -->
                    <div class="enrol-card mb-3">
                        <?php if ($course['is_enrolled']): ?>
                            <div class="text-center mb-3">
                                <i class="bi bi-check-circle-fill text-success" style="font-size:2.5rem;"></i>
                                <div class="fw-bold mt-2">You're enrolled!</div>
                                <div class="text-muted" style="font-size:.82rem;">
                                    Since <?= date('M j, Y', strtotime($course['enrolled_at'] ?? 'now')) ?>
                                </div>
                            </div>
                            <?php if ($course['progress_percentage'] > 0): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1" style="font-size:.82rem;">
                                    <span class="text-muted">Your progress</span>
                                    <span class="fw-semibold"><?= (int)$course['progress_percentage'] ?>%</span>
                                </div>
                                <div style="height:8px;background:#eef2ff;border-radius:4px;">
                                    <div style="width:<?= (int)$course['progress_percentage'] ?>%;height:100%;background:linear-gradient(90deg,#0b77ff,#6f8cff);border-radius:4px;"></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <a href="<?= $startLink ?>" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-play-fill me-1"></i><?= $startLabel ?>
                            </a>
                            <a href="../learning_dashboard.php" class="btn btn-outline-secondary w-100" style="font-size:.85rem;">
                                <i class="bi bi-speedometer2 me-1"></i>Back to Dashboard
                            </a>

                        <?php else: ?>
                            <div class="text-center mb-3">
                                <?php if ($course['is_free']): ?>
                                    <div class="fw-bold text-success" style="font-size:1.5rem;">FREE</div>
                                    <div class="text-muted" style="font-size:.82rem;">No payment required</div>
                                <?php else: ?>
                                    <div class="price">$<?= number_format($course['price'], 2) ?></div>
                                    <div class="text-muted" style="font-size:.82rem;">One-time payment</div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" class="mb-3">
                                <?php if ($course['is_free']): ?>
                                    <button type="submit" name="enroll" class="btn btn-success w-100 btn-lg">
                                        <i class="bi bi-plus-circle me-1"></i>Enrol for Free
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="enroll" class="btn btn-primary w-100 btn-lg">
                                        <i class="bi bi-credit-card me-1"></i>Enrol Now
                                    </button>
                                    <div class="text-center mt-2" style="font-size:.78rem;color:#6b7280;">
                                        <i class="bi bi-shield-check me-1"></i>30-day money-back guarantee
                                    </div>
                                <?php endif; ?>
                            </form>
                            <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i><?= (int)($course['total_lessons'] ?? 12) ?> lessons</li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i><?= (int)($course['total_hours'] ?? 8) ?> hours of content</li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i>Lifetime access</li>
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i>Certificate of completion</li>
                                <li class="d-flex gap-2 mb-0"><i class="bi bi-check-circle-fill text-success mt-1"></i>Mobile &amp; desktop access</li>
                            </ul>
                        <?php endif; ?>
                    </div>

                    <!-- Course features -->
                    <div class="detail-card" style="margin-bottom:0;">
                        <h6 class="fw-bold mb-3">Course Features</h6>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-laptop"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Online Learning</div><div class="text-muted" style="font-size:.78rem;">Access from anywhere, anytime</div></div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-infinity"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Lifetime Access</div><div class="text-muted" style="font-size:.78rem;">Learn at your own pace</div></div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon"><i class="bi bi-award"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Certificate</div><div class="text-muted" style="font-size:.78rem;">Earn on completion</div></div>
                        </div>
                        <div class="feature-item mb-0">
                            <div class="feature-icon"><i class="bi bi-headset"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Support</div><div class="text-muted" style="font-size:.78rem;">Direct access to instructors</div></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>

<?php include INCLUDES_PATH . '/adverts.php'; ?>
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
