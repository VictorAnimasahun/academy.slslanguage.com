<?php
// POST /orientation/save.php  (JSON) -- the end of the orientation session (orientation/index.php).
// Founder, 2026-10-21: the orientation ends with a quiz, then a form and commitment ("I promise to study so many
// hours. I aim for this grade. My classes are so many hours and days"), linked to the course's tutor. Saves:
//   student_orientation   the quiz result (needed: QUIZ_PASS of the questions right)
//   student_class_plans   one plan per course the student is enrolled in -- the tutor's planner reads these
// Body: {"quiz": {"score": 5, "total": 6}, "plans": [{"course_id": 12, "class_format": "group", "class_days": ["mon","thu"],
//        "class_time": "18:00", "timezone": "America/Toronto", "class_hours": 2, "start_date": "2026-10-26",
//        "test_date": "", "target_score": "CLB 9", "study_hours_week": 6, "signed_name": "Ada Student"}]}
require_once dirname(__DIR__) . '/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

const ORIENTATION_QUIZ_PASS = 5;
const ORIENTATION_QUIZ_TOTAL = 6;
const ORIENTATION_DAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

$fail = function (string $msg, int $code = 422) {
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit();
};
if (empty($_SESSION['user_id'])) $fail('Please log in again.', 401);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') $fail('Method not allowed', 405);

$studentId = (int) $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

$score = (int) ($input['quiz']['score'] ?? -1);
if ($score < ORIENTATION_QUIZ_PASS || $score > ORIENTATION_QUIZ_TOTAL) $fail('Please pass the quiz first.');

$st = $db->prepare("SELECT e.course_id, c.title FROM enrollments e JOIN courses c ON c.id = e.course_id WHERE e.student_id = ?");
$st->execute([$studentId]);
$enrolled = array_column($st->fetchAll(PDO::FETCH_ASSOC), 'title', 'course_id');

$plans = [];
foreach ((array) ($input['plans'] ?? []) as $p) {
    $courseId = (int) ($p['course_id'] ?? 0);
    $title = $enrolled[$courseId] ?? null;
    if ($title === null) $fail('You are not enrolled in one of these courses.');
    $label = fn ($m) => $title . ': ' . $m;

    $format = $p['class_format'] ?? '';
    if (!in_array($format, ['one_to_one', 'group'], true)) $fail($label('choose one-to-one or group classes.'));
    $days = array_values(array_intersect(ORIENTATION_DAYS, (array) ($p['class_days'] ?? [])));
    if (!$days) $fail($label('choose your class days.'));
    $time = (string) ($p['class_time'] ?? '');
    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time)) $fail($label('enter your class time.'));
    $tz = (string) ($p['timezone'] ?? '');
    if (!in_array($tz, DateTimeZone::listIdentifiers(), true)) $fail($label('choose your time zone.'));
    $hours = (float) ($p['class_hours'] ?? 0);
    if ($hours < 0.5 || $hours > 6) $fail($label('class length must be between 0.5 and 6 hours.'));
    $start = DateTime::createFromFormat('!Y-m-d', (string) ($p['start_date'] ?? ''));
    if (!$start) $fail($label('choose your start date.'));
    $testDate = ($p['test_date'] ?? '') === '' ? null : DateTime::createFromFormat('!Y-m-d', (string) $p['test_date']);
    if ($testDate === false) $fail($label('the test date is not a valid date.'));
    $target = trim((string) ($p['target_score'] ?? ''));
    if ($target === '' || mb_strlen($target) > 30) $fail($label('enter the score you are aiming for.'));
    $study = (float) ($p['study_hours_week'] ?? -1);
    if ($study < 0 || $study > 80) $fail($label('enter the hours you will study each week (0-80).'));
    $signed = trim((string) ($p['signed_name'] ?? ''));
    if (mb_strlen($signed) < 3 || mb_strlen($signed) > 150) $fail($label('type your full name to sign your commitment.'));

    $plans[] = [$studentId, $courseId, $format, implode(',', $days), $time . ':00', $tz, $hours, $start->format('Y-m-d'),
                $study, $target, $testDate ? $testDate->format('Y-m-d') : null, $signed];
}
if ($enrolled && count($plans) !== count($enrolled)) $fail('Please fill in a class plan for each of your courses.');

$db->beginTransaction();
$db->prepare("INSERT INTO student_orientation (student_id, quiz_score, quiz_total) VALUES (?, ?, ?)
              ON DUPLICATE KEY UPDATE quiz_score = VALUES(quiz_score), quiz_total = VALUES(quiz_total), completed_at = NOW()")
   ->execute([$studentId, $score, ORIENTATION_QUIZ_TOTAL]);
$up = $db->prepare("INSERT INTO student_class_plans
        (student_id, course_id, class_format, class_days, class_time, timezone, class_hours, start_date, study_hours_week, target_score, test_date, signed_name)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE class_format = VALUES(class_format), class_days = VALUES(class_days), class_time = VALUES(class_time),
        timezone = VALUES(timezone), class_hours = VALUES(class_hours), start_date = VALUES(start_date),
        study_hours_week = VALUES(study_hours_week), target_score = VALUES(target_score), test_date = VALUES(test_date),
        signed_name = VALUES(signed_name)");
foreach ($plans as $row) $up->execute($row);
$db->commit();

echo json_encode(['success' => true]);
