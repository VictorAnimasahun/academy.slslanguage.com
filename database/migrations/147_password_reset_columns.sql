-- ============================================================
-- Migration 147 -- students.password_reset_token / password_reset_token_created_at
--
-- Backs Forgot password (SLS-Academy-App-Spec.md 8.16, web edu_hub_registration.php +
-- new forgot_password.php/reset_password.php, mobile forgot-password.tsx). Nothing built this
-- for either platform before tonight -- config/email_helper.php already had an unused
-- send_password_reset_email() function (built, wired to nothing), pointing at a
-- reset_password.php that didn't exist.
--
-- Separate columns from verification_token/token_created_at on purpose: a verified student
-- resetting their password must not disturb is_verified or the signup verification flow, and
-- the two tokens have different lifetimes (24h for verification, 1h for reset, matching
-- send_password_reset_email()'s own copy).
--
-- Idempotent (MySQL 5.7-safe): each column is added only if missing, so this is safe to re-run.
-- Run on LOCAL first, then LIVE.
-- ============================================================

SET NAMES utf8mb4;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'password_reset_token');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN password_reset_token VARCHAR(64) NULL DEFAULT NULL AFTER token_created_at',
    'SELECT ''password_reset_token column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'password_reset_token_created_at');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN password_reset_token_created_at TIMESTAMP NULL DEFAULT NULL AFTER password_reset_token',
    'SELECT ''password_reset_token_created_at column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
