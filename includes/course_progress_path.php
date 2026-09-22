<?php
/**
 * Course "Progress path" accordion -- one week open at a time, completion
 * shown on every week, mock weeks/classes tinted. Replaces the per-course
 * copies of the Bootstrap accordion on course_overview.php pages.
 *
 * Needs assets/css/progress_path.css and Bootstrap's JS (collapse) on the page.
 *
 * Usage (see courses/CELPIP_Gen_3Mo/course_overview.php):
 *   require_once INCLUDES_PATH . '/course_progress_path.php';
 *   echo renderProgressPath($modules, $completedLessonIds, [
 *       'folder'        => 'CELPIP_Gen_3Mo',   // for the ?from= query on class links
 *       'tier_level'    => $student_tier_level,
 *       'mock_weeks'    => [8, 11],            // module_order values
 *       'mock_classes'  => [15, 21],           // 1-based class numbers across the course
 *       'parts'         => progressPathLoadParts($db, $course_id, $student_id), // optional
 *       'class_number'  => fn($week, $lesson, $i) => ...,  // optional, default = running 1..N
 *       'is_mock'       => fn($week, $lesson, $num, $i) => bool, // optional, overrides mock_classes
 *       'class_url'     => fn($num, $lesson) => 'class' . $num . '.php', // optional, default = lesson file_path
 *       'week_brief'    => fn($weekNum, $color) => weekBriefButton(...),  // optional
 *   ]);
 *
 * $modules is the shape the overview pages already build: module_order =>
 * ['id','title','min_tier','lessons' => [lesson rows with lesson_id, title,
 * duration_minutes, min_tier, icon, file_path]].
 */

if (defined('COURSE_PROGRESS_PATH_LOADED')) return;
define('COURSE_PROGRESS_PATH_LOADED', true);

// Week colours: number/ring colour and, for mock weeks, the soft band tint.
const PP_PALETTE = [
    ['accent' => '#3d7a5a', 'tint' => '#e3f0e6'], // green
    ['accent' => '#3b5ba5', 'tint' => '#e4e9f5'], // blue
    ['accent' => '#2f7480', 'tint' => '#dcedf0'], // teal
    ['accent' => '#6a4c9c', 'tint' => '#ebe5f4'], // purple
];

/**
 * What sits inside each class: the class lesson itself is added by the
 * renderer; this loads the tests/quizzes attached to each lesson through
 * course_pacing_items, with this student's completion for each.
 * Returns lesson_id => [ ['title'=>, 'kind'=>'Quiz'|'Practice test', 'done'=>bool], ... ].
 * Looks tests up by code in separate queries (no string-column join), the
 * same pattern course_pacing.php uses -- avoids collation mismatches.
 */
require_once __DIR__ . '/lesson_title.php';

function progressPathLoadParts(PDO $db, int $courseId, int $studentId): array {
    $stmt = $db->prepare("SELECT lesson_id, title, item_type, test_code FROM course_pacing_items WHERE course_id = ? AND lesson_id IS NOT NULL ORDER BY display_order");
    $stmt->execute([$courseId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) return [];

    $codes = array_values(array_unique(array_column($items, 'test_code')));
    $ph = implode(',', array_fill(0, count($codes), '?'));
    $stmt = $db->prepare("SELECT id, code FROM tests WHERE code IN ($ph)");
    $stmt->execute($codes);
    $testIds = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $t) $testIds[$t['code']] = (int) $t['id'];

    $done = [];
    if ($testIds) {
        $ph = implode(',', array_fill(0, count($testIds), '?'));
        $stmt = $db->prepare("SELECT DISTINCT test_id FROM test_attempts WHERE student_id = ? AND status = 'completed' AND test_id IN ($ph)");
        $stmt->execute([$studentId, ...array_values($testIds)]);
        $done = array_flip(array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN)));
    }

    $parts = [];
    foreach ($items as $it) {
        $tid = $testIds[$it['test_code']] ?? null;
        if ($tid === null) continue; // content not seeded yet -- nothing to show or complete
        $parts[(int) $it['lesson_id']][] = [
            'title' => $it['title'],
            'kind'  => test_kind_label($it['test_code'], $it['item_type']),
            'done'  => isset($done[$tid]),
        ];
    }
    return $parts;
}

