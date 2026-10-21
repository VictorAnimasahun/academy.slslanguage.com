-- ============================================================
-- Migration 158 -- crash courses compress their introduction into Class 1; 2-month mocks at each month's end
--
-- Founder, 2026-10-21:
--  * "Maybe we should compress stuff here since it's a crash course ... applicable to all crash courses."
--    Every 1-month course's Class 1 now holds the whole introduction -- Course Introduction, <Exam> Introduction,
--    Introduction Quiz, Foundational English Assessment, Diagnostic Test -- and Class 2 onward is the exam's sections.
--      IELTS General 1-Month (10): the Diagnostic Test moves from Class 2 to Class 1; Class 2 keeps Band Descriptor
--        Review + its quiz.
--      CELPIP 1-Month (12), PTE 1-Month (18), IELTS Academic 1-Month (23): Class 1 gains the exam introduction, the
--        introduction quiz (Coming Soon) and the diagnostic (PTE's: Coming Soon -- none exists yet). New Course
--        Introduction pages for IELTS Academic and PTE; their exam pages are now Class 1 item 2.
--  * "[A 2-month course] has/should have 2 mocks. Month 1 and Month 2. Applicable to all 2-month courses."
--    IELTS General and PTE 2-Month already have them in Weeks 4 and 8.
--      CELPIP 2-Month (13): Mock Test 1 moves from Class 15 (Week 8) to Class 8 (Week 4); Classes 8-14 each move one
--        class later, in order. Its schedule entry follows.
--      IELTS Academic 2-Month (16): Mock Test 1 moves from Class 9 to Class 8 (Week 4) and its review from Class 10
--        to Class 9; the old Class 8 moves to Class 10. Moved pages were renamed for their new class.
--    A class keeps its own plan level (weeks 1-4 / 5-8); only its contents move. Week titles follow the contents.
--
-- One transaction. Classes found by course + week + class order. The class moves only run while each mock is still
-- in its old place, so re-running is safe. MySQL 5.7-safe (each temporary table is used once per statement).
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

-- ======================= 1. Crash courses: the whole introduction in Class 1 =======================
DELETE p FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
 WHERE (l.course_id IN (12, 18, 23) AND m.module_order = 1 AND l.lesson_order = 1)
    OR (l.course_id = 10 AND m.module_order = 1 AND l.lesson_order IN (1, 2));

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code, pass_percent)
SELECT l.id, x.ord, x.title, x.kind, x.status,
       CASE WHEN x.file IS NULL OR x.file NOT LIKE 'lessons/%' THEN x.file
            ELSE CONCAT('courses/', CONVERT(c.folder_name USING utf8mb4) COLLATE utf8mb4_unicode_ci, '/',
                        CONVERT(x.file USING utf8mb4) COLLATE utf8mb4_unicode_ci) END,
       x.test_code, x.pass_percent
  FROM lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN courses c ON c.id = l.course_id
  JOIN (
        -- IELTS General 1-Month
        SELECT 10 AS course_id, 1 AS cls, 1 AS ord, 'Course Introduction' AS title, 'lesson' AS kind, 'ready' AS status, 'courses/IELTS_Gen/lessons/intro.php?part=course' AS file, NULL AS test_code, NULL AS pass_percent
        UNION ALL SELECT 10, 1, 2, 'IELTS General Introduction', 'lesson', 'ready', 'courses/IELTS_Gen/lessons/intro.php?part=exam', NULL, NULL
        UNION ALL SELECT 10, 1, 3, 'IELTS Format Knowledge Quiz', 'quiz', 'ready', 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C1_QUIZ', 'IELTS_GM_C1_QUIZ', 70
        UNION ALL SELECT 10, 1, 4, 'Foundational English Assessment', 'placement_test', 'coming_soon', 'resources/placement/foundational_english_assessment.php', NULL, NULL
        UNION ALL SELECT 10, 1, 5, 'IELTS General Training Diagnostic Test', 'diagnostic_test', 'ready', 'resources/mock_tests/ielts_gt_diagnostic.php', 'IELTS_GT_DIAGNOSTIC', NULL
        UNION ALL SELECT 10, 2, 1, 'Band Descriptor Review', 'lesson', 'ready', 'courses/IELTS_Gen/lessons/class02.php', NULL, NULL
        UNION ALL SELECT 10, 2, 2, 'Band Descriptor Matching Quiz', 'quiz', 'ready', 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C2_QUIZ', 'IELTS_GM_C2_QUIZ', 70
        -- CELPIP 1-Month
        UNION ALL SELECT 12, 1, 1, 'Course Introduction', 'lesson', 'ready', 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php', NULL, NULL
        UNION ALL SELECT 12, 1, 2, 'CELPIP Introduction', 'lesson', 'coming_soon', 'courses/CELPIP_Gen/lessons/exam_introduction.php', NULL, NULL
        UNION ALL SELECT 12, 1, 3, 'CELPIP Introduction Quiz', 'quiz', 'coming_soon', NULL, NULL, NULL
        UNION ALL SELECT 12, 1, 4, 'Foundational English Assessment', 'placement_test', 'coming_soon', 'resources/placement/foundational_english_assessment.php', NULL, NULL
        UNION ALL SELECT 12, 1, 5, 'CELPIP Diagnostic Test', 'diagnostic_test', 'ready', 'courses/CELPIP_intro/celpip_mini_mock.php', NULL, NULL
        -- PTE 1-Month
        UNION ALL SELECT 18, 1, 1, 'Course Introduction', 'lesson', 'ready', 'lessons/class01_p1_course-introduction.php', NULL, NULL
        UNION ALL SELECT 18, 1, 2, 'PTE Academic Introduction', 'lesson', 'ready', 'lessons/class01_p2_pte-academic-introduction.php', NULL, NULL
        UNION ALL SELECT 18, 1, 3, 'PTE Academic Introduction Quiz', 'quiz', 'coming_soon', NULL, NULL, NULL
        UNION ALL SELECT 18, 1, 4, 'Foundational English Assessment', 'placement_test', 'coming_soon', 'resources/placement/foundational_english_assessment.php', NULL, NULL
        UNION ALL SELECT 18, 1, 5, 'PTE Academic Diagnostic Test', 'diagnostic_test', 'coming_soon', NULL, NULL, NULL
        -- IELTS Academic 1-Month
        UNION ALL SELECT 23, 1, 1, 'Course Introduction', 'lesson', 'ready', 'lessons/class01_p1_course-introduction.php', NULL, NULL
        UNION ALL SELECT 23, 1, 2, 'IELTS Academic Introduction', 'lesson', 'ready', 'lessons/class01_p2_ielts-academic-introduction.php', NULL, NULL
        UNION ALL SELECT 23, 1, 3, 'IELTS Academic Introduction Quiz', 'quiz', 'coming_soon', NULL, NULL, NULL
        UNION ALL SELECT 23, 1, 4, 'Foundational English Assessment', 'placement_test', 'coming_soon', 'resources/placement/foundational_english_assessment.php', NULL, NULL
        UNION ALL SELECT 23, 1, 5, 'IELTS Academic Diagnostic Test', 'diagnostic_test', 'ready', 'resources/mock_tests/ielts_aca_diagnostic.php', 'IELTS_ACA_DIAGNOSTIC', NULL
       ) x ON x.course_id = l.course_id AND x.cls = (m.module_order - 1) * 2 + l.lesson_order
 WHERE l.course_id IN (10, 12, 18, 23);

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = CASE
         WHEN l.course_id = 10 AND l.lesson_order = 1 THEN 'Class 1: Course Introduction + IELTS General Introduction + IELTS Format Knowledge Quiz + Foundational English Assessment + IELTS General Training Diagnostic Test'
         WHEN l.course_id = 10 AND l.lesson_order = 2 THEN 'Class 2: Band Descriptor Review + Band Descriptor Matching Quiz'
         WHEN l.course_id = 12 THEN 'Course Introduction + CELPIP Introduction + CELPIP Introduction Quiz + Foundational English Assessment + CELPIP Diagnostic Test'
         WHEN l.course_id = 18 THEN 'Course Introduction + PTE Academic Introduction + PTE Academic Introduction Quiz + Foundational English Assessment + PTE Academic Diagnostic Test'
         ELSE 'Course Introduction + IELTS Academic Introduction + IELTS Academic Introduction Quiz + Foundational English Assessment + IELTS Academic Diagnostic Test' END,
       l.file_path = (SELECT p.file_path FROM lesson_parts p WHERE p.lesson_id = l.id AND p.part_order = 1)
 WHERE m.module_order = 1 AND ((l.course_id IN (12, 18, 23) AND l.lesson_order = 1) OR (l.course_id = 10 AND l.lesson_order IN (1, 2)));

