-- ============================================================
-- Migration 153 -- Foundational English Assessment opens every course; IELTS General's existing
-- quizzes and practice tests become their own items
--
-- Needs 152 (content types). Founder, 2026-10-19:
--  * "Should every IELTS, PTE and 1-Month course start with [the Foundational English Assessment]?"
--    -- "Yes nau." It goes in Class 1 of every paid course, after that class's opening lesson.
--  * A class is a day of separate items; a test (or a quiz) is never folded into a lesson's page.
--    IELTS General Classes 1-7 kept their quiz and practice test INSIDE the lesson page. They now
--    get their own lines. Only quizzes/tests that actually exist get a line -- no placeholders
--    (most courses have no quizzes yet; their lines come with the content).
--
-- Course 9 (IELTS General 3-Month) also attaches those quizzes/tests through course_pacing_items
-- (which drives due dates). Their titles are renamed to the same clean names, and the course page
-- now skips a scheduled entry that the class already lists (includes/course_progress_path.php).
--
-- Classes are found by course + week + class order, never by row id. Re-runnable and MySQL
-- 5.7-safe: INSERT ... SELECT guarded by NOT EXISTS on (lesson_id, title); titles only appended
-- when missing.
-- ============================================================

SET NAMES utf8mb4;

-- ---------- 1. Foundational English Assessment in Class 1 ----------
-- IELTS Academic 2/3/1-Month: Orientation, then the assessment, then the IELTS Academic Diagnostic Test.
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.part_order = 3
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1
   AND p.title = 'IELTS Academic Diagnostic Test' AND p.part_order = 2;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Foundational English Assessment', 'placement_test', 'ready', 'mini_tests/english-placement-test/BELStart.html'
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.part_order = 2);

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Orientation + Foundational English Assessment + IELTS Academic Diagnostic Test'
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1;

-- Every other paid course whose Class 1 doesn't have it yet (IELTS General 3/1/2-Month, CELPIP
-- 1-Month, PTE 1/2/3-Month): added after the class's existing items.
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, (SELECT COUNT(*) FROM lesson_parts c WHERE c.lesson_id = l.id) + 1,
       'Foundational English Assessment', 'placement_test', 'ready', 'mini_tests/english-placement-test/BELStart.html'
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (9, 10, 11, 12, 18, 20, 21) AND m.module_order = 1 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = 'Foundational English Assessment');

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = CONCAT(l.title, ' + Foundational English Assessment')
 WHERE l.course_id IN (9, 10, 11, 12, 18, 20, 21) AND m.module_order = 1 AND l.lesson_order = 1
   AND l.title NOT LIKE '%Foundational English Assessment%';

