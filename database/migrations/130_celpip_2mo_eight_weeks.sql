-- ============================================================
-- Migration 130 -- CELPIP General 2-Month is 8 weeks / 16 classes (there is no such thing as a 9-week 2-month course)
--
-- Migration 121 had added a 9th week (Mock Test 2 + Final Performance Review) on top of the 8-week mirror of the
-- 3-Month course. Now (instructor, 2026-09-26): Week 8 holds BOTH mocks -- Class 15 = Mock Test 1 (unchanged),
-- Class 16 = Mock Test 2 -- and week 9 is gone. Classes 1-14 keep their places, so the shared class pages
-- (class_day.php, numbered to match the 3-Month course) still line up.
-- Removed from the SCHEDULE: "Mock 1 Review & Band Estimate" and "Final Performance Review" (their pages stay on disk
-- and can be re-added as pieces of the mock classes in Step 2).
--
-- Works whether or not migration 121 was ever run on the server:
--   121 was run  -> week 9 exists: Mock Test 2 moves into week 8, week 9 is deleted (steps 1-3).
--   121 not run  -> no week 9; week 8 still ends with "Mock 1 Review": that class becomes Mock Test 2 in place (step 4).
-- Either way the result is the same: 8 weeks, 16 classes, Class 15 = Mock Test 1, Class 16 = Mock Test 2.
-- Run AFTER 126 (it cleans lesson_parts rows of the removed classes). Repeatable: once the shape is right it changes nothing.
-- Written portable for older MySQL/MariaDB (user variables, no correlated derived tables). Tested on MySQL 5.7.
-- ============================================================

SET NAMES utf8mb4;

SET @c13 = (SELECT id FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1);
SET @m8  = (SELECT id FROM modules WHERE course_id = @c13 AND module_order = 8 LIMIT 1);
SET @m9  = (SELECT id FROM modules WHERE course_id = @c13 AND module_order = 9 LIMIT 1);

-- 1. Mock 1 Review leaves the schedule (week 8, class 2)
DELETE FROM lessons WHERE @m9 IS NOT NULL AND module_id = @m8 AND lesson_order = 2 AND title LIKE 'Mock 1 Review%';

-- 2. Mock Test 2 moves into week 8 as class 16
UPDATE lessons SET module_id = @m8, lesson_order = 2
 WHERE @m9 IS NOT NULL AND module_id = @m9 AND lesson_order = 1 AND title LIKE 'Mock Test 2%';

-- 3. whatever is left in week 9 (the Final Performance Review) and the week itself go
DELETE FROM lessons WHERE @m9 IS NOT NULL AND module_id = @m9;
DELETE FROM modules WHERE @m9 IS NOT NULL AND id = @m9;

-- 4. 121 was never run: week 8 class 2 is still "Mock 1 Review" -> make it Mock Test 2 (same page, tier and title 121 would have given it)
UPDATE lessons SET title = 'Mock Test 2 — Final Assessment', file_path = 'resources/mock_tests/celpip_full_mock_b.php', min_tier = 'intermediate'
 WHERE @m9 IS NULL AND module_id = @m8 AND lesson_order = 2 AND title LIKE 'Mock 1 Review%';

UPDATE modules SET module_title = 'Week 8 — Mock Test 1 & Mock Test 2' WHERE id = @m8;

-- Mock Test 2 needs the same plan as the rest of week 8 (121 had given it 'intermediate' while class 15 needs 'advanced')
SET @t8 = (SELECT min_tier FROM (SELECT min_tier FROM lessons WHERE module_id = @m8 AND lesson_order = 1) t);
UPDATE lessons SET min_tier = @t8 WHERE @t8 IS NOT NULL AND module_id = @m8 AND lesson_order = 2 AND title LIKE 'Mock Test 2%';
UPDATE courses SET total_lessons = 16 WHERE id = @c13;

-- pieces of the removed classes (needs migration 126), and the piece of the class that just became Mock Test 2
DELETE lp FROM lesson_parts lp LEFT JOIN lessons l ON l.id = lp.lesson_id WHERE l.id IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id
   SET lp.title = l.title, lp.kind = 'mock_test'
 WHERE l.module_id = @m8 AND l.lesson_order = 2 AND l.title LIKE 'Mock Test 2%' AND lp.title LIKE 'Mock 1 Review%';

-- Verify: 8 weeks, 16 classes, both mocks in week 8
SELECT m.module_order AS week, m.module_title, COUNT(l.id) AS classes FROM modules m LEFT JOIN lessons l ON l.module_id = m.id
 WHERE m.course_id = @c13 GROUP BY m.id ORDER BY m.module_order;
SELECT (SELECT COUNT(*) FROM modules WHERE course_id = @c13) AS weeks, (SELECT COUNT(*) FROM lessons WHERE course_id = @c13) AS classes;
