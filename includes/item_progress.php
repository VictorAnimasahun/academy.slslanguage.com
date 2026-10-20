<?php
/**
 * Per-item progress (migration 156). Founder, 2026-10-19: a class is completed only when everything in it is
 * completed -- lessons marked complete (after a set time on the page), quizzes and tests done (and passed where
 * a % is required). Only lessons are completed by hand.
 *
 * Each item of a class is in one of four states:
 *   done       -- finished (lesson marked complete; quiz/test result recorded, passed where required)
 *   todo       -- counts towards the class and isn't finished
 *   untracked  -- the platform records nothing for it yet (writing/speaking practice tests, the Foundational
 *                 English Assessment, the CELPIP Diagnostic Test, exercises): shown, but doesn't block the class
 *   soon       -- Coming Soon: nothing to do yet
 * A class is done when it has at least one counted item and every counted item is done. That is written to
 * lesson_progress (the class tick every page already reads) and the course's progress percentage.
 *
 *   item_progress_key($path)                         lesson page path without ?from=
 *   item_states($db, $studentId, $lessonId)          [part_order => ['title','kind','state', ...]]
 *   item_progress_sync_course($db, $studentId, $courseId)   ticks finished classes, returns progress %
 *   item_progress_open($db, $studentId, $lessonId, $path)    first-open time for a lesson page
 *   item_progress_complete($db, $studentId, $lessonId, $path) ['ok'] or ['error', 'seconds_left']
 */
require_once __DIR__ . '/content_types.php';

const ITEM_LESSON_MIN_SECONDS = 300; // 5 minutes on a lesson before it can be marked complete (founder, 2026-10-19)

