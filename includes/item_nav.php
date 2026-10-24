<?php
/**
 * Previous / Back to course / Next for pages shared by many courses: the Foundational English Assessment, class
 * quizzes, diagnostic tests (founder, 2026-10-24: "some of the pages in the class 1 don't have buttons taking students
 * to the next page/item or the previous page"). Lesson pages have their own (includes/lesson_shell.php).
 *
 * Which course: the course page's links carry ?from=<folder> (course_item_url). It is remembered for the session, so a
 * test that moves through several pages (the IELTS diagnostics) still knows at the end.
 *
 *   echo item_nav_html($db, 'resources/placement/foundational_english_assessment.php');
 *   echo item_nav_html($db, ['resources/mock_tests/ielts_aca_diagnostic.php', 'resources/mock_tests/ielts_gt_diagnostic.php']);
 */
require_once __DIR__ . '/course_shape.php';

if (!function_exists('item_nav_html')) {
    /** The course this page was opened from: ?from=, else the last one remembered this session. */
    function item_nav_course(PDO $db): ?array {
        $from = $_GET['from'] ?? null;
        if (is_string($from) && preg_match('/^[A-Za-z0-9_]{1,64}$/', $from) && ($c = course_by_folder($db, $from))) {
            $_SESSION['item_nav_from'] = $from;
            return $c;
        }
        $saved = $_SESSION['item_nav_from'] ?? null;
        return is_string($saved) ? course_by_folder($db, $saved) : null;
    }

    /** @param string|string[] $paths this item's path(s) as stored on the course (the first one the course has is used) */
    function item_nav_html(PDO $db, $paths): string {
        $course = item_nav_course($db);
        if (!$course) return '';
        $near = ['found' => false];
        foreach ((array) $paths as $p) {
            $near = course_item_neighbours($db, $course, $p);
            if ($near['found']) break;
        }
        if (!$near['found']) return '';
        $h = fn ($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
        $back = ACADEMY_URL . 'courses/' . $course['folder'] . '/course_overview.php';
        $out = '<div class="item-nav" style="display:flex;flex-wrap:wrap;justify-content:space-between;gap:.75rem 1rem;margin-top:2rem;">';
        if ($near['prev']) {
            $out .= '<a href="' . $h($near['prev']['url']) . '" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Previous: ' . $h($near['prev']['title']) . '</a>';
        }
        $out .= '<a href="' . $h($back) . '" class="btn btn-outline-secondary">' . ($near['prev'] ? '' : '<i class="bi bi-arrow-left me-1"></i>') . 'Back to course</a>';
        if ($near['next']) {
            $out .= '<a href="' . $h($near['next']['url']) . '" class="btn btn-primary" style="background-color:#0b77ff;border-color:#0b77ff;">Next: ' . $h($near['next']['title']) . ' <i class="bi bi-arrow-right ms-1"></i></a>';
        }
        return $out . '</div>';
    }
}
