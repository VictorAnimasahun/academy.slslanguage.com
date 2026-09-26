<?php
/**
 * The page for ONE lesson piece of a class -- an empty shell until the lesson is designed.
 *
 * Each lesson piece has its own small file (courses/<course>/lessons/classNN_pK_<slug>.php) that only says
 * which course / class / piece it is and holds the lesson body between two markers. This file draws
 * everything around it (login, plan check, header with week/class/kind, "Coming Soon" notice while the
 * body is empty, back link), so changing the look of every lesson page is a one-file edit.
 *
 * To develop a lesson: open its file and put the HTML between  LESSON BODY START  and  LESSON BODY END.
 * Nothing else needs to change; while the body is empty students see "Coming Soon".
 *
 * Access is read from the database (lessons.min_tier for the class), not hard-coded here, so the plan
 * rule is not written down one more time.
 */
if (!function_exists('render_lesson_shell')) {

    /**
     * Everything a lesson page needs to know, WITHOUT drawing anything: who may see it, and which
     * course / week / class / piece it is. Redirects to login when nobody is logged in.
     * A lesson page that wants its own full design calls this and then writes its own HTML:
     *
     *     $ctx = lesson_context($db, 'IELTS_Aca_2Mo', 7, 2);
     *     if (!$ctx['can_access']) { echo lesson_locked_html($ctx); exit; }
     *     // ...your own <html> from here; $ctx['title'], ['kind'], ['week'], ['class'], ['back_url'] are ready.
     *
     * @return array{course:array,lesson:array,lessons:array,title:string,kind:string,week:int,class:int,class_total:int,min_tier:string,can_access:bool,back_url:string,back_label:string}
     */
    function lesson_context(PDO $db, string $folder, int $classNum, int $partOrder): array {
        require_once INCLUDES_PATH . '/tier_access.php';
        require_once INCLUDES_PATH . '/lesson_title.php';   // also loads coming_soon.php

        if (!isset($_SESSION['user_id'])) {
            header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login+to+access+courses");
            exit();
        }
        $st = $db->prepare("SELECT * FROM courses WHERE folder_name = ? ORDER BY is_visible DESC, id LIMIT 1");
        $st->execute([$folder]);
        $course = $st->fetch(PDO::FETCH_ASSOC);
        if (!$course) { header("Location: " . ACADEMY_URL . "courses/courses_catalogue.php?message=Course+not+found"); exit(); }

        $st = $db->prepare("SELECT l.*, m.module_order FROM lessons l JOIN modules m ON m.id = l.module_id
                            WHERE m.course_id = ? ORDER BY m.module_order, l.lesson_order");
        $st->execute([(int)$course['id']]);
        $lessons = $st->fetchAll(PDO::FETCH_ASSOC);
        $lesson  = $lessons[$classNum - 1] ?? null;
        if (!$lesson) { header("Location: " . ACADEMY_URL . "courses/" . $folder . "/course_overview.php?message=Lesson+not+found"); exit(); }

        $part = false;
        try {   // lesson_parts (migration 126) may not exist yet on a server that has the code but not the migration
            $st = $db->prepare("SELECT title, kind FROM lesson_parts WHERE lesson_id = ? AND part_order = ?");
            $st->execute([(int)$lesson['id'], $partOrder]);
            $part = $st->fetch(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) { /* fall back to the title and the title rule */ }
        $pieces = lesson_title_lines($lesson['title']);
        $title  = $part['title'] ?? ($pieces[$partOrder - 1] ?? $lesson['title']);

        $minTier = $lesson['min_tier'] ?: 'beginner';
        $classPageFs = dirname(__DIR__) . '/courses/' . $folder . '/class' . $classNum . '.php';
        $hasClassPage = is_file($classPageFs);
        return [
            'course' => $course, 'lesson' => $lesson, 'lessons' => $lessons, 'folder' => $folder,
            'title' => $title, 'kind' => $part['kind'] ?? lesson_piece_kind($title),
            'week' => (int)$lesson['module_order'], 'class' => $classNum, 'class_total' => count($lessons),
            'min_tier' => $minTier, 'can_access' => can_access($minTier),
            'back_url' => ACADEMY_URL . 'courses/' . $folder . '/' . ($hasClassPage ? 'class' . $classNum . '.php' : 'course_overview.php'),
            'back_label' => $hasClassPage ? 'Back to Class ' . $classNum : 'Back to course',
        ];
    }

    /** The standard "this class needs plan X" box, for pages that draw their own layout. */
    function lesson_locked_html(array $ctx): string {
        $h = fn($x) => htmlspecialchars((string)$x, ENT_QUOTES, 'UTF-8');
        return '<div class="highlight-box"><h4 style="color:var(--accent);"><i class="bi bi-lock-fill me-2"></i>Locked</h4>'
             . '<p class="mb-2">This class requires the <strong>' . $h(ucfirst($ctx['min_tier'])) . '</strong> plan.</p>'
             . '<a href="' . ACADEMY_URL . 'upgrade.php?required=' . $h($ctx['min_tier']) . '" class="btn btn-primary btn-sm"><i class="bi bi-lightning-charge me-1"></i>Upgrade to Access</a></div>';
    }

    /**
     * The default frame around a lesson body (same look as the class pages). Optional: a lesson page can skip this
     * entirely and use lesson_context() with its own HTML/CSS -- the frame never limits how a lesson is designed.
     */
    function render_lesson_shell(PDO $db, string $folder, int $classNum, int $partOrder, string $bodyHtml = ''): void {
        $ctx = lesson_context($db, $folder, $classNum, $partOrder);
        $h = fn($x) => htmlspecialchars((string)$x, ENT_QUOTES, 'UTF-8');
        [$course, $lesson, $lessons, $title, $kind, $minTier, $canAccess, $backUrl, $backLabel] =
            [$ctx['course'], $ctx['lesson'], $ctx['lessons'], $ctx['title'], $ctx['kind'], $ctx['min_tier'], $ctx['can_access'], $ctx['back_url'], $ctx['back_label']];
        // The LESSON BODY markers are HTML comments: they must not count as content.
        $hasBody      = trim(preg_replace('/<!--.*?-->/s', '', $bodyHtml)) !== '';
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $h($title) ?> — <?= $h($course['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= ACADEMY_URL ?>assets/css/courses.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .lesson-content { font-size: .95rem; line-height: 1.8; color: #1f2937; }
        .class-head { display: flex; gap: 1rem; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
        .class-head-icon { flex: none; width: 52px; height: 52px; border-radius: 12px; background: #e8f1ff; color: #0b77ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .class-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .55rem; }
        .class-chip { font-size: .72rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; background: #f1f5f9; color: #475569; border-radius: 999px; padding: .2rem .7rem; }
        .class-chip.kind { background: #e0f2fe; color: #075985; }
        .class-chip.kind.practice_test { background: #fef3c7; color: #92400e; }
        .class-chip.kind.mock_test { background: #fee2e2; color: #991b1b; }
        .class-title { font-size: 1.5rem; font-weight: 700; margin: 0; line-height: 1.3; }
        .lesson-nav { display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem; }
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
                    <li class="breadcrumb-item"><a href="<?= ACADEMY_URL ?>courses/courses_catalogue.php" class="text-decoration-none">Courses</a></li>
                    <li class="breadcrumb-item"><a href="<?= ACADEMY_URL ?>courses/<?= $h($folder) ?>/course_overview.php" class="text-decoration-none"><?= $h($course['title']) ?></a></li>
                    <li class="breadcrumb-item active"><?= $h($title) ?></li>
                </ol>
            </nav>

            <div class="class-head">
                <div class="class-head-icon"><i class="bi <?= $h($lesson['icon'] ?: 'bi-journal-text') ?>"></i></div>
                <div>
                    <div class="class-chips">
                        <span class="class-chip">Week <?= (int)$lesson['module_order'] ?></span>
                        <span class="class-chip">Class <?= (int)$classNum ?> of <?= count($lessons) ?></span>
                        <span class="class-chip kind <?= $h($kind) ?>"><?= $h(lesson_kind_label($kind)) ?></span>
                    </div>
                    <h1 class="class-title"><?= $h($title) ?></h1>
                </div>
            </div>

            <?php if (!$canAccess): ?>
                <div class="highlight-box">
                    <h4 style="color:var(--accent);"><i class="bi bi-lock-fill me-2"></i>Locked</h4>
                    <p class="mb-2">This class requires the <strong><?= $h(ucfirst($minTier)) ?></strong> plan.</p>
                    <a href="<?= ACADEMY_URL ?>upgrade.php?required=<?= $h($minTier) ?>" class="btn btn-primary btn-sm"><i class="bi bi-lightning-charge me-1"></i>Upgrade to Access</a>
                </div>
            <?php elseif ($hasBody): ?>
                <div class="lesson-content mb-4"><?= $bodyHtml ?></div>
            <?php else: ?>
                <?= coming_soon_box('lesson') ?>
            <?php endif; ?>

            <div class="lesson-nav">
                <a href="<?= $h($backUrl) ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i><?= $h($backLabel) ?></a>
            </div>
        </div>
    </main>

    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
        <?php
    }
}
