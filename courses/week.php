<?php
// /academy/courses/week.php?module=ID
// Week introduction -- the full, broken-down version of a course week's brief.
// One generic page for EVERY course: the course is derived from the module, so
// nothing here is course-specific. The right pane shows the same week abridged
// (renderWeekPanel) with links back to the sections below.
require_once dirname(__DIR__) . '/bootstrap.php';
require_once INCLUDES_PATH . '/tier_access.php';
require_once INCLUDES_PATH . '/week_brief.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../edu_hub_registration.php?message=Please+login+to+access+courses");
    exit();
}
$student_id = (int)$_SESSION['user_id'];
$moduleId   = (int)($_GET['module'] ?? 0);

$brief = $moduleId ? weekBriefLoad($db, $moduleId) : null;
if (!$brief) { header("Location: courses_catalogue.php?message=Week+not+found"); exit(); }

$stmt = $db->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([(int)$brief['module']['course_id']]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$course) { header("Location: courses_catalogue.php?message=Course+not+found"); exit(); }
$courseId = (int)$course['id'];
$folder   = $course['folder_name'];
$overview = $folder . '/course_overview.php?id=' . $courseId;

// Week locking ("finish last week first") will be enforced in weekIsUnlocked().
if (!weekIsUnlocked($db, $courseId, $student_id, $moduleId)) {
    header("Location: " . $overview . "&message=Finish+the+previous+week+first");
    exit();
}

$mods = weekModules($db, $courseId);
$pos = 0; foreach ($mods as $i => $m) if ((int)$m['id'] === $moduleId) { $pos = $i; break; }
$prev = $mods[$pos - 1] ?? null;
$next = $mods[$pos + 1] ?? null;

$stmt = $db->prepare("SELECT lesson_id FROM lesson_progress WHERE student_id = ? AND completed = 1");
$stmt->execute([$student_id]);
$done = array_flip(array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN)));
$tier = get_student_tier_level();

$stmt = $db->prepare("SELECT id, title, file_path, min_tier, duration_minutes, icon FROM lessons WHERE module_id = ? ORDER BY lesson_order");
$stmt->execute([$moduleId]);
$lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

