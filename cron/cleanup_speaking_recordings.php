<?php
// academy/cron/cleanup_speaking_recordings.php
//
// Deletes the audio FILE (not the row) for any speaking_recordings where the audio is either:
//   (a) flagged by a tutor as transcribed (tutor_marked_transcribed_at IS NOT NULL), via the
//       "Mark as transcribed" button on practice_session_detail.php / mock_session_detail.php, or
//   (b) the student's program has ended -- computed as the LATEST
//       enrolled_at + length_months + access_extra_months across every course they've ever
//       enrolled in (access_extra_months is the real grace period courses.php already defines for
//       "how long after the nominal length a student keeps access" -- the same concept this
//       script needs, already modelled in the schema), or
//   (c) for a student with no enrollment that defines a length at all (self-paced/intro courses
//       only, or never enrolled in anything) -- there's no "program" to wait out, so this falls
//       back to a flat 30-day safety net instead of keeping the audio forever by accident.
// The transcript, ai_feedback, manual_score and manual_analysis columns are never touched -- only
// audio_path is nulled and audio_deleted_at is stamped, exactly what the existing manual
// "Delete audio" button already does (speaking_recordings_api.php's delete_audio action). This
// script is the automated, bulk version of that same action, not a new deletion rule.
//
// Built 2026-10-14 after the live cPanel disk quota (2GB) filled up; academy/media/speaking_recordings/
// had no retention policy at all and nothing else in the codebase ever deletes these files.
// Retention rule changed 2026-10-15 (founder's call): originally a flat 7-day window, now tied to
// the student's actual program length instead, since a fixed short window could delete a
// recording a tutor still needed to review well before a 1-3 month course even finished.
//
// CLI-only by design -- this performs real deletions, so it must never be reachable over HTTP.
// Run manually to test: php cleanup_speaking_recordings.php [--dry-run]
// Then schedule in cPanel -> Cron Jobs (the founder sets this up; not something run from here):
//   Command: /usr/local/bin/php /home/<cpanel_user>/academy.slslanguage.com/cron/cleanup_speaking_recordings.php
//   Suggested schedule: once daily, e.g. "0 3 * * *" (03:00 server time)
// Adjust the path above to wherever academy actually lives under the cPanel account.

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script is CLI-only.\n");
}

require_once dirname(__DIR__) . '/bootstrap.php';

$dryRun = in_array('--dry-run', $argv, true);

// One query, not N+1: pe.program_end_date is the latest "enrolled_at + length_months +
// access_extra_months" across every course the student has ever enrolled in that actually
// defines a length (self-paced/intro courses leave length_months NULL and are excluded from this
// calculation on purpose -- they don't define a "program" to wait out).
$stmt = $db->query("
    SELECT sr.id, sr.student_id, sr.audio_path, sr.created_at, sr.tutor_marked_transcribed_at,
           pe.program_end_date
    FROM speaking_recordings sr
    LEFT JOIN (
        SELECT e.student_id,
               MAX(DATE_ADD(e.enrolled_at, INTERVAL (c.length_months + COALESCE(c.access_extra_months, 0)) MONTH)) AS program_end_date
        FROM enrollments e
        JOIN courses c ON c.id = e.course_id
        WHERE c.length_months IS NOT NULL
        GROUP BY e.student_id
    ) pe ON pe.student_id = sr.student_id
    WHERE sr.audio_path IS NOT NULL
      AND (
        sr.tutor_marked_transcribed_at IS NOT NULL
        OR (pe.program_end_date IS NOT NULL AND NOW() > pe.program_end_date)
        OR (pe.program_end_date IS NULL AND sr.created_at < NOW() - INTERVAL 30 DAY)
      )
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$deletedCount = 0;
$bytesFreed = 0;
$missingFileCount = 0;

foreach ($rows as $row) {
    $fullPath = ACADEMY_ROOT . '/' . $row['audio_path'];

    if ($row['tutor_marked_transcribed_at']) {
        $reason = 'marked transcribed';
    } elseif ($row['program_end_date']) {
        $reason = "program ended ({$row['program_end_date']})";
    } else {
        $reason = 'no program on record, older than 30 days';
    }

    if (is_file($fullPath)) {
        $bytesFreed += filesize($fullPath);
        if (!$dryRun) {
            unlink($fullPath);
        }
    } else {
        $missingFileCount++;
    }

    if (!$dryRun) {
        $db->prepare("UPDATE speaking_recordings SET audio_path = NULL, audio_deleted_at = NOW() WHERE id = ?")
           ->execute([$row['id']]);
    }

    $deletedCount++;
    echo sprintf(
        "[%s] recording #%d (student %d) — %s%s\n",
        $dryRun ? 'DRY RUN' : 'DELETED',
        $row['id'],
        $row['student_id'],
        $reason,
        is_file($fullPath) ? '' : ' (file already missing on disk)'
    );
}

echo sprintf(
    "\n%s %d recording(s), freed %s%s.\n",
    $dryRun ? 'Would delete' : 'Deleted',
    $deletedCount,
    number_format($bytesFreed / 1024 / 1024, 1) . ' MB',
    $missingFileCount > 0 ? " ($missingFileCount file(s) were already missing on disk, only the DB row was updated)" : ''
);
