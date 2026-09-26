<?php
/**
 * A class title in the database can carry several pieces of content joined with
 * " + " (e.g. "Writing Test 1 (Timed) + Listening Formats"). The "+" is only a
 * storage separator: everywhere it is shown, each piece goes on its own line,
 * never tied together with a "+".
 */
require_once __DIR__ . '/coming_soon.php';

if (!function_exists('lesson_title_lines')) {
    /** @return string[] one entry per piece of content */
    function lesson_title_lines(string $title): array {
        $title = preg_replace('/^Class\s+\d+\s*:\s*/u', '', $title);              // the "Class N" label is shown separately
        $lines = array_values(array_filter(array_map('trim', preg_split('/\s+[+·]\s+/u', $title))));
        return $lines ?: [trim($title)];
    }
    /** HTML: each piece on its own line. $h is the caller's escaper. */
    function lesson_title_html(string $title, callable $h, string $lineClass = ''): string {
        $o = '';
        foreach (lesson_title_lines($title) as $line) {
            $o .= '<span class="' . trim('lesson-line ' . $lineClass) . '" style="display:block;">' . $h($line) . '</span>';
        }
        return $o;
    }
}

if (!function_exists('test_kind_label')) {
    /**
     * A short accordion subtitle that names WHICH numbered test this is —
     * "Practice Test 2", "Mock Test 1" — derived from the test_code, not from
     * whatever free-text a pacing item's own title happens to say. Before this,
     * every attached test showed as the same bare "Practice test" label
     * (course_pacing_items' item_type collapsed 'mock_test' into it too), so a
     * student could only find out which numbered test it was by opening it.
     *
     *   IELTS_PT_L_002        -> "Practice Test 2"
     *   IELTS_PT_W1_001       -> "Practice Test 1" (the "W1" is Task 1, not the set number)
     *   IELTS_FULL_MOCK_003   -> "Mock Test 3"
     *   CELPIP_FULL_MOCK_A    -> "Mock Test A"
     *   anything else / quiz  -> the item_type-based fallback (unchanged)
     */
    function test_kind_label(string $testCode, string $itemType): string {
        if ($itemType === 'quiz') return 'Quiz';
        if (preg_match('/_MOCK_([A-Z]|\d+)$/', $testCode, $m)) {
            $n = ctype_digit($m[1]) ? (int) $m[1] : $m[1];
            return "Mock Test $n";
        }
        if (preg_match('/_PT_[A-Z]*\d*_(\d+)$/', $testCode, $m)) {
            return 'Practice Test ' . (int) $m[1];
        }
        return $itemType === 'mock_test' ? 'Mock test' : 'Practice test';
    }
}

