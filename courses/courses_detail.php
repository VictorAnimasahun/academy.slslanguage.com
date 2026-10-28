<?php
require_once dirname(__DIR__) . '/bootstrap.php';
require_once INCLUDES_PATH . '/currency.php';
require_once INCLUDES_PATH . '/coming_soon.php';

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

// Selar-sold courses (courses.selar_months) are enrolled only through "Choose your
// course" on the dashboard after paying. Enforced here on the server, not just by
// hiding the button. Does nothing while FREE_ACCESS_FOR_ALL is true; never blocks
// staff/admins/testers. See config/selar_purchases.php.
require_once INCLUDES_PATH . '/tier_access.php';
$selarBlocked = false;
if (is_file(CONFIG_PATH . '/selar_purchases.php')) {
    require_once CONFIG_PATH . '/selar_purchases.php';
    $selarBlocked = selar_enrolment_blocked($course);
}

// Handle enrolment
$error_message = '';
$comingSoon = course_is_coming_soon($course);   // nothing to enrol into yet: no purchase, no enrolment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && $comingSoon) {
    $error_message = "This course is coming soon.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && !$course['is_enrolled'] && $selarBlocked) {
    $error_message = "This course is bought on Selar. After you pay, choose it from your dashboard.";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll']) && !$course['is_enrolled']) {
    try {
        $db->prepare("INSERT INTO enrollments (student_id, course_id, enrolled_at, progress_percentage)
                      VALUES (?, ?, NOW(), 0)")->execute([$user_id, $course_id]);

        require_once INCLUDES_PATH . '/course_pacing.php';
        generateAssignmentsForEnrollment($db, $user_id, $course_id);

        header("Location: courses_detail.php?id={$course_id}&enrolled=1");
        exit();
    } catch (PDOException $e) {
        $error_message = "Failed to enrol. Please try again.";
    }
}

// Student ratings (course_ratings, migration 129). Nothing is shown until someone has rated; only a student who is
// enrolled can rate, once per course (rating again updates it). Safe if the table is not there yet.
$ratingMessage = ''; $ratingCount = 0; $ratingAvg = null; $myRating = null;
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rate_course'])) {
        $stars = (int)($_POST['rating'] ?? 0);
        if (!$course['is_enrolled']) {
            $ratingMessage = 'Only students enrolled in this course can rate it.';
        } elseif ($stars < 1 || $stars > 5) {
            $ratingMessage = 'Choose a rating from 1 to 5 stars.';
        } else {
            $comment = trim((string)($_POST['comment'] ?? ''));
            $db->prepare("INSERT INTO course_ratings (course_id, student_id, rating, comment) VALUES (?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE rating = VALUES(rating), comment = VALUES(comment)")
               ->execute([$course_id, $user_id, $stars, $comment !== '' ? mb_substr($comment, 0, 1000) : null]);
            $ratingMessage = 'Thank you for rating this course.';
        }
    }
    $st = $db->prepare("SELECT COUNT(*), AVG(rating) FROM course_ratings WHERE course_id = ?");
    $st->execute([$course_id]);
    [$ratingCount, $ratingAvg] = $st->fetch(PDO::FETCH_NUM);
    $ratingCount = (int)$ratingCount;
    $st = $db->prepare("SELECT rating, comment FROM course_ratings WHERE course_id = ? AND student_id = ?");
    $st->execute([$course_id, $user_id]);
    $myRating = $st->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (PDOException $e) { /* course_ratings not created yet */ }

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
    // Break the reference: the page loops over $modules again with `as $i => $mod`, which otherwise overwrites the
    // LAST week with each week in turn -- live showed "Week 11" twice and no Week 12 (2026-10-21).
    unset($mod);
} catch (PDOException $e) {
    error_log("Modules/lessons error: " . $e->getMessage());
}

$learningPoints = json_decode($course['learning_points'] ?? '[]', true) ?: [];

