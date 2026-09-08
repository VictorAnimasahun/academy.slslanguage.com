-- Migration 063 — Rebuild the IELTS Academic Diagnostic Test on the real DB-driven
-- mock-session architecture (same tests/questions/mock_sessions machinery as the
-- Full Mock tests), replacing the old hardcoded/client-JS-graded diagnostic_IELTS.php.
-- Listening reuses the real audio + questions/answers that were already hardcoded in
-- the old page (they were written together, so the answers actually match the audio).
-- The old Reading passage was left as an unfinished stub with no answer key, and the
-- old Writing task was a General Training letter — wrong content for an Academic
-- course — so both are replaced with real, complete, Academic-appropriate content.
-- Run on LOCAL first, then LIVE.
-- Idempotent: every INSERT below is guarded so re-running this file only fills
-- in whatever's missing — safe to paste again after a partial/failed run.

-- ── Container + section tests ────────────────────────────────────────────────
INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_ACA_DIAGNOSTIC', 'IELTS Academic Diagnostic Test',
       'A short diagnostic across Listening, Reading, Writing Task 1, and Speaking Part 2 — used to show where a student stands before starting the Masterclass.',
       'IELTS_Academic', 'Full', 60, 18, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_ACA_DIAGNOSTIC');

INSERT INTO tests (code, title, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_ACA_DIAG_L', 'IELTS Academic Diagnostic — Listening', 'IELTS_Academic', 'Listening', 20, 10, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_ACA_DIAG_L');

INSERT INTO tests (code, title, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_ACA_DIAG_R', 'IELTS Academic Diagnostic — Reading', 'IELTS_Academic', 'Reading', 20, 7, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_ACA_DIAG_R');

INSERT INTO tests (code, title, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_ACA_DIAG_W', 'IELTS Academic Diagnostic — Writing', 'IELTS_Academic', 'Writing', 20, 1, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_ACA_DIAG_W');

INSERT INTO mock_exams (code, exam_type, title, description, total_duration_minutes)
SELECT 'IELTS_ACA_DIAGNOSTIC', 'IELTS_Academic', 'IELTS Academic Diagnostic Test',
       'Abridged diagnostic covering all four skills, taken before Week 2 of the Masterclass.', 60
WHERE NOT EXISTS (SELECT 1 FROM mock_exams WHERE code = 'IELTS_ACA_DIAGNOSTIC');

-- ── Listening — Q1-6 form completion, Q7-10 matching (real answers, matches the
--    real audio already on disk at assets/audio/IELTS_ACA_DIAGNOSTIC/listening_part1.mp3) ──
-- Note: for question_type='matching', the listening template only renders the
-- shared A/B/C option-legend box once, gated on the FIRST matching question in
-- the group having a non-empty stimulus_text — later questions in the group
-- just show a bare letter-input referencing that same box.
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, d.qt, d.txt, d.instr, d.stim, 1, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'form_note_completion' qt, 'Contact phone number is _____'                              txt, 'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.' instr, NULL stim
  UNION ALL SELECT 2, 'form_note_completion', 'Collect from 119 _____ Hamilton, Waikato, New Zealand',           'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.', NULL
  UNION ALL SELECT 3, 'form_note_completion', 'Ship to 2096 _____ Edmonton, Alberta, Canada',                    'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.', NULL
  UNION ALL SELECT 4, 'form_note_completion', 'Prepare for shipment on _____, January the 9th',                  'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.', NULL
  UNION ALL SELECT 5, 'form_note_completion', 'Tidy up the collection site by 9 AM on _____, January the 12th',  'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.', NULL
  UNION ALL SELECT 6, 'form_note_completion', 'Store before shipment for _____ months',                          'Complete the form below. Write NO MORE THAN THREE WORDS OR A NUMBER.', NULL
  UNION ALL SELECT 7,  'matching', 'Where does the agent suggest packing: clothes?',        'Where does the agent suggest packing the following items? Choose the correct letter A, B, or C.', 'Where does the agent suggest packing each item?'
  UNION ALL SELECT 8,  'matching', 'Where does the agent suggest packing: coffee maker?',   'Where does the agent suggest packing the following items? Choose the correct letter A, B, or C.', NULL
  UNION ALL SELECT 9,  'matching', 'Where does the agent suggest packing: family photos?',  'Where does the agent suggest packing the following items? Choose the correct letter A, B, or C.', NULL
  UNION ALL SELECT 10, 'matching', 'Where does the agent suggest packing: computers?',      'Where does the agent suggest packing the following items? Choose the correct letter A, B, or C.', NULL
) d
WHERE t.code = 'IELTS_ACA_DIAG_L'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, '0215551234' ans UNION ALL SELECT 2, 'Queen Street' UNION ALL SELECT 3, 'Maple Road'
  UNION ALL SELECT 4, 'January 6th' UNION ALL SELECT 5, 'January 11th' UNION ALL SELECT 6, '2'
) d
WHERE t.code = 'IELTS_ACA_DIAG_L' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

