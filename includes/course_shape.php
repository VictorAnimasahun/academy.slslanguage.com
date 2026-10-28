<?php
/**
 * The real shape of a course, read from the database -- for lesson pages that are SHARED by several
 * course lengths (CELPIP_Gen/, IELTS_Gen/). Those pages used to hard-code one course's facts
 * ("Class 2 of 24", "Welcome to the 3-Month Masterclass", a 24-row timetable, "Next: <a fixed file>"),
 * so a 1- or 2-month student read the wrong course. Founder, 2026-10-19: never again.
 *
 *   course_by_folder($db, 'CELPIP_Gen_2Mo')        -> ['id','title','folder','classes','weeks','months'] or null
 *   course_schedule($db, $courseId)                -> weeks -> classes -> items (one row per item)
 *   next_course_item($db, $courseId, 'path.php')   -> the item after this page: next in the class, else the
 *                                                     next class's first item ('title','kind','url') or null
 *   course_item_url($path, $folder)                -> absolute URL; lesson pages carry ?from=<folder>
 */
require_once __DIR__ . '/lesson_title.php';

if (!function_exists('course_by_folder')) {
    function course_by_folder(PDO $db, string $folder): ?array {
        static $cache = [];
        if (array_key_exists($folder, $cache)) return $cache[$folder];
        try {
            $st = $db->prepare(
                "SELECT c.id, c.title, c.folder_name, c.length_months,
                        (SELECT COUNT(*) FROM lessons l WHERE l.course_id = c.id) AS classes,
                        (SELECT COUNT(*) FROM modules m WHERE m.course_id = c.id) AS weeks
                   FROM courses c WHERE c.folder_name = ? LIMIT 1"
            );
            $st->execute([$folder]);
            $r = $st->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('course_by_folder: ' . $e->getMessage());
            $r = false;
        }
        return $cache[$folder] = $r ? [
            'id' => (int) $r['id'], 'title' => $r['title'], 'folder' => $r['folder_name'],
            'classes' => (int) $r['classes'], 'weeks' => (int) $r['weeks'],
            'months' => $r['length_months'] !== null ? (int) $r['length_months'] : null,
        ] : null;
    }

    /** The course's designated tutor (migration 165): ['name'] or null. Shown to students on the course page. */
    function course_tutor(PDO $db, string $folder): ?array {
        try {
            $st = $db->prepare("SELECT e.name FROM courses c JOIN employees e ON e.id = c.tutor_employee_id WHERE c.folder_name = ? LIMIT 1");
            $st->execute([$folder]);
            $name = $st->fetchColumn();
        } catch (PDOException $e) {
            return null;   // before migration 165
        }
        return $name !== false ? ['name' => $name] : null;
    }

    /** Absolute URL for an item, with ?from=<folder>: shared pages (lessons, tests, quizzes, the slide viewer) use it to
     *  know which course they're in, for their Previous / Next buttons (includes/item_nav.php). */
    function course_item_url(string $path, string $folder): string {
        $path = ltrim($path, '/');
        if (!preg_match('/[?&]from=/', $path)) {
            $path .= (str_contains($path, '?') ? '&' : '?') . 'from=' . rawurlencode($folder);
        }
        return ACADEMY_URL . $path;
    }

    /** @return array<int, array{number:int, title:string, classes: array<int, array{number:int, items: array}>}> */
    function course_schedule(PDO $db, int $courseId): array {
        static $cache = [];
        if (isset($cache[$courseId])) return $cache[$courseId];
        try {
            $st = $db->prepare(
                "SELECT m.module_order, m.module_title, l.id AS lesson_id, l.lesson_order, l.title AS class_title, l.file_path AS class_file,
                        p.part_order, p.title AS part_title, p.kind, p.status, p.file_path AS part_file
                   FROM modules m
                   JOIN lessons l ON l.module_id = m.id
                   LEFT JOIN lesson_parts p ON p.lesson_id = l.id
                  WHERE m.course_id = ?
                  ORDER BY m.module_order, l.lesson_order, p.part_order"
            );
            $st->execute([$courseId]);
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('course_schedule: ' . $e->getMessage());
            $rows = [];
        }
        $weeks = [];
        foreach ($rows as $r) {
            $w = (int) $r['module_order'];
            $weeks[$w] ??= ['number' => $w, 'title' => $r['module_title'], 'classes' => []];
            $lid = (int) $r['lesson_id'];
            $weeks[$w]['classes'][$lid] ??= ['number' => ($w - 1) * 2 + (int) $r['lesson_order'], 'items' => [], 'title' => $r['class_title'], 'file' => $r['class_file']];
            if ($r['part_order'] !== null) {
                $weeks[$w]['classes'][$lid]['items'][] = [
                    'title' => $r['part_title'],
                    'kind' => $r['kind'],
                    'ready' => $r['status'] === 'ready',
                    'path' => ($r['part_file'] ?? '') !== '' ? $r['part_file'] : ($r['class_file'] ?? null),
                ];
            }
        }
        // A class with no item rows yet still shows its title's lines (as on the course page).
        foreach ($weeks as &$week) {
            foreach ($week['classes'] as &$cls) {
                if (!$cls['items']) {
                    foreach (lesson_title_lines($cls['title']) as $line) {
                        $cls['items'][] = ['title' => $line, 'kind' => lesson_piece_kind($line), 'ready' => !empty($cls['file']), 'path' => $cls['file']];
                    }
                }
            }
            $week['classes'] = array_values($week['classes']);
        }
        unset($week, $cls);
        return $cache[$courseId] = array_values($weeks);
    }

    /**
     * The course plan in one paragraph (founder, 2026-10-21: the full class-by-class table was "too long"), written from
     * the real schedule so it can't drift: length, Week 1, practice tests, mock weeks and how the course ends.
     */
    function course_plan_paragraph(PDO $db, array $course): string {
        require_once __DIR__ . '/content_types.php';
        $exam = trim(preg_replace('/\s+(Masterclass|Crash Course).*$/u', '', $course['title']));
        $practice = [];
        $mocks = [];
        $mockWeeks = [];
        $schedule = course_schedule($db, $course['id']);
        foreach ($schedule as $week) {
            foreach ($week['classes'] as $cls) {
                foreach ($cls['items'] as $item) {
                    if ($item['kind'] === 'practice_test') $practice[$item['title']] = true;
                    if ($item['kind'] === 'mock_test') { $mocks[$item['title']] = true; $mockWeeks[$week['number']] = true; }
                }
            }
        }
        $mockWeeks = array_keys($mockWeeks);
        $list = function (array $nums): string {
            if (count($nums) === 1) return 'Week ' . $nums[0];
            $last = array_pop($nums);
            return 'Weeks ' . implode(', ', $nums) . ' and ' . $last;
        };
        $last = end($schedule);
        $ending = $last ? trim(preg_replace('/^Week\s+\d+\s*[—–-]\s*/u', '', $last['title'])) : '';

        $crash = (int) $course['weeks'] <= 4;   // a crash course doesn't spend a whole week on introductions
        $s = sprintf('Over %d weeks you have %d two-hour classes, two a week.', (int) $course['weeks'], (int) $course['classes']);
        $s .= $crash
            ? sprintf(' It starts with the course introduction and finds your starting point, then goes straight into the %s exam', $exam)
            : sprintf(' Week 1 introduces the course and the %s exam and finds your starting point. From Week 2, every class works on the exam itself, one section at a time', $exam);
        $s .= $practice ? sprintf(', with %d practice tests along the way', count($practice)) : '';
        $s .= '.';
        if ($mockWeeks) {
            $s .= sprintf(' You sit %s in %s.', count($mocks) === 1 ? 'a full mock exam' : count($mocks) . ' full mock exams', $list($mockWeeks));
        }
        if ($ending !== '') $s .= sprintf(' The last week: %s.', $ending);
        return $s;
    }

    /**
     * Where the item whose page is $path sits in this course: ['week' => n, 'class' => n] or null. Pages shared by
     * several courses (or moved between classes) read their position from here instead of typing it in.
     * $match 'prefix' also finds e.g. class_day.php?slot=c8&part=lesson when given class_day.php?slot=c8.
     */
    function course_item_position(PDO $db, array $course, string $path, string $match = 'exact'): ?array {
        // [file, params] without ?from=; 'prefix' matches when the item has at least the wanted params
        // (class_day.php?slot=c8 finds class_day.php?slot=c8&part=lesson, whatever order the params are in).
        $split = function ($p) {
            $parts = explode('?', ltrim((string) $p, '/'), 2);
            parse_str($parts[1] ?? '', $q);
            unset($q['from']);
            return [$parts[0], $q];
        };
        [$wantFile, $wantQ] = $split($path);
        foreach (course_schedule($db, $course['id']) as $week) {
            foreach ($week['classes'] as $cls) {
                foreach ($cls['items'] as $item) {
                    if (empty($item['path'])) continue;
                    [$haveFile, $haveQ] = $split($item['path']);
                    if ($haveFile !== $wantFile) continue;
                    $same = $match === 'prefix' ? array_intersect_assoc($wantQ, $haveQ) === $wantQ : $haveQ == $wantQ;
                    if ($same) {
                        return ['week' => (int) $week['number'], 'class' => (int) $cls['number']];
                    }
                }
            }
        }
        return null;
    }

    /** The ready item before / after the page at $path in this course (within its class, else across classes). */
    function course_item_neighbours(PDO $db, array $course, string $path): array {
        $flat = [];
        $classes = 0;
        foreach (course_schedule($db, $course['id']) as $week) {
            foreach ($week['classes'] as $cls) {
                $classes++;
                foreach ($cls['items'] as $item) $flat[] = $item + ['class' => $cls['number'], 'week' => $week['number']];
            }
        }
        $strip = function ($p) {
            $parts = explode('?', ltrim((string) $p, '/'), 2);
            parse_str($parts[1] ?? '', $q);
            unset($q['from']);
            ksort($q);
            return $parts[0] . ($q ? '?' . http_build_query($q) : '');
        };
        $usable = fn ($it) => $it['ready'] && $it['path'];
        $url = fn ($it) => $it + ['url' => course_item_url($it['path'], $course['folder'])];
        foreach ($flat as $i => $item) {
            if ($item['path'] !== null && $strip($item['path']) === $strip($path)) {
                $prev = $next = null;
                for ($j = $i - 1; $j >= 0 && !$prev; $j--) if ($usable($flat[$j]) && $strip($flat[$j]['path']) !== $strip($path)) $prev = $url($flat[$j]);
                for ($j = $i + 1; $j < count($flat) && !$next; $j++) if ($usable($flat[$j]) && $strip($flat[$j]['path']) !== $strip($path)) $next = $url($flat[$j]);
                // 'item': where this page sits (its title, kind, class and week), for a page header like a lesson's.
                return ['prev' => $prev, 'next' => $next, 'found' => true, 'item' => $item, 'classes' => $classes];
            }
        }
        return ['prev' => null, 'next' => null, 'found' => false, 'item' => null, 'classes' => $classes];
    }

    function prev_course_item(PDO $db, array $course, string $path): ?array {
        return course_item_neighbours($db, $course, $path)['prev'];
    }

    /** The item that comes after the page at $path in this course: the next item in its class, else the next class's first. */
    function next_course_item(PDO $db, array $course, string $path): ?array {
        $flat = [];
        foreach (course_schedule($db, $course['id']) as $week) {
            foreach ($week['classes'] as $cls) {
                foreach ($cls['items'] as $item) $flat[] = $item + ['class' => $cls['number']];
            }
        }
        // Same page = same file and same query (class_day.php?slot=c3&part=lesson), ignoring ?from=.
        $strip = function ($p) {
            $parts = explode('?', ltrim((string) $p, '/'), 2);
            parse_str($parts[1] ?? '', $q);
            unset($q['from']);
            ksort($q);
            return $parts[0] . ($q ? '?' . http_build_query($q) : '');
        };
        foreach ($flat as $i => $item) {
            if ($item['path'] !== null && $strip($item['path']) === $strip($path)) {
                for ($j = $i + 1; $j < count($flat); $j++) {
                    if ($flat[$j]['ready'] && $flat[$j]['path']) {
                        return $flat[$j] + ['url' => course_item_url($flat[$j]['path'], $course['folder'])];
                    }
                }
                return null;
            }
        }
        return null;
    }
}