if (!function_exists('lesson_piece_kind')) {
    /**
     * What a piece of a class IS -- 'lesson', 'resource', 'practice_test' or 'mock_test'.
     * Instructor's rule (2026-09-26): anything that does not carry the word "Test" is a lesson;
     * "Mock Test N" / "Mock Exam N" is a mock, any other "... Test ..." is a practice test. The stored value in
     * `lesson_parts.kind` (migration 126) wins over this rule where a row exists, so an
     * exception (a resource, an assessment) is set in the data, not in code.
     */
    function lesson_piece_kind(string $pieceTitle): string {
        if (preg_match('/\bMock\s+(Test|Exam)\b/i', $pieceTitle)) return 'mock_test';
        if (preg_match('/\bTest\b/i', $pieceTitle))       return 'practice_test';
        return 'lesson';
    }

    function lesson_kind_label(string $kind): string {
        return ['lesson' => 'Lesson', 'resource' => 'Resource', 'practice_test' => 'Practice test', 'mock_test' => 'Mock test'][$kind] ?? 'Lesson';
    }

    /** Stored kinds for a whole course: [lesson_id => [piece title => kind]]. Empty if migration 126 hasn't run. */
    function lesson_part_kinds_for_course(PDO $db, int $courseId): array {
        static $cache = [];
        if (isset($cache[$courseId])) return $cache[$courseId];
        $out = [];
        try {
            $st = $db->prepare("SELECT lp.lesson_id, lp.title, lp.kind FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id WHERE l.course_id = ?");
            $st->execute([$courseId]);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $out[(int)$r['lesson_id']][$r['title']] = $r['kind'];
        } catch (\Throwable $e) { /* table not there yet: fall back to the title rule */ }
        return $cache[$courseId] = $out;
    }

    /** Stored kinds for specific lessons: [lesson_id => [piece title => kind]]. Empty if migration 126 hasn't run. */
    function lesson_part_kinds_for_lessons(PDO $db, array $lessonIds): array {
        $lessonIds = array_values(array_unique(array_filter(array_map('intval', $lessonIds))));
        if (!$lessonIds) return [];
        $out = [];
        try {
            $ph = implode(',', array_fill(0, count($lessonIds), '?'));
            $st = $db->prepare("SELECT lesson_id, title, kind FROM lesson_parts WHERE lesson_id IN ($ph)");
            $st->execute($lessonIds);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $out[(int)$r['lesson_id']][$r['title']] = $r['kind'];
        } catch (\Throwable $e) { /* table not there yet */ }
        return $out;
    }

    /** Own page per piece for specific lessons: [lesson_id => [piece title => file path relative to the academy root]]. */
    function lesson_part_files_for_lessons(PDO $db, array $lessonIds): array {
        $lessonIds = array_values(array_unique(array_filter(array_map('intval', $lessonIds))));
        if (!$lessonIds) return [];
        $out = [];
        try {
            $ph = implode(',', array_fill(0, count($lessonIds), '?'));
            $st = $db->prepare("SELECT lesson_id, title, file_path FROM lesson_parts WHERE file_path IS NOT NULL AND file_path <> '' AND lesson_id IN ($ph)");
            $st->execute($lessonIds);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $out[(int)$r['lesson_id']][$r['title']] = $r['file_path'];
        } catch (\Throwable $e) { /* column/table not there yet */ }
        return $out;
    }

    /** The stored parts of one class (by course folder + running class number), in order. */
    function lesson_class_parts(PDO $db, string $folder, int $classNum): array {
        try {
            $st = $db->prepare("SELECT l.id FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
                                WHERE c.folder_name = ? ORDER BY c.is_visible DESC, c.id, m.module_order, l.lesson_order");
            $st->execute([$folder]);
            $ids = array_map('intval', $st->fetchAll(PDO::FETCH_COLUMN));
            $lid = $ids[$classNum - 1] ?? 0;
            if (!$lid) return [];
            $st = $db->prepare("SELECT title, kind, file_path FROM lesson_parts WHERE lesson_id = ? ORDER BY part_order");
            $st->execute([$lid]);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) { return []; }
    }

    /**
     * The plan a class needs, read from the database (lessons.min_tier) so the free-preview rule lives in one place.
     * Class pages used to type their own tier, and drifted (Class 1 of IELTS Academic 2-Month said "intermediate"
     * while the database and the overview said it was free). Falls back to $default if the class can't be found.
     */
    function lesson_min_tier(PDO $db, string $folder, int $classNum, string $default = 'intermediate'): string {
        try {
            $st = $db->prepare("SELECT l.min_tier FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
                                WHERE c.folder_name = ? ORDER BY c.is_visible DESC, c.id, m.module_order, l.lesson_order");
            $st->execute([$folder]);
            $tiers = $st->fetchAll(PDO::FETCH_COLUMN);
            return ($tiers[$classNum - 1] ?? '') ?: $default;
        } catch (\Throwable $e) { return $default; }
    }

    /** "Open Lesson" button for a lesson piece that has its own page; '' when it has none. */
    function lesson_part_button(PDO $db, string $folder, int $classNum, string $pieceTitle): string {
        $pieceTitle = html_entity_decode($pieceTitle, ENT_QUOTES, 'UTF-8');
        foreach (lesson_class_parts($db, $folder, $classNum) as $p) {
            if ($p['title'] === $pieceTitle && !empty($p['file_path']) && ($p['kind'] === 'lesson' || $p['kind'] === 'resource')) {
                return '<a href="' . htmlspecialchars(ACADEMY_URL . $p['file_path']) . '" class="btn btn-outline-primary mt-2"><i class="bi bi-journal-text me-2"></i>Open Lesson</a>';
            }
        }
        return '';
    }

    /**
     * For a class page with no content of its own: every piece with its kind, an "Open Lesson" button where the
     * piece has a page, and a plain "coming soon" line where it doesn't (tests not built yet).
     */
    function lesson_class_parts_list(PDO $db, string $folder, int $classNum): string {
        $parts = lesson_class_parts($db, $folder, $classNum);
        if (!$parts) return '';
        $o = '<div class="lesson-content mb-4">';
        foreach ($parts as $p) {
            $title = htmlspecialchars($p['title']);
            $badge = lesson_kind_badge($db, $folder, $classNum, $p['title']);
            $o .= '<div class="lesson-item"><h5>' . $title . ' ' . $badge . '</h5>';
            if (!empty($p['file_path']) && ($p['kind'] === 'lesson' || $p['kind'] === 'resource')) {
                $o .= '<a href="' . htmlspecialchars(ACADEMY_URL . $p['file_path']) . '" class="btn btn-outline-primary"><i class="bi bi-journal-text me-2"></i>Open Lesson</a>';
            } else {
                $o .= coming_soon_line();
            }
            $o .= '</div>';
        }
        return $o . '</div>';
    }

    /** Everything stored about pieces: [lesson_id => [piece title => ['kind','status','file_path']]]. Empty if migration 126/128 hasn't run. */
    function lesson_parts_for_lessons(PDO $db, array $lessonIds): array {
        $lessonIds = array_values(array_unique(array_filter(array_map('intval', $lessonIds))));
        if (!$lessonIds) return [];
        $out = [];
        $ph = implode(',', array_fill(0, count($lessonIds), '?'));
        foreach (["SELECT lesson_id, title, kind, status, file_path FROM lesson_parts WHERE lesson_id IN ($ph)",
                  "SELECT lesson_id, title, kind, 'ready' AS status, file_path FROM lesson_parts WHERE lesson_id IN ($ph)"] as $sql) {   // second form: before migration 128
            try {
                $st = $db->prepare($sql); $st->execute($lessonIds);
                foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $out[(int)$r['lesson_id']][$r['title']] = ['kind' => $r['kind'], 'status' => $r['status'], 'file_path' => $r['file_path'] ?: null];
                return $out;
            } catch (\Throwable $e) { /* try the older shape / table missing */ }
        }
        return [];
    }

    /** Is this file one of the empty-lesson-page files (courses/<course>/lessons/classNN_pK_*.php)? */
    function lesson_file_is_shell(string $relPath): bool {
        return (bool)preg_match('#/lessons/class\d{2}_p\d+_[^/]*\.php$#', $relPath);
    }

    /** True when a lesson page still has nothing between its LESSON BODY markers. Read from the file, so developing a lesson needs no data change. */
    function lesson_shell_is_empty(string $relPath): bool {
        static $cache = [];
        if (isset($cache[$relPath])) return $cache[$relPath];
        $file = dirname(__DIR__) . '/' . $relPath;
        if (!is_file($file)) return $cache[$relPath] = true;
        $src = file_get_contents($file);
        if (!preg_match('/LESSON BODY START -->(.*?)<!-- LESSON BODY END/s', $src, $m)) return $cache[$relPath] = false;   // not the shell layout: treat as a real page
        return $cache[$relPath] = trim(preg_replace('/<!--.*?-->/s', '', $m[1])) === '';
    }

    /**
     * Coming Soon for one piece. A piece with a page is judged by the page (an empty lesson shell = Coming Soon,
     * any other page = ready); a piece without a page follows its stored status.
     */
    function lesson_piece_coming_soon(array $part): bool {
        $f = $part['file_path'] ?? null;
        if ($f) return lesson_file_is_shell($f) ? lesson_shell_is_empty($f) : false;
        return ($part['status'] ?? 'ready') === 'coming_soon';
    }

    function lesson_piece_kind_stored(array $kinds, int $lessonId, string $pieceTitle): string {
        return $kinds[$lessonId][$pieceTitle] ?? lesson_piece_kind($pieceTitle);
    }

    /**
     * Small coloured tag for a class page heading. Looks the class up by course folder + running
     * class number, then the piece by its title; falls back to the title rule.
     */
    function lesson_kind_badge(PDO $db, string $folder, int $classNum, string $pieceTitle): string {
        static $lessonIds = [];
        $pieceTitle = html_entity_decode($pieceTitle, ENT_QUOTES, 'UTF-8');
        if (!isset($lessonIds[$folder])) {
            $st = $db->prepare("SELECT l.id, c.id AS course_id FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
                                WHERE c.folder_name = ? ORDER BY m.module_order, l.lesson_order");
            $st->execute([$folder]);
            $rows = $st->fetchAll(PDO::FETCH_ASSOC);
            $lessonIds[$folder] = ['ids' => array_map('intval', array_column($rows, 'id')), 'course' => (int)($rows[0]['course_id'] ?? 0)];
        }
        $lid = $lessonIds[$folder]['ids'][$classNum - 1] ?? 0;
        $kind = lesson_piece_kind_stored(lesson_part_kinds_for_course($db, $lessonIds[$folder]['course']), $lid, $pieceTitle);
        $colours = ['lesson' => ['#e0f2fe', '#075985'], 'resource' => ['#f1f5f9', '#475569'], 'practice_test' => ['#fef3c7', '#92400e'], 'mock_test' => ['#fee2e2', '#991b1b']];
        [$bg, $fg] = $colours[$kind] ?? $colours['lesson'];
        return '<span style="display:inline-block;vertical-align:middle;margin-left:.5rem;padding:.12rem .55rem;border-radius:999px;font-size:.66rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;background:' . $bg . ';color:' . $fg . ';">' . lesson_kind_label($kind) . '</span>';
    }
}
