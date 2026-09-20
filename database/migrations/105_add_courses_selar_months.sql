-- ============================================================
-- Migration 105 -- courses.selar_months: which Selar product a course belongs to
--
-- Selar sells three combined IELTS/CELPIP products by duration (1, 2, 3 months).
-- Migration 101 stored the product LINK in courses.buy_url; parsing a duration
-- back out of a URL is brittle, so this stores it explicitly. Code that needs
-- "which paid duration unlocks this course" must read selar_months, never buy_url.
-- Same folder_name mapping as 101 (folder names, not ids: ids differ local/live).
-- NULL = not sold through Selar. Idempotent (MySQL 5.7-safe). LOCAL first, then LIVE.
-- ============================================================

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'selar_months'
);
SET @sql = IF(@existing = 0,
    'ALTER TABLE courses ADD COLUMN selar_months TINYINT UNSIGNED NULL DEFAULT NULL AFTER buy_url',
    'SELECT ''selar_months column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE courses SET selar_months = 1 WHERE folder_name IN ('CELPIP_Gen_1Mo', 'IELTS_Aca_1Mo', 'IELTS_Gen_1Mo');
UPDATE courses SET selar_months = 2 WHERE folder_name IN ('CELPIP_Gen_2Mo', 'IELTS_Aca_2Mo', 'IELTS_Gen_2Mo');
UPDATE courses SET selar_months = 3 WHERE folder_name IN ('CELPIP_Gen_3Mo', 'IELTS_Aca_3Mo', 'IELTS_Gen_Mst');

-- Verify: SELECT id, folder_name, buy_url, selar_months FROM courses WHERE buy_url IS NOT NULL;
--   (every row with a buy_url should have selar_months 1/2/3)
-- Rollback: ALTER TABLE courses DROP COLUMN selar_months;
