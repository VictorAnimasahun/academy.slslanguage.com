<?php
// CELPIP Introduction — CELPIP General — 3-Month Masterclass, Class 2, piece 1 (lesson): the exam itself, part by part, and
// how it is scored. The crash course's exam introduction, reused (founder, 2026-10-27); the course's own weeks are listed.
// Shared structure and facts in includes/exam_introduction.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';
require_once INCLUDES_PATH . '/exam_introduction.php';

render_lesson_shell($db, 'CELPIP_Gen_3Mo', 2, 1, crash_exam_introduction_body($db, 'CELPIP_Gen_3Mo', 'celpip'));
