-- ============================================================
-- Migration 126 -- lesson_parts: what each piece of a class IS, and the page that holds it
--
-- A class row (lessons) holds one or two pieces of content in its title, joined with " + ".
-- Nothing said whether a piece is a lesson or a test, so the overview labelled every piece
-- "Class lesson" ("Reading Test 1" included) and students could not tell what they were opening.
--
-- kind: lesson | resource | practice_test | mock_test. Instructor's rule (2026-09-26): anything that does
-- not carry the word "Test" is a lesson; "Mock Test N" is a mock; any other "... Test ..." is a practice
-- test. The stored value beats the rule, so an exception is a data edit.
--
-- file_path: the page that holds the piece, relative to the academy root. NULL = the class's own page
-- shows it (courses that already have real lesson files are left exactly as they are). Lesson pieces
-- that had no page get an EMPTY SHELL file (courses/<course>/lessons/classNN_pK_<slug>.php) -- develop
-- the lesson by putting HTML between the LESSON BODY markers in that file. Shells exist for the IELTS
-- Academic 1/2/3-Month and PTE 1/2/3-Month courses; Listening Formats points at its existing worksheet.
--
-- Seeded for EVERY course that has lessons (looked up by folder + week + class-in-week, no ids; where two
-- courses share a folder name -- CELPIP_Gen_1Mo has a hidden duplicate -- the visible one is used).
-- Class 1 of IELTS Academic 2/3-Month, "Orientation & Diagnostic Assessment", is a lesson by the rule
-- although it names an assessment -- left to the instructor.
-- IDEMPOTENT: CREATE TABLE IF NOT EXISTS; the seed deletes and re-inserts every row.
-- RUN ORDER WARNING: the seed below describes the courses AS THEY WERE when this file was written. Run it BEFORE 128, 129, 130 and 131.
-- Re-running it AFTER them would put back the old pieces (CELPIP 2-Month week 9 / Mock 1 Review, IELTS Academic 1-Month topics).
-- If it stops half-way, fix and re-run it, then continue with 128-131 in order; never run it again once 130/131 have run.
-- ============================================================

