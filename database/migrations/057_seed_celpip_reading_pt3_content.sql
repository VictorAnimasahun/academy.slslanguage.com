-- ============================================================
-- Migration 057 — Seed real question content for CELPIP_PT_R_003
-- Source: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Reading/Test 3
--         (CELPIP READING Test III.pdf, all 4 parts in one file)
--         + "READING TEST 3 Answers.docx" (sits directly in Celpip Reading/,
--         not inside the Test 3/ subfolder -- easy to miss)
-- Test 3's source prose was already native-quality (unlike Test 2), so this
-- is a faithful transcription with only light grammar polish (one broken
-- question stem in Part 4 Q3 -- "Pallister does not [answer text]" didn't
-- parse against its own answer key text, fixed to drop "does not").
-- IDEMPOTENT: safe to re-run.
--
-- Question numbering: global 1-38, all scored:
--   Part 1 (Correspondence): 1-11
--   Part 2 (Diagram):        12-19
--   Part 3 (Information):    20-28 (paragraph matching A-D, E = not given)
--   Part 4 (Viewpoints):     29-38
-- ============================================================

UPDATE tests SET total_questions = 38, duration_minutes = 55
WHERE code = 'CELPIP_PT_R_003';

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_003' LIMIT 1);

DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- ── Part 1 — Reading Correspondence (Q1-11) ────────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 1,  'Reading Correspondence', 'According to Cara, the winter weather in Korea is usually', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 10),
(@tid, 2,  'Reading Correspondence', 'Cara says global warming', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 20),
(@tid, 3,  'Reading Correspondence', 'The bus stops in Canada', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 30),
(@tid, 4,  'Reading Correspondence', 'Christen usually', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 40),
(@tid, 5,  'Reading Correspondence', 'Cara', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 50),
(@tid, 6,  'Reading Correspondence', 'Cara', 'multiple_choice_single', 'Choose the best way to complete each statement using the appropriate information from the passage.', 1.0, 1, 60),
(@tid, 7,  'Reading Correspondence', 'Blank 7 in the reply letter', 'multiple_choice_single', 'Complete the response by selecting the best choice for each blank.', 1.0, 1, 70),
(@tid, 8,  'Reading Correspondence', 'Blank 8 in the reply letter', 'multiple_choice_single', 'Complete the response by selecting the best choice for each blank.', 1.0, 1, 80),
(@tid, 9,  'Reading Correspondence', 'Blank 9 in the reply letter', 'multiple_choice_single', 'Complete the response by selecting the best choice for each blank.', 1.0, 1, 90),
(@tid, 10, 'Reading Correspondence', 'Blank 10 in the reply letter', 'multiple_choice_single', 'Complete the response by selecting the best choice for each blank.', 1.0, 1, 100),
(@tid, 11, 'Reading Correspondence', 'Blank 11 in the reply letter', 'multiple_choice_single', 'Complete the response by selecting the best choice for each blank.', 1.0, 1, 110);

