-- ============================================================
-- Migration 009 — Seed IELTS Listening Practice Test 1
-- Test code: IELTS_PT_L_001
-- 40 questions across Parts 1–4
-- Run on LOCAL first, then LIVE. See migration_log.md.
-- ============================================================

-- Guard: skip if already seeded
SET @existing = (SELECT COUNT(*) FROM tests WHERE code = 'IELTS_PT_L_001');

-- Insert test record
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_L_001',
       'IELTS Listening – Practice Test 1',
       'Full 40-question IELTS Listening practice test covering Parts 1–4: form/note completion, multiple choice, matching, and table completion.',
       'IELTS',
       30,
       40,
       1,
       0,
       'Listening'
WHERE @existing = 0;

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001');

-- ============================================================
-- PART 1 – Questions 1–10 (form_note_completion)
-- Topic: Bankside Recruitment Agency
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 1,  'Bankside Recruitment Agency – agent details',          'Name of agent: Becky ___',                                      'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 10),
(@tid, 2,  'Bankside Recruitment Agency – contact',               'Best to call her in the ___',                                   'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 20),
(@tid, 3,  'Bankside Recruitment Agency – typical jobs',          'Must have good ___ skills',                                     'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 30),
(@tid, 4,  'Bankside Recruitment Agency – typical jobs',          'Jobs are usually for at least one ___',                         'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 40),
(@tid, 5,  'Bankside Recruitment Agency – typical jobs',          'Pay is usually £___ per hour',                                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 50),
(@tid, 6,  'Bankside Recruitment Agency – registration process',  'Wear a ___ to the interview',                                   'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 60),
(@tid, 7,  'Bankside Recruitment Agency – registration process',  'Must bring your ___ to the interview',                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 70),
(@tid, 8,  'Bankside Recruitment Agency – registration process',  'They will ask questions about each applicant''s ___',            'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 80),
(@tid, 9,  'Bankside Recruitment Agency – advantages',            'The ___ you receive at interview will benefit you',              'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 90),
(@tid, 10, 'Bankside Recruitment Agency – advantages',            'Less ___ is involved in applying for jobs',                     'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 100);

-- Part 1 correct answers
SET @q1  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 1);
SET @q2  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 2);
SET @q3  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 3);
SET @q4  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 4);
SET @q5  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 5);
SET @q6  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 6);
SET @q7  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 7);
SET @q8  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 8);
SET @q9  = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 9);
SET @q10 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 10);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
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
(@q10, 'time',          0, 0);

-- ============================================================
-- PART 2 – Questions 11–14 (multiple_choice_single)
-- Topic: Matthews Island Holidays
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 11, 'Matthews Island Holidays', 'According to the speaker, the company',                                                          'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 110),
(@tid, 12, 'Matthews Island Holidays', 'Where can customers meet the tour manager before travelling to the Isle of Man?',                 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 120),
(@tid, 13, 'Matthews Island Holidays', 'How many lunches are included in the price of the holiday?',                                      'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 130),
(@tid, 14, 'Matthews Island Holidays', 'Customers have to pay extra for',                                                                 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 140);

SET @q11 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11);
SET @q12 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12);
SET @q13 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13);
SET @q14 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q11, 'A', 'has been in business for longer than most of its competitors.',  1, 10),
(@q11, 'B', 'arranges holidays to more destinations than its competitors.',   0, 20),
(@q11, 'C', 'has more customers than its competitors.',                        0, 30),
(@q12, 'A', 'Liverpool',  0, 10),
(@q12, 'B', 'Heysham',    1, 20),
(@q12, 'C', 'Luton',      0, 30),
(@q13, 'A', 'three',      1, 10),
(@q13, 'B', 'four',       0, 20),
(@q13, 'C', 'five',       0, 30),
(@q14, 'A', 'guaranteeing themselves a larger room.', 0, 10),
(@q14, 'B', 'booking at short notice.',               0, 20),
(@q14, 'C', 'transferring to another date.',          1, 30);

-- ============================================================
-- PART 2 – Questions 15–20 (table_completion)
-- Topic: Timetable for Isle of Man holiday
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 15, 'Isle of Man Timetable – Day 1',  'Hotel dining room has view of the ___',                         'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 150),
(@tid, 16, 'Isle of Man Timetable – Day 2',  'Tynwald may have been founded in ___ (not 979)',                'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 160),
(@tid, 17, 'Isle of Man Timetable – Day 3',  'Train to the ___ of Snaefell',                                 'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 170),
(@tid, 18, 'Isle of Man Timetable – Day 4',  'Company provides a ___ for local transport and heritage sites', 'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 180),
(@tid, 19, 'Isle of Man Timetable – Day 5',  'Take the ___ railway train from Douglas to Port Erin',          'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 190),
(@tid, 20, 'Isle of Man Timetable – Day 5',  'Former ___ has old castle (Castletown)',                        'table_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 200);

SET @q15 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 15);
SET @q16 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 16);
SET @q17 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 17);
SET @q18 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 18);
SET @q19 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 19);
SET @q20 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 20);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
(@q15, 'river',   0, 0),
(@q16, '1422',    0, 0),
(@q17, 'top',     0, 0),
(@q18, 'pass',    0, 0),
(@q19, 'steam',   0, 0),
(@q20, 'capital', 0, 0);