-- Titles contain a real em dash: force UTF-8 for this session so it is stored as one character
-- whatever the client's default charset is.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_parts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT UNSIGNED NOT NULL,
    part_order TINYINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    kind ENUM('lesson','resource','practice_test','mock_test') NOT NULL DEFAULT 'lesson',
    file_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY lesson_parts_lesson_part (lesson_id, part_order),
    KEY lesson_parts_lesson (lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM lesson_parts;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction & CELPIP Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — News Item & Conversation', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Correspondence & Diagram', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing — Email Task (CLB Bands)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Tasks 1-4 (Word Repeat to Short Answer)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Extended Passage & Graph Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Tasks 5-8 & Writing Survey Response', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Practice Mock Exam — All 4 Skills', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Foundational English Assessment', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mini Diagnostic — All 4 Skills', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Listening Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 1 Lesson (Correspondence)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 & 2 Structure Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Inference & Signal Words Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Clarity & Structure Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test (Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 2 Lesson (Schedules & Diagrams)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Argument Vocabulary Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Supporting Details Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Storytelling & Predictions Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test (Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 3 Lesson (Key Ideas)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Transitions & Collocations Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Speaker Attitude Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Persuasion & Comparison Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 1 — Full-Length, All 4 Sections', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock 1 Review & Band Estimate', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 2 — Final Assessment', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Performance Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Foundational English Assessment', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mini Diagnostic — All 4 Skills', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Listening Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 1 Lesson (Correspondence)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 & 2 Structure Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Inference & Signal Words Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 1)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Clarity & Structure Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test (Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 2 Lesson (Schedules & Diagrams)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Argument Vocabulary Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Supporting Details Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 2)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Storytelling & Predictions Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test (Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 3 Lesson (Key Ideas)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Reading Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Transitions & Collocations Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Speaking Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Speaker Attitude Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complete Writing Test (Practice Test 3)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Persuasion & Comparison Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 1 — Full-Length, All 4 Sections', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock 1 Review & Band Estimate', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test (Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Part 4 Lesson (Viewpoints)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test (Practice Test 4 — Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Self-Editing Strategy Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test (Practice Test 4 — Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Fast-Audio Drills Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test (Practice Test 4 — Coming Soon)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Full-Timed-Task Strategy Lesson', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 2 — Final Assessment', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Performance Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Speaking Simulation & Listening Sprints', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Test-Day Coaching', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='CELPIP_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction & IELTS Academic Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Academic Reading — Skimming, Scanning & Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Task 1 — Graphs, Charts & Diagrams', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Task 2 — Essay Types & Band Descriptors', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Completing Notes, Forms & Diagrams', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Parts 1 & 2 (Introduction & Long Turn)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Part 3 & Advanced Fluency Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 1 — All 4 Skills', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Orientation & Diagnostic Assessment', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class01_p1_orientation-diagnostic-assessment.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task Overview', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class02_p2_writing-task-overview.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Trend Vocabulary & Paraphrasing', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class03_p2_trend-vocabulary-paraphrasing.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Comparative Structures & TEE', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class04_p2_comparative-structures-tee.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 1 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Formats', 'lesson', 'resources/practice_tests/ielts_listening_formats_sample.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Map Description & Passive Voice', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class06_p2_map-description-passive-voice.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Process Description & Passive Voice', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class07_p2_process-description-passive-voice.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Task 1 Report Structuring', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class08_p2_task-1-report-structuring.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 1 — Full Timed (L, R, W, S)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock 1 Review', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class10_p1_mock-1-review.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Time-Management', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class10_p2_reading-time-management.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 2 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 2 Drilling', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class11_p2_speaking-part-2-drilling.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 3 Drilling', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class12_p2_speaking-part-3-drilling.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Fast-Audio Drills', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class13_p2_listening-fast-audio-drills.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Complex Passage Strategy', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class14_p2_complex-passage-strategy.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 3 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Final Self-Assessment', 'lesson', 'courses/IELTS_Aca_2Mo/lessons/class15_p2_final-self-assessment.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 2 (Final) — Full Timed (L, R, W, S)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Orientation & Diagnostic Assessment', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class01_p1_orientation-diagnostic-assessment.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task Overview', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class02_p2_writing-task-overview.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Trend Vocabulary & Paraphrasing', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class03_p2_trend-vocabulary-paraphrasing.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Comparative Structures & TEE', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class04_p2_comparative-structures-tee.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 1 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Formats', 'lesson', 'resources/practice_tests/ielts_listening_formats_sample.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Map Description & Passive Voice', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class06_p2_map-description-passive-voice.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Process Description & Passive Voice', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class07_p2_process-description-passive-voice.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Task 1 Report Structuring', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class08_p2_task-1-report-structuring.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 1 — Full Timed (L, R, W, S)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock 1 Review', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class10_p1_mock-1-review.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading Time-Management', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class10_p2_reading-time-management.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 2 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 2 Drilling', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class11_p2_speaking-part-2-drilling.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 3 Drilling', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class12_p2_speaking-part-3-drilling.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening Fast-Audio Drills', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class13_p2_listening-fast-audio-drills.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 3', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Complex Passage Strategy', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class14_p2_complex-passage-strategy.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 3 (Timed)', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Error Review', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class15_p2_error-review.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 2 — Full Timed (L, R, W, S)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock 2 Review', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class17_p1_mock-2-review.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Strategy Adjustments', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class17_p2_strategy-adjustments.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test 4', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Advanced Listening', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class18_p2_advanced-listening.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test 4', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Advanced Reading', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class19_p2_advanced-reading.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test 4', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Advanced Speaking', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class20_p2_advanced-speaking.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test 4', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Final Writing Polish', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class21_p2_final-writing-polish.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Consolidation — Targeted Weak-Area Drilling', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class22_p1_consolidation-targeted-weak-area-drillin.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Test 3 (Final Assessment) — Full Timed (L, R, W, S)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Review', 'lesson', 'courses/IELTS_Aca_3Mo/lessons/class24_p1_final-review.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Test-Day Coaching', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Welcome to IELTS Academic Crash Course', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Understanding IELTS Test Format', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Band Scores Explained', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Study Strategies & Time Management', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Knowledge Check Quiz', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Section Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Section 1 & 2: Social Contexts', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Section 3 & 4: Academic Contexts', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Note-Taking Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Practice Test 1 - Full Listening', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Practice Test 2 - Full Listening', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Section Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Skimming & Scanning Techniques', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'True/False/Not Given & Yes/No/Not Given', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Matching & Multiple Choice Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Completion Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Practice Passage 1', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Practice Passage 2', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=7;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Task 1 Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Line Graphs & Bar Charts', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Pie Charts & Tables', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Process Diagrams', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Maps & Multiple Charts', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Task 1 Practice Session', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Task 2 Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Essay Structure Fundamentals', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Opinion Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Discussion Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Problem-Solution Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advantage-Disadvantage Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Cohesive Devices & Academic Vocabulary', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=7;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Task 2 Practice Session', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=8;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Test Overview', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Part 1: Introduction & Familiar Topics', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Part 1 Practice Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Part 2: Long Turn Structure', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Part 2: Common Topics', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Extending Your Answers', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Speaking Test: Parts 1 & 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=7;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Part 3: Abstract Discussion', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Expressing & Justifying Opinions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Comparing & Contrasting', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speculating & Predicting', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Vocabulary for Part 3', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Mock Speaking Test 1', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Mock Speaking Test 2', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=7;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Pronunciation & Fluency Tips', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=8;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Test Day Preparation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Listening Test - Simulation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Test - Answer Review', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Reading Test - Simulation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Test - Answer Review', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Error Pattern Analysis', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Writing Test - Simulation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Test - Model Answers', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Speaking Test - Simulation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=3;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Common Mistakes Across All Sections', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=4;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Last-Minute Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=5;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Time Management Mastery', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=6;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Test Day Checklist', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=7;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Q&A and Next Steps', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Crash' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=8;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Welcome, Program Structure & the IELTS Band System', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Diagnostic Self-Assessment', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Anatomy of the Listening Paper & Prediction Strategies', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening Question Types & Timed Practice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Skimming, Scanning & True/False/Not Given Logic', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Matching Tasks, Summary Completion & Timed Practice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Academic Word List, Collocations & Paraphrasing', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Complex Grammar, Cohesive Devices & Practice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction to IELTS Academic Writing Task 1', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Describing Trends in Graphs & Charts', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 3, 'Comparing Data and Making Comparisons', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Describing Maps (Before and After Changes)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Describing Processes (Flowcharts & Diagrams)', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 3, 'Structuring Task 1 Reports', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 4, 'Task 1 Practice & Feedback', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction to IELTS Academic Writing Task 2', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'How to Write an Effective Introduction', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 3, 'Structuring Body Paragraphs', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 4, 'Developing Strong Arguments', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing a Balanced Conclusion', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Common Grammar and Vocabulary Mistakes', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 3, 'Full Task 2 Essay Practice & Feedback', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 1 & Part 2 — Fluency and Cue Card Strategy', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 3 & Full Mock Speaking Simulation', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Timed Mock — Listening, Reading & Writing', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Error Pattern Analysis, Test-Day Strategy & Final Tips', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Course Orientation & IELTS General Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Diagnostic Mini Mock & Band Descriptor Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Form Completion & Gap-fill', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking — Introduction & Fluency', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Skimming & Scanning', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Vocabulary & Registers', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Multiple Choice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 1 — Common Topics & Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — True/False/Not Given', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Formal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Map Listening', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 2 — Topic Development', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 1 — Full Timed Exam (End of Month 1)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Course Orientation & IELTS General Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Diagnostic Mini Mock & Band Descriptor Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Form Completion & Gap-fill', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking — Introduction & Fluency', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Skimming & Scanning', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Vocabulary & Registers', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Multiple Choice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 1 — Common Topics & Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — True/False/Not Given', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Formal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Map Listening', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 2 — Topic Development', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 1 — Full Timed Exam (End of Month 1)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Matching Paragraphs & Headings', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening — Plan Labelling Parts 3 & 4', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 2 — Long Turn', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Informal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Multiple Choice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Semi-Formal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Note Completion, Table & Flowchart', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Form & Gap Fill Flow', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 3 — A Complete Guide', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Critical Thinking & Extended Responses', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Sentence Completion', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 2 — Effective Introduction', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Distractors & Synonyms', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Cohesive Devices in Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 2 — Full Timed Exam (End of Month 2)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Course Orientation & IELTS General Overview', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Diagnostic Mini Mock & Band Descriptor Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Form Completion & Gap-fill', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking — Introduction & Fluency', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Skimming & Scanning', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Vocabulary & Registers', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Multiple Choice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 1 — Common Topics & Questions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — True/False/Not Given', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Formal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Map Listening', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Speaking Part 2 — Topic Development', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 1 — Full Timed Exam (End of Month 1)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Matching Paragraphs & Headings', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Listening — Plan Labelling Parts 3 & 4', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 2 — Long Turn', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Informal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Multiple Choice', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 1 — Semi-Formal Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Note Completion, Table & Flowchart', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Form & Gap Fill Flow', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 3 — A Complete Guide', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Critical Thinking & Extended Responses', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Sentence Completion', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Writing Task 2 — Effective Introduction', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Distractors & Synonyms', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Cohesive Devices in Letters', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 2 — Full Timed Exam (End of Month 2)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Part 3 — Opinions & Abstract Topics', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Reading — Summary/Note/Table Completion', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Task 2 — Body Paragraphs', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Opinion Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Task 2 — Balanced Conclusions', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 2, 'Bonus: Discussion Essays', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing Task 2 Masterclass — All Five Essay Types', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading Mastery — All Question Types Rapid Review', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking Mastery — Parts 1, 2 & 3 End-to-End', 'lesson', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Final Preparation — Listening Masterclass & Test-Day Strategy', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'MOCK TEST 3 — Final Full Exam Simulation (End of Month 3)', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Gen_Mst' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction & PTE Academic Overview', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class01_p1_introduction-pte-academic-overview.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Read Aloud & Repeat Sentence', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class02_p1_speaking-read-aloud-repeat-sentence.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Describe Image & Re-tell Lecture', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class03_p1_speaking-describe-image-re-tell-lecture.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing — Summarize Written Text & Essay', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class04_p1_writing-summarize-written-text-essay.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Multiple Choice, Re-order & Fill in Blanks', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class05_p1_reading-multiple-choice-re-order-fill-in.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Summarize Spoken Text & MCQ', 'lesson', 'courses/PTE_Gen_1Mo/lessons/class06_p1_listening-summarize-spoken-text-mcq.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Scoring Strategies & Test-Day Preparation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 1 — Full PTE Simulation', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_1Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction & PTE Academic Overview', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class01_p1_introduction-pte-academic-overview.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Read Aloud & Repeat Sentence', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class02_p1_speaking-read-aloud-repeat-sentence.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Describe Image & Re-tell Lecture', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class03_p1_speaking-describe-image-re-tell-lecture.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing — Summarize Written Text & Essay', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class04_p1_writing-summarize-written-text-essay.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Multiple Choice, Re-order & Fill in Blanks', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class05_p1_reading-multiple-choice-re-order-fill-in.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Summarize Spoken Text & MCQ', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class06_p1_listening-summarize-spoken-text-mcq.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Scoring Strategies & Test-Day Preparation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 1 — Full PTE Simulation', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class09_p1_advanced-speaking-fluency-pronunciation.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Writing — Essay Coherence & Discourse Markers', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class10_p1_advanced-writing-essay-coherence-discour.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Reading — Speed & Complex Item Types', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class11_p1_advanced-reading-speed-complex-item-type.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Listening — Fill Blanks, Dictation & Highlight', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class12_p1_advanced-listening-fill-blanks-dictation.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Timed Practice — Speaking & Writing', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class13_p1_timed-practice-speaking-writing.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Timed Practice — Reading & Listening', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class14_p1_timed-practice-reading-listening.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Score Maximisation — Common Errors & Fixes', 'lesson', 'courses/PTE_Gen_2Mo/lessons/class15_p1_ai-score-maximisation-common-errors-fixe.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 2 — Full Timed Simulation', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Introduction & PTE Academic Overview', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class01_p1_introduction-pte-academic-overview.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Read Aloud & Repeat Sentence', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class02_p1_speaking-read-aloud-repeat-sentence.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Speaking — Describe Image & Re-tell Lecture', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class03_p1_speaking-describe-image-re-tell-lecture.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Writing — Summarize Written Text & Essay', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class04_p1_writing-summarize-written-text-essay.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Reading — Multiple Choice, Re-order & Fill in Blanks', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class05_p1_reading-multiple-choice-re-order-fill-in.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Listening — Summarize Spoken Text & MCQ', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class06_p1_listening-summarize-spoken-text-mcq.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Scoring Strategies & Test-Day Preparation', 'practice_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 1 — Full PTE Simulation', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class09_p1_advanced-speaking-fluency-pronunciation.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Writing — Essay Coherence & Discourse Markers', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class10_p1_advanced-writing-essay-coherence-discour.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=5 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Reading — Speed & Complex Item Types', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class11_p1_advanced-reading-speed-complex-item-type.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Advanced Listening — Fill Blanks, Dictation & Highlight', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class12_p1_advanced-listening-fill-blanks-dictation.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=6 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Timed Practice — Speaking & Writing', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class13_p1_timed-practice-speaking-writing.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Timed Practice — Reading & Listening', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class14_p1_timed-practice-reading-listening.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Score Maximisation — Common Errors & Fixes', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class15_p1_ai-score-maximisation-common-errors-fixe.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 2 — Full Timed Simulation', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=8 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mastery Speaking — Perfect Pronunciation Patterns', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class17_p1_mastery-speaking-perfect-pronunciation-p.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mastery Writing — Band 90 Essay Structures', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class18_p1_mastery-writing-band-90-essay-structures.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=9 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mastery Reading — Accuracy Under Pressure', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class19_p1_mastery-reading-accuracy-under-pressure.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mastery Listening — Write From Dictation & All Types', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class20_p1_mastery-listening-write-from-dictation-a.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Exam Simulation Day 1', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class21_p1_full-exam-simulation-day-1.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Full Exam Simulation Day 2', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class22_p1_full-exam-simulation-day-2.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=11 AND l.lesson_order=2;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'AI Score Review & Final Targeted Practice', 'lesson', 'courses/PTE_Gen_3Mo/lessons/class23_p1_ai-score-review-final-targeted-practice.php' FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=1;
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, file_path)
SELECT l.id, 1, 'Mock Exam 3 — Final Full Timed Exam', 'mock_test', NULL FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
WHERE c.id=(SELECT id FROM courses WHERE folder_name='PTE_Gen_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=12 AND l.lesson_order=2;

-- Verify: kinds per course, and how many pieces have their own page
SELECT c.folder_name, lp.kind, COUNT(*) AS pieces, SUM(lp.file_path IS NOT NULL) AS with_own_page FROM lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id GROUP BY c.folder_name, lp.kind ORDER BY 1,2;
