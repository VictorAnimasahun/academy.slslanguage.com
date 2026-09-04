-- ============================================================
-- Migration 056 — Seed real question content for CELPIP_PT_R_002
-- Source: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Reading/Test 2
--         (4 PDFs) + "Celpip Reading Test II (Answers).docx"
-- Part 1 uses the user's own rewritten version (sent directly in chat,
-- replacing the source PDF's broken English + its own answer key).
-- Parts 2-4 are cleaned-up rewrites of the source content -- same topics
-- and same correct answers as the original, wording polished because the
-- source PDF's phrasing was broken/non-native throughout (unlike Test 1's
-- source, which was already clean). See project_celpip_practice_tests.md.
-- IDEMPOTENT: safe to re-run.
--
-- Question numbering: global 1-38 across the 4 parts, all scored (unlike
-- CELPIP_PT_R_001, every question here has real MCQ options):
--   Part 1 (Correspondence): 1-11
--   Part 2 (Diagram):        12-19
--   Part 3 (Information):    20-28 (paragraph matching A-D, E = not given)
--   Part 4 (Viewpoints):     29-38
-- ============================================================

UPDATE tests SET total_questions = 38, duration_minutes = 55
WHERE code = 'CELPIP_PT_R_002';

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_002' LIMIT 1);

DELETE FROM question_correct_answers
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options
    WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- ── Part 1 — Reading Correspondence (Q1-11) ────────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 1,  'Reading Correspondence', 'The letter is mainly about', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 10),
(@tid, 2,  'Reading Correspondence', 'If a patient has new insurance information, they should', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 20),
(@tid, 3,  'Reading Correspondence', 'Patients without insurance must', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 30),
(@tid, 4,  'Reading Correspondence', 'Patients are asked to', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 40),
(@tid, 5,  'Reading Correspondence', 'Previous dental records help the clinic', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 50),
(@tid, 6,  'Reading Correspondence', 'Patients with insurance would likely', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 60),
(@tid, 7,  'Reading Correspondence', 'Blank 7 in the reply email', 'multiple_choice_single', 'Complete the email by selecting the most appropriate phrase for each blank.', 1.0, 1, 70),
(@tid, 8,  'Reading Correspondence', 'Blank 8 in the reply email', 'multiple_choice_single', 'Complete the email by selecting the most appropriate phrase for each blank.', 1.0, 1, 80),
(@tid, 9,  'Reading Correspondence', 'Blank 9 in the reply email', 'multiple_choice_single', 'Complete the email by selecting the most appropriate phrase for each blank.', 1.0, 1, 90),
(@tid, 10, 'Reading Correspondence', 'Blank 10 in the reply email', 'multiple_choice_single', 'Complete the email by selecting the most appropriate phrase for each blank.', 1.0, 1, 100),
(@tid, 11, 'Reading Correspondence', 'According to the response, the writer expects to be away for approximately', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 1, 110);

