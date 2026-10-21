-- ============================================================
-- Migration 163 -- IELTS General 3-Month: no test sat twice
--
-- Founder, 2026-10-21 (from documentation/CONTENT_BANK_MAP.md "Known clashes"):
--  * Class 21 "Reading Practice Test 2" (IELTS_PT_R_002) has the same passages as Class 4's Reading Practice Test 1
--    (both Cambridge IELTS 15 GT, Test 1). It now opens Reading Practice Test 3 (IELTS_PT_R_003, Cambridge IELTS 15
--    GT, Test 2), used by no other class.
--  * Class 23 "Listening Practice Test 2" repeated Class 7's test. It now opens Listening Practice Test 3
--    (IELTS_PT_L_003, all four audio parts present), used by no other course.
-- Item and class titles name the test they open.
--
-- Matched by course, class and old page, so re-running is safe. MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.title = 'Reading Practice Test 3',
       p.file_path = 'resources/practice_tests/ielts_reading_003.php',
       p.test_code = 'IELTS_PT_R_003'
 WHERE l.course_id = 9 AND (m.module_order - 1) * 2 + l.lesson_order = 21
   AND p.file_path = 'resources/practice_tests/ielts_reading_002.php';

UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.title = 'Listening Practice Test 3',
       p.file_path = 'resources/practice_tests/ielts_listening_003.php',
       p.test_code = 'IELTS_PT_L_003'
 WHERE l.course_id = 9 AND (m.module_order - 1) * 2 + l.lesson_order = 23
   AND p.file_path = 'resources/practice_tests/ielts_listening_002.php';

UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
   SET l.title = REPLACE(REPLACE(l.title, 'Reading Practice Test 2', 'Reading Practice Test 3'), 'Listening Practice Test 2', 'Listening Practice Test 3')
 WHERE l.course_id = 9 AND (m.module_order - 1) * 2 + l.lesson_order IN (21, 23);

COMMIT;
