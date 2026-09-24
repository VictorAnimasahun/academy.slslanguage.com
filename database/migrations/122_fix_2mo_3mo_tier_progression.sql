-- ============================================================
-- Migration 122 -- Fix tier progression for CELPIP_Gen_2Mo and CELPIP_Gen_3Mo
--
-- Instructor confirmed 2026-09-24: a course's own duration name IS its
-- required tier -- "isn't that obvious in the name?" IELTS_Aca_2Mo/3Mo
-- already do this correctly (verified as the reference pattern): weeks are
-- split into month-sized blocks, each block gated one tier higher than the
-- last (4 weeks/block): intermediate (month 1) -> advanced (month 2) ->
-- fluent (month 3). CELPIP's own 2Mo/3Mo courses never got this treatment
-- -- every lesson past Week 1 sat flat at 'intermediate' regardless of
-- which month it's actually in, meaning a 1-month subscriber currently
-- gets the ENTIRE 2- or 3-month course for free. Invisible while
-- FREE_ACCESS_FOR_ALL was on (migration in tier_access.php, 2026-09-24).
--
-- CELPIP_Gen_2Mo is now 9 weeks (migration 121 added Week 9/Mock 2) --
-- weeks 5-9 (month 2) all become 'advanced', matching how IELTS_Aca_2Mo's
-- own final week is still 'advanced', not a new higher tier.
-- CELPIP_Gen_3Mo is 12 weeks -- weeks 5-8 (month 2) -> 'advanced',
-- weeks 9-12 (month 3) -> 'fluent', exactly mirroring IELTS_Aca_3Mo.
-- Weeks 1-4 (month 1) stay 'intermediate' on both, unchanged; Week 1's own
-- free-preview lesson (min_tier='beginner') is untouched either way since
-- this only touches non-beginner rows.
-- Matches by folder_name, not hardcoded ids. Idempotent (re-running just
-- re-applies the same target values).
-- Run on LOCAL first, then LIVE.
-- ============================================================

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.min_tier = 'advanced'
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order BETWEEN 5 AND 9 AND l.min_tier != 'beginner';

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.min_tier = 'advanced'
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order BETWEEN 5 AND 8 AND l.min_tier != 'beginner';

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.min_tier = 'fluent'
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order BETWEEN 9 AND 12 AND l.min_tier != 'beginner';

-- Verify:
--   SELECT m.module_order, l.lesson_order, l.min_tier FROM modules m JOIN lessons l ON l.module_id=m.id
--     JOIN courses c ON c.id=m.course_id WHERE c.folder_name IN ('CELPIP_Gen_2Mo','CELPIP_Gen_3Mo')
--     ORDER BY c.folder_name, m.module_order, l.lesson_order;
-- Rollback: no clean rollback (previous per-row tier values weren't uniformly 'intermediate' to
--   begin with in a way that's safe to blindly restore) -- restore from a pre-migration DB backup.
