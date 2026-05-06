<?php
if (defined('COURSE_CONTEXT_LOADED')) return;
define('COURSE_CONTEXT_LOADED', true);

// Determines which course the student arrived from, for breadcrumb navigation.
// Set by the ?from= parameter appended to lesson links in each course_overview.php.
$_from = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from'] ?? 'IELTS_Gen_Mst');

$_contexts = [
    'IELTS_Gen_Mst' => [
        'name' => 'IELTS General Masterclass',
        'url'  => ACADEMY_URL . 'courses/IELTS_Gen_Mst/course_overview.php',
    ],
    'IELTS_Gen_2Mo' => [
        'name' => 'IELTS General — 2 Month Intensive',
        'url'  => ACADEMY_URL . 'courses/IELTS_Gen_2Mo/course_overview.php',
    ],
    'IELTS_Gen_1Mo' => [
        'name' => 'IELTS General — 1 Month Starter',
        'url'  => ACADEMY_URL . 'courses/IELTS_Gen_1Mo/course_overview.php',
    ],
];

$back = $_contexts[$_from] ?? $_contexts['IELTS_Gen_Mst'];
unset($_from, $_contexts);
