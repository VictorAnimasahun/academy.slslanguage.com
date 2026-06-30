-- ============================================================
-- Migration 034 — Seed IELTS_FM4_L (Listening Full Mock Test 4)
-- Cambridge IELTS GT Test 4 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_L', 'IELTS Full Mock 4 — Listening',
       'Cambridge IELTS GT Test 4 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_L');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM4_L' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Part 1: Holiday rental (note completion Q1–10)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'HOLIDAY RENTAL — Owners'' names: Jack Fitzgerald and Shirley Fitzgerald.' AS stimulus_text,
    'Granary Cottage — available for week beginning ___ May' AS question_text,
    'form_note_completion' AS question_type,
    'Write ONE WORD AND/OR A NUMBER for each answer.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1,  2, NULL, 'Granary Cottage — cost for the week: £___',                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  20
UNION ALL SELECT @tid, 1,  3, NULL, '___ Cottage',                                                  'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  30
UNION ALL SELECT @tid, 1,  4, NULL, 'Building was originally a ___',                                'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  40
UNION ALL SELECT @tid, 1,  5, NULL, 'Walk through doors from living room into a ___',               'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  50
UNION ALL SELECT @tid, 1,  6, NULL, 'Several ___ spaces at the front',                              'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  60
UNION ALL SELECT @tid, 1,  7, NULL, 'Central heating and stove that burns ___',                      'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  70
UNION ALL SELECT @tid, 1,  8, NULL, 'Views of old ___ from living room',                             'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  80
UNION ALL SELECT @tid, 1,  9, NULL, 'View of hilltop ___ from the bedroom',                          'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0,  90
UNION ALL SELECT @tid, 1, 10, NULL, 'Payment — deposit £144, deadline for final payment: end of ___', 'form_note_completion', 'Write ONE WORD AND/OR A NUMBER for each answer.', 1.0, 100

-- Part 2: Local council report on traffic and highways — MCQ (Q11–14)
UNION ALL SELECT @tid, 2, 11,
    'LOCAL COUNCIL REPORT ON TRAFFIC AND HIGHWAYS',
    'A survey found people''s main concern about traffic in the area was', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 110
UNION ALL SELECT @tid, 2, 12, NULL, 'Which change will shortly be made to the cycle path next to the river?', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 120
UNION ALL SELECT @tid, 2, 13, NULL, 'Plans for a pedestrian crossing have been postponed because',           'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 130
UNION ALL SELECT @tid, 2, 14, NULL, 'On Station Road, notices have been erected',                             'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 140

-- Part 2: Recreation ground map labelling (Q15–20)
UNION ALL SELECT @tid, 2, 15,
    'Recreation ground after proposed changes',
    'New car park', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL, 'New cricket pitch',     'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL, 'Children''s playground', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL, 'Skateboard ramp',       'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL, 'Pavilion',              'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL, 'Notice board',          'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 200

-- Part 3: Bike-sharing benefits — choose TWO (Q21–22)
UNION ALL SELECT @tid, 3, 21,
    'A: reducing noise pollution  B: reducing traffic congestion  C: improving air quality  D: encouraging health and fitness  E: making cycling affordable',
    'Which TWO benefits of city bike-sharing schemes do the students agree are the most important?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 210
UNION ALL SELECT @tid, 3, 22, NULL, 'Which TWO benefits of city bike-sharing schemes do the students agree are the most important?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 220

-- Part 3: Necessary for successful bike-sharing schemes — choose TWO (Q23–24)
UNION ALL SELECT @tid, 3, 23,
    'A: Bikes should have a GPS system.  B: The app should be easy to use.  C: Public awareness should be raised.  D: Only one scheme should be available.  E: There should be a large network of cycle lanes.',
    'Which TWO things do the students think are necessary for successful bike-sharing schemes?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 230
UNION ALL SELECT @tid, 3, 24, NULL, 'Which TWO things do the students think are necessary for successful bike-sharing schemes?', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 240

