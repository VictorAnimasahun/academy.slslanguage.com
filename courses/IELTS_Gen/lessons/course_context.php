<?php
if (defined('COURSE_CONTEXT_LOADED')) return;
define('COURSE_CONTEXT_LOADED', true);

// Which course the student is in. These lesson pages are shared by every length of the course, so
// ?from=<course folder> says which one (the website's course pages and the app both send it); the
// session keeps it for page-to-page navigation. Name, class count and timetable come from the
// database via includes/course_shape.php -- never typed into a page (founder, 2026-10-19).
require_once INCLUDES_PATH . '/course_shape.php';

$_folders = ['IELTS_Gen_Mst', 'IELTS_Gen_2Mo', 'IELTS_Gen_1Mo'];
$_from = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from'] ?? '');
if (in_array($_from, $_folders, true)) {
    $_SESSION['ielts_gen_from'] = $_from;
} else {
    $_from = $_SESSION['ielts_gen_from'] ?? 'IELTS_Gen_Mst';
}

$ctxCourse = course_by_folder($db, $_from) ?? course_by_folder($db, 'IELTS_Gen_Mst');
$back = [
    'name' => $ctxCourse['title'] ?? '',
    'url'  => ACADEMY_URL . 'courses/' . ($ctxCourse['folder'] ?? 'IELTS_Gen_Mst') . '/course_overview.php',
];
unset($_from, $_folders);
