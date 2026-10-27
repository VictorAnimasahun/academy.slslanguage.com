-- ============================================================
-- Migration 175 -- Inbox: per-student item state, and the course a message is about
--
-- Founder, 2026-10-27: one Inbox, the same UI as Canvas's (course filter, mailboxes, starring, archive, delete, the
-- course under the sender). Notifications fold into it.
--   inbox_item_state       one row per student per inbox item they've starred, archived, deleted or (for
--                          assignment-due and event reminders, which have no read table of their own) read.
--                          item_key: a<broadcast_messages.id>, t<threads.id>, d<assignments.id>, e<events.id>.
--                          No foreign keys, so it works whatever engine the live tables use.
--   broadcast_messages.course_id   the course a message is about (NULL = not about one course). The planner's
--                          week-ahead brief sets it; course-targeted messages fall back to their target course.
-- Re-runnable. MySQL 5.7-safe (no ADD COLUMN IF NOT EXISTS). The SELECTs at the end show both.
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS inbox_item_state (
  student_id int unsigned NOT NULL,
  item_key varchar(24) NOT NULL,
  starred tinyint(1) NOT NULL DEFAULT 0,
  archived tinyint(1) NOT NULL DEFAULT 0,
  deleted tinyint(1) NOT NULL DEFAULT 0,
  read_at datetime NULL,
  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (student_id, item_key)
) DEFAULT CHARSET=utf8mb4;

SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'broadcast_messages' AND COLUMN_NAME = 'course_id');
SET @s := IF(@c = 0, 'ALTER TABLE broadcast_messages ADD COLUMN course_id int unsigned NULL, ADD KEY idx_broadcast_course (course_id)', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

SHOW COLUMNS FROM inbox_item_state;
SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE FROM information_schema.COLUMNS
 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'broadcast_messages' AND COLUMN_NAME = 'course_id';
