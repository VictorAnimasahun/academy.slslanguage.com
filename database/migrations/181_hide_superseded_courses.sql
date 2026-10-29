-- ============================================================
-- Migration 181 -- hide the superseded courses from the catalogue (founder, 2026-10-29: "make the decision")
--
-- Migrations 131 and 132 removed these duplicates only where nobody was enrolled; on live, enrolled students kept them,
-- so they still show in the catalogue next to the real courses:
--   IELTS_Aca_Crash    -- the original 9-week Academic Crash Course (now IELTS_Aca_1Mo, 4 weeks / 8 classes)
--   IELTS_Aca_Mst      -- the older Academic Masterclass (now IELTS_Aca_2Mo)
--   IELTS_Crash_Course -- a first-day seed course (now the real Crash Courses)
-- Nothing is deleted: enrolled students keep the course, its access and their progress (the dashboard lists enrolled
-- courses whether shown in the catalogue or not); new students no longer find it. Undo: set is_visible = 1.
-- Safe to run more than once. MySQL 5.7 and 8.0.
-- ============================================================

UPDATE courses SET is_visible = 0
 WHERE folder_name IN ('IELTS_Aca_Crash', 'IELTS_Aca_Mst', 'IELTS_Crash_Course');

SELECT folder_name, is_visible, (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = courses.id) AS enrolled
  FROM courses WHERE folder_name IN ('IELTS_Aca_Crash', 'IELTS_Aca_Mst', 'IELTS_Crash_Course');
