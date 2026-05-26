-- ============================================================
-- Migration 013 — Add missing columns and tables
-- IDEMPOTENT: uses IF NOT EXISTS / ADD COLUMN IF NOT EXISTS
-- Run on LOCAL first, then LIVE.
-- ============================================================

-- 1. Add `mode` column to test_attempts (save_attempt.php inserts this)
ALTER TABLE test_attempts
    ADD COLUMN IF NOT EXISTS `mode` ENUM('practice','mock') NOT NULL DEFAULT 'practice' AFTER attempt_number;

-- 2. Add `time_spent` column to test_attempts (analytics + dashboard SELECT this)
ALTER TABLE test_attempts
    ADD COLUMN IF NOT EXISTS `time_spent` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `mode`;

-- 3. Create user_certificates table (learning_dashboard.php queries this)
CREATE TABLE IF NOT EXISTS user_certificates (
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id       INT UNSIGNED NOT NULL,
    test_id          INT UNSIGNED NULL,
    certificate_type VARCHAR(100)  NOT NULL DEFAULT 'course_completion',
    issued_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student (student_id)
);
