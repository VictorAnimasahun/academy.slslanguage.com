-- ============================================================
-- Migration 117 -- Remove exact-duplicate lesson rows (e.g. CELPIP_Gen_2Mo
-- Week 1: "Foundational English Assessment" and "Mini Diagnostic — All 4
-- Skills" were each inserted twice, showing as 4 classes in a week instead
-- of 2 -- reported live 2026-09-23, confirmed by screenshot).
--
-- Generic, not one course: finds any lessons sharing the same (module_id,
-- title) -- a literal re-insert, not two different lessons that happen to
-- be about the same topic -- and keeps only the lowest-id row. Before
-- deleting a duplicate:
--   * lesson_progress on the duplicate is merged onto the kept lesson's row
--     (a student who somehow completed the duplicate keeps that credit;
--     unique_progress(student_id, lesson_id) means a plain UPDATE could
--     collide with a progress row that already exists on the kept lesson,
--     so this is an insert-or-merge, not a blind repoint).
--   * course_pacing_items pointing at the duplicate (a quiz/test attached
--     to it) is repointed at the kept lesson instead.
-- Matches by title text, not by id -- no hardcoded course/lesson ids.
-- Idempotent: after the first run no module has two lessons with the same
-- title, so a re-run finds nothing and changes nothing.
-- Run on LOCAL first, then LIVE (pull academy first, back up DB first).
-- ============================================================

CREATE TEMPORARY TABLE tmp_dupe_map AS
SELECT l.id AS dup_id, keep.keep_id AS keep_id
FROM lessons l
JOIN (
    SELECT module_id, title, MIN(id) AS keep_id
    FROM lessons
    GROUP BY module_id, title
    HAVING COUNT(*) > 1
) keep ON keep.module_id = l.module_id AND keep.title = l.title
WHERE l.id <> keep.keep_id;

-- Merge progress onto the surviving lesson (mark complete if either row was).
INSERT INTO lesson_progress (student_id, lesson_id, completed, completed_at)
SELECT lp.student_id, m.keep_id, lp.completed, lp.completed_at
FROM lesson_progress lp
JOIN tmp_dupe_map m ON m.dup_id = lp.lesson_id
ON DUPLICATE KEY UPDATE
    completed    = GREATEST(lesson_progress.completed, VALUES(completed)),
    completed_at = COALESCE(lesson_progress.completed_at, VALUES(completed_at));

DELETE lp FROM lesson_progress lp
JOIN tmp_dupe_map m ON m.dup_id = lp.lesson_id;

-- Repoint any quiz/test attached to the duplicate onto the kept lesson.
UPDATE course_pacing_items p
JOIN tmp_dupe_map m ON m.dup_id = p.lesson_id
SET p.lesson_id = m.keep_id;

DELETE l FROM lessons l
JOIN tmp_dupe_map m ON m.dup_id = l.id;

DROP TEMPORARY TABLE tmp_dupe_map;

-- Verify (should return 0 rows before AND after -- confirms no dupes left):
--   SELECT module_id, title, COUNT(*) FROM lessons GROUP BY module_id, title HAVING COUNT(*) > 1;
-- Rollback: no clean rollback (the duplicate rows' original ids are gone) --
-- restore from the pre-migration DB backup instead.
