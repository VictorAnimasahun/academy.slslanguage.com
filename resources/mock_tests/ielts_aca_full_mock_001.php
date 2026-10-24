<?php
/**
 * IELTS Academic Mock Test 1 — session launcher (Cambridge IELTS 13 Academic, Test 1; migration 172).
 * Same engine as the General Training Full Mocks: Listening -> Reading -> Writing -> Speaking (tutor-administered).
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}
require_once INCLUDES_PATH . '/course_lock.php';
require_course_enrollment(course_ids_for_folders(['IELTS_Aca_1Mo', 'IELTS_Aca_2Mo', 'IELTS_Aca_3Mo']), 'this IELTS Academic mock test');

$student_id = (int)$_SESSION['user_id'];

// Look up the full mock test container
$stmt = $db->prepare("SELECT id, code FROM tests WHERE code = 'IELTS_ACA_FULL_MOCK_001' AND is_active = 1 LIMIT 1");
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("IELTS Academic Mock Test 1 is not set up yet (migration 172). Please contact your instructor.");
}

$test_id  = (int)$test['id'];
$mockCode = $test['code'];

// Resume an existing in-progress session if one exists
$stmt = $db->prepare("
    SELECT * FROM mock_sessions
    WHERE student_id = ? AND mock_test_id = ? AND status = 'in_progress'
    ORDER BY created_at DESC LIMIT 1
");
$stmt->execute([$student_id, $test_id]);
$existing = $stmt->fetch(PDO::FETCH_ASSOC);

if ($existing) {
    $sid = $existing['id'];
    if (is_null($existing['listening_attempt_id'])) {
        header("Location: aca_full_mock_001_listening.php?session_id={$sid}");
    } elseif (is_null($existing['reading_attempt_id'])) {
        header("Location: aca_full_mock_001_reading.php?session_id={$sid}");
    } elseif (is_null($existing['writing_attempt_id'])) {
        header("Location: mock_writing.php?session_id={$sid}");
    } else {
        header("Location: mock_speaking.php?session_id={$sid}");
    }
    exit();
}

// Create a new session and start with Listening
$stmt = $db->prepare("INSERT INTO mock_sessions (mock_test_id, student_id, status) VALUES (?, ?, 'in_progress')");
$stmt->execute([$test_id, $student_id]);
$session_id = (int)$db->lastInsertId();

header("Location: aca_full_mock_001_listening.php?session_id={$session_id}");
exit();
