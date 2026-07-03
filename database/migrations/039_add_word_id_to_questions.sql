-- Migration 039 — Add word_id (nullable FK) to questions table
-- Phase 1, Step 2 of the Vocabulary Banks feature.
-- Run on LOCAL first, then LIVE.
-- Safe to re-run — uses IF NOT EXISTS pattern via SET @existing check.
-- All existing questions are unaffected (word_id defaults to NULL).

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'questions'
      AND COLUMN_NAME  = 'word_id'
);

SET @sql = IF(
    @existing = 0,
    'ALTER TABLE questions ADD COLUMN word_id INT UNSIGNED NULL DEFAULT NULL AFTER test_id',
    'SELECT ''word_id column already exists — skipping'' AS info'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add index so lookups by word_id are fast
SET @idx_existing = (
    SELECT COUNT(*) FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'questions'
      AND INDEX_NAME   = 'idx_word_id'
);

SET @idx_sql = IF(
    @idx_existing = 0,
    'ALTER TABLE questions ADD INDEX idx_word_id (word_id)',
    'SELECT ''idx_word_id already exists — skipping'' AS info'
);

PREPARE idx_stmt FROM @idx_sql;
EXECUTE idx_stmt;
DEALLOCATE PREPARE idx_stmt;
