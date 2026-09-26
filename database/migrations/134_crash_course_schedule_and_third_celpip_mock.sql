-- ============================================================
-- Migration 134 -- Step 2 schedule fixes: IELTS Academic Crash Course gets its classes; CELPIP 3-Month gets its third mock
--
-- Founder rules (2026-09-26): a 1-month course has 1 mock, a 2-month course 2, a 3-month course 3; a class is about 2 hours and holds
-- several pieces (a test is always its own piece); the Crash Course takes what it can from the first month of the Masterclass.
--
-- 1. IELTS Academic Crash Course (IELTS_Aca_1Mo) = the first month of IELTS Academic Masterclass -- 2 Months (IELTS_Aca_2Mo):
--    weeks 1-3 as they are, week 4 = Class 7 (Reading Test 2 + Process Description) and Class 8 = Mock Test 1 (Coming Soon).
--    Its pieces copy the Masterclass's (same kinds, same test pages, same shell lesson pages, the shells copied under the Crash Course).
--    Reading Test 2 is listed but left Coming Soon here until the founder has reviewed that test.
-- 2. CELPIP General Masterclass -- 3 Months (CELPIP_Gen_3Mo): the third mock is added to Class 24 as a Coming Soon piece, and
--    "Test-Day Coaching" (a lesson, though its title says Test) is corrected from practice test to lesson.
--
-- Found by folder and position, never by id. Repeatable. Needs 126 and 128 (lesson_parts, lesson_parts.status). Run after 131.
-- Written for older MySQL/MariaDB (no correlated derived tables).
-- ============================================================

SET NAMES utf8mb4;

-- ---- 1. IELTS Academic Crash Course --------------------------------------------------------------------
UPDATE modules m1
  JOIN courses c1 ON c1.id = m1.course_id AND c1.folder_name = 'IELTS_Aca_1Mo'
  JOIN courses c2 ON c2.folder_name = 'IELTS_Aca_2Mo'
  JOIN modules m2 ON m2.course_id = c2.id AND m2.module_order = m1.module_order
   SET m1.module_title = m2.module_title, m1.min_tier = m2.min_tier
 WHERE m1.module_order IN (1, 2, 3);
UPDATE modules m1 JOIN courses c1 ON c1.id = m1.course_id AND c1.folder_name = 'IELTS_Aca_1Mo'
   SET m1.module_title = 'Week 4 — Reading Test 2 & Mock Test 1', m1.min_tier = 'intermediate'
 WHERE m1.module_order = 4;

-- classes 1-7 copy the Masterclass's classes 1-7
UPDATE lessons l1
  JOIN modules m1 ON m1.id = l1.module_id
  JOIN courses c1 ON c1.id = m1.course_id AND c1.folder_name = 'IELTS_Aca_1Mo'
  JOIN courses c2 ON c2.folder_name = 'IELTS_Aca_2Mo'
  JOIN modules m2 ON m2.course_id = c2.id AND m2.module_order = m1.module_order
  JOIN lessons l2 ON l2.module_id = m2.id AND l2.lesson_order = l1.lesson_order
   SET l1.title = l2.title, l1.duration_minutes = l2.duration_minutes, l1.icon = l2.icon, l1.min_tier = l2.min_tier, l1.file_path = NULL
 WHERE (m1.module_order - 1) * 2 + l1.lesson_order <= 7;
-- class 8 = the mock (a full class: 4 hours)
UPDATE lessons l1
  JOIN modules m1 ON m1.id = l1.module_id
  JOIN courses c1 ON c1.id = m1.course_id AND c1.folder_name = 'IELTS_Aca_1Mo'
   SET l1.title = 'Mock Test 1 — Full Timed (L, R, W, S)', l1.duration_minutes = 240, l1.icon = 'bi-book', l1.min_tier = 'intermediate', l1.file_path = NULL
 WHERE m1.module_order = 4 AND l1.lesson_order = 2;

-- pieces: start again for this course, then copy
DELETE lp FROM lesson_parts lp
  JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Aca_1Mo';
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l1.id, lp2.part_order, lp2.title, lp2.kind, lp2.status, REPLACE(lp2.file_path, 'IELTS_Aca_2Mo', 'IELTS_Aca_1Mo')
  FROM lessons l1
  JOIN modules m1 ON m1.id = l1.module_id
  JOIN courses c1 ON c1.id = m1.course_id AND c1.folder_name = 'IELTS_Aca_1Mo'
  JOIN courses c2 ON c2.folder_name = 'IELTS_Aca_2Mo'
  JOIN modules m2 ON m2.course_id = c2.id AND m2.module_order = m1.module_order
  JOIN lessons l2 ON l2.module_id = m2.id AND l2.lesson_order = l1.lesson_order
  JOIN lesson_parts lp2 ON lp2.lesson_id = l2.id
 WHERE (m1.module_order - 1) * 2 + l1.lesson_order <= 7 AND lp2.title <> 'Reading Test 2';
-- Reading Test 2: listed, Coming Soon for now (its test page is being reviewed by the founder)
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l1.id, 1, 'Reading Test 2', 'practice_test', 'coming_soon', NULL
  FROM lessons l1 JOIN modules m1 ON m1.id = l1.module_id JOIN courses c1 ON c1.id = m1.course_id
 WHERE c1.folder_name = 'IELTS_Aca_1Mo' AND m1.module_order = 4 AND l1.lesson_order = 1;
-- the mock
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l1.id, 1, 'Mock Test 1 — Full Timed (L, R, W, S)', 'mock_test', 'coming_soon', NULL
  FROM lessons l1 JOIN modules m1 ON m1.id = l1.module_id JOIN courses c1 ON c1.id = m1.course_id
 WHERE c1.folder_name = 'IELTS_Aca_1Mo' AND m1.module_order = 4 AND l1.lesson_order = 2;
UPDATE courses SET total_lessons = 8 WHERE folder_name = 'IELTS_Aca_1Mo';

-- ---- 2. CELPIP General Masterclass -- 3 Months: the third mock ---------------------------------------
UPDATE lesson_parts lp
  JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.kind = 'lesson'
 WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 12 AND l.lesson_order = 2 AND lp.title = 'Test-Day Coaching';
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Mock Test 3 — Final Assessment', 'mock_test', 'coming_soon', NULL
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 12 AND l.lesson_order = 2
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Mock Test 3 — Final Assessment');

-- Verify: 1 mock in the Crash Course, 3 in the CELPIP 3-Month
SELECT c.folder_name, COUNT(*) AS mock_pieces FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name IN ('IELTS_Aca_1Mo', 'CELPIP_Gen_3Mo') AND lp.kind = 'mock_test' GROUP BY c.folder_name;
