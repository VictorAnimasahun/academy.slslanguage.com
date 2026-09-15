-- ============================================================
-- Migration 084 — Seed CELPIP Listening Practice Test 1 (CELPIP_PT_L_001)
-- Source: Downloads/CELPIP Practice Test C - Listening Transcripts (2).docx
--
-- Backs the auto-grading pipeline (loadTestAnswers() + save_attempt.php)
-- for celpip_listening_001.php. The actual question text/options rendered
-- on the page are hardcoded in that PHP file (matching the established
-- CELPIP Reading practice-test convention, e.g. migration 055) — this
-- migration only needs to carry real option_label/is_correct data for
-- scoring, though question_text/stimulus_text are filled in for admin
-- visibility and consistency with that same convention.
--
-- Question numbering: global 1-38 across the 6 official CELPIP Listening
-- parts (the source document restarts numbering at 1 within each part;
-- offsets applied here):
--   Part 1 (Problem Solving):        1-8   (3 sections: Q1-3, Q4-5, Q6-8)
--   Part 2 (Daily Life Conversation): 9-13
--   Part 3 (Listening for Information): 14-19
--   Part 4 (News Item):               20-24
--   Part 5 (Discussion):              25-32
--   Part 6 (Viewpoints):              33-38
--
-- IDEMPOTENT: safe to re-run.
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_L_001',
       'CELPIP Listening — Practice Test 1',
       'CELPIP Listening practice set covering all 6 official parts: Problem Solving, Daily Life Conversation, Listening for Information, News Item, Discussion, and Viewpoints.',
       'CELPIP',
       47,
       38,
       1,
       0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_L_001');

UPDATE tests SET total_questions = 38, duration_minutes = 47 WHERE code = 'CELPIP_PT_L_001';

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_L_001' LIMIT 1);

DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = @tid);
DELETE FROM questions WHERE test_id = @tid;

