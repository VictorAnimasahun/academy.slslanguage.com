-- ============================================================
-- Migration 151 -- Week 1: every test is its own item in its class, with one name
--
-- Founder, 2026-10-19: a class is one day made of separate items (lessons, quizzes, exercises,
-- tests). A test is never folded into a lesson's line, and tests are taken on a computer only
-- (the app only offers them on the web). Two different tests open the IELTS/CELPIP courses:
--   * Foundational English Assessment -- general English level (do they need English lessons
--     before exam prep?). The placement test in mini_tests/english-placement-test.
--   * <Exam> Diagnostic Test -- current exam level. "Mini Mock" is only its informal name.
--
--   CELPIP 2-Month (13) / 3-Month (14), Class 1: was one item "Foundational English Assessment"
--     whose page was really the course introduction (and held no test). Now:
--       1. Course Introduction (lesson)  2. Foundational English Assessment (test)
--   CELPIP 2-Month / 3-Month, Class 2: "Mini Diagnostic -- All 4 Skills", filed as a lesson, is a
--     test. Now: CELPIP Diagnostic Test (mock_test).
--   IELTS General 3/1/2-Month (9, 10, 11), Class 2: "Diagnostic Mini Mock & Band Descriptor Review"
--     was one lesson; the diagnostic it talks about wasn't linked at all. Now:
--       1. IELTS General Training Diagnostic Test (test)  2. Band Descriptor Review (lesson)
--   IELTS Academic 2/3/1-Month (16, 17, 23), Class 1: "Orientation & Diagnostic Assessment" was one
--     lesson with the diagnostic linked inside it. Now:
--       1. Orientation (lesson)  2. IELTS Academic Diagnostic Test (test)
--
-- Classes are found by course + week + class order, never by row id (ids differ on live).
-- A class title holds its items joined with " + " (includes/lesson_title.php shows each on its
-- own line). Idempotent and MySQL 5.7-safe: plain UPDATE/INSERT ... SELECT, no derived tables,
-- every INSERT guarded by NOT EXISTS on the (lesson_id, part_order) unique key.
-- ============================================================

SET NAMES utf8mb4;

-- ---------- CELPIP 2-Month / 3-Month, Class 1 ----------
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Course Introduction + Foundational English Assessment'
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.title = 'Course Introduction', p.kind = 'lesson', p.status = 'ready',
       p.file_path = 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php'
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 1 AND p.part_order = 1;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Foundational English Assessment', 'practice_test', 'ready', 'mini_tests/english-placement-test/BELStart.html'
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.part_order = 2);

-- ---------- CELPIP 2-Month / 3-Month, Class 2 ----------
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'CELPIP Diagnostic Test'
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.title = 'CELPIP Diagnostic Test', p.kind = 'mock_test', p.status = 'ready',
       p.file_path = 'courses/CELPIP_intro/celpip_mini_mock.php'
 WHERE l.course_id IN (13, 14) AND m.module_order = 1 AND l.lesson_order = 2 AND p.part_order = 1;

-- ---------- IELTS General 3/1/2-Month, Class 2 ----------
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Class 2: IELTS General Training Diagnostic Test + Band Descriptor Review'
 WHERE l.course_id IN (9, 10, 11) AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.title = 'IELTS General Training Diagnostic Test', p.kind = 'mock_test', p.status = 'ready',
       p.file_path = 'resources/mock_tests/ielts_gt_diagnostic.php'
 WHERE l.course_id IN (9, 10, 11) AND m.module_order = 1 AND l.lesson_order = 2 AND p.part_order = 1;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'Band Descriptor Review', 'lesson', 'ready', 'courses/IELTS_Gen/lessons/class02.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (9, 10, 11) AND m.module_order = 1 AND l.lesson_order = 2
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.part_order = 2);

-- ---------- IELTS Academic 2/3/1-Month, Class 1 ----------
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Orientation + IELTS Academic Diagnostic Test'
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.title = 'Orientation', p.kind = 'lesson', p.status = 'ready'
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1 AND p.part_order = 1;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, 2, 'IELTS Academic Diagnostic Test', 'mock_test', 'ready', 'resources/mock_tests/ielts_aca_diagnostic.php'
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (16, 17, 23) AND m.module_order = 1 AND l.lesson_order = 1
   AND NOT EXISTS (SELECT 1 FROM lesson_parts x WHERE x.lesson_id = l.id AND x.part_order = 2);

-- ---------- CELPIP 1-Month (12), Class 1 ----------
-- It opened the FREE intro course's "Module 0" page, whose buttons led into the free course. It now
-- opens the CELPIP course introduction, which reads its course (name, classes, timetable) from the DB.
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Course Introduction', l.file_path = 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php'
 WHERE l.course_id = 12 AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
   SET p.title = 'Course Introduction', p.kind = 'lesson', p.status = 'ready',
       p.file_path = 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php'
 WHERE l.course_id = 12 AND m.module_order = 1 AND l.lesson_order = 1 AND p.part_order = 1;

-- ---------- CELPIP 3-Month (14), Week 12 Class 2 ----------
-- Holds two items (Test-Day Coaching, Mock Test 3) but its title named only the first.
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = 'Test-Day Coaching + Mock Test 3 — Final Assessment'
 WHERE l.course_id = 14 AND m.module_order = 12 AND l.lesson_order = 2 AND l.title = 'Test-Day Coaching';

-- ---------- CELPIP class days (2-Month and 3-Month, Weeks 2-7 and 9-10) ----------
-- Each class day already lists its test and its lesson as separate items, but neither item had a page
-- of its own, so both opened the combined class_day.php page (test inside the lesson). class_day.php now
-- has ?part=test and ?part=lesson views; each item points at its own.
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.file_path = CONCAT(l.file_path, '&part=test')
 WHERE l.file_path LIKE 'courses/CELPIP_Gen/lessons/class_day.php?slot=%'
   AND p.kind IN ('practice_test', 'mock_test') AND (p.file_path IS NULL OR p.file_path = '');

UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.file_path = CONCAT(l.file_path, '&part=lesson')
 WHERE l.file_path LIKE 'courses/CELPIP_Gen/lessons/class_day.php?slot=%'
   AND p.kind IN ('lesson', 'resource') AND (p.file_path IS NULL OR p.file_path = '');
