-- Migration 044 — Seed quiz questions for first 30 vocabulary words
-- 3 questions per word: (1) Definition MCQ, (2) Gap-fill, (3) Word form MCQ
-- 90 questions · 240 question_options rows · 30 question_correct_answers rows
-- Run on LOCAL first, then LIVE.
-- Idempotent: clears existing vocab questions before re-inserting.

-- ── Clear existing vocab questions (safe re-run) ─────────────────────────────
DELETE qca FROM question_correct_answers qca
JOIN questions q ON qca.question_id = q.id
JOIN tests t ON q.test_id = t.id
WHERE t.test_type = 'Vocabulary';

DELETE qo FROM question_options qo
JOIN questions q ON qo.question_id = q.id
JOIN tests t ON q.test_id = t.id
WHERE t.test_type = 'Vocabulary';

DELETE q FROM questions q
JOIN tests t ON q.test_id = t.id
WHERE t.test_type = 'Vocabulary';

-- ═════════════════════════════════════════════════════════════════════════════
-- 1. ANALYSE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_001');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'analyse');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "analyse" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To examine something in detail in order to understand it or draw conclusions',1,10),
(@q,'B','To create something by combining separate elements together',0,20),
(@q,'C','To describe something in general terms without looking at its parts',0,30),
(@q,'D','To disagree with or publicly challenge an existing idea',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Researchers need to _______ the data carefully before drawing any conclusions."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'analyse',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The scientist\'s _______ of the samples revealed unexpected results."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','analyse',0,10),(@q,'B','analytical',0,20),(@q,'C','analysis',1,30),(@q,'D','analytically',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 2. APPROACH
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_002');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'approach');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "approach" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','A way of dealing with a situation or problem',1,10),
(@q,'B','The final result achieved after completing a task',0,20),
(@q,'C','A formal written argument or detailed proposal',0,30),
(@q,'D','A fixed rule that all members of a group must follow',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The government needs a new _______ to solving the housing crisis."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'approach',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The manager was friendly and _______, which made staff feel comfortable raising concerns."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','approach',0,10),(@q,'B','approached',0,20),(@q,'C','approachable',1,30),(@q,'D','approaching',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 3. ASSESS
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_003');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'assess');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "assess" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To judge or decide the quality, value, or importance of something',1,10),
(@q,'B','To prevent something from happening or developing further',0,20),
(@q,'C','To describe in detail exactly how something was originally created',0,30),
(@q,'D','To improve or practise a skill over a period of time',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Teachers must regularly _______ students\' understanding to identify learning gaps."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'assess',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The annual performance _______ helped employees understand their strengths and weaknesses."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','assess',0,10),(@q,'B','assessor',0,20),(@q,'C','reassess',0,30),(@q,'D','assessment',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 4. BENEFIT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_004');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'benefit');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "benefit" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','An advantage or something that has a positive effect',1,10),
(@q,'B','A serious problem that needs to be solved urgently',0,20),
(@q,'C','A rule that must be followed within an organisation',0,30),
(@q,'D','A formal request submitted to an authority for information',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Regular exercise has many _______ for both physical and mental health."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'benefits',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Volunteering abroad is _______ to both the individual and the wider community."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','benefit',0,10),(@q,'B','beneficiary',0,20),(@q,'C','beneficial',1,30),(@q,'D','beneficially',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 5. CONCEPT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_005');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'concept');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "concept" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','An idea or principle that is connected with something abstract',1,10),
(@q,'B','A specific event that occurs at a particular moment in time',0,20),
(@q,'C','A written document that outlines a detailed plan of action',0,30),
(@q,'D','A physical object used to demonstrate or explain a theory',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Students found it difficult to grasp the _______ of supply and demand at first."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'concept',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The report takes a _______ approach, focusing on theories rather than practical application."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','concept',0,10),(@q,'B','conceptually',0,20),(@q,'C','conceptualise',0,30),(@q,'D','conceptual',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 6. CONTEXT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_006');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'context');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "context" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','The background information that helps explain something',1,10),
(@q,'B','A formal agreement made between two separate parties',0,20),
(@q,'C','The final section or conclusion of a written document',0,30),
(@q,'D','A difficulty or obstacle that prevents progress',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "You need to consider the historical _______ before judging events from the past."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'context',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "It is important to _______ new vocabulary by studying it within real sentences."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','context',0,10),(@q,'B','contextual',0,20),(@q,'C','contextually',0,30),(@q,'D','contextualise',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 7. CONTRIBUTE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_007');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'contribute');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "contribute" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To give something as part of a combined effort to achieve a result',1,10),
(@q,'B','To take something away from a situation or group',0,20),
(@q,'C','To describe the advantages of a proposed plan',0,30),
(@q,'D','To formally challenge an opinion or official decision',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Poor air quality can _______ to a range of serious long-term health problems."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'contribute',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The charity depends entirely on the generous _______ of its donors."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','contribute',0,10),(@q,'B','contributor',0,20),(@q,'C','contributory',0,30),(@q,'D','contributions',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 8. CRUCIAL
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_008');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'crucial');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "crucial" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','Extremely important because other things depend on it',1,10),
(@q,'B','Difficult to understand without specialist knowledge',0,20),
(@q,'C','Relating to a decision that was made in the distant past',0,30),
(@q,'D','Impossible to achieve without a significant amount of resources',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Sleep is _______ for the brain to process and store new information effectively."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'crucial',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "_______, the new safety guidelines were introduced before the accident occurred."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','Crucial',0,10),(@q,'B','Crucially',1,20),(@q,'C','Crucialness',0,30),(@q,'D','Crucialism',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 9. DEMONSTRATE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_009');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'demonstrate');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "demonstrate" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To show or prove something clearly through evidence or action',1,10),
(@q,'B','To prevent something from being seen or understood by others',0,20),
(@q,'C','To estimate the likely outcome of a future situation',0,30),
(@q,'D','To describe something in general terms without providing evidence',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The study clearly _______ that diet has a direct impact on long-term brain function."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'demonstrated',0,0),(@q,'demonstrates',0,1);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The live _______ showed exactly how the new software operates."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','demonstrate',0,10),(@q,'B','demonstrator',0,20),(@q,'C','demonstrable',0,30),(@q,'D','demonstration',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 10. ENVIRONMENT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_010');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'environment');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "environment" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','The natural world or the conditions that surround and affect living things',1,10),
(@q,'B','A specific law designed to protect natural resources from damage',0,20),
(@q,'C','The overall financial system of a country or region',0,30),
(@q,'D','A group of people working collectively towards a shared goal',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Companies have a duty to minimise the damage they cause to the _______."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'environment',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The new policy was praised for being _______ responsible."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','environment',0,10),(@q,'B','environmentalist',0,20),(@q,'C','environmentally',1,30),(@q,'D','environmental',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 11. ESTABLISH
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_011');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'establish');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "establish" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To start or create something intended to last for a long time',1,10),
(@q,'B','To remove or dismantle something that currently exists',0,20),
(@q,'C','To repeat a process that has already been completed previously',0,30),
(@q,'D','To provide a detailed history of how an organisation developed',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The university was _______ in 1837 and has grown considerably since then."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'established',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The _______ of new trade agreements took several months of difficult negotiation."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','establish',0,10),(@q,'B','established',0,20),(@q,'C','establishment',1,30),(@q,'D','establishes',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 12. EVALUATE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_012');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'evaluate');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "evaluate" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To judge the quality, importance, or value of something',1,10),
(@q,'B','To increase the size or overall scope of something',0,20),
(@q,'C','To describe all the stages of a particular process',0,30),
(@q,'D','To make a formal request for additional information',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The committee will _______ each application carefully and select the strongest candidates."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'evaluate',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Her thorough _______ of the evidence impressed the entire review panel."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','evaluate',0,10),(@q,'B','evaluative',0,20),(@q,'C','evaluator',0,30),(@q,'D','evaluation',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 13. FACTOR
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_013');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'factor');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "factor" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','One of the things that influences whether an event happens or how it develops',1,10),
(@q,'B','A written summary of the key points found in a document',0,20),
(@q,'C','A specific target or goal that has been set by management',0,30),
(@q,'D','A legal document that formally confirms ownership of something',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Price is often the deciding _______ when customers choose between two similar products."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'factor',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct form: "Several _______ were identified as contributing to the high student dropout rate."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','factor',0,10),(@q,'B','factoring',0,20),(@q,'C','factorial',0,30),(@q,'D','factors',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 14. FOCUS
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_014');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'focus');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "focus" mean when used as a verb?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To give attention or effort to one particular subject or area',1,10),
(@q,'B','To move rapidly between many different tasks at once',0,20),
(@q,'C','To describe all aspects of a situation with equal detail',0,30),
(@q,'D','To avoid or delay dealing with a difficult problem',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The team decided to _______ on improving customer satisfaction above all other goals."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'focus',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "She remained _______ throughout the entire exam despite the noise outside."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','focus',0,10),(@q,'B','focusing',0,20),(@q,'C','focused',1,30),(@q,'D','refocus',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 15. IDENTIFY
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_015');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'identify');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "identify" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To recognise something and be able to name it; to find or discover something',1,10),
(@q,'B','To make something harder for others to understand or access',0,20),
(@q,'C','To merge two completely separate ideas into a single concept',0,30),
(@q,'D','To repeat information that has already been clearly stated',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The doctor was able to _______ the cause of the illness from the symptoms alone."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'identify',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Proof of _______ is required before you can open a bank account."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','identify',0,10),(@q,'B','identifiable',0,20),(@q,'C','identifiably',0,30),(@q,'D','identification',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 16. IMPACT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_016');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'impact');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "impact" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','The strong effect or influence that something has on a situation',1,10),
(@q,'B','A formal written complaint submitted about a service',0,20),
(@q,'C','The root cause of a problem that still needs to be solved',0,30),
(@q,'D','A brief summary of the most important findings in a report',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Social media has had a significant _______ on the way young people communicate."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'impact',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The new transport policy had an _______ effect on commute times across the city."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','impact',0,10),(@q,'B','impactfully',0,20),(@q,'C','impacts',0,30),(@q,'D','impactful',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 17. INDICATE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_017');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'indicate');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "indicate" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To show or point to something; to be a sign of something',1,10),
(@q,'B','To prevent something from being clearly understood',0,20),
(@q,'C','To make a formal and binding promise about something',0,30),
(@q,'D','To change the direction of an argument or plan of action',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The falling unemployment figures _______ that the economy is beginning to recover."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'indicate',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Rising global temperatures are a clear _______ of ongoing climate change."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','indicate',0,10),(@q,'B','indicative',0,20),(@q,'C','indicator',1,30),(@q,'D','indicating',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 18. INDIVIDUAL
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_018');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'individual');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "individual" mean when used as an adjective?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','Relating to one single person or thing separately from a group',1,10),
(@q,'B','Shared equally among all members of a particular group',0,20),
(@q,'C','Connected to an official government or public policy',0,30),
(@q,'D','Happening repeatedly over a very long period of time',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The teacher gave _______ feedback to each student about their essay."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'individual',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Students are encouraged to work _______ before sharing their ideas with the group."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','individual',0,10),(@q,'B','individualism',0,20),(@q,'C','individualise',0,30),(@q,'D','individually',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 19. INVOLVE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_019');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'involve');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "involve" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To include someone or something as a necessary part of something',1,10),
(@q,'B','To completely remove something from a process or plan',0,20),
(@q,'C','To make a task simpler by reducing the number of steps required',0,30),
(@q,'D','To copy an existing system or approach exactly as it is',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The project will _______ close collaboration between three separate departments."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'involve',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Her deep _______ in the local community made her an ideal candidate for the role."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','involve',0,10),(@q,'B','involved',0,20),(@q,'C','involvement',1,30),(@q,'D','involves',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 20. ISSUE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_020');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'issue');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "issue" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','An important topic or problem that people are discussing or arguing about',1,10),
(@q,'B','A formal written agreement between two separate organisations',0,20),
(@q,'C','A positive result that is achieved after considerable hard work',0,30),
(@q,'D','A specific set of instructions for completing a task correctly',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The government must address the _______ of rising energy costs as a matter of urgency."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'issue',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Which sentence uses the word "issue" correctly?','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','"We need to issue more time to this problem."',0,10),
(@q,'B','"The government issued new guidelines on food safety last month."',1,20),
(@q,'C','"She issued to complete the task on time."',0,30),
(@q,'D','"The issue was very pleased with the outcome."',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 21. MAINTAIN
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_021');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'maintain');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "maintain" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To keep something in its current state or at a particular level',1,10),
(@q,'B','To completely change the way in which something is done',0,20),
(@q,'C','To formally disagree with an official decision or ruling',0,30),
(@q,'D','To reduce the overall cost of a product or service',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "It is important to _______ a healthy diet throughout all stages of life."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'maintain',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The building requires regular _______ to keep it safe for all occupants."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','maintain',0,10),(@q,'B','maintained',0,20),(@q,'C','maintainable',0,30),(@q,'D','maintenance',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 22. METHOD
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_022');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'method');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "method" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','A particular planned or established way of doing something',1,10),
(@q,'B','The very last stage or step of a longer process',0,20),
(@q,'C','A reason that explains why something cannot be completed',0,30),
(@q,'D','A formal written record of past events or decisions',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Scientists must use a reliable _______ to ensure their results can be independently repeated."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'method',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The researcher followed a _______ approach, verifying each step before moving to the next."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','method',0,10),(@q,'B','methodology',0,20),(@q,'C','methodically',0,30),(@q,'D','methodical',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 23. POLICY
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_023');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'policy');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "policy" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','A plan of action agreed on by a government or organisation',1,10),
(@q,'B','A personal belief held privately by a single individual',0,20),
(@q,'C','A formal punishment handed down by a court of law',0,30),
(@q,'D','A financial reward or bonus given to a high-performing employee',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The school introduced a strict mobile phone _______ to reduce distractions in the classroom."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'policy',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The minister met with _______ to discuss proposed changes to the national healthcare system."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','policy',0,10),(@q,'B','policies',0,20),(@q,'C','policymakers',1,30),(@q,'D','policying',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 24. PRINCIPLE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_024');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'principle');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "principle" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','A basic rule or belief that guides behaviour or explains how something works',1,10),
(@q,'B','The most senior or important person in a school or organisation',0,20),
(@q,'C','A specific example chosen to support a particular argument',0,30),
(@q,'D','A formal agreement between two or more separate countries',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The _______ of fairness should apply to all students regardless of their background."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'principle',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word: "She refused to accept the offer on _______, as she believed it was unethical."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','principal',0,10),(@q,'B','principled',0,20),(@q,'C','principally',0,30),(@q,'D','principle',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 25. PROCESS
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_025');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'process');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "process" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','A series of actions or events that produce a result or bring about a change',1,10),
(@q,'B','A single decision made quickly and without any prior consultation',0,20),
(@q,'C','A specific type of formal document required by an authority',0,30),
(@q,'D','The final outcome produced at the end of a series of experiments',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The _______ of applying for a student visa can take several weeks to complete."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'process',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The factory uses automated machinery to _______ thousands of customer orders every day."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','process',1,10),(@q,'B','processing',0,20),(@q,'C','processed',0,30),(@q,'D','processor',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 26. SIGNIFICANT
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_026');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'significant');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "significant" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','Important or large enough to be noticed or to have an effect',1,10),
(@q,'B','Too small or unimportant to deserve any serious attention',0,20),
(@q,'C','Relating to a decision that has already been made and acted upon',0,30),
(@q,'D','Occurring only under very specific or unusual circumstances',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "There has been a _______ improvement in air quality since the new regulations came into force."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'significant',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The discovery _______ changed our understanding of ancient human history."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','significant',0,10),(@q,'B','significance',0,20),(@q,'C','signify',0,30),(@q,'D','significantly',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 27. STRUCTURE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_027');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'structure');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does "structure" mean when used as a noun?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','The way in which the parts of a system or object are organised or arranged',1,10),
(@q,'B','A detailed list of rules that every member must strictly follow',0,20),
(@q,'C','The total financial value of a building or organisation',0,30),
(@q,'D','A type of written evidence commonly cited in an academic report',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "A well-written essay needs a clear _______ with an introduction, body paragraphs, and a conclusion."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'structure',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Engineers identified several _______ weaknesses in the design of the old bridge."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','structure',0,10),(@q,'B','structurally',0,20),(@q,'C','restructure',0,30),(@q,'D','structural',1,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 28. SUGGEST
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_028');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'suggest');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "suggest" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To mention an idea for someone to consider; to show that something is likely',1,10),
(@q,'B','To formally order someone to take a specific required action',0,20),
(@q,'C','To confirm that a particular outcome has already been decided',0,30),
(@q,'D','To explain in detail why something is impossible to achieve',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The available evidence _______ that regular reading significantly improves vocabulary."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'suggests',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "Her _______ to meet earlier in the day was welcomed by everyone on the team."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','suggest',0,10),(@q,'B','suggestive',0,20),(@q,'C','suggestion',1,30),(@q,'D','suggesting',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 29. THEREFORE
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_029');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'therefore');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "therefore" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','For that reason; as a result of what has just been stated',1,10),
(@q,'B','Despite this fact; in contrast to what was said before',0,20),
(@q,'C','At exactly the same time as another separate event',0,30),
(@q,'D','In addition to everything that has already been mentioned',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "The train was severely delayed; _______, many passengers missed their connecting flights."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'therefore',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Which sentence uses "therefore" correctly?','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','"Therefore he likes football, he plays every weekend."',0,10),
(@q,'B','"She was exhausted; therefore, she went to bed early."',1,20),
(@q,'C','"I went to the shop therefore to buy some milk."',0,30),
(@q,'D','"The weather was therefore warm and perfectly sunny."',0,40);

-- ═════════════════════════════════════════════════════════════════════════════
-- 30. VARY
-- ═════════════════════════════════════════════════════════════════════════════
SET @tid = (SELECT id FROM tests WHERE code = 'VOCAB_WORD_030');
SET @wid = (SELECT id FROM vocabulary_words WHERE headword = 'vary');

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,1,'What does the word "vary" mean?','multiple_choice_single',1.0,10);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','To be different from something else, or to change over time',1,10),
(@q,'B','To remain exactly the same without any change at all',0,20),
(@q,'C','To describe something in very precise and specific detail',0,30),
(@q,'D','To combine two separate things to produce something entirely new',0,40);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,2,'Complete the sentence: "Opinions on this topic _______ widely from one person to the next."','gap_fill',1.0,20);
SET @q=LAST_INSERT_ID();
INSERT INTO question_correct_answers (question_id,answer_text,is_case_sensitive,is_alternative) VALUES
(@q,'vary',0,0);

INSERT INTO questions (test_id,word_id,question_number,question_text,question_type,points,display_order) VALUES
(@tid,@wid,3,'Choose the correct word form: "The restaurant offers a wide _______ of dishes from cuisines all around the world."','multiple_choice_single',1.0,30);
SET @q=LAST_INSERT_ID();
INSERT INTO question_options (question_id,option_label,option_text,is_correct,display_order) VALUES
(@q,'A','vary',0,10),(@q,'B','variable',0,20),(@q,'C','varied',0,30),(@q,'D','variety',1,40);

-- ── Update total_questions count on each vocab test container ─────────────────
UPDATE tests t
SET t.total_questions = (SELECT COUNT(*) FROM questions q WHERE q.test_id = t.id)
WHERE t.test_type = 'Vocabulary';