// The real size of the course, from its classes (there is no total_hours column: the page used to fall back to "8 hours").
require_once INCLUDES_PATH . '/lesson_title.php';
require_once INCLUDES_PATH . '/course_shape.php';
$classCount = 0; $minutes = 0;
foreach ($modules as $m) foreach ($m['lessons'] as $l) { $classCount++; $minutes += (int) ($l['duration_minutes'] ?? 0); }
$hoursTotal = (int) round($minutes / 60);
$fmtLen = fn (int $min) => $min <= 0 ? '' : ($min % 60 === 0 ? ($min / 60) . ($min === 60 ? ' hour' : ' hours') : $min . ' min');
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
        /* Flush to the sidebar, topbar and advert panel already: the wrapper has no padding of its own on a page
           with a .content area (includes/navbar_styles.php), so the hero needs no negative margin -- the old
           -2rem pulled it, and its text, 2rem under the menu rail (2026-10-29). */
        .course-hero .breadcrumb-item a { color: rgba(255,255,255,.65); text-decoration: none; }
        .course-hero .breadcrumb-item a:hover { color: #fff; }
        .course-hero .breadcrumb-item.active,
        .course-hero .breadcrumb-separator { color: rgba(255,255,255,.5); }

        .hero-badge {
            display: inline-flex; align-items: center; gap: .3rem;
            padding: .35rem .85rem; border-radius: 3px;
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
            border-radius: 3px;
            box-shadow: 0 8px 32px rgba(15,23,42,.12);
            padding: 1.5rem; border:1px solid rgba(15,23,42,.12);
        }
        .enrol-card .price { font-size: 2rem; font-weight: 800; color: #0b77ff; line-height: 1; }

        /* Content cards */
        .detail-card {
            background: #fff;
            border-radius: 3px;
            box-shadow: 0 2px 12px rgba(15,23,42,.06);
            padding: 1.5rem;
            margin-bottom: 1.25rem; border:1px solid rgba(15,23,42,.12);
        }

        .feature-item { display: flex; align-items: flex-start; gap: .75rem; margin-bottom: 1rem; }
        .feature-icon {
            width: 36px; height: 36px; border-radius: 3px;
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
            <?php if ($comingSoon): ?>
                <span class="hero-badge hero-badge-paid"><i class="bi bi-hourglass-split"></i> Coming Soon</span>
            <?php elseif ($course['is_free']): ?>
                <span class="hero-badge hero-badge-free"><i class="bi bi-gift"></i> FREE</span>
            <?php else: ?>
                <span class="hero-badge hero-badge-paid"><i class="bi bi-tag"></i> <?= course_price_html($course) ?></span>
            <?php endif; ?>
            <?php if ($course['is_enrolled']): ?>
                <span class="hero-badge hero-badge-enrolled"><i class="bi bi-check-circle-fill"></i> Enrolled</span>
            <?php endif; ?>
        </div>

        <div class="d-flex flex-wrap gap-4">
            <span class="hero-stat"><i class="bi bi-play-circle me-1"></i><strong><?= $classCount ?: (int)($course['total_lessons'] ?? 0) ?></strong> classes</span>
            <?php if ($hoursTotal > 0): ?><span class="hero-stat"><i class="bi bi-clock me-1"></i><strong><?= $hoursTotal ?></strong> hours</span><?php endif; ?>
            <span class="hero-stat"><i class="bi bi-person me-1"></i><strong><?= htmlspecialchars($course['instructor_name'] ?? 'EduHub Team') ?></strong></span>
            <?php if ($ratingCount > 0): ?>
                <span class="hero-stat"><i class="bi bi-star-fill me-1"></i><strong><?= number_format((float)$ratingAvg, 1) ?></strong> (<?= $ratingCount ?> rating<?= $ratingCount === 1 ? '' : 's' ?>)</span>
            <?php endif; ?>
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
                        <div class="accordion-item border-0 mb-2" style="border-radius:3px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.06); border:1px solid rgba(15,23,42,.12);">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?= $i > 0 ? 'collapsed' : '' ?> fw-semibold"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#mod<?= $i ?>"
                                        style="font-size:.9rem;">
                                    <i class="bi bi-folder2 me-2 text-primary"></i>
                                    <?= htmlspecialchars($mod['module_title'] ?? 'Module ' . ($i+1)) ?>
                                    <span class="badge bg-light text-dark ms-auto me-2" style="font-size:.73rem;">
                                        <?= count($mod['lessons']) ?> <?= count($mod['lessons']) === 1 ? 'class' : 'classes' ?>
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
                                                    // The class opens on its first page (with ?from= for shared pages); each item on its own line.
                                                    $lUrl = !empty($lesson['file_path']) ? course_item_url($lesson['file_path'], $course['folder_name']) : null;
                                                    ?>
                                                    <i class="bi bi-<?= htmlspecialchars($lesson['icon'] ?? 'play-circle') ?> text-primary flex-shrink-0"></i>
                                                    <?php if ($lUrl): ?><a href="<?= htmlspecialchars($lUrl) ?>" class="text-decoration-none text-dark" style="font-size:.88rem;min-width:0;"><?php else: ?><span class="text-dark" style="font-size:.88rem;min-width:0;"><?php endif; ?>
                                                        <?= lesson_title_html($lesson['title'] ?? '', 'htmlspecialchars') ?>
                                                    <?php if ($lUrl): ?></a><?php else: ?></span><?php endif; ?>
                                                <?php else: ?>
                                                    <i class="bi bi-lock-fill text-muted flex-shrink-0"></i>
                                                    <span class="text-muted" style="font-size:.88rem;min-width:0;">
                                                        <?= lesson_title_html($lesson['title'] ?? '', 'htmlspecialchars') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-muted flex-shrink-0 ms-3" style="font-size:.78rem;">
                                                <?= htmlspecialchars($fmtLen((int)($lesson['duration_minutes'] ?? 0))) ?>
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
                        <?php if ($comingSoon): ?>
                            <div class="text-center"><?= coming_soon_box('course') ?></div>
                        <?php elseif ($course['is_enrolled']): ?>
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
                                <div style="height:8px;background:#eef2ff;border-radius:3px;">
                                    <div style="width:<?= (int)$course['progress_percentage'] ?>%;height:100%;background:linear-gradient(90deg,#0b77ff,#6f8cff);border-radius:3px;"></div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <a href="<?= $startLink ?>" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-play-fill me-1"></i><?= $startLabel ?>
                            </a>
                            <a href="course_schedule.php?id=<?= $course_id ?>" class="btn btn-outline-primary w-100 mb-2" style="font-size:.85rem;">
                                <i class="bi bi-calendar-check me-1"></i>View Full Schedule
                            </a>
                            <a href="../learning_dashboard.php" class="btn btn-outline-secondary w-100" style="font-size:.85rem;">
                                <i class="bi bi-speedometer2 me-1"></i>Back to Dashboard
                            </a>
                            <hr class="my-3">
                            <form method="POST" id="rate">
                                <div class="fw-semibold mb-2" style="font-size:.9rem;"><?= $myRating ? 'Your rating' : 'Rate this course' ?></div>
                                <?php if ($ratingMessage): ?><div class="alert alert-info py-2 mb-2" style="font-size:.82rem;"><?= htmlspecialchars($ratingMessage) ?></div><?php endif; ?>
                                <select name="rating" class="form-select form-select-sm mb-2" required>
                                    <option value="">Choose 1–5 stars…</option>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= $myRating && (int)$myRating['rating'] === $i ? 'selected' : '' ?>><?= str_repeat('★', $i) . str_repeat('☆', 5 - $i) ?> (<?= $i ?>)</option>
                                    <?php endfor; ?>
                                </select>
                                <textarea name="comment" rows="2" maxlength="1000" class="form-control form-control-sm mb-2" placeholder="Optional comment"><?= htmlspecialchars($myRating['comment'] ?? '') ?></textarea>
                                <button type="submit" name="rate_course" value="1" class="btn btn-outline-primary btn-sm w-100"><?= $myRating ? 'Update rating' : 'Submit rating' ?></button>
                            </form>

                        <?php else: ?>
                            <div class="text-center mb-3">
                                <?php if ($course['is_free']): ?>
                                    <div class="fw-bold text-success" style="font-size:1.5rem;">FREE</div>
                                    <div class="text-muted" style="font-size:.82rem;">No payment required</div>
                                <?php else: ?>
                                    <div class="price"><?= course_price_html($course) ?></div>
                                    <div class="mt-2"><?= currency_switcher_html() ?></div>
                                    <div class="text-muted" style="font-size:.82rem;">One-time payment</div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($course['buy_url'])): ?>
                                <a href="<?= htmlspecialchars($course['buy_url']) ?>" target="_blank" rel="noopener"
                                   class="btn btn-success w-100 btn-lg mb-3">
                                    <i class="bi bi-cart me-1"></i>Buy on Selar
                                </a>
                            <?php endif; ?>
                            <?php if ($selarBlocked): ?>
                                <p class="text-muted text-center mb-3" style="font-size:.85rem;">Buy this course on Selar, then choose it from your dashboard.</p>
                            <?php else: ?>
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
                            <?php endif; ?>
                            <ul class="list-unstyled mb-0" style="font-size:.85rem;">
                                <li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i><?= $classCount ?: (int)($course['total_lessons'] ?? 0) ?> classes</li>
                                <?php if ($hoursTotal > 0): ?><li class="d-flex gap-2 mb-2"><i class="bi bi-check-circle-fill text-success mt-1"></i><?= $hoursTotal ?> hours of classes</li><?php endif; ?>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>   <!-- the week accordion and the alert's close button need it -->
<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
