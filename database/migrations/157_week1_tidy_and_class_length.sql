-- ============================================================
-- Migration 157 -- Week 1 tidy-up, two-hour classes, the platform's own Foundational English Assessment page
--
-- Founder, 2026-10-21:
--  * "Writing Task Overview has no business in Week 1." IELTS Academic 2/3-Month (16, 17): it moves from Class 2
--    to Class 5, straight before Writing Test 1 (its page was renamed class05_p1_writing-task-overview.php).
--      Class 2: IELTS Academic Introduction + IELTS Academic Introduction Quiz + IELTS Academic Diagnostic Test
--      Class 5: Writing Task Overview + Writing Test 1 (Timed) + Listening Formats
--  * Week 1 of every 2/3-month course introduces the course and the exam; the old titles still promised section
--    work ("... & Listening Start", "Introduction & Read Aloud"). All become "Week 1 — Course & Exam Introduction".
--  * Every class is two hours: classes stored as 0, 60 or 90 minutes become 120. Mock classes keep their longer
--    stored length; CELPIP's (stored as 0) become 180.
--  * CELPIP class-day tests that are a single page open it directly (no launcher screen in between).
--  * The Foundational English Assessment opens the platform's own page (resources/placement/...), not the old
--    public quiz (different look, start screen, saves nothing). The founder writes its questions; until then its
--    items are Coming Soon.
--
-- One transaction (a failure commits nothing). Classes found by course + week + class order. Re-runnable,
-- MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

-- ---------- IELTS Academic 2/3-Month: Writing Task Overview from Class 2 to Class 5 ----------
DELETE p FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (16, 17) AND (m.module_order - 1) * 2 + l.lesson_order IN (2, 5);

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code, pass_percent)
SELECT l.id, x.ord, x.title, x.kind, x.status,
       CASE WHEN x.file IS NULL OR x.file LIKE 'resources/%' THEN x.file
            ELSE CONCAT('courses/', CONVERT(c.folder_name USING utf8mb4) COLLATE utf8mb4_unicode_ci, '/lessons/',
                        CONVERT(x.file USING utf8mb4) COLLATE utf8mb4_unicode_ci) END,
       x.test_code, NULL
  FROM lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN courses c ON c.id = l.course_id
  JOIN (SELECT 2 AS cls, 1 AS ord, 'IELTS Academic Introduction' AS title, 'lesson' AS kind, 'ready' AS status, 'class02_p1_ielts-academic-introduction.php' AS file, NULL AS test_code
        UNION ALL SELECT 2, 2, 'IELTS Academic Introduction Quiz', 'quiz', 'coming_soon', NULL, NULL
        UNION ALL SELECT 2, 3, 'IELTS Academic Diagnostic Test', 'diagnostic_test', 'ready', 'resources/mock_tests/ielts_aca_diagnostic.php', 'IELTS_ACA_DIAGNOSTIC'
        UNION ALL SELECT 5, 1, 'Writing Task Overview', 'lesson', 'ready', 'class05_p1_writing-task-overview.php', NULL
        UNION ALL SELECT 5, 2, 'Writing Test 1 (Timed)', 'practice_test', 'ready', 'resources/practice_tests/ielts_writing_academic_001.php', NULL
        UNION ALL SELECT 5, 3, 'Listening Formats', 'quiz', 'ready', 'resources/practice_tests/ielts_listening_formats_sample.php', NULL) x
    ON x.cls = (m.module_order - 1) * 2 + l.lesson_order
 WHERE l.course_id IN (16, 17);

UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = CASE (m.module_order - 1) * 2 + l.lesson_order
                   WHEN 2 THEN 'IELTS Academic Introduction + IELTS Academic Introduction Quiz + IELTS Academic Diagnostic Test'
                   ELSE 'Writing Task Overview + Writing Test 1 (Timed) + Listening Formats' END,
       l.file_path = (SELECT p.file_path FROM lesson_parts p WHERE p.lesson_id = l.id AND p.part_order = 1)
 WHERE l.course_id IN (16, 17) AND (m.module_order - 1) * 2 + l.lesson_order IN (2, 5);

-- ---------- Week 1 titles ----------
UPDATE modules SET module_title = 'Week 1 — Course & Exam Introduction'
 WHERE course_id IN (9, 11, 13, 14, 16, 17, 20, 21) AND module_order = 1;

-- ---------- Two-hour classes ----------
UPDATE lessons l
   SET l.duration_minutes = 180
 WHERE l.course_id IN (13, 14) AND COALESCE(l.duration_minutes, 0) = 0
   AND EXISTS (SELECT 1 FROM lesson_parts p WHERE p.lesson_id = l.id AND p.kind = 'mock_test');
UPDATE lessons SET duration_minutes = 120
 WHERE course_id IN (9, 10, 11, 12, 13, 14, 16, 17, 18, 20, 21, 23) AND COALESCE(duration_minutes, 0) IN (0, 60, 90);

-- ---------- Foundational English Assessment: the platform's own page ----------
UPDATE lesson_parts SET file_path = 'resources/placement/foundational_english_assessment.php', status = 'coming_soon'
 WHERE kind = 'placement_test' AND title = 'Foundational English Assessment';

INSERT INTO content_types (path, kind, note) VALUES
  ('resources/placement/', 'placement_test', 'the platform''s Foundational English Assessment')
ON DUPLICATE KEY UPDATE kind = VALUES(kind), note = VALUES(note);

-- ---------- CELPIP class days: a single test opens directly (founder, 2026-10-21) ----------
-- "If I open it from outside, it should open immediately, not bring me to this screen that again takes me to
-- another screen." Test items whose test is ONE page now point at it, not at class_day.php's launcher. The writing
-- days (Task 1 and Task 2 are separate pages) keep their small page listing both.
UPDATE lesson_parts SET file_path = 'courses/CELPIP_Gen/lessons/celpip_listening_001.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c3&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_reading_001.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c4&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_speaking_001.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c5&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_reading_002.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c8&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_speaking_002.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c9&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_reading_003.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c12&part=test';
UPDATE lesson_parts SET file_path = 'resources/practice_tests/celpip_speaking_003.php' WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c13&part=test';

COMMIT;
