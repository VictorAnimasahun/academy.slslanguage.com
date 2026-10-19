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

    /** Absolute URL for an item. Shared lesson pages need ?from=<folder> to know which course they're in. */
    function course_item_url(string $path, string $folder): string {
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'courses/')) {
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
