-- ============================================================
-- Migration 023 — Seed IELTS_FM2_L (Listening Full Mock Test 2)
-- Cambridge IELTS GT Test 2 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM2_L', 'IELTS Full Mock 2 — Listening',
       'Cambridge IELTS GT Test 2 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM2_L');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM2_L' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Part 1: Copying photos to digital format — Picturerep (note/table completion Q1–10)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'Copying photos to digital format — Picturerep' AS stimulus_text,
    'Photos must not be in a ___ or an album' AS question_text,
    'form_note_completion' AS question_type,
    'Write ONE WORD AND/OR A NUMBER for each answer.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1,  2, NULL, 'The cost for 360 photos is £___ (including one disk)',                       'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  20
UNION ALL SELECT @tid, 1,  3, NULL, 'Before the completed order is sent, ___ is required',                         'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  30
UNION ALL SELECT @tid, 1,  4, NULL, 'Photos can be placed in a folder, e.g. with the name ___',                    'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  40
UNION ALL SELECT @tid, 1,  5, NULL, 'The ___ and contrast can be improved if necessary',                           'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  50
UNION ALL SELECT @tid, 1,  6, NULL, 'Photos which are very fragile will be scanned by ___',                        'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  60
UNION ALL SELECT @tid, 1,  7, NULL, 'It may be possible to remove an object from a photo, or change the ___',     'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  70
UNION ALL SELECT @tid, 1,  8, NULL, 'A photo which is not correctly in ___ cannot be fixed',                       'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  80
UNION ALL SELECT @tid, 1,  9, NULL, 'Orders are completed within ___',                                             'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  90
UNION ALL SELECT @tid, 1, 10, NULL, 'Send the photos in a box (not ___)',                                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 100

-- Part 2: Dartfield House School MCQ (Q11–15)
UNION ALL SELECT @tid, 2, 11, 'Part 2 — Dartfield House School', 'Dartfield House school used to be',                            'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 110
UNION ALL SELECT @tid, 2, 12, NULL,                                'What is planned with regard to the lower school?',             'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 120
UNION ALL SELECT @tid, 2, 13, NULL,                                'The catering has been changed because of',                     'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 130
UNION ALL SELECT @tid, 2, 14, NULL,                                'Parents are asked to',                                         'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 140
UNION ALL SELECT @tid, 2, 15, NULL,                                'What does the speaker say about the existing canteen?',        'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 150

-- Part 2: Food Hall serving points — match to comments box (Q16–18)
UNION ALL SELECT @tid, 2, 16,
    'Comments — A: pupils help to plan menus  B: only vegetarian food  C: different food every week  D: daily change in menu',
    'World Adventures', 'matching', 'Choose THREE answers from the box and write the correct letter, A–D, next to Questions 16–18.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL, 'Street Life',     'matching', 'Choose THREE answers from the box and write the correct letter, A–D, next to Questions 16–18.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL, 'Speedy Italian',  'matching', 'Choose THREE answers from the box and write the correct letter, A–D, next to Questions 16–18.', 1.0, 180

-- Part 2: Choose TWO new after-school lessons (Q19–20)
UNION ALL SELECT @tid, 2, 19, NULL, 'Which TWO optional after-school lessons are new?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL, 'Which TWO optional after-school lessons are new?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 200

-- Part 3: Assignment on sleep and dreams (Q21–24 MCQ)
UNION ALL SELECT @tid, 3, 21, 'Part 3 — Assignment on sleep and dreams', 'Luke read that one reason why we often forget dreams is that',         'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 210
UNION ALL SELECT @tid, 3, 22, NULL,                                       'What do Luke and Susie agree about dreams predicting the future?',     'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 220
UNION ALL SELECT @tid, 3, 23, NULL,                                       'Susie says that a study on pre-school children having a short nap in the day', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 230
UNION ALL SELECT @tid, 3, 24, NULL,                                       'In their last assignment, both students had problems with',            'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 240

-- Part 3: Assignment plan flow chart (Q25–30)
UNION ALL SELECT @tid, 3, 25, 'Assignment plan', 'Twelve students from the ___ department',                                  'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 250
UNION ALL SELECT @tid, 3, 26, NULL,                'Answers on ___',                                                            'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 260
UNION ALL SELECT @tid, 3, 27, NULL,                'Check ethical guidelines for working with ___',                             'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 270
UNION ALL SELECT @tid, 3, 28, NULL,                'Ensure that risk is assessed and ___ is kept to a minimum',                 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL,                'Calculate the correlation and make a ___',                                  'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL,                '___ the research',                                                          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 300

