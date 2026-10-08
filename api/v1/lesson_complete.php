<?php
// POST /api/v1/lesson_complete.php  body: {"lesson_id": 123}
// LSN-02: marks a class (a `lessons` row) complete and recomputes that course's enrolment
// progress. This is the first thing anywhere in the codebase that writes to `lesson_progress` --
// nothing else ever has (confirmed by reading every reference before building this); every
// `progress_percentage` currently reads 0 for the same reason. A simple explicit "Mark complete"
// action, matching the app's own Lesson screen (spec 8.19) -- the stricter web-only idea of
// gating completion on video watch time + 70% on exercises was never built either, and this
// doesn't attempt it.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$lessonId = (int) ($input['lesson_id'] ?? 0);

$lesson = $lessonId ? executeQuery($db, "SELECT l.id, m.course_id FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.id = ?", [$lessonId])->fetch(PDO::FETCH_ASSOC) : null;
if (!$lesson) {
    http_response_code(404);
    echo json_encode(['error' => 'Class not found']);
    exit();
}
$courseId = (int) $lesson['course_id'];

$enrolled = executeQuery($db, "SELECT 1 FROM enrollments WHERE student_id = ? AND course_id = ?", [$student['id'], $courseId])->fetch();
if (!$enrolled) {
    http_response_code(403);
    echo json_encode(['error' => 'Not enrolled in this course']);
    exit();
}

executeQuery($db, "
    INSERT INTO lesson_progress (student_id, lesson_id, completed, completed_at)
    VALUES (?, ?, 1, NOW())
    ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()
", [$student['id'], $lessonId]);

// Recompute this course's progress the same way the rest of the platform defines it
// (completed classes / total classes), rather than just incrementing a counter.
$total = (int) executeQuery($db, "SELECT COUNT(*) FROM lessons l JOIN modules m ON m.id = l.module_id WHERE m.course_id = ?", [$courseId])->fetchColumn();
$done = (int) executeQuery($db, "
    SELECT COUNT(*) FROM lesson_progress p
    JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
    WHERE m.course_id = ? AND p.student_id = ? AND p.completed = 1
", [$courseId, $student['id']])->fetchColumn();
$percent = $total > 0 ? (int) round($done / $total * 100) : 0;

executeQuery($db, "UPDATE enrollments SET progress_percentage = ? WHERE student_id = ? AND course_id = ?", [$percent, $student['id'], $courseId]);

echo json_encode(['success' => true, 'progress' => $percent]);
