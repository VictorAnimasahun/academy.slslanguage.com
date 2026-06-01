-- ============================================================
-- Migration 016 — Seed IELTS_FM1_L (Listening Full Mock Test 1)
-- Cambridge IELTS General Training Test 1 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
-- ============================================================

-- Step 0: Add part_number column (MySQL 5.7/8.0 idempotent)
SET @_col_check = IF(
    (SELECT COUNT(*) FROM information_schema.columns
     WHERE table_schema = DATABASE() AND table_name = 'questions' AND column_name = 'part_number') = 0,
    'ALTER TABLE questions ADD COLUMN part_number TINYINT UNSIGNED NULL DEFAULT NULL',
    'SELECT 1'
);
PREPARE _add_col FROM @_col_check;
EXECUTE _add_col;
DEALLOCATE PREPARE _add_col;

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM1_L', 'IELTS Full Mock 1 — Listening',
       'Cambridge IELTS GT Test 1 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_L');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Part 1: Children's Engineering Workshops (note completion)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'Children''s Engineering Workshops' AS stimulus_text,
    'Create a cover for an ___ so they can drop it from a height without breaking it' AS question_text,
    'form_note_completion' AS question_type,
    'Write ONE WORD AND/OR A NUMBER for each answer.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1,  2, NULL, 'Take part in a competition to build the tallest ___',                                             'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  20
UNION ALL SELECT @tid, 1,  3, NULL, 'Make a ___ powered by a balloon',                                                                 'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  30
UNION ALL SELECT @tid, 1,  4, NULL, 'Build model cars, trucks and ___ and learn how to program them so they can move',                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  40
UNION ALL SELECT @tid, 1,  5, NULL, 'Take part in a competition to build the longest ___ using card and wood',                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  50
UNION ALL SELECT @tid, 1,  6, NULL, 'Create a short ___ with special software',                                                         'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  60
UNION ALL SELECT @tid, 1,  7, NULL, 'Build, ___ and program a humanoid robot',                                                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  70
UNION ALL SELECT @tid, 1,  8, NULL, 'Held on ___ from 10 am to 11 am',                                                                 'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  80
UNION ALL SELECT @tid, 1,  9, NULL, 'Building 10A, ___ Industrial Estate, Grasford',                                                   'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  90
UNION ALL SELECT @tid, 1, 10, NULL, 'Plenty of ___ is available',                                                                       'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 100

-- Part 2: Stevenson's Company MCQ (Q11–14)
UNION ALL SELECT @tid, 2, 11, 'Part 2 — Stevenson''s Company',  'Stevenson''s was founded in',                               'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 110
UNION ALL SELECT @tid, 2, 12, NULL,                              'Originally, Stevenson''s manufactured goods for',           'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 120
UNION ALL SELECT @tid, 2, 13, NULL,                              'What does the speaker say about the company premises?',     'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 130
UNION ALL SELECT @tid, 2, 14, NULL,                              'The programme for the work experience group includes',      'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 140

-- Part 2: Stevenson's Site Plan (map labelling Q15–20)
UNION ALL SELECT @tid, 2, 15, 'Part 2 — Plan of Stevenson''s site', 'Coffee room',     'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL,                                   'Warehouse',       'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL,                                   'Staff canteen',   'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL,                                   'Meeting room',    'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL,                                   'Human resources', 'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL,                                   'Boardroom',       'diagram_map_labelling', 'Write the correct letter, A–J, next to Questions 15–20.', 1.0, 200

-- Part 3: Choose TWO letters A–E (Q21–24)
UNION ALL SELECT @tid, 3, 21, 'Part 3 — Jess and Tom: art projects', 'Which TWO parts of the introductory stage to their art projects do Jess and Tom agree were useful?',  'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 210
UNION ALL SELECT @tid, 3, 22, NULL,                                    'Which TWO parts of the introductory stage to their art projects do Jess and Tom agree were useful?',  'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 220
UNION ALL SELECT @tid, 3, 23, NULL,                                    'In which TWO ways do both Jess and Tom decide to change their proposals?',                              'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 230
UNION ALL SELECT @tid, 3, 24, NULL,                                    'In which TWO ways do both Jess and Tom decide to change their proposals?',                              'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 240

