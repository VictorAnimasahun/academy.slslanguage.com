-- ============================================================
-- Migration 125 -- Audit log for tutor score corrections
--
-- sls-admin can now correct a student's Listening/Reading score: one
-- question marked right/wrong, or the total (raw score / band) overridden
-- (mock_session_detail.php and student_view.php, via attempt_correct_api.php).
-- Every correction is recorded here -- who, when, old -> new -- so a
-- changed result is never unexplained. No foreign keys on purpose (mixed
-- signed/unsigned id types and collations across this schema have broken
-- other migrations on live); it is a log, not a source of truth.
-- Run on LOCAL first, then LIVE. CREATE TABLE IF NOT EXISTS is idempotent.
-- ============================================================

CREATE TABLE IF NOT EXISTS score_corrections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    attempt_id BIGINT UNSIGNED NOT NULL,
    answer_id BIGINT UNSIGNED DEFAULT NULL,          -- attempt_answers.id when a single question was corrected
    kind ENUM('question','total') NOT NULL,
    old_score DECIMAL(6,2) DEFAULT NULL,             -- raw score of the attempt (question: the question's own mark)
    new_score DECIMAL(6,2) DEFAULT NULL,
    old_attempt_score DECIMAL(6,2) DEFAULT NULL,     -- attempt raw total before/after (for question corrections)
    new_attempt_score DECIMAL(6,2) DEFAULT NULL,
    old_band DECIMAL(3,1) DEFAULT NULL,
    new_band DECIMAL(3,1) DEFAULT NULL,
    old_overall DECIMAL(3,1) DEFAULT NULL,           -- mock overall, when the attempt belongs to a mock session
    new_overall DECIMAL(3,1) DEFAULT NULL,
    mock_session_id INT UNSIGNED DEFAULT NULL,
    note VARCHAR(500) DEFAULT NULL,
    corrected_by INT UNSIGNED DEFAULT NULL,          -- sls-admin employee id
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY score_corrections_attempt (attempt_id),
    KEY score_corrections_session (mock_session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
