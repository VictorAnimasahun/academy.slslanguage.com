-- ============================================================
-- Migration 035 — Seed IELTS_FM4_R (Reading Full Mock Test 4)
-- Cambridge IELTS 16 Test 4 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
--
-- Scoring paths (matches mock_save_section.php precedence):
--   Q1–8   matching (boot review, A–G)         → question_correct_answers + question_options (per-question copy, per FM1/FM2/FM3 convention)
--   Q9–14  true_false_not_given                → question_correct_answers
--   Q15–20 sentence_completion                 → question_correct_answers
--   Q21–27 form_note_completion                → question_correct_answers
--   Q28–31 multiple_choice_single               → question_options.is_correct=1 only
--   Q32–37 matching (organisation, A–D)         → question_correct_answers + question_options
--   Q38–40 summary_completion                   → question_correct_answers
--
-- Reading passages themselves live in full_mock_004_reading.php's $passages
-- array, not duplicated here in stimulus_text (stimulus_text on the first
-- question of each group is just a short title label).
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_R', 'IELTS Full Mock 4 — Reading',
       'Cambridge IELTS 16 Test 4 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_R');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM4_R' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Section 1 · Text A: "The best hiking boots" (Q1–8, matching A–G)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'The best hiking boots' AS stimulus_text,
    'These boots are a good choice for people who want to look smart when they are walking.' AS question_text,
    'matching' AS question_type,
    'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1, 2, NULL, 'People do not need to spend time getting their feet accustomed to these boots.',          'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 20
UNION ALL SELECT @tid, 1, 3, NULL, 'These boots should last for many years.',                                                  'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 30
UNION ALL SELECT @tid, 1, 4, NULL, 'People find these boots useful when travelling as they are not heavy.',                    'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 40
UNION ALL SELECT @tid, 1, 5, NULL, 'One feature of these boots does not continue to be effective for very long.',              'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 50
UNION ALL SELECT @tid, 1, 6, NULL, 'These boots do not keep the rain out.',                                                   'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 60
UNION ALL SELECT @tid, 1, 7, NULL, 'It is important to make sure these boots are done up tightly before starting a walk.',     'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 70
UNION ALL SELECT @tid, 1, 8, NULL, 'These boots should suit people who don''t want to spend a lot.',                          'matching', 'For which hiking boots are the following statements true? Write the correct letter, A–G. NB You may use any letter more than once.', 1.0, 80

-- Section 1 · Text B: "Beekeeping workshop at Elm Farm" (Q9–14, TRUE/FALSE/NOT GIVEN)
UNION ALL SELECT @tid, 1, 9, 'Beekeeping workshop at Elm Farm',
    'The workshop is only suitable for people who already keep their own bees.', 'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 90
UNION ALL SELECT @tid, 1, 10, NULL, 'Participants will meet people who are involved in selling honey to the public.',         'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 100
UNION ALL SELECT @tid, 1, 11, NULL, 'Vegetarian refreshments are available if requested in advance.',                          'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 110
UNION ALL SELECT @tid, 1, 12, NULL, 'Participants will need to pay extra to hire appropriate clothes for the workshop.',       'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 120
UNION ALL SELECT @tid, 1, 13, NULL, 'Protective footwear will be required during the workshop.',                               'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 130
UNION ALL SELECT @tid, 1, 14, NULL, 'If someone has to cancel before the workshop, the fee will be repaid.',                    'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE if the statement agrees with the information, FALSE if the statement contradicts the information, NOT GIVEN if there is no information on this.', 1.0, 140

-- Section 2 · Text C: "Should you pay someone to write your CV?" (Q15–20, sentence completion)
UNION ALL SELECT @tid, 2, 15, 'Should you pay someone to write your CV?',
    'Some jobseekers have difficulty with their CV because they have not learnt which qualities they should ___.', 'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL, 'Professional CV writers know which ___ are best left out of the CV.',                                                      'sentence_completion', 'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL, 'CV writers with knowledge of a particular field of work often provide useful ___ about the skills firms expect from job applicants.', 'sentence_completion', 'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL, 'It is advisable to request ___ of what a professional CV writer has previously produced.',                                  'sentence_completion', 'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL, 'Professional CV writers often ask jobseekers to work through a ___ as a first step.',                                       'sentence_completion', 'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL, 'If the jobseeker assists the professional writer, the tone of the CV will be ___.',                                         'sentence_completion', 'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 200

