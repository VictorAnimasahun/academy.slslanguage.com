<?php
/**
 * IELTS General Training Diagnostic Test — session launcher.
 * Sibling of ielts_aca_diagnostic.php — same DB-driven mock session system,
 * registered under IELTS_GT_DIAGNOSTIC in mock_test_map.php. Listening and
 * Writing reuse the Academic diagnostic's files (see that map entry's
 * comment); only Reading has its own file/content.
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$student_id = (int)$_SESSION['user_id'];

$stmt = $db->prepare("SELECT id, code FROM tests WHERE code = 'IELTS_GT_DIAGNOSTIC' AND is_active = 1 LIMIT 1");
$stmt->execute();
$test = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("The diagnostic test is not configured yet. Please contact your instructor.");
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
        header("Location: diagnostic_aca_listening.php?session_id={$sid}");
    } elseif (is_null($existing['reading_attempt_id'])) {
        header("Location: diagnostic_gt_reading.php?session_id={$sid}");
    } elseif (is_null($existing['writing_attempt_id'])) {
        header("Location: diagnostic_aca_writing.php?session_id={$sid}");
    } else {
        header("Location: diagnostic_aca_speaking.php?session_id={$sid}");
    }
    exit();
}

// Create a new session and start with Listening
$stmt = $db->prepare("INSERT INTO mock_sessions (mock_test_id, student_id, status) VALUES (?, ?, 'in_progress')");
$stmt->execute([$test_id, $student_id]);
$session_id = (int)$db->lastInsertId();

header("Location: diagnostic_aca_listening.php?session_id={$session_id}");
exit();
