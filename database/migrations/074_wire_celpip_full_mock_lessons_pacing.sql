-- ============================================================
-- Migration 074 — Wire CELPIP Full Mock A/B into course 13's lessons + pacing
--
-- Depends on migration 073 (seeds the CELPIP_FULL_MOCK_A/B content).
-- Course 13 = "CELPIP General — 2-Month Plan". Lesson 163 (Month 1, Class 8)
-- currently launches the old hardcoded courses/CELPIP_intro/celpip_mini_mock.php;
-- lesson 178 (Month 2, Class 8/16) currently has no file_path at all.
--
-- Offsets follow the same cadence established for course_id=9 (migration 072):
-- an 8-class month runs on a roughly 3-4 day cadence, landing its Mock class
-- at day 24. Month 2 continues that same cadence uninterrupted from Month 1's
-- end, landing its Mock class (the 16th overall) at day 52. These are
-- reasonable defaults, not a commitment -- easy to adjust in course_pacing_items
-- later if the instructor wants a different cadence for this course.
--
-- Idempotent — safe to re-run.
--
-- ⚠️ CHARSET WARNING: the pacing item titles below contain em-dashes. If
-- importing via the `mysql` CLI, pass --default-character-set=utf8mb4 (see
-- migration 073's header for the full explanation — the same bug bit this
-- file's titles on the first local run).
-- ============================================================

UPDATE lessons
SET file_path = 'resources/mock_tests/celpip_full_mock_a.php'
WHERE id = 163 AND (file_path IS NULL OR file_path <> 'resources/mock_tests/celpip_full_mock_a.php');

UPDATE lessons
SET file_path = 'resources/mock_tests/celpip_full_mock_b.php'
WHERE id = 178 AND (file_path IS NULL OR file_path <> 'resources/mock_tests/celpip_full_mock_b.php');

INSERT INTO course_pacing_items (course_id, lesson_id, item_type, title, test_code, offset_days, display_order)
SELECT d.course_id, d.lesson_id, d.item_type, d.title, d.test_code, d.offset_days, d.display_order
FROM (
  SELECT 13 course_id, 163 lesson_id, 'mock_test' item_type, 'Class 8 — Mock Exam 1 (End of Month 1)' title, 'CELPIP_FULL_MOCK_A' test_code, 24 offset_days, 80 display_order
  UNION ALL SELECT 13, 178, 'mock_test', 'Class 16 — Mock Exam 2 (End of Month 2)', 'CELPIP_FULL_MOCK_B', 52, 160
) d
WHERE NOT EXISTS (
  SELECT 1 FROM course_pacing_items cpi WHERE cpi.course_id = d.course_id AND cpi.lesson_id = d.lesson_id
);
