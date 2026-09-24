-- ============================================================
-- Migration 120 -- Release status for speaking_session_scores
--
-- Listening/Reading/Writing practice tests (and every Full Mock skill)
-- already deliver a proper, official result to the student -- Speaking
-- practice never did. Students only ever saw an instant, unsaved AI
-- reaction right after submitting (celpip_speaking_00N.php's
-- analyze_speaking_batch call) -- never an instructor-confirmed score.
-- Mirrors mock_sessions' own status/released_at convention exactly
-- (see sls-admin/mock_session_detail.php's release_speaking action) so
-- a student sees nothing until the instructor explicitly releases it,
-- and can be recalled the same way.
-- Run on LOCAL first, then LIVE.
-- ============================================================

ALTER TABLE speaking_session_scores
    ADD COLUMN status ENUM('pending','results_released') NOT NULL DEFAULT 'pending' AFTER summary,
    ADD COLUMN released_at DATETIME DEFAULT NULL AFTER status;

-- Verify: DESCRIBE speaking_session_scores;
-- Rollback: ALTER TABLE speaking_session_scores DROP COLUMN status, DROP COLUMN released_at;
