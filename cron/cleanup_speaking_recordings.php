<?php
// academy/cron/cleanup_speaking_recordings.php
//
// Deletes the audio FILE (not the row) for any speaking_recordings where the audio is either:
//   (a) flagged by a tutor as transcribed (tutor_marked_transcribed_at IS NOT NULL), via the
//       "Mark as transcribed" button on practice_session_detail.php / mock_session_detail.php, or
//   (b) older than 7 days, regardless of whether a tutor ever reviewed it.
// The transcript, ai_feedback, manual_score and manual_analysis columns are never touched -- only
// audio_path is nulled and audio_deleted_at is stamped, exactly what the existing manual
// "Delete audio" button already does (speaking_recordings_api.php's delete_audio action). This
// script is the automated, bulk version of that same action, not a new deletion rule.
//
// Built 2026-10-14 after the live cPanel disk quota (2GB) filled up; academy/media/speaking_recordings/
// has no retention policy today and nothing else in the codebase ever deletes these files.
//
// CLI-only by design -- this performs real deletions, so it must never be reachable over HTTP.
// Run manually to test: php cleanup_speaking_recordings.php [--dry-run]
// Then schedule in cPanel -> Cron Jobs (the founder sets this up; not something run from here):
//   Command: /usr/local/bin/php /home/<cpanel_user>/public_html/academy/cron/cleanup_speaking_recordings.php
//   Suggested schedule: once daily, e.g. "0 3 * * *" (03:00 server time)
// Adjust the path above to wherever academy/ actually lives under the cPanel account.

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script is CLI-only.\n");
}

require_once dirname(__DIR__) . '/bootstrap.php';

$dryRun = in_array('--dry-run', $argv, true);

$stmt = $db->query("
    SELECT id, student_id, audio_path, created_at, tutor_marked_transcribed_at
    FROM speaking_recordings
    WHERE audio_path IS NOT NULL
      AND (tutor_marked_transcribed_at IS NOT NULL OR created_at < NOW() - INTERVAL 7 DAY)
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$deletedCount = 0;
$bytesFreed = 0;
$missingFileCount = 0;

foreach ($rows as $row) {
    $fullPath = ACADEMY_ROOT . '/' . $row['audio_path'];
    $reason = $row['tutor_marked_transcribed_at'] ? 'marked transcribed' : 'older than 7 days';

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
        is_file($fullPath) && !$dryRun ? '' : (is_file($fullPath) ? '' : ' (file already missing on disk)')
    );
}

echo sprintf(
    "\n%s %d recording(s), freed %s%s.\n",
    $dryRun ? 'Would delete' : 'Deleted',
    $deletedCount,
    number_format($bytesFreed / 1024 / 1024, 1) . ' MB',
    $missingFileCount > 0 ? " ($missingFileCount file(s) were already missing on disk, only the DB row was updated)" : ''
);
