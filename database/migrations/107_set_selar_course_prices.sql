-- ============================================================
-- Migration 107 -- real Selar prices on the courses (Naira) + struck-through original
--
-- Read from the three live Selar product pages on 2026-09-20 (SLS Language on Selar):
--   1-month (IELTS/CELPIP Crash Course 2026)      N90,000   (was N105,000)
--   2-month (IELTS/CELPIP Masterclass 2026)       N180,000  (was N195,000)
--   3-month (IELTS/CELPIP Masterclass 2026)       N240,000  (no discount)
-- Keyed by courses.selar_months (migration 105), so it applies to every
-- IELTS/CELPIP course sold as that product. Courses with selar_months are shown
-- in Naira (N, whole numbers); all others keep the existing $ format.
-- compare_price = the original price shown struck through (NULL = no discount).
-- REQUIRES migration 105. Idempotent (MySQL 5.7-safe). LOCAL first, then LIVE.
-- Re-run this file whenever Selar prices change (edit the numbers below first).
-- ============================================================

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'compare_price'
);
SET @sql = IF(@existing = 0,
    'ALTER TABLE courses ADD COLUMN compare_price DECIMAL(10,2) NULL DEFAULT NULL AFTER price',
    'SELECT ''compare_price column already exists -- skipping'' AS info');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

UPDATE courses SET price = 90000.00,  compare_price = 105000.00 WHERE selar_months = 1;
UPDATE courses SET price = 180000.00, compare_price = 195000.00 WHERE selar_months = 2;
UPDATE courses SET price = 240000.00, compare_price = NULL      WHERE selar_months = 3;

-- Verify: SELECT id, folder_name, selar_months, price, compare_price, is_free FROM courses WHERE selar_months IS NOT NULL;
--   (is_free should be 0 for these; if any shows 1 the catalogue displays FREE instead of the price)
-- Rollback: ALTER TABLE courses DROP COLUMN compare_price;  (prices stay; restore from backup if needed)
