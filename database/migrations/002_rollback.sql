-- Rollback 002: Remove min_tier column from lessons
-- Run this ONLY if migration 002 needs to be undone

ALTER TABLE `lessons`
  DROP COLUMN IF EXISTS `min_tier`;

-- Verify: SHOW COLUMNS FROM lessons LIKE 'min_tier';  (should return empty)
