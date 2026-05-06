-- Migration 008: Seed IELTS General — 2 Month Intensive course
--
-- Creates the 2-Month product with 2 modules (Months 1 & 2 — Classes 1–16).
-- Lesson rows are copied directly from IELTS_Gen_Mst Months 1 and 2,
-- so all content, tiers, and file_paths are inherited automatically.
--
-- Dependencies: Migration 006 MUST be applied first. Migration 007 recommended first
--   (so all three products are applied in logical order), but not strictly required.
--
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

-- ── Source references ───────────────────────────────────────────────────────

SET @mst_course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst' LIMIT 1);
SET @mst_mod1_id   = (SELECT id FROM modules  WHERE course_id = @mst_course_id AND module_order = 1 LIMIT 1);
SET @mst_mod2_id   = (SELECT id FROM modules  WHERE course_id = @mst_course_id AND module_order = 2 LIMIT 1);

-- ── Step 1: New course ───────────────────────────────────────────────────────

INSERT INTO `courses`
  (title, folder_name, description, category, price, is_free, instructor_name, total_lessons)
VALUES (
  'IELTS General - 2 Month Intensive',
  'IELTS_Gen_2Mo',
  '8 weeks, 16 sessions (2 per week). Builds on all four IELTS General skills from foundation through to skill development: full Listening question type coverage, advanced Reading strategies (MCQ, matching headings, sentence completion), Writing Task 1 all three registers plus Writing Task 2 introductions, and Speaking Parts 1, 2 and 3. Includes 3 full practice test sets and 2 full mock exams.',
  'IELTS',
  0.00,
  0,
  'Scholarly Language Services',
  16
);

SET @new_course_id = LAST_INSERT_ID();

-- ── Step 2: Modules ──────────────────────────────────────────────────────────

INSERT INTO `modules` (course_id, module_title, module_order, min_tier)
VALUES
  (@new_course_id, 'Month 1 - Foundations',       1, 'intermediate'),
  (@new_course_id, 'Month 2 - Skill Development',  2, 'advanced');

SET @new_mod1_id = (SELECT id FROM modules WHERE course_id = @new_course_id AND module_order = 1 LIMIT 1);
SET @new_mod2_id = (SELECT id FROM modules WHERE course_id = @new_course_id AND module_order = 2 LIMIT 1);

-- ── Step 3a: Lessons — Month 1 (copied from Mst Month 1) ────────────────────

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

-- ── Step 3b: Lessons — Month 2 (copied from Mst Month 2) ────────────────────

INSERT INTO lessons
  (course_id, module_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT
  @new_course_id,
  @new_mod2_id,
  title,
  content,
  icon,
  lesson_order,
  duration_minutes,
  min_tier,
  file_path
FROM lessons
WHERE course_id = @mst_course_id
  AND module_id  = @mst_mod2_id
ORDER BY lesson_order;

-- ── Verify ───────────────────────────────────────────────────────────────────
-- SELECT id, title, folder_name, total_lessons FROM courses WHERE folder_name = 'IELTS_Gen_2Mo';
-- SELECT id, module_title, module_order, min_tier FROM modules WHERE course_id = @new_course_id ORDER BY module_order;
-- SELECT m.module_order, l.lesson_order, l.min_tier, l.file_path
-- FROM lessons l JOIN modules m ON l.module_id = m.id
-- WHERE l.course_id = @new_course_id ORDER BY m.module_order, l.lesson_order;
-- Expected: 1 course, 2 modules, 16 lessons total (8 per module)
