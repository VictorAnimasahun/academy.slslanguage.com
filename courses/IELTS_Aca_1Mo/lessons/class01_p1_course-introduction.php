<?php
// Course Introduction — IELTS Academic Crash Course — 1 Month, Class 1, piece 1 (lesson).
// A crash course compresses its whole introduction into Class 1: this, the IELTS Academic Introduction, the introduction quiz,
// the Foundational English Assessment and the diagnostic (founder, 2026-10-21). Built from the course's own data
// (includes/course_introduction.php), so it always matches the real timetable.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/course_introduction.php';

render_lesson_shell($db, 'IELTS_Aca_1Mo', 1, 1, crash_course_introduction_body($db, 'IELTS_Aca_1Mo', 'IELTS Academic', 'band score'));
