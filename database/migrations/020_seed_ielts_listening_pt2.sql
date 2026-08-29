-- ============================================================
-- Migration 020 — Seed IELTS Listening Practice Test 2
-- Test code: IELTS_PT_L_002
-- Real source content: academy/resources/practice_tests/ielts_listening_002.php
-- NOTE: This file contains the real PT2 question bank, option data, and verified
-- answer key.
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_L_002',
       'IELTS Listening – Practice Test 2',
       'Listening practice test with 40 questions across Parts 1–4: form completion, MCQ, map labelling, matching, and note completion.',
       'IELTS',
       30,
       40,
       1,
       0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_L_002');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_002' LIMIT 1);

DELETE FROM question_correct_answers
WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
VALUES
(@tid, 1,  'Festival information', 'Name of the company: ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 10),
(@tid, 2,  'Festival information', 'The show was a comedy called ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 20),
(@tid, 3,  'Festival information', 'The festival includes a ___ show on the 20th evening', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 30),
(@tid, 4,  'Festival information', 'The evening show is called ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 40),
(@tid, 5,  'Workshops', 'Making ___ food', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 50),
(@tid, 6,  'Workshops', 'Making ___ (children only)', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 60),
(@tid, 7,  'Workshops', 'Making toys from ___ using various tools (adults only)', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 70),
(@tid, 8,  'Outdoor activities', 'Swimming in the ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 80),
(@tid, 9,  'Outdoor activities', 'Walking in the woods, led by an expert on ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 90),
(@tid, 10, 'Outdoor activities', 'See the festival organiser''s ___ for more information', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 100),

(@tid, 11, 'Minster Park', 'The park was originally established', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 110),
(@tid, 12, 'Minster Park', 'Why is there a statue of Diane Gosforth in the park?', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 120),
(@tid, 13, 'Minster Park', 'During the First World War, the park was mainly used for', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 130),
(@tid, 14, 'Minster Park', 'When did the physical transformation of the park begin?', 'multiple_choice_single', 'Choose the correct letter, A, B or C.', 1.0, 140),

(@tid, 15, 'Park map', 'statue of Diane Gosforth', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 150),
(@tid, 16, 'Park map', 'wooden sculptures', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 160),
(@tid, 17, 'Park map', 'playground', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 170),
(@tid, 18, 'Park map', 'maze', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 180),
(@tid, 19, 'Park map', 'tennis courts', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 190),
(@tid, 20, 'Park map', 'fitness area', 'diagram_map_labelling', 'Write the correct letter, A–I, next to Questions 15–20.', 1.0, 200),

(@tid, 21, 'Display audience', 'Which TWO groups of people is the display primarily intended for? (first answer)', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 210),
(@tid, 22, 'Display audience', 'Which TWO groups of people is the display primarily intended for? (second answer)', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 220),
(@tid, 23, 'Charles Dickens', 'What are Cathy and Graham''s TWO reasons for choosing the novelist Charles Dickens? (first answer)', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 230),
(@tid, 24, 'Charles Dickens', 'What are Cathy and Graham''s TWO reasons for choosing the novelist Charles Dickens? (second answer)', 'multiple_choice_multiple', 'Choose TWO letters, A–E.', 1.0, 240),

(@tid, 25, 'Dickens topics', 'The Pickwick Papers', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 250),
(@tid, 26, 'Dickens topics', 'Oliver Twist', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 260),
(@tid, 27, 'Dickens topics', 'Nicholas Nickleby', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 270),
(@tid, 28, 'Dickens topics', 'Martin Chuzzlewit', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 280),
(@tid, 29, 'Dickens topics', 'Bleak House', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 290),
(@tid, 30, 'Dickens topics', 'Little Dorrit', 'matching', 'Choose SIX answers from the box and write the correct letter, A–H, next to Questions 25–30.', 1.0, 300),

(@tid, 31, 'Agricultural programme in Mozambique', '___ was seen as the main priority to ensure the supply of water', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 310),
(@tid, 32, 'Agricultural programme in Mozambique', 'Most of the work organised by farmers'' associations was done by ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 320),
(@tid, 33, 'Agricultural programme in Mozambique', '___ was provided for the fences', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 330),
(@tid, 34, 'Agricultural programme in Mozambique', '___ was provided for suitable crops', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 340),
(@tid, 35, 'Agricultural programme in Mozambique', 'The farmers provided ___ for the fences on their land', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 350),
(@tid, 36, 'Agricultural programme in Mozambique', 'The marketing of produce was sometimes difficult due to lack of ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 360),
(@tid, 37, 'Agricultural programme in Mozambique', 'Training was therefore provided in methods of food ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 370),
(@tid, 38, 'Agricultural programme in Mozambique', 'Farmers made special places where ___ could be kept', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 380),
(@tid, 39, 'Agricultural programme in Mozambique', 'Local people later suggested keeping ___', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 390),
(@tid, 40, 'Agricultural programme in Mozambique', 'Enough time must be allowed, particularly for the ___ phase of the programme', 'form_note_completion', 'Write ONE WORD ONLY for each answer.', 1.0, 400);

-- Q11–Q14 MCQ options
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, o.option_label, o.option_text, o.is_correct, o.display_order
FROM (
    SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11) AS question_id, 'A' AS option_label, 'as an amenity provided by the city council.' AS option_text, 0 AS is_correct, 10 AS display_order
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11), 'B', 'as land belonging to a private house.', 0, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 11), 'C', 'as a shared area set up by the local community.', 1, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12), 'A', 'She was a resident who helped to lead a campaign.', 1, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12), 'B', 'She was a council member responsible for giving the public access.', 0, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 12), 'C', 'She was a senior worker at the park for many years.', 0, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13), 'A', 'exercises by troops.', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13), 'B', 'growing vegetables.', 1, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 13), 'C', 'public meetings.', 0, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14), 'A', '2013', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14), 'B', '2015', 0, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 14), 'C', '2016', 1, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21), 'A', 'students from the English department', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21), 'B', 'residents of the local area', 1, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21), 'C', 'the university''s teaching staff', 0, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21), 'D', 'potential new students', 1, 40
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 21), 'E', 'students from other departments', 0, 50
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22), 'A', 'students from the English department', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22), 'B', 'residents of the local area', 1, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22), 'C', 'the university''s teaching staff', 0, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22), 'D', 'potential new students', 1, 40
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 22), 'E', 'students from other departments', 0, 50
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23), 'A', 'His speeches inspired others to try to improve society.', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23), 'B', 'He used his publications to draw attention to social problems.', 1, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23), 'C', 'His novels are well-known now.', 1, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23), 'D', 'He was consulted on a number of social issues.', 0, 40
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 23), 'E', 'His reputation has changed in recent times.', 0, 50
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24), 'A', 'His speeches inspired others to try to improve society.', 0, 10
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24), 'B', 'He used his publications to draw attention to social problems.', 1, 20
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24), 'C', 'His novels are well-known now.', 1, 30
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24), 'D', 'He was consulted on a number of social issues.', 0, 40
    UNION ALL SELECT (SELECT id FROM questions WHERE test_id = @tid AND question_number = 24), 'E', 'His reputation has changed in recent times.', 0, 50
) o
JOIN questions q ON q.id = o.question_id;

