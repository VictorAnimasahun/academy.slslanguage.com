-- ============================================================
-- Migration 012 — Seed IELTS_PT_L_001 (Listening Practice Test 1)
-- IDEMPOTENT: safe to re-run.
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_L_001',
       'IELTS Listening – Practice Test 1',
       'A full 40-question IELTS Listening practice test: recruitment agency, Isle of Man holiday, birth order research, and the eucalyptus tree.',
       'IELTS',
       30,
       40,
       1, 0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_L_001');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001' LIMIT 1);

-- Step 2: Wipe child records (idempotent)
DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- Step 3: Insert questions
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
VALUES
-- Part 1 Q1–10: Form completion (Bankside Recruitment Agency)
(@tid,  1, 'Part 1 – Bankside Recruitment Agency', 'Name of agent: Becky ___',                                    'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  10),
(@tid,  2, 'Part 1 – Bankside Recruitment Agency', 'Best to call her in the ___',                                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  20),
(@tid,  3, 'Part 1 – Bankside Recruitment Agency', 'Must have good ___ skills',                                    'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  30),
(@tid,  4, 'Part 1 – Bankside Recruitment Agency', 'Jobs are usually for at least one ___',                        'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  40),
(@tid,  5, 'Part 1 – Bankside Recruitment Agency', 'Pay is usually £ ___ per hour',                               'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  50),
(@tid,  6, 'Part 1 – Bankside Recruitment Agency', 'Wear a ___ to the interview',                                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  60),
(@tid,  7, 'Part 1 – Bankside Recruitment Agency', 'Must bring your ___ to the interview',                         'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  70),
(@tid,  8, 'Part 1 – Bankside Recruitment Agency', "They will ask questions about each applicant's ___",           'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  80),
(@tid,  9, 'Part 1 – Bankside Recruitment Agency', 'The ___ you receive at interview will benefit you',            'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0,  90),
(@tid, 10, 'Part 1 – Bankside Recruitment Agency', 'Less ___ is involved in applying for jobs',                    'form_note_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 100),

-- Part 2 Q11–14: MCQ (Matthews Island Holidays)
(@tid, 11, 'Part 2 – Matthews Island Holidays', 'According to the speaker, the company',                                                            'multiple_choice_single', 'Choose A, B or C.', 1.0, 110),
(@tid, 12, 'Part 2 – Matthews Island Holidays', 'Where can customers meet the tour manager before travelling to the Isle of Man?',                  'multiple_choice_single', 'Choose A, B or C.', 1.0, 120),
(@tid, 13, 'Part 2 – Matthews Island Holidays', 'How many lunches are included in the price of the holiday?',                                       'multiple_choice_single', 'Choose A, B or C.', 1.0, 130),
(@tid, 14, 'Part 2 – Matthews Island Holidays', 'Customers have to pay extra for',                                                                  'multiple_choice_single', 'Choose A, B or C.', 1.0, 140),

-- Part 2 Q15–20: Table completion (Isle of Man timetable)
(@tid, 15, 'Part 2 – Isle of Man timetable', 'Hotel dining room has view of the ___',                                 'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 150),
(@tid, 16, 'Part 2 – Isle of Man timetable', 'Tynwald may have been founded in ___',                                  'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 160),
(@tid, 17, 'Part 2 – Isle of Man timetable', 'Train to the ___ of Snaefell',                                         'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 170),
(@tid, 18, 'Part 2 – Isle of Man timetable', 'Company provides a ___ for local transport and heritage sites',         'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 180),
(@tid, 19, 'Part 2 – Isle of Man timetable', 'Take the ___ railway train from Douglas to Port Erin',                 'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 190),
(@tid, 20, 'Part 2 – Isle of Man timetable', 'Coach to Castletown – former ___ has old castle',                      'table_completion', 'Write ONE WORD AND/OR A NUMBER.', 1.0, 200),

-- Part 3 Q21–26: Matching (birth order personality traits A–H)
(@tid, 21, 'Part 3 – Birth order / personality traits', 'the eldest child',              'matching', 'Write the correct letter, A–H.', 1.0, 210),
(@tid, 22, 'Part 3 – Birth order / personality traits', 'a middle child',                'matching', 'Write the correct letter, A–H.', 1.0, 220),
(@tid, 23, 'Part 3 – Birth order / personality traits', 'the youngest child',            'matching', 'Write the correct letter, A–H.', 1.0, 230),
(@tid, 24, 'Part 3 – Birth order / personality traits', 'a twin',                        'matching', 'Write the correct letter, A–H.', 1.0, 240),
(@tid, 25, 'Part 3 – Birth order / personality traits', 'an only child',                 'matching', 'Write the correct letter, A–H.', 1.0, 250),
(@tid, 26, 'Part 3 – Birth order / personality traits', 'a child with much older siblings', 'matching', 'Write the correct letter, A–H.', 1.0, 260),

-- Part 3 Q27–28: MCQ
(@tid, 27, 'Part 3 – Sibling rivalry research', 'What do the speakers say about the evidence relating to birth order and academic success?',         'multiple_choice_single', 'Choose A, B or C.', 1.0, 270),
(@tid, 28, 'Part 3 – Sibling rivalry research', "What does Ruth think is surprising about the difference in oldest children's academic performance?",'multiple_choice_single', 'Choose A, B or C.', 1.0, 280),

-- Part 3 Q29–30: Multi-select pair (choose TWO from A–E)
(@tid, 29, 'Part 3 – Sibling rivalry / valuable experiences', 'Which TWO experiences of sibling rivalry do the speakers agree has been valuable? (Answer 1)', 'multi_select_pair', 'Choose TWO letters, A–E.', 1.0, 290),
(@tid, 30, 'Part 3 – Sibling rivalry / valuable experiences', 'Which TWO experiences of sibling rivalry do the speakers agree has been valuable? (Answer 2)', 'multi_select_pair', 'Choose TWO letters, A–E.', 1.0, 300),

-- Part 4 Q31–40: Form completion (Eucalyptus tree)
(@tid, 31, 'Part 4 – The Eucalyptus Tree in Australia', 'It provides ___ and food for a wide range of species',                                      'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 310),
(@tid, 32, 'Part 4 – The Eucalyptus Tree in Australia', 'Its leaves provide ___ which is used to make a disinfectant',                               'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 320),
(@tid, 33, 'Part 4 – The Eucalyptus Tree in Australia', 'Cause of Mundulla Yellows: lime used for making ___ was absorbed',                          'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 330),
(@tid, 34, 'Part 4 – The Eucalyptus Tree in Australia', 'Cause of Bell-miner die-back: ___ feed on eucalyptus leaves',                               'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 340),
(@tid, 35, 'Part 4 – The Eucalyptus Tree in Australia', 'High-frequency bushfires result in the growth of ___',                                      'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 350),
(@tid, 36, 'Part 4 – The Eucalyptus Tree in Australia', 'Mid-frequency bushfires make more ___ available to the trees',                              'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 360),
(@tid, 37, 'Part 4 – The Eucalyptus Tree in Australia', 'Mid-frequency bushfires maintain the quality of the ___',                                   'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 370),
(@tid, 38, 'Part 4 – The Eucalyptus Tree in Australia', 'Low-frequency bushfires result in the growth of ___ rainforest',                            'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 380),
(@tid, 39, 'Part 4 – The Eucalyptus Tree in Australia', 'The resulting ecosystem is a ___ one',                                                      'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 390),
(@tid, 40, 'Part 4 – The Eucalyptus Tree in Australia', 'It is an ideal environment for the ___ of the bell-miner',                                  'form_note_completion', 'Write ONE WORD ONLY.', 1.0, 400)
;

-- Step 4: Set question ID variables
SET @q1  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 1  LIMIT 1);
SET @q2  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 2  LIMIT 1);
SET @q3  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 3  LIMIT 1);
SET @q4  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 4  LIMIT 1);
SET @q5  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 5  LIMIT 1);
SET @q6  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 6  LIMIT 1);
SET @q7  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 7  LIMIT 1);
SET @q8  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 8  LIMIT 1);
SET @q9  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 9  LIMIT 1);
SET @q10 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 10 LIMIT 1);
SET @q11 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11 LIMIT 1);
SET @q12 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12 LIMIT 1);
SET @q13 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13 LIMIT 1);
SET @q14 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14 LIMIT 1);
SET @q15 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 15 LIMIT 1);
SET @q16 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 16 LIMIT 1);
SET @q17 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 17 LIMIT 1);
SET @q18 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 18 LIMIT 1);
SET @q19 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 19 LIMIT 1);
SET @q20 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 20 LIMIT 1);
SET @q21 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21 LIMIT 1);
SET @q22 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22 LIMIT 1);
SET @q23 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23 LIMIT 1);
SET @q24 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24 LIMIT 1);
SET @q25 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 25 LIMIT 1);
SET @q26 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 26 LIMIT 1);
SET @q27 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 27 LIMIT 1);
SET @q28 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 28 LIMIT 1);
SET @q29 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 29 LIMIT 1);
SET @q30 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 30 LIMIT 1);
SET @q31 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 31 LIMIT 1);
SET @q32 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 32 LIMIT 1);
SET @q33 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 33 LIMIT 1);
SET @q34 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 34 LIMIT 1);
SET @q35 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 35 LIMIT 1);
SET @q36 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 36 LIMIT 1);
SET @q37 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 37 LIMIT 1);
SET @q38 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 38 LIMIT 1);
SET @q39 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 39 LIMIT 1);
SET @q40 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 40 LIMIT 1);

