-- ============================================================
-- Migration 089 — Seed IELTS Academic Reading Practice Test 1
-- (test_code = IELTS_PT_R_ACA_001, file =
--  resources/practice_tests/ielts_reading_academic_001.php)
--
-- Genuinely Academic content — built from 7 official IELTS.org "Academic
-- Reading sample task" PDFs (one per question type), compiled into 5 parts
-- covering 30 questions. All passage/question/answer text is verbatim from
-- the official material; question numbers were renumbered 1-30 sequentially
-- (each source PDF used its own original numbering in isolation — see the
-- PHP file's own header comment for the full breakdown of which PDFs pair
-- into a shared passage). This is a compiled sample set, not one
-- continuous 60-minute/3-passage Cambridge-style exam.
--
-- The PHP file renders all passage/question text itself from a hardcoded
-- array — this migration only needs to seed the answer key (questions +
-- question_correct_answers / question_options), which loadTestAnswers()
-- reads by test_code + question_number.
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_ACA_001',
       'IELTS Academic Reading — Practice Test 1',
       'Compiled from 7 official IELTS.org Academic Reading sample tasks (one per question type) into 5 short passages, 30 questions total.',
       'IELTS_Academic', 40, 30, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_ACA_001');

-- ── Part 1: Government Policy and the Environment (Q1-8) ──────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, 1, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Section A' txt, 'matching' qtype
  UNION ALL SELECT 2, 'Section B', 'matching'
  UNION ALL SELECT 3, 'Section C', 'matching'
  UNION ALL SELECT 4, 'Section D', 'matching'
  UNION ALL SELECT 5, 'Section F', 'matching'
  UNION ALL SELECT 6, 'Research completed in 1982 found that in the United States soil erosion', 'multiple_choice_single'
  UNION ALL SELECT 7, 'By the mid-1980s, farmers in Denmark', 'multiple_choice_single'
  UNION ALL SELECT 8, 'Which one of the following increased in New Zealand after 1984?', 'multiple_choice_single'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'v' ans UNION ALL SELECT 2, 'vii' UNION ALL SELECT 3, 'ii'
  UNION ALL SELECT 4, 'iv' UNION ALL SELECT 5, 'i'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 6 qn, 'C' letter UNION ALL SELECT 7, 'B' UNION ALL SELECT 8, 'D'
) ans ON ans.qn = q.question_number
JOIN (
  SELECT 6 qn, 'A' lbl, 'reduced the productivity of farmland by 20 per cent.' txt, 1 ord
  UNION ALL SELECT 6, 'B', 'was almost as severe as in India and China.', 2
  UNION ALL SELECT 6, 'C', 'was causing significant damage to 20 per cent of farmland.', 3
  UNION ALL SELECT 6, 'D', 'could be reduced by converting cultivated land to meadow or forest.', 4
  UNION ALL SELECT 7, 'A', 'used 50 per cent less fertiliser than Dutch farmers.', 1
  UNION ALL SELECT 7, 'B', 'used twice as much fertiliser as they had in 1960.', 2
  UNION ALL SELECT 7, 'C', 'applied fertiliser much more frequently than in 1960.', 3
  UNION ALL SELECT 7, 'D', 'more than doubled the amount of pesticide they used in just 3 years.', 4
  UNION ALL SELECT 8, 'A', 'farm incomes', 1
  UNION ALL SELECT 8, 'B', 'use of fertiliser', 2
  UNION ALL SELECT 8, 'C', 'over-stocking', 3
  UNION ALL SELECT 8, 'D', 'farm diversification', 4
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number IN (6, 7, 8)
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

-- ── Part 2: The Motor Car (Q9-14) ──────────────────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, 'matching', 2, d.qn
FROM tests t,
(
  SELECT 9 qn, 'a comparison of past and present transportation methods' txt
  UNION ALL SELECT 10, 'how driving habits contribute to road problems'
  UNION ALL SELECT 11, 'the relative merits of cars and public transport'
  UNION ALL SELECT 12, 'the writer''s prediction on future solutions'
  UNION ALL SELECT 13, 'the increasing use of motor vehicles'
  UNION ALL SELECT 14, 'the impact of the car on city development'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 9 qn, 'c' ans UNION ALL SELECT 10, 'f' UNION ALL SELECT 11, 'e'
  UNION ALL SELECT 12, 'h' UNION ALL SELECT 13, 'a' UNION ALL SELECT 14, 'd'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id);

-- ── Part 3: The Risks of Cigarette Smoke (Q15-18) ──────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, 'yes_no_not_given', 3, d.qn
FROM tests t,
(
  SELECT 15 qn, 'Thirty per cent of deaths in the United States are caused by smoking-related diseases.' txt
  UNION ALL SELECT 16, 'If one partner in a marriage smokes, the other is likely to take up smoking.'
  UNION ALL SELECT 17, 'Teenagers whose parents smoke are at risk of getting lung cancer at some time during their lives.'
  UNION ALL SELECT 18, 'Opponents of smoking financed the UCSF study.'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 15 qn, 'no' ans UNION ALL SELECT 16, 'not given'
  UNION ALL SELECT 17, 'yes' UNION ALL SELECT 18, 'not given'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id);

-- ── Part 4: The History of Rockets (Q19-22) ────────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, 'matching', 4, d.qn
FROM tests t,
(
  SELECT 19 qn, 'black powder' txt
  UNION ALL SELECT 20, 'rocket-propelled arrows for fighting'
  UNION ALL SELECT 21, 'rockets as war weapons'
  UNION ALL SELECT 22, 'the rocket launcher'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, 0
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 19 qn, 'a' ans UNION ALL SELECT 20, 'a' UNION ALL SELECT 21, 'b' UNION ALL SELECT 22, 'e'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id);

-- ── Part 5: Dung Beetles (Q23-30) ───────────────────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, 5, d.qn
FROM tests t,
(
  SELECT 23 qn, 'Diagram label (deepest tunnel, ~30 cm)' txt, 'diagram_map_labelling' qtype
  UNION ALL SELECT 24, 'Diagram label (middle-depth tunnel, ~20 cm)', 'diagram_map_labelling'
  UNION ALL SELECT 25, 'Diagram label (shallowest tunnel)', 'diagram_map_labelling'
  UNION ALL SELECT 26, 'Table: Spanish — preferred climate', 'table_completion'
  UNION ALL SELECT 27, 'Table: Spanish — start of active period', 'table_completion'
  UNION ALL SELECT 28, 'Table: Spanish — generations per year', 'table_completion'
  UNION ALL SELECT 29, 'Table: South African ball roller — preferred climate', 'table_completion'
  UNION ALL SELECT 30, 'Table: South African ball roller — complementary species', 'table_completion'
) d
WHERE t.code = 'IELTS_PT_R_ACA_001'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 23 qn, 'south african' ans, 0 alt
  UNION ALL SELECT 24, 'french', 0
  UNION ALL SELECT 25, 'spanish', 0
  UNION ALL SELECT 26, 'temperate', 0
  UNION ALL SELECT 27, 'early spring', 0
  UNION ALL SELECT 28, 'two to five', 0
  UNION ALL SELECT 28, '2-5', 1
  UNION ALL SELECT 28, '2 to 5', 1
  UNION ALL SELECT 29, 'sub-tropical', 0
  UNION ALL SELECT 29, 'subtropical', 1
  UNION ALL SELECT 30, 'south african tunneling', 0
  UNION ALL SELECT 30, 'south african tunnelling', 1
) d
WHERE t.code = 'IELTS_PT_R_ACA_001' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

-- ── Verify ──────────────────────────────────────────────────────────────
-- SELECT question_number, question_type FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code='IELTS_PT_R_ACA_001' ORDER BY question_number;
-- SELECT q.question_number, qca.answer_text, qca.is_alternative FROM questions q JOIN question_correct_answers qca ON qca.question_id=q.id JOIN tests t ON t.id=q.test_id WHERE t.code='IELTS_PT_R_ACA_001' ORDER BY q.question_number;
-- SELECT q.question_number, qo.option_label, qo.is_correct FROM questions q JOIN question_options qo ON qo.question_id=q.id JOIN tests t ON t.id=q.test_id WHERE t.code='IELTS_PT_R_ACA_001' ORDER BY q.question_number;