-- ---------- 2. IELTS General 3/1/2-Month: existing quizzes and practice tests as items ----------
-- (class, kind, title, file) -- each class page linked these from inside the lesson.
CREATE TEMPORARY TABLE tmp153 (
    class_no TINYINT UNSIGNED NOT NULL,
    seq TINYINT UNSIGNED NOT NULL,
    kind VARCHAR(32) NOT NULL,
    title VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tmp153 VALUES
  (1, 1, 'quiz',          'Class 1 Quiz — IELTS Format Knowledge',             'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C1_QUIZ'),
  (2, 1, 'quiz',          'Class 2 Quiz — Band Descriptor Matching',           'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C2_QUIZ'),
  (3, 1, 'practice_test', 'Practice Test Set 1 — Listening',                   'resources/practice_tests/ielts_listening_001.php'),
  (3, 2, 'quiz',          'Class 3 Quiz — Gap-Fill Prediction Strategies',     'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C3_QUIZ'),
  (4, 1, 'practice_test', 'Practice Test Set 1 — Reading',                     'resources/practice_tests/ielts_reading_001.php'),
  (4, 2, 'quiz',          'Class 4 Quiz — Skimming vs Scanning Decision Drill','resources/quizzes/class_quiz.php?test_code=IELTS_GM_C4_QUIZ'),
  (5, 1, 'practice_test', 'Practice Test Set 1 — Writing Task 1',              'resources/practice_tests/ielts_writing_t1_001.php'),
  (5, 2, 'quiz',          'Class 5 Quiz — MCQ Elimination Drill',              'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C5_QUIZ'),
  (6, 1, 'practice_test', 'Practice Test Set 1 — Speaking',                    'resources/practice_tests/ielts_speaking_001.php'),
  (6, 2, 'quiz',          'Class 6 Quiz — TFNG vs YNNG Decision Drill',        'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C6_QUIZ'),
  (7, 1, 'practice_test', 'Practice Test Set 2 — Listening',                   'resources/practice_tests/ielts_listening_002.php'),
  (7, 2, 'quiz',          'Class 7 Quiz — Map Orientation Drill',              'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C7_QUIZ');

-- Class number n = (week - 1) * 2 + class order. (lessons.title is utf8mb4_general_ci, the rest
-- utf8mb4_unicode_ci, hence the explicit COLLATE where the two meet.)
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, (SELECT COUNT(*) FROM lesson_parts c WHERE c.lesson_id = l.id) + 1, t.title, t.kind, 'ready', t.file_path
  FROM tmp153 t
  JOIN lessons l ON l.course_id IN (9, 10, 11)
  JOIN modules m ON m.id = l.module_id AND (m.module_order - 1) * 2 + l.lesson_order = t.class_no
 WHERE t.seq = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = t.title);

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, (SELECT COUNT(*) FROM lesson_parts c WHERE c.lesson_id = l.id) + 1, t.title, t.kind, 'ready', t.file_path
  FROM tmp153 t
  JOIN lessons l ON l.course_id IN (9, 10, 11)
  JOIN modules m ON m.id = l.module_id AND (m.module_order - 1) * 2 + l.lesson_order = t.class_no
 WHERE t.seq = 2
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.title = t.title);

UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN tmp153 t ON (m.module_order - 1) * 2 + l.lesson_order = t.class_no
   SET l.title = CONCAT(l.title, ' + ', t.title COLLATE utf8mb4_general_ci)
 WHERE l.course_id IN (9, 10, 11) AND t.seq = 1 AND l.title NOT LIKE CONCAT('%', t.title COLLATE utf8mb4_general_ci, '%');

UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN tmp153 t ON (m.module_order - 1) * 2 + l.lesson_order = t.class_no
   SET l.title = CONCAT(l.title, ' + ', t.title COLLATE utf8mb4_general_ci)
 WHERE l.course_id IN (9, 10, 11) AND t.seq = 2 AND l.title NOT LIKE CONCAT('%', t.title COLLATE utf8mb4_general_ci, '%');

-- The 3-Month course's schedule entries get the same names, so the course page lists each once.
UPDATE course_pacing_items ci JOIN tmp153 t
    ON (ci.item_type = 'quiz' AND t.kind = 'quiz' AND ci.test_code = SUBSTRING_INDEX(t.file_path, 'test_code=', -1))
    OR (ci.item_type = 'practice_test' AND t.kind = 'practice_test' AND ci.test_code = CASE t.file_path
          WHEN 'resources/practice_tests/ielts_listening_001.php'  THEN 'IELTS_PT_L_001'
          WHEN 'resources/practice_tests/ielts_reading_001.php'    THEN 'IELTS_PT_R_001'
          WHEN 'resources/practice_tests/ielts_writing_t1_001.php' THEN 'IELTS_PT_W1_001'
          WHEN 'resources/practice_tests/ielts_speaking_001.php'   THEN 'IELTS_PT_S_001'
          WHEN 'resources/practice_tests/ielts_listening_002.php'  THEN 'IELTS_PT_L_002' END)
   SET ci.title = t.title
 WHERE ci.course_id = 9;

DROP TEMPORARY TABLE tmp153;
