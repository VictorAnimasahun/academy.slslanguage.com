<?php
// Class 1, item 1 of the CELPIP General 1/2/3-month courses: the course introduction. (The file keeps its
// old name so existing links still work; until 2026-10-19 it was mislabelled "Foundational English
// Assessment", which is the separate test that follows it.) Everything course-specific comes from the
// database (course_context.php -> includes/course_shape.php), never typed in.
require_once (dirname(dirname(dirname(__DIR__)))) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once __DIR__ . '/course_context.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

// The plan level of the class this page sits in, in the course it was opened from (Class 1 is the free preview).
// It used to require Intermediate everywhere, so Class 1 of the 2/3-month courses was locked (found 2026-10-26).
require_once INCLUDES_PATH . '/lesson_title.php';
$pagePos = course_item_position($db, $ctxCourse, 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php') ?? ['class' => 1];
$pageTier = lesson_min_tier($db, $ctxCourse['folder'], (int) $pagePos['class'], 'beginner');
if (!can_access($pageTier)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>CELPIP — Course Introduction</title>
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
                <?php render_upgrade_prompt($pageTier, 'Course Introduction'); ?>
            </div>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit();
}
?>
<?php
$schedule = course_schedule($db, $ctxCourse['id']);
$next = next_course_item($db, $ctxCourse, 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php');
$isTest = fn (array $i) => content_type_is_test($i['kind']);
// Only talk about the opening tests this course actually has.
$has = ['fea' => false, 'diag' => false];
foreach ($schedule as $w) foreach ($w['classes'] as $c) foreach ($c['items'] as $i) {
    if ($i['title'] === 'Foundational English Assessment') $has['fea'] = true;
    if ($i['title'] === 'CELPIP Diagnostic Test') $has['diag'] = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($ctxCourse['title']) ?> — Course Introduction</title>
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
                    <li class="breadcrumb-item active" aria-current="page">Course Introduction</li>
                </ol>
            </nav>

            <h1 class="mb-3"><i class="bi bi-compass me-2" style="color:#0b77ff;"></i>Course Introduction</h1>

            <div class="highlight-box">
                <p class="mb-0"><strong>Week:</strong> 1 &nbsp;|&nbsp; <strong>Class:</strong> 1 of <?= (int) $ctxCourse['classes'] ?></p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-map me-2" style="color:#0b77ff;"></i>Welcome to the <?= htmlspecialchars($ctxCourse['title']) ?></h2>
                <?php if ($has['fea'] || $has['diag']): ?>
                <p>
                    Before any CELPIP-specific content, this course first finds out where you're actually starting
                    from in general English: not the exam format, your underlying language level. The exam format
                    can be learned in a few weeks; vocabulary range, grammar accuracy and listening speed take longer,
                    and knowing your starting point now shapes how the next <?= (int) $ctxCourse['weeks'] ?> weeks should be spent.
                </p>
                <p class="mb-0">That's why the course opens with:</p>
                <ul class="custom-list">
                    <?php if ($has['fea']): ?><li><strong>Foundational English Assessment</strong>: your general English level. If it shows you need more English first, we'll tell you before exam strategy starts.</li><?php endif; ?>
                    <?php if ($has['diag']): ?><li><strong>CELPIP Diagnostic Test</strong> (the "Mini Mock"): your CELPIP level today, in all four skills.</li><?php endif; ?>
                </ul>
                <p class="text-muted small mb-0">Tests are taken on a computer, not on your phone, so that conditions match the real exam.</p>
                <?php else: ?>
                <p class="mb-0">This class sets you up for the next <?= (int) $ctxCourse['weeks'] ?> weeks: how the course runs, where you're starting from, and the CLB level you're aiming for.</p>
                <?php endif; ?>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-clipboard-data me-2" style="color:#0b77ff;"></i>Your Plan</h2>
                <p class="mb-0"><?= htmlspecialchars(course_plan_paragraph($db, $ctxCourse)) ?> <a href="<?= htmlspecialchars($back['url']) ?>">See every class on the course page</a>.</p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-person-check me-2" style="color:#0b77ff;"></i>Your Self-Assessment</h2>
                <p>Before your first test, answer these honestly. There's no scoring here, only your own baseline:</p>
                <ol class="custom-list">
                    <li>How comfortable are you having an unscripted conversation in English for 5+ minutes on an everyday topic?</li>
                    <li>When reading a page of English text you haven't seen before, how much do you understand without a dictionary?</li>
                    <li>When writing an email in English, how long does composing 150 words typically take you?</li>
                    <li>When listening to English spoken at a natural, non-slowed pace, what percentage do you feel you follow?</li>
                </ol>
                <p class="mb-0">
                    Your answers, set against your test results, show
                    your instructor whether a gap is a <em>confidence</em> problem (you know more than you think) or a real
                    <em>skill</em> gap that needs English work before exam strategy will help.
                </p>
            </div>

            <div class="content-section">
                <h2><i class="bi bi-bullseye me-2" style="color:#0b77ff;"></i>Setting Your Target CLB</h2>
                <p class="mb-0">
                    Know your target Canadian Language Benchmark (CLB) level before you start. Most permanent residence
                    and citizenship pathways require specific CLB levels per skill, and knowing yours means every test
                    result can be read against a real target, not just "higher is better". Write down your target CLB
                    per skill and your self-assessment answers, and keep them for your instructor.
                </p>
            </div>

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
