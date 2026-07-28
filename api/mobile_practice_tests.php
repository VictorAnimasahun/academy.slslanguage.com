<?php
// Lists standalone practice tests (IELTS_PT_*, CELPIP_PT_*) for the mobile app.
// Excludes mock tests, vocabulary quizzes, and other `tests` rows that aren't
// part of the documented "one test = one section" practice test catalog
// (see academy/resources/practice_tests/DOCUMENTATION.md).

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
header('Content-Type: application/json');

requireMobileAuth($db);

$stmt = $db->prepare(
    "SELECT code, title, category, test_type, duration_minutes, total_questions
     FROM tests
     WHERE is_active = 1
       AND (code LIKE 'IELTS_PT_%' OR code LIKE 'CELPIP_PT_%')
     ORDER BY code"
);
$stmt->execute();
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'tests' => $tests,
]);
