-- ============================================================
-- Migration 138 -- IELTS General Training practice tests (Cambridge IELTS 15 GT) into the IELTS General Masterclass
--
-- 1. Writing Task 1 (letter) and Task 2 (essay) practice tests 2-5 (Cambridge IELTS 15 General Training Tests 1-4): the `tests` rows
--    (pages ielts_writing_t1_002..005.php / ielts_writing_t2_002..005.php were empty placeholders). Reading Practice Tests 2-5 are migration 137.
--    Both writing tasks are open-ended and AI-marked (essay_analyzer.php): no question rows. Listening (no audio) and Speaking stay in the bank.
-- 2. IELTS General Masterclass -- 3 Months, classes 20-23 (each was a single piece of about an hour): a practice-test piece is added beside it, so each
--    class is about two hours and the test is its own piece:
--      Class 20  Writing Task 2 Practice Test 2      (this migration's Cambridge GT Test 1 essay)
--      Class 21  Reading Practice Test 2             (Cambridge GT Test 1 reading, migration 137)
--      Class 22  Speaking Practice Test 2            (existing page ielts_speaking_002.php)
--      Class 23  Listening Practice Test 2           (existing page ielts_listening_002.php)  and "Final Preparation ..." is corrected from practice test to lesson
--    Nothing existing is replaced; the pieces are added.
--    Reading Practice Tests 3-5 and the other writing tests have no class slot: they exist as pages and are open to IELTS General students by link.
-- Idempotent. Run AFTER 126, 128 and 137. Written portable for older MySQL/MariaDB.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_002', 'IELTS General Training Writing Task 1 – Practice Test 2', 'A 20-minute timed IELTS General Training Writing Task 1 (letter). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_002');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_002', 'IELTS General Training Writing Task 2 – Practice Test 2', 'A 40-minute timed IELTS General Training Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_002');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_003', 'IELTS General Training Writing Task 1 – Practice Test 3', 'A 20-minute timed IELTS General Training Writing Task 1 (letter). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_003');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_003', 'IELTS General Training Writing Task 2 – Practice Test 3', 'A 40-minute timed IELTS General Training Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_003');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_004', 'IELTS General Training Writing Task 1 – Practice Test 4', 'A 20-minute timed IELTS General Training Writing Task 1 (letter). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_004');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_004', 'IELTS General Training Writing Task 2 – Practice Test 4', 'A 40-minute timed IELTS General Training Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_004');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_005', 'IELTS General Training Writing Task 1 – Practice Test 5', 'A 20-minute timed IELTS General Training Writing Task 1 (letter). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_005');
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_005', 'IELTS General Training Writing Task 2 – Practice Test 5', 'A 40-minute timed IELTS General Training Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_005');

-- Class 23: the 'Final Preparation' piece is a lesson (its title says Test-Day, which the kind rule reads as a test)
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.kind = 'lesson'
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 12 AND l.lesson_order = 1 AND lp.part_order = 1 AND lp.kind = 'practice_test';

-- the added test pieces
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Writing Task 2 Practice Test 2', 'practice_test', 'ready', 'resources/practice_tests/ielts_writing_t2_002.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 10 AND l.lesson_order = 2
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Writing Task 2 Practice Test 2');
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Reading Practice Test 2', 'practice_test', 'ready', 'resources/practice_tests/ielts_reading_002.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 11 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Reading Practice Test 2');
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Speaking Practice Test 2', 'practice_test', 'ready', 'resources/practice_tests/ielts_speaking_002.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 11 AND l.lesson_order = 2
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Speaking Practice Test 2');
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Listening Practice Test 2', 'practice_test', 'ready', 'resources/practice_tests/ielts_listening_002.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 12 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Listening Practice Test 2');

-- The overview builds a class's piece list from the class title (pieces joined with ' + '), so the added pieces are named in the titles too
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.title = CONCAT(l.title, ' + Writing Task 2 Practice Test 2')
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 10 AND l.lesson_order = 2 AND l.title NOT LIKE '%Writing Task 2 Practice Test 2';
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.title = CONCAT(l.title, ' + Reading Practice Test 2')
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 11 AND l.lesson_order = 1 AND l.title NOT LIKE '%Reading Practice Test 2';
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.title = CONCAT(l.title, ' + Speaking Practice Test 2')
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 11 AND l.lesson_order = 2 AND l.title NOT LIKE '%Speaking Practice Test 2';
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.title = CONCAT(l.title, ' + Listening Practice Test 2')
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND m.module_order = 12 AND l.lesson_order = 1 AND l.title NOT LIKE '%Listening Practice Test 2';

-- Verify
SELECT COUNT(*) AS writing_test_rows FROM tests WHERE code REGEXP '^IELTS_PT_W[12]_00[2-5]$';
SELECT (m.module_order - 1) * 2 + l.lesson_order AS class_no, lp.part_order, lp.title, lp.kind, lp.file_path
  FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Gen_Mst' AND (m.module_order - 1) * 2 + l.lesson_order BETWEEN 20 AND 23 ORDER BY 1, 2;
