<?php
// GET /api/v1/assignment_detail.php?id= -- Assignment detail (SLS-Academy-App-Spec.md 8.20).
// No per-criterion breakdown exists anywhere in this schema (only an overall band/score per
// attempt) -- this shows what's real (band or score, plus the assignment's own description as
// the instructions) rather than inventing criteria rows.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$id = (int) ($_GET['id'] ?? 0);

$row = executeQuery($db, "
    SELECT a.id, a.title, a.description, a.due_date, a.type, c.title AS course_title, c.instructor_name,
           latest.score AS attempt_score, latest.max_score AS attempt_max,
           latest.band_score AS attempt_band, latest.status AS attempt_status,
           " . academyUtcExpr('latest.completed_at') . " AS attempt_date
    FROM assignments a
    JOIN courses c ON c.id = a.course_id
    JOIN enrollments e ON e.course_id = a.course_id AND e.student_id = ?
    LEFT JOIN (
        SELECT ta1.test_id, ta1.score, ta1.max_score, ta1.band_score, ta1.status, ta1.completed_at
        FROM test_attempts ta1
        JOIN (SELECT test_id, MAX(id) AS max_id FROM test_attempts WHERE student_id = ? GROUP BY test_id) ta2 ON ta1.id = ta2.max_id
    ) latest ON latest.test_id = a.test_id
    WHERE a.id = ? AND (a.student_id IS NULL OR a.student_id = ?)
", [$student['id'], $student['id'], $id, $student['id']])->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    http_response_code(404);
    echo json_encode(['error' => 'Assignment not found']);
    exit();
}

$isDone = $row['attempt_status'] === 'completed';
$hasBand = $row['attempt_band'] !== null;
$status = !$isDone ? 'to_do' : ($hasBand ? 'graded' : 'submitted');

echo json_encode([
    'id' => (string) $row['id'],
    'title' => $row['title'],
    'type' => $row['type'],
    'courseTitle' => $row['course_title'],
    'coach' => $row['instructor_name'],
    'instructions' => $row['description'],
    'dueAt' => $row['due_date'],
    'status' => $status,
    'band' => $hasBand ? (float) $row['attempt_band'] : null,
    'score' => $row['attempt_score'] !== null ? (int) $row['attempt_score'] : null,
    'maxScore' => $row['attempt_max'] !== null ? (int) $row['attempt_max'] : null,
    'submittedAt' => $row['attempt_date'] ? str_replace(' ', 'T', $row['attempt_date']) . 'Z' : null,
]);
