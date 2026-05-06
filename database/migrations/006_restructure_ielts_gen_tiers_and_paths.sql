-- Migration 006: Restructure IELTS General Masterclass — fix tiers and set shared file_paths
-- Supersedes migration 005 (which was never run — skip 005 entirely).
--
-- What this does:
--   1. Corrects min_tier on IELTS_Gen_Mst modules:
--        Month 1 → intermediate, Month 2 → advanced, Month 3 → fluent
--   2. Corrects min_tier on IELTS_Gen_Mst lessons:
--        Class 1 (intro) → beginner (free preview)
--        Classes 2–8    → intermediate (1-Month Starter access)
--        Classes 9–16   → advanced    (2-Month Intensive access)
--        Classes 17–24  → fluent      (3-Month Masterclass access)
--   3. Sets file_path on all 24 lessons to the new shared lesson bank:
--        courses/IELTS_Gen/lessons/intro.php
--        courses/IELTS_Gen/lessons/class02.php … class24.php
--
-- Dependencies: Migrations 001–004 must be applied first.
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

SET @course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst' LIMIT 1);

-- ── Step 1: Module min_tiers ────────────────────────────────────────────────

UPDATE modules SET min_tier = 'intermediate' WHERE course_id = @course_id AND module_order = 1;
UPDATE modules SET min_tier = 'advanced'     WHERE course_id = @course_id AND module_order = 2;
UPDATE modules SET min_tier = 'fluent'       WHERE course_id = @course_id AND module_order = 3;

-- ── Step 2: Lesson min_tiers + file_paths ──────────────────────────────────
-- Global class number = (module_order - 1) * 8 + lesson_order

UPDATE lessons l
JOIN modules m ON l.module_id = m.id
SET
    l.min_tier = CASE
        WHEN (m.module_order - 1) * 8 + l.lesson_order = 1  THEN 'beginner'
        WHEN (m.module_order - 1) * 8 + l.lesson_order <= 8  THEN 'intermediate'
        WHEN (m.module_order - 1) * 8 + l.lesson_order <= 16 THEN 'advanced'
        ELSE 'fluent'
    END,
    l.file_path = CASE
        WHEN (m.module_order - 1) * 8 + l.lesson_order = 1
            THEN 'courses/IELTS_Gen/lessons/intro.php'
        ELSE CONCAT(
            'courses/IELTS_Gen/lessons/class',
            LPAD((m.module_order - 1) * 8 + l.lesson_order, 2, '0'),
            '.php'
        )
    END
WHERE l.course_id = @course_id;

-- ── Verify ──────────────────────────────────────────────────────────────────
-- SELECT m.module_order, m.min_tier AS module_tier, l.lesson_order,
--        (m.module_order-1)*8 + l.lesson_order AS class_num,
--        l.min_tier AS lesson_tier, l.file_path
-- FROM lessons l
-- JOIN modules m ON l.module_id = m.id
-- WHERE l.course_id = @course_id
-- ORDER BY m.module_order, l.lesson_order;
-- Expected: class 1 = beginner/intro.php, classes 2-8 = intermediate, 9-16 = advanced, 17-24 = fluent
