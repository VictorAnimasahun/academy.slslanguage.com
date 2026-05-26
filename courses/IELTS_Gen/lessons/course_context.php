<?php
if (defined('COURSE_CONTEXT_LOADED')) return;
define('COURSE_CONTEXT_LOADED', true);

// Determines which course the student arrived from, for breadcrumb navigation.
// ?from= sets it on first entry; session preserves it for sequential navigation.
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

if (isset($_GET['from']) && isset($_contexts[preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from'])])) {
    $_from = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from']);
    $_SESSION['ielts_gen_from'] = $_from;
} else {
    $_from = $_SESSION['ielts_gen_from'] ?? 'IELTS_Gen_Mst';
}

$back = $_contexts[$_from] ?? $_contexts['IELTS_Gen_Mst'];
unset($_from, $_contexts);
