-- ============================================================
-- Migration 030 — Seed IELTS_FM3_R (Reading Full Mock Test 3)
-- Cambridge IELTS GT Test 3 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
--
-- Scoring paths (matches mock_save_section.php precedence):
--   Q1–5   matching (paragraph reference, A–E) → question_correct_answers + question_options (per-question copy, per FM1/FM2 convention)
--   Q6–14  true_false_not_given               → question_correct_answers
--   Q15–22 form_note_completion               → question_correct_answers
--   Q23–27 form_note_completion (flow chart)  → question_correct_answers
--   Q28–33 matching (heading, i–viii)         → question_correct_answers + question_options
--   Q34–36 multiple_choice_single             → question_options.is_correct=1 only
--   Q37–40 matching (sentence ending, A–G)    → question_correct_answers + question_options
--
-- Reading passages themselves live in full_mock_003_reading.php's $passages
-- array, not duplicated here in stimulus_text (stimulus_text on the first
-- question of each group is just a short title label).
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM3_R', 'IELTS Full Mock 3 — Reading',
       'Cambridge IELTS GT Test 3 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_R');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM3_R' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- Section 1 · Passage 1: "Maps showing walks starting from Bingham Town Hall" (Q1–5, paragraph matching)
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'Maps showing walks starting from Bingham Town Hall' AS stimulus_text,
    'the chance to go into caves' AS question_text,
    'matching' AS question_type,
    'Which paragraph mentions the following? Write the correct letter, A–E. NB You may use any letter more than once.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1, 2, NULL, 'the chance to spend time beside a lake',          'matching', 'Which paragraph mentions the following? Write the correct letter, A–E. NB You may use any letter more than once.', 1.0, 20
UNION ALL SELECT @tid, 1, 3, NULL, 'some unusual architecture',                       'matching', 'Which paragraph mentions the following? Write the correct letter, A–E. NB You may use any letter more than once.', 1.0, 30
UNION ALL SELECT @tid, 1, 4, NULL, 'unsuitability for young children',                'matching', 'Which paragraph mentions the following? Write the correct letter, A–E. NB You may use any letter more than once.', 1.0, 40
UNION ALL SELECT @tid, 1, 5, NULL, 'the length of the walk depending on the weather', 'matching', 'Which paragraph mentions the following? Write the correct letter, A–E. NB You may use any letter more than once.', 1.0, 50

-- Section 1 · Passage 2: "The Maplehampton scarecrow competition" (Q6–14, TRUE/FALSE/NOT GIVEN)
UNION ALL SELECT @tid, 1, 6, 'The Maplehampton scarecrow competition – a great success!',
    'Traditionally, most scarecrows were the same size as a human being.', 'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 60
UNION ALL SELECT @tid, 1,  7, NULL, 'The competition in September was the first one in Maplehampton.',                'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0,  70
UNION ALL SELECT @tid, 1,  8, NULL, 'The farmers who provided materials could take part in the competition.',         'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0,  80
UNION ALL SELECT @tid, 1,  9, NULL, 'Old clothes were supplied to the people who made the scarecrows.',               'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0,  90
UNION ALL SELECT @tid, 1, 10, NULL, 'The venue for the competition was changed because of the weather.',              'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 100
UNION ALL SELECT @tid, 1, 11, NULL, 'Competitors could get advice on making their scarecrows.',                       'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 110
UNION ALL SELECT @tid, 1, 12, NULL, 'In the judges'' opinion, the scarecrow dressed as an alien was better than the giant bird.', 'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 120
UNION ALL SELECT @tid, 1, 13, NULL, 'The competition organisers supplied a picnic for the competitors and spectators.', 'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 130
UNION ALL SELECT @tid, 1, 14, NULL, 'Alice Cameron bought a scarecrow to frighten birds away from her crops.',        'true_false_not_given', 'Do the following statements agree with the information given in the text? Write TRUE, FALSE, or NOT GIVEN.', 1.0, 140

-- Section 2 · Passage 1: "Qualities that make a great barista" (Q15–22, note completion)
UNION ALL SELECT @tid, 2, 15, 'Qualities that make a great barista',
    'Be sure you make drinks that are ___ for the customer', 'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL, 'Ignore any ___ around you',                                'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL, 'Clean the machine ___ regularly',                           'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL, 'Always use ground coffee that is ___',                      'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL, 'Too early reduces the ___',                                 'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL, 'Too late makes the coffee ___',                             'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 200
