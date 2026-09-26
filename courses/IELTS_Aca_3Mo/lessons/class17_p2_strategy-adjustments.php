<?php
// Strategy Adjustments — IELTS Academic Masterclass — 3 Months, Class 17, piece 2 (lesson).
// EMPTY SHELL. To develop this lesson, put its HTML between the two LESSON BODY markers below.
// Everything around it (login, plan check, header, back link) comes from includes/lesson_shell.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';

ob_start(); ?>
<!-- LESSON BODY START -->

<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 17, 2, ob_get_clean());
