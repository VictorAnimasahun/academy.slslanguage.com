-- ============================================================
-- Migration 141 -- students.exam_type / target_band / baseline_band / test_date
--
-- Backs the mobile app's "Set your test date" / "Your goal" editor (SLS-Academy-App-Spec.md
-- ME-01, HOME-02/03, OQ-6/OQ-7). Nothing reads or writes these yet except the new
-- api/mobile_update_goal.php and mobile_me.php's response -- this is additive and touches no
-- existing feature.
--
-- target_band / baseline_band store the IELTS-style 0.0-9.0 half-band scale. CELPIP's CLB 1-12
-- scale is a different shape (OQ-7 in the spec is still open); until that's decided, a CELPIP
-- student's target/baseline are stored on the same column, unvalidated against a scale, and the
-- app is responsible for showing them sensibly. Revisit this column's shape when OQ-7 is settled.
--
-- Idempotent (MySQL 5.7-safe): each column is added only if missing, so this is safe to re-run.
-- Run on LOCAL first, then LIVE.
-- ============================================================

SET NAMES utf8mb4;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'exam_type');
SET @sql = IF(@existing = 0,
    "ALTER TABLE students ADD COLUMN exam_type ENUM('ielts_academic','ielts_general','celpip') NULL DEFAULT NULL AFTER is_tester",
    'SELECT ''exam_type column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'target_band');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN target_band DECIMAL(2,1) NULL DEFAULT NULL AFTER exam_type',
    'SELECT ''target_band column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'baseline_band');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN baseline_band DECIMAL(2,1) NULL DEFAULT NULL AFTER target_band',
    'SELECT ''baseline_band column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @existing = (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'test_date');
SET @sql = IF(@existing = 0,
    'ALTER TABLE students ADD COLUMN test_date DATE NULL DEFAULT NULL AFTER baseline_band',
    'SELECT ''test_date column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Verify: DESCRIBE students;
-- Rollback: ALTER TABLE students DROP COLUMN exam_type, DROP COLUMN target_band, DROP COLUMN baseline_band, DROP COLUMN test_date;