-- Section 2 · Text D: "Starting a new job" (Q21–27, note completion)
UNION ALL SELECT @tid, 2, 21, 'Starting a new job',
    'Before arriving at work — try out a different morning ___ that will create a sense of well-being.', 'form_note_completion',
    'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 210
UNION ALL SELECT @tid, 2, 22, NULL, 'Make sure your chosen outfit conforms to the company''s ___.',                                            'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 220
UNION ALL SELECT @tid, 2, 23, NULL, 'If you eat with colleagues at midday — it will provide information on their ___ and the way they operate.', 'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 230
UNION ALL SELECT @tid, 2, 24, NULL, 'It may be wise to prepare some ___ to help the interaction flow.',                                          'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 240
UNION ALL SELECT @tid, 2, 25, NULL, 'During the first few weeks — work out some ___ and how to go about fulfilling them.',                        'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 250
UNION ALL SELECT @tid, 2, 26, NULL, 'Try to keep a completely ___ as you settle into the post.',                                                  'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 260
UNION ALL SELECT @tid, 2, 27, NULL, 'Avoid making proposals for ___ too soon.',                                                                   'form_note_completion', 'Complete the notes below. Choose NO MORE THAN TWO WORDS from the text for each answer.', 1.0, 270

-- Section 3: "History of women's football in Britain" (Q28–40)
-- Q28–31: multiple_choice_single
UNION ALL SELECT @tid, 3, 28, 'History of women''s football in Britain',
    'In the first paragraph, the writer says that in 18th-century Scotland', 'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL, 'The writer says that Nettie J Honeyball was unwilling to',                       'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL, 'The writer suggests that in Britain, between 1895 and 1914,',                     'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 300
UNION ALL SELECT @tid, 3, 31, NULL, 'After the First World War broke out in 1914, factory managers',                  'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 310

-- Q32–37: matching (organisation, A–D)
UNION ALL SELECT @tid, 3, 32, NULL, 'It felt threatened by the rise of women''s football.',                'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 320
UNION ALL SELECT @tid, 3, 33, NULL, 'It was established by a male office worker.',                          'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 330
UNION ALL SELECT @tid, 3, 34, NULL, 'It donated money from football matches to good causes.',               'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 340
UNION ALL SELECT @tid, 3, 35, NULL, 'It called for the ending of the ban on women''s football in Britain.', 'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 350
UNION ALL SELECT @tid, 3, 36, NULL, 'It was accused of being old-fashioned.',                                'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 360
UNION ALL SELECT @tid, 3, 37, NULL, 'It was led by a believer in women''s rights.',                           'matching', 'Match each statement with the correct organisation, A, B, C or D. NB You may use any letter more than once.', 1.0, 370

-- Q38–40: summary completion
UNION ALL SELECT @tid, 3, 38, 'A catastrophic year for women''s football',
    'At the end of 1921, women''s football teams were forbidden to use the ___ of the Football Association.', 'summary_completion',
    'Complete the summary below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 380