-- Part 3: Match artworks to personal meanings A–H (Q25–30)
UNION ALL SELECT @tid, 3, 25,
    'Personal meanings — A: a childhood memory  B: hope for the future  C: fast movement  D: a potential threat  E: the power of colour  F: the continuity of life  G: protection of nature  H: a confused attitude to nature',
    'Falcon (Landseer)',                   'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 250
UNION ALL SELECT @tid, 3, 26, NULL, 'Fish hawk (Audubon)',              'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 260
UNION ALL SELECT @tid, 3, 27, NULL, 'Kingfisher (van Gogh)',            'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 270
UNION ALL SELECT @tid, 3, 28, NULL, 'Portrait of William Wells',        'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL, 'Vairumati (Gauguin)',              'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL, 'Portrait of Giovanni de Medici',   'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 300

-- Part 4: Stoicism note completion (Q31–40)
UNION ALL SELECT @tid, 4, 31, 'Stoicism', 'Stoicism is still relevant today because of its ___ appeal',                                                                          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310
UNION ALL SELECT @tid, 4, 32, NULL,        'The Stoics'' ideas are surprisingly well known, despite not being intended for ___',                                                   'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320
UNION ALL SELECT @tid, 4, 33, NULL,        'Epictetus said that external events cannot be controlled but the ___ people make in response can be controlled',                       'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330
UNION ALL SELECT @tid, 4, 34, NULL,        'A Stoic is someone who has a different view on experiences which others would consider as ___',                                        'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340
UNION ALL SELECT @tid, 4, 35, NULL,        'George Washington organised a ___ about Cato to motivate his men',                                                                    'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350
UNION ALL SELECT @tid, 4, 36, NULL,        'Adam Smith''s ideas on ___ were influenced by Stoicism',                                                                              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360
UNION ALL SELECT @tid, 4, 37, NULL,        'the treatment for ___ is based on ideas from Stoicism',                                                                               'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370
UNION ALL SELECT @tid, 4, 38, NULL,        'people learn to base their thinking on ___',                                                                                          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380
UNION ALL SELECT @tid, 4, 39, NULL,        'In business, people benefit from Stoicism by identifying obstacles as ___',                                                           'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390
UNION ALL SELECT @tid, 4, 40, NULL,        'It requires a lot of ___ but Stoicism can help people to lead a good life',                                                           'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400

) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- ============================================================
-- Step 3: Resolve question IDs
-- ============================================================
SET @q1  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  1);
SET @q2  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  2);
SET @q3  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  3);
SET @q4  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  4);
SET @q5  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  5);
SET @q6  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  6);
SET @q7  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  7);
SET @q8  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  8);
SET @q9  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  9);
SET @q10 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 10);
SET @q11 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11);
SET @q12 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12);
SET @q13 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13);
SET @q14 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14);
SET @q15 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 15);
SET @q16 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 16);
SET @q17 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 17);
SET @q18 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 18);
SET @q19 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 19);
SET @q20 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 20);
SET @q21 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21);
SET @q22 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22);
SET @q23 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23);
SET @q24 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24);
SET @q25 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 25);
SET @q26 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 26);
SET @q27 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 27);
SET @q28 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 28);
SET @q29 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 29);
SET @q30 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 30);
SET @q31 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 31);
SET @q32 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 32);
SET @q33 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 33);
SET @q34 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 34);
SET @q35 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 35);
SET @q36 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 36);
SET @q37 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 37);
SET @q38 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 38);
SET @q39 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 39);
SET @q40 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 40);

-- ============================================================
-- Step 4: Insert MCQ + matching options
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- Q11: Stevenson's was founded in (correct: C)
SELECT @q11 AS question_id, 'A' AS option_label, '1923.' AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q11, 'B', '1924.', 0, 20
UNION ALL SELECT @q11, 'C', '1926.', 1, 30

-- Q12: manufactured goods for (correct: A)
UNION ALL SELECT @q12, 'A', 'the healthcare industry.', 1, 10
UNION ALL SELECT @q12, 'B', 'the automotive industry.', 0, 20
UNION ALL SELECT @q12, 'C', 'the machine tools industry.', 0, 30

-- Q13: company premises (correct: B)
UNION ALL SELECT @q13, 'A', 'The company has recently moved.', 0, 10
UNION ALL SELECT @q13, 'B', 'The company has no plans to move.', 1, 20
UNION ALL SELECT @q13, 'C', 'The company is going to move shortly.', 0, 30