-- Part 3: Opinion of bike-sharing scheme by city — matching (box A–G, Q25–30)
UNION ALL SELECT @tid, 3, 25,
    'A: They agree it has been disappointing.  B: They think it should be cheaper.  C: They are surprised it has been so successful.  D: They agree that more investment is required.  E: They think the system has been well designed.  F: They disagree about the reasons for its success.  G: They think it has expanded too quickly.',
    'Amsterdam', 'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 250
UNION ALL SELECT @tid, 3, 26, NULL, 'Dublin',       'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 260
UNION ALL SELECT @tid, 3, 27, NULL, 'London',       'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 270
UNION ALL SELECT @tid, 3, 28, NULL, 'Buenos Aires', 'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL, 'New York',     'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL, 'Sydney',       'matching', 'Choose SIX answers from the box and write the correct letter, A–G, next to Questions 25–30.', 1.0, 300

-- Part 4: The extinction of the dodo bird (note completion Q31–40)
UNION ALL SELECT @tid, 4, 31, 'THE EXTINCTION OF THE DODO BIRD — The dodo was a large flightless bird which used to inhabit the island of Mauritius.', '1507 — Portuguese ships transporting ___ stopped at the island to collect food and water.', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310
UNION ALL SELECT @tid, 4, 32, NULL, '1638 — The Dutch established a ___ on the island.',                                          'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320
UNION ALL SELECT @tid, 4, 33, NULL, 'A Dutch painting suggests the dodo was very ___.',                                            'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330
UNION ALL SELECT @tid, 4, 34, NULL, 'The only remaining soft tissue is a dried ___.',                                              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340
UNION ALL SELECT @tid, 4, 35, NULL, 'Recent studies of a dodo skeleton suggest the birds were capable of rapid ___.',               'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350
UNION ALL SELECT @tid, 4, 36, NULL, 'It''s thought they were able to use their small wings to maintain ___.',                      'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360
UNION ALL SELECT @tid, 4, 37, NULL, 'Their ___ was of average size.',                                                              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370
UNION ALL SELECT @tid, 4, 38, NULL, 'Their sense of ___ enabled them to find food.',                                              'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380
UNION ALL SELECT @tid, 4, 39, NULL, '___ also escaped onto the island and ate the birds'' eggs.',                                  'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390
UNION ALL SELECT @tid, 4, 40, NULL, 'The arrival of farming meant the ___ was destroyed.',                                        'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400

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
-- (Choose-TWO pairs Q21-24 score via mock_save_section.php's
--  $pair_defs, but is_correct is still set here for the admin review UI.)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- Q11: main concern about traffic (correct: C)
SELECT @q11 AS question_id, 'A' AS option_label, 'cuts to public transport' AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q11, 'B', 'poor maintenance of roads', 0, 20
UNION ALL SELECT @q11, 'C', 'changes in the type of traffic', 1, 30

-- Q12: cycle path change (correct: A)
UNION ALL SELECT @q12, 'A', 'It will be widened.', 1, 10
UNION ALL SELECT @q12, 'B', 'It will be extended.', 0, 20
UNION ALL SELECT @q12, 'C', 'It will be resurfaced.', 0, 30

-- Q13: pedestrian crossing postponed (correct: B)
UNION ALL SELECT @q13, 'A', 'the Post Office has moved.', 0, 10
UNION ALL SELECT @q13, 'B', 'the proposed location is unsafe.', 1, 20
UNION ALL SELECT @q13, 'C', 'funding is not available at present.', 0, 30

-- Q14: Station Road notices (correct: B)
UNION ALL SELECT @q14, 'A', 'telling cyclists not to leave their bikes outside the station ticket office.', 0, 10
UNION ALL SELECT @q14, 'B', 'asking motorists to switch off engines when waiting at the level crossing.', 1, 20
UNION ALL SELECT @q14, 'C', 'warning pedestrians to leave enough time when crossing the railway line.', 0, 30

-- Q21&22: bike-sharing benefits (correct: B, C)
UNION ALL SELECT @q21, 'A', 'reducing noise pollution', 0, 10
UNION ALL SELECT @q21, 'B', 'reducing traffic congestion', 1, 20
UNION ALL SELECT @q21, 'C', 'improving air quality', 1, 30
UNION ALL SELECT @q21, 'D', 'encouraging health and fitness', 0, 40
UNION ALL SELECT @q21, 'E', 'making cycling affordable', 0, 50

