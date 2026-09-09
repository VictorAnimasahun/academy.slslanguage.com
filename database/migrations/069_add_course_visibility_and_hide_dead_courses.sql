-- ============================================================
-- Migration 069 — Add courses.is_visible; hide dead/duplicate course cards
--
-- The student course catalogue (courses/courses_catalogue.php) has never
-- filtered on anything but search/price — every row in `courses` is shown
-- as a card, including rows with zero modules/lessons. Investigation found:
--
--   id=2  "IELTS Masterclass"                — 0 modules, 0 lessons, 0 enrollments.
--         Legacy/superseded by id=4 (IELTS Academic Masterclass, 26 real
--         lessons). Dead record; `total_lessons` still stale-shows 11.
--   id=7  "CELPIP Masterclass"                — 0 modules, 0 lessons, 0 enrollments.
--         This is the course the instructor reported as "not working" —
--         it isn't broken so much as never built; superseded by id=14
--         (CELPIP General Masterclass — 3 Months, 24 real lessons).
--   id=11 "IELTS General - 2 Month Intensive" — 2 modules, 16 lessons, but its
--         "Month 1 - Foundations" / "Month 2 - Skill Development" modules are
--         literally the same titles as the first two months of id=9's
--         3-month curriculum (IELTS General - 3 Month Masterclass) — a
--         redundant shorter subset, per instructor: "IELTS Masterclass is
--         the same as intensive, remove the latter from the course cards."
--         Has 1 real student enrollment, so this HIDES it rather than
--         deleting the course/enrollment — the enrolled student keeps their
--         access via the dashboard/direct URL, it just won't be offered to
--         new students on the catalogue.
--
-- id=17 (IELTS Academic Masterclass — 3 Months, 3 modules but ZERO lessons)
-- and the exact-duplicate id=12/19 (CELPIP General — 1-Month Plan) are
-- NOT touched here — both need a product decision (which one is canonical)
-- rather than a mechanical hide, so they're left for a follow-up migration.
--
-- Idempotent — safe to re-run. Run on LOCAL first, then LIVE.
-- ============================================================

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'courses'
      AND COLUMN_NAME  = 'is_visible'
);

SET @sql = IF(
    @existing = 0,
    'ALTER TABLE courses ADD COLUMN is_visible TINYINT(1) NOT NULL DEFAULT 1 AFTER is_free',
    'SELECT ''is_visible column already exists — skipping'' AS info'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

UPDATE courses SET is_visible = 0 WHERE id IN (2, 7, 11);

-- Verify: expect is_visible=0 only for 2, 7, 11
-- SELECT id, title, is_visible FROM courses ORDER BY id;