UPDATE modules SET module_title = 'Week 1 — Introduction & First Steps'
 WHERE course_id IN (10, 12, 18, 23) AND module_order = 1;

-- ======================= 2. 2-month courses: Mock Test 1 at the end of Month 1 =======================
-- Which class's contents go where (dst <- src). Only while each mock is still in its old class.
CREATE TEMPORARY TABLE m158_rot (course_id INT NOT NULL, dst_cls INT NOT NULL, src_cls INT NOT NULL);

INSERT INTO m158_rot (course_id, dst_cls, src_cls)
SELECT 13, x.dst, x.src FROM (SELECT 8 AS dst, 15 AS src UNION ALL SELECT 9, 8 UNION ALL SELECT 10, 9 UNION ALL SELECT 11, 10
                              UNION ALL SELECT 12, 11 UNION ALL SELECT 13, 12 UNION ALL SELECT 14, 13 UNION ALL SELECT 15, 14) x
 WHERE EXISTS (SELECT 1 FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
                WHERE l.course_id = 13 AND (m.module_order - 1) * 2 + l.lesson_order = 15 AND p.kind = 'mock_test');

INSERT INTO m158_rot (course_id, dst_cls, src_cls)
SELECT 16, x.dst, x.src FROM (SELECT 8 AS dst, 9 AS src UNION ALL SELECT 9, 10 UNION ALL SELECT 10, 8) x
 WHERE EXISTS (SELECT 1 FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
                WHERE l.course_id = 16 AND (m.module_order - 1) * 2 + l.lesson_order = 9 AND p.kind = 'mock_test');

