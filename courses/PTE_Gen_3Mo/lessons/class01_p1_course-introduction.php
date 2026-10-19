<?php
// Course Introduction — PTE Academic Masterclass — 3 Months, Class 1, piece 1 (lesson).
// Class 1 introduces the COURSE; Class 2 introduces the PTE Academic exam (founder, 2026-10-19). The body is built
// from the course's own data (includes/course_introduction.php), so it always matches the real timetable.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/course_introduction.php';

render_lesson_shell($db, 'PTE_Gen_3Mo', 1, 1, course_introduction_body($db, 'PTE_Gen_3Mo', 'PTE Academic', 'PTE score'));
