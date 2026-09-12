-- Backs the redesigned sls-admin Students page: staff notes on a student's
-- profile, and the ability to flag a whole test attempt or a single question
-- within it for review.

CREATE TABLE IF NOT EXISTS staff_notes (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id   INT UNSIGNED NOT NULL,
    employee_id  INT UNSIGNED NULL,
    author_name  VARCHAR(255) NOT NULL,
    note_text    TEXT NOT NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE test_attempts
    ADD COLUMN flagged TINYINT(1) NOT NULL DEFAULT 0 AFTER status;

ALTER TABLE attempt_answers
    ADD COLUMN flagged TINYINT(1) NOT NULL DEFAULT 0 AFTER score_awarded;
