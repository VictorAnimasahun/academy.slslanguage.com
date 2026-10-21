<?php
// POST /api/lesson_complete_web.php  body: {"lesson_id": 123, "path": "courses/.../lesson.php"}
// The website's "Mark complete" (includes/lesson_complete_bar.php): the same rules as the app's
// api/v1/lesson_complete.php -- lesson items only, 5 minutes after first opening, checked here -- with the website's
// login instead of the app token. The class ticks once every counted item in it is done.
//   200 {"success":true,"progress":40,"classCompleted":false}
//   409 {"error":"...","secondsLeft":120}
require_once dirname(__DIR__) . '/bootstrap.php';
require_once INCLUDES_PATH . '/admin_check.php';
require_once INCLUDES_PATH . '/item_progress.php';
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in again.']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$studentId = (int) $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$lessonId = (int) ($input['lesson_id'] ?? 0);
$path = trim((string) ($input['path'] ?? ''));

$st = $db->prepare("SELECT l.id, m.course_id FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.id = ?");
$st->execute([$lessonId]);
$lesson = $st->fetch(PDO::FETCH_ASSOC);
if (!$lesson || $path === '') {
    http_response_code(404);
    echo json_encode(['error' => 'Class not found']);
    exit();
}
$courseId = (int) $lesson['course_id'];

$st = $db->prepare("SELECT 1 FROM enrollments WHERE student_id = ? AND course_id = ?");
$st->execute([$studentId, $courseId]);
if (!$st->fetch() && !is_platform_admin($studentId)) {
    http_response_code(403);
    echo json_encode(['error' => 'Not enrolled in this course']);
    exit();
}

$r = item_progress_complete($db, $studentId, $lessonId, $path);
if (isset($r['error'])) {
    http_response_code(409);
    echo json_encode(['error' => $r['error'], 'secondsLeft' => $r['seconds_left'] ?? null]);
    exit();
}

$percent = item_progress_sync_course($db, $studentId, $courseId);
$st = $db->prepare("SELECT completed FROM lesson_progress WHERE student_id = ? AND lesson_id = ?");
$st->execute([$studentId, $lessonId]);
echo json_encode(['success' => true, 'progress' => $percent, 'classCompleted' => (bool) $st->fetchColumn()]);