-- ── Part 2 — Reading to Apply a Diagram (Q12-19) ───────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 12, 'Reading to Apply a Diagram', 'Blank 1 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 120),
(@tid, 13, 'Reading to Apply a Diagram', 'Blank 2 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 130),
(@tid, 14, 'Reading to Apply a Diagram', 'Blank 3 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 140),
(@tid, 15, 'Reading to Apply a Diagram', 'Blank 4 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 150),
(@tid, 16, 'Reading to Apply a Diagram', 'Blank 5 in the email', 'multiple_choice_single', 'Complete the email by filling in the blanks. Select the best choice for each blank.', 1.0, 2, 160),
(@tid, 17, 'Reading to Apply a Diagram', 'J.K Young is most likely a', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 170),
(@tid, 18, 'Reading to Apply a Diagram', 'The main purpose of this email is to', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 180),
(@tid, 19, 'Reading to Apply a Diagram', 'J.K Young comes across as', 'multiple_choice_single', 'Choose the best option according to the information given in the message.', 1.0, 2, 190);

-- ── Part 3 — Reading for Information (Q20-28), paragraph matching A-D ──
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 20, 'Reading for Information', 'Emperor penguins are currently at risk of extinction.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 200),
(@tid, 21, 'Reading for Information', 'Male and female emperor penguins look similar to one another.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 210),
(@tid, 22, 'Reading for Information', 'Emperor penguins mainly eat krill and squid.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 220),
(@tid, 23, 'Reading for Information', 'Emperor penguins show highly social behaviour when hunting.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 230),
(@tid, 24, 'Reading for Information', 'Because they have no fixed nest sites, emperor penguins depend on vocal calls to identify each other.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 240),
(@tid, 25, 'Reading for Information', 'Climate change, human activity, and disease all threaten emperor penguin populations.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 250),
(@tid, 26, 'Reading for Information', "The emperor penguin's body is physically adapted for life in the water.", 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 260),
(@tid, 27, 'Reading for Information', 'Emperor penguins have successfully bred in captivity outside Antarctica.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 270),
(@tid, 28, 'Reading for Information', 'Emperor penguins can vocalize using two frequency bands at once.', 'matching', 'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.', 1.0, 3, 280);

-- ── Part 4 — Reading for Viewpoints (Q29-38) ───────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 29, 'Reading for Viewpoints', 'What is this article mainly about?', 'multiple_choice_single', 'Choose the best option according to the information given in the article.', 1.0, 4, 290),
(@tid, 30, 'Reading for Viewpoints', 'According to the AAOS, families should', 'multiple_choice_single', 'Choose the best option according to the information given in the article.', 1.0, 4, 300),
(@tid, 31, 'Reading for Viewpoints', 'According to Dr. Jennifer Weiss, vitamins D and C are important because', 'multiple_choice_single', 'Choose the best option according to the information given in the article.', 1.0, 4, 310),
(@tid, 32, 'Reading for Viewpoints', 'The AAOS recommends that children', 'multiple_choice_single', 'Choose the best option according to the information given in the article.', 1.0, 4, 320),
(@tid, 33, 'Reading for Viewpoints', "Overall, the article suggests that childhood habits around bone health", 'multiple_choice_single', 'Choose the best option according to the information given in the article.', 1.0, 4, 330),
(@tid, 34, 'Reading for Viewpoints', 'Blank 6 in the comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 340),
(@tid, 35, 'Reading for Viewpoints', 'Blank 7 in the comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 350),
(@tid, 36, 'Reading for Viewpoints', 'Blank 8 in the comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 360),
(@tid, 37, 'Reading for Viewpoints', 'Blank 9 in the comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 370),
(@tid, 38, 'Reading for Viewpoints', 'Blank 10 in the comment', 'multiple_choice_single', 'Complete the comment by choosing the best option to fill in each blank.', 1.0, 4, 380);

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
(@q1,'A','Changing the date of the appointment',0,10),(@q1,'B','Apologizing for a billing error',0,20),(@q1,'C',"Explaining the clinic's policies",0,30),(@q1,'D','Confirming the appointment and providing related information',1,40),
(@q2,'A','Bring their updated insurance card only for their first appointment',0,10),(@q2,'B','Call the clinic before the appointment to report the change',0,20),(@q2,'C','Wait until after the appointment to update their information',0,30),(@q2,'D','Show proof of their current coverage at the appointment',1,40),
(@q3,'A','Provide proof of income before being treated',0,10),(@q3,'B','Settle their bill on the day of the appointment',1,20),(@q3,'C','Pay in advance of the appointment date',0,30),(@q3,'D','Discuss a payment plan with clinic staff',0,40),
(@q4,'A','Arrive exactly at their scheduled time',0,10),(@q4,'B','Arrive fifteen minutes after their scheduled time',0,20),(@q4,'C','Get to the clinic at least a quarter of an hour before their scheduled time',1,30),(@q4,'D','Arrive at least thirty minutes early',0,40),
(@q5,'A','Check whether the patient currently has valid insurance',0,10),(@q5,'B','See what dental treatment the patient has already received',1,20),(@q5,'C','Decide whether the clinic will accept the patient as new',0,30),(@q5,'D','Confirm treatments already performed at SmileBright itself',0,40),
(@q6,'A','Have their payment handled through their coverage rather than paid directly at the visit',1,10),(@q6,'B','Be exempt from arriving early',0,20),(@q6,'C','Not need to bring a photo ID',0,30),(@q6,'D','Receive a discount on treatment',0,40);

-- ── MCQ options: Part 1 Q7-11 (cloze) ───────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q7,'A',"I'm really sorry, but I need to cancel our appointment",0,10),(@q7,'B','I needed to cancel our appointment',0,20),(@q7,'C','I have to cancel our appointment',1,30),(@q7,'D','I am cancelling our appointment',0,40),
(@q8,'A','scheduled',0,10),(@q8,'B','hospitalized',1,20),(@q8,'C','treated',0,30),(@q8,'D','prepared',0,40),
(@q9,'A','However,',1,10),(@q9,'B','As a result,',0,20),(@q9,'C','In addition,',0,30),(@q9,'D','For example,',0,40),
(@q10,'A','Thank you for understanding',1,10),(@q10,'B','I look forward to seeing you',0,20),(@q10,'C','Thank you for your support',0,30),(@q10,'D','I request you to look into this',0,40),
(@q11,'A','One week',0,10),(@q11,'B','Two weeks',0,20),(@q11,'C','One month',0,30),(@q11,'D','Three weeks',1,40);

-- ── MCQ options: Part 2 Q12-16 (cloze) ──────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q12,'A','all days in Week 9',0,10),(@q12,'B','in the morning on the first day',1,20),(@q12,'C','on the stage every morning',0,30),(@q12,'D','the Tuesday afternoon office session',0,40),
(@q13,'A','Whole-group rehearsals',0,10),(@q13,'B','Individual auditions',0,20),(@q13,'C','Costume fittings',0,30),(@q13,'D','Dress rehearsals',1,40),
(@q14,'A','come and watch a preview performance',0,10),(@q14,'B','invite your friends and family',0,20),(@q14,'C','send us an email',1,30),(@q14,'D','bring a packed lunch',0,40),
(@q15,'A','the last day has no scheduled session',1,10),(@q15,'B','everyone needs a special costume',0,20),(@q15,'C','we need to meet all day in Week 8',0,30),(@q15,'D','the Tuesday schedule will change',0,40),
(@q16,'A','threatening',0,10),(@q16,'B','negative',0,20),(@q16,'C','positive',1,30),(@q16,'D','cautious',0,40);

-- ── MCQ options: Part 2 Q17-19 ──────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q17,'A','student',0,10),(@q17,'B','teacher',1,20),(@q17,'C','assistant',0,30),(@q17,'D','developer',0,40),
(@q18,'A','change an appointment',0,10),(@q18,'B','complain about a service',0,20),(@q18,'C','announce a schedule',1,30),(@q18,'D','request a reschedule',0,40),
(@q19,'A','volatile',0,10),(@q19,'B','despondent',0,20),(@q19,'C','offensive',0,30),(@q19,'D','enthusiastic',1,40);

-- ── Matching options: Part 3 Q20-28 (A-D paragraphs + E = not given) ────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q20,'A','Paragraph A',0,10),(@q20,'B','Paragraph B',0,20),(@q20,'C','Paragraph C',0,30),(@q20,'D','Paragraph D',1,40),(@q20,'E','Not given',0,50),
(@q21,'A','Paragraph A',1,10),(@q21,'B','Paragraph B',0,20),(@q21,'C','Paragraph C',0,30),(@q21,'D','Paragraph D',0,40),(@q21,'E','Not given',0,50),
(@q22,'A','Paragraph A',0,10),(@q22,'B','Paragraph B',0,20),(@q22,'C','Paragraph C',0,30),(@q22,'D','Paragraph D',0,40),(@q22,'E','Not given',1,50),
(@q23,'A','Paragraph A',0,10),(@q23,'B','Paragraph B',1,20),(@q23,'C','Paragraph C',0,30),(@q23,'D','Paragraph D',0,40),(@q23,'E','Not given',0,50),
(@q24,'A','Paragraph A',0,10),(@q24,'B','Paragraph B',0,20),(@q24,'C','Paragraph C',1,30),(@q24,'D','Paragraph D',0,40),(@q24,'E','Not given',0,50),
(@q25,'A','Paragraph A',0,10),(@q25,'B','Paragraph B',0,20),(@q25,'C','Paragraph C',0,30),(@q25,'D','Paragraph D',1,40),(@q25,'E','Not given',0,50),
(@q26,'A','Paragraph A',1,10),(@q26,'B','Paragraph B',0,20),(@q26,'C','Paragraph C',0,30),(@q26,'D','Paragraph D',0,40),(@q26,'E','Not given',0,50),
(@q27,'A','Paragraph A',0,10),(@q27,'B','Paragraph B',0,20),(@q27,'C','Paragraph C',0,30),(@q27,'D','Paragraph D',0,40),(@q27,'E','Not given',1,50),
(@q28,'A','Paragraph A',0,10),(@q28,'B','Paragraph B',0,20),(@q28,'C','Paragraph C',1,30),(@q28,'D','Paragraph D',0,40),(@q28,'E','Not given',0,50);

-- ── MCQ options: Part 4 Q29-33 ───────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q29,'A','Raising a healthy child in general',0,10),(@q29,'B',"How vitamins affect a child's growth",0,20),(@q29,'C','The causes of childhood obesity',0,30),(@q29,'D','How to help children build stronger bones',1,40),
(@q30,'A','encourage children to be self-reliant',0,10),(@q30,'B','take responsibility for helping their children stay active',1,20),(@q30,'C','rely on their doctor to manage bone health',0,30),(@q30,'D','leave physical activity to the school',0,40),
(@q31,'A','they help the body absorb calcium',1,10),(@q31,'B','they help children eat more evenly',0,20),(@q31,'C','they should be taken three times a day',0,30),(@q31,'D','they are made naturally by sunlight',0,40),
(@q32,'A','keep learning constantly',0,10),(@q32,'B','have a healthy diet provided by their school',0,20),(@q32,'C','get plenty of physical activity and eat a healthy diet',1,30),(@q32,'D','exercise for exactly one hour a day',0,40),
(@q33,'A',"can have a long-term impact on a child's health",1,10),(@q33,'B','do not affect bone growth',0,20),(@q33,'C','should be managed from birth',0,30),(@q33,'D','are closely tied to obesity',0,40);

-- ── MCQ options: Part 4 Q34-38 (cloze) ───────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q34,'A','enough nutrients',0,10),(@q34,'B','a lot of bones',0,20),(@q34,'C','no trouble developing strong bones',1,30),(@q34,'D','studied bone health a lot',0,40),
(@q35,'A','grow well',0,10),(@q35,'B','gain knowledge about bones',0,20),(@q35,'C','have parents managing it for them',0,30),(@q35,'D','develop strong, healthy bones',1,40),
(@q36,'A','regulator of bone growth',1,10),(@q36,'B','consumer of food',0,20),(@q36,'C','circulator of blood',0,30),(@q36,'D','generator of new cells only',0,40),
(@q37,'A','tissue that stops growing',0,10),(@q37,'B','tissue that is dying',0,20),(@q37,'C','tissue that keeps changing shape',0,30),(@q37,'D','a living tissue',1,40),
(@q38,'A','a routine affected by low-level exercise',0,10),(@q38,'B','a rate determined by insurance',0,20),(@q38,'C','a cycle that is influenced',1,30),(@q38,'D','a level determined by vitamins',0,40);
