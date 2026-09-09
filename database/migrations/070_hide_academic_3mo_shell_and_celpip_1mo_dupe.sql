-- ============================================================
-- Migration 070 — Resolve the two remaining course duplicates from 069
--
-- id=4 "IELTS Academic Masterclass" (26 real lessons, migration 062) is
-- confirmed as the canonical IELTS Academic Masterclass going forward —
-- faster/more efficient than porting its content into id=17's empty
-- month-framed shell. id=17 "IELTS Academic Masterclass — 3 Months" (3
-- modules, 0 lessons, 0 enrollments) is hidden, same pattern as migration
-- 069 (is_visible, not deleted — nothing enrolled in it to lose either way,
-- but keeping the pattern consistent and reversible).
--
-- id=12/19 are exact-duplicate "CELPIP General — 1-Month Plan" rows
-- (flagged since migration 067, never resolved). id=12 has some module
-- scaffolding (even if duplicated within itself, see below); id=19 has
-- none. Keep id=12, hide id=19.
--
-- Also cleans up id=12's OWN internal duplicate: two identical "Month 1 —
-- CELPIP Foundations" module rows (id 16 and 29), both with zero lessons
-- under them — deletes the redundant one (29), keeps the original (16).
--
-- Idempotent — safe to re-run. Run on LOCAL first, then LIVE.
-- ============================================================

UPDATE courses SET is_visible = 0 WHERE id IN (17, 19);

DELETE FROM modules
WHERE id = 29
  AND course_id = 12
  AND module_title = 'Month 1 — CELPIP Foundations'
  AND NOT EXISTS (SELECT 1 FROM lessons WHERE module_id = 29);

-- Verify: expect is_visible=0 for 17 and 19; module id=29 gone, id=16 remains
-- SELECT id, title, is_visible FROM courses WHERE id IN (17,19);
-- SELECT id, course_id, module_title FROM modules WHERE course_id = 12;
