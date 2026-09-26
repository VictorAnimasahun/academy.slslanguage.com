-- ============================================================
-- Migration 131 -- one row per course: remove the two duplicate IELTS Academic rows, give the survivor the standard name
--
-- Rule (founder, 1.4): "the major issue is to not have a duplicate -- remove that guy if someone else already exists in that form".
--   1 month  -> IELTS Academic Crash Course (IELTS_Aca_Crash, the paid one, has the students) is the ONE Academic 1-Month course.
--               The empty IELTS_Aca_1Mo row (4 weeks / 8 classes, no students) is removed.
--               Its structure (4 weeks / 8 classes, the Crash Course rule) is applied to the survivor in Step 2.
--   2 months -> IELTS Academic Masterclass -- 2 Months (IELTS_Aca_2Mo, has the students) is the ONE Academic 2-Month course.
--               The older IELTS_Aca_Mst row (no students, $50, no purchase route) is removed.
-- A row is removed ONLY if nobody is enrolled in it and it has no activity; otherwise it is left alone and the final
-- SELECT still lists it. Page files on disk are not touched. Safe to run more than once. Run AFTER 126 and 129.
-- ============================================================

SET @d1 = (SELECT c.id FROM courses c WHERE c.folder_name = 'IELTS_Aca_1Mo'
             AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
             AND NOT EXISTS (SELECT 1 FROM students_activity a WHERE a.course_id = c.id) LIMIT 1);
SET @d2 = (SELECT c.id FROM courses c WHERE c.folder_name = 'IELTS_Aca_Mst'
             AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
             AND NOT EXISTS (SELECT 1 FROM students_activity a WHERE a.course_id = c.id) LIMIT 1);

-- children first (no foreign-key cascade on these), for each duplicate that qualified
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

-- the survivor of the 1-month pair takes the standard name (2-Month already has it, from 129)
UPDATE courses SET title = 'IELTS Academic Crash Course — 1 Month' WHERE folder_name = 'IELTS_Aca_Crash';

-- Verify: IELTS Academic should now be exactly three rows (Crash 1 Month, Masterclass 2 Months, Masterclass 3 Months)
SELECT id, title, folder_name, length_months, selar_months FROM courses WHERE folder_name LIKE 'IELTS_Aca%' ORDER BY id;
