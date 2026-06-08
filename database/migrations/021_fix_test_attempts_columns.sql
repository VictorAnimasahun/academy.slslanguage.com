-- ============================================================
-- Migration 021 — Supersedes 013
--
-- Run each statement one at a time in phpMyAdmin.
-- If you get "Duplicate column name" on any ALTER, that column
-- already exists — just skip it and run the next one.
-- ============================================================

-- 1. Add mode column
ALTER TABLE test_attempts
    ADD COLUMN `mode` ENUM('practice','mock') NOT NULL DEFAULT 'practice' AFTER attempt_number;

-- 2. Add time_spent column
ALTER TABLE test_attempts
    ADD COLUMN `time_spent` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `mode`;

-- 3. Create user_certificates table
CREATE TABLE IF NOT EXISTS user_certificates (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id       INT UNSIGNED NOT NULL,
    test_id          INT UNSIGNED NULL,
    certificate_type VARCHAR(100) NOT NULL DEFAULT 'course_completion',
    issued_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student (student_id)
);
