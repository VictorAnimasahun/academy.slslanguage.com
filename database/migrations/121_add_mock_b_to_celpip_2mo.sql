-- ============================================================
-- Migration 121 -- Add a second full mock (Mock B) to CELPIP_Gen_2Mo
--
-- Reverses part of migration 113's decision ("there is no second mock in
-- the first 16 classes of the 3-month course... Mock B removed") --
-- instructor confirmed 2026-09-24 this was wrong: the 2-Month course
-- should have its own Mock B too, same as the 3-Month course does at its
-- own Week 11. Adds a new Week 9 (Class 17-18), modeled directly on
-- course 14's real Week 11 (same two lesson titles/file_paths/tiers).
-- Matches courses by folder_name only (no hardcoded ids). Idempotent
-- (checks the module doesn't already exist first).
-- Run on LOCAL first, then LIVE.
-- ============================================================

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Week 9 — Mock 2', 9, 'beginner'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules m WHERE m.course_id = c.id AND m.module_order = 9);

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, duration_minutes, min_tier)
SELECT c.id, m.id, 'Mock Test 2 — Final Assessment', 'resources/mock_tests/celpip_full_mock_b.php', 1, 0, 'intermediate'
FROM courses c
JOIN modules m ON m.course_id = c.id AND m.module_order = 9
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.module_id = m.id AND l.lesson_order = 1);

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, duration_minutes, min_tier)
SELECT c.id, m.id, 'Final Performance Review', 'courses/CELPIP_Gen/lessons/week11_final_review.php', 2, 0, 'intermediate'
FROM courses c
JOIN modules m ON m.course_id = c.id AND m.module_order = 9
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.module_id = m.id AND l.lesson_order = 2);

UPDATE courses SET total_lessons = 18 WHERE folder_name = 'CELPIP_Gen_2Mo';

-- Verify:
--   SELECT m.module_order, l.lesson_order, l.title, l.file_path, l.min_tier
--     FROM modules m JOIN lessons l ON l.module_id=m.id JOIN courses c ON c.id=m.course_id
--     WHERE c.folder_name='CELPIP_Gen_2Mo' ORDER BY m.module_order, l.lesson_order;  -- 9 weeks, 18 classes
-- Rollback: DELETE lessons for module_order=9 on this course, then DELETE that module row,
--   then UPDATE courses SET total_lessons=16 WHERE folder_name='CELPIP_Gen_2Mo'.
