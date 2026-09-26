-- ============================================================
-- Migration 136 -- IELTS Academic Writing Tests 2, 3 and 4 (Cambridge IELTS 17 Academic, Tests 2, 3, 4) wired to "Writing Test 2 / 3 / 4"
-- (test codes IELTS_PT_W1_ACA_002..004 and IELTS_PT_W2_ACA_002..004; hub pages resources/practice_tests/ielts_writing_academic_002..004.php,
--  each with a Task 1 page (t1) and a Task 2 page (t2))
--
-- Source: documentation/test_bank/cambridge_ielts17_academic/test2|3|4.json (`writing`). Like Writing Test 1 (migration 110) both tasks are open-ended and
-- AI-marked (essay_analyzer.php), so there are no question rows: only the two `tests` rows per test. The Task 1 figures are redrawn from the book's
-- charts as inline SVG (bar chart, line graph, pie charts) because the book's images were not extracted; values were read from the printed figures.
-- Writing Test 1 (official IELTS.org sample tasks) is unchanged; the Cambridge Test 1 writing (a pair of maps) stays in the bank.
-- Points the "Writing Test 2 (Timed)", "Writing Test 3 (Timed)" (2- and 3-Month) and "Writing Test 4" (3-Month) pieces at the hub pages.
-- Idempotent. Run AFTER 126, 128 and 134. Written portable for older MySQL/MariaDB.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_ACA_002', 'IELTS Academic Writing Task 1 – Test 2', 'A 20-minute timed IELTS Academic Writing Task 1 (a table and two pie charts). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_ACA_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_ACA_002', 'IELTS Academic Writing Task 2 – Test 2', 'A 40-minute timed IELTS Academic Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_ACA_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_ACA_003', 'IELTS Academic Writing Task 1 – Test 3', 'A 20-minute timed IELTS Academic Writing Task 1 (a bar chart). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_ACA_003');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_ACA_003', 'IELTS Academic Writing Task 2 – Test 3', 'A 40-minute timed IELTS Academic Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_ACA_003');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_ACA_004', 'IELTS Academic Writing Task 1 – Test 4', 'A 20-minute timed IELTS Academic Writing Task 1 (a line graph). AI-graded via essay_analyzer.', 'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_ACA_004');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_ACA_004', 'IELTS Academic Writing Task 2 – Test 4', 'A 40-minute timed IELTS Academic Writing Task 2 essay. AI-graded via essay_analyzer.', 'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_ACA_004');

UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_writing_academic_002.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Writing Test 2 (Timed)' AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;

UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_writing_academic_003.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Writing Test 3 (Timed)' AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;

UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_writing_academic_004.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_3Mo') AND lp.title = 'Writing Test 4' AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;

-- Verify: 6 tests rows and where the pieces point
SELECT code, duration_minutes FROM tests WHERE code REGEXP '^IELTS_PT_W[12]_ACA_00[234]$' ORDER BY code;
SELECT c.folder_name, lp.title, lp.file_path FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title REGEXP '^Writing Test [234]' ORDER BY c.folder_name, lp.title;