UNION ALL SELECT @tid, 2, 21, NULL, 'Ask about the customers'' ___',                              'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 210
UNION ALL SELECT @tid, 2, 22, NULL, 'Know something about the important ___ in the area',        'form_note_completion', 'Complete the notes below. Choose ONE WORD ONLY for each answer.', 1.0, 220

-- Section 2 · Passage 2: "Running a meeting" (Q23–27, flow-chart completion)
UNION ALL SELECT @tid, 2, 23, 'Running a meeting',
    'In small meetings, ask people for some ___ as they introduce themselves', 'form_note_completion',
    'Complete the flow chart below. Choose NO MORE THAN TWO WORDS for each answer.', 1.0, 230
UNION ALL SELECT @tid, 2, 24, NULL, 'Make sure the ___ is available to everyone',                       'form_note_completion', 'Complete the flow chart below. Choose NO MORE THAN TWO WORDS for each answer.', 1.0, 240
UNION ALL SELECT @tid, 2, 25, NULL, 'Involve people in the discussion and solve any ___ quickly if they arise', 'form_note_completion', 'Complete the flow chart below. Choose NO MORE THAN TWO WORDS for each answer.', 1.0, 250
UNION ALL SELECT @tid, 2, 26, NULL, 'Avoid ___ by involving a range of people in tasks',                'form_note_completion', 'Complete the flow chart below. Choose NO MORE THAN TWO WORDS for each answer.', 1.0, 260
UNION ALL SELECT @tid, 2, 27, NULL, 'Thank people for coming, and possibly have some kind of ___ afterwards', 'form_note_completion', 'Complete the flow chart below. Choose NO MORE THAN TWO WORDS for each answer.', 1.0, 270

-- Section 3: "Feathers as decoration in European history" (Q28–40)
-- Q28–33: heading matching (i–viii)
UNION ALL SELECT @tid, 3, 28, 'Feathers as decoration in European history',
    'Section A', 'matching',
    'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL, 'Section B', 'matching', 'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL, 'Section C', 'matching', 'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 300
UNION ALL SELECT @tid, 3, 31, NULL, 'Section D', 'matching', 'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 310
UNION ALL SELECT @tid, 3, 32, NULL, 'Section E', 'matching', 'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 320
UNION ALL SELECT @tid, 3, 33, NULL, 'Section F', 'matching', 'The text has six sections, A–F. Choose the correct heading for each section from the list of headings below. Write the correct number, i–viii.', 1.0, 330

-- Q34–36: multiple_choice_single
UNION ALL SELECT @tid, 3, 34, NULL, 'In Section B, what information is given about the use of feathers in the 16th century?', 'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 340
UNION ALL SELECT @tid, 3, 35, NULL, 'Rublack suggests the feather costume worn by Duke Frederick in 1599 represented',         'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 350
UNION ALL SELECT @tid, 3, 36, NULL, 'According to Rublack, one reason why feathers survived in European military costume was because', 'multiple_choice_single', 'Choose the correct letter, A, B, C or D.', 1.0, 360

-- Q37–40: matching (sentence ending, A–G)
UNION ALL SELECT @tid, 3, 37, NULL, 'Hats decorated with long black feathers', 'matching', 'Complete each sentence with the correct ending, A–G, below.', 1.0, 370
UNION ALL SELECT @tid, 3, 38, NULL, 'Feathers from cranes and swallows',       'matching', 'Complete each sentence with the correct ending, A–G, below.', 1.0, 380
UNION ALL SELECT @tid, 3, 39, NULL, 'Feathers from exotic birds',              'matching', 'Complete each sentence with the correct ending, A–G, below.', 1.0, 390
UNION ALL SELECT @tid, 3, 40, NULL, 'Peacock feathers',                        'matching', 'Complete each sentence with the correct ending, A–G, below.', 1.0, 400

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
--  FM1/FM2 convention — the Reading UI's dropdown reads options per
--  question_id, unlike Listening's shared-box + free-text input.)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- Q1–5: paragraph matching (correct: E, D, C, A, C)
SELECT @q1 AS question_id, 'A' AS option_label, 'Paragraph A' AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q1, 'B', 'Paragraph B', 0, 20
UNION ALL SELECT @q1, 'C', 'Paragraph C', 0, 30
UNION ALL SELECT @q1, 'D', 'Paragraph D', 0, 40
UNION ALL SELECT @q1, 'E', 'Paragraph E', 1, 50

