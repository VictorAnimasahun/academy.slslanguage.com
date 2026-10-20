<?php
// POST /api/v1/lesson_item_open.php  body: {"lesson_id": 123, "path": "courses/.../page.php?from=..."}
// Starts a lesson page's clock: the first time a student opens it is recorded, and "Mark complete" is refused
// until 5 minutes after that (includes/item_progress.php; founder, 2026-10-19). Re-opening keeps the first time.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once INCLUDES_PATH . '/item_progress.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}
$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$lessonId = (int) ($input['lesson_id'] ?? 0);
$path = trim((string) ($input['path'] ?? ''));
$exists = $lessonId && $path !== '' ? executeQuery($db, "SELECT 1 FROM lesson_parts WHERE lesson_id = ? AND kind = 'lesson'", [$lessonId])->fetch() : false;
if (!$exists) {
    http_response_code(404);
    echo json_encode(['error' => 'Lesson not found']);
    exit();
}
item_progress_open($db, (int) $student['id'], $lessonId, $path);
echo json_encode(['success' => true]);
