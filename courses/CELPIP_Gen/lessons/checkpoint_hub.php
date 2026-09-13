<?php
// /academy/courses/CELPIP_Gen/lessons/checkpoint_hub.php
// One generic "complete test checkpoint" page reused for every paired
// Listening+Speaking / Reading+Writing checkpoint class in the CELPIP
// 3-Month course's 12-week schedule (course_id=14), instead of writing
// near-identical files for each of the 8 checkpoint classes. Selected via
// ?slot=c7 (etc.) -- see $SLOTS below. Real, already-built practice tests
// are linked directly; skills with no complete test built yet (Listening
// PT1-4, and the Reading/Writing/Speaking PT4 slot) show an honest
// "not built yet" card instead of a dead or fake link.
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

// Each slot: page title, week label, and two skill cards. A card with
// 'link' set is real and clickable; a card with 'link' => null renders as
// "not built yet" instead.
$SLOTS = [
    'c7'  => ['title' => 'Checkpoint 1 — Complete Listening + Speaking Test', 'week' => 'Week 4',
        'skills' => [
            ['label' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
            ['label' => 'Speaking',  'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_001.php'],
        ]],
    'c8'  => ['title' => 'Checkpoint 1 — Complete Reading + Writing Test', 'week' => 'Week 4',
        'skills' => [
            ['label' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_001.php'],
            ['label' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
                ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_001.php'],
                ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_001.php'],
            ]],
        ]],
    'c11' => ['title' => 'Checkpoint 2 — Complete Listening + Speaking Test', 'week' => 'Week 6',
        'skills' => [
            ['label' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
            ['label' => 'Speaking',  'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_002.php'],
        ]],
    'c12' => ['title' => 'Checkpoint 2 — Complete Reading + Writing Test', 'week' => 'Week 6',
        'skills' => [
            ['label' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_002.php'],
            ['label' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
                ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_002.php'],
                ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_002.php'],
            ]],
        ]],
    'c17' => ['title' => 'Checkpoint 3 — Complete Listening + Speaking Test', 'week' => 'Week 9',
        'skills' => [
            ['label' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
            ['label' => 'Speaking',  'sub' => 'All 8 tasks, recorded + transcribed', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_speaking_003.php'],
        ]],
    'c18' => ['title' => 'Checkpoint 3 — Complete Reading + Writing Test', 'week' => 'Week 9',
        'skills' => [
            ['label' => 'Reading', 'sub' => 'All 4 parts', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_reading_003.php'],
            ['label' => 'Writing', 'sub' => 'Task 1 (Email) + Task 2 (Survey Response)', 'link' => null, 'dual' => [
                ['name' => 'Task 1 — Email', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t1_003.php'],
                ['name' => 'Task 2 — Survey Response', 'link' => ACADEMY_URL . 'resources/practice_tests/celpip_writing_t2_003.php'],
            ]],
        ]],
    'c19' => ['title' => 'Checkpoint 4 — Complete Listening + Speaking Test', 'week' => 'Week 10',
        'skills' => [
            ['label' => 'Listening', 'sub' => 'Full 6-part test, real time limits', 'link' => null],
            ['label' => 'Speaking',  'sub' => 'All 8 tasks — Practice Test 4', 'link' => null],
        ]],
    'c20' => ['title' => 'Checkpoint 4 — Complete Reading + Writing Test', 'week' => 'Week 10',
        'skills' => [
            ['label' => 'Reading', 'sub' => 'All 4 parts — Practice Test 4', 'link' => null],
            ['label' => 'Writing', 'sub' => 'Both tasks — Practice Test 4', 'link' => null],
        ]],
];

$slot = $_GET['slot'] ?? '';
if (!isset($SLOTS[$slot])) {
    die("Unknown checkpoint slot.");
}
$cfg = $SLOTS[$slot];

if (!can_access('intermediate')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CELPIP Masterclass — <?= htmlspecialchars($cfg['title']) ?></title>
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
                <?php render_upgrade_prompt('intermediate', $cfg['title']); ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CELPIP Masterclass — <?= htmlspecialchars($cfg['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../../../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .hub-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:1.25rem; margin-top:1.5rem; }
        .hub-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; padding:1.75rem; }
        .hub-card h3 { font-size:1.15rem; font-weight:700; margin-bottom:.25rem; }
        .hub-card .sub { color:#64748b; font-size:.88rem; margin-bottom:1.25rem; }
        .hub-not-built { background:#fef3c7; border:1px dashed #fcd34d; border-radius:10px; padding:1rem 1.25rem; color:#92400e; font-size:.9rem; }
        .hub-dual-links { display:flex; flex-direction:column; gap:.6rem; }
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
                    <li class="breadcrumb-item"><a href="../../courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= htmlspecialchars($back['url']) ?>" class="text-decoration-none"><?= htmlspecialchars($back['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($cfg['title']) ?></li>
                </ol>
            </nav>

            <h1 class="mb-2"><i class="bi bi-flag-fill me-2" style="color:#0b77ff;"></i><?= htmlspecialchars($cfg['title']) ?></h1>
            <p class="text-muted"><?= htmlspecialchars($cfg['week']) ?> of the 3-Month Masterclass — sit both complete tests below under real timed conditions, one right after the other.</p>

            <div class="hub-grid">
                <?php foreach ($cfg['skills'] as $skill): ?>
                    <div class="hub-card">
                        <h3><?= htmlspecialchars($skill['label']) ?></h3>
                        <div class="sub"><?= htmlspecialchars($skill['sub']) ?></div>
                        <?php if (!empty($skill['dual'])): ?>
                            <div class="hub-dual-links">
                                <?php foreach ($skill['dual'] as $d): ?>
                                    <?php if ($d['link']): ?>
                                        <a href="<?= htmlspecialchars($d['link']) ?>" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;"><?= htmlspecialchars($d['name']) ?> <i class="bi bi-arrow-right ms-1"></i></a>
                                    <?php else: ?>
                                        <div class="hub-not-built"><i class="bi bi-hourglass-split me-2"></i><?= htmlspecialchars($d['name']) ?> — not built yet</div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($skill['link']): ?>
                            <a href="<?= htmlspecialchars($skill['link']) ?>" class="btn btn-primary btn-lg w-100" style="background-color:#0b77ff;border-color:#0b77ff;">Start <?= htmlspecialchars($skill['label']) ?> Test <i class="bi bi-arrow-right ms-1"></i></a>
                        <?php else: ?>
                            <div class="hub-not-built"><i class="bi bi-hourglass-split me-2"></i>This complete <?= htmlspecialchars($skill['label']) ?> test hasn't been built yet. This checkpoint is wired into the schedule as a placeholder so the 12-week structure is complete — check back once it's added, or ask your instructor for interim material.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-top:2rem;">
                <a href="<?= htmlspecialchars($back['url']) ?>" class="btn btn-outline-primary"><i class="bi bi-grid me-1"></i> Course Overview</a>
            </div>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
