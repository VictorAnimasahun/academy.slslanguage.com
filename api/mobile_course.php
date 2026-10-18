<?php
// GET /api/mobile_course.php?id=N -- one course's weeks and classes, for the native app.
// Each class lists its pieces (lesson / resource / practice test / mock test) and whether each is ready or Coming Soon.

require_once dirname(__DIR__) . '/bootstrap.php';
require_once __DIR__ . '/mobile_auth.php';
require_once INCLUDES_PATH . '/admin_check.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'A course id is required']);
    exit();
}

try {
    $c = $db->prepare(
        "SELECT c.id, c.title, c.exam_variant, c.length_months, c.availability, c.description, c.instructor_name,
                c.total_lessons, c.is_free, c.free_preview_classes, c.buy_url,
                e.progress_percentage AS progress, (e.id IS NOT NULL) AS enrolled
           FROM courses c
           LEFT JOIN enrollments e ON e.course_id = c.id AND e.student_id = ?
          WHERE c.id = ? AND c.is_visible = 1"
    );
    $c->execute([$student['id'], $id]);
    $course = $c->fetch(PDO::FETCH_ASSOC);
    if (!$course) {
        http_response_code(404);
        echo json_encode(['error' => 'Course not found']);
        exit();
    }

    $rows = $db->prepare(
        "SELECT m.module_order, m.module_title, l.id AS lesson_id, l.lesson_order, l.title AS class_title,
                lp.id AS part_id, lp.part_order, lp.title AS part_title, lp.kind, lp.status, lp.file_path,
                l.file_path AS class_file_path,
                (prog.completed = 1) AS class_completed
           FROM modules m
           JOIN lessons l ON l.module_id = m.id
           LEFT JOIN lesson_parts lp ON lp.lesson_id = l.id
           LEFT JOIN lesson_progress prog ON prog.lesson_id = l.id AND prog.student_id = ?
          WHERE m.course_id = ?
          ORDER BY m.module_order, l.lesson_order, lp.part_order"
    );
    $rows->execute([$student['id'], $id]);

    $weeks = [];
    foreach ($rows->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $w = (int) $r['module_order'];
        $weeks[$w] ??= ['number' => $w, 'title' => $r['module_title'], 'classes' => []];

        $classNumber = ($w - 1) * 2 + (int) $r['lesson_order'];
        $weeks[$w]['classes'][$r['lesson_id']] ??= [
            'id' => (int) $r['lesson_id'],
            'number' => $classNumber,
            'title' => $r['class_title'],
            'completed' => (bool) $r['class_completed'],
            'pieces' => [],
        ];
        if ($r['part_id'] !== null) {
            // Older courses (IELTS GT, CELPIP) keep the page on the class, not the piece. The website
            // opens the class page in that case (includes/course_progress_path.php), so the app does too.
            $filePath = $r['file_path'];
            if (($filePath === null || $filePath === '') && $r['status'] === 'ready'
                && in_array($r['kind'], ['lesson', 'resource'], true)) {
                $filePath = $r['class_file_path'] ?: null;
            }
            $weeks[$w]['classes'][$r['lesson_id']]['pieces'][] = [
                'id' => (int) $r['part_id'],
                'title' => $r['part_title'],
                'kind' => $r['kind'],
                'comingSoon' => $r['status'] === 'coming_soon',
                'filePath' => $filePath,
            ];
        }
    }
    foreach ($weeks as &$week) {
        $week['classes'] = array_values($week['classes']);
    }
    unset($week);

    echo json_encode([
        'success' => true,
        'course' => [
            'id' => (int) $course['id'],
            'title' => $course['title'],
            'exam' => $course['exam_variant'],
            'lengthMonths' => $course['length_months'] !== null ? (int) $course['length_months'] : null,
            'availability' => $course['availability'],
            'description' => $course['description'],
            'instructor' => $course['instructor_name'],
            'totalClasses' => (int) $course['total_lessons'],
            'isFree' => (bool) $course['is_free'],
            'freePreviewClasses' => (int) $course['free_preview_classes'],
            'buyUrl' => $course['buy_url'],
            'enrolled' => (bool) $course['enrolled'],
            // Staff/admin and testers open every class on the website (includes/admin_check.php); same here.
            'fullAccess' => is_platform_admin((int) $student['id']),
            'progress' => $course['progress'] !== null ? (int) $course['progress'] : 0,
            'weeks' => array_values($weeks),
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    error_log('mobile_course error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not load this course']);
}
