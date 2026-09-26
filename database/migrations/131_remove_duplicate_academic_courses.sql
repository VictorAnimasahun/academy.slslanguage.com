-- ============================================================
-- Migration 131 -- ONE IELTS Academic Crash Course (4 weeks / 8 classes) and ONE IELTS Academic Masterclass -- 2 Months
--
-- Rule (founder): a 1-month course is a Crash Course of 4 weeks / 8 classes; a 2-month course is a Masterclass of
-- 8 weeks / 16 classes; "the major issue is to not have a duplicate -- remove that guy if someone else already exists".
--
--   1 month  KEEP   IELTS_Aca_1Mo  -- 4 weeks / 8 classes (the Crash Course shape). Gets the Crash Course price and text,
--                                     and each class now points at the old-course page that matches its topic (the seed
--                                     had them one off: "Reading" opened the Listening page).
--            REMOVE IELTS_Aca_Crash -- the original 9-module / 61-lesson course (the "stray"). Its only two enrolments are
--                                     test accounts (Akkad, Victor) and are removed with it. Its page files stay on disk:
--                                     the fresh course's classes still open them until the instructor designs new ones.
--   2 months KEEP   IELTS_Aca_2Mo  -- 8 weeks / 16 classes.
--            REMOVE IELTS_Aca_Mst  -- the older Masterclass (no students, $50, no purchase route).
--
-- A row is removed ONLY if nobody is enrolled in it (after the two test enrolments are cleared) and it has no activity;
-- otherwise it is left alone and the final SELECT still lists it. Safe to run more than once.
-- Run AFTER 126 and 129. Back up live first.
-- ============================================================

SET NAMES utf8mb4;

-- 1. test enrolments on the stray course (Akkad, Victor): clear them so the guard below sees "nobody enrolled"
DELETE e FROM enrollments e
  JOIN courses c ON c.id = e.course_id
  JOIN students s ON s.id = e.student_id
 WHERE c.folder_name = 'IELTS_Aca_Crash'
   AND s.email IN ('akkadianabbey@gmail.com', 'ashonibarevik@gmail.com');

-- 2. the rows to remove (found once, kept in variables: older MySQL refuses outer-row references inside derived tables)
SET @d1 = (SELECT c.id FROM courses c WHERE c.folder_name = 'IELTS_Aca_Crash'
             AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
             AND NOT EXISTS (SELECT 1 FROM students_activity a WHERE a.course_id = c.id) LIMIT 1);
SET @d2 = (SELECT c.id FROM courses c WHERE c.folder_name = 'IELTS_Aca_Mst'
             AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
             AND NOT EXISTS (SELECT 1 FROM students_activity a WHERE a.course_id = c.id) LIMIT 1);

-- children first (no foreign-key cascade on these)
DELETE wb FROM week_briefs wb JOIN modules m ON m.id = wb.module_id WHERE m.course_id IN (@d1, @d2);
DELETE wr FROM week_resources wr JOIN modules m ON m.id = wr.module_id WHERE m.course_id IN (@d1, @d2);
DELETE wv FROM week_vocab_words wv JOIN modules m ON m.id = wv.module_id WHERE m.course_id IN (@d1, @d2);
DELETE lp FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id WHERE m.course_id IN (@d1, @d2);
DELETE pr FROM lesson_progress pr JOIN lessons l ON l.id = pr.lesson_id JOIN modules m ON m.id = l.module_id WHERE m.course_id IN (@d1, @d2);
DELETE FROM course_pacing_items WHERE course_id IN (@d1, @d2);
DELETE l FROM lessons l JOIN modules m ON m.id = l.module_id WHERE m.course_id IN (@d1, @d2);
DELETE FROM modules WHERE course_id IN (@d1, @d2);
DELETE FROM learning_points WHERE course_id IN (@d1, @d2);
DELETE FROM assignments WHERE course_id IN (@d1, @d2);
DELETE FROM course_ratings WHERE course_id IN (@d1, @d2);
DELETE FROM courses WHERE id IN (@d1, @d2);

-- 3. the surviving Crash Course: name, price, text (same as the other 1-month courses: 90,000 / 105,000 NGN, Selar link by length)
UPDATE courses
   SET title = 'IELTS Academic Crash Course — 1 Month',
       description = 'A focused 4-week IELTS Academic Crash Course: 8 classes (2 per week) covering Academic Reading, Writing Tasks 1 and 2, Listening and Speaking, ending with a full mock exam.',
       price = 90000.00,
       compare_price = 105000.00
 WHERE folder_name = 'IELTS_Aca_1Mo';
UPDATE courses SET buy_url = 'https://selar.com/sls_ielts_celpip_crash_course' WHERE folder_name = 'IELTS_Aca_1Mo';

-- 4. each class opens the page for ITS topic. (module N of the old course: 2 Listening, 3 Reading, 4 Writing Task 1,
--    5 Writing Task 2, 6 Speaking Parts 1 & 2, 7 Speaking Part 3.) Class 8 is the mock exam: no page yet, so Coming Soon.
UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN courses c ON c.id = m.course_id
   SET l.file_path = CASE (m.module_order - 1) * 2 + l.lesson_order
        WHEN 1 THEN 'courses/IELTS_Aca_Crash/intro.php'
        WHEN 2 THEN 'courses/IELTS_Aca_Crash/module3.php'
        WHEN 3 THEN 'courses/IELTS_Aca_Crash/module4.php'
        WHEN 4 THEN 'courses/IELTS_Aca_Crash/module5.php'
        WHEN 5 THEN 'courses/IELTS_Aca_Crash/module2.php'
        WHEN 6 THEN 'courses/IELTS_Aca_Crash/module6.php'
        WHEN 7 THEN 'courses/IELTS_Aca_Crash/module7.php'
        ELSE NULL END
 WHERE c.folder_name = 'IELTS_Aca_1Mo';

-- Verify: IELTS Academic = Crash Course 1 Month, Masterclass 2 Months, Masterclass 3 Months (three rows), 8 classes in the Crash Course
SELECT id, title, folder_name, length_months, selar_months, price, compare_price FROM courses WHERE folder_name LIKE 'IELTS_Aca%' ORDER BY id;
SELECT (m.module_order - 1) * 2 + l.lesson_order AS class_no, l.title, l.file_path
  FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name = 'IELTS_Aca_1Mo' ORDER BY class_no;