-- question_options here is for DISPLAY only (the shared "Personal meanings" legend
-- box on Q7 reads from this). Scoring for question_type='matching' is NOT based on
-- option_options.is_correct — mock_save_section.php scores it like a free-text
-- answer, checked against question_correct_answers below.
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 7 qn, 'A' lbl, 'readily accessible' txt, 0 correct, 1 ord UNION ALL SELECT 7, 'B', 'personal objects', 1, 2 UNION ALL SELECT 7, 'C', 'precious items', 0, 3
  UNION ALL SELECT 8, 'A', 'readily accessible', 1, 1 UNION ALL SELECT 8, 'B', 'personal objects', 0, 2 UNION ALL SELECT 8, 'C', 'precious items', 0, 3
  UNION ALL SELECT 9, 'A', 'readily accessible', 0, 1 UNION ALL SELECT 9, 'B', 'personal objects', 0, 2 UNION ALL SELECT 9, 'C', 'precious items', 1, 3
  UNION ALL SELECT 10,'A', 'readily accessible', 0, 1 UNION ALL SELECT 10,'B', 'personal objects', 0, 2 UNION ALL SELECT 10,'C', 'precious items', 1, 3
) d
WHERE t.code = 'IELTS_ACA_DIAG_L' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 7 qn, 'b' ans UNION ALL SELECT 8, 'a' UNION ALL SELECT 9, 'c' UNION ALL SELECT 10, 'c'
) d
WHERE t.code = 'IELTS_ACA_DIAG_L' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

-- ── Reading — one short academic passage (Urban Beekeeping), summary completion ──
-- NOTE: the passage text itself is hardcoded in diagnostic_aca_reading.php (matching
-- the existing full_mock_00X_reading.php pattern — passages are not stored in the DB).
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, part_number, points, display_order)
SELECT t.id, d.qn, 'summary_completion', d.txt,
       'Complete the summary below. Choose NO MORE THAN TWO WORDS from the passage for each answer.', 1, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Bee-keeping in cities has increased partly because of concern about _____ in bee numbers.' txt
  UNION ALL SELECT 2, 'Bees are vital for _____ many food crops.'
  UNION ALL SELECT 3, 'Cities can suit bees well because they offer a wider range of _____ than a typical farm.'
  UNION ALL SELECT 4, 'Cities also tend to involve less use of large-scale _____.'
  UNION ALL SELECT 5, 'Urban beekeepers must position hives carefully to avoid upsetting _____.'
  UNION ALL SELECT 6, 'Many municipalities require beekeepers to _____ their hives with the local authority.'
  UNION ALL SELECT 7, 'Many new beekeepers now join _____ that provide training.'
) d
WHERE t.code = 'IELTS_ACA_DIAG_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'declining' ans UNION ALL SELECT 1, 'decline'
  UNION ALL SELECT 2, 'pollinating'
  UNION ALL SELECT 3, 'flowering plants'
  UNION ALL SELECT 4, 'pesticides'
  UNION ALL SELECT 5, 'neighbours'
  UNION ALL SELECT 6, 'register'
  UNION ALL SELECT 7, 'associations' UNION ALL SELECT 7, 'beekeeping associations'
) d
WHERE t.code = 'IELTS_ACA_DIAG_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

-- ── Writing — Task 1 only (bar chart data given as text; no chart image asset
--    exists yet, so the data is described in the prompt itself instead) ──────
INSERT INTO questions (test_id, question_number, question_type, question_text, points, display_order)
SELECT t.id, 1, 'essay',
'The bar chart below shows the percentage of households with access to the internet in four countries in 2005 and 2020.

Summarise the information by selecting and reporting the main features, and make comparisons where relevant.

Internet access (% of households):
Country        2005    2020
Brazil          14%     81%
Egypt            8%     72%
South Korea     78%     99%
United Kingdom  60%     96%

Write at least 150 words.',
1.0, 1
FROM tests t
WHERE t.code = 'IELTS_ACA_DIAG_W'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = 1);

-- ── Speaking — Part 2, administered live for now, same as mock_speaking.php for the
--    Full Mock tests: it's a pure status/handoff screen (marks the session
--    awaiting_speaking_grade and emails the admin) with no cue-card content pulled
--    from the DB — the instructor gives the topic directly in the live session, so
--    no question row is needed here.
