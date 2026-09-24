-- ============================================================
-- Migration 119 -- Manual per-task score + analysis for speaking_recordings
--
-- Superseded the AI combined-analysis idea from migration 118's original
-- brief (that AI call took 45-55s in testing, well past Apache's default
-- 30s FastCGI idle-timeout, and the instructor decided manual scoring off
-- the transcript is preferable anyway). This adds a plain manual score +
-- analysis per task, separate from the existing AI ai_feedback column
-- (which stays, for the existing per-recording "Analyze" button -- this
-- migration doesn't touch that).
-- NOT idempotent -- tested "ADD COLUMN IF NOT EXISTS" against this actual
-- local MySQL 8.0.40 and it's a real syntax error here (ERROR 1064), despite
-- an earlier migration (013) assuming that syntax works on "MySQL 8" in
-- general. Don't trust that assumption elsewhere either -- verify against
-- the real server before relying on it. Run this once per environment,
-- tracked via migration_log.md as usual.
-- Run on LOCAL first, then LIVE.
-- ============================================================

ALTER TABLE speaking_recordings
    ADD COLUMN manual_score VARCHAR(20) DEFAULT NULL AFTER ai_feedback,
    ADD COLUMN manual_analysis MEDIUMTEXT DEFAULT NULL AFTER manual_score;

-- Verify: DESCRIBE speaking_recordings;
-- Rollback: ALTER TABLE speaking_recordings DROP COLUMN manual_score, DROP COLUMN manual_analysis;
