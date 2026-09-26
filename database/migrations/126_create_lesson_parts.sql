-- ============================================================
-- Migration 126 -- lesson_parts: say what each piece of a class IS
--
-- A class row (lessons) holds one or two pieces of content in its title, joined with " + ".
-- Nothing said whether a piece is a lesson or a test, so the overview labelled every piece
-- "Class lesson" ("Reading Test 1" included) and students could not tell what they were opening.
-- kind: lesson | resource | practice_test | mock_test.
-- Instructor's rule (2026-09-26): anything that does not carry the word "Test" is a lesson;
-- "Mock Test N" is a mock; any other "... Test ..." is a practice test. Change a row's kind here
-- (or in the future admin screen) to make an exception -- the stored value beats the rule.
-- Seeded for IELTS_Aca_2Mo and IELTS_Aca_3Mo (looked up by folder + week + class-in-week, no ids);
-- every other course uses the title rule until it gets rows. Ambiguous on purpose left to the rule:
-- Class 1 "Orientation & Diagnostic Assessment" (a lesson by the rule, but it names an assessment).
-- IDEMPOTENT: CREATE TABLE IF NOT EXISTS; the seed deletes and re-inserts those two courses' rows.
-- ============================================================

-- Titles contain a real em dash: force UTF-8 for this session so it is stored as one character
-- whatever the client's default charset is (the first local run double-encoded it).
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_parts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT UNSIGNED NOT NULL,
    part_order TINYINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    kind ENUM('lesson','resource','practice_test','mock_test') NOT NULL DEFAULT 'lesson',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY lesson_parts_lesson_part (lesson_id, part_order),
    KEY lesson_parts_lesson (lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE lp FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id WHERE c.folder_name IN ('IELTS_Aca_2Mo','IELTS_Aca_3Mo');

INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Orientation & Diagnostic Assessment', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Writing Task Overview', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Trend Vocabulary & Paraphrasing', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Comparative Structures & TEE', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 1 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Listening Formats', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Map Description & Passive Voice', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Process Description & Passive Voice', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Task 1 Report Structuring', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock Test 1 — Full Timed (L, R, W, S)', 'mock_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock 1 Review', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Reading Time-Management', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 2 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Speaking Part 2 Drilling', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Speaking Part 3 Drilling', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Listening Fast-Audio Drills', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Complex Passage Strategy', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 3 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Final Self-Assessment', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock Test 2 (Final) — Full Timed (L, R, W, S)', 'mock_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_2Mo' AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Orientation & Diagnostic Assessment', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Writing Task Overview', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Trend Vocabulary & Paraphrasing', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 1', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Comparative Structures & TEE', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 1 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Listening Formats', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Map Description & Passive Voice', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Process Description & Passive Voice', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 2', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Task 1 Report Structuring', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock Test 1 — Full Timed (L, R, W, S)', 'mock_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock 1 Review', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Reading Time-Management', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 2 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Speaking Part 2 Drilling', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Speaking Part 3 Drilling', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Listening Fast-Audio Drills', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 3', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Complex Passage Strategy', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 3 (Timed)', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Error Review', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock Test 2 — Full Timed (L, R, W, S)', 'mock_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock 2 Review', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Strategy Adjustments', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Listening Test 4', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Advanced Listening', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Reading Test 4', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Advanced Reading', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Speaking Test 4', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Advanced Speaking', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Writing Test 4', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Final Writing Polish', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Consolidation — Targeted Weak-Area Drilling', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=11 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Mock Test 3 (Final Assessment) — Full Timed (L, R, W, S)', 'mock_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=12 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 1, 'Final Review', 'lesson' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=12 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind)
SELECT l.id, 2, 'Test-Day Coaching', 'practice_test' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.folder_name='IELTS_Aca_3Mo' AND m.module_order=12 AND l.lesson_order=2;

-- Verify: one row per piece; kinds
SELECT c.folder_name, lp.kind, COUNT(*) AS pieces FROM lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id GROUP BY c.folder_name, lp.kind ORDER BY 1,2;
