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
        $lines = array_values(array_filter(array_map('trim', preg_split('/\s+\+\s+/u', $title))));
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