-- Correct answers for text completion, map labelling, and matching.
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, a.answer_text, 0, 0
FROM questions q
JOIN (
    SELECT 1 AS question_number, 'Eustatis' AS answer_text
    UNION ALL SELECT 2, 'Review'
    UNION ALL SELECT 3, 'Dance'
    UNION ALL SELECT 4, 'Chat'
    UNION ALL SELECT 5, 'Healthy'
    UNION ALL SELECT 6, 'Posters'
    UNION ALL SELECT 7, 'Wood'
    UNION ALL SELECT 8, 'Lake'
    UNION ALL SELECT 9, 'Insects'
    UNION ALL SELECT 10, 'Blog'
    UNION ALL SELECT 15, 'E'
    UNION ALL SELECT 16, 'C'
    UNION ALL SELECT 17, 'B'
    UNION ALL SELECT 18, 'A'
    UNION ALL SELECT 19, 'G'
    UNION ALL SELECT 20, 'D'
    UNION ALL SELECT 25, 'G'
    UNION ALL SELECT 26, 'B'
    UNION ALL SELECT 27, 'D'
    UNION ALL SELECT 28, 'C'
    UNION ALL SELECT 29, 'H'
    UNION ALL SELECT 30, 'F'
    UNION ALL SELECT 31, 'Irrigation'
    UNION ALL SELECT 32, 'Women'
    UNION ALL SELECT 33, 'Wire'
    UNION ALL SELECT 34, 'Seeds'
    UNION ALL SELECT 35, 'Posts'
    UNION ALL SELECT 36, 'Transport'
    UNION ALL SELECT 37, 'Preservation'
    UNION ALL SELECT 38, 'Fish'
    UNION ALL SELECT 39, 'Bees'
    UNION ALL SELECT 40, 'Design'
) a ON a.question_number = q.question_number
WHERE q.test_id = @tid;