-- Q14: work experience programme (correct: C)
UNION ALL SELECT @q14, 'A', 'time to do research.', 0, 10
UNION ALL SELECT @q14, 'B', 'meetings with a teacher.', 0, 20
UNION ALL SELECT @q14, 'C', 'talks by staff.', 1, 30

-- Q21–22: introductory stage (correct: C and E)
UNION ALL SELECT @q21, 'A', 'the Bird Park visit', 0, 10
UNION ALL SELECT @q21, 'B', 'the workshop sessions', 0, 20
UNION ALL SELECT @q21, 'C', 'the Natural History Museum visit', 1, 30
UNION ALL SELECT @q21, 'D', 'the projects done in previous years', 0, 40
UNION ALL SELECT @q21, 'E', 'the handouts with research sources', 1, 50
UNION ALL SELECT @q22, 'A', 'the Bird Park visit', 0, 10
UNION ALL SELECT @q22, 'B', 'the workshop sessions', 0, 20
UNION ALL SELECT @q22, 'C', 'the Natural History Museum visit', 1, 30
UNION ALL SELECT @q22, 'D', 'the projects done in previous years', 0, 40
UNION ALL SELECT @q22, 'E', 'the handouts with research sources', 1, 50

-- Q23–24: change proposals (correct: B and E)
UNION ALL SELECT @q23, 'A', 'by giving a rationale for their action plans', 0, 10
UNION ALL SELECT @q23, 'B', 'by being less specific about the outcome', 1, 20
UNION ALL SELECT @q23, 'C', 'by adding a video diary presentation', 0, 30
UNION ALL SELECT @q23, 'D', 'by providing a timeline and a mind map', 0, 40
UNION ALL SELECT @q23, 'E', 'by making their notes more evaluative', 1, 50
UNION ALL SELECT @q24, 'A', 'by giving a rationale for their action plans', 0, 10
UNION ALL SELECT @q24, 'B', 'by being less specific about the outcome', 1, 20
UNION ALL SELECT @q24, 'C', 'by adding a video diary presentation', 0, 30
UNION ALL SELECT @q24, 'D', 'by providing a timeline and a mind map', 0, 40
UNION ALL SELECT @q24, 'E', 'by making their notes more evaluative', 1, 50

-- Q25–30: personal meanings A–H
UNION ALL SELECT @q25, 'A', 'a childhood memory', 0, 10 UNION ALL SELECT @q25, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q25, 'C', 'fast movement', 0, 30 UNION ALL SELECT @q25, 'D', 'a potential threat', 1, 40 UNION ALL SELECT @q25, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q25, 'F', 'the continuity of life', 0, 60 UNION ALL SELECT @q25, 'G', 'protection of nature', 0, 70 UNION ALL SELECT @q25, 'H', 'a confused attitude to nature', 0, 80
UNION ALL SELECT @q26, 'A', 'a childhood memory', 0, 10 UNION ALL SELECT @q26, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q26, 'C', 'fast movement', 1, 30 UNION ALL SELECT @q26, 'D', 'a potential threat', 0, 40 UNION ALL SELECT @q26, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q26, 'F', 'the continuity of life', 0, 60 UNION ALL SELECT @q26, 'G', 'protection of nature', 0, 70 UNION ALL SELECT @q26, 'H', 'a confused attitude to nature', 0, 80
UNION ALL SELECT @q27, 'A', 'a childhood memory', 1, 10 UNION ALL SELECT @q27, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q27, 'C', 'fast movement', 0, 30 UNION ALL SELECT @q27, 'D', 'a potential threat', 0, 40 UNION ALL SELECT @q27, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q27, 'F', 'the continuity of life', 0, 60 UNION ALL SELECT @q27, 'G', 'protection of nature', 0, 70 UNION ALL SELECT @q27, 'H', 'a confused attitude to nature', 0, 80
UNION ALL SELECT @q28, 'A', 'a childhood memory', 0, 10 UNION ALL SELECT @q28, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q28, 'C', 'fast movement', 0, 30 UNION ALL SELECT @q28, 'D', 'a potential threat', 0, 40 UNION ALL SELECT @q28, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q28, 'F', 'the continuity of life', 0, 60 UNION ALL SELECT @q28, 'G', 'protection of nature', 0, 70 UNION ALL SELECT @q28, 'H', 'a confused attitude to nature', 1, 80
UNION ALL SELECT @q29, 'A', 'a childhood memory', 0, 10 UNION ALL SELECT @q29, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q29, 'C', 'fast movement', 0, 30 UNION ALL SELECT @q29, 'D', 'a potential threat', 0, 40 UNION ALL SELECT @q29, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q29, 'F', 'the continuity of life', 1, 60 UNION ALL SELECT @q29, 'G', 'protection of nature', 0, 70 UNION ALL SELECT @q29, 'H', 'a confused attitude to nature', 0, 80
UNION ALL SELECT @q30, 'A', 'a childhood memory', 0, 10 UNION ALL SELECT @q30, 'B', 'hope for the future', 0, 20 UNION ALL SELECT @q30, 'C', 'fast movement', 0, 30 UNION ALL SELECT @q30, 'D', 'a potential threat', 0, 40 UNION ALL SELECT @q30, 'E', 'the power of colour', 0, 50 UNION ALL SELECT @q30, 'F', 'the continuity of life', 0, 60 UNION ALL SELECT @q30, 'G', 'protection of nature', 1, 70 UNION ALL SELECT @q30, 'H', 'a confused attitude to nature', 0, 80

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Part 1 Q1–10
SELECT @q1  AS question_id, 'egg'        AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2,  'tower',      0, 0
UNION ALL SELECT @q3,  'car',        0, 0
UNION ALL SELECT @q4,  'animals',    0, 0
UNION ALL SELECT @q5,  'bridge',     0, 0
UNION ALL SELECT @q6,  'movie',      0, 0
UNION ALL SELECT @q6,  'film',       0, 1
UNION ALL SELECT @q7,  'decorate',   0, 0
UNION ALL SELECT @q8,  'Wednesdays', 0, 0
UNION ALL SELECT @q8,  'wednesdays', 0, 1
UNION ALL SELECT @q9,  'Fradstone',  0, 0
UNION ALL SELECT @q9,  'fradstone',  0, 1
UNION ALL SELECT @q10, 'parking',    0, 0

