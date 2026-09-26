<?php
// AI Score Review & Final Targeted Practice — PTE Academic Masterclass — 3 Months, Class 23, piece 1 (lesson).
// EMPTY SHELL. To develop this lesson, put its HTML between the two LESSON BODY markers below.
// Everything around it (login, plan check, header, back link) comes from includes/lesson_shell.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';

ob_start(); ?>
<!-- LESSON BODY START -->

<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 23, 1, ob_get_clean());
