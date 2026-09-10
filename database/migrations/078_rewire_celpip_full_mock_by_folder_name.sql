-- ============================================================
-- Migration 078 — Re-wire CELPIP Full Mock A/B using stable lookups
--
-- Migration 074 wired lesson 163 -> celpip_full_mock_a.php, lesson 178 ->
-- celpip_full_mock_b.php, and 2 course_pacing_items rows, all via hardcoded
-- numeric ids (id=163, id=178, course_id=13). Those ids only happen to be
-- correct locally because course 13 ("CELPIP_Gen_2Mo") and its lessons
-- already existed there.
--
-- Investigation on 2026-09-10 (prompted by "only 8 courses in the
-- catalogue") found that course_id 9-19 -- every "General"/"Academic"/PTE
-- course track, course 13 included -- was seeded from an untracked draft
-- file that was run locally but never on live (see migration 077, which
-- formalizes that draft into the tracked, idempotent migration system).
--
-- This means migration 074, despite being checked off as run on live,
-- almost certainly did nothing there: `UPDATE lessons ... WHERE id = 163`
-- against a live DB that has no lesson with that id silently affects 0
-- rows (MySQL does not error on a no-op UPDATE), and the course_pacing_items
-- insert would either silently create orphaned rows (course_id 13 pointing
-- at nothing) or fail on a FK constraint, if one exists on that column.
-- Either way, live needs this migration -- run AFTER 077 -- to actually
-- wire the CELPIP Full Mock A/B content (seeded independently by migration
-- 073, which has no course/lesson dependency and did apply cleanly on live)
-- into course 13's lesson list and pacing schedule.
--
-- Resolves everything by folder_name + module_order + lesson_order instead
-- of raw ids, so it works correctly regardless of what auto-increment
-- values 077 produces on a given environment.
--
-- Idempotent — safe to re-run (including on local, where 074 already did
-- this correctly; this should be a no-op there).
--
-- ⚠️ CHARSET WARNING: pacing item titles contain em-dashes. If importing via
-- the `mysql` CLI, pass --default-character-set=utf8mb4 (see migration 073).
-- ============================================================

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/mock_tests/celpip_full_mock_a.php'
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order = 1
  AND l.lesson_order = 8
  AND (l.file_path IS NULL OR l.file_path <> 'resources/mock_tests/celpip_full_mock_a.php');

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/mock_tests/celpip_full_mock_b.php'
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order = 2
  AND l.lesson_order = 8
  AND (l.file_path IS NULL OR l.file_path <> 'resources/mock_tests/celpip_full_mock_b.php');

INSERT INTO course_pacing_items (course_id, lesson_id, item_type, title, test_code, offset_days, display_order)
SELECT c.id, l.id, 'mock_test',
       CASE m.module_order WHEN 1 THEN 'Class 8 — Mock Exam 1 (End of Month 1)'
                            ELSE 'Class 16 — Mock Exam 2 (End of Month 2)' END,
       CASE m.module_order WHEN 1 THEN 'CELPIP_FULL_MOCK_A' ELSE 'CELPIP_FULL_MOCK_B' END,
       CASE m.module_order WHEN 1 THEN 24 ELSE 52 END,
       CASE m.module_order WHEN 1 THEN 80 ELSE 160 END
FROM lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order IN (1, 2)
  AND l.lesson_order = 8
  AND NOT EXISTS (
    SELECT 1 FROM course_pacing_items cpi WHERE cpi.course_id = c.id AND cpi.lesson_id = l.id
  );

-- Rollback:
-- DELETE FROM course_pacing_items WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo') AND test_code IN ('CELPIP_FULL_MOCK_A','CELPIP_FULL_MOCK_B');
-- UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id SET l.file_path = 'courses/CELPIP_intro/celpip_mini_mock.php' WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 1 AND l.lesson_order = 8;
-- UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id SET l.file_path = NULL WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 2 AND l.lesson_order = 8;
