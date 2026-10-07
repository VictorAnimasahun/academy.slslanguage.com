<?php
// GET /api/v1/classmates.php?query=  -- the mobile Students screen (SLS-Academy-App-Spec.md 8.5).
// Same cohort rule as the web's students.php: any other student sharing a course enrollment.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$query = trim((string) ($_GET['query'] ?? ''));

$rows = executeQuery($db, "
    SELECT DISTINCT s.id, s.firstname, s.lastname, s.target_band,
           (s.last_seen_at IS NOT NULL AND s.last_seen_at > NOW() - INTERVAL 2 MINUTE) AS online
    FROM students s
    JOIN enrollments e ON e.student_id = s.id
    WHERE e.course_id IN (SELECT course_id FROM enrollments WHERE student_id = ?)
      AND s.id != ?
      AND (? = '' OR CONCAT(s.firstname, ' ', s.lastname) LIKE CONCAT('%', ?, '%'))
    ORDER BY s.firstname, s.lastname
", [$student['id'], $student['id'], $query, $query])->fetchAll(PDO::FETCH_ASSOC);

$items = array_map(fn ($r) => [
    'id' => (string) $r['id'],
    'name' => trim($r['firstname'] . ' ' . $r['lastname']),
    'targetBand' => $r['target_band'] !== null ? (float) $r['target_band'] : null,
    'online' => (bool) $r['online'],
    'tintSeed' => (int) $r['id'],
], $rows);

echo json_encode(['items' => $items]);
