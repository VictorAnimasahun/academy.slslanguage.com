-- ============================================================
-- Migration 159 -- 3-month courses: a mock at the end of each month (Weeks 4, 8 and 12)
--
-- Founder, 2026-10-21: 3-month courses follow the same rule as 2-month ones (158) -- one mock per month, at its end.
-- IELTS General and PTE 3-Month already have Weeks 4, 8 and 12.
--   IELTS Academic 3-Month (17): Mock Test 1 from Class 9 (Week 5) to Class 8 (Week 4); its review from Class 10
--     to Class 9; the old Class 8 to Class 10 (same as the 2-Month course). Mocks 2 and 3 stay (Weeks 8, 12).
--   CELPIP 3-Month (14): Mock Test 1 + its review from Classes 15-16 (Week 8) to Classes 8-9 (Week 4); Mock Test 2
--     + its review from Classes 21-22 (Week 11) to Classes 16-17 (Week 8 / 9); every other class keeps its order
--     around them. Mock 3 stays in Week 12. Mock 2 is no longer the last mock: renamed "Mock Test 2 — Full-Length,
--     All 4 Sections", and its review "Mock 2 Review & Performance Trend".
-- A class keeps its own plan level; only its contents move. Week titles follow the contents. Moved IELTS Academic
-- pages were renamed for their new class; the CELPIP pages read their position from the course.
--
-- One transaction. The moves only run while Mock 1 is still in its old class, so re-running is safe.
-- MySQL 5.7-safe (each temporary table is used once per statement).
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

CREATE TEMPORARY TABLE m159_rot (course_id INT NOT NULL, dst_cls INT NOT NULL, src_cls INT NOT NULL);

INSERT INTO m159_rot (course_id, dst_cls, src_cls)
SELECT 17, x.dst, x.src FROM (SELECT 8 AS dst, 9 AS src UNION ALL SELECT 9, 10 UNION ALL SELECT 10, 8) x
 WHERE EXISTS (SELECT 1 FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
                WHERE l.course_id = 17 AND (m.module_order - 1) * 2 + l.lesson_order = 9 AND p.kind = 'mock_test');

INSERT INTO m159_rot (course_id, dst_cls, src_cls)
SELECT 14, x.dst, x.src FROM (
         SELECT 8 AS dst, 15 AS src UNION ALL SELECT 9, 16                                  -- Mock 1 + review
         UNION ALL SELECT 10, 8 UNION ALL SELECT 11, 9 UNION ALL SELECT 12, 10 UNION ALL SELECT 13, 11
         UNION ALL SELECT 14, 12 UNION ALL SELECT 15, 13
         UNION ALL SELECT 16, 21 UNION ALL SELECT 17, 22                                    -- Mock 2 + review
         UNION ALL SELECT 18, 14 UNION ALL SELECT 19, 17 UNION ALL SELECT 20, 18 UNION ALL SELECT 21, 19
         UNION ALL SELECT 22, 20) x
 WHERE EXISTS (SELECT 1 FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
                WHERE l.course_id = 14 AND (m.module_order - 1) * 2 + l.lesson_order = 15 AND p.kind = 'mock_test');

CREATE TEMPORARY TABLE m159_src AS
SELECT l.course_id, (m.module_order - 1) * 2 + l.lesson_order AS cls, l.id AS lesson_id,
       l.title, l.content, l.video_url, l.file_path, l.icon, l.duration_minutes
  FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.course_id IN (14, 17);
CREATE TEMPORARY TABLE m159_dst AS
SELECT l.course_id, (m.module_order - 1) * 2 + l.lesson_order AS cls, l.id AS lesson_id
  FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.course_id IN (14, 17);

CREATE TEMPORARY TABLE m159_move AS
SELECT p.id AS part_id, r.src_cls, d.lesson_id AS new_lesson_id
  FROM lesson_parts p
  JOIN m159_src s ON s.lesson_id = p.lesson_id
  JOIN m159_rot r ON r.course_id = s.course_id AND r.src_cls = s.cls
  JOIN m159_dst d ON d.course_id = r.course_id AND d.cls = r.dst_cls;