-- Part 2 map Q15–20 (H, C, G, B, I, A)
UNION ALL SELECT @q15, 'H', 0, 0 UNION ALL SELECT @q15, 'h', 0, 1
UNION ALL SELECT @q16, 'C', 0, 0 UNION ALL SELECT @q16, 'c', 0, 1
UNION ALL SELECT @q17, 'G', 0, 0 UNION ALL SELECT @q17, 'g', 0, 1
UNION ALL SELECT @q18, 'B', 0, 0 UNION ALL SELECT @q18, 'b', 0, 1
UNION ALL SELECT @q19, 'I', 0, 0 UNION ALL SELECT @q19, 'i', 0, 1
UNION ALL SELECT @q20, 'A', 0, 0 UNION ALL SELECT @q20, 'a', 0, 1

-- Part 3 matching Q25–30 (D, C, A, H, F, G)
UNION ALL SELECT @q25, 'D', 0, 0 UNION ALL SELECT @q25, 'd', 0, 1
UNION ALL SELECT @q26, 'C', 0, 0 UNION ALL SELECT @q26, 'c', 0, 1
UNION ALL SELECT @q27, 'A', 0, 0 UNION ALL SELECT @q27, 'a', 0, 1
UNION ALL SELECT @q28, 'H', 0, 0 UNION ALL SELECT @q28, 'h', 0, 1
UNION ALL SELECT @q29, 'F', 0, 0 UNION ALL SELECT @q29, 'f', 0, 1
UNION ALL SELECT @q30, 'G', 0, 0 UNION ALL SELECT @q30, 'g', 0, 1

-- Part 4 Q31–40
UNION ALL SELECT @q31, 'practical',   0, 0
UNION ALL SELECT @q32, 'publication', 0, 0
UNION ALL SELECT @q33, 'choices',     0, 0
UNION ALL SELECT @q34, 'negative',    0, 0
UNION ALL SELECT @q35, 'play',        0, 0
UNION ALL SELECT @q36, 'capitalism',  0, 0
UNION ALL SELECT @q37, 'depression',  0, 0
UNION ALL SELECT @q38, 'logic',       0, 0
UNION ALL SELECT @q39, 'opportunity', 0, 0
UNION ALL SELECT @q40, 'practice',    0, 0
UNION ALL SELECT @q40, 'practise',    0, 1

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, 76 options, 36 correct answers
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
