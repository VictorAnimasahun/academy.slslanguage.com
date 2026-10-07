-- ============================================================
-- Migration 143 -- students.last_seen_at: backs the "online now" dot on Students/classmates
--
-- SLS-Academy-App-Spec.md STU-03: online status real-time or at most 2 minutes stale. bootstrap.php
-- (every signed-in page load) will stamp this; "online" is simply last_seen_at within 2 minutes,
-- computed at read time -- nothing here decides that, it's just the timestamp.
--
-- Idempotent (MySQL 5.7-safe): guarded ADD COLUMN, same pattern as migration 105/141.
-- ============================================================

SET NAMES utf8mb4;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'last_seen_at');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN last_seen_at DATETIME NULL DEFAULT NULL AFTER test_date',
    'SELECT ''last_seen_at column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Verify: DESCRIBE students;
-- Rollback: ALTER TABLE students DROP COLUMN last_seen_at;
