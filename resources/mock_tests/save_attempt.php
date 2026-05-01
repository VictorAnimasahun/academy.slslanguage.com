<?php
/**
 * Save mock test section attempt.
 * Accepts JSON POST: { mock_code, section_type, section_order, answers: {q_number: value, ...} }
 * Returns JSON: { success, attempt_id } or { error }
 */
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'POST required']);
    exit();
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body) {
    echo json_encode(['error' => 'Invalid JSON']);
    exit();
}

$studentId    = (int) $_SESSION['user_id'];
$mockCode     = trim($body['mock_code'] ?? '');
$sectionType  = trim($body['section_type'] ?? '');
$sectionOrder = (int) ($body['section_order'] ?? 1);
$answers      = $body['answers'] ?? [];
$timeTaken    = (int) ($body['time_taken_seconds'] ?? 0);

if (!$mockCode || !$sectionType || empty($answers)) {
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

try {
    // Look up the test record for this mock section
    $stmt = $db->prepare("
        SELECT t.id AS test_id
        FROM mock_exam_sections ms
        JOIN tests t ON t.code = ms.test_code
        WHERE ms.mock_code = ? AND ms.section_type = ?
        LIMIT 1
    ");
    $stmt->execute([$mockCode, $sectionType]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['error' => 'Section not found in DB']);
        exit();
    }

    $testId = (int) $row['test_id'];

    // Resolve attempt number
    $stmt = $db->prepare("
        SELECT COALESCE(MAX(attempt_number), 0) + 1 AS next
        FROM test_attempts
        WHERE student_id = ? AND test_id = ?
    ");
    $stmt->execute([$studentId, $testId]);
    $attemptNumber = (int) $stmt->fetchColumn();

    // Create the attempt record
    $stmt = $db->prepare("
        INSERT INTO test_attempts (student_id, test_id, attempt_number, mode, started_at, completed_at, status)
        VALUES (?, ?, ?, 'mock', NOW() - INTERVAL ? SECOND, NOW(), 'completed')
    ");
    $stmt->execute([$studentId, $testId, $attemptNumber, $timeTaken]);
    $attemptId = (int) $db->lastInsertId();

    // Store all answers as a single JSON blob.
    // question_id = 0 is a sentinel meaning "full mock answer set".
    // Keys use prefixes: L1-L40 (listening), R1-R40 (reading), W1/W2 (writing).
    $db->prepare("
        INSERT INTO attempt_answers (attempt_id, question_id, answer_text)
        VALUES (?, 0, ?)
    ")->execute([$attemptId, json_encode($answers)]);

    echo json_encode(['success' => true, 'attempt_id' => $attemptId]);

} catch (PDOException $e) {
    error_log('save_attempt error: ' . $e->getMessage());
    echo json_encode(['error' => 'Database error']);
}
