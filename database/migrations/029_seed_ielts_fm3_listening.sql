-- ============================================================
-- Migration 029 — Seed IELTS_FM3_L (Listening Full Mock Test 3)
-- Cambridge IELTS GT Test 3 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM3_L', 'IELTS Full Mock 3 — Listening',
       'Cambridge IELTS GT Test 3 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_L');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM3_L' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Part 1: Junior Cycle Camp (note completion Q1–10)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'JUNIOR CYCLE CAMP — The course focuses on skills and safety. Charlie would be placed in Level 5.' AS stimulus_text,
    'First of all, children at this level are taken to practise in a ___' AS question_text,
    'form_note_completion' AS question_type,
    'Write ONE WORD AND/OR A NUMBER for each answer.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1,  2, NULL, 'Instructors — Instructors wear ___ shirts',                                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  20
UNION ALL SELECT @tid, 1,  3, NULL, 'A ___ is required and training is given',                                            'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  30
UNION ALL SELECT @tid, 1,  4, NULL, 'Classes — There are quiet times during the morning for a ___ or a game',            'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  40
UNION ALL SELECT @tid, 1,  5, NULL, 'Classes are held even if there is ___',                                              'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  50
UNION ALL SELECT @tid, 1,  6, NULL, 'What to bring — a change of clothing, a ___',                                       'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  60
UNION ALL SELECT @tid, 1,  7, NULL, 'shoes (not sandals), Charlie''s ___',                                                'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  70
UNION ALL SELECT @tid, 1,  8, NULL, 'Day 1 — Charlie should arrive at 9.20 am on the first day. Before the class, his ___ will be checked', 'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  80
UNION ALL SELECT @tid, 1,  9, NULL, 'He should then go to the ___ to meet his class instructor',                         'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  90
UNION ALL SELECT @tid, 1, 10, NULL, 'Cost — The course costs $___ per week',                                             'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 100

-- Part 2: Working in agriculture/horticulture — choose TWO (Q11–12)
UNION ALL SELECT @tid, 2, 11,
    'A: the active lifestyle  B: the above-average salaries  C: the flexible working opportunities  D: the opportunities for overseas travel  E: the chance to be in a natural environment',
    'According to Megan, what are the TWO main advantages of working in the agriculture and horticulture sectors?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 110
UNION ALL SELECT @tid, 2, 12, NULL, 'According to Megan, what are the TWO main advantages of working in the agriculture and horticulture sectors?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 120

-- Part 2: Disadvantages of working outdoors — choose TWO (Q13–14)
UNION ALL SELECT @tid, 2, 13,
    'A: the increasing risk of accidents  B: being in a very quiet location  C: difficult weather conditions at times  D: the cost of housing  E: the level of physical fitness required',
    'Which TWO of the following are likely to be disadvantages for people working outdoors?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 130
UNION ALL SELECT @tid, 2, 14, NULL, 'Which TWO of the following are likely to be disadvantages for people working outdoors?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 140

-- Part 2: Job opportunities — matching (box A–H, Q15–20)
UNION ALL SELECT @tid, 2, 15,
    'A: not a permanent job  B: involves leading a team  C: experience not essential  D: intensive work but also fun  E: chance to earn more through overtime  F: chance for rapid promotion  G: accommodation available  H: local travel involved',
    'Fresh food commercial manager', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL, 'Agronomist',                  'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL, 'Fresh produce buyer',         'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL, 'Garden centre sales manager', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL, 'Tree technician',             'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL, 'Farm worker',                 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 15–20.', 1.0, 200

-- Part 3: Artificial sweeteners experiment — choose TWO (Q21–22)
UNION ALL SELECT @tid, 3, 21,
    'A: The results were what he had predicted.  B: The experiment was simple to set up.  C: A large sample of people was tested.  D: The subjects were unaware of what they were drinking.  E: The test was repeated several times for each person.',
    'Which TWO points does Adam make about his experiment on artificial sweeteners?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 210
UNION ALL SELECT @tid, 3, 22, NULL, 'Which TWO points does Adam make about his experiment on artificial sweeteners?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 220

-- Part 3: Measuring fat content of nuts — choose TWO (Q23–24)
UNION ALL SELECT @tid, 3, 23,
    'A: She used the wrong sort of nuts.  B: She used an unsuitable chemical.  C: She did not grind the nuts finely enough.  D: The information on the nut package was incorrect.  E: The weighing scales may have been unsuitable.',
    'Which TWO problems did Rosie have when measuring the fat content of nuts?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 230
UNION ALL SELECT @tid, 3, 24, NULL, 'Which TWO problems did Rosie have when measuring the fat content of nuts?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 240

