-- ============================================================
-- Migration 132 -- remove the four dead first-day seed courses (no duplicates)
--
-- Facts (checked 2026-09-26 on local): IELTS Crash Course (id 3), CELPIP Crash Course (id 8), IELTS Masterclass (id 2, hidden)
-- and CELPIP Masterclass (id 7, hidden) were created on 2025-09-26 with the very first setup. Each has NO modules, NO lessons,
-- no progress, activity, assignments or learning points, no folder on disk, and nothing in the code refers to them. Their only enrolments are the two test
-- accounts (Akkad, Victor) at 0% progress. Every one of them now exists in its real form: IELTS General / IELTS Academic /
-- CELPIP General Crash Courses (1 Month) and Masterclasses (2 / 3 Months).
-- Rule (founder): "the major issue is to not have a duplicate -- remove that guy if someone else already exists in that form".
--
-- GUARD: a row is removed ONLY if it has no modules, no lessons and no activity, and nobody is enrolled except the two test
-- accounts (whose enrolments are cleared first). A row that does not meet that is left alone and shows in the final SELECT.
-- Safe to run more than once. Run AFTER 129 (order: 126, 128, 129, 130, 131, 132). Back up live first.
-- ============================================================

SET NAMES utf8mb4;

DELETE e FROM enrollments e
  JOIN courses c ON c.id = e.course_id
  JOIN students s ON s.id = e.student_id
 WHERE c.folder_name IN ('IELTS_Crash_Course', 'CELPIP_Crash_Course', 'IELTS_Masterclass', 'CELPIP_Masterclass')
   AND s.email IN ('akkadianabbey@gmail.com', 'ashonibarevik@gmail.com')
   AND NOT EXISTS (SELECT 1 FROM modules m WHERE m.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.course_id = c.id);

DELETE c FROM courses c
 WHERE c.folder_name IN ('IELTS_Crash_Course', 'CELPIP_Crash_Course', 'IELTS_Masterclass', 'CELPIP_Masterclass')
   AND NOT EXISTS (SELECT 1 FROM modules m WHERE m.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM students_activity a WHERE a.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM assignments x WHERE x.course_id = c.id)
   AND NOT EXISTS (SELECT 1 FROM learning_points y WHERE y.course_id = c.id);

-- Verify: nothing left with those folders (or, if a row was kept, why: it has content or a real student)
SELECT id, title, folder_name FROM courses WHERE folder_name IN ('IELTS_Crash_Course', 'CELPIP_Crash_Course', 'IELTS_Masterclass', 'CELPIP_Masterclass');