if (!function_exists('item_progress_key')) {
    function item_progress_key(?string $path): string {
        $parts = explode('?', ltrim((string) $path, '/'), 2);
        parse_str($parts[1] ?? '', $q);
        unset($q['from']);
        ksort($q);
        return substr($parts[0] . ($q ? '?' . http_build_query($q) : ''), 0, 191);
    }

    /** Is a test/quiz code finished by this student? tests.code -> best completed attempt (and pass %); mock_exams.code -> a submitted session. */
    function item_code_done(PDO $db, int $studentId, string $code, ?int $passPercent): bool {
        static $cache = [];
        $k = "$studentId|$code|$passPercent";
        if (isset($cache[$k])) return $cache[$k];
        $st = $db->prepare("SELECT MAX(CASE WHEN a.max_score > 0 THEN a.score / a.max_score * 100 ELSE 100 END) AS best, COUNT(a.id) AS n
                              FROM tests t JOIN test_attempts a ON a.test_id = t.id
                             WHERE t.code = ? AND a.student_id = ? AND a.status = 'completed'");
        $st->execute([$code, $studentId]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if ($r && (int) $r['n'] > 0) {
            return $cache[$k] = $passPercent === null || (float) $r['best'] >= $passPercent;
        }
        $st = $db->prepare("SELECT 1 FROM mock_exams me JOIN mock_sessions ms ON ms.mock_test_id = me.id
                             WHERE me.code = ? AND ms.student_id = ? AND ms.status <> 'in_progress' LIMIT 1");
        $st->execute([$code, $studentId]);
        return $cache[$k] = (bool) $st->fetchColumn();
    }

    function item_states(PDO $db, int $studentId, int $lessonId): array {
        // An item with no page of its own uses its class's page (as the website and the app open it).
        $st = $db->prepare("SELECT p.part_order, p.title, p.kind, p.status, COALESCE(NULLIF(p.file_path, ''), l.file_path) AS file_path, p.test_code, p.pass_percent
                              FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id WHERE p.lesson_id = ? ORDER BY p.part_order");
        $st->execute([$lessonId]);
        $items = $st->fetchAll(PDO::FETCH_ASSOC);
        if (!$items) return [];

        $st = $db->prepare("SELECT item_key, completed_at FROM lesson_item_progress WHERE student_id = ? AND lesson_id = ?");
        $st->execute([$studentId, $lessonId]);
        $lessonDone = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) if ($r['completed_at'] !== null) $lessonDone[$r['item_key']] = true;

        $out = [];
        foreach ($items as $it) {
            if ($it['status'] === 'coming_soon') {
                $state = 'soon';
            } elseif ($it['kind'] === 'lesson') {
                $state = isset($lessonDone[item_progress_key($it['file_path'])]) ? 'done' : 'todo';
            } elseif (!empty($it['test_code'])) {
                $pass = $it['pass_percent'] !== null ? (int) $it['pass_percent'] : null;
                $allDone = true;
                foreach (explode(',', $it['test_code']) as $code) {
                    if (!item_code_done($db, $studentId, trim($code), $pass)) { $allDone = false; break; }
                }
                $state = $allDone ? 'done' : 'todo';
            } else {
                $state = 'untracked';
            }
            $out[(int) $it['part_order']] = $it + ['state' => $state];
        }
        return $out;
    }

    function item_class_done(array $states): bool {
        $counted = array_filter($states, fn ($s) => in_array($s['state'], ['done', 'todo'], true));
        return $counted && !array_filter($counted, fn ($s) => $s['state'] === 'todo');
    }

    /**
     * Ticks every class of the course whose items are all done, then recomputes the course's progress %.
     * It never un-ticks: a class ticked before per-item progress existed stays ticked.
     */
    function item_progress_sync_course(PDO $db, int $studentId, int $courseId): int {
        $st = $db->prepare("SELECT l.id FROM lessons l JOIN modules m ON m.id = l.module_id WHERE m.course_id = ?");
        $st->execute([$courseId]);
        $lessonIds = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
        if (!$lessonIds) return 0;

        $ph = implode(',', array_fill(0, count($lessonIds), '?'));
        $st = $db->prepare("SELECT lesson_id FROM lesson_progress WHERE student_id = ? AND completed = 1 AND lesson_id IN ($ph)");
        $st->execute([$studentId, ...$lessonIds]);
        $ticked = array_flip(array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN)));

        $tick = $db->prepare("INSERT INTO lesson_progress (student_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW())
                              ON DUPLICATE KEY UPDATE completed = 1, completed_at = COALESCE(completed_at, NOW())");
        foreach ($lessonIds as $lid) {
            if (isset($ticked[$lid])) continue;
            if (item_class_done(item_states($db, $studentId, $lid))) {
                $tick->execute([$studentId, $lid]);
                $ticked[$lid] = true;
            }
        }

        $percent = (int) round(count($ticked) / count($lessonIds) * 100);
        $db->prepare("UPDATE enrollments SET progress_percentage = ? WHERE student_id = ? AND course_id = ?")
           ->execute([$percent, $studentId, $courseId]);
        return $percent;
    }

    /** Records when a lesson page was first opened (the clock the 5-minute rule runs from). */
    function item_progress_open(PDO $db, int $studentId, int $lessonId, string $path): void {
        $db->prepare("INSERT INTO lesson_item_progress (student_id, lesson_id, item_key, opened_at) VALUES (?, ?, ?, NOW())
                      ON DUPLICATE KEY UPDATE opened_at = opened_at")
           ->execute([$studentId, $lessonId, item_progress_key($path)]);
    }

    /** Marks a LESSON item complete -- only a lesson item of that class, and only 5 minutes after it was opened. */
    function item_progress_complete(PDO $db, int $studentId, int $lessonId, string $path): array {
        $key = item_progress_key($path);
        $isLesson = false;
        foreach (item_states($db, $studentId, $lessonId) as $it) {
            if ($it['kind'] === 'lesson' && item_progress_key($it['file_path']) === $key) { $isLesson = true; break; }
        }
        if (!$isLesson) return ['error' => 'Only lessons can be marked complete.'];

        $st = $db->prepare("SELECT TIMESTAMPDIFF(SECOND, opened_at, NOW()) FROM lesson_item_progress WHERE student_id = ? AND lesson_id = ? AND item_key = ?");
        $st->execute([$studentId, $lessonId, $key]);
        $elapsed = $st->fetchColumn();
        if ($elapsed === false) return ['error' => 'Open the lesson first.', 'seconds_left' => ITEM_LESSON_MIN_SECONDS];
        if ((int) $elapsed < ITEM_LESSON_MIN_SECONDS) return ['error' => 'Spend a little longer on this lesson first.', 'seconds_left' => ITEM_LESSON_MIN_SECONDS - (int) $elapsed];

        $db->prepare("UPDATE lesson_item_progress SET completed_at = COALESCE(completed_at, NOW()) WHERE student_id = ? AND lesson_id = ? AND item_key = ?")
           ->execute([$studentId, $lessonId, $key]);
        return ['ok' => true];
    }
}
