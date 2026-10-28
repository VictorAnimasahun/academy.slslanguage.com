-- ============================================================
-- Migration 176 -- CELPIP General Masterclass (3 Months): remove the duplicate copies of Weeks 1-2
--
-- Found 2026-10-28 (the Course Introduction said "Class 1 of 28" for a 24-class course). On live the course has
-- two module rows for Week 1 and two for Week 2 -- an older pair (54, 55: lessons 308-311) and the pair the rest of
-- the course follows (150, 151: lessons 392-395, then 152-161). The course page groups classes by week number, so
-- Weeks 1-2 showed four classes each, and every "Class N of M" counted 28.
--
-- For each week number of the course (found by folder, never by id) with more than one module, the NEWEST module
-- is kept. Each older class maps to the kept class at the same position (lesson_order). Then, before anything is
-- deleted:
--   lesson_progress        moved to the kept class; where the student already has a row there, that row keeps
--                          "completed" if either copy had it, and the old row goes
--   lesson_item_progress   moved to the kept class (an item the student also did on the kept copy stays once)
--   course_pacing_items    moved only if the kept class has none of the same title (no doubled assignments)
--   week_briefs / week_resources / week_vocab_words   moved only if the kept week has none of its own
-- Destructive steps (lesson_parts, lessons, modules of the old copies) come LAST, so a half-run can simply be run
-- again. Re-runnable: a second run finds no duplicates and changes nothing. MySQL 5.7-safe, no information_schema
-- (phpMyAdmin switches to it -- migration 175). The SELECTs at the end show the course's weeks and classes.
-- ============================================================

SET NAMES utf8mb4;

-- Which module stays for each week number: the newest.
DROP TABLE IF EXISTS m176_keep_module;
CREATE TABLE m176_keep_module (course_id int unsigned NOT NULL, module_order int NOT NULL, keep_id int unsigned NOT NULL, PRIMARY KEY (course_id, module_order));
INSERT INTO m176_keep_module (course_id, module_order, keep_id)
SELECT m.course_id, m.module_order, MAX(m.id)
  FROM modules m JOIN courses c ON c.id = m.course_id AND c.folder_name = 'CELPIP_Gen_3Mo'
 GROUP BY m.course_id, m.module_order
HAVING COUNT(*) > 1;

-- The old modules, and each old class -> the kept class in the same position.
DROP TABLE IF EXISTS m176_old_module;
CREATE TABLE m176_old_module (old_id int unsigned NOT NULL PRIMARY KEY, keep_id int unsigned NOT NULL);
INSERT INTO m176_old_module (old_id, keep_id)
SELECT m.id, k.keep_id FROM modules m
  JOIN m176_keep_module k ON k.course_id = m.course_id AND k.module_order = m.module_order
 WHERE m.id <> k.keep_id;

DROP TABLE IF EXISTS m176_old_lesson;
CREATE TABLE m176_old_lesson (old_id int unsigned NOT NULL PRIMARY KEY, keep_id int unsigned NULL);
INSERT INTO m176_old_lesson (old_id, keep_id)
SELECT o.id, (SELECT kl.id FROM lessons kl WHERE kl.module_id = om.keep_id AND kl.lesson_order = o.lesson_order ORDER BY kl.id LIMIT 1)
  FROM lessons o JOIN m176_old_module om ON om.old_id = o.module_id;

-- lesson_progress: carry "completed" onto an existing kept row, then move the rest.
UPDATE lesson_progress kp
  JOIN m176_old_lesson ol ON ol.keep_id = kp.lesson_id
  JOIN lesson_progress op ON op.lesson_id = ol.old_id AND op.student_id = kp.student_id
   SET kp.completed = 1, kp.completed_at = COALESCE(kp.completed_at, op.completed_at)
 WHERE op.completed = 1 AND (kp.completed IS NULL OR kp.completed = 0);
UPDATE IGNORE lesson_progress p JOIN m176_old_lesson ol ON ol.old_id = p.lesson_id
   SET p.lesson_id = ol.keep_id WHERE ol.keep_id IS NOT NULL;

-- lesson_item_progress: move (duplicates of an item already done on the kept class are skipped, then removed below).
UPDATE IGNORE lesson_item_progress p JOIN m176_old_lesson ol ON ol.old_id = p.lesson_id
   SET p.lesson_id = ol.keep_id WHERE ol.keep_id IS NOT NULL;

-- course_pacing_items: move only what the kept class doesn't already have (by title).
UPDATE course_pacing_items pi JOIN m176_old_lesson ol ON ol.old_id = pi.lesson_id
   SET pi.lesson_id = ol.keep_id
 WHERE ol.keep_id IS NOT NULL
   AND NOT EXISTS (SELECT 1 FROM (SELECT lesson_id, title FROM course_pacing_items) x WHERE x.lesson_id = ol.keep_id AND x.title = pi.title);

-- Week Brief content: move only where the kept week has none of its own.
UPDATE IGNORE week_briefs b JOIN m176_old_module om ON om.old_id = b.module_id SET b.module_id = om.keep_id;
UPDATE week_resources r JOIN m176_old_module om ON om.old_id = r.module_id
   SET r.module_id = om.keep_id
 WHERE NOT EXISTS (SELECT 1 FROM (SELECT module_id FROM week_resources) x WHERE x.module_id = om.keep_id);
UPDATE IGNORE week_vocab_words v JOIN m176_old_module om ON om.old_id = v.module_id SET v.module_id = om.keep_id;

-- Destructive, last: whatever still points at the old copies, then the copies themselves.
DELETE p FROM lesson_progress p JOIN m176_old_lesson ol ON ol.old_id = p.lesson_id;
DELETE p FROM lesson_item_progress p JOIN m176_old_lesson ol ON ol.old_id = p.lesson_id;
DELETE pi FROM course_pacing_items pi JOIN m176_old_lesson ol ON ol.old_id = pi.lesson_id;
DELETE b FROM week_briefs b JOIN m176_old_module om ON om.old_id = b.module_id;
DELETE r FROM week_resources r JOIN m176_old_module om ON om.old_id = r.module_id;
DELETE v FROM week_vocab_words v JOIN m176_old_module om ON om.old_id = v.module_id;
DELETE lp FROM lesson_parts lp JOIN m176_old_lesson ol ON ol.old_id = lp.lesson_id;
DELETE l FROM lessons l JOIN m176_old_lesson ol ON ol.old_id = l.id;
DELETE m FROM modules m JOIN m176_old_module om ON om.old_id = m.id;

DROP TABLE IF EXISTS m176_old_lesson;
DROP TABLE IF EXISTS m176_old_module;
DROP TABLE IF EXISTS m176_keep_module;

-- Check: 12 weeks, 24 classes, one module per week.
SELECT COUNT(DISTINCT m.id) AS modules, COUNT(DISTINCT m.module_order) AS weeks, COUNT(l.id) AS classes
  FROM courses c JOIN modules m ON m.course_id = c.id LEFT JOIN lessons l ON l.module_id = m.id
 WHERE c.folder_name = 'CELPIP_Gen_3Mo';