-- Part 3: Single MCQs (Q25–30)
UNION ALL SELECT @tid, 3, 25, NULL, 'Adam suggests that restaurants could reduce obesity if their menus',                                   'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 250
UNION ALL SELECT @tid, 3, 26, NULL, 'The students agree that food manufacturers deliberately',                                              'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 260
UNION ALL SELECT @tid, 3, 27, NULL, 'What does Rosie say about levels of exercise in England?',                                              'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 270
UNION ALL SELECT @tid, 3, 28, NULL, 'Adam refers to the location and width of stairs in a train station to illustrate',                      'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL, 'What do the students agree about including reference to exercise in their presentation?',               'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL, 'What are the students going to do next for their presentation?',                                        'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 300

-- Part 4: Hand knitting (note completion Q31–40)
UNION ALL SELECT @tid, 4, 31, 'Hand knitting — Interest in knitting: Knitting has a long history around the world.', 'We imagine someone like a ___ knitting',                                    'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310
UNION ALL SELECT @tid, 4, 32, NULL, 'A ___ ago, knitting was expected to disappear',                                                        'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320
UNION ALL SELECT @tid, 4, 33, NULL, 'The number of knitting classes is now increasing. People are buying more ___ for knitting nowadays',     'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330
UNION ALL SELECT @tid, 4, 34, NULL, 'Benefits of knitting — gives support in times of ___ difficulty',                                       'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340
UNION ALL SELECT @tid, 4, 35, NULL, 'requires only ___ skills and little money to start',                                                    'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350
UNION ALL SELECT @tid, 4, 36, NULL, 'Early knitting — The origins are not known. Findings show early knitted items to be ___ in shape',       'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360
UNION ALL SELECT @tid, 4, 37, NULL, 'The first needles were made of natural materials such as wood and ___',                                 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370
UNION ALL SELECT @tid, 4, 38, NULL, 'Early yarns felt ___ to touch',                                                                         'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380
UNION ALL SELECT @tid, 4, 39, NULL, 'Wool became the most popular yarn for spinning. Geographical areas had their own ___ of knitting',       'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390
UNION ALL SELECT @tid, 4, 40, NULL, 'Everyday tasks like looking after ___ were done while knitting',                                         'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400

) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- ============================================================
-- Step 3: Resolve question IDs
-- ============================================================
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
-- (Choose-TWO pairs Q11-14, Q21-24 score via mock_save_section.php's
--  $pair_defs, but is_correct is still set here for the admin review UI.)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- Q11&12: agriculture/horticulture advantages (correct: A, C)
SELECT @q11 AS question_id, 'A' AS option_label, 'the active lifestyle' AS option_text, 1 AS is_correct, 10 AS display_order
UNION ALL SELECT @q11, 'B', 'the above-average salaries', 0, 20
UNION ALL SELECT @q11, 'C', 'the flexible working opportunities', 1, 30
UNION ALL SELECT @q11, 'D', 'the opportunities for overseas travel', 0, 40
UNION ALL SELECT @q11, 'E', 'the chance to be in a natural environment', 0, 50

-- Q13&14: disadvantages of working outdoors (correct: B, D)
UNION ALL SELECT @q13, 'A', 'the increasing risk of accidents', 0, 10
UNION ALL SELECT @q13, 'B', 'being in a very quiet location', 1, 20
UNION ALL SELECT @q13, 'C', 'difficult weather conditions at times', 0, 30
UNION ALL SELECT @q13, 'D', 'the cost of housing', 1, 40
UNION ALL SELECT @q13, 'E', 'the level of physical fitness required', 0, 50

-- Q15-20 matching box (A-H) — is_correct not meaningful here; per-question answer lives in question_correct_answers
UNION ALL SELECT @q15, 'A', 'not a permanent job', 0, 10
UNION ALL SELECT @q15, 'B', 'involves leading a team', 0, 20
UNION ALL SELECT @q15, 'C', 'experience not essential', 0, 30
UNION ALL SELECT @q15, 'D', 'intensive work but also fun', 0, 40
UNION ALL SELECT @q15, 'E', 'chance to earn more through overtime', 0, 50
UNION ALL SELECT @q15, 'F', 'chance for rapid promotion', 0, 60
UNION ALL SELECT @q15, 'G', 'accommodation available', 0, 70
UNION ALL SELECT @q15, 'H', 'local travel involved', 0, 80

-- Q21&22: artificial sweeteners experiment (correct: C, D)
UNION ALL SELECT @q21, 'A', 'The results were what he had predicted.', 0, 10
UNION ALL SELECT @q21, 'B', 'The experiment was simple to set up.', 0, 20
UNION ALL SELECT @q21, 'C', 'A large sample of people was tested.', 1, 30
UNION ALL SELECT @q21, 'D', 'The subjects were unaware of what they were drinking.', 1, 40
UNION ALL SELECT @q21, 'E', 'The test was repeated several times for each person.', 0, 50

