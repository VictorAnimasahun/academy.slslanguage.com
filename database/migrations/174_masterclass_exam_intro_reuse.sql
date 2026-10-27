-- ============================================================
-- Migration 174 -- Masterclass Class 2: reuse the crash courses' exam introduction and exam quiz
--
-- Founder, 2026-10-27: the 2/3-month courses' Class 2 items that were Coming Soon reuse what the crash courses
-- already have, instead of waiting on new content:
--   CELPIP_Gen_2Mo / CELPIP_Gen_3Mo  item 1 CELPIP Introduction      -> courses/<folder>/lessons/class02_p1_celpip-introduction.php
--                                    item 2 CELPIP Introduction Quiz -> CELPIP_C1_QUIZ (20 questions)
--   IELTS_Aca_2Mo / IELTS_Aca_3Mo    item 2 IELTS Academic Introduction Quiz -> IELTS_ACA_C1_QUIZ (20 questions)
-- (The IELTS Academic Masterclasses' introduction lesson was already written.) The four crash-course quizzes lose
-- "Class 1 Quiz —" from their names, since they now also sit in Class 2 (migration 170's generator changed to match).
-- Class 2 = Week 1, second class. Courses found by folder. Re-runnable. MySQL 5.7-safe. The SELECT at the end lists
-- each changed Class 2.
-- ============================================================

SET NAMES utf8mb4;

UPDATE tests SET title = 'IELTS Academic Introduction Quiz', description = 'The exam parts, timing and scoring.' WHERE code = 'IELTS_ACA_C1_QUIZ';
UPDATE tests SET title = 'IELTS General Training Introduction Quiz', description = 'The exam parts, timing and scoring.' WHERE code = 'IELTS_GT_C1_QUIZ';
UPDATE tests SET title = 'CELPIP General Introduction Quiz', description = 'The exam parts, timing and scoring.' WHERE code = 'CELPIP_C1_QUIZ';
UPDATE tests SET title = 'PTE Academic Introduction Quiz', description = 'The exam parts, timing and scoring.' WHERE code = 'PTE_C1_QUIZ';

-- CELPIP Masterclasses: item 1, the exam introduction
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id
   SET p.status = 'ready', p.file_path = CONCAT('courses/', c.folder_name, '/lessons/class02_p1_celpip-introduction.php')
 WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo') AND m.module_order = 1 AND l.lesson_order = 2 AND p.part_order = 1
   AND p.title = 'CELPIP Introduction';

-- CELPIP Masterclasses: item 2, the exam quiz
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id
   SET p.status = 'ready', p.kind = 'quiz', p.file_path = 'resources/quizzes/class_quiz.php?test_code=CELPIP_C1_QUIZ',
       p.test_code = 'CELPIP_C1_QUIZ', p.pass_percent = 70
 WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo') AND m.module_order = 1 AND l.lesson_order = 2 AND p.part_order = 2
   AND p.title = 'CELPIP Introduction Quiz';

-- IELTS Academic Masterclasses: item 2, the exam quiz
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id
   SET p.status = 'ready', p.kind = 'quiz', p.file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_ACA_C1_QUIZ',
       p.test_code = 'IELTS_ACA_C1_QUIZ', p.pass_percent = 70
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND m.module_order = 1 AND l.lesson_order = 2 AND p.part_order = 2
   AND p.title = 'IELTS Academic Introduction Quiz';

SELECT c.folder_name, p.part_order, p.title, p.kind, p.status, p.file_path
  FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = l.course_id
 WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo', 'IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND m.module_order = 1 AND l.lesson_order = 2
 ORDER BY c.folder_name, p.part_order;
