<?php
// IELTS Academic Introduction — Class 1, item 3 of the crash course (lesson): the exam itself, part by part, and how it is scored
// (founder, 2026-10-24). Shared structure and facts in includes/exam_introduction.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/exam_introduction.php';

render_lesson_shell($db, 'IELTS_Aca_1Mo', 1, 3, crash_exam_introduction_body($db, 'IELTS_Aca_1Mo', 'ielts_academic'));
