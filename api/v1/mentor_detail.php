<?php
// GET /api/v1/mentor_detail.php?id=1 -- mentor profile + booking (SLS-Academy-App-Spec.md 8.22,
// MBK-01..04): the real day/time picker, mirroring the web's mentor_book.php exactly via the
// shared includes/mentor_booking.php, rather than the app's one-tap-only pill.
require_once dirname(__DIR__, 2) . '/bootstrap.php';
require_once dirname(__DIR__) . '/mobile_auth.php';
require_once dirname(__DIR__, 2) . '/includes/mentor_booking.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$student = requireMobileAuth($db);
$mentorId = (int) ($_GET['id'] ?? 0);

$mentor = $mentorId ? executeQuery($db, "SELECT id, display_name, focus_area, bio FROM mentors WHERE id = ? AND is_active = 1 AND is_bookable = 1", [$mentorId])->fetch(PDO::FETCH_ASSOC) : null;
if (!$mentor) {
    http_response_code(404);
    echo json_encode(['error' => 'Mentor not found']);
    exit();
}

$openRequest = executeQuery($db, "
    SELECT id, preferred_at, note FROM mentor_requests
    WHERE mentor_id = ? AND student_id = ? AND status IN ('requested','confirmed')
    ORDER BY id DESC LIMIT 1
", [$mentorId, $student['id']])->fetch(PDO::FETCH_ASSOC);

$days = buildMentorBookingDays($db, $mentorId);

echo json_encode([
    'mentor' => [
        'id' => (string) $mentor['id'],
        'name' => $mentor['display_name'],
        'focusArea' => $mentor['focus_area'],
        'bio' => $mentor['bio'],
    ],
    // preferred_at is built entirely in PHP (buildMentorBookingDays()/validateMentorBookingSlot()
    // use PHP's own UTC clock throughout, matching this file's day/slot generation below) and
    // never touches MySQL's NOW() -- unlike columns academyUtcIso() exists for, it's already UTC,
    // so it's formatted directly rather than shifted again.
    'openRequest' => $openRequest ? [
        'id' => (string) $openRequest['id'],
        'preferredAt' => gmdate('Y-m-d\TH:i:s', strtotime($openRequest['preferred_at'])) . 'Z',
        'note' => $openRequest['note'],
    ] : null,
    'days' => array_map(fn ($d) => [
        'date' => $d['date'],
        'weekdayLabel' => $d['weekday_label'],
        'dayLabel' => $d['day_label'],
        'slots' => array_map(fn ($s) => ['value' => $s['value'], 'label' => $s['label'], 'taken' => $s['taken']], $d['slots']),
    ], $days),
]);