UNION ALL SELECT @q2, 'A', 'Paragraph A', 0, 10
UNION ALL SELECT @q2, 'B', 'Paragraph B', 0, 20
UNION ALL SELECT @q2, 'C', 'Paragraph C', 0, 30
UNION ALL SELECT @q2, 'D', 'Paragraph D', 1, 40
UNION ALL SELECT @q2, 'E', 'Paragraph E', 0, 50

UNION ALL SELECT @q3, 'A', 'Paragraph A', 0, 10
UNION ALL SELECT @q3, 'B', 'Paragraph B', 0, 20
UNION ALL SELECT @q3, 'C', 'Paragraph C', 1, 30
UNION ALL SELECT @q3, 'D', 'Paragraph D', 0, 40
UNION ALL SELECT @q3, 'E', 'Paragraph E', 0, 50

UNION ALL SELECT @q4, 'A', 'Paragraph A', 1, 10
UNION ALL SELECT @q4, 'B', 'Paragraph B', 0, 20
UNION ALL SELECT @q4, 'C', 'Paragraph C', 0, 30
UNION ALL SELECT @q4, 'D', 'Paragraph D', 0, 40
UNION ALL SELECT @q4, 'E', 'Paragraph E', 0, 50

UNION ALL SELECT @q5, 'A', 'Paragraph A', 0, 10
UNION ALL SELECT @q5, 'B', 'Paragraph B', 0, 20
UNION ALL SELECT @q5, 'C', 'Paragraph C', 1, 30
UNION ALL SELECT @q5, 'D', 'Paragraph D', 0, 40
UNION ALL SELECT @q5, 'E', 'Paragraph E', 0, 50

-- Q28–33: heading matching (correct: vii, iii, i, vi, viii, v)
UNION ALL SELECT @q28, 'i',    'The link between feathers and a wider international awareness', 0, 10
UNION ALL SELECT @q28, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q28, 'iii',  'A significant rise in the popularity of feathers', 0, 30
UNION ALL SELECT @q28, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q28, 'v',    'A new approach to researching the past', 0, 50
UNION ALL SELECT @q28, 'vi',   'Feathers as protection and as a symbol of sophistication', 0, 60
UNION ALL SELECT @q28, 'vii',  'An interesting relationship between the wearing of feathers and gender', 1, 70
UNION ALL SELECT @q28, 'viii', 'A reason for the continued use of feathers by soldiers', 0, 80

UNION ALL SELECT @q29, 'i',    'The link between feathers and a wider international awareness', 0, 10
UNION ALL SELECT @q29, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q29, 'iii',  'A significant rise in the popularity of feathers', 1, 30
UNION ALL SELECT @q29, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q29, 'v',    'A new approach to researching the past', 0, 50
UNION ALL SELECT @q29, 'vi',   'Feathers as protection and as a symbol of sophistication', 0, 60
UNION ALL SELECT @q29, 'vii',  'An interesting relationship between the wearing of feathers and gender', 0, 70
UNION ALL SELECT @q29, 'viii', 'A reason for the continued use of feathers by soldiers', 0, 80

UNION ALL SELECT @q30, 'i',    'The link between feathers and a wider international awareness', 1, 10
UNION ALL SELECT @q30, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q30, 'iii',  'A significant rise in the popularity of feathers', 0, 30
UNION ALL SELECT @q30, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q30, 'v',    'A new approach to researching the past', 0, 50
UNION ALL SELECT @q30, 'vi',   'Feathers as protection and as a symbol of sophistication', 0, 60
UNION ALL SELECT @q30, 'vii',  'An interesting relationship between the wearing of feathers and gender', 0, 70
UNION ALL SELECT @q30, 'viii', 'A reason for the continued use of feathers by soldiers', 0, 80

UNION ALL SELECT @q31, 'i',    'The link between feathers and a wider international awareness', 0, 10
UNION ALL SELECT @q31, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q31, 'iii',  'A significant rise in the popularity of feathers', 0, 30
UNION ALL SELECT @q31, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q31, 'v',    'A new approach to researching the past', 0, 50
UNION ALL SELECT @q31, 'vi',   'Feathers as protection and as a symbol of sophistication', 1, 60
UNION ALL SELECT @q31, 'vii',  'An interesting relationship between the wearing of feathers and gender', 0, 70
UNION ALL SELECT @q31, 'viii', 'A reason for the continued use of feathers by soldiers', 0, 80

