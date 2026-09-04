-- ============================================================
-- Migration 055 — Seed real question content for CELPIP_PT_R_001
-- Source: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Reading/Test 1
--         (4 PDFs: P1 Correspondence, P2 Diagram, P3 Information, P4 Viewpoints)
--         + "CELPIP READING Test I (Answers).docx"
-- IDEMPOTENT: safe to re-run.
--
-- Question numbering: global 1-38 across the 4 parts (parts restart at 1
-- in the source PDFs, offsets applied here):
--   Part 1 (Correspondence): 1-11  (Q1-6 standalone MCQ, Q7-11 cloze-MCQ)
--   Part 2 (Diagram):        12-19 (Q12-16 cloze-MCQ, Q17-19 standalone MCQ)
--   Part 3 (Information):    20-28 (paragraph matching A-E, E = "not given")
--   Part 4 (Viewpoints):     29-38 (Q29-33 NOT auto-graded -- see note below;
--                             Q34-38 cloze-MCQ)
--
-- NOTE on Q29-33: the source PDF for Part 4 Questions 1-5 never printed
-- multiple-choice options (unlike every other question in this test) --
-- only the answer key gives the correct phrasing. Rather than inventing
-- plausible-looking wrong options that were never in the source material,
-- these 5 are seeded as ungraded self-check questions (points = 0,
-- question_type = 'short_answer', the answer key text stored so the page
-- can reveal it after submission). Scored max is therefore 33, not 38.
-- ============================================================

UPDATE tests SET total_questions = 38, duration_minutes = 55
WHERE code = 'CELPIP_PT_R_001';

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001' LIMIT 1);

DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- ── Part 1 — Reading Correspondence (Q1-11) ────────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 1,  'Reading Correspondence', 'It was unnecessary for the writer to mention the', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 10),
(@tid, 2,  'Reading Correspondence', 'Mrs. Birch accepts that', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 20),
(@tid, 3,  'Reading Correspondence', 'Mrs. Birch disagrees with the', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 30),
(@tid, 4,  'Reading Correspondence', 'The trip to Africa', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 40),
(@tid, 5,  'Reading Correspondence', "Mrs. Birch's travel plans include", 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 50),
(@tid, 6,  'Reading Correspondence', 'In general, Mrs. Birch seems', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 60),
(@tid, 7,  'Reading Correspondence', 'Blank 7 in the reply email', 'multiple_choice_single', "Complete the response by filling in the blanks. Select the best choice for each blank.", 1.0, 1, 70),
(@tid, 8,  'Reading Correspondence', 'Blank 8 in the reply email', 'multiple_choice_single', "Complete the response by filling in the blanks. Select the best choice for each blank.", 1.0, 1, 80),
(@tid, 9,  'Reading Correspondence', 'Blank 9 in the reply email', 'multiple_choice_single', "Complete the response by filling in the blanks. Select the best choice for each blank.", 1.0, 1, 90),
(@tid, 10, 'Reading Correspondence', 'Blank 10 in the reply email', 'multiple_choice_single', "Complete the response by filling in the blanks. Select the best choice for each blank.", 1.0, 1, 100),
(@tid, 11, 'Reading Correspondence', 'Blank 11 in the reply email', 'multiple_choice_single', "Complete the response by filling in the blanks. Select the best choice for each blank.", 1.0, 1, 110);

