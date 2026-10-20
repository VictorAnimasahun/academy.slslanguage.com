<?php
// POST /api/v1/lesson_complete.php  body: {"lesson_id": 123, "path": "courses/.../lesson.php?from=..."}
// Marks ONE LESSON ITEM of a class complete (founder, 2026-10-19: only lessons are completed by hand, and only
// after a set time on the page -- 5 minutes from first opening it, checked here, not just in the app). The
// class then ticks only when every counted item in it is done (includes/item_progress.php), and the course's
// progress is recomputed. Quizzes and tests complete from their own recorded results.
//   200 {"success":true,"progress":40,"classCompleted":false}
//   409 {"error":"...","secondsLeft":120}   too soon / not opened / not a lesson
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once INCLUDES_PATH . '/admin_check.php';
require_once INCLUDES_PATH . '/item_progress.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$studentId = (int) $student['id'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$lessonId = (int) ($input['lesson_id'] ?? 0);
$path = trim((string) ($input['path'] ?? ''));

$lesson = $lessonId ? executeQuery($db, "SELECT l.id, m.course_id FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.id = ?", [$lessonId])->fetch(PDO::FETCH_ASSOC) : null;
if (!$lesson) {
    http_response_code(404);
    echo json_encode(['error' => 'Class not found']);
    exit();
}
$courseId = (int) $lesson['course_id'];

// Enrolled students -- and staff/testers, who see every course without enrolling.
$enrolled = executeQuery($db, "SELECT 1 FROM enrollments WHERE student_id = ? AND course_id = ?", [$studentId, $courseId])->fetch();
if (!$enrolled && !is_platform_admin($studentId)) {
    http_response_code(403);
    echo json_encode(['error' => 'Not enrolled in this course']);
    exit();
}

if ($path === '') {
    // Older app builds marked the whole class with no lesson named; that is no longer allowed.
    http_response_code(409);
    echo json_encode(['error' => 'Please update the app to mark lessons complete.']);
    exit();
}

$r = item_progress_complete($db, $studentId, $lessonId, $path);
if (isset($r['error'])) {
    http_response_code(409);
    echo json_encode(['error' => $r['error'], 'secondsLeft' => $r['seconds_left'] ?? null]);
    exit();
}

$percent = item_progress_sync_course($db, $studentId, $courseId);
$classDone = (bool) executeQuery($db, "SELECT completed FROM lesson_progress WHERE student_id = ? AND lesson_id = ?", [$studentId, $lessonId])->fetchColumn();
echo json_encode(['success' => true, 'progress' => $percent, 'classCompleted' => $classDone]);