/** lesson ids this student has completed in a course (for pages that don't load them already). */
function progressPathLoadCompleted(PDO $db, int $courseId, int $studentId): array {
    try {
        $st = $db->prepare("SELECT lp.lesson_id FROM lesson_progress lp JOIN lessons l ON l.id = lp.lesson_id
                            JOIN modules m ON m.id = l.module_id WHERE lp.student_id = ? AND lp.completed = 1 AND m.course_id = ?");
        $st->execute([$studentId, $courseId]);
        return array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
    } catch (PDOException $e) {
        error_log('progressPathLoadCompleted: ' . $e->getMessage());
        return [];
    }
}

function renderProgressPath(array $modules, array $completedLessonIds, array $opts = []): string {
    $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    $folder      = $opts['folder'] ?? '';
    $tierLevel   = (int) ($opts['tier_level'] ?? 1);
    $mockWeeks   = $opts['mock_weeks'] ?? [];
    $mockClasses = $opts['mock_classes'] ?? [];
    $briefFn     = $opts['week_brief'] ?? null;
    $partsByLesson = $opts['parts'] ?? [];
    $numFn       = $opts['class_number'] ?? null; // fn($weekNum, $lesson, $runningIndex): int
    $mockFn      = $opts['is_mock'] ?? null;      // fn($weekNum, $lesson, $classNum, $indexInWeek): bool (overrides mock_classes)
    $urlFn       = $opts['class_url'] ?? null;    // fn($classNum, $lesson): ?string  (default: lesson file_path)
    $levels      = ['beginner' => 1, 'intermediate' => 2, 'advanced' => 3, 'fluent' => 4];
    $doneSet     = array_flip(array_map('intval', $completedLessonIds));

    // Pass 1: number every class, tally completion, find the current week and next class.
    $n = 0; $totalClasses = 0; $totalDone = 0;
    $weeks = []; $currentWeek = null; $next = null;
    foreach ($modules as $weekNum => $mod) {
        $classes = []; $done = 0;
        foreach ($mod['lessons'] as $lesson) {
            $n++;
            $lid = (int) ($lesson['lesson_id'] ?? $lesson['id'] ?? 0);
            $num = $numFn ? (int) $numFn($weekNum, $lesson, $n) : $n;
            if ($urlFn)                              $url = $urlFn($num, $lesson);
            elseif (!empty($lesson['file_path']))    $url = ACADEMY_URL . $lesson['file_path'] . (str_contains($lesson['file_path'], '?') ? '&' : '?') . 'from=' . $folder;
            else                                     $url = null;
            $url    = ($url && ($levels[$lesson['min_tier']] ?? 1) <= $tierLevel) ? $url : null; // null = locked
            $isDone = isset($doneSet[$lid]);
            if ($isDone) $done++;
            $classes[] = ['num' => $num, 'lesson' => $lesson, 'lid' => $lid, 'url' => $url, 'done' => $isDone];
            if (!$isDone && $next === null) $next = ['num' => $num, 'lesson' => $lesson, 'week' => $weekNum, 'url' => $url];
        }
        $weeks[$weekNum] = ['mod' => $mod, 'classes' => $classes, 'done' => $done, 'count' => count($classes)];
        $totalClasses += count($classes); $totalDone += $done;
        if ($currentWeek === null && $done < count($classes)) $currentWeek = $weekNum;
    }
    if ($currentWeek === null) $currentWeek = array_key_last($modules); // course finished: show the last week open

    $unit = stripos((string) (reset($modules)['title'] ?? ''), 'month') === 0 ? 'month' : 'week';
    $pct = $totalClasses ? round($totalDone / $totalClasses * 100) : 0;
    $out  = '<div class="pp-summary"><div class="pp-summary-title">' . $totalDone . ' of ' . $totalClasses . ' classes complete</div>';
    $out .= '<div class="pp-bar" role="progressbar" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"><span style="width:' . $pct . '%"></span></div>';
    if ($next) {
        $label = lesson_title_html($next['lesson']['title'], $h);
        if ($next['url']) $label = '<a href="' . $h($next['url']) . '">' . $label . '</a>';
        $out .= '<div class="pp-next">Up next: Class ' . (int) $next['num'] . ', ' . $unit . ' ' . (int) $next['week'] . $label . '</div>';
    } else {
        $out .= '<div class="pp-next">All classes complete. Well done!</div>';
    }
    $out .= '</div><div id="progressPath">';

    // Pass 2: render each week.
    $i = 0;
    foreach ($weeks as $weekNum => $w) {
        $palette  = PP_PALETTE[$i % count(PP_PALETTE)]; $i++;
        $isMock   = in_array($weekNum, $mockWeeks, true);
        $isOpen   = ($weekNum === $currentWeek);
        $complete = $w['count'] > 0 && $w['done'] === $w['count'];
        $ringPct  = $w['count'] ? round($w['done'] / $w['count'] * 100) : 0;
        $cid      = 'pp-week-' . (int) $weekNum;
        $style    = '--accent:' . $palette['accent'] . ';--tint:' . $palette['tint'] . ';--accent-week:' . $palette['accent'] . ';';

        $out .= '<div class="pp-week' . ($isOpen ? ' is-open' : '') . ($isMock ? ' is-mock' : '') . '" style="' . $style . '">';
        $out .= '<button class="pp-toggle' . ($isOpen ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $cid . '" aria-expanded="' . ($isOpen ? 'true' : 'false') . '" aria-controls="' . $cid . '">';
        $out .= '<div class="pp-ring' . ($complete ? ' is-done' : '') . '" style="--p:' . $ringPct . ';--c:' . ($complete ? '#3d7a5a' : $palette['accent']) . ';"><span>'
              . ($complete ? '<i class="bi bi-check-lg"></i>' : (int) $weekNum) . '</span></div>';
        $out .= '<div class="pp-head"><div class="pp-head-title">' . $h($w['mod']['title'])
              . ($isMock ? '<span class="pp-mock-tag">Mock exam ' . $unit . '</span>' : '') . '</div>';
        $out .= '<div class="pp-head-sub">' . $w['done'] . ' of ' . $w['count'] . ' classes done</div></div>';
        $out .= '<i class="bi bi-chevron-down pp-chev"></i></button>';

        $out .= '<div id="' . $cid . '" class="collapse' . ($isOpen ? ' show' : '') . '" data-bs-parent="#progressPath"><div class="pp-body">';
        if (is_callable($briefFn)) $out .= '<div class="pp-brief">' . $briefFn((int) $weekNum, $palette['accent']) . '</div>';

        $bodyId = 'pp-classes-' . (int) $weekNum;
        $out .= '<div id="' . $bodyId . '">';
        foreach ($w['classes'] as $ci => $c) {
            $lesson   = $c['lesson'];
            $lid      = $c['lid'];
            $required = $levels[$lesson['min_tier']] ?? 1;
            $can      = $c['url'] !== null;
            $isMockCl = $mockFn ? (bool) $mockFn($weekNum, $lesson, $c['num'], $ci) : in_array($c['num'], $mockClasses, true);
            $isCurCl  = $next && $next['num'] === $c['num'];

            // Parts: the class lesson first, then the tests/quizzes attached to it.
            // Every piece of the class (the stored title joins them with " + ") gets its own line.
            $parts = [];
            foreach (lesson_title_lines($lesson['title']) as $piece) {
                $parts[] = ['title' => $piece, 'kind' => 'Class lesson', 'meta' => '', 'done' => $c['done'], 'lesson' => true];
            }
            foreach ($partsByLesson[$lid] ?? [] as $pt) $parts[] = $pt + ['meta' => '', 'lesson' => false];
            $partsDone = count(array_filter($parts, fn($p) => $p['done']));
            $cid2 = 'pp-class-' . $lid;

            $out .= '<div class="pp-class' . ($can ? '' : ' is-locked') . ($isMockCl ? ' is-mock' : '') . '">';
            $out .= '<button class="pp-class-toggle' . ($isCurCl ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $cid2 . '" aria-expanded="' . ($isCurCl ? 'true' : 'false') . '" aria-controls="' . $cid2 . '">';
            $out .= '<span class="pp-class-title">Class ' . $c['num'] . '</span>';
            $out .= '<span class="pp-class-meta">';
            if ($isMockCl) $out .= '<span class="pp-pill mock">Mock exam</span>';
            elseif ($required === 1) $out .= '<span class="pp-pill free">Free</span>';
            if (!$can) $out .= '<i class="bi bi-lock-fill"></i>';
            $out .= (int) $lesson['duration_minutes'] > 0 ? '<span>' . (int) $lesson['duration_minutes'] . ' min</span>' : '';
            $out .= '<span>' . $partsDone . ' of ' . count($parts) . ' done</span><i class="bi bi-chevron-down pp-chev"></i></span></button>';

            $out .= '<div id="' . $cid2 . '" class="collapse' . ($isCurCl ? ' show' : '') . '" data-bs-parent="#' . $bodyId . '"><ul class="pp-parts">';
            foreach ($parts as $pt) {
                $ico  = $pt['done'] ? '<i class="bi bi-check-circle-fill pp-part-ico is-done"></i>' : '<span class="pp-part-ico is-todo"></span>';
                $text = '<span class="pp-part-text"><span class="pp-part-title' . ($pt['done'] ? ' is-done' : '') . '">' . $h($pt['title']) . '</span>'
                      . '<span class="pp-part-sub">' . $h($pt['kind'] . ($pt['meta'] !== '' ? ', ' . $pt['meta'] : '')) . '</span></span>';
                $inner = $ico . $text;
                $out  .= '<li>' . (($pt['lesson'] && $can)
                    ? '<a class="pp-part" href="' . $h($c['url']) . '">' . $inner . '</a>'
                    : '<span class="pp-part">' . $inner . '</span>') . '</li>';
            }
            $out .= '</ul></div></div>';
        }
        $out .= '</div>';
        $out .= '</div></div></div>';
    }
    return $out . '</div>';
}