-- Part 4: Health benefits of dance (Q31–40)
UNION ALL SELECT @tid, 4, 31, 'Health benefits of dance', 'An experiment on university students suggested that dance increases ___',          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310
UNION ALL SELECT @tid, 4, 32, NULL,                          'For those with mental illness, dance could be used as a form of ___',                'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320
UNION ALL SELECT @tid, 4, 33, NULL,                          'Accessible for people with low levels of ___',                                       'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330
UNION ALL SELECT @tid, 4, 34, NULL,                          'Better ___ reduces the risk of accidents',                                           'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340
UNION ALL SELECT @tid, 4, 35, NULL,                          'Improves ___ function by making it work faster',                                     'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350
UNION ALL SELECT @tid, 4, 36, NULL,                          'Gives people more ___ to take exercise',                                             'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360
UNION ALL SELECT @tid, 4, 37, NULL,                          'Can lessen the feeling of ___, very common in older people',                          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370
UNION ALL SELECT @tid, 4, 38, NULL,                          'A study showed that doing Zumba for 40 minutes uses up as many ___ as other quite intense forms of exercise', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380
UNION ALL SELECT @tid, 4, 39, NULL,                          'Women suffering from ___ benefited from doing Zumba',                                'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390
UNION ALL SELECT @tid, 4, 40, NULL,                          'Zumba became a ___ for the participants',                                            'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400

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

-- Q11: Dartfield House used to be (correct: C)
SELECT @q11 AS question_id, 'A' AS option_label, 'a tourist information centre.' AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q11, 'B', 'a private home.', 0, 20
UNION ALL SELECT @q11, 'C', 'a local council building.', 1, 30

-- Q12: lower school plan (correct: B)
UNION ALL SELECT @q12, 'A', 'All buildings on the main site will be improved.', 0, 10
UNION ALL SELECT @q12, 'B', 'The lower school site will be used for new homes.', 1, 20
UNION ALL SELECT @q12, 'C', 'Additional school buildings will be constructed on the lower school site.', 0, 30

-- Q13: catering changed because of (correct: A)
UNION ALL SELECT @q13, 'A', 'long queuing times.', 1, 10
UNION ALL SELECT @q13, 'B', 'changes to the school timetable.', 0, 20
UNION ALL SELECT @q13, 'C', 'dissatisfaction with the menus.', 0, 30

-- Q14: parents asked to (correct: A)
UNION ALL SELECT @q14, 'A', 'help their children to decide in advance which serving point to use.', 1, 10
UNION ALL SELECT @q14, 'B', 'make sure their children have enough money for food.', 0, 20
UNION ALL SELECT @q14, 'C', 'advise their children on healthy food to eat.', 0, 30

-- Q15: existing canteen (correct: C)
UNION ALL SELECT @q15, 'A', 'Food will still be served there.', 0, 10
UNION ALL SELECT @q15, 'B', 'Only staff will have access to it.', 0, 20
UNION ALL SELECT @q15, 'C', 'Pupils can take their food into it.', 1, 30

-- Q16–18: Food Hall serving points comments A–D
UNION ALL SELECT @q16, 'A', 'pupils help to plan menus', 0, 10 UNION ALL SELECT @q16, 'B', 'only vegetarian food', 0, 20 UNION ALL SELECT @q16, 'C', 'different food every week', 0, 30 UNION ALL SELECT @q16, 'D', 'daily change in menu', 1, 40
UNION ALL SELECT @q17, 'A', 'pupils help to plan menus', 1, 10 UNION ALL SELECT @q17, 'B', 'only vegetarian food', 0, 20 UNION ALL SELECT @q17, 'C', 'different food every week', 0, 30 UNION ALL SELECT @q17, 'D', 'daily change in menu', 0, 40
UNION ALL SELECT @q18, 'A', 'pupils help to plan menus', 0, 10 UNION ALL SELECT @q18, 'B', 'only vegetarian food', 1, 20 UNION ALL SELECT @q18, 'C', 'different food every week', 0, 30 UNION ALL SELECT @q18, 'D', 'daily change in menu', 0, 40

