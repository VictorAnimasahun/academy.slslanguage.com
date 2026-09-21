-- ============================================================
-- Migration 112 -- Week titles: no "+" tying topics together
--
-- CELPIP_Gen_2Mo and CELPIP_Gen_3Mo week titles used " + " ("Week 2 -- Reading + Speaking
-- Focus"). Replaced with " & ", matching the IELTS Academic week titles. Class (lesson)
-- titles keep their storage separator in the database but are always displayed one piece
-- per line -- see includes/lesson_title.php.
-- Idempotent (only touches titles that still contain " + "). Safe on MySQL 5.7 and 8.0.
-- Run on LOCAL first, then LIVE.
-- ============================================================

UPDATE modules m
JOIN courses c ON c.id = m.course_id
SET m.module_title = REPLACE(m.module_title, ' + ', ' & ')
WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo')
  AND m.module_title LIKE '% + %';

-- Verify: SELECT COUNT(*) FROM modules WHERE module_title LIKE '% + %';   -- 0
-- Rollback: not needed (cosmetic); re-apply the old titles from migrations 077/081 if ever required.
