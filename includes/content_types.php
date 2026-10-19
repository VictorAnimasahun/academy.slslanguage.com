<?php
/**
 * Content types -- the ONE place that says what each kind of content is and how it behaves
 * (founder, 2026-10-19: tag every item and file with its type, and write behaviour against the type).
 * Items carry their type in lesson_parts.kind; files in the content_types table (migration 152).
 * The app gets the same rules from api/v1/content_types.php -- change them here, not in a page.
 *
 *   content_type_info($kind)        -> ['label','group','web_only','button','icon','bg','fg']
 *   content_type_is_test($kind)     -> true for practice / mock / diagnostic / placement tests
 *   content_type_web_only($kind)    -> true if it must be done on a computer (tests, analysers; spec D11)
 *   content_type_of_path($db, $p)   -> the kind of a content file (longest matching rule), or null
 */

if (!function_exists('content_type_info')) {
    function content_types_table(): array { return [
        //                  label (fallback)         category     web_only button         icon                    badge bg / fg
        'lesson'          => ['Lesson',                'lesson',    false, 'Open Lesson',   'bi-journal-text',     '#e0f2fe', '#075985'],
        'quiz'            => ['Quiz',                  'practice',  false, 'Open Quiz',     'bi-question-circle',  '#ede9fe', '#5b21b6'],
        'exercise'        => ['Exercise',              'practice',  false, 'Open Exercise', 'bi-pencil-square',    '#dcfce7', '#166534'],
        'resource'        => ['Resource',              'resource',  false, 'Open',          'bi-folder2-open',     '#f1f5f9', '#475569'],
        'practice_test'   => ['Practice test',         'test',      true,  'Open Test',     'bi-clipboard-check',  '#fef3c7', '#92400e'],
        'mock_test'       => ['Mock test',             'test',      true,  'Open Test',     'bi-clipboard-data',   '#fee2e2', '#991b1b'],
        'diagnostic_test' => ['Diagnostic test',       'test',      true,  'Open Test',     'bi-speedometer2',     '#fce7f3', '#9d174d'],
        'placement_test'  => ['English assessment',    'test',      true,  'Open Test',     'bi-translate',        '#e0e7ff', '#3730a3'],
        'analyser'        => ['AI analyser',           'tool',      true,  'Open',          'bi-stars',            '#f1f5f9', '#475569'],
    ]; }

    /** label / category / web_only per kind from the content_kinds table (migration 152); null until it exists. */
    function content_kinds_db(): ?array {
        static $rows = false;
        if ($rows !== false) return $rows;
        global $db;
        $rows = null;
        if (isset($db) && $db instanceof PDO) {
            try {
                foreach ($db->query('SELECT kind, label, category, web_only FROM content_kinds') as $r) $rows[$r['kind']] = $r;
            } catch (PDOException $e) {
                $rows = null;
            }
        }
        return $rows;
    }

    /** The database (content_kinds) decides label, category and computer-only; the table above only adds looks. */
    function content_type_info(?string $kind): array {
        [$label, $group, $webOnly, $button, $icon, $bg, $fg] = content_types_table()[$kind] ?? content_types_table()['lesson'];
        $row = content_kinds_db()[$kind] ?? null;
        if ($row) {
            $label = $row['label'];
            $group = $row['category'];
            $webOnly = (bool) $row['web_only'];
        }
        return compact('label', 'group', 'button', 'icon', 'bg', 'fg') + ['web_only' => $webOnly];
    }

    function content_type_is_test(?string $kind): bool {
        return content_type_info($kind)['group'] === 'test';
    }

    function content_type_web_only(?string $kind): bool {
        return content_type_info($kind)['web_only'];
    }

    /** @return array<string,string> every file rule, path => kind (longest first) */
    function content_type_rules(PDO $db): array {
        static $rules = null;
        if ($rules !== null) return $rules;
        $rules = [];
        try {
            foreach ($db->query('SELECT path, kind FROM content_types') as $r) $rules[$r['path']] = $r['kind'];
        } catch (PDOException $e) {
            error_log('content_type_rules: ' . $e->getMessage()); // migration 152 not run yet
        }
        uksort($rules, fn ($a, $b) => strlen($b) <=> strlen($a));
        return $rules;
    }

    function content_type_of_path(PDO $db, string $path): ?string {
        $path = ltrim(explode('?', $path)[0], '/');
        foreach (content_type_rules($db) as $rule => $kind) {
            if ($path === $rule || (str_ends_with($rule, '/') || str_ends_with($rule, '_')) && str_starts_with($path, $rule)) return $kind;
        }
        return null;
    }
}