-- Step 5: Correct answers for text/fill/matching questions
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
-- Part 1 (Q1–10)
(@q1,  'jamieson',      0, 0),
(@q2,  'afternoon',     0, 0),
(@q3,  'communication', 0, 0),
(@q4,  'week',          0, 0),
(@q5,  '10',            0, 0),
(@q5,  'ten',           0, 1),
(@q6,  'suit',          0, 0),
(@q7,  'passport',      0, 0),
(@q8,  'personality',   0, 0),
(@q9,  'feedback',      0, 0),
(@q10, 'time',          0, 0),

-- Part 2 table fill (Q15–20)
(@q15, 'river',   0, 0),
(@q16, '1422',    0, 0),
(@q17, 'top',     0, 0),
(@q18, 'pass',    0, 0),
(@q19, 'steam',   0, 0),
(@q20, 'capital', 0, 0),

-- Part 3 matching (Q21–26)
(@q21, 'g', 0, 0),
(@q22, 'f', 0, 0),
(@q23, 'a', 0, 0),
(@q24, 'e', 0, 0),
(@q25, 'b', 0, 0),
(@q26, 'c', 0, 0),

-- Part 3 multi-select pair (Q29–30): both 'b' and 'd' are valid in either slot
(@q29, 'b', 0, 0),
(@q29, 'd', 0, 1),
(@q30, 'b', 0, 0),
(@q30, 'd', 0, 1),