-- ── Part 2 — Reading to Apply a Diagram (Q12-19) ───────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 12, 'Reading to Apply a Diagram', 'Blank 1 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 120),
(@tid, 13, 'Reading to Apply a Diagram', 'Blank 2 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 130),
(@tid, 14, 'Reading to Apply a Diagram', 'Blank 3 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 140),
(@tid, 15, 'Reading to Apply a Diagram', 'Blank 4 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 150),
(@tid, 16, 'Reading to Apply a Diagram', 'Blank 5 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 160),
(@tid, 17, 'Reading to Apply a Diagram', 'What does Lucy want Alan to do?', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 170),
(@tid, 18, 'Reading to Apply a Diagram', 'Why does Alan think a garden is a good idea?', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 180),
(@tid, 19, 'Reading to Apply a Diagram', 'Why did Alan attach the brochure to his email?', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 190);

-- ── Part 3 — Reading for Information (Q20-28), paragraph matching A-E ──
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 20, 'Reading for Information', "Camping's popularity in Canada is evidenced by the percentage of frequent campers.", 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 200),
(@tid, 21, 'Reading for Information', 'Certain types of camping cannot be done spontaneously.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 210),
(@tid, 22, 'Reading for Information', 'Some people who prefer one style of camping enjoy travelling in groups.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 220),
(@tid, 23, 'Reading for Information', 'Camping is a growing trend among wilderness enthusiasts.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 230),
(@tid, 24, 'Reading for Information', 'Campers should check where they are permitted to set up camp.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 240),
(@tid, 25, 'Reading for Information', 'Campsites are evenly spaced across the country.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 250),
(@tid, 26, 'Reading for Information', 'People who use motorhomes bring a second vehicle with them.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 260),
(@tid, 27, 'Reading for Information', 'Some campers choose a middle ground between luxury and rustic camping.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 270),
(@tid, 28, 'Reading for Information', 'Some camping equipment is less able to withstand unpleasant conditions.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 280);

-- ── Part 4 — Reading for Viewpoints (Q29-38) ───────────────────────────
-- Q29-33: source PDF never printed MCQ options for these -- ungraded, points=0.
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 29, 'Reading for Viewpoints', 'Annalisa Ducharme most likely objects to...', 'short_answer', 'Not auto-graded -- the source material did not provide answer options for this question. Compare your reasoning to the model answer shown after submitting.', 0.0, 4, 290),
(@tid, 30, 'Reading for Viewpoints', 'Marianne Houseman thinks language preservation programs should be...', 'short_answer', 'Not auto-graded -- the source material did not provide answer options for this question. Compare your reasoning to the model answer shown after submitting.', 0.0, 4, 300),
(@tid, 31, 'Reading for Viewpoints', 'Who holds directly opposing viewpoints?', 'short_answer', 'Not auto-graded -- the source material did not provide answer options for this question. Compare your reasoning to the model answer shown after submitting.', 0.0, 4, 310),
(@tid, 32, 'Reading for Viewpoints', 'Marianne Houseman would most likely agree that...', 'short_answer', 'Not auto-graded -- the source material did not provide answer options for this question. Compare your reasoning to the model answer shown after submitting.', 0.0, 4, 320),
(@tid, 33, 'Reading for Viewpoints', 'Overall, the article suggests that efforts to preserve dying languages are...', 'short_answer', 'Not auto-graded -- the source material did not provide answer options for this question. Compare your reasoning to the model answer shown after submitting.', 0.0, 4, 330),
(@tid, 34, 'Reading for Viewpoints', 'Blank 6 in the visitor comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 340),
(@tid, 35, 'Reading for Viewpoints', 'Blank 7 in the visitor comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 350),
(@tid, 36, 'Reading for Viewpoints', 'Blank 8 in the visitor comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 360),
(@tid, 37, 'Reading for Viewpoints', 'Blank 9 in the visitor comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 370),
(@tid, 38, 'Reading for Viewpoints', 'Blank 10 in the visitor comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 380);

-- ── Resolve question IDs ────────────────────────────────────────────────
SET @q1=(SELECT id FROM questions WHERE test_id=@tid AND question_number=1 LIMIT 1);
SET @q2=(SELECT id FROM questions WHERE test_id=@tid AND question_number=2 LIMIT 1);
SET @q3=(SELECT id FROM questions WHERE test_id=@tid AND question_number=3 LIMIT 1);
SET @q4=(SELECT id FROM questions WHERE test_id=@tid AND question_number=4 LIMIT 1);
SET @q5=(SELECT id FROM questions WHERE test_id=@tid AND question_number=5 LIMIT 1);
SET @q6=(SELECT id FROM questions WHERE test_id=@tid AND question_number=6 LIMIT 1);
SET @q7=(SELECT id FROM questions WHERE test_id=@tid AND question_number=7 LIMIT 1);
SET @q8=(SELECT id FROM questions WHERE test_id=@tid AND question_number=8 LIMIT 1);
SET @q9=(SELECT id FROM questions WHERE test_id=@tid AND question_number=9 LIMIT 1);
SET @q10=(SELECT id FROM questions WHERE test_id=@tid AND question_number=10 LIMIT 1);
SET @q11=(SELECT id FROM questions WHERE test_id=@tid AND question_number=11 LIMIT 1);
SET @q12=(SELECT id FROM questions WHERE test_id=@tid AND question_number=12 LIMIT 1);
SET @q13=(SELECT id FROM questions WHERE test_id=@tid AND question_number=13 LIMIT 1);
SET @q14=(SELECT id FROM questions WHERE test_id=@tid AND question_number=14 LIMIT 1);
SET @q15=(SELECT id FROM questions WHERE test_id=@tid AND question_number=15 LIMIT 1);
SET @q16=(SELECT id FROM questions WHERE test_id=@tid AND question_number=16 LIMIT 1);
SET @q17=(SELECT id FROM questions WHERE test_id=@tid AND question_number=17 LIMIT 1);
SET @q18=(SELECT id FROM questions WHERE test_id=@tid AND question_number=18 LIMIT 1);
SET @q19=(SELECT id FROM questions WHERE test_id=@tid AND question_number=19 LIMIT 1);
SET @q20=(SELECT id FROM questions WHERE test_id=@tid AND question_number=20 LIMIT 1);
SET @q21=(SELECT id FROM questions WHERE test_id=@tid AND question_number=21 LIMIT 1);
SET @q22=(SELECT id FROM questions WHERE test_id=@tid AND question_number=22 LIMIT 1);
SET @q23=(SELECT id FROM questions WHERE test_id=@tid AND question_number=23 LIMIT 1);
SET @q24=(SELECT id FROM questions WHERE test_id=@tid AND question_number=24 LIMIT 1);
SET @q25=(SELECT id FROM questions WHERE test_id=@tid AND question_number=25 LIMIT 1);
SET @q26=(SELECT id FROM questions WHERE test_id=@tid AND question_number=26 LIMIT 1);
SET @q27=(SELECT id FROM questions WHERE test_id=@tid AND question_number=27 LIMIT 1);
SET @q28=(SELECT id FROM questions WHERE test_id=@tid AND question_number=28 LIMIT 1);
SET @q34=(SELECT id FROM questions WHERE test_id=@tid AND question_number=34 LIMIT 1);
SET @q35=(SELECT id FROM questions WHERE test_id=@tid AND question_number=35 LIMIT 1);
SET @q36=(SELECT id FROM questions WHERE test_id=@tid AND question_number=36 LIMIT 1);
SET @q37=(SELECT id FROM questions WHERE test_id=@tid AND question_number=37 LIMIT 1);
SET @q38=(SELECT id FROM questions WHERE test_id=@tid AND question_number=38 LIMIT 1);
SET @q29=(SELECT id FROM questions WHERE test_id=@tid AND question_number=29 LIMIT 1);
SET @q30=(SELECT id FROM questions WHERE test_id=@tid AND question_number=30 LIMIT 1);
SET @q31=(SELECT id FROM questions WHERE test_id=@tid AND question_number=31 LIMIT 1);
SET @q32=(SELECT id FROM questions WHERE test_id=@tid AND question_number=32 LIMIT 1);
SET @q33=(SELECT id FROM questions WHERE test_id=@tid AND question_number=33 LIMIT 1);

-- ── MCQ options: Part 1 Q1-6 ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q1,'A','date of her initial request',0,10),(@q1,'B','expected period of absence',0,20),(@q1,'C','name of the travel agency',1,30),(@q1,'D','type of deposit she made',0,40),
(@q2,'A','advance notice of vacation is required',1,10),(@q2,'B','most departments are understaffed',0,20),(@q2,'C','replacement workers are unavailable',0,30),(@q2,'D',"6 month's notice is the minimum",0,40),
(@q3,'A','"changes not allowed" rule',1,10),(@q3,'B','"no other employment" rule',0,20),(@q3,'C','"requests must be in writing" rule',0,30),(@q3,'D',"\"6 months' notice\" rule",0,40),
(@q4,'A','is offered only in the summer',0,10),(@q4,'B','occurs when July is over',1,20),(@q4,'C','takes 4 weeks in total',0,30),(@q4,'D','will depart from Japan',0,40),
(@q5,'A','family members',1,10),(@q5,'B','her close friends',0,20),(@q5,'C','only her husband',0,30),(@q5,'D','her co-workers',0,40),
(@q6,'A','confused',0,10),(@q6,'B','happy',0,20),(@q6,'C','pressured',1,30),(@q6,'D','sorry',0,40);

-- ── MCQ options: Part 1 Q7-11 (cloze) ───────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q7,'A',"change the dates you'll be away",1,10),(@q7,'B','confirm your travel insurance',0,20),(@q7,'C','give you extra time off work',0,30),(@q7,'D','revise our vacation policy',0,40),
(@q8,'A','have difficulty meeting',1,10),(@q8,'B','insist on changes to',0,20),(@q8,'C','need an explanation of',0,30),(@q8,'D','refuse to follow',0,40),
(@q9,'A','different in terms of their',1,10),(@q9,'B','ignoring such vacation',0,20),(@q9,'C','now changing such strict',0,30),(@q9,'D','popular because of their',0,40),
(@q10,'A',"approve 2 months' vacation",0,10),(@q10,'B','forward your message upwards',0,20),(@q10,'C','grant your revised request',1,30),(@q10,'D','hire your temporary replacement',0,40),
(@q11,'A','Africa',1,10),(@q11,'B','Canada',0,20),(@q11,'C','Japan',0,30),(@q11,'D','Mexico',0,40);

-- ── MCQ options: Part 2 Q12-16 (cloze) ──────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q12,'A','watermelon',1,10),(@q12,'B','peas',0,20),(@q12,'C','tomatoes',0,30),(@q12,'D','carrots',0,40),
(@q13,'A','spinach',1,10),(@q13,'B','peas',0,20),(@q13,'C','watermelon',0,30),(@q13,'D','carrots',0,40),
(@q14,'A','plenty of space',1,10),(@q14,'B','warm and dry soil',0,20),(@q14,'C','lots of shade',0,30),(@q14,'D','specialized seed compost',0,40),
(@q15,'A','most helpful',1,10),(@q15,'B','busiest',0,20),(@q15,'C','largest',0,30),(@q15,'D','most popular',0,40),
(@q16,'A','carrots and spinach',1,10),(@q16,'B','watermelon and peas',0,20),(@q16,'C','spinach and tomatoes',0,30),(@q16,'D','pumpkin and carrots',0,40);

-- ── MCQ options: Part 2 Q17-19 ──────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q17,'A','call the garden center for information',0,10),(@q17,'B','research the harvest time of each plant',0,20),(@q17,'C','make a pumpkin pie for dinner',0,30),(@q17,'D','plant a vegetable garden with him',1,40),
(@q18,'A','He knows Lucy would enjoy it.',0,10),(@q18,'B','It is an affordable way to eat healthy food.',1,20),(@q18,'C','It will fill empty space in their yard.',0,30),(@q18,'D',"He feels they don't eat enough healthy food.",0,40),
(@q19,'A','to show her the new gardening in town',0,10),(@q19,'B','to provide examples of things they can grow',1,20),(@q19,'C','to encourage Lucy to plant a garden again',0,30),(@q19,'D',"to explain what he'll be doing this summer",0,40);

-- ── Matching options: Part 3 Q20-28 (A-D paragraphs + E = not given) ────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q20,'A','Paragraph A',1,10),(@q20,'B','Paragraph B',0,20),(@q20,'C','Paragraph C',0,30),(@q20,'D','Paragraph D',0,40),(@q20,'E','Not given',0,50),
(@q21,'A','Paragraph A',0,10),(@q21,'B','Paragraph B',1,20),(@q21,'C','Paragraph C',0,30),(@q21,'D','Paragraph D',0,40),(@q21,'E','Not given',0,50),
(@q22,'A','Paragraph A',0,10),(@q22,'B','Paragraph B',1,20),(@q22,'C','Paragraph C',0,30),(@q22,'D','Paragraph D',0,40),(@q22,'E','Not given',0,50),
(@q23,'A','Paragraph A',0,10),(@q23,'B','Paragraph B',0,20),(@q23,'C','Paragraph C',0,30),(@q23,'D','Paragraph D',0,40),(@q23,'E','Not given',1,50),
(@q24,'A','Paragraph A',0,10),(@q24,'B','Paragraph B',0,20),(@q24,'C','Paragraph C',0,30),(@q24,'D','Paragraph D',1,40),(@q24,'E','Not given',0,50),
(@q25,'A','Paragraph A',0,10),(@q25,'B','Paragraph B',0,20),(@q25,'C','Paragraph C',0,30),(@q25,'D','Paragraph D',1,40),(@q25,'E','Not given',0,50),
(@q26,'A','Paragraph A',0,10),(@q26,'B','Paragraph B',0,20),(@q26,'C','Paragraph C',0,30),(@q26,'D','Paragraph D',0,40),(@q26,'E','Not given',1,50),
(@q27,'A','Paragraph A',0,10),(@q27,'B','Paragraph B',0,20),(@q27,'C','Paragraph C',1,30),(@q27,'D','Paragraph D',0,40),(@q27,'E','Not given',0,50),
(@q28,'A','Paragraph A',0,10),(@q28,'B','Paragraph B',0,20),(@q28,'C','Paragraph C',1,30),(@q28,'D','Paragraph D',0,40),(@q28,'E','Not given',0,50);

-- ── MCQ options: Part 4 Q34-38 (cloze) ──────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q34,'A','the next generation',1,10),(@q34,'B','linguistic researchers',0,20),(@q34,'C','a range of First Nation groups',0,30),(@q34,'D','Michif speakers',0,40),
(@q35,'A','professor',1,10),(@q35,'B','graduate students',0,20),(@q35,'C','CALD representative',0,30),(@q35,'D','United Nations',0,40),
(@q36,'A','continue evolving',0,10),(@q36,'B','grow more diverse',0,20),(@q36,'C','make a comeback',1,30),(@q36,'D','become official',0,40),
(@q37,'A','language standardization efforts',0,10),(@q37,'B','aggressive nationalist agendas',1,20),(@q37,'C','likely to proliferate',0,30),(@q37,'D','indulgent charity campaigns',0,40),
(@q38,'A','unnecessary',0,10),(@q38,'B','linguistic',0,20),(@q38,'C','essential',0,30),(@q38,'D','arbitrary',1,40);

-- ── Model answers for the 5 ungraded Part 4 questions (Q29-33) ─────────
-- Stored for display only -- loadTestAnswers() will surface these but the
-- page treats question_number IN (29,30,31,32,33) as unscored/self-check.
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative) VALUES
(@q29,'a simplistic categorization or definition of a language',0,0),
(@q30,'abandoned',0,0),
(@q31,'houseman and reideger',0,0),
(@q32,'people stand to gain from a lingua franca',0,0),
(@q33,'fraught with an array of sociopolitical complications',0,0);
