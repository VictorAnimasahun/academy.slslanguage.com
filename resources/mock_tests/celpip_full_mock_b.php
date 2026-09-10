<?php
/**
 * CELPIP Full Mock Test B — session launcher.
 * Routes through the DB-driven mock session system, same pattern as
 * ielts_full_mock_003.php. Content seeded by migration 073.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$student_id = (int)$_SESSION['user_id'];

$stmt = $db->prepare("SELECT id, code FROM tests WHERE code = 'CELPIP_FULL_MOCK_B' AND is_active = 1 LIMIT 1");
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("CELPIP Full Mock Test B is not configured yet. Please run the database migrations for this mock and contact your instructor.");
}

$test_id  = (int)$test['id'];

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
        header("Location: celpip_full_mock_listening.php?session_id={$sid}");
    } elseif (is_null($existing['reading_attempt_id'])) {
        header("Location: celpip_full_mock_reading.php?session_id={$sid}");
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

header("Location: celpip_full_mock_listening.php?session_id={$session_id}");
exit();