-- Q23&24: measuring fat content of nuts (correct: C, E)
UNION ALL SELECT @q23, 'A', 'She used the wrong sort of nuts.', 0, 10
UNION ALL SELECT @q23, 'B', 'She used an unsuitable chemical.', 0, 20
UNION ALL SELECT @q23, 'C', 'She did not grind the nuts finely enough.', 1, 30
UNION ALL SELECT @q23, 'D', 'The information on the nut package was incorrect.', 0, 40
UNION ALL SELECT @q23, 'E', 'The weighing scales may have been unsuitable.', 1, 50

-- Q25: restaurants reducing obesity (correct: C)
UNION ALL SELECT @q25, 'A', 'offered fewer options.', 0, 10
UNION ALL SELECT @q25, 'B', 'had more low-calorie foods.', 0, 20
UNION ALL SELECT @q25, 'C', 'were organised in a particular way.', 1, 30

-- Q26: food manufacturers deliberately (correct: A)
UNION ALL SELECT @q26, 'A', 'make calorie counts hard to understand.', 1, 10
UNION ALL SELECT @q26, 'B', 'fail to provide accurate calorie counts.', 0, 20
UNION ALL SELECT @q26, 'C', 'use ineffective methods to reduce calories.', 0, 30

-- Q27: levels of exercise in England (correct: B)
UNION ALL SELECT @q27, 'A', 'The amount recommended is much too low.', 0, 10
UNION ALL SELECT @q27, 'B', 'Most people overestimate how much they do.', 1, 20
UNION ALL SELECT @q27, 'C', 'Women now exercise more than they used to.', 0, 30

-- Q28: stairs in a train station illustrate (correct: A)
UNION ALL SELECT @q28, 'A', 'practical changes that can influence people''s behaviour.', 1, 10
UNION ALL SELECT @q28, 'B', 'methods of helping people who have mobility problems.', 0, 20
UNION ALL SELECT @q28, 'C', 'ways of preventing accidents by controlling crowd movement.', 0, 30

-- Q29: reference to exercise in presentation (correct: A)
UNION ALL SELECT @q29, 'A', 'They should probably leave it out.', 1, 10
UNION ALL SELECT @q29, 'B', 'They need to do more research on it.', 0, 20
UNION ALL SELECT @q29, 'C', 'They should discuss this with their tutor.', 0, 30

-- Q30: what students will do next (correct: C)
UNION ALL SELECT @q30, 'A', 'prepare some slides for it', 0, 10
UNION ALL SELECT @q30, 'B', 'find out how long they have for it', 0, 20
UNION ALL SELECT @q30, 'C', 'decide on its content and organisation', 1, 30

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- (form_note_completion text answers + matching letters for Q15-20)
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Part 1 Q1–10
SELECT @q1  AS question_id, 'park'        AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2,  'blue',       0, 0
UNION ALL SELECT @q3,  'reference',  0, 0
UNION ALL SELECT @q4,  'story',      0, 0
UNION ALL SELECT @q5,  'rain',       0, 0
UNION ALL SELECT @q6,  'snack',      0, 0
UNION ALL SELECT @q7,  'medication', 0, 0
UNION ALL SELECT @q8,  'helmet',     0, 0
UNION ALL SELECT @q9,  'tent',       0, 0
UNION ALL SELECT @q10, '199',        0, 0

-- Part 2 Q15–20 matching letters
UNION ALL SELECT @q15, 'D', 0, 0
UNION ALL SELECT @q16, 'F', 0, 0
UNION ALL SELECT @q17, 'A', 0, 0
UNION ALL SELECT @q18, 'H', 0, 0
UNION ALL SELECT @q19, 'C', 0, 0
UNION ALL SELECT @q20, 'G', 0, 0

-- Part 4 Q31–40
UNION ALL SELECT @q31, 'grandmother', 0, 0
UNION ALL SELECT @q32, 'decade',      0, 0
UNION ALL SELECT @q33, 'equipment',   0, 0
UNION ALL SELECT @q34, 'economic',    0, 0
UNION ALL SELECT @q35, 'basic',       0, 0
UNION ALL SELECT @q36, 'round',       0, 0
UNION ALL SELECT @q37, 'bone',        0, 0
UNION ALL SELECT @q38, 'rough',       0, 0
UNION ALL SELECT @q39, 'style',       0, 0
UNION ALL SELECT @q40, 'sheep',       0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, 46 options (5+5+8+5+5+6*3), 26 correct answers (10+6+10)
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