-- Snapshots of each class's contents (src) and its row (dst), before anything moves.
CREATE TEMPORARY TABLE m158_src AS
SELECT l.course_id, (m.module_order - 1) * 2 + l.lesson_order AS cls, l.id AS lesson_id,
       l.title, l.content, l.video_url, l.file_path, l.icon, l.duration_minutes
  FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.course_id IN (13, 16);
CREATE TEMPORARY TABLE m158_dst AS
SELECT l.course_id, (m.module_order - 1) * 2 + l.lesson_order AS cls, l.id AS lesson_id
  FROM lessons l JOIN modules m ON m.id = l.module_id WHERE l.course_id IN (13, 16);

-- Each moving item and the class row it goes to.
CREATE TEMPORARY TABLE m158_move AS
SELECT p.id AS part_id, r.src_cls, d.lesson_id AS new_lesson_id
  FROM lesson_parts p
  JOIN m158_src s ON s.lesson_id = p.lesson_id
  JOIN m158_rot r ON r.course_id = s.course_id AND r.src_cls = s.cls
  JOIN m158_dst d ON d.course_id = r.course_id AND d.cls = r.dst_cls;

-- Move the items (part_order is offset by the source class first, so no two items ever share a slot mid-move).
UPDATE lesson_parts p JOIN m158_move mv ON mv.part_id = p.id SET p.part_order = p.part_order + 10 * mv.src_cls;
UPDATE lesson_parts p JOIN m158_move mv ON mv.part_id = p.id SET p.lesson_id = mv.new_lesson_id;
UPDATE lesson_parts p JOIN m158_move mv ON mv.part_id = p.id SET p.part_order = p.part_order MOD 10;