UNION ALL SELECT @q32, 'i',    'The link between feathers and a wider international awareness', 0, 10
UNION ALL SELECT @q32, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q32, 'iii',  'A significant rise in the popularity of feathers', 0, 30
UNION ALL SELECT @q32, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q32, 'v',    'A new approach to researching the past', 0, 50
UNION ALL SELECT @q32, 'vi',   'Feathers as protection and as a symbol of sophistication', 0, 60
UNION ALL SELECT @q32, 'vii',  'An interesting relationship between the wearing of feathers and gender', 0, 70
UNION ALL SELECT @q32, 'viii', 'A reason for the continued use of feathers by soldiers', 1, 80

UNION ALL SELECT @q33, 'i',    'The link between feathers and a wider international awareness', 0, 10
UNION ALL SELECT @q33, 'ii',   'An unsuitable decoration for military purposes', 0, 20
UNION ALL SELECT @q33, 'iii',  'A significant rise in the popularity of feathers', 0, 30
UNION ALL SELECT @q33, 'iv',   'Growing disapproval of the trapping of birds for their feathers', 0, 40
UNION ALL SELECT @q33, 'v',    'A new approach to researching the past', 1, 50
UNION ALL SELECT @q33, 'vi',   'Feathers as protection and as a symbol of sophistication', 0, 60
UNION ALL SELECT @q33, 'vii',  'An interesting relationship between the wearing of feathers and gender', 0, 70
UNION ALL SELECT @q33, 'viii', 'A reason for the continued use of feathers by soldiers', 0, 80

-- Q34: 16th-century feather use info (correct: B)
UNION ALL SELECT @q34, 'A', 'Some were not real feathers, but imitations.', 0, 10
UNION ALL SELECT @q34, 'B', 'They were sometimes coloured artificially.', 1, 20
UNION ALL SELECT @q34, 'C', 'Birds were specially bred for their feathers.', 0, 30
UNION ALL SELECT @q34, 'D', 'There was some disapproval of their use for decoration.', 0, 40

-- Q35: Duke Frederick's feather costume (correct: C)
UNION ALL SELECT @q35, 'A', 'a lack of sensitivity to American traditions.', 0, 10
UNION ALL SELECT @q35, 'B', 'a rejection of the beliefs held by those around him.', 0, 20
UNION ALL SELECT @q35, 'C', 'a positive attitude towards the culture of the Americas.', 1, 30
UNION ALL SELECT @q35, 'D', 'a wish to follow a fashion of the time.', 0, 40

-- Q36: feathers surviving in military costume (correct: B)
UNION ALL SELECT @q36, 'A', 'birds were seen as having religious significance.', 0, 10
UNION ALL SELECT @q36, 'B', 'feathers suggested certain qualities about military activities.', 1, 20
UNION ALL SELECT @q36, 'C', 'the power of feathers was feared by other cultures.', 0, 30
UNION ALL SELECT @q36, 'D', 'soldiers came to associate particular birds with warlike qualities.', 0, 40

-- Q37–40: sentence-ending matching box (correct: C, G, E, B)
UNION ALL SELECT @q37, 'A', 'lost popularity in the 16th century.', 0, 10
UNION ALL SELECT @q37, 'B', 'were used as protection from bad weather.', 0, 20
UNION ALL SELECT @q37, 'C', 'are worn today by some soldiers.', 1, 30
UNION ALL SELECT @q37, 'D', 'could only be worn by men of noble birth.', 0, 40
UNION ALL SELECT @q37, 'E', 'were used to create an outfit worn by a person of high status.', 0, 50
UNION ALL SELECT @q37, 'F', 'were once awarded for military achievements.', 0, 60
UNION ALL SELECT @q37, 'G', 'became popular decorations for urban dwellers in the 16th century.', 0, 70

UNION ALL SELECT @q38, 'A', 'lost popularity in the 16th century.', 0, 10
UNION ALL SELECT @q38, 'B', 'were used as protection from bad weather.', 0, 20
UNION ALL SELECT @q38, 'C', 'are worn today by some soldiers.', 0, 30
UNION ALL SELECT @q38, 'D', 'could only be worn by men of noble birth.', 0, 40
UNION ALL SELECT @q38, 'E', 'were used to create an outfit worn by a person of high status.', 0, 50
UNION ALL SELECT @q38, 'F', 'were once awarded for military achievements.', 0, 60
UNION ALL SELECT @q38, 'G', 'became popular decorations for urban dwellers in the 16th century.', 1, 70