$h = fn($x) => htmlspecialchars((string)$x, ENT_QUOTES, 'UTF-8');
$typeLabels = WEEK_BRIEF_TYPE_LABELS;
$color = '#0b77ff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $h($brief['module']['module_title']) ?> — <?= $h($course['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .wk-section { scroll-margin-top: 90px; margin-bottom: 2rem; }
        .wk-section h2 { font-size: 1.15rem; border-bottom: 2px solid #e2e8f0; padding-bottom: .4rem; margin-bottom: .9rem; }
        .wk-class { display:flex; justify-content:space-between; align-items:center; gap:.75rem; padding:.7rem .9rem; border:1px solid #e2e8f0; border-radius:10px; margin-bottom:.5rem; text-decoration:none; color:inherit; background:#fff; }
        .wk-class:hover { border-color:<?= $color ?>; }
        .wk-vocab { width:100%; border-collapse:collapse; font-size:.92rem; }
        .wk-vocab th, .wk-vocab td { padding:.55rem .6rem; border-bottom:1px solid #eef2f7; text-align:left; vertical-align:top; }
        .wk-vocab th { font-size:.72rem; text-transform:uppercase; letter-spacing:.05em; color:#64748b; }
        .wk-tone { background:#f0f9ff; border-left:4px solid <?= $color ?>; padding:.75rem 1rem; border-radius:6px; font-weight:600; }
        .wk-weeks a { display:inline-block; margin:0 .3rem .4rem 0; padding:.25rem .7rem; border-radius:999px; border:1px solid #cbd5e1; font-size:.8rem; text-decoration:none; color:#334155; }
        .wk-weeks a.current { background:<?= $color ?>; color:#fff; border-color:<?= $color ?>; }
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
                    <li class="breadcrumb-item"><a href="courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= $h($overview) ?>" class="text-decoration-none"><?= $h($course['title']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Week <?= $pos + 1 ?></li>
                </ol>
            </nav>

            <div class="text-muted small text-uppercase" style="letter-spacing:.06em;">Week introduction &middot; <?= $pos + 1 ?> of <?= count($mods) ?></div>
            <h1 class="mb-3"><?= $h($brief['module']['module_title']) ?></h1>

            <div class="wk-weeks mb-4">
                <?php foreach ($mods as $i => $m): ?>
                    <a href="week.php?module=<?= (int)$m['id'] ?>" class="<?= (int)$m['id'] === $moduleId ? 'current' : '' ?>">Week <?= $i + 1 ?></a>
                <?php endforeach; ?>
            </div>

            <section class="wk-section" id="summary">
                <h2><i class="bi bi-compass me-2"></i>What this week is about</h2>
                <?php if ($brief['summary'] !== ''): ?>
                    <p style="white-space:pre-line;"><?= $h($brief['summary']) ?></p>
                <?php else: ?>
                    <p class="text-muted mb-0">Your instructor hasn't written the overview for this week yet — the classes below show what's planned.</p>
                <?php endif; ?>
            </section>

            <section class="wk-section" id="classes">
                <h2><i class="bi bi-play-circle me-2"></i>Your classes this week</h2>
                <?php if (!$lessons): ?>
                    <p class="text-muted mb-0">Classes for this week are still being prepared.</p>
                <?php endif; ?>
                <?php foreach ($lessons as $n => $l):
                    $need = ['beginner'=>1,'intermediate'=>2,'advanced'=>3,'fluent'=>4][$l['min_tier']] ?? 1;
                    $locked = $tier < $need;
                    $fp = (string)$l['file_path'];
                    $href = $locked ? '../upgrade.php?required=' . urlencode($l['min_tier'])
                          : ($fp === '' ? '#classes' : ACADEMY_URL . $fp . (str_contains($fp, '?') ? '&' : '?') . 'from=' . urlencode($folder));
                    $isDone = isset($done[(int)$l['id']]);
                ?>
                <a class="wk-class" href="<?= $h($href) ?>">
                    <span>
                        <?php if ($isDone): ?><i class="bi bi-check-circle-fill text-success me-2"></i>
                        <?php elseif ($locked): ?><i class="bi bi-lock-fill text-warning me-2"></i>
                        <?php else: ?><i class="bi <?= $h($l['icon'] ?: 'bi-play-circle') ?> me-2" style="color:<?= $color ?>;"></i><?php endif; ?>
                        <strong>Class <?= $n + 1 ?>:</strong> <?= $h($l['title']) ?>
                    </span>
                    <span class="text-muted small text-nowrap"><?= $locked ? 'Upgrade to unlock' : ((int)$l['duration_minutes'] ? (int)$l['duration_minutes'] . ' min' : '') ?></span>
                </a>
                <?php endforeach; ?>
            </section>

            <section class="wk-section" id="tests">
                <h2><i class="bi bi-clipboard-check me-2"></i>Tests &amp; quizzes</h2>
                <div class="wk-tone mb-3"><?= $h($brief['tests_message']) ?></div>
                <?php if ($brief['tests']): ?>
                    <ul class="mb-0">
                    <?php foreach ($brief['tests'] as $t): ?>
                        <li><?= $h($t['title']) ?> <span class="text-muted small">(<?= $h($typeLabels[$t['item_type']] ?? 'Test') ?>)</span></li>
                    <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <section class="wk-section" id="vocab">
                <h2><i class="bi bi-translate me-2"></i>Vocabulary sheet</h2>
                <?php if ($brief['vocab']): ?>
                    <p class="text-muted small">Try to use several of these words in this week's assignments and tests — that's how they move from your notes into your own English.</p>
                    <div class="table-responsive">
                    <table class="wk-vocab">
                        <thead><tr><th>Word</th><th>Meaning</th><th>Use it like</th></tr></thead>
                        <tbody>
                        <?php foreach ($brief['vocab'] as $w): ?>
                            <tr>
                                <td><strong><?= $h($w['headword']) ?></strong><br><span class="text-muted small"><?= $h($w['word_class']) ?><?= $w['phonetic'] ? ' · ' . $h($w['phonetic']) : '' ?></span></td>
                                <td><?= $h($w['definition']) ?></td>
                                <td class="small text-muted"><?= $h($w['collocations'] ?: ($w['synonyms'] ? 'Similar: ' . $w['synonyms'] : '')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">No vocabulary list for this week yet.</p>
                <?php endif; ?>
            </section>

            <section class="wk-section" id="resources">
                <h2><i class="bi bi-collection me-2"></i>Resources &amp; exercises</h2>
                <?php if ($brief['resources']): ?>
                    <ul class="mb-0">
                    <?php foreach ($brief['resources'] as $r): ?>
                        <li class="mb-1">
                            <?php if ($r['status'] === 'ready' && $r['url']): ?><a href="<?= $h($r['url']) ?>"><?= $h($r['title']) ?></a><?php else: ?><?= $h($r['title']) ?><?php endif; ?>
                            <span class="text-muted small">(<?= $h($typeLabels[$r['item_type']] ?? '') ?>)</span>
                            <?php if ($r['status'] === 'planned'): ?><span class="badge text-bg-secondary">Coming soon</span><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Nothing added for this week yet.</p>
                <?php endif; ?>
            </section>

            <div class="d-flex justify-content-between flex-wrap gap-2 mt-4">
                <?php if ($prev): ?><a class="btn btn-outline-secondary" href="week.php?module=<?= (int)$prev['id'] ?>"><i class="bi bi-arrow-left me-1"></i>Previous week</a><?php else: ?><span></span><?php endif; ?>
                <a class="btn btn-outline-secondary" href="<?= $h($overview) ?>">Course overview</a>
                <?php if ($next): ?><a class="btn btn-primary" href="week.php?module=<?= (int)$next['id'] ?>">Next week<i class="bi bi-arrow-right ms-1"></i></a><?php else: ?><span></span><?php endif; ?>
            </div>
        </div>
    </main>

    <aside class="advert-sidebar">
        <?= renderWeekPanel($db, $courseId, $student_id, $moduleId, $folder) ?>
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