-- Part 4 form fill (Q31–40)
(@q31, 'shelter',  0, 0),
(@q32, 'oil',      0, 0),
(@q33, 'roads',    0, 0),
(@q34, 'insects',  0, 0),
(@q35, 'grass',    0, 0),
(@q35, 'grasses',  0, 1),
(@q36, 'water',    0, 0),
(@q37, 'soil',     0, 0),
(@q38, 'dry',      0, 0),
(@q39, 'simple',   0, 0),
(@q40, 'nest',     0, 0),
(@q40, 'nests',    0, 1)
;

-- Step 6: MCQ options (Q11–14, Q27–28)
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
-- Q11
(@q11, 'A', 'has been in business for longer than most of its competitors.',  1, 10),
(@q11, 'B', 'arranges holidays to more destinations than its competitors.',   0, 20),
(@q11, 'C', 'has more customers than its competitors.',                        0, 30),
-- Q12
(@q12, 'A', 'Liverpool', 0, 10),
(@q12, 'B', 'Heysham',   1, 20),
(@q12, 'C', 'Luton',     0, 30),
-- Q13
(@q13, 'A', 'three', 1, 10),
(@q13, 'B', 'four',  0, 20),
(@q13, 'C', 'five',  0, 30),
-- Q14
(@q14, 'A', 'guaranteeing themselves a larger room.', 0, 10),
(@q14, 'B', 'booking at short notice.',               0, 20),
(@q14, 'C', 'transferring to another date.',          1, 30),
-- Q27
(@q27, 'A', 'There is conflicting evidence about whether oldest children perform best in intelligence tests.',               0, 10),
(@q27, 'B', 'There is little doubt that birth order has less influence on academic achievement than socio-economic status.', 0, 20),
(@q27, 'C', 'Some studies have neglected to include important factors such as family size.',                                 1, 30),
-- Q28
(@q28, 'A', 'It is mainly thanks to their roles as teachers for their younger siblings.',        1, 10),
(@q28, 'B', 'The advantages they have only lead to a slightly higher level of achievement.',     0, 20),
(@q28, 'C', 'The extra parental attention they receive at a young age makes little difference.', 0, 30)
;
