<?php
// POST /api/mobile_update_goal.php  body: {"exam_type": "ielts_academic"|"ielts_general"|"celpip"|null,
//                                           "target_band": number|null, "test_date": "YYYY-MM-DD"|null}
// Updates the signed-in student's goal (exam, target band, test date) -- the mobile app's
// "Set your test date" / "Your goal" editor (SLS-Academy-App-Spec.md ME-01, HOME-02).
// baseline_band is not writable here: it is meant to come from the student's first mock result,
// not a form field (spec section 12.2), and nothing sets it yet.
// Any field left out of the body is left unchanged; sending null for a field clears it.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request body.']);
    exit();
}

$sets = [];
$params = [];

if (array_key_exists('exam_type', $input)) {
    $examType = $input['exam_type'];
    if ($examType !== null && !in_array($examType, ['ielts_academic', 'ielts_general', 'celpip'], true)) {
        http_response_code(400);
        echo json_encode(['error' => 'exam_type must be ielts_academic, ielts_general, celpip, or null.']);
        exit();
    }
    $sets[] = 'exam_type = ?';
    $params[] = $examType;
}

if (array_key_exists('target_band', $input)) {
    $targetBand = $input['target_band'];
    if ($targetBand !== null) {
        if (!is_numeric($targetBand) || $targetBand < 0 || $targetBand > 9) {
            http_response_code(400);
            echo json_encode(['error' => 'target_band must be a number from 0 to 9, or null.']);
            exit();
        }
        // The IELTS scale moves in half-band steps; round so e.g. 6.3 becomes 6.5, not stored raw.
        $targetBand = round($targetBand * 2) / 2;
    }
    $sets[] = 'target_band = ?';
    $params[] = $targetBand;
}

if (array_key_exists('test_date', $input)) {
    $testDate = $input['test_date'];
    if ($testDate !== null) {
        $parsed = DateTime::createFromFormat('Y-m-d', $testDate);
        if (!$parsed || $parsed->format('Y-m-d') !== $testDate) {
            http_response_code(400);
            echo json_encode(['error' => 'test_date must be in YYYY-MM-DD format, or null.']);
            exit();
        }
    }
    $sets[] = 'test_date = ?';
    $params[] = $testDate;
}

if (!$sets) {
    http_response_code(400);
    echo json_encode(['error' => 'Nothing to update. Send exam_type, target_band and/or test_date.']);
    exit();
}

try {
    $params[] = $student['id'];
    $stmt = $db->prepare('UPDATE students SET ' . implode(', ', $sets) . ' WHERE id = ?');
    $stmt->execute($params);

    $stmt = $db->prepare(
        'SELECT id, firstname, lastname, email, exam_type, target_band, baseline_band, test_date FROM students WHERE id = ?'
    );
    $stmt->execute([$student['id']]);
    $updated = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'student' => normalizeMobileStudent($updated)]);
} catch (PDOException $e) {
    error_log('mobile_update_goal error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your goal. Please try again.']);
}
