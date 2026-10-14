-- Adds a lightweight "tutor confirmed this is transcribed" flag to speaking_recordings, distinct
-- from the existing manual "Delete audio" button in sls-admin (speaking_recordings_api.php's
-- delete_audio action, which unlinks the file immediately). This flag lets a tutor flip a
-- checkbox while reviewing without committing to an instant delete; a scheduled cleanup job
-- (academy/cron/cleanup_speaking_recordings.php) later deletes the audio for any recording that's
-- either flagged here OR older than 7 days, reusing the exact same delete logic (unlink + null
-- audio_path + stamp audio_deleted_at) so the transcript/ai_feedback/manual_score are never
-- touched -- only the audio blob goes. Built 2026-10-14: the live cPanel disk quota (2GB) was
-- found mostly full of real shared test content, not runaway recordings, but with zero retention
-- policy on student-uploaded speaking audio, that will change as more students use the platform.

SET @col_exists = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'speaking_recordings' AND COLUMN_NAME = 'tutor_marked_transcribed_at'
);
SET @sql = IF(@col_exists = 0,
    'ALTER TABLE speaking_recordings ADD COLUMN tutor_marked_transcribed_at DATETIME NULL AFTER graded_at',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