-- ============================================================
-- PART 3 – Questions 21–26 (matching)
-- Topic: Personality traits by birth order
-- Options: A=outgoing, B=selfish, C=independent, D=attention-seeking,
--          E=introverted, F=co-operative, G=caring, H=competitive
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 21, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'the eldest child',               'matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 210),
(@tid, 22, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'a middle child',                 'matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 220),
(@tid, 23, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'the youngest child',             'matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 230),
(@tid, 24, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'a twin',                         'matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 240),
(@tid, 25, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'an only child',                  'matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 250),
(@tid, 26, 'Personality Traits box: A=outgoing B=selfish C=independent D=attention-seeking E=introverted F=co-operative G=caring H=competitive', 'a child with much older siblings','matching', 'Choose SIX answers from the box (A–H) and write the correct letter, A–H, next to Questions 21–26.', 1.0, 260);

SET @q21 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21);
SET @q22 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22);
SET @q23 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23);
SET @q24 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24);
SET @q25 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 25);
SET @q26 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 26);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
(@q21, 'g', 0, 0),
(@q22, 'f', 0, 0),
(@q23, 'a', 0, 0),
(@q24, 'e', 0, 0),
(@q25, 'b', 0, 0),
(@q26, 'c', 0, 0);

-- ============================================================
-- PART 3 – Questions 27–28 (multiple_choice_single)
-- Topic: Birth order and academic success
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 27, 'Birth order and academic success', 'What do the speakers say about the evidence relating to birth order and academic success?',            'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 270),
(@tid, 28, 'Birth order and academic success', 'What does Ruth think is surprising about the difference in oldest children''s academic performance?',  'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 280);

SET @q27 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 27);
SET @q28 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 28);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q27, 'A', 'There is conflicting evidence about whether oldest children perform best in intelligence tests.',          0, 10),
(@q27, 'B', 'There is little doubt that birth order has less influence on academic achievement than socio-economic status.', 0, 20),
(@q27, 'C', 'Some studies have neglected to include important factors such as family size.',                            1, 30),
(@q28, 'A', 'It is mainly thanks to their roles as teachers for their younger siblings.',                              1, 10),
(@q28, 'B', 'The advantages they have only lead to a slightly higher level of achievement.',                           0, 20),
(@q28, 'C', 'The extra parental attention they receive at a young age makes little difference.',                       0, 30);

-- ============================================================
-- PART 3 – Questions 29–30 (multiple_choice_multiple – choose TWO)
-- Correct answers: B (learning to stand up for oneself) and D (learning to be tolerant)
-- Both is_correct=1; grading logic requires the PAIR covers {B,D} with no duplicates.
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 29, 'Sibling rivalry – choose TWO', 'Which TWO experiences of sibling rivalry do the speakers agree has been valuable? (first answer)',  'multiple_choice_multiple', 'Choose TWO letters, A–E. Your two answers (Q29 and Q30) must together include both correct letters.', 1.0, 290),
(@tid, 30, 'Sibling rivalry – choose TWO', 'Which TWO experiences of sibling rivalry do the speakers agree has been valuable? (second answer)', 'multiple_choice_multiple', 'Choose TWO letters, A–E. Your two answers (Q29 and Q30) must together include both correct letters.', 1.0, 300);

SET @q29 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 29);
SET @q30 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 30);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q29, 'A', 'learning to share',                  0, 10),
(@q29, 'B', 'learning to stand up for oneself',   1, 20),
(@q29, 'C', 'learning to be a good loser',        0, 30),
(@q29, 'D', 'learning to be tolerant',            1, 40),
(@q29, 'E', 'learning to say sorry',              0, 50),
(@q30, 'A', 'learning to share',                  0, 10),
(@q30, 'B', 'learning to stand up for oneself',   1, 20),
(@q30, 'C', 'learning to be a good loser',        0, 30),
(@q30, 'D', 'learning to be tolerant',            1, 40),
(@q30, 'E', 'learning to say sorry',              0, 50);

-- ============================================================
-- PART 4 – Questions 31–40 (form_note_completion)
-- Topic: The Eucalyptus Tree in Australia
-- ============================================================

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order) VALUES
(@tid, 31, 'The Eucalyptus Tree in Australia – Importance',               'it provides ___ and food for a wide range of species',                     'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310),
(@tid, 32, 'The Eucalyptus Tree in Australia – Importance',               'its leaves provide ___ which is used to make a disinfectant',               'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320),
(@tid, 33, 'The Eucalyptus Tree in Australia – Mundulla Yellows',         'Cause: lime used for making ___ was absorbed',                              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330),
(@tid, 34, 'The Eucalyptus Tree in Australia – Bell-miner Die-back',      '___ feed on eucalyptus leaves',                                             'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340),
(@tid, 35, 'The Eucalyptus Tree in Australia – Bushfires high-frequency', 'high-frequency bushfires result in the growth of ___',                      'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350),
(@tid, 36, 'The Eucalyptus Tree in Australia – Bushfires mid-frequency',  'mid-frequency bushfires make more ___ available to the trees',              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360),
(@tid, 37, 'The Eucalyptus Tree in Australia – Bushfires mid-frequency',  'mid-frequency bushfires maintain the quality of the ___',                   'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370),
(@tid, 38, 'The Eucalyptus Tree in Australia – Bushfires low-frequency',  'low-frequency bushfires result in the growth of ___ rainforest',            'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380),
(@tid, 39, 'The Eucalyptus Tree in Australia – Bushfires low-frequency',  'the rainforest is a ___ ecosystem',                                        'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390),
(@tid, 40, 'The Eucalyptus Tree in Australia – Bushfires low-frequency',  'the rainforest is an ideal environment for the ___ of the bell-miner',     'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400);

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

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
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
(@q40, 'nests',    0, 1);
