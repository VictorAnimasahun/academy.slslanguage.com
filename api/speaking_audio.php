<?php
// /academy/api/speaking_audio.php
// Serves a student's own speaking recording (playback or download), gated on
// the recording belonging to their own session. Admin playback goes through
// sls-admin's own copy of this script instead (separate auth realm).
require_once dirname(__DIR__) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    http_response_code(400);
    exit('Missing id');
}

$stmt = $db->prepare("SELECT student_id, audio_path, test_code, task_number FROM speaking_recordings WHERE id = ?");
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || (int)$row['student_id'] !== (int)$_SESSION['user_id']) {
    http_response_code(404);
    exit('Not found');
}

if (empty($row['audio_path'])) {
    http_response_code(410);
    exit('This recording has been deleted');
}

$fullPath = ACADEMY_ROOT . '/' . $row['audio_path'];
if (!is_file($fullPath)) {
    http_response_code(404);
    exit('File missing on disk');
}

$download = isset($_GET['download']);
header('Content-Type: audio/webm');
header('Content-Length: ' . filesize($fullPath));
if ($download) {
    $downloadName = $row['test_code'] . '_task' . $row['task_number'] . '.webm';
    header('Content-Disposition: attachment; filename="' . $downloadName . '"');
} else {
    header('Content-Disposition: inline');
}
readfile($fullPath);
