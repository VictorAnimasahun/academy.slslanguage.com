-- ============================================================
-- Migration 146 -- fix migration 142's mentor seed: don't hardcode an email
--
-- 142 seeded the one real mentor by matching email 'v.animasahun@slslanguage.com'. That account is
-- LOCAL ONLY (see memory: "admin skip controls + admin account ... local only, not committed to
-- git") -- it does not exist on live, so 142's seed silently inserted zero mentors there (an
-- INSERT...SELECT with no matching row is not an error, just a no-op). Confirmed live: mentors.php
-- showed "No mentors available right now" after 142 ran clean.
--
-- This picks whichever staff_accounts row actually exists in this environment instead -- preferring
-- 'admin' over 'staff', oldest first -- so it seeds the real admin on live and does nothing extra
-- locally (which already has a mentor from 142's own seed matching there).
--
-- Idempotent: only acts if `mentors` is completely empty. Safe to re-run.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO mentors (staff_account_id, display_name, focus_area, bio, tint, is_active)
SELECT sa.id, TRIM(CONCAT(s.firstname, ' ', s.lastname)), 'IELTS and CELPIP coaching', 'Bio to be updated.', 'purple', 1
FROM staff_accounts sa
JOIN students s ON s.id = sa.student_id
WHERE NOT EXISTS (SELECT 1 FROM mentors)
ORDER BY (sa.role = 'admin') DESC, sa.id ASC
LIMIT 1;

-- Weekly availability template for that mentor, same Mon-Fri 9am-5pm hourly pattern as 142 --
-- only if it doesn't already have one (a mentor seeded by 142 itself already does).
INSERT INTO mentor_availability (mentor_id, weekday, start_time, end_time, slot_minutes)
SELECT m.id, wd.weekday, '09:00:00', '17:00:00', 60
FROM mentors m
JOIN (SELECT 1 AS weekday UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) wd
WHERE NOT EXISTS (SELECT 1 FROM mentor_availability a WHERE a.mentor_id = m.id);

-- Verify: SELECT * FROM mentors; SELECT * FROM mentor_availability;
-- Rollback: this only adds rows when `mentors` was empty; if it fired, DELETE FROM mentor_availability WHERE mentor_id = <seeded id>; DELETE FROM mentors WHERE id = <seeded id>;
