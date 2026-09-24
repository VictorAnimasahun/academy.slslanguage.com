-- ============================================================
-- Migration 118 -- Overall (whole-session) speaking score + summary
--
-- sls-admin's Practice Sessions page could only score one recording (one
-- task) at a time manually, with no single overall score/summary for the
-- whole practice attempt. This table holds exactly one row per
-- (student_id, test_code) -- the same pairing speaking_recordings and
-- practice_sessions.php already group by -- for that overall score.
-- total_score is varchar, not a number: CELPIP reports "CLB 7", IELTS
-- reports "Band 6.5" -- same reason speaking_recordings.ai_feedback
-- stores banded text, not a numeric column.
-- Manually entered/synced from migration 119's per-task manual_score
-- values (see practice_session_detail.php's "Sync from individual scores"
-- button) -- no AI call involved; an earlier draft of this feature tried
-- combining every task into one AI prompt, which took 45-55s in testing,
-- past Apache's default 30s FastCGI idle-timeout, and was dropped in
-- favor of manual scoring off the transcript.
-- NOT idempotent (see 119's note on ADD COLUMN IF NOT EXISTS failing on
-- this actual MySQL 8.0.40) -- CREATE TABLE IF NOT EXISTS is fine though,
-- that syntax is genuinely supported. Run on LOCAL first, then LIVE.
-- ============================================================

CREATE TABLE IF NOT EXISTS speaking_session_scores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    test_code VARCHAR(64) NOT NULL,
    total_score VARCHAR(50) DEFAULT NULL,
    summary MEDIUMTEXT DEFAULT NULL,
    edited_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY speaking_session_scores_student_test (student_id, test_code),
    KEY speaking_session_scores_student_id (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify: SELECT * FROM speaking_session_scores LIMIT 5;
-- Rollback: DROP TABLE speaking_session_scores;
