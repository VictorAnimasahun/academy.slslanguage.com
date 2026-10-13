<?php
// Shared day/slot generation for Mentor booking (SLS-Academy-App-Spec.md 8.22, MBK-01..04).
// Extracted from mentor_book.php (web) so the new api/v1/mentor_detail.php (mobile) can't drift
// from what the web picker actually shows -- same lesson as includes/notifications_feed.php.

/**
 * Builds the next 5 days (within a 21-day lookahead) that have an availability window for this
 * mentor, each with its bookable time slots (open or taken).
 *
 * @return array<int, array{date: string, weekday_label: string, day_label: string, slots: array<int, array{value: string, label: string, taken: bool}>}>
 */
function buildMentorBookingDays(PDO $db, int $mentorId): array {
    $availability = executeQuery($db, "SELECT * FROM mentor_availability WHERE mentor_id = ? ORDER BY weekday", [$mentorId])->fetchAll(PDO::FETCH_ASSOC);
    $byWeekday = [];
    foreach ($availability as $a) {
        $byWeekday[(int) $a['weekday']][] = $a;
    }

    $takenSlots = array_column(executeQuery($db, "
        SELECT preferred_at FROM mentor_requests WHERE mentor_id = ? AND status IN ('requested','confirmed') AND preferred_at >= NOW()
    ", [$mentorId])->fetchAll(PDO::FETCH_ASSOC), 'preferred_at');
    $takenSlots = array_flip($takenSlots);

    $days = [];
    $cursor = new DateTime('tomorrow');
    for ($i = 0; count($days) < 5 && $i < 21; $i++, $cursor->modify('+1 day')) {
        $weekday = (int) $cursor->format('w');
        if (empty($byWeekday[$weekday])) {
            continue;
        }
        $slots = [];
        foreach ($byWeekday[$weekday] as $win) {
            $t = DateTime::createFromFormat('Y-m-d H:i:s', $cursor->format('Y-m-d') . ' ' . $win['start_time']);
            $end = DateTime::createFromFormat('Y-m-d H:i:s', $cursor->format('Y-m-d') . ' ' . $win['end_time']);
            while ($t < $end) {
                $key = $t->format('Y-m-d H:i:s');
                $slots[] = ['value' => $key, 'label' => $t->format('g:i A'), 'taken' => isset($takenSlots[$key])];
                $t->modify('+' . (int) $win['slot_minutes'] . ' minutes');
            }
        }
        $days[] = ['date' => $cursor->format('Y-m-d'), 'weekday_label' => $cursor->format('D'), 'day_label' => $cursor->format('j M'), 'slots' => $slots];
    }
    return $days;
}

/**
 * Re-validates a proposed slot server-side (never trust the posted value): the mentor's
 * availability window actually covers it, it isn't already taken, and this student doesn't
 * already have an open request with this mentor. Returns an error message, or null if OK.
 */
function validateMentorBookingSlot(PDO $db, int $mentorId, int $studentId, DateTime $parsed): ?string {
    $weekday = (int) $parsed->format('w');
    $time = $parsed->format('H:i:s');

    $alreadyOpen = executeQuery($db, "
        SELECT 1 FROM mentor_requests
        WHERE mentor_id = ? AND student_id = ? AND status IN ('requested','confirmed')
        LIMIT 1
    ", [$mentorId, $studentId])->fetch();
    if ($alreadyOpen) {
        return 'You already have an open request with this mentor.';
    }

    $inWindow = executeQuery($db, "
        SELECT 1 FROM mentor_availability
        WHERE mentor_id = ? AND weekday = ? AND ? >= start_time AND ? < end_time
        LIMIT 1
    ", [$mentorId, $weekday, $time, $time])->fetch();
    if (!$inWindow) {
        return "That time is outside this mentor's hours.";
    }

    $taken = executeQuery($db, "
        SELECT 1 FROM mentor_requests
        WHERE mentor_id = ? AND preferred_at = ? AND status IN ('requested','confirmed')
        LIMIT 1
    ", [$mentorId, $parsed->format('Y-m-d H:i:s')])->fetch();
    if ($taken) {
        return 'That time was just taken. Choose another.';
    }

    return null;
}
