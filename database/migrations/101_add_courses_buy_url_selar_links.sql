-- ============================================================
-- Migration 101 — Add courses.buy_url; set Selar purchase links
--
-- The IELTS/CELPIP 1-, 2- and 3-month products are sold on Selar as ONE
-- combined product per duration (not per exam), so every IELTS and CELPIP
-- course of the same duration shares the same link:
--
--   1-month -> IELTS/CELPIP CRASH COURSE 2026 (1-Month Standard)
--   2-month -> IELTS/CELPIP Masterclass 2026 (2-Month Standard)
--   3-month -> IELTS/CELPIP Masterclass 2026 (3 Months Standard)
--
-- Matched on folder_name (not id) because ids differ between local and live.
-- Not touched: PTE_*, BEL, *_intro, IELTS_Aca_Mst (no confirmed duration),
-- IELTS_Aca_Crash.
--
-- Idempotent (MySQL 5.7-safe: no ADD COLUMN IF NOT EXISTS). Run on LOCAL
-- first, then LIVE.
-- ============================================================

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'courses'
      AND COLUMN_NAME  = 'buy_url'
);

SET @sql = IF(
    @existing = 0,
    'ALTER TABLE courses ADD COLUMN buy_url VARCHAR(255) NULL DEFAULT NULL AFTER price',
    'SELECT ''buy_url column already exists — skipping'' AS info'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE courses SET buy_url = 'https://share.google/gUZlnYh5rXytRYrtc'
WHERE folder_name IN ('CELPIP_Gen_1Mo', 'IELTS_Aca_1Mo', 'IELTS_Gen_1Mo');

UPDATE courses SET buy_url = 'https://share.google/zxZWGzHd6aFUkAUbu'
WHERE folder_name IN ('CELPIP_Gen_2Mo', 'IELTS_Aca_2Mo', 'IELTS_Gen_2Mo');

UPDATE courses SET buy_url = 'https://share.google/xCafq2JBGJw7duixc'
WHERE folder_name IN ('CELPIP_Gen_3Mo', 'IELTS_Aca_3Mo', 'IELTS_Gen_Mst');

-- Verify:
-- SELECT id, title, folder_name, buy_url FROM courses ORDER BY id;
