-- ============================================================
-- Migration 177 -- Remove duplicate pacing items and duplicate assignments (every course)
--
-- Found 2026-10-28: a CELPIP General Masterclass (2 Months) student's Assignments page listed "Class 8 -- Mock
-- Exam 1" and "Class 16 -- Mock Exam 2" twice each, same due dates. Assignments are generated once per enrollment
-- from course_pacing_items (includes/course_pacing.php and SLS Admin's copy both refuse to generate twice), so a
-- doubled assignment means a doubled pacing item: every student enrolled since then got two of each.
--
--   course_pacing_items   exact duplicates (same course, class, item type, test, offset) -> the oldest row stays
--   assignments           exact duplicates (same student, course, test, title, due date) -> the oldest row stays
-- Nothing else refers to an assignment row (completion comes from test_attempts by test), so removing a duplicate
-- loses nothing. Re-runnable: a second run finds no duplicates. MySQL 5.7-safe, no information_schema.
-- The SELECTs at the end should both show 0.
-- ============================================================

SET NAMES utf8mb4;

DELETE p FROM course_pacing_items p
  JOIN (SELECT MIN(id) AS keep_id, course_id, COALESCE(lesson_id, 0) AS lid, item_type, test_code, offset_days
          FROM course_pacing_items GROUP BY course_id, COALESCE(lesson_id, 0), item_type, test_code, offset_days
        HAVING COUNT(*) > 1) d
    ON d.course_id = p.course_id AND d.lid = COALESCE(p.lesson_id, 0) AND d.item_type = p.item_type
   AND d.test_code = p.test_code AND d.offset_days = p.offset_days AND p.id <> d.keep_id;

DELETE a FROM assignments a
  JOIN (SELECT MIN(id) AS keep_id, student_id, course_id, COALESCE(test_id, 0) AS tid, title, COALESCE(due_date, '1000-01-01') AS dd
          FROM assignments WHERE student_id IS NOT NULL
         GROUP BY student_id, course_id, COALESCE(test_id, 0), title, COALESCE(due_date, '1000-01-01')
        HAVING COUNT(*) > 1) d
    ON d.student_id = a.student_id AND d.course_id = a.course_id AND d.tid = COALESCE(a.test_id, 0)
   AND d.title = a.title AND d.dd = COALESCE(a.due_date, '1000-01-01') AND a.id <> d.keep_id;

-- Check: both 0.
SELECT COUNT(*) AS duplicate_pacing_items FROM (
  SELECT 1 FROM course_pacing_items GROUP BY course_id, COALESCE(lesson_id, 0), item_type, test_code, offset_days HAVING COUNT(*) > 1) x;
SELECT COUNT(*) AS duplicate_assignments FROM (
  SELECT 1 FROM assignments WHERE student_id IS NOT NULL GROUP BY student_id, course_id, COALESCE(test_id, 0), title, COALESCE(due_date, '1000-01-01') HAVING COUNT(*) > 1) x;
