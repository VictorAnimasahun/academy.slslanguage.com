-- Migration 065 — Replace IELTS Academic Diagnostic Listening content with
-- real Full Mock 1 Part 1 content (Cambridge, already verified via FM1_L).
--
-- Migration 063 wired the diagnostic Listening section to the OLD
-- diagnostic_IELTS.php page's content — audio + questions that were never
-- verified against real IELTS material. The correct approach (per original
-- instruction) is to reuse a Full Mock's already-confirmed audio + written
-- content. This uses IELTS_FM1_L Part 1 (Q1-10, "Children's Engineering
-- Workshops", form/note completion — see migration 016), which already
-- matches the diagnostic's 10-question, single-part shape with no
-- adaptation needed.
--
-- Safe on both LOCAL (already has the old wrong content from 063 — this
-- deletes it first) and LIVE (063 never got past the container-row insert,
-- so there's nothing to delete there — the DELETE is a no-op).
-- Run on LOCAL first, then LIVE.

-- ── Step 1: wipe any existing (wrong) Listening content for the diagnostic ──
DELETE FROM question_correct_answers
WHERE question_id IN (
    SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L')
);
DELETE FROM question_options
WHERE question_id IN (
    SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L')
);
DELETE FROM questions
WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L');

-- ── Step 2: insert real FM1 Part 1 questions (Q1-10) ─────────────────────────
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'form_note_completion', d.txt, 'Write ONE WORD AND/OR A NUMBER for each answer.', d.stim, 1, 1.0, d.qn
FROM tests t,
(
  SELECT 1  qn, 'Create a cover for an ___ so they can drop it from a height without breaking it' txt, 'Children''s Engineering Workshops' stim
  UNION ALL SELECT 2,  'Take part in a competition to build the tallest ___', NULL
  UNION ALL SELECT 3,  'Make a ___ powered by a balloon', NULL
  UNION ALL SELECT 4,  'Build model cars, trucks and ___ and learn how to program them so they can move', NULL
  UNION ALL SELECT 5,  'Take part in a competition to build the longest ___ using card and wood', NULL
  UNION ALL SELECT 6,  'Create a short ___ with special software', NULL
  UNION ALL SELECT 7,  'Build, ___ and program a humanoid robot', NULL
  UNION ALL SELECT 8,  'Held on ___ from 10 am to 11 am', NULL
  UNION ALL SELECT 9,  'Building 10A, ___ Industrial Estate, Grasford', NULL
  UNION ALL SELECT 10, 'Plenty of ___ is available', NULL
) d
WHERE t.code = 'IELTS_ACA_DIAG_L';

-- ── Step 3: insert real FM1 Part 1 correct answers ───────────────────────────
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1  qn, 'egg'        ans, 0 alt
  UNION ALL SELECT 2,  'tower',      0
  UNION ALL SELECT 3,  'car',        0
  UNION ALL SELECT 4,  'animals',    0
  UNION ALL SELECT 5,  'bridge',     0
  UNION ALL SELECT 6,  'movie',      0
  UNION ALL SELECT 6,  'film',       1
  UNION ALL SELECT 7,  'decorate',   0
  UNION ALL SELECT 8,  'Wednesdays', 0
  UNION ALL SELECT 8,  'wednesdays', 1
  UNION ALL SELECT 9,  'Fradstone',  0
  UNION ALL SELECT 9,  'fradstone',  1
  UNION ALL SELECT 10, 'parking',    0
) d
WHERE t.code = 'IELTS_ACA_DIAG_L' AND q.question_number = d.qn;

-- Verify: expect 10 questions, 13 answer rows (3 have an alternative)
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L');
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L');