-- ── Part 2 — Reading to Apply a Diagram (Q12-19) ───────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 12, 'Reading to Apply a Diagram', 'Blank 1 in the email', 'multiple_choice_single', 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.', 1.0, 2, 120),
(@tid, 13, 'Reading to Apply a Diagram', 'Blank 2 in the email', 'multiple_choice_single', 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.', 1.0, 2, 130),
(@tid, 14, 'Reading to Apply a Diagram', 'Blank 3 in the email', 'multiple_choice_single', 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.', 1.0, 2, 140),
(@tid, 15, 'Reading to Apply a Diagram', 'Blank 4 in the email', 'multiple_choice_single', 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.', 1.0, 2, 150),
(@tid, 16, 'Reading to Apply a Diagram', 'Blank 5 in the email', 'multiple_choice_single', 'Read the passage as it relates to the diagram above. Select the best answer to fill in each blank.', 1.0, 2, 160),
(@tid, 17, 'Reading to Apply a Diagram', 'Part of the reason why Charles is inclined to go for the Landscape style comes from', 'multiple_choice_single', 'Complete the following statements by selecting the best answer.', 1.0, 2, 170),
(@tid, 18, 'Reading to Apply a Diagram', 'Charles is', 'multiple_choice_single', 'Complete the following statements by selecting the best answer.', 1.0, 2, 180),
(@tid, 19, 'Reading to Apply a Diagram', 'Charles', 'multiple_choice_single', 'Complete the following statements by selecting the best answer.', 1.0, 2, 190);

-- ── Part 3 — Reading for Information (Q20-28), paragraph matching A-D ──
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 20, 'Reading for Information', 'The person who spends a certain amount of money can be found.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 200),
(@tid, 21, 'Reading for Information', 'A name for the concept is formulated.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 210),
(@tid, 22, 'Reading for Information', 'Blockchain leaves no digital footprint due to no identification.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 220),
(@tid, 23, 'Reading for Information', 'Government control.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 230),
(@tid, 24, 'Reading for Information', 'It is impossible for governments to take action against you if you use blockchain.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 240),
(@tid, 25, 'Reading for Information', 'Another component is needed due to storage constraints.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 250),
(@tid, 26, 'Reading for Information', 'Protects the hard drive.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 260),
(@tid, 27, 'Reading for Information', 'Several devices are used to execute the workings of blockchain.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 270),
(@tid, 28, 'Reading for Information', 'A global application.', 'matching', 'Decide which paragraph, A to D, matches each statement below. Select E if the information is not given.', 1.0, 3, 280);

-- ── Part 4 — Reading for Viewpoints (Q29-38) ───────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 29, 'Reading for Viewpoints', 'In terms of profitability', 'multiple_choice_single', 'Complete each statement by selecting the best option.', 1.0, 4, 290),
(@tid, 30, 'Reading for Viewpoints', 'Francis Goodwin', 'multiple_choice_single', 'Complete each statement by selecting the best option.', 1.0, 4, 300),
(@tid, 31, 'Reading for Viewpoints', 'Pallister', 'multiple_choice_single', 'Complete each statement by selecting the best option.', 1.0, 4, 310),
(@tid, 32, 'Reading for Viewpoints', 'Francis Goodwin is the type of person who', 'multiple_choice_single', 'Complete each statement by selecting the best option.', 1.0, 4, 320),
(@tid, 33, 'Reading for Viewpoints', 'Japanese cars', 'multiple_choice_single', 'Complete each statement by selecting the best option.', 1.0, 4, 330),
(@tid, 34, 'Reading for Viewpoints', 'Blank 6 in the visitor comment', 'multiple_choice_single', 'Pick the best answer to complete each blank.', 1.0, 4, 340),
(@tid, 35, 'Reading for Viewpoints', 'Blank 7 in the visitor comment', 'multiple_choice_single', 'Pick the best answer to complete each blank.', 1.0, 4, 350),
(@tid, 36, 'Reading for Viewpoints', 'Blank 8 in the visitor comment', 'multiple_choice_single', 'Pick the best answer to complete each blank.', 1.0, 4, 360),
(@tid, 37, 'Reading for Viewpoints', 'Blank 9 in the visitor comment', 'multiple_choice_single', 'Pick the best answer to complete each blank.', 1.0, 4, 370),
(@tid, 38, 'Reading for Viewpoints', 'Blank 10 in the visitor comment', 'multiple_choice_single', 'Pick the best answer to complete each blank.', 1.0, 4, 380);

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
SET @q29=(SELECT id FROM questions WHERE test_id=@tid AND question_number=29 LIMIT 1);
SET @q30=(SELECT id FROM questions WHERE test_id=@tid AND question_number=30 LIMIT 1);
SET @q31=(SELECT id FROM questions WHERE test_id=@tid AND question_number=31 LIMIT 1);
SET @q32=(SELECT id FROM questions WHERE test_id=@tid AND question_number=32 LIMIT 1);
SET @q33=(SELECT id FROM questions WHERE test_id=@tid AND question_number=33 LIMIT 1);
SET @q34=(SELECT id FROM questions WHERE test_id=@tid AND question_number=34 LIMIT 1);
SET @q35=(SELECT id FROM questions WHERE test_id=@tid AND question_number=35 LIMIT 1);
SET @q36=(SELECT id FROM questions WHERE test_id=@tid AND question_number=36 LIMIT 1);
SET @q37=(SELECT id FROM questions WHERE test_id=@tid AND question_number=37 LIMIT 1);
SET @q38=(SELECT id FROM questions WHERE test_id=@tid AND question_number=38 LIMIT 1);

-- ── MCQ options: Part 1 Q1-6 ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q1,'A','minus 10 degrees Celsius',0,10),(@q1,'B','minus 5 degrees Celsius',0,20),(@q1,'C','30 degrees Celsius',0,30),(@q1,'D','minus 2 degrees Celsius',1,40),
(@q2,'A','has a different meaning than what people think',1,10),(@q2,'B','kills people in the winter',0,20),(@q2,'C','is making things extremely hot',0,30),(@q2,'D','is causing ice',0,40),
(@q3,'A','are heated',0,10),(@q3,'B','need to be modelled on ones in other countries',0,20),(@q3,'C','are mostly cold inside',1,30),(@q3,'D','are all being upgraded by the city',0,40),
(@q4,'A','rents a car',0,10),(@q4,'B','takes the bus',1,20),(@q4,'C','drives',0,30),(@q4,'D','uses peer-to-peer commuting',0,40),
(@q5,'A','can get a good deal if she buys a car now',0,10),(@q5,'B','cannot find the right car since she was late',0,20),(@q5,'C','cannot find a good deal',1,30),(@q5,'D','will rather go for peer-to-peer commuting',0,40),
(@q6,'A','loves the snow',0,10),(@q6,'B',"has anxiety about Christen's return",0,20),(@q6,'C','will buy Korean DVDs if Christen finds a cheaper deal',0,30),(@q6,'D','probably paid over $10 for Korean DVDs',1,40);

-- ── MCQ options: Part 1 Q7-11 (cloze) ───────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q7,'A','is very comparable to Canada',0,10),(@q7,'B','is less slippery than Canada',0,20),(@q7,'C','is nothing like Canada',1,30),(@q7,'D','can be betted against',0,40),
(@q8,'A',"life hasn't changed much",0,10),(@q8,'B','not the usual',1,20),(@q8,'C','I am renting a car',0,30),(@q8,'D','I am avoiding the cold bus stops',0,40),
(@q9,'A','the food is great',1,10),(@q9,'B','the bus stops are heated',0,20),(@q9,'C','we don''t have mountains of snow here',0,30),(@q9,'D','people don''t fall on ice here',0,40),
(@q10,'A','your thirst for entertainment will die',1,10),(@q10,'B','you will find good deals on cars',0,20),(@q10,'C','you will find good deals on DVDs',0,30),(@q10,'D','the snowfall will lessen',0,40),
(@q11,'A','not being allowed to travel',0,10),(@q11,'B','not too optimistic',1,20),(@q11,'C','and Carrie working so hard',0,30),(@q11,'D','taking instructions from Carrie',0,40);

-- ── MCQ options: Part 2 Q12-16 (cloze) ──────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q12,'A','financially',0,10),(@q12,'B','visually',0,20),(@q12,'C','content-wise',1,30),(@q12,'D','business-wise',0,40),
(@q13,'A','its latest design',1,10),(@q13,'B','the jam-packed information',0,20),(@q13,'C','the visuals and graphics',0,30),(@q13,'D','the lesser pages',0,40),
(@q14,'A','its sales',0,10),(@q14,'B','its effectiveness for businesses',1,20),(@q14,'C','its dominance',0,30),(@q14,'D','a reasonable mix',0,40),
(@q15,'A','save us money',1,10),(@q15,'B','give more text',0,20),(@q15,'C','be more detailed',0,30),(@q15,'D','be more underwhelming',0,40),
(@q16,'A','non-vintage design',0,10),(@q16,'B','excessive graphics',0,20),(@q16,'C','number of pages',0,30),(@q16,'D','lack of information',1,40);

-- ── MCQ options: Part 2 Q17-19 ──────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q17,'A','definite facts',1,10),(@q17,'B','curiosity',0,20),(@q17,'C','his dislike of the other two styles',0,30),(@q17,'D','his dislike of the Book Style',0,40),
(@q18,'A','definite about his choice',0,10),(@q18,'B','asking Maria for help in deciding the right brochure',0,20),(@q18,'C','presenting his opinions with doubt',0,30),(@q18,'D','presenting his opinions with reasonable confidence',1,40),
(@q19,'A','just wants to present his opinion',0,10),(@q19,'B','is waiting for feedback',1,20),(@q19,'C','has come to a final conclusion',0,30),(@q19,'D',"needs confirmation from Maria on her decision",0,40);

-- ── Matching options: Part 3 Q20-28 (A-D paragraphs + E = not given) ────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q20,'A','Paragraph A',0,10),(@q20,'B','Paragraph B',0,20),(@q20,'C','Paragraph C',1,30),(@q20,'D','Paragraph D',0,40),(@q20,'E','Not given',0,50),
(@q21,'A','Paragraph A',1,10),(@q21,'B','Paragraph B',0,20),(@q21,'C','Paragraph C',0,30),(@q21,'D','Paragraph D',0,40),(@q21,'E','Not given',0,50),
(@q22,'A','Paragraph A',0,10),(@q22,'B','Paragraph B',0,20),(@q22,'C','Paragraph C',0,30),(@q22,'D','Paragraph D',0,40),(@q22,'E','Not given',1,50),
(@q23,'A','Paragraph A',0,10),(@q23,'B','Paragraph B',0,20),(@q23,'C','Paragraph C',0,30),(@q23,'D','Paragraph D',1,40),(@q23,'E','Not given',0,50),
(@q24,'A','Paragraph A',0,10),(@q24,'B','Paragraph B',0,20),(@q24,'C','Paragraph C',0,30),(@q24,'D','Paragraph D',0,40),(@q24,'E','Not given',1,50),
(@q25,'A','Paragraph A',1,10),(@q25,'B','Paragraph B',0,20),(@q25,'C','Paragraph C',0,30),(@q25,'D','Paragraph D',0,40),(@q25,'E','Not given',0,50),
(@q26,'A','Paragraph A',0,10),(@q26,'B','Paragraph B',0,20),(@q26,'C','Paragraph C',0,30),(@q26,'D','Paragraph D',0,40),(@q26,'E','Not given',1,50),
(@q27,'A','Paragraph A',1,10),(@q27,'B','Paragraph B',0,20),(@q27,'C','Paragraph C',0,30),(@q27,'D','Paragraph D',0,40),(@q27,'E','Not given',0,50),
(@q28,'A','Paragraph A',0,10),(@q28,'B','Paragraph B',1,20),(@q28,'C','Paragraph C',0,30),(@q28,'D','Paragraph D',0,40),(@q28,'E','Not given',0,50);

-- ── MCQ options: Part 4 Q29-33 ───────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q29,'A','Goodwin prefers charging people more for German cars',0,10),(@q29,'B','Japanese cars do much better due to higher sales',0,20),(@q29,'C','Goodwin clearly wins the debate',0,30),(@q29,'D','German cars may have an edge',1,40),
(@q30,'A','has made a pros and cons list',0,10),(@q30,'B','thinks that the pros of German cars cost more',0,20),(@q30,'C','feels the high cost of German cars is justified',1,30),(@q30,'D','claims that German cars offer more warranties',0,40),
(@q31,'A','cares too much about the safety ratings',0,10),(@q31,'B','is concerned about the safety ratings',0,20),(@q31,'C','has self-assurance that his industry leads the race',1,30),(@q31,'D','is a very stubborn person',0,40),
(@q32,'A','insists his customers pay more',0,10),(@q32,'B',"is appreciative if his company's revenues are good",1,20),(@q32,'C',"is proud of German cars' safety ratings",0,30),(@q32,'D','debates George Pallister on what "basic needs" are',0,40),
(@q33,'A','can push beyond 200,000 km with no issues',0,10),(@q33,'B','offer more warranties than German cars',0,20),(@q33,'C','have more focus on reliability as a selling point',1,30),(@q33,'D','are preferred for long-term use',0,40);

-- ── MCQ options: Part 4 Q34-38 (cloze) ───────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q34,'A','support Francis',0,10),(@q34,'B','support George',1,20),(@q34,'C','support price tags',0,30),(@q34,'D','support justified prices',0,40),
(@q35,'A','more savings on maintenance',0,10),(@q35,'B','more warranties',0,20),(@q35,'C','more mileage',1,30),(@q35,'D','more luxury',0,40),
(@q36,'A','worrying about mileage',0,10),(@q36,'B','joining the majority that does not',1,20),(@q36,'C','worrying about luxury',0,30),(@q36,'D','going for a car with no value',0,40),
(@q37,'A','luxury',0,10),(@q37,'B','costs',1,20),(@q37,'C','reliability',0,30),(@q37,'D','mileage',0,40),
(@q38,'A','Japanese cars offering more reliability',0,10),(@q38,'B','Japanese cars seeming confident with their warranties',0,20),(@q38,'C','German cars coming up with new safety measures',1,30),(@q38,'D','both industries rating high on safety',0,40);
