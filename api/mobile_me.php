<?php
// Returns the authenticated student's profile. Used to prove the bearer-token
// flow works end to end, and as the pattern for future authenticated mobile endpoints.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
header('Content-Type: application/json');

$student = requireMobileAuth($db);

echo json_encode([
    'success' => true,
    'student' => $student,
]);