-- Q19–20: TWO new optional after-school lessons (correct: B and C)
UNION ALL SELECT @q19, 'A', 'swimming', 0, 10
UNION ALL SELECT @q19, 'B', 'piano', 1, 20
UNION ALL SELECT @q19, 'C', 'acting', 1, 30
UNION ALL SELECT @q19, 'D', 'cycling', 0, 40
UNION ALL SELECT @q19, 'E', 'theatre sound and lighting', 0, 50
UNION ALL SELECT @q20, 'A', 'swimming', 0, 10
UNION ALL SELECT @q20, 'B', 'piano', 1, 20
UNION ALL SELECT @q20, 'C', 'acting', 1, 30
UNION ALL SELECT @q20, 'D', 'cycling', 0, 40
UNION ALL SELECT @q20, 'E', 'theatre sound and lighting', 0, 50

-- Q21: forgetting dreams (correct: B)
UNION ALL SELECT @q21, 'A', 'our memories cannot cope with too much information.', 0, 10
UNION ALL SELECT @q21, 'B', 'we might otherwise be confused about what is real.', 1, 20
UNION ALL SELECT @q21, 'C', 'we do not think they are important.', 0, 30

-- Q22: dreams predicting the future (correct: A)
UNION ALL SELECT @q22, 'A', 'It may just be due to chance.', 1, 10
UNION ALL SELECT @q22, 'B', 'It only happens with certain types of event.', 0, 20
UNION ALL SELECT @q22, 'C', 'It happens more often than some people think.', 0, 30

-- Q23: pre-school nap study (correct: C)
UNION ALL SELECT @q23, 'A', 'had controversial results.', 0, 10
UNION ALL SELECT @q23, 'B', 'used faulty research methodology.', 0, 20
UNION ALL SELECT @q23, 'C', 'failed to reach any clear conclusions.', 1, 30

-- Q24: last assignment problems (correct: C)
UNION ALL SELECT @q24, 'A', 'statistical analysis.', 0, 10
UNION ALL SELECT @q24, 'B', 'making an action plan.', 0, 20
UNION ALL SELECT @q24, 'C', 'self-assessment.', 1, 30

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Part 1 Q1–10
SELECT @q1  AS question_id, 'frame'       AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2,  '195',        0, 0
UNION ALL SELECT @q3,  'payment',    0, 0
UNION ALL SELECT @q4,  'Grandparents', 0, 0
UNION ALL SELECT @q4,  'grandparents', 0, 1
UNION ALL SELECT @q5,  'colour',     0, 0
UNION ALL SELECT @q5,  'color',      0, 1
UNION ALL SELECT @q6,  'hand',       0, 0
UNION ALL SELECT @q7,  'background', 0, 0
UNION ALL SELECT @q8,  'focus',      0, 0
UNION ALL SELECT @q9,  'ten',        0, 0
UNION ALL SELECT @q9,  '10 days',    0, 1
UNION ALL SELECT @q9,  'ten days',   0, 1
UNION ALL SELECT @q10, 'plastic',    0, 0

-- Q16–18 matching (D, A, B)
UNION ALL SELECT @q16, 'D', 0, 0 UNION ALL SELECT @q16, 'd', 0, 1
UNION ALL SELECT @q17, 'A', 0, 0 UNION ALL SELECT @q17, 'a', 0, 1
UNION ALL SELECT @q18, 'B', 0, 0 UNION ALL SELECT @q18, 'b', 0, 1

-- Part 3 Q25–30
UNION ALL SELECT @q25, 'history',  0, 0
UNION ALL SELECT @q26, 'paper',    0, 0
UNION ALL SELECT @q27, 'humans',   0, 0
UNION ALL SELECT @q27, 'people',   0, 1
UNION ALL SELECT @q28, 'stress',   0, 0
UNION ALL SELECT @q29, 'graph',    0, 0
UNION ALL SELECT @q30, 'evaluate', 0, 0

-- Part 4 Q31–40
UNION ALL SELECT @q31, 'creativity', 0, 0
UNION ALL SELECT @q32, 'therapy',    0, 0
UNION ALL SELECT @q33, 'fitness',    0, 0
UNION ALL SELECT @q34, 'balance',    0, 0
UNION ALL SELECT @q35, 'brain',      0, 0
UNION ALL SELECT @q36, 'motivation', 0, 0
UNION ALL SELECT @q37, 'isolation',  0, 0
UNION ALL SELECT @q38, 'calories',   0, 0
UNION ALL SELECT @q39, 'obesity',    0, 0
UNION ALL SELECT @q40, 'habit',      0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, ~46 options, ~34 correct answers
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
