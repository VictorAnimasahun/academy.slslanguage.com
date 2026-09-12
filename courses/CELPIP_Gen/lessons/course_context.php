<?php
if (defined('COURSE_CONTEXT_LOADED')) return;
define('COURSE_CONTEXT_LOADED', true);

// Determines which course the student arrived from, for breadcrumb navigation.
// Month 1 content is shared by all three CELPIP General plans; Month 2 is
// shared by the 2- and 3-month plans; Month 3 belongs to the 3-month plan
// only (files for months a course doesn't include simply aren't linked to
// from that course's lessons, so this context list can safely include all
// three regardless of which month is currently open).
$_contexts = [
    'CELPIP_Gen_3Mo' => [
        'name' => 'CELPIP General Masterclass — 3 Months',
        'url'  => ACADEMY_URL . 'courses/CELPIP_Gen_3Mo/course_overview.php',
    ],
    'CELPIP_Gen_2Mo' => [
        'name' => 'CELPIP General Masterclass — 2 Months',
        'url'  => ACADEMY_URL . 'courses/CELPIP_Gen_2Mo/course_overview.php',
    ],
    'CELPIP_Gen_1Mo' => [
        'name' => 'CELPIP General — 1-Month Plan',
        'url'  => ACADEMY_URL . 'courses/CELPIP_Gen_1Mo/course_overview.php',
    ],
];

if (isset($_GET['from']) && isset($_contexts[preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from'])])) {
    $_from = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['from']);
    $_SESSION['celpip_gen_from'] = $_from;
} else {
    $_from = $_SESSION['celpip_gen_from'] ?? 'CELPIP_Gen_3Mo';
}

$back = $_contexts[$_from] ?? $_contexts['CELPIP_Gen_3Mo'];
unset($_from, $_contexts);
