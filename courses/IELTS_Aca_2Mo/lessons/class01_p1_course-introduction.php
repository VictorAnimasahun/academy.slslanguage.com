<?php
// Course Introduction — IELTS Academic Masterclass — 2 Months, Class 1, piece 1 (lesson).
// Class 1 introduces the COURSE; Class 2 introduces the IELTS Academic exam (founder, 2026-10-19). The body is built
// from the course's own data (includes/course_introduction.php), so it always matches the real timetable.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/course_introduction.php';

render_lesson_shell($db, 'IELTS_Aca_2Mo', 1, 1, course_introduction_body($db, 'IELTS_Aca_2Mo', 'IELTS Academic', 'band score'));
