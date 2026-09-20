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

function renderProgressPath(array $modules, array $completedLessonIds, array $opts = []): string {
    $h = fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
    $folder      = $opts['folder'] ?? '';
    $tierLevel   = (int) ($opts['tier_level'] ?? 1);
    $mockWeeks   = $opts['mock_weeks'] ?? [];
    $mockClasses = $opts['mock_classes'] ?? [];
    $briefFn     = $opts['week_brief'] ?? null;
    $levels      = ['beginner' => 1, 'intermediate' => 2, 'advanced' => 3, 'fluent' => 4];
    $doneSet     = array_flip(array_map('intval', $completedLessonIds));

    // Pass 1: number every class, tally completion, find the current week and next class.
    $n = 0; $totalClasses = 0; $totalDone = 0;
    $weeks = []; $currentWeek = null; $next = null;
    foreach ($modules as $weekNum => $mod) {
        $classes = []; $done = 0;
        foreach ($mod['lessons'] as $lesson) {
            $n++;
            $isDone = isset($doneSet[(int) $lesson['lesson_id']]);
            if ($isDone) $done++;
            $classes[] = ['num' => $n, 'lesson' => $lesson, 'done' => $isDone];
            if (!$isDone && $next === null) $next = ['num' => $n, 'lesson' => $lesson, 'week' => $weekNum];
        }
        $weeks[$weekNum] = ['mod' => $mod, 'classes' => $classes, 'done' => $done, 'count' => count($classes)];
        $totalClasses += count($classes); $totalDone += $done;
        if ($currentWeek === null && $done < count($classes)) $currentWeek = $weekNum;
    }
    if ($currentWeek === null) $currentWeek = array_key_last($modules); // course finished: show the last week open

    $pct = $totalClasses ? round($totalDone / $totalClasses * 100) : 0;
    $out  = '<div class="pp-summary"><div class="pp-summary-title">' . $totalDone . ' of ' . $totalClasses . ' classes complete</div>';
    $out .= '<div class="pp-bar" role="progressbar" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"><span style="width:' . $pct . '%"></span></div>';
    if ($next) {
        $nextFile = $next['lesson']['file_path'] ?? '';
        $nextOk   = ($levels[$next['lesson']['min_tier']] ?? 1) <= $tierLevel && $nextFile;
        $label    = 'Class ' . $next['num'] . ': ' . $h($next['lesson']['title']);
        if ($nextOk) $label = '<a href="' . $h(ACADEMY_URL . $nextFile) . '?from=' . $h($folder) . '">' . $label . '</a>';
        $out .= '<div class="pp-next">Up next: ' . $label . ' in week ' . (int) $next['week'] . '</div>';
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
              . ($isMock ? '<span class="pp-mock-tag">Mock exam week</span>' : '') . '</div>';
        $out .= '<div class="pp-head-sub">' . $w['done'] . ' of ' . $w['count'] . ' classes done</div></div>';
        $out .= '<i class="bi bi-chevron-down pp-chev"></i></button>';

        $out .= '<div id="' . $cid . '" class="collapse' . ($isOpen ? ' show' : '') . '" data-bs-parent="#progressPath"><div class="pp-body">';
        if (is_callable($briefFn)) $out .= '<div class="pp-brief">' . $briefFn((int) $weekNum, $palette['accent']) . '</div>';

        foreach ($w['classes'] as $c) {
            $lesson   = $c['lesson'];
            $required = $levels[$lesson['min_tier']] ?? 1;
            $file     = $lesson['file_path'] ?? '';
            $can      = $tierLevel >= $required && $file;
            $isMockCl = in_array($c['num'], $mockClasses, true);
            $icon     = $c['done'] ? 'bi-check-circle-fill' : ($can ? ($lesson['icon'] ?: 'bi-play-circle') : 'bi-lock-fill');
            $icoCls   = 'pp-class-ico' . ($c['done'] ? ' is-done' : ($can ? ' is-open' : ''));
            $title    = '<span class="pp-class-title"><span class="pp-class-num">Class ' . $c['num'] . ':</span> ' . $h($lesson['title']) . '</span>';
            $inner    = '<i class="bi ' . $h($icon) . ' ' . $icoCls . '"></i>' . $title;

            $out .= '<div class="pp-class' . ($can ? '' : ' is-locked') . ($isMockCl ? ' is-mock' : '') . '">';
            $out .= $can
                ? '<a class="pp-class-main" href="' . $h(ACADEMY_URL . $file) . '?from=' . $h($folder) . '">' . $inner . '</a>'
                : '<span class="pp-class-main">' . $inner . '</span>';
            $out .= '<div class="pp-class-meta">';
            if ($isMockCl) $out .= '<span class="pp-pill mock">Mock exam</span>';
            elseif ($required === 1) $out .= '<span class="pp-pill free">Free</span>';
            $out .= '<span><i class="bi bi-clock"></i> ' . (int) $lesson['duration_minutes'] . ' min</span></div></div>';
        }
        $out .= '</div></div></div>';
    }
    return $out . '</div>';
}
