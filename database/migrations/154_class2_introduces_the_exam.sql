-- ============================================================
-- Migration 154 -- 2- and 3-month courses: Class 1 introduces the COURSE, Class 2 the EXAM
--
-- Founder, 2026-10-19: "the introduction in class 1 introduces the course, not the exam ... the intro
-- in class 2 should introduce the actual exam and then a quiz is taken to lock in the lesson, before the
-- diagnostic test is taken." Band descriptors are exam information, so they're Class 2 too. From Class 3
-- (Week 2) every class covers an actual section. Where an item's content doesn't exist yet, its page is
-- created with headings and the item says Coming Soon until it's written.
-- (1-month crash courses keep their shape. IELTS Academic and PTE wait on the founder's call about where
-- their current Class 2 section content moves.)
--
--   CELPIP 2/3-Month (13, 14)
--     Class 2: CELPIP Introduction (lesson, outline only -- Coming Soon) + CELPIP Introduction Quiz
--              (quiz, Coming Soon) + CELPIP Diagnostic Test
--   IELTS General 3/2-Month (9, 11)
--     Class 1: Course Introduction (intro.php?part=course) + Foundational English Assessment
--     Class 2: IELTS General Introduction (intro.php?part=exam) + Band Descriptor Review
--              + IELTS Format Knowledge Quiz + Band Descriptor Matching Quiz
--              + IELTS General Training Diagnostic Test
--     The 3-month course's two quiz schedule entries follow (same names, Format Knowledge quiz now Class 2).
--
-- These classes' items are rewritten in full (delete + insert in a fixed order): nothing refers to an
-- item row by id. Classes are found by course + week + class order. Re-runnable, MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;

-- ---------- CELPIP 2/3-Month, Class 2 ----------
DELETE p FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 2;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, x.ord, x.title, x.kind, x.status, x.file_path
  FROM lessons l JOIN modules m ON m.id = l.module_id
  JOIN (SELECT 1 AS ord, 'CELPIP Introduction' AS title, 'lesson' AS kind, 'coming_soon' AS status, 'courses/CELPIP_Gen/lessons/exam_introduction.php' AS file_path
        UNION ALL SELECT 2, 'CELPIP Introduction Quiz', 'quiz', 'coming_soon', NULL
        UNION ALL SELECT 3, 'CELPIP Diagnostic Test', 'diagnostic_test', 'ready', 'courses/CELPIP_intro/celpip_mini_mock.php') x
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'CELPIP Introduction + CELPIP Introduction Quiz + CELPIP Diagnostic Test',
       l.file_path = 'courses/CELPIP_Gen/lessons/exam_introduction.php'
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 2;

-- ---------- IELTS General 3/2-Month, Classes 1 and 2 ----------
DELETE p FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (9, 11) AND m.module_order = 1 AND l.lesson_order IN (1, 2);

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, x.ord, x.title, x.kind, 'ready', x.file_path
  FROM lessons l JOIN modules m ON m.id = l.module_id
  JOIN (SELECT 1 AS ord, 'Course Introduction' AS title, 'lesson' AS kind, 'courses/IELTS_Gen/lessons/intro.php?part=course' AS file_path
        UNION ALL SELECT 2, 'Foundational English Assessment', 'placement_test', 'mini_tests/english-placement-test/BELStart.html') x
 WHERE l.course_id IN (9, 11) AND m.module_order = 1 AND l.lesson_order = 1;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, x.ord, x.title, x.kind, 'ready', x.file_path
  FROM lessons l JOIN modules m ON m.id = l.module_id
  JOIN (SELECT 1 AS ord, 'IELTS General Introduction' AS title, 'lesson' AS kind, 'courses/IELTS_Gen/lessons/intro.php?part=exam' AS file_path
        UNION ALL SELECT 2, 'Band Descriptor Review', 'lesson', 'courses/IELTS_Gen/lessons/class02.php'
        UNION ALL SELECT 3, 'IELTS Format Knowledge Quiz', 'quiz', 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C1_QUIZ'
        UNION ALL SELECT 4, 'Band Descriptor Matching Quiz', 'quiz', 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C2_QUIZ'
        UNION ALL SELECT 5, 'IELTS General Training Diagnostic Test', 'diagnostic_test', 'resources/mock_tests/ielts_gt_diagnostic.php') x
 WHERE l.course_id IN (9, 11) AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Class 1: Course Introduction + Foundational English Assessment',
       l.file_path = 'courses/IELTS_Gen/lessons/intro.php?part=course'
 WHERE l.course_id IN (9, 11) AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Class 2: IELTS General Introduction + Band Descriptor Review + IELTS Format Knowledge Quiz + Band Descriptor Matching Quiz + IELTS General Training Diagnostic Test',
       l.file_path = 'courses/IELTS_Gen/lessons/intro.php?part=exam'
 WHERE l.course_id IN (9, 11) AND m.module_order = 1 AND l.lesson_order = 2;

-- The 3-month course's schedule: both quizzes now belong to Class 2 (due with it), under the same names.
UPDATE course_pacing_items ci
  JOIN lessons l ON l.course_id = 9
  JOIN modules m ON m.id = l.module_id AND m.module_order = 1 AND l.lesson_order = 2
   SET ci.lesson_id = l.id, ci.offset_days = 3,
       ci.title = CASE ci.test_code WHEN 'IELTS_GM_C1_QUIZ' THEN 'IELTS Format Knowledge Quiz' ELSE 'Band Descriptor Matching Quiz' END,
       ci.display_order = CASE ci.test_code WHEN 'IELTS_GM_C1_QUIZ' THEN 20 ELSE 21 END
 WHERE ci.course_id = 9 AND ci.item_type = 'quiz' AND ci.test_code IN ('IELTS_GM_C1_QUIZ', 'IELTS_GM_C2_QUIZ');