UNION ALL SELECT @q39, 'A', 'lost popularity in the 16th century.', 0, 10
UNION ALL SELECT @q39, 'B', 'were used as protection from bad weather.', 0, 20
UNION ALL SELECT @q39, 'C', 'are worn today by some soldiers.', 0, 30
UNION ALL SELECT @q39, 'D', 'could only be worn by men of noble birth.', 0, 40
UNION ALL SELECT @q39, 'E', 'were used to create an outfit worn by a person of high status.', 1, 50
UNION ALL SELECT @q39, 'F', 'were once awarded for military achievements.', 0, 60
UNION ALL SELECT @q39, 'G', 'became popular decorations for urban dwellers in the 16th century.', 0, 70

UNION ALL SELECT @q40, 'A', 'lost popularity in the 16th century.', 0, 10
UNION ALL SELECT @q40, 'B', 'were used as protection from bad weather.', 1, 20
UNION ALL SELECT @q40, 'C', 'are worn today by some soldiers.', 0, 30
UNION ALL SELECT @q40, 'D', 'could only be worn by men of noble birth.', 0, 40
UNION ALL SELECT @q40, 'E', 'were used to create an outfit worn by a person of high status.', 0, 50
UNION ALL SELECT @q40, 'F', 'were once awarded for military achievements.', 0, 60
UNION ALL SELECT @q40, 'G', 'became popular decorations for urban dwellers in the 16th century.', 0, 70

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Q1–5 paragraph letters
SELECT @q1 AS question_id, 'E' AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2, 'D', 0, 0
UNION ALL SELECT @q3, 'C', 0, 0
UNION ALL SELECT @q4, 'A', 0, 0
UNION ALL SELECT @q5, 'C', 0, 0

-- Q6–14 TRUE/FALSE/NOT GIVEN
UNION ALL SELECT @q6,  'TRUE', 0, 0
UNION ALL SELECT @q7,  'NOT GIVEN', 0, 0
UNION ALL SELECT @q8,  'NOT GIVEN', 0, 0
UNION ALL SELECT @q9,  'FALSE', 0, 0
UNION ALL SELECT @q10, 'TRUE', 0, 0
UNION ALL SELECT @q11, 'TRUE', 0, 0
UNION ALL SELECT @q12, 'NOT GIVEN', 0, 0
UNION ALL SELECT @q13, 'FALSE', 0, 0
UNION ALL SELECT @q14, 'FALSE', 0, 0

-- Q15–22 note completion
UNION ALL SELECT @q15, 'correct', 0, 0
UNION ALL SELECT @q16, 'conversation', 0, 0
UNION ALL SELECT @q17, 'filter', 0, 0
UNION ALL SELECT @q18, 'fresh', 0, 0
UNION ALL SELECT @q19, 'flavour', 0, 0
UNION ALL SELECT @q19, 'flavor', 0, 1
UNION ALL SELECT @q20, 'bitter', 0, 0
UNION ALL SELECT @q21, 'day', 0, 0
UNION ALL SELECT @q22, 'issues', 0, 0

-- Q23–27 flow chart completion
UNION ALL SELECT @q23, 'relevant information', 0, 0
UNION ALL SELECT @q24, 'agenda', 0, 0
UNION ALL SELECT @q24, 'meeting agenda', 0, 1
UNION ALL SELECT @q25, 'conflicts', 0, 0
UNION ALL SELECT @q26, 'tension', 0, 0
UNION ALL SELECT @q27, 'social activity', 0, 0

-- Q28–33 heading numbers
UNION ALL SELECT @q28, 'vii', 0, 0
UNION ALL SELECT @q29, 'iii', 0, 0
UNION ALL SELECT @q30, 'i', 0, 0
UNION ALL SELECT @q31, 'vi', 0, 0
UNION ALL SELECT @q32, 'viii', 0, 0
UNION ALL SELECT @q33, 'v', 0, 0

-- Q37–40 sentence-ending letters
UNION ALL SELECT @q37, 'C', 0, 0
UNION ALL SELECT @q38, 'G', 0, 0
UNION ALL SELECT @q39, 'E', 0, 0
UNION ALL SELECT @q40, 'B', 0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- Verify: expect 40 questions, 113 options (5*5 + 6*8 + 3*4 + 4*7), 39 correct answers (5+9+9+6+6+4, incl. 2 alt-spelling/alt-wording rows for Q19 & Q24)
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
