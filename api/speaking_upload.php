<?php
// /academy/api/speaking_upload.php
// Receives one recorded speaking-task audio blob, saves it to disk, creates a
// speaking_recordings row, and transcribes it via Groq's free-tier Whisper.
// Used by the CELPIP speaking practice tests (and, from Phase 2, the CELPIP
// Full Mock speaking flow via the optional mock_session_id field).
require_once dirname(__DIR__) . '/bootstrap.php';
require_once CONFIG_PATH . '/api_keys.php';
require_once INCLUDES_PATH . '/ai_client.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized. Please log in.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST required']);
    exit();
}

$studentId   = (int)$_SESSION['user_id'];
$testCode    = trim($_POST['test_code'] ?? '');
$taskNumber  = (int)($_POST['task_number'] ?? 0);
$taskTitle   = trim($_POST['task_title'] ?? '');
$prompt      = trim($_POST['prompt'] ?? '');
$mockSessionId = isset($_POST['mock_session_id']) && $_POST['mock_session_id'] !== ''
    ? (int)$_POST['mock_session_id']
    : null;

if ($testCode === '' || $taskNumber < 1) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing test_code or task_number']);
    exit();
}

if (!isset($_FILES['audio']) || $_FILES['audio']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => 'No audio file uploaded']);
    exit();
}

// Store under academy/media/speaking_recordings/{student_id}/, filenames are
// not guessable (random token) since this directory is web-reachable and
// playback/download goes through the auth-checked speaking_audio.php instead
// of a direct link, but there's no reason to make enumeration easy either.
$studentDir = ACADEMY_ROOT . '/media/speaking_recordings/' . $studentId;
if (!is_dir($studentDir)) {
    mkdir($studentDir, 0755, true);
}
$token = bin2hex(random_bytes(8));
$filename = preg_replace('/[^A-Za-z0-9_]/', '_', $testCode) . '_task' . $taskNumber . '_' . $token . '.webm';
$destPath = $studentDir . '/' . $filename;

if (!move_uploaded_file($_FILES['audio']['tmp_name'], $destPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save audio file']);
    exit();
}

// Store the path relative to ACADEMY_ROOT so it stays valid if the app moves.
$relativePath = 'media/speaking_recordings/' . $studentId . '/' . $filename;

try {
    $stmt = $db->prepare("
        INSERT INTO speaking_recordings
            (student_id, test_code, mock_session_id, task_number, task_title, prompt, audio_path, transcript_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'transcribing')
    ");
    $stmt->execute([$studentId, $testCode, $mockSessionId, $taskNumber, $taskTitle, $prompt, $relativePath]);
    $recordingId = (int)$db->lastInsertId();
} catch (PDOException $e) {
    error_log('speaking_upload.php insert failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error saving recording']);
    exit();
}

// Transcribe synchronously. If Groq isn't configured yet (placeholder key)
// or the call fails, the audio and DB row are still saved -- transcription
// can be retried later without re-recording.
$transcript = null;
$status = 'failed';
if (defined('GROQ_API_KEY') && GROQ_API_KEY !== '' && strpos(GROQ_API_KEY, 'YOUR_GROQ_KEY') === false) {
    $result = transcribeWithGroq($destPath);
    if ($result['success']) {
        $transcript = trim($result['text']);
        $status = 'done';
    } else {
        error_log('speaking_upload.php transcription failed: ' . $result['error']);
    }
} else {
    error_log('speaking_upload.php: GROQ_API_KEY not configured, skipping transcription');
}

$db->prepare("UPDATE speaking_recordings SET transcript = ?, transcript_status = ? WHERE id = ?")
   ->execute([$transcript, $status, $recordingId]);

echo json_encode([
    'success' => true,
    'recording_id' => $recordingId,
    'transcript' => $transcript,
    'transcript_status' => $status,
]);
