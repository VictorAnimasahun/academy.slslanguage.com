<?php
// POST /api/v1/course_enroll.php {course_id} -- join a course from the app's new Browse tab
// (SLS Academy prototype's "Start this free course" / Browse flow). Mirrors
// courses/courses_detail.php's own enrol branch exactly (coming-soon block, Selar block, the
// same generateAssignmentsForEnrollment() call) rather than reimplementing the rule, so mobile
// and web behave identically -- including once FREE_ACCESS_FOR_ALL (config/tier_access.php) is
// eventually turned off and Selar-sold courses start blocking real self-enrol again.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once INCLUDES_PATH . '/coming_soon.php';
require_once INCLUDES_PATH . '/tier_access.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$courseId = (int) ($input['course_id'] ?? 0);
if ($courseId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'A course id is required']);
    exit();
}

$stmt = $db->prepare(
    "SELECT c.*, (e.student_id IS NOT NULL) AS is_enrolled
       FROM courses c
       LEFT JOIN enrollments e ON e.course_id = c.id AND e.student_id = ?
      WHERE c.id = ? AND c.is_visible = 1"
);
$stmt->execute([$student['id'], $courseId]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$course) {
    http_response_code(404);
    echo json_encode(['error' => 'Course not found']);
    exit();
}

if ($course['is_enrolled']) {
    echo json_encode(['success' => true, 'alreadyEnrolled' => true]);
    exit();
}

if (course_is_coming_soon($course)) {
    http_response_code(409);
    echo json_encode(['error' => 'This course is coming soon.']);
    exit();
}

$selarBlocked = false;
if (is_file(CONFIG_PATH . '/selar_purchases.php')) {
    require_once CONFIG_PATH . '/selar_purchases.php';
    $selarBlocked = selar_enrolment_blocked($course);
}
if ($selarBlocked) {
    http_response_code(403);
    echo json_encode(['error' => 'This course is bought on Selar. After you pay, choose it from your dashboard.']);
    exit();
}

try {
    $db->prepare(
        "INSERT INTO enrollments (student_id, course_id, enrolled_at, progress_percentage) VALUES (?, ?, NOW(), 0)"
    )->execute([$student['id'], $courseId]);

    require_once INCLUDES_PATH . '/course_pacing.php';
    generateAssignmentsForEnrollment($db, $student['id'], $courseId);

    echo json_encode(['success' => true, 'alreadyEnrolled' => false]);
} catch (PDOException $e) {
    error_log('course_enroll error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not join this course. Please try again.']);
}
