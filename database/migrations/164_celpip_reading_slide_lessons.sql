-- ============================================================
-- Migration 164 -- the instructor's CELPIP Reading slide decks are the Reading lessons
--
-- Founder, 2026-10-21: "I sent you a bunch of PowerPoint presentations for four CELPIP reading classes and I can't
-- find them in the course." The Part 1-4 decks were imported on 2026-09-08 (protected_resources/celpip_reading_pt1-4)
-- but no Masterclass item opened them: the "Reading Part N Lesson" items opened ~150-word text pages, and the decks
-- were only reachable from stray Previous/Next buttons on Crash Course writing/speaking pages. The Overview and
-- Paraphrases & Summaries decks were never imported. Now (decks read slide by slide, then placed by content):
--   Overview (format, timing, question types, score table)  -> first Reading class, before Part 1
--   Part 1-4 (format, strategy, a real passage answered step by step) -> each "Reading Part N" lesson
--   Identifying Paraphrases (questions never repeat the passage's words) -> with Part 1
--   Identifying Summaries (reduce to the key ideas, then reword) -> with Part 3 (matching statements to paragraphs)
-- CELPIP 2-Month had no Part 4 lesson: it now follows Part 3 in Class 12. The Crash Course's two Reading classes
-- (Class 3, Class 6) take Parts 1-2 and Parts 3-4. Every CELPIP course can open every deck (includes/
-- protected_reading_resources.php); it used to be a subscription tier, which shut Crash Course students out of 3-4.
--
-- Items matched by course, class and title; inserts only add what isn't there, so re-running is safe.
-- One transaction. MySQL 5.7-safe (each temporary table used once per statement).
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

CREATE TEMPORARY TABLE m164_cls AS
SELECT l.id AS lesson_id, l.course_id, (m.module_order - 1) * 2 + l.lesson_order AS cls
  FROM lessons l JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (12, 13, 14);

-- Deck lessons to add (step 2).
CREATE TEMPORARY TABLE m164_new (course_id INT, cls INT, title VARCHAR(255), deck VARCHAR(64), po INT)
  DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
INSERT INTO m164_new VALUES
 (12, 3, 'CELPIP Reading Test — Overview',        'celpip_reading_overview',    1),
 (12, 3, 'Reading Part 2 — Reading to Apply a Diagram', 'celpip_reading_pt2',   3),
 (12, 3, 'Identifying Paraphrases',               'celpip_reading_paraphrases', 4),
 (12, 6, 'Reading Part 4 — Reading for Viewpoints', 'celpip_reading_pt4',       2),
 (12, 6, 'Identifying Summaries',                 'celpip_reading_summaries',   3),
 (13, 3, 'CELPIP Reading Test — Overview',        'celpip_reading_overview',    2),
 (13, 3, 'Identifying Paraphrases',               'celpip_reading_paraphrases', 4),
 (13, 12, 'Identifying Summaries',                'celpip_reading_summaries',   3),
 (13, 12, 'Reading Part 4 — Reading for Viewpoints', 'celpip_reading_pt4',      4),
 (14, 3, 'CELPIP Reading Test — Overview',        'celpip_reading_overview',    2),
 (14, 3, 'Identifying Paraphrases',               'celpip_reading_paraphrases', 4),
 (14, 13, 'Identifying Summaries',                'celpip_reading_summaries',   3);

-- ---------- 1. existing items open their deck ----------
-- (course, class, old title) -> new title, deck, new place in the class
CREATE TEMPORARY TABLE m164_ren (course_id INT, cls INT, old_title VARCHAR(255), new_title VARCHAR(255), deck VARCHAR(64), po INT)
  DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
INSERT INTO m164_ren VALUES
 (12, 3, 'Reading — Correspondence & Diagram',           'Reading Part 1 — Reading Correspondence',      'celpip_reading_pt1', 2),
 (12, 6, 'Reading — Extended Passage & Graph Strategies', 'Reading Part 3 — Reading for Information',     'celpip_reading_pt3', 1),
 (13, 3, 'Reading Part 1 Lesson (Correspondence)',       'Reading Part 1 — Reading Correspondence',      'celpip_reading_pt1', 3),
 (13, 7, 'Reading Part 2 Lesson (Schedules & Diagrams)', 'Reading Part 2 — Reading to Apply a Diagram',  'celpip_reading_pt2', 2),
 (13, 12, 'Reading Part 3 Lesson (Key Ideas)',           'Reading Part 3 — Reading for Information',     'celpip_reading_pt3', 2),
 (14, 3, 'Reading Part 1 Lesson (Correspondence)',       'Reading Part 1 — Reading Correspondence',      'celpip_reading_pt1', 3),
 (14, 7, 'Reading Part 2 Lesson (Schedules & Diagrams)', 'Reading Part 2 — Reading to Apply a Diagram',  'celpip_reading_pt2', 2),
 (14, 13, 'Reading Part 3 Lesson (Key Ideas)',           'Reading Part 3 — Reading for Information',     'celpip_reading_pt3', 2),
 (14, 19, 'Reading Part 4 Lesson (Viewpoints)',          'Reading Part 4 — Reading for Viewpoints',      'celpip_reading_pt4', 2);

-- Park the affected classes' items out of the way (part_order is unique per class), then place them.
UPDATE lesson_parts p JOIN m164_cls c ON c.lesson_id = p.lesson_id
   SET p.part_order = p.part_order + 50
 WHERE (c.course_id, c.cls) IN ((12, 3), (12, 6), (13, 3), (13, 12), (14, 3), (14, 13))
   AND p.part_order < 50;

UPDATE lesson_parts p
  JOIN m164_cls c ON c.lesson_id = p.lesson_id
  JOIN m164_ren r ON r.course_id = c.course_id AND r.cls = c.cls AND (r.old_title = p.title OR r.new_title = p.title)
   SET p.title = r.new_title,
       p.file_path = CONCAT('resources/protected_viewer/celpip_reading.php?part=', r.deck),
       p.part_order = r.po;

-- The CELPIP Crash Course's Reading Practice Test 1 comes after Class 6's three lessons.
UPDATE lesson_parts p JOIN m164_cls c ON c.lesson_id = p.lesson_id
   SET p.part_order = 4
 WHERE c.course_id = 12 AND c.cls = 6 AND p.title = 'Complete Reading Test (Practice Test 1)';
-- Masterclass Class 3 keeps its Listening test first; 2-Month Class 12 keeps its Listening test first.
UPDATE lesson_parts p JOIN m164_cls c ON c.lesson_id = p.lesson_id
   SET p.part_order = 1
 WHERE ((c.course_id IN (13, 14) AND c.cls = 3) OR (c.course_id = 13 AND c.cls = 12) OR (c.course_id = 14 AND c.cls = 13))
   AND p.kind = 'practice_test' AND p.part_order > 50;

-- ---------- 2. new deck lessons ----------
UPDATE lesson_parts p
  JOIN m164_cls c ON c.lesson_id = p.lesson_id
  JOIN m164_new n ON n.course_id = c.course_id AND n.cls = c.cls AND n.title = p.title
   SET p.part_order = n.po;

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT c.lesson_id, n.po, n.title, 'lesson', 'ready', CONCAT('resources/protected_viewer/celpip_reading.php?part=', n.deck)
  FROM m164_new n
  JOIN m164_cls c ON c.course_id = n.course_id AND c.cls = n.cls
 WHERE NOT EXISTS (SELECT 1 FROM lesson_parts p WHERE p.lesson_id = c.lesson_id AND p.title = n.title);

-- ---------- 3. class titles = their items; a class opens at its first item; week titles ----------
UPDATE lessons l
  JOIN (SELECT lesson_id, GROUP_CONCAT(title ORDER BY part_order SEPARATOR ' + ') AS t,
               SUBSTRING_INDEX(GROUP_CONCAT(COALESCE(file_path, '') ORDER BY part_order SEPARATOR '\n'), '\n', 1) AS first_path
          FROM lesson_parts GROUP BY lesson_id) x ON x.lesson_id = l.id
  JOIN m164_cls c ON c.lesson_id = l.id
   SET l.title = x.t,
       l.file_path = IF(x.first_path = '', l.file_path, x.first_path)
 WHERE (c.course_id, c.cls) IN ((12, 3), (12, 6), (13, 3), (13, 7), (13, 12), (14, 3), (14, 7), (14, 13), (14, 19));

UPDATE modules SET module_title = 'Week 2 — Reading Parts 1-2 & Writing Email'
 WHERE course_id = 12 AND module_order = 2;
UPDATE modules SET module_title = 'Week 3 — Speaking Tasks 1-4, Reading Parts 3-4 & Reading Test'
 WHERE course_id = 12 AND module_order = 3;

DROP TEMPORARY TABLE m164_cls;
DROP TEMPORARY TABLE m164_ren;
DROP TEMPORARY TABLE m164_new;

COMMIT;
