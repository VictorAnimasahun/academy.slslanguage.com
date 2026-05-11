-- ============================================================
-- Migration 010 — Seed IELTS_PT_R_001 (Reading Practice Test 1)
-- IDEMPOTENT: safe to re-run.
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_R_001',
       'IELTS General Training Reading – Practice Test 1',
       'A full 40-question IELTS General Training Reading practice test covering consumer advice, rice cooker reviews, workplace safety, maternity allowance, and the California Gold Rush.',
       'IELTS',
       60,
       40,
       1, 0,
       'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_001');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_PT_R_001' LIMIT 1);

-- Step 2: Wipe child records (makes re-runs safe)
DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- Step 3: Insert questions
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
VALUES
-- Section 1, Q1–6: True/False/Not Given (Consumer advice)
(@tid, 1,  'Consumer advice – Section 1', 'You will receive a card telling you if an item has been left with a neighbour.',                'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 10),
(@tid, 2,  'Consumer advice – Section 1', 'It may be quicker to get a refund than a replacement for a non-delivered item.',              'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 20),
(@tid, 3,  'Consumer advice – Section 1', 'You are entitled to a refund if the item fails to arrive by a certain time.',                  'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 30),
(@tid, 4,  'Consumer advice – Section 1', 'There is a time limit when using the chargeback scheme for a debit card payment.',             'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 40),
(@tid, 5,  'Consumer advice – Section 1', 'You can use the chargeback scheme for a credit card payment of more than £100.',              'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 50),
(@tid, 6,  'Consumer advice – Section 1', 'PayPal''s online resolution centre has a good reputation for efficiency.',                    'true_false_not_given', 'Write TRUE, FALSE or NOT GIVEN.', 1.0, 60),

-- Section 1, Q7–14: Matching (Rice cookers A–E)
(@tid, 7,  'Rice cookers – Section 1', 'The handles at the side are hard to use.',                           'matching', 'Write the correct letter, A–E.', 1.0, 70),
(@tid, 8,  'Rice cookers – Section 1', 'It cooks brown rice without making a mess.',                         'matching', 'Write the correct letter, A–E.', 1.0, 80),
(@tid, 9,  'Rice cookers – Section 1', 'It automatically switches setting to keep the rice warm when cooked.','matching', 'Write the correct letter, A–E.', 1.0, 90),
(@tid, 10, 'Rice cookers – Section 1', 'It''s difficult to get the removable top really clean.',              'matching', 'Write the correct letter, A–E.', 1.0, 100),
(@tid, 11, 'Rice cookers – Section 1', 'A selection of recipes is provided with the cooker.',                'matching', 'Write the correct letter, A–E.', 1.0, 110),
(@tid, 12, 'Rice cookers – Section 1', 'It has a handle at the top for carrying the cooker safely.',         'matching', 'Write the correct letter, A–E.', 1.0, 120),
(@tid, 13, 'Rice cookers – Section 1', 'The outside of the cooker doesn''t get too hot.',                    'matching', 'Write the correct letter, A–E.', 1.0, 130),
(@tid, 14, 'Rice cookers – Section 1', 'You can put the pot in the dishwasher.',                             'matching', 'Write the correct letter, A–E.', 1.0, 140),

-- Section 2, Q15–22: Form/Note completion (Safety when working on roofs)
(@tid, 15, 'Safety when working on roofs – Section 2', 'over half of falls are from less than ___',                                     'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 150),
(@tid, 16, 'Safety when working on roofs – Section 2', 'the majority of falls occur on ___',                                            'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 160),
(@tid, 17, 'Safety when working on roofs – Section 2', '___ the hazard at the planning stage before the work begins if possible',        'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 170),
(@tid, 18, 'Safety when working on roofs – Section 2', 'prevent a fall by using edge protection, e.g. scaffolding or ___',              'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 180),
(@tid, 19, 'Safety when working on roofs – Section 2', 'reduce the likelihood of injury, e.g. by using ___',                            'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 190),
(@tid, 20, 'Safety when working on roofs – Section 2', 'these should only be used for ___ which does not take a long time',             'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 200),
(@tid, 21, 'Safety when working on roofs – Section 2', 'training should be provided in their ___ and use',                              'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 210),
(@tid, 22, 'Safety when working on roofs – Section 2', 'regular ___ of ladders is required',                                            'form_note_completion', 'Choose NO MORE THAN THREE WORDS AND/OR A NUMBER from the text.', 1.0, 220),

-- Section 2, Q23–27: Sentence completion (Maternity Allowance)
(@tid, 23, 'Maternity Allowance – Section 2', 'The maximum amount of money a woman can get each week is £ ___.',                                                                                    'sentence_completion', 'Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.', 1.0, 230),
(@tid, 24, 'Maternity Allowance – Section 2', 'Being ___ for a time does not necessarily mean that a woman will not be eligible for Maternity Allowance.',                                          'sentence_completion', 'Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.', 1.0, 240),
(@tid, 25, 'Maternity Allowance – Section 2', 'In order to claim, a woman must send a ___ or a Small Earnings Exemption Certificate as evidence of her income.',                                    'sentence_completion', 'Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.', 1.0, 250),
(@tid, 26, 'Maternity Allowance – Section 2', 'In order to claim, a woman may need to provide a ___ as evidence of the due date.',                                                                 'sentence_completion', 'Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.', 1.0, 260),
(@tid, 27, 'Maternity Allowance – Section 2', 'Payment may be affected by differences in someone''s ___, such as a return to work, and the local Jobcentre Plus must be informed.',                'sentence_completion', 'Choose NO MORE THAN TWO WORDS AND/OR A NUMBER from the text.', 1.0, 270),

-- Section 3, Q28–31: Multiple choice (California Gold Rush)
(@tid, 28, 'The California Gold Rush of 1849 – Section 3', 'The writer suggests that Marshall''s discovery came at a good time for the US because', 'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 280),
(@tid, 29, 'The California Gold Rush of 1849 – Section 3', 'What was the reaction in 1848 to the news of the discovery of gold?',                   'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 290),
(@tid, 30, 'The California Gold Rush of 1849 – Section 3', 'What was the result of thousands of people moving to California?',                       'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 300),
(@tid, 31, 'The California Gold Rush of 1849 – Section 3', 'What does the writer say about using pans and rockers to find gold?',                    'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 310),

-- Section 3, Q32–36: Section matching (A–G)
(@tid, 32, 'The California Gold Rush of 1849 – Section 3', 'a reference to ways of making money in California other than mining for gold',                    'matching', 'Write the correct letter, A–G.', 1.0, 320),
(@tid, 33, 'The California Gold Rush of 1849 – Section 3', 'a suggestion that the gold that was found did not often compensate for the hard work undertaken',  'matching', 'Write the correct letter, A–G.', 1.0, 330),
(@tid, 34, 'The California Gold Rush of 1849 – Section 3', 'a mention of an individual who convinced many of the existence of gold in California',             'matching', 'Write the correct letter, A–G.', 1.0, 340),
(@tid, 35, 'The California Gold Rush of 1849 – Section 3', 'details of the pre-Gold Rush population of California',                                           'matching', 'Write the correct letter, A–G.', 1.0, 350),
(@tid, 36, 'The California Gold Rush of 1849 – Section 3', 'a contrast between shrinking revenue and increasing population',                                   'matching', 'Write the correct letter, A–G.', 1.0, 360),

-- Section 3, Q37–40: Summary completion
(@tid, 37, 'Basic techniques for extracting gold – Section 3', 'The most basic method began with digging some ___ out of a river.',                           'summary_completion', 'Choose ONE WORD ONLY from the text.', 1.0, 370),
(@tid, 38, 'Basic techniques for extracting gold – Section 3', 'There might even be some ___ too.',                                                          'summary_completion', 'Choose ONE WORD ONLY from the text.', 1.0, 380),
(@tid, 39, 'Basic techniques for extracting gold – Section 3', 'Larger stones stuck in the ___.',                                                            'summary_completion', 'Choose ONE WORD ONLY from the text.', 1.0, 390),
(@tid, 40, 'Basic techniques for extracting gold – Section 3', 'A process was introduced involving ___ to ensure no gold was washed out in the water.',      'summary_completion', 'Choose ONE WORD ONLY from the text.', 1.0, 400)
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

-- Step 5: Insert correct answers for text/select-type questions
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
-- Q1–6 True/False/Not Given
(@q1,  'not given', 0, 0),
(@q2,  'not given', 0, 0),
(@q3,  'true',      0, 0),
(@q4,  'true',      0, 0),
(@q5,  'false',     0, 0),
(@q6,  'not given', 0, 0),

-- Q7–14 Matching (rice cookers)
(@q7,  'd', 0, 0),
(@q8,  'e', 0, 0),
(@q9,  'b', 0, 0),
(@q10, 'a', 0, 0),
(@q11, 'd', 0, 0),
(@q12, 'b', 0, 0),
(@q13, 'e', 0, 0),
(@q14, 'c', 0, 0),

-- Q15–22 Note completion (roofs)
(@q15, '3',                          0, 0),
(@q15, 'three metres',               0, 1),
(@q15, 'three meters',               0, 1),
(@q16, 'residential building sites', 0, 0),
(@q17, 'eliminate',                  0, 0),
(@q18, 'temporary work platforms',   0, 0),
(@q18, 'temporary platforms',        0, 1),
(@q18, 'work platforms',             0, 1),
(@q19, 'safety nets',                0, 0),
(@q20, 'maintenance work',           0, 0),
(@q20, 'maintenance',                0, 1),
(@q21, 'selection',                  0, 0),
(@q22, 'inspection',                 0, 0),

-- Q23–27 Sentence completion (maternity)
(@q23, '140.98',        0, 0),
(@q24, 'unemployed',    0, 0),
(@q25, 'payslip',       0, 0),
(@q26, 'doctor''s letter', 0, 0),
(@q27, 'circumstances', 0, 0),

-- Q32–36 Section matching (A–G)
(@q32, 'c', 0, 0),
(@q33, 'f', 0, 0),
(@q34, 'b', 0, 0),
(@q35, 'a', 0, 0),
(@q36, 'g', 0, 0),

-- Q37–40 Summary completion
(@q37, 'gravel',  0, 0),
(@q38, 'nuggets', 0, 0),
(@q39, 'sieve',   0, 0),
(@q40, 'mercury', 0, 0)
;

-- Step 6: Insert MCQ options (Q28–31)
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
-- Q28
(@q28, 'A', 'the Mexican–American War was ending so there were men needing work.', 0, 10),
(@q28, 'B', 'his expertise in water power would be useful in gold mining.',        0, 20),
(@q28, 'C', 'the population of California had already begun to increase rapidly.', 0, 30),
(@q28, 'D', 'the region was about to come under the control of the US.',           1, 40),
-- Q29
(@q29, 'A', 'The press played a large part in convincing the public of the riches available.', 0, 10),
(@q29, 'B', 'Many men in San Francisco left immediately to check it out for themselves.',       0, 20),
(@q29, 'C', 'People needed to see physical evidence before they took it seriously.',           1, 30),
(@q29, 'D', 'Men in other mines in the US were among the first to respond to it.',            0, 40),
-- Q30
(@q30, 'A', 'San Francisco could not cope with the influx of people from around the world.',  0, 10),
(@q30, 'B', 'Many miners got more money than they could ever have earned at home.',           0, 20),
(@q30, 'C', 'Some of those who stayed behind had to take on unexpected roles.',               1, 30),
(@q30, 'D', 'New towns were established which became good places to live.',                   0, 40),
-- Q31
(@q31, 'A', 'Both methods required the addition of mercury.',             0, 10),
(@q31, 'B', 'A rocker needed more than one miner to operate it.',        0, 20),
(@q31, 'C', 'Pans were the best system for novice miners to use.',       0, 30),
(@q31, 'D', 'Miners had to find a way round a design fault in one system.', 1, 40)
;
