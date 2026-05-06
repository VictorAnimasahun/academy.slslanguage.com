-- Rollback 001: Drop subscriptions table
-- Run this ONLY if migration 001 needs to be undone
-- WARNING: Destroys all subscription data

DROP TABLE IF EXISTS `subscriptions`;

-- Verify: SHOW TABLES LIKE 'subscriptions';  (should return empty)
