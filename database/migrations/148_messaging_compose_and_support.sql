-- ============================================================
-- Migration 148 -- threads.subject/is_urgent, mentors.is_bookable, seed "SLS Support"
--
-- Backs CMP-01..06 / 8.37 "New message": a student can start a brand-new conversation from the
-- app (not just reply inside one a coach already started), choosing a coach or SLS Support,
-- with a subject and an optional urgent flag. CMP-04 (the matching web inbox for coaches/admins
-- to read and reply) is built in sls-admin, reusing these same tables.
--
-- "SLS Support" is seeded as a real mentors row with staff_account_id = NULL (already nullable,
-- migration 142's own comment: "NULL for a mentor with no app login yet" -- this is exactly that
-- case) and is_bookable = 0, so it's a valid message recipient without ever showing up as a
-- bookable coach on the Mentors tab.
--
-- Idempotent (MySQL 5.7-safe): guarded ADD COLUMN, NOT EXISTS-guarded seed INSERT.
-- Run on LOCAL first, then LIVE.
-- ============================================================

SET NAMES utf8mb4;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'threads' AND COLUMN_NAME = 'subject');
SET @sql = IF(@existing = 0,
    'ALTER TABLE threads ADD COLUMN subject VARCHAR(255) NULL AFTER title',
    'SELECT ''threads.subject already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'threads' AND COLUMN_NAME = 'is_urgent');
SET @sql = IF(@existing = 0,
    'ALTER TABLE threads ADD COLUMN is_urgent TINYINT(1) NOT NULL DEFAULT 0 AFTER subject',
    'SELECT ''threads.is_urgent already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'mentors' AND COLUMN_NAME = 'is_bookable');
SET @sql = IF(@existing = 0,
    'ALTER TABLE mentors ADD COLUMN is_bookable TINYINT(1) NOT NULL DEFAULT 1 AFTER is_active',
    'SELECT ''mentors.is_bookable already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

INSERT INTO mentors (staff_account_id, display_name, focus_area, bio, tint, is_active, is_bookable)
SELECT NULL, 'SLS Support', 'Account, payments and technical help', NULL, 'blue', 1, 0
WHERE NOT EXISTS (SELECT 1 FROM mentors WHERE display_name = 'SLS Support');

-- Verify: DESCRIBE threads; DESCRIBE mentors; SELECT * FROM mentors WHERE display_name = 'SLS Support';
-- Rollback:
--   DELETE FROM mentors WHERE display_name = 'SLS Support' AND staff_account_id IS NULL;
--   ALTER TABLE mentors DROP COLUMN is_bookable;
--   ALTER TABLE threads DROP COLUMN is_urgent;
--   ALTER TABLE threads DROP COLUMN subject;
