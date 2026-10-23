<?php
/**
 * Course content on the course_overview.php pages: a one-line progress summary, then a list of weeks on the left
 * and the chosen week's classes on the right, each class opening to its pieces (Coursera-style, 2026-10-23).
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

/**
 * What sits inside each class: the class lesson itself is added by the
 * renderer; this loads the tests/quizzes attached to each lesson through
 * course_pacing_items, with this student's completion for each.
 * Returns lesson_id => [ ['title'=>, 'kind'=>'Quiz'|'Practice test', 'done'=>bool], ... ].
 * Looks tests up by code in separate queries (no string-column join), the
 * same pattern course_pacing.php uses -- avoids collation mismatches.
 */
require_once __DIR__ . '/lesson_title.php';
require_once __DIR__ . '/course_shape.php';

function progressPathLoadParts(PDO $db, int $courseId, int $studentId): array {
    // Tick any class whose items are all done (a quiz or test finished since the last visit) before the page
    // reads lesson_progress (includes/item_progress.php; migration 156).
    if ($studentId > 0) {
        require_once __DIR__ . '/item_progress.php';
        try { item_progress_sync_course($db, $studentId, $courseId); } catch (PDOException $e) { error_log('item_progress_sync_course: ' . $e->getMessage()); }
    }
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
    $partsStored = [];
    if (isset($GLOBALS['db']) && $GLOBALS['db'] instanceof PDO) {
        $allIds = [];
        foreach ($modules as $mm) foreach ($mm['lessons'] as $ll) $allIds[] = (int) ($ll['lesson_id'] ?? $ll['id'] ?? 0);
        $partsStored = lesson_parts_for_lessons($GLOBALS['db'], $allIds);   // kind / page / Coming Soon per piece (migrations 126, 128)
    }
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
            // A class opens on its first item that has a page (never an in-between "class hub" page: founder,
            // 2026-10-21 -- "if I open it, it should open immediately"). Locking is the plan level alone.
            $firstItem = null;
            foreach ($partsStored[$lid] ?? [] as $sp) { if (!empty($sp['file_path']) && !lesson_piece_coming_soon($sp)) { $firstItem = $sp['file_path']; break; } }
            if ($urlFn)                              $url = $urlFn($num, $lesson);
            elseif ($firstItem !== null)             $url = course_item_url($firstItem, $folder);
            elseif (!empty($lesson['file_path']))    $url = course_item_url($lesson['file_path'], $folder);
            else                                     $url = null;
            $tierOk = ($levels[$lesson['min_tier']] ?? 1) <= $tierLevel;
            $url    = ($url && $tierOk) ? $url : null; // null = no page / locked
            $isDone = isset($doneSet[$lid]);
            if ($isDone) $done++;
            $classes[] = ['num' => $num, 'lesson' => $lesson, 'lid' => $lid, 'url' => $url, 'tierOk' => $tierOk, 'done' => $isDone];
            if (!$isDone && $next === null) $next = ['num' => $num, 'lesson' => $lesson, 'week' => $weekNum, 'url' => $url];
        }
        $weeks[$weekNum] = ['mod' => $mod, 'classes' => $classes, 'done' => $done, 'count' => count($classes)];
        $totalClasses += count($classes); $totalDone += $done;
        if ($currentWeek === null && $done < count($classes)) $currentWeek = $weekNum;
    }
    if ($currentWeek === null) $currentWeek = array_key_last($modules); // course finished: show the last week open

    $unit = stripos((string) (reset($modules)['title'] ?? ''), 'month') === 0 ? 'month' : 'week';
    $pct = $totalClasses ? round($totalDone / $totalClasses * 100) : 0;

    // Summary: progress and where to go next, in one line (founder, 2026-10-23: "Up next: Class 1" is enough, not
    // every piece of the class).
    $out  = '<div class="pp-summary"><div class="pp-summary-row"><span class="pp-summary-title">' . $totalDone . ' of ' . $totalClasses . ' classes complete</span>';
    if ($next) {
        $label = 'Class ' . (int) $next['num'] . ', ' . $unit . ' ' . (int) $next['week'];
        $out .= '<span class="pp-next">Up next: ' . ($next['url'] ? '<a href="' . $h($next['url']) . '">' . $label . ' <i class="bi bi-arrow-right"></i></a>' : $label) . '</span>';
    } else {
        $out .= '<span class="pp-next">All classes complete. Well done!</span>';
    }
    $out .= '</div><div class="pp-bar" role="progressbar" aria-label="Course progress" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"><span style="width:' . $pct . '%"></span></div>';
    // The course's designated tutor (migration 165).
    if ($folder !== '' && isset($GLOBALS['db']) && $GLOBALS['db'] instanceof PDO) {
        require_once __DIR__ . '/course_shape.php';
        if ($tutor = course_tutor($GLOBALS['db'], $folder)) {
            $out .= '<div class="pp-tutor">Your tutor: <strong>' . $h($tutor['name']) . '</strong></div>';
        }
    }
    $out .= '</div>';

    // Pass 2: a list of weeks on the left (Coursera-style, founder 2026-10-23) and the chosen week's classes on the
    // right. The week the student is in is shown first; each class opens to its pieces.
    $nav = ''; $panels = '';
    foreach ($weeks as $weekNum => $w) {
        $isMock   = in_array($weekNum, $mockWeeks, true);
        $isOpen   = ($weekNum === $currentWeek);
        $complete = $w['count'] > 0 && $w['done'] === $w['count'];
        $locked   = $w['count'] > 0 && !array_filter($w['classes'], fn($c) => $c['tierOk']);
        $pid      = 'pp-week-' . (int) $weekNum;
        $ico      = $complete ? '<i class="bi bi-check-circle-fill pp-wk-ico is-done"></i>'
                  : ($locked ? '<i class="bi bi-lock pp-wk-ico"></i>' : '<span class="pp-wk-ico is-todo"></span>');
        $nav .= '<button type="button" class="pp-wk' . ($isOpen ? ' is-active' : '') . '" role="tab" id="' . $pid . '-tab" aria-controls="' . $pid
              . '" aria-selected="' . ($isOpen ? 'true' : 'false') . '" data-pp-week="' . $pid . '">' . $ico
              . '<span>' . ucfirst($unit) . ' ' . (int) $weekNum . '</span>' . ($isMock ? '<span class="pp-pill mock">Mock</span>' : '') . '</button>';

        // "Week 1 — Introduction & First Steps": the heading keeps the part after the dash when there is one.
        $title = $w['mod']['title'];
        $panels .= '<section class="pp-panel" role="tabpanel" id="' . $pid . '" aria-labelledby="' . $pid . '-tab"' . ($isOpen ? '' : ' hidden') . '>';
        $panels .= '<h3 class="pp-panel-title">' . $h($title) . '</h3>';
        $panels .= '<div class="pp-panel-sub"><span>' . $w['done'] . ' of ' . $w['count'] . ' classes done</span>';
        if (is_callable($briefFn) && ($brief = $briefFn((int) $weekNum, ''))) $panels .= '<span class="pp-dot">&bull;</span>' . $brief;
        $panels .= '</div>';

        $bodyId = 'pp-classes-' . (int) $weekNum;
        $panels .= '<div id="' . $bodyId . '" class="pp-classes">';
        foreach ($w['classes'] as $ci => $c) {
            $lesson   = $c['lesson'];
            $lid      = $c['lid'];
            $required = $levels[$lesson['min_tier']] ?? 1;
            $can      = $c['tierOk'];
            // A mock class is one that holds a piece of kind mock_test (stored data). The typed 'mock_classes' list is only a
            // fallback for a course that has no stored pieces, so the schedule in the database is the one source of truth.
            $storedMock = false;
            foreach ($partsStored[(int) $lid] ?? [] as $sp) { if (($sp['kind'] ?? '') === 'mock_test') { $storedMock = true; break; } }
            $isMockCl = $mockFn ? (bool) $mockFn($weekNum, $lesson, $c['num'], $ci) : ($partsStored ? $storedMock : in_array($c['num'], $mockClasses, true));
            $isCurCl  = $next && $next['num'] === $c['num'];

            // Parts: the class lesson first, then the tests/quizzes attached to it.
            // Every piece of the class (the stored title joins them with " + ") gets its own line.
            $parts = [];
            foreach (lesson_title_lines($lesson['title']) as $piece) {
                // What the piece IS (stored kind, else the title rule: no "Test" = lesson), the page that holds it, and
                // whether it is Coming Soon. It used to say "Class lesson" for every piece, so tests looked like lessons.
                $stored   = $partsStored[(int) $lid][$piece] ?? null;
                $kindLabel = lesson_kind_label($stored['kind'] ?? lesson_piece_kind($piece));
                $parts[] = ['title' => $piece, 'kind' => $kindLabel, 'meta' => '', 'done' => $c['done'], 'lesson' => true,
                            'href' => !empty($stored['file_path']) ? course_item_url($stored['file_path'], $folder) : null,
                            'soon' => $stored ? lesson_piece_coming_soon($stored) : false];
            }
            // Scheduled quizzes/tests (course_pacing_items) are added unless the class already lists that item
            // itself (migration 153 gave both the same name), so nothing shows twice.
            $listed = array_flip(array_column($parts, 'title'));
            foreach ($partsByLesson[$lid] ?? [] as $pt) {
                if (!isset($listed[$pt['title']])) $parts[] = $pt + ['meta' => '', 'lesson' => false];
            }
            $partsDone = count(array_filter($parts, fn($p) => $p['done']));
            $classSoon = $parts && count(array_filter($parts, fn($p) => !empty($p['soon']))) === count($parts);   // every piece is Coming Soon
            $cid2 = 'pp-class-' . $lid;

            $panels .= '<div class="pp-class' . ($can ? '' : ' is-locked') . ($isMockCl ? ' is-mock' : '') . '">';
            $panels .= '<button class="pp-class-toggle' . ($isCurCl ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $cid2 . '" aria-expanded="' . ($isCurCl ? 'true' : 'false') . '" aria-controls="' . $cid2 . '">';
            $panels .= '<span class="pp-class-title">' . (!$can ? '<i class="bi bi-lock me-2"></i>' : '') . 'Class ' . $c['num'] . '</span>';
            $panels .= '<span class="pp-class-meta">';
            if ($c['done'])        $panels .= '<span class="pp-pill done">Complete</span>';
            elseif ($classSoon)    $panels .= coming_soon_pill();
            elseif ($isMockCl)     $panels .= '<span class="pp-pill mock">Mock exam</span>';
            elseif ($required === 1) $panels .= '<span class="pp-pill free">Free</span>';
            $panels .= '<span class="pp-count">' . $partsDone . ' of ' . count($parts) . ' done</span><i class="bi bi-chevron-down pp-chev"></i></span></button>';

            $panels .= '<div id="' . $cid2 . '" class="collapse' . ($isCurCl ? ' show' : '') . '" data-bs-parent="#' . $bodyId . '"><ul class="pp-parts">';
            foreach ($parts as $pt) {
                $ico  = $pt['done'] ? '<i class="bi bi-check-circle-fill pp-part-ico is-done"></i>' : '<span class="pp-part-ico is-todo"></span>';
                $text = '<span class="pp-part-text"><span class="pp-part-title' . ($pt['done'] ? ' is-done' : '') . '">' . $h($pt['title']) . '</span>'
                      . '<span class="pp-part-sub">' . $h($pt['kind'] . ($pt['meta'] !== '' ? ', ' . $pt['meta'] : '') . (!empty($pt['soon']) ? ' · Coming Soon' : '')) . '</span></span>';
                $inner = $ico . $text;
                $href  = $pt['href'] ?? $c['url'];
                $panels .= '<li>' . (($pt['lesson'] && $can && $href && empty($pt['soon']))
                    ? '<a class="pp-part" href="' . $h($href) . '">' . $inner . '</a>'
                    : '<span class="pp-part">' . $inner . '</span>') . '</li>';
            }
            $panels .= '</ul></div></div>';
        }
        $panels .= '</div></section>';
    }

    $out .= '<div class="pp-layout"><nav class="pp-weeks" role="tablist" aria-label="' . ucfirst($unit) . 's">' . $nav . '</nav>'
          . '<div class="pp-main">' . $panels . '</div></div>';
    // Week switching (no Bootstrap needed for this part).
    $out .= '<script>(function(){var r=document.currentScript.previousElementSibling;r.querySelectorAll("[data-pp-week]").forEach(function(b){'
          . 'b.addEventListener("click",function(){r.querySelectorAll("[data-pp-week]").forEach(function(x){var on=x===b;x.classList.toggle("is-active",on);'
          . 'x.setAttribute("aria-selected",on?"true":"false");document.getElementById(x.dataset.ppWeek).hidden=!on;});});});})();</script>';
    return $out;
}
