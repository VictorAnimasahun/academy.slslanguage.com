<?php
// GET /api/mobile_courses.php -- the course list for the native app.
// Visible courses only, with whether the signed-in student is enrolled and their progress.
// Structure (weeks, classes) comes from mobile_course.php; lesson page content is not served here yet.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);

try {
    $stmt = $db->prepare(
        "SELECT c.id, c.title, c.folder_name, c.exam_variant, c.length_months, c.availability,
                c.description, c.instructor_name, c.total_lessons,
                e.progress_percentage AS progress, (e.id IS NOT NULL) AS enrolled
           FROM courses c
           LEFT JOIN enrollments e ON e.course_id = c.id AND e.student_id = ?
          WHERE c.is_visible = 1
          ORDER BY c.is_free DESC, c.length_months, c.title"
    );
    $stmt->execute([$student['id']]);

    $courses = array_map(fn ($c) => [
        'id' => (int) $c['id'],
        'title' => $c['title'],
        'exam' => $c['exam_variant'],
        'lengthMonths' => $c['length_months'] !== null ? (int) $c['length_months'] : null,
        'availability' => $c['availability'],
        'description' => $c['description'],
        'instructor' => $c['instructor_name'],
        'totalClasses' => (int) $c['total_lessons'],
        'enrolled' => (bool) $c['enrolled'],
        'progress' => $c['progress'] !== null ? (int) $c['progress'] : 0,
    ], $stmt->fetchAll(PDO::FETCH_ASSOC));

    echo json_encode(['success' => true, 'courses' => $courses], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    error_log('mobile_courses error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not load courses']);
}
