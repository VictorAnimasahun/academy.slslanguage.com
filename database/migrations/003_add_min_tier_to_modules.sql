-- Migration 003: Add min_tier column to modules table
-- All 9 existing modules get DEFAULT 'beginner' — nothing breaks
-- NOTE: Run once only. Column must not already exist.
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [x] Date: 2026-05-04

ALTER TABLE `modules`
  ADD COLUMN `min_tier`
    ENUM('beginner','intermediate','advanced','fluent')
    NOT NULL DEFAULT 'beginner'
    COMMENT 'Minimum subscription tier required to access this module'
    AFTER `module_order`;

-- Verify: SHOW COLUMNS FROM modules LIKE 'min_tier';
-- Verify: SELECT COUNT(*) FROM modules WHERE min_tier = 'beginner';  (should = 9)