UNION ALL SELECT @tid, 3, 39, NULL, 'They were not allowed to have Football Association members as ___.',     'summary_completion', 'Complete the summary below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 390
UNION ALL SELECT @tid, 3, 40, NULL, 'Female workers accused the FA of ___ against women, but the ban continued until 1971.', 'summary_completion', 'Complete the summary below. Choose ONE WORD ONLY from the text for each answer.', 1.0, 400

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
-- Step 4: Insert matching + MCQ options
-- (Matching questions each get their own full copy of the box, per
--  FM1/FM2/FM3 convention — the Reading UI's dropdown reads options per
--  question_id, unlike Listening's shared-box + free-text input.)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- Q1–8: hiking boot review matching (correct: D, B, A, C, F, C, E, G)
SELECT @q1 AS question_id, 'A' AS option_label, 'Hanwag Tatra Boots' AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q1, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q1, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q1, 'D', 'Danner Jag', 1, 40
UNION ALL SELECT @q1, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q1, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q1, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q2, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q2, 'B', 'Scarpa Peak Gore-tex Boots', 1, 20
UNION ALL SELECT @q2, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q2, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q2, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q2, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q2, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q3, 'A', 'Hanwag Tatra Boots', 1, 10
UNION ALL SELECT @q3, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q3, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q3, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q3, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q3, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q3, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q4, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q4, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q4, 'C', 'Keen Terradora Ethos', 1, 30
UNION ALL SELECT @q4, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q4, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q4, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q4, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q5, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q5, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q5, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q5, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q5, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q5, 'F', 'Teva Arrowood Mid WP', 1, 60
UNION ALL SELECT @q5, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q6, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q6, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q6, 'C', 'Keen Terradora Ethos', 1, 30
UNION ALL SELECT @q6, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q6, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q6, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q6, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q7, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q7, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q7, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q7, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q7, 'E', 'Merrell Siren Sport Q2 Mid Boots', 1, 50
UNION ALL SELECT @q7, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q7, 'G', 'Regatta Clydebank Mid Boots', 0, 70

UNION ALL SELECT @q8, 'A', 'Hanwag Tatra Boots', 0, 10
UNION ALL SELECT @q8, 'B', 'Scarpa Peak Gore-tex Boots', 0, 20
UNION ALL SELECT @q8, 'C', 'Keen Terradora Ethos', 0, 30
UNION ALL SELECT @q8, 'D', 'Danner Jag', 0, 40
UNION ALL SELECT @q8, 'E', 'Merrell Siren Sport Q2 Mid Boots', 0, 50
UNION ALL SELECT @q8, 'F', 'Teva Arrowood Mid WP', 0, 60
UNION ALL SELECT @q8, 'G', 'Regatta Clydebank Mid Boots', 1, 70

-- Q28: 18th-century Scotland (correct: D)
UNION ALL SELECT @q28, 'A', 'only unmarried women were allowed to play football.', 0, 10
UNION ALL SELECT @q28, 'B', 'women''s football was more common than men''s football.', 0, 20
UNION ALL SELECT @q28, 'C', 'women were sometimes forbidden to watch football matches.', 0, 30
UNION ALL SELECT @q28, 'D', 'skill at football might be considered when choosing a wife.', 1, 40

-- Q29: Nettie J Honeyball unwilling to (correct: C)
UNION ALL SELECT @q29, 'A', 'take an active part in team sports.', 0, 10
UNION ALL SELECT @q29, 'B', 'mix with people she considered lower class.', 0, 20
UNION ALL SELECT @q29, 'C', 'let the public know of her involvement in football.', 1, 30
UNION ALL SELECT @q29, 'D', 'take a leadership role in the British Ladies'' Football Club.', 0, 40

-- Q30: 1895–1914 (correct: A)
UNION ALL SELECT @q30, 'A', 'society was not yet ready for women''s football.', 1, 10
UNION ALL SELECT @q30, 'B', 'there were false reports of the decline of women''s football.', 0, 20
UNION ALL SELECT @q30, 'C', 'the media felt that women''s football should not be allowed.', 0, 30
UNION ALL SELECT @q30, 'D', 'women''s football mainly attracted people because it was unusual.', 0, 40

-- Q31: factory managers after 1914 (correct: D)
UNION ALL SELECT @q31, 'A', 'were initially unwilling to employ women.', 0, 10
UNION ALL SELECT @q31, 'B', 'played in matches against female employees.', 0, 20
UNION ALL SELECT @q31, 'C', 'allowed extra time for their employees to play football.', 0, 30
UNION ALL SELECT @q31, 'D', 'decided that women''s football might have positive effects.', 1, 40

-- Q32–37: football organisations matching box (correct: C, B, A, D, C, A)
UNION ALL SELECT @q32, 'A', 'the British Ladies'' Football Club (BLFC)', 0, 10
UNION ALL SELECT @q32, 'B', 'the Dick, Kerr''s Ladies team', 0, 20
UNION ALL SELECT @q32, 'C', 'the Football Association (FA)', 1, 30
UNION ALL SELECT @q32, 'D', 'the Union of European Football Associations (UEFA)', 0, 40

UNION ALL SELECT @q33, 'A', 'the British Ladies'' Football Club (BLFC)', 0, 10
UNION ALL SELECT @q33, 'B', 'the Dick, Kerr''s Ladies team', 1, 20
UNION ALL SELECT @q33, 'C', 'the Football Association (FA)', 0, 30
UNION ALL SELECT @q33, 'D', 'the Union of European Football Associations (UEFA)', 0, 40

UNION ALL SELECT @q34, 'A', 'the British Ladies'' Football Club (BLFC)', 1, 10
UNION ALL SELECT @q34, 'B', 'the Dick, Kerr''s Ladies team', 0, 20
UNION ALL SELECT @q34, 'C', 'the Football Association (FA)', 0, 30
UNION ALL SELECT @q34, 'D', 'the Union of European Football Associations (UEFA)', 0, 40

UNION ALL SELECT @q35, 'A', 'the British Ladies'' Football Club (BLFC)', 0, 10
UNION ALL SELECT @q35, 'B', 'the Dick, Kerr''s Ladies team', 0, 20
UNION ALL SELECT @q35, 'C', 'the Football Association (FA)', 0, 30
UNION ALL SELECT @q35, 'D', 'the Union of European Football Associations (UEFA)', 1, 40

UNION ALL SELECT @q36, 'A', 'the British Ladies'' Football Club (BLFC)', 0, 10
UNION ALL SELECT @q36, 'B', 'the Dick, Kerr''s Ladies team', 0, 20
UNION ALL SELECT @q36, 'C', 'the Football Association (FA)', 1, 30
UNION ALL SELECT @q36, 'D', 'the Union of European Football Associations (UEFA)', 0, 40

UNION ALL SELECT @q37, 'A', 'the British Ladies'' Football Club (BLFC)', 1, 10
UNION ALL SELECT @q37, 'B', 'the Dick, Kerr''s Ladies team', 0, 20
UNION ALL SELECT @q37, 'C', 'the Football Association (FA)', 0, 30
UNION ALL SELECT @q37, 'D', 'the Union of European Football Associations (UEFA)', 0, 40

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Q1–8 boot review letters
SELECT @q1 AS question_id, 'D' AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2, 'B', 0, 0
UNION ALL SELECT @q3, 'A', 0, 0
UNION ALL SELECT @q4, 'C', 0, 0
UNION ALL SELECT @q5, 'F', 0, 0
UNION ALL SELECT @q6, 'C', 0, 0
UNION ALL SELECT @q7, 'E', 0, 0
UNION ALL SELECT @q8, 'G', 0, 0

-- Q9–14 TRUE/FALSE/NOT GIVEN
UNION ALL SELECT @q9,  'FALSE', 0, 0
UNION ALL SELECT @q10, 'NOT GIVEN', 0, 0
UNION ALL SELECT @q11, 'NOT GIVEN', 0, 0
UNION ALL SELECT @q12, 'FALSE', 0, 0
UNION ALL SELECT @q13, 'TRUE', 0, 0
UNION ALL SELECT @q14, 'FALSE', 0, 0

-- Q15–20 sentence completion
UNION ALL SELECT @q15, 'highlight', 0, 0
UNION ALL SELECT @q16, 'details', 0, 0
UNION ALL SELECT @q17, 'insights', 0, 0
UNION ALL SELECT @q18, 'samples', 0, 0
UNION ALL SELECT @q19, 'questionnaire', 0, 0
UNION ALL SELECT @q20, 'authentic', 0, 0

-- Q21–27 note completion
UNION ALL SELECT @q21, 'routine', 0, 0
UNION ALL SELECT @q22, 'dress code', 0, 0
UNION ALL SELECT @q23, 'personalities', 0, 0
UNION ALL SELECT @q24, 'conversation starters', 0, 0
UNION ALL SELECT @q25, 'goals', 0, 0
UNION ALL SELECT @q26, 'open mind', 0, 0
UNION ALL SELECT @q27, 'improvements', 0, 0

-- Q32–37 organisation letters
UNION ALL SELECT @q32, 'C', 0, 0
UNION ALL SELECT @q33, 'B', 0, 0
UNION ALL SELECT @q34, 'A', 0, 0
UNION ALL SELECT @q35, 'D', 0, 0
UNION ALL SELECT @q36, 'C', 0, 0
UNION ALL SELECT @q37, 'A', 0, 0

-- Q38–40 summary completion
UNION ALL SELECT @q38, 'grounds', 0, 0
UNION ALL SELECT @q39, 'referees', 0, 0
UNION ALL SELECT @q40, 'prejudice', 0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, 96 options (8*7 + 4*4 + 6*4), 36 correct answers (8+6+6+7+6+3)
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
