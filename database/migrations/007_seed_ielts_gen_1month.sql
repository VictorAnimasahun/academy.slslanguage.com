-- Migration 007: Seed IELTS General — 1 Month Starter course
--
-- Creates the 1-Month product with 1 module (Month 1 — Classes 1–8).
-- Lesson rows are copied directly from IELTS_Gen_Mst Month 1,
-- so titles, content, icons, durations, min_tiers, and file_paths
-- are all inherited — no duplication of content strings.
--
-- Dependencies: Migration 006 MUST be applied first (so Mst file_paths and
--   min_tiers are correct before we copy them here).
--
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

-- ── Source references ───────────────────────────────────────────────────────

SET @mst_course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst' LIMIT 1);
SET @mst_mod1_id   = (SELECT id FROM modules  WHERE course_id = @mst_course_id AND module_order = 1 LIMIT 1);

-- ── Step 1: New course ───────────────────────────────────────────────────────

INSERT INTO `courses`
  (title, folder_name, description, category, price, is_free, instructor_name, total_lessons)
VALUES (
  'IELTS General - 1 Month Starter',
  'IELTS_Gen_1Mo',
  '4 weeks, 8 sessions (2 per week). Covers all four IELTS General skills at foundation level: Listening form completion and MCQ, Reading skimming/scanning and TFNG, Writing Task 1 register and formal letters, Speaking Parts 1 and 2. Includes 1 full practice test set and a full mock exam at the end of the month.',
  'IELTS',
  0.00,
  0,
  'Scholarly Language Services',
  8
);

SET @new_course_id = LAST_INSERT_ID();

-- ── Step 2: Module ───────────────────────────────────────────────────────────

INSERT INTO `modules` (course_id, module_title, module_order, min_tier)
VALUES (@new_course_id, 'Month 1 - Foundations', 1, 'intermediate');

SET @new_mod1_id = LAST_INSERT_ID();

-- ── Step 3: Lessons (copied from Mst Month 1) ────────────────────────────────

INSERT INTO lessons
  (course_id, module_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT
  @new_course_id,
  @new_mod1_id,
  title,
  content,
  icon,
  lesson_order,
  duration_minutes,
  min_tier,
  file_path
FROM lessons
WHERE course_id = @mst_course_id
  AND module_id  = @mst_mod1_id
ORDER BY lesson_order;

-- ── Verify ───────────────────────────────────────────────────────────────────
-- SELECT id, title, folder_name, total_lessons FROM courses WHERE folder_name = 'IELTS_Gen_1Mo';
-- SELECT id, module_title, module_order, min_tier FROM modules WHERE course_id = @new_course_id;
-- SELECT lesson_order, min_tier, file_path FROM lessons WHERE course_id = @new_course_id ORDER BY lesson_order;
-- Expected: 1 course, 1 module, 8 lessons (lesson_order 1-8)