-- ── Part 1 — Listening to Problem Solving (Q1-8) ───────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 1, 'Listening to Problem Solving', 'What is the woman ultimately hoping to do?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 10),
(@tid, 2, 'Listening to Problem Solving', "What best describes the man's help in this section?", 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 20),
(@tid, 3, 'Listening to Problem Solving', 'What will the woman probably do next?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 30),
(@tid, 4, 'Listening to Problem Solving', 'What problem does the woman have?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 40),
(@tid, 5, 'Listening to Problem Solving', 'Which statement is true?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 50),
(@tid, 6, 'Listening to Problem Solving', 'What happened to the two misplaced books?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 60),
(@tid, 7, 'Listening to Problem Solving', 'How will the woman know when the third book is ready?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 70),
(@tid, 8, 'Listening to Problem Solving', 'What can we tell about the man from this conversation?', 'multiple_choice_single', 'Listen to the conversation, then answer the question. You will hear it only once.', 1.0, 1, 80);

-- ── Part 2 — Listening to a Daily Life Conversation (Q9-13) ────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 9,  'Listening to a Daily Life Conversation', 'What are the man and woman preparing for?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 2, 90),
(@tid, 10, 'Listening to a Daily Life Conversation', 'Why is the woman behind on her part of the work?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 2, 100),
(@tid, 11, 'Listening to a Daily Life Conversation', 'Which flight do they decide to book?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 2, 110),
(@tid, 12, 'Listening to a Daily Life Conversation', 'Which statement is most likely true?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 2, 120),
(@tid, 13, 'Listening to a Daily Life Conversation', "What new request from Mr. Alvarez surprises them?", 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 2, 130);

-- ── Part 3 — Listening for Information (Q14-19) ────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 14, 'Listening for Information', 'Why does the woman want to buy a bike?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 140),
(@tid, 15, 'Listening for Information', 'What kind of bike does the man recommend, and why?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 150),
(@tid, 16, 'Listening for Information', 'According to the man, which of these is true of a pure road bike?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 160),
(@tid, 17, 'Listening for Information', 'According to the man, which of these is true of the mid-range hybrid model?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 170),
(@tid, 18, 'Listening for Information', "Which of these best describes the man's approach as a salesperson?", 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 180),
(@tid, 19, 'Listening for Information', 'What will most likely happen next?', 'multiple_choice_single', 'Listen to the conversation, then answer the questions. You will hear it only once.', 1.0, 3, 190);

-- ── Part 4 — Listening to a News Item (Q20-24) ─────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 20, 'Listening to a News Item', 'This news item is about ______', 'multiple_choice_single', 'Listen to the news item, then answer the questions. You will hear it only once.', 1.0, 4, 200),
(@tid, 21, 'Listening to a News Item', 'Fatima Noor spent the past year ______', 'multiple_choice_single', 'Listen to the news item, then answer the questions. You will hear it only once.', 1.0, 4, 210),
(@tid, 22, 'Listening to a News Item', 'According to Councillor Doyle, community gardens can improve ______', 'multiple_choice_single', 'Listen to the news item, then answer the questions. You will hear it only once.', 1.0, 4, 220),
(@tid, 23, 'Listening to a News Item', "Councillor Doyle's comments suggest that the city is ______", 'multiple_choice_single', 'Listen to the news item, then answer the questions. You will hear it only once.', 1.0, 4, 230),
(@tid, 24, 'Listening to a News Item', 'The news item ends by mentioning ______', 'multiple_choice_single', 'Listen to the news item, then answer the questions. You will hear it only once.', 1.0, 4, 240);

-- ── Part 5 — Listening to a Discussion (Q25-32) ────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 25, 'Listening to a Discussion', 'What are the three coworkers deciding in this discussion?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 250),
(@tid, 26, 'Listening to a Discussion', 'How much did the silent auction raise last year, after expenses?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 260),
(@tid, 27, 'Listening to a Discussion', 'What does Dev believe about the trivia night idea?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 270),
(@tid, 28, 'Listening to a Discussion', 'What do all three coworkers agree on by the end of the discussion?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 280),
(@tid, 29, 'Listening to a Discussion', "What best describes Sam's attitude toward writing the trivia questions?", 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 290),
(@tid, 30, 'Listening to a Discussion', "What would Mia most likely have done if Dev hadn't pointed out the cost of renting equipment for the auction?", 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 300),
(@tid, 31, 'Listening to a Discussion', 'Which saying would Sam most likely agree with, given his approach to organizing the trivia night?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 310),
(@tid, 32, 'Listening to a Discussion', 'What does Dev offer to do regarding the trivia questions Sam writes?', 'multiple_choice_single', 'Listen to the discussion, then answer the questions. You will hear it only once.', 1.0, 5, 320);

-- ── Part 6 — Listening for Viewpoints (Q33-38) ─────────────────────────
INSERT INTO questions (test_id, question_number, stimulus_text, question_text, question_type, instructions, points, part_number, display_order) VALUES
(@tid, 33, 'Listening for Viewpoints', 'This is a report about a ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 330),
(@tid, 34, 'Listening for Viewpoints', 'Large retailers would need to comply ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 340),
(@tid, 35, 'Listening for Viewpoints', 'Money collected from the paper bag fee would go toward ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 350),
(@tid, 36, 'Listening for Viewpoints', 'Tom Whitfield argues that the bylaw ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 360),
(@tid, 37, 'Listening for Viewpoints', 'Dr. Priya Anand, who helped draft the bylaw, believes it ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 370),
(@tid, 38, 'Listening for Viewpoints', 'The bylaw will most likely be voted on ______', 'multiple_choice_single', 'Listen to the report, then answer the questions. You will hear it only once.', 1.0, 6, 380);

-- ── Resolve question ids ────────────────────────────────────────────────
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

-- ── Options: Part 1 (Q1-8) ──────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q1,'A','Buy a book as a gift',0,10),(@q1,'B','Continue a mystery series she started years ago',1,20),(@q1,'C','Meet the author in person',0,30),(@q1,'D','Return an overdue book',0,40),
(@q2,'A','reluctant',0,10),(@q2,'B','dismissive',0,20),(@q2,'C','knowledgeable',1,30),(@q2,'D','confused',0,40),
(@q3,'A','Follow the man to see where the books are',1,10),(@q3,'B','Leave the library without the books',0,20),(@q3,'C','Ask for a refund',0,30),(@q3,'D','Call a different branch',0,40),
(@q4,'A','She forgot her library card',0,10),(@q4,'B','She does not have enough money',0,20),(@q4,'C','The library is about to close',0,30),(@q4,'D','The books are not where she was told they would be',1,40),
(@q5,'A','All three books were checked out by another patron',0,10),(@q5,'B','Two of the books are misplaced somewhere in the building',1,20),(@q5,'C','The woman decides not to wait for the third book',0,30),(@q5,'D','The man refuses to place a hold on the missing book',0,40),
(@q6,'A','They were checked out by another patron',0,10),(@q6,'B','They were sent to another branch',0,20),(@q6,'C','They were found behind the returns desk',1,30),(@q6,'D','They were damaged',0,40),
(@q7,'A','She will get an email',1,10),(@q7,'B','She will get a phone call',0,20),(@q7,'C','She has to check back in person',0,30),(@q7,'D','A librarian will mail it to her',0,40),
(@q8,'A','He is new to the job',0,10),(@q8,'B','He dislikes his job',0,20),(@q8,'C','He is unfamiliar with the catalogue system',0,30),(@q8,'D','He goes out of his way to help patrons',1,40);

-- ── Options: Part 2 (Q9-13) ─────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q9,'A','A client dinner',0,10),(@q9,'B','A team building event',0,20),(@q9,'C','A conference presentation',1,30),(@q9,'D','A product launch',0,40),
(@q10,'A','She has been busy with client calls',1,10),(@q10,'B','She was on vacation',0,20),(@q10,'C','Her computer broke',0,30),(@q10,'D','She forgot about it',0,40),
(@q11,'A','Friday morning',0,10),(@q11,'B','Thursday evening',0,20),(@q11,'C','Friday evening',0,30),(@q11,'D','Thursday 7am',1,40),
(@q12,'A','The man has presented to this audience before',0,10),(@q12,'B','Neither of them has presented to this specific audience before',1,20),(@q12,'C','The woman prefers the Friday morning flight',0,30),(@q12,'D',"Mr. Alvarez's request was part of the original plan",0,40),
(@q13,'A','They need to add a Q&A session',1,10),(@q13,'B','The venue changed',0,20),(@q13,'C','The budget was cut',0,30),(@q13,'D','The presentation was moved to Monday',0,40);

-- ── Options: Part 3 (Q14-19) ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q14,'A','To train for a race',0,10),(@q14,'B','To start commuting to work instead of driving',1,20),(@q14,'C','As a gift for her child',0,30),(@q14,'D','For weekend leisure rides',0,40),
(@q15,'A',"A mountain bike, because it's the toughest",0,10),(@q15,'B',"A road bike, because it's the fastest",0,20),(@q15,'C',"A folding bike, because it's compact",0,30),(@q15,'D','A hybrid bike, because it handles both pavement and light gravel',1,40),
(@q16,'A','Its thin tires might struggle on gravel',1,10),(@q16,'B','It has better gears than a hybrid',0,20),(@q16,'C',"It's the cheapest option in the store",0,30),(@q16,'D','It comes with lights and a lock included',0,40),
(@q17,'A',"It is the store's most expensive option",0,10),(@q17,'B','It has hydraulic disc brakes',0,20),(@q17,'C','It offers good value and should last several years',1,30),(@q17,'D','It does not come with a warranty',0,40),
(@q18,'A','knowledgeable and attentive to her specific needs',1,10),(@q18,'B','pushy and focused on the most expensive option',0,20),(@q18,'C','indifferent to her budget concerns',0,30),(@q18,'D','dismissive of beginner cyclists',0,40),
(@q19,'A','The woman will decide not to buy a bike',0,10),(@q19,'B','The woman will pay and leave immediately',0,20),(@q19,'C','The man will grab a bike in her size for a test ride',1,30),(@q19,'D','They will discuss financing paperwork first',0,40);

-- ── Options: Part 4 (Q20-24) ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q20,'A','a new city park with walking trails',0,10),(@q20,'B','the opening of a new community garden',1,20),(@q20,'C','a change to city zoning laws',0,30),(@q20,'D','a fundraiser for local schools',0,40),
(@q21,'A','building the raised garden beds herself',0,10),(@q21,'B','negotiating with the fire department',0,20),(@q21,'C','raising funds through grants, crowdfunding, and donations',1,30),(@q21,'D','teaching gardening classes at a local school',0,40),
(@q22,'A','neighborhood relationships and access to fresh produce',1,10),(@q22,'B','property values and tourism',0,20),(@q22,'C','public transit ridership',0,30),(@q22,'D','school enrollment numbers',0,40),
(@q23,'A','uncertain whether the garden will succeed',0,10),(@q23,'B','reluctant to fund any further garden projects',0,20),(@q23,'C','regretting the cost of the project',0,30),(@q23,'D','planning to expand this kind of initiative',1,40),
(@q24,'A','criticism from nearby residents',0,10),(@q24,'B','free workshops planned for the summer',1,20),(@q24,'C','a delay in the second phase',0,30),(@q24,'D','a dispute over funding',0,40);

-- ── Options: Part 5 (Q25-32) ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q25,'A',"How to spend last year's fundraiser profits",0,10),(@q25,'B','Whether to cancel the fundraiser entirely',0,20),(@q25,'C','What format the fundraiser should take this year',1,30),(@q25,'D','Who will replace Mia as event organizer',0,40),
(@q26,'A','$200',0,10),(@q26,'B','$20,000',0,20),(@q26,'C','$700',0,30),(@q26,'D','$2,000',1,40),
(@q27,'A','It will be too hard to get people excited about it',0,10),(@q27,'B','It should be cancelled in favor of a raffle',0,20),(@q27,'C','It requires more staff than the club has',0,30),(@q27,'D','It is cheaper and simpler to organize than an auction',1,40),
(@q28,'A','Trivia night, at the pub, on a Friday, is the way to go',1,10),(@q28,'B','The fundraiser should be postponed until next year',0,20),(@q28,'C','The silent auction should be brought back next year',0,30),(@q28,'D','Mia should write all the trivia questions herself',0,40),
(@q29,'A','reluctant',0,10),(@q29,'B','indifferent',0,20),(@q29,'C','enthusiastic',1,30),(@q29,'D','anxious',0,40),
(@q30,'A','She would have insisted on repeating the silent auction',1,10),(@q30,'B','She would have cancelled the fundraiser altogether',0,20),(@q30,'C','She would have immediately proposed trivia night herself',0,30),(@q30,'D','She would have asked Sam to organize everything alone',0,40),
(@q31,'A','"If it ain\'t broke, don\'t fix it."',0,10),(@q31,'B','"Don\'t reinvent the wheel."',1,20),(@q31,'C','"The squeaky wheel gets the grease."',0,30),(@q31,'D','"Too many cooks spoil the broth."',0,40),
(@q32,'A','Write his own set of questions instead',0,10),(@q32,'B','Review them for accuracy before the event',1,20),(@q32,'C','Ask the pub to hire a professional writer',0,30),(@q32,'D','Cancel the deadline Sam set',0,40);

-- ── Options: Part 6 (Q33-38) ────────────────────────────────────────────
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order) VALUES
(@q33,'A','new curbside recycling program for Ashford',0,10),(@q33,'B','proposed bylaw to ban single-use plastic bags',1,20),(@q33,'C','shoreline cleanup organized by volunteers',0,30),(@q33,'D','fee increase on paper products in stores',0,40),
(@q34,'A','within three months of the bylaw passing',1,10),(@q34,'B','within one year of the bylaw passing',0,20),(@q34,'C','within six months of the bylaw passing',0,30),(@q34,'D','immediately after the vote is held',0,40),
(@q35,'A','lowering property taxes',0,10),(@q35,'B','a city environmental fund',1,20),(@q35,'C','compensating retailers',0,30),(@q35,'D','building a new recycling center',0,40),
(@q36,'A','will raise costs and confuse some customers',1,10),(@q36,'B','does not go far enough to cut plastic waste',0,20),(@q36,'C','should apply to paper bags as well',0,30),(@q36,'D','will have no effect on local retailers',0,40),
(@q37,'A','will fail since plastic bags are a small share of waste',0,10),(@q37,'B','can help shift public habits toward less plastic overall',1,20),(@q37,'C','should be delayed until more research is done',0,30),(@q37,'D','will only work if all plastics are banned at once',0,40),
(@q38,'A','immediately, without any public input',0,10),(@q38,'B','within two months, after public consultations',1,20),(@q38,'C','next year, following a province-wide review',0,30),(@q38,'D','never, since it was already rejected',0,40);

-- Rollback:
-- DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code='CELPIP_PT_L_001'));
-- DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code='CELPIP_PT_L_001');
-- DELETE FROM tests WHERE code = 'CELPIP_PT_L_001';