-- Move each class's own fields with its contents; the class keeps its position and plan level.
UPDATE lessons l
  JOIN m158_dst d ON d.lesson_id = l.id
  JOIN m158_rot r ON r.course_id = d.course_id AND r.dst_cls = d.cls
  JOIN m158_src s ON s.course_id = r.course_id AND s.cls = r.src_cls
   SET l.title = s.title, l.content = s.content, l.video_url = s.video_url, l.file_path = s.file_path,
       l.icon = s.icon, l.duration_minutes = s.duration_minutes;

-- IELTS Academic 2-Month: the moved pages were renamed for their new class.
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_2Mo/lessons/class08_p2_task-1-report-structuring.php', 'IELTS_Aca_2Mo/lessons/class10_p2_task-1-report-structuring.php')
 WHERE file_path LIKE '%IELTS_Aca_2Mo/lessons/class08_p2_task-1-report-structuring.php%';
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_2Mo/lessons/class10_p1_mock-1-review.php', 'IELTS_Aca_2Mo/lessons/class09_p1_mock-1-review.php')
 WHERE file_path LIKE '%IELTS_Aca_2Mo/lessons/class10_p1_mock-1-review.php%';
UPDATE lesson_parts SET file_path = REPLACE(file_path, 'IELTS_Aca_2Mo/lessons/class10_p2_reading-time-management.php', 'IELTS_Aca_2Mo/lessons/class09_p2_reading-time-management.php')
 WHERE file_path LIKE '%IELTS_Aca_2Mo/lessons/class10_p2_reading-time-management.php%';
UPDATE lessons l SET l.file_path = (SELECT p.file_path FROM lesson_parts p WHERE p.lesson_id = l.id AND p.part_order = 1)
 WHERE l.course_id = 16 AND l.file_path LIKE '%IELTS_Aca_2Mo/lessons/class%';

-- CELPIP 2-Month's schedule entry for Mock 1 follows it to Class 8 (end of Month 1).
UPDATE course_pacing_items ci
  JOIN m158_dst d ON d.course_id = 13 AND d.cls = 8
   SET ci.lesson_id = d.lesson_id, ci.offset_days = 24, ci.title = 'Class 8 — Mock Exam 1'
 WHERE ci.course_id = 13 AND ci.item_type = 'mock_test' AND ci.test_code = 'CELPIP_FULL_MOCK_A';

-- Week titles follow the contents.
UPDATE modules SET module_title = CASE module_order
         WHEN 4 THEN 'Week 4 — Reading Part 2 & Mock Test 1'
         WHEN 5 THEN 'Week 5 — Reading Test 2 & Speaking Test 2'
         WHEN 6 THEN 'Week 6 — Writing Test 2 & Reading Part 3'
         WHEN 7 THEN 'Week 7 — Reading Test 3 & Speaking Test 3'
         ELSE 'Week 8 — Writing Test 3 & Mock Test 2' END
 WHERE course_id = 13 AND module_order BETWEEN 4 AND 8;
UPDATE modules SET module_title = CASE module_order
         WHEN 4 THEN 'Week 4 — Reading Test 2 & Mock Test 1'
         ELSE 'Week 5 — Mock 1 Review & Speaking Test 2' END
 WHERE course_id = 16 AND module_order IN (4, 5);

DROP TEMPORARY TABLE m158_rot;
DROP TEMPORARY TABLE m158_src;
DROP TEMPORARY TABLE m158_dst;
DROP TEMPORARY TABLE m158_move;

COMMIT;
