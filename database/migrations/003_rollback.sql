-- Rollback 003: Remove min_tier column from modules
-- Run this ONLY if migration 003 needs to be undone

ALTER TABLE `modules`
  DROP COLUMN IF EXISTS `min_tier`;

-- Verify: SHOW COLUMNS FROM modules LIKE 'min_tier';  (should return empty)
