-- Migration 002: Add min_tier column to lessons table
-- All 61 existing lessons get DEFAULT 'beginner' — nothing breaks
-- NOTE: Run once only. Column must not already exist.
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [x] Date: 2026-05-04

ALTER TABLE `lessons`
  ADD COLUMN `min_tier`
    ENUM('beginner','intermediate','advanced','fluent')
    NOT NULL DEFAULT 'beginner'
    COMMENT 'Minimum subscription tier required to access this lesson'
    AFTER `duration_minutes`;

-- Verify: SHOW COLUMNS FROM lessons LIKE 'min_tier';
-- Verify: SELECT COUNT(*) FROM lessons WHERE min_tier = 'beginner';  (should = 61)