UPDATE lesson_parts p JOIN m159_move mv ON mv.part_id = p.id SET p.part_order = p.part_order + 10 * mv.src_cls;
UPDATE lesson_parts p JOIN m159_move mv ON mv.part_id = p.id SET p.lesson_id = mv.new_lesson_id;
UPDATE lesson_parts p JOIN m159_move mv ON mv.part_id = p.id SET p.part_order = p.part_order MOD 10;

UPDATE lessons l
  JOIN m159_dst d ON d.lesson_id = l.id
  JOIN m159_rot r ON r.course_id = d.course_id AND r.dst_cls = d.cls
  JOIN m159_src s ON s.course_id = r.course_id AND s.cls = r.src_cls
   SET l.title = s.title, l.content = s.content, l.video_url = s.video_url, l.file_path = s.file_path,
       l.icon = s.icon, l.duration_minutes = s.duration_minutes;

-- IELTS Academic 3-Month: moved pages renamed for their new class.
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_3Mo/lessons/class08_p2_task-1-report-structuring.php', 'IELTS_Aca_3Mo/lessons/class10_p2_task-1-report-structuring.php')
 WHERE file_path LIKE '%IELTS_Aca_3Mo/lessons/class08_p2_task-1-report-structuring.php%';
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_3Mo/lessons/class10_p1_mock-1-review.php', 'IELTS_Aca_3Mo/lessons/class09_p1_mock-1-review.php')
 WHERE file_path LIKE '%IELTS_Aca_3Mo/lessons/class10_p1_mock-1-review.php%';
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_3Mo/lessons/class10_p2_reading-time-management.php', 'IELTS_Aca_3Mo/lessons/class09_p2_reading-time-management.php')
 WHERE file_path LIKE '%IELTS_Aca_3Mo/lessons/class10_p2_reading-time-management.php%';
UPDATE lessons l SET l.file_path = (SELECT p.file_path FROM lesson_parts p WHERE p.lesson_id = l.id AND p.part_order = 1)
 WHERE l.course_id = 17 AND l.file_path LIKE '%IELTS_Aca_3Mo/lessons/class%';

-- CELPIP 3-Month: Mock 2 is no longer the final mock.
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.title = 'Mock Test 2 — Full-Length, All 4 Sections' WHERE l.course_id = 14 AND p.title = 'Mock Test 2 — Final Assessment';
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.title = 'Mock 2 Review & Performance Trend' WHERE l.course_id = 14 AND p.title = 'Final Performance Review';
UPDATE lessons SET title = 'Mock Test 2 — Full-Length, All 4 Sections' WHERE course_id = 14 AND title = 'Mock Test 2 — Final Assessment';
UPDATE lessons SET title = 'Mock 2 Review & Performance Trend' WHERE course_id = 14 AND title = 'Final Performance Review';

-- Week titles follow the contents.
UPDATE modules SET module_title = CASE module_order
         WHEN 4 THEN 'Week 4 — Reading Test 2 & Mock Test 1'
         ELSE 'Week 5 — Mock 1 Review & Speaking Test 2' END
 WHERE course_id = 17 AND module_order IN (4, 5);
UPDATE modules SET module_title = CASE module_order
         WHEN 4 THEN 'Week 4 — Reading Part 2 & Mock Test 1'
         WHEN 5 THEN 'Week 5 — Mock 1 Review & Reading Test 2'
         WHEN 6 THEN 'Week 6 — Speaking Test 2 & Writing Test 2'
         WHEN 7 THEN 'Week 7 — Reading Part 3 & Reading Test 3'
         WHEN 8 THEN 'Week 8 — Speaking Test 3 & Mock Test 2'
         WHEN 9 THEN 'Week 9 — Mock 2 Review & Writing Test 3'
         WHEN 10 THEN 'Week 10 — Reading Part 4 & Writing Self-Editing'
         ELSE 'Week 11 — Speaking & Writing Practice Test 4' END
 WHERE course_id = 14 AND module_order BETWEEN 4 AND 11;

DROP TEMPORARY TABLE m159_rot;
DROP TEMPORARY TABLE m159_src;
DROP TEMPORARY TABLE m159_dst;
DROP TEMPORARY TABLE m159_move;

COMMIT;