-- Q23&24: necessary for successful schemes (correct: B, C)
UNION ALL SELECT @q23, 'A', 'Bikes should have a GPS system.', 0, 10
UNION ALL SELECT @q23, 'B', 'The app should be easy to use.', 1, 20
UNION ALL SELECT @q23, 'C', 'Public awareness should be raised.', 1, 30
UNION ALL SELECT @q23, 'D', 'Only one scheme should be available.', 0, 40
UNION ALL SELECT @q23, 'E', 'There should be a large network of cycle lanes.', 0, 50

-- Q25-30 matching box (A-G) — is_correct not meaningful here; per-question answer lives in question_correct_answers
UNION ALL SELECT @q25, 'A', 'They agree it has been disappointing.', 0, 10
UNION ALL SELECT @q25, 'B', 'They think it should be cheaper.', 0, 20
UNION ALL SELECT @q25, 'C', 'They are surprised it has been so successful.', 0, 30
UNION ALL SELECT @q25, 'D', 'They agree that more investment is required.', 0, 40
UNION ALL SELECT @q25, 'E', 'They think the system has been well designed.', 0, 50
UNION ALL SELECT @q25, 'F', 'They disagree about the reasons for its success.', 0, 60
UNION ALL SELECT @q25, 'G', 'They think it has expanded too quickly.', 0, 70

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- (form_note_completion text answers + map/matching letters for Q15-20, Q25-30)
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Part 1 Q1–10
SELECT @q1  AS question_id, '28th'    AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2,  '550',      0, 0
UNION ALL SELECT @q3,  'Chervil',  0, 0
UNION ALL SELECT @q4,  'garage',   0, 0
UNION ALL SELECT @q5,  'garden',   0, 0
UNION ALL SELECT @q6,  'parking',  0, 0
UNION ALL SELECT @q7,  'wood',     0, 0
UNION ALL SELECT @q8,  'bridge',   0, 0
UNION ALL SELECT @q9,  'monument', 0, 0
UNION ALL SELECT @q10, 'March',    0, 0

-- Part 2 Q15–20 map letters
UNION ALL SELECT @q15, 'C', 0, 0
UNION ALL SELECT @q16, 'F', 0, 0
UNION ALL SELECT @q17, 'A', 0, 0
UNION ALL SELECT @q18, 'I', 0, 0
UNION ALL SELECT @q19, 'E', 0, 0
UNION ALL SELECT @q20, 'H', 0, 0

-- Part 3 Q25–30 matching letters
UNION ALL SELECT @q25, 'C', 0, 0
UNION ALL SELECT @q26, 'F', 0, 0
UNION ALL SELECT @q27, 'D', 0, 0
UNION ALL SELECT @q28, 'E', 0, 0
UNION ALL SELECT @q29, 'B', 0, 0
UNION ALL SELECT @q30, 'A', 0, 0

-- Part 4 Q31–40 (alternatives for Q31, Q32, Q36)
UNION ALL SELECT @q31, 'spice',     0, 0
UNION ALL SELECT @q31, 'spices',    0, 1
UNION ALL SELECT @q32, 'colony',    0, 0
UNION ALL SELECT @q32, 'settlement', 0, 1
UNION ALL SELECT @q33, 'fat',       0, 0
UNION ALL SELECT @q34, 'head',      0, 0
UNION ALL SELECT @q35, 'movement',  0, 0
UNION ALL SELECT @q36, 'balance',   0, 0
UNION ALL SELECT @q36, 'balancing', 0, 1
UNION ALL SELECT @q37, 'brain',     0, 0
UNION ALL SELECT @q38, 'smell',     0, 0
UNION ALL SELECT @q39, 'rats',      0, 0
UNION ALL SELECT @q40, 'forest',    0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, 29 options (3+3+3+3+5+5+7), 35 correct answers (10+6+6+13)
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
