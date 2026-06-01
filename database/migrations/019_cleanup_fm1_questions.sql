-- ============================================================
-- Migration 019 — Clear FM1 Listening & Reading questions
--
-- PURPOSE: Remove incorrect/placeholder questions that were
-- manually added via the admin form so that migrations 016
-- and 017 (which contain the real Cambridge content) can
-- be re-run cleanly.
--
-- SAFE TO RUN: only touches questions/options/answers for
-- IELTS_FM1_L and IELTS_FM1_R. Does NOT delete the test
-- container rows in `tests` — migrations 016/017 will skip
-- re-inserting them (they use WHERE NOT EXISTS guards).
-- ============================================================

-- ── Verify counts BEFORE (run these SELECTs first) ────────────────────────────
-- SELECT 'FM1_L questions' AS label, COUNT(*) AS n FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L');
-- SELECT 'FM1_R questions' AS label, COUNT(*) AS n FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');
-- SELECT 'FM1_L options'   AS label, COUNT(*) AS n FROM question_options  qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L');
-- SELECT 'FM1_R options'   AS label, COUNT(*) AS n FROM question_options  qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');
-- SELECT 'FM1_L answers'   AS label, COUNT(*) AS n FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L');
-- SELECT 'FM1_R answers'   AS label, COUNT(*) AS n FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');

-- ── Step 1: Delete correct answers ───────────────────────────────────────────
DELETE FROM question_correct_answers
WHERE question_id IN (
    SELECT id FROM questions
    WHERE test_id IN (
        SELECT id FROM tests WHERE code IN ('IELTS_FM1_L', 'IELTS_FM1_R')
    )
);

-- ── Step 2: Delete options ────────────────────────────────────────────────────
DELETE FROM question_options
WHERE question_id IN (
    SELECT id FROM questions
    WHERE test_id IN (
        SELECT id FROM tests WHERE code IN ('IELTS_FM1_L', 'IELTS_FM1_R')
    )
);

-- ── Step 3: Delete questions ──────────────────────────────────────────────────
DELETE FROM questions
WHERE test_id IN (
    SELECT id FROM tests WHERE code IN ('IELTS_FM1_L', 'IELTS_FM1_R')
);

-- ── Verify counts AFTER (all should be 0) ─────────────────────────────────────
SELECT 'FM1_L questions remaining' AS label, COUNT(*) AS n FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L');
SELECT 'FM1_R questions remaining' AS label, COUNT(*) AS n FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');

-- ── Next steps ────────────────────────────────────────────────────────────────
-- Once counts show 0, run in order:
--   016_seed_ielts_fm1_listening.sql
--   017_seed_ielts_fm1_reading.sql
