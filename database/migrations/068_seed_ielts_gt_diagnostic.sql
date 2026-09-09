-- ============================================================
-- Migration 068 — Seed the IELTS General Training Diagnostic Test
--
-- The diagnostic selection page (resources/diagnostic_tests/diagnostic_tests_home.php)
-- has always advertised the single "IELTS" card as "Academic & General Training",
-- but only an Academic diagnostic (IELTS_ACA_DIAGNOSTIC, migration 063) was ever
-- built — the card's link went straight to it regardless of what the copy said.
-- This migration adds a real, separate General Training diagnostic.
--
-- Content source: real IELTS General Training material already seeded for
-- Full Mock 3 (Cambridge IELTS GT Test 3 — migrations 030 & 031), reused/
-- abridged here rather than newly authored (per instructor: "pick from the
-- full mocks"). Reading = FM3's Section 2 Passage 1 ("Qualities that make a
-- great barista", originally Q15-22, renumbered 1-8 for this standalone
-- test). Writing = FM3's Task 1 letter, unchanged.
--
-- Listening is NOT duplicated: in real IELTS, Listening is identical between
-- Academic and General Training, so this diagnostic's Listening section
-- reuses the exact same IELTS_ACA_DIAG_L test row + audio as the Academic
-- diagnostic (wired in includes/mock_test_map.php, not here). Only Reading
-- and Writing actually differ between the two, so only those get new rows.
--
-- Idempotent — every INSERT is guarded so re-running only fills in what's
-- missing. Run on LOCAL first, then LIVE.
-- ============================================================

-- ── Container + section tests ────────────────────────────────────────────────
INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_GT_DIAGNOSTIC', 'IELTS General Training Diagnostic Test',
       'A short diagnostic across Listening, Reading, Writing Task 1, and Speaking Part 2 — used to show where a General Training student stands before starting the Masterclass.',
       'IELTS_General', 'Full', 60, 19, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_GT_DIAGNOSTIC');

INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_GT_DIAG_R', 'IELTS General Training Diagnostic — Reading',
       'Abridged from Cambridge IELTS GT Test 3, Section 2 Passage 1.',
       'IELTS_General', 'Reading', 20, 8, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_GT_DIAG_R');

INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT 'IELTS_GT_DIAG_W', 'IELTS General Training Diagnostic — Writing',
       'Task 1 only — Cambridge IELTS GT Test 3 letter task.',
       'IELTS_General', 'Writing', 20, 1, 1, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_GT_DIAG_W');

INSERT INTO mock_exams (code, exam_type, title, description, total_duration_minutes)
SELECT 'IELTS_GT_DIAGNOSTIC', 'IELTS_General', 'IELTS General Training Diagnostic Test',
       'Abridged General Training diagnostic covering all four skills, taken before Week 2 of the Masterclass.', 60
WHERE NOT EXISTS (SELECT 1 FROM mock_exams WHERE code = 'IELTS_GT_DIAGNOSTIC');

-- ── Reading — "Qualities that make a great barista" (real Cambridge IELTS GT
--    Test 3 content, abridged from IELTS_FM3_R Q15-22, renumbered 1-8 here
--    since this is its own standalone test). Passage text itself lives in
--    diagnostic_gt_reading.php's $passages array, matching the
--    diagnostic_aca_reading.php convention — not duplicated in stimulus_text. ──
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, part_number, points, display_order)
SELECT t.id, d.qn, 'form_note_completion', d.txt,
       'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Be sure you make drinks that are ___ for the customer' txt
  UNION ALL SELECT 2, 'Ignore any ___ around you'
  UNION ALL SELECT 3, 'Clean the machine ___ regularly'
  UNION ALL SELECT 4, 'Always use ground coffee that is ___'
  UNION ALL SELECT 5, 'Too early reduces the ___'
  UNION ALL SELECT 6, 'Too late makes the coffee ___'
  UNION ALL SELECT 7, 'Ask about the customers'' ___'
  UNION ALL SELECT 8, 'Know something about the important ___ in the area'
) d
WHERE t.code = 'IELTS_GT_DIAG_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'correct' ans, 0 alt
  UNION ALL SELECT 2, 'conversation', 0
  UNION ALL SELECT 3, 'filter', 0
  UNION ALL SELECT 4, 'fresh', 0
  UNION ALL SELECT 5, 'flavour', 0
  UNION ALL SELECT 5, 'flavor', 1
  UNION ALL SELECT 6, 'bitter', 0
  UNION ALL SELECT 7, 'day', 0
  UNION ALL SELECT 8, 'issues', 0
) d
WHERE t.code = 'IELTS_GT_DIAG_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

-- ── Writing — Task 1 only, the real GT letter task from IELTS_FM3_W Q1.
--    Word-count/format instructions are folded into question_text (not the
--    instructions column) to match diagnostic_aca_writing.php's rendering —
--    that template only ever reads `instructions` back out as an image path
--    for Academic chart tasks, never displays it as text (see that file's
--    comment). Leaving `instructions` NULL here mirrors how migration 063
--    stored the Academic Task 1 prompt. ──────────────────────────────────────
INSERT INTO questions (test_id, question_number, question_type, question_text, points, display_order)
SELECT t.id, 1, 'essay',
'A magazine wants to include contributions from its readers for an article called ''The book that influenced me most''.

Write a letter to the editor of the magazine about the book that influenced you most. In your letter:
• describe what this book was about
• explain how this book influenced you
• say whether this book would be likely to influence other people

Write at least 150 words. You do NOT need to write any addresses.
Begin your letter as follows: Dear Sir or Madam,',
1.0, 1
FROM tests t
WHERE t.code = 'IELTS_GT_DIAG_W'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = 1);

-- ── Listening — deliberately no new test row. See includes/mock_test_map.php:
--    'IELTS_GT_DIAGNOSTIC'.listening points at the existing IELTS_ACA_DIAG_L
--    test code and diagnostic_aca_listening.php file, unchanged.
--
-- ── Speaking — Part 2, administered live, same handoff pattern as
--    diagnostic_aca_speaking.php (reused unmodified — pure status page with
--    no content dependency). No question row needed, same as migration 063.
