<?php
/**
 * A class title in the database can carry several pieces of content joined with
 * " + " (e.g. "Writing Test 1 (Timed) + Listening Formats"). The "+" is only a
 * storage separator: everywhere it is shown, each piece goes on its own line,
 * never tied together with a "+".
 */
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
