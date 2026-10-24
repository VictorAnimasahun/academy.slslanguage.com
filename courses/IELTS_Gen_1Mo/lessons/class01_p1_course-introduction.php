<?php
// Course Introduction — IELTS General Crash Course — 1 Month, Class 1, item 1 (lesson). The crash course's own introduction (founder, 2026-10-24):
// what the course is, what is expected, the orientation, how each kind of item works. Built from the course's own data
// (includes/course_introduction.php). The 2/3-month courses keep their own introduction page.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/course_introduction.php';

render_lesson_shell($db, 'IELTS_Gen_1Mo', 1, 1, crash_course_introduction_body($db, 'IELTS_Gen_1Mo', 'IELTS General Training', 'band score'));
