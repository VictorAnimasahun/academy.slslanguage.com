-- ============================================================
-- Migration 071 — Seed real quiz content for IELTS General 3-Month
-- Masterclass (course_id=9), Classes 1-7 (Month 1)
--
-- documentation/ielts_general_3month_syllabus.md specifies an exact quiz
-- format for every teaching class, but the class0N.php lesson pages have
-- only ever shown "Quiz questions coming soon" (see migration_log.md 071
-- entry). This authors real questions matching each class's documented
-- format. Class 8 has no quiz (it's Mock Test 1, the full timed exam).
--
-- All questions use 'multiple_choice_single' (scored via
-- question_options.is_correct) for one consistent, simple grading path in
-- the new class_quiz.php engine (migration 072), even where the syllabus
-- describes a classification/decision drill rather than a traditional MCQ —
-- classification-among-fixed-options reduces cleanly to MCQ.
--
-- Idempotent — every INSERT guarded. Run on LOCAL first, then LIVE.
-- ============================================================

-- ── Container tests (one per class quiz) ──────────────────────────────────
INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT d.code, d.title, d.description, 'IELTS_General', 'Quiz', 10, d.qcount, 1, 0
FROM (
  SELECT 'IELTS_GM_C1_QUIZ' code, 'Class 1 Quiz — IELTS Format Knowledge' title, 'Section timings, question counts, and band ranges.' description, 10 qcount
  UNION ALL SELECT 'IELTS_GM_C2_QUIZ', 'Class 2 Quiz — Band Descriptor Matching', 'Match each descriptor phrase to the correct marking criterion.', 10
  UNION ALL SELECT 'IELTS_GM_C3_QUIZ', 'Class 3 Quiz — Gap-Fill Prediction Strategies', 'For each gap context, identify the expected answer type.', 10
  UNION ALL SELECT 'IELTS_GM_C4_QUIZ', 'Class 4 Quiz — Skimming vs Scanning Decision Drill', 'Decide whether each task needs skimming for gist or scanning for specific information.', 10
  UNION ALL SELECT 'IELTS_GM_C5_QUIZ', 'Class 5 Quiz — MCQ Elimination Drill', 'Practice eliminating distractors using a short spoken-extract transcript.', 10
  UNION ALL SELECT 'IELTS_GM_C6_QUIZ', 'Class 6 Quiz — TFNG vs YNNG Decision Drill', 'Decide which system applies (TFNG or YNNG) to each statement, then give the correct answer.', 10
  UNION ALL SELECT 'IELTS_GM_C7_QUIZ', 'Class 7 Quiz — Map Orientation Drill', 'Practice following directions and compass points for map/plan labelling.', 8
) d
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = d.code);

-- ============================================================
-- CLASS 1 — IELTS Format Knowledge (10 Q)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'How many sections does the IELTS test have in total?' txt
  UNION ALL SELECT 2, 'In IELTS General Training, how long do you get to transfer your answers at the end of the Listening test?'
  UNION ALL SELECT 3, 'How many passages are in the IELTS Reading section?'
  UNION ALL SELECT 4, 'What is the minimum recommended word count for General Training Writing Task 1?'
  UNION ALL SELECT 5, 'What is the minimum recommended word count for Writing Task 2?'
  UNION ALL SELECT 6, 'How many parts does the Speaking test have?'
  UNION ALL SELECT 7, 'What is the highest possible IELTS band score?'
  UNION ALL SELECT 8, 'In General Training, which Writing task is a letter rather than a report or essay?'
  UNION ALL SELECT 9, 'Roughly how long does the Speaking test take?'
  UNION ALL SELECT 10, 'In GT Reading, what kind of topics does Section 1 typically cover?'
) d
WHERE t.code = 'IELTS_GM_C1_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'3' txt,0 correct,1 ord UNION ALL SELECT 1,'B','4',1,2 UNION ALL SELECT 1,'C','5',0,3 UNION ALL SELECT 1,'D','6',0,4
  UNION ALL SELECT 2,'A','5 minutes',0,1 UNION ALL SELECT 2,'B','10 minutes',1,2 UNION ALL SELECT 2,'C','15 minutes',0,3 UNION ALL SELECT 2,'D','No transfer time is given',0,4
  UNION ALL SELECT 3,'A','2',0,1 UNION ALL SELECT 3,'B','3',1,2 UNION ALL SELECT 3,'C','4',0,3 UNION ALL SELECT 3,'D','5',0,4
  UNION ALL SELECT 4,'A','100 words',0,1 UNION ALL SELECT 4,'B','150 words',1,2 UNION ALL SELECT 4,'C','200 words',0,3 UNION ALL SELECT 4,'D','250 words',0,4
  UNION ALL SELECT 5,'A','150 words',0,1 UNION ALL SELECT 5,'B','200 words',0,2 UNION ALL SELECT 5,'C','250 words',1,3 UNION ALL SELECT 5,'D','300 words',0,4
  UNION ALL SELECT 6,'A','1',0,1 UNION ALL SELECT 6,'B','2',0,2 UNION ALL SELECT 6,'C','3',1,3 UNION ALL SELECT 6,'D','4',0,4
  UNION ALL SELECT 7,'A','7',0,1 UNION ALL SELECT 7,'B','8',0,2 UNION ALL SELECT 7,'C','9',1,3 UNION ALL SELECT 7,'D','10',0,4
  UNION ALL SELECT 8,'A','Task 1',1,1 UNION ALL SELECT 8,'B','Task 2',0,2 UNION ALL SELECT 8,'C','Both tasks',0,3 UNION ALL SELECT 8,'D','Neither task',0,4
  UNION ALL SELECT 9,'A','2–4 minutes',0,1 UNION ALL SELECT 9,'B','11–14 minutes',1,2 UNION ALL SELECT 9,'C','20–25 minutes',0,3 UNION ALL SELECT 9,'D','30 minutes',0,4
  UNION ALL SELECT 10,'A','Academic research topics',0,1 UNION ALL SELECT 10,'B','Everyday and social survival topics (notices, ads, timetables)',1,2 UNION ALL SELECT 10,'C','Literary extracts',0,3 UNION ALL SELECT 10,'D','Scientific journal articles',0,4
) d
WHERE t.code = 'IELTS_GM_C1_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 2 — Band Descriptor Criterion Matching (10 Q)
-- Matches class02.php's own description exactly: match the descriptor
-- phrase to the correct marking criterion (Task Achievement / Coherence &
-- Cohesion / Lexical Resource / Grammatical Range & Accuracy) — not a band
-- number. Q1 is the page's own worked example.
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, '"Uses a range of cohesive devices although there may be some inaccuracies." Which criterion is this describing?' txt
  UNION ALL SELECT 2, '"Uses a sufficient range of vocabulary to allow some flexibility and precision." Which criterion?'
  UNION ALL SELECT 3, '"Addresses all parts of the task, though some parts may be more fully covered than others." Which criterion?'
  UNION ALL SELECT 4, '"Uses a mix of simple and complex sentence forms." Which criterion?'
  UNION ALL SELECT 5, '"Makes occasional errors in spelling and/or word formation." Which criterion?'
  UNION ALL SELECT 6, '"Presents a clear overview of the main trends, differences, or stages." Which criterion?'
  UNION ALL SELECT 7, '"Uses paragraphing sufficiently and appropriately." Which criterion?'
  UNION ALL SELECT 8, '"Produces frequent error-free sentences." Which criterion?'
  UNION ALL SELECT 9, '"Uses less common vocabulary with some awareness of style and collocation." Which criterion?'
  UNION ALL SELECT 10, '"May not always use referencing clearly or appropriately." Which criterion?'
) d
WHERE t.code = 'IELTS_GM_C2_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'Task Achievement' txt,0 correct,1 ord UNION ALL SELECT 1,'B','Coherence & Cohesion',1,2 UNION ALL SELECT 1,'C','Lexical Resource',0,3 UNION ALL SELECT 1,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 2,'A','Task Achievement',0,1 UNION ALL SELECT 2,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 2,'C','Lexical Resource',1,3 UNION ALL SELECT 2,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 3,'A','Task Achievement',1,1 UNION ALL SELECT 3,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 3,'C','Lexical Resource',0,3 UNION ALL SELECT 3,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 4,'A','Task Achievement',0,1 UNION ALL SELECT 4,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 4,'C','Lexical Resource',0,3 UNION ALL SELECT 4,'D','Grammatical Range & Accuracy',1,4
  UNION ALL SELECT 5,'A','Task Achievement',0,1 UNION ALL SELECT 5,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 5,'C','Lexical Resource',1,3 UNION ALL SELECT 5,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 6,'A','Task Achievement',1,1 UNION ALL SELECT 6,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 6,'C','Lexical Resource',0,3 UNION ALL SELECT 6,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 7,'A','Task Achievement',0,1 UNION ALL SELECT 7,'B','Coherence & Cohesion',1,2 UNION ALL SELECT 7,'C','Lexical Resource',0,3 UNION ALL SELECT 7,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 8,'A','Task Achievement',0,1 UNION ALL SELECT 8,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 8,'C','Lexical Resource',0,3 UNION ALL SELECT 8,'D','Grammatical Range & Accuracy',1,4
  UNION ALL SELECT 9,'A','Task Achievement',0,1 UNION ALL SELECT 9,'B','Coherence & Cohesion',0,2 UNION ALL SELECT 9,'C','Lexical Resource',1,3 UNION ALL SELECT 9,'D','Grammatical Range & Accuracy',0,4
  UNION ALL SELECT 10,'A','Task Achievement',0,1 UNION ALL SELECT 10,'B','Coherence & Cohesion',1,2 UNION ALL SELECT 10,'C','Lexical Resource',0,3 UNION ALL SELECT 10,'D','Grammatical Range & Accuracy',0,4
) d
WHERE t.code = 'IELTS_GM_C2_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 3 — Gap-Fill Prediction Strategies (10 Q)
-- Matches class03.php's own example format exactly: "identify the expected
-- answer type: name / number / place / date / noun / adjective".
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt,
       'Choose the type of answer you would expect to hear in the gap.', 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'The appointment is scheduled for ___.' txt
  UNION ALL SELECT 2, 'Please contact ___ if you have any questions.'
  UNION ALL SELECT 3, 'The nearest car park is located on ___ Street.'
  UNION ALL SELECT 4, 'Your reference number is ___.'
  UNION ALL SELECT 5, 'The workshop will be held in the ___ room.'
  UNION ALL SELECT 6, 'Please bring a ___ to the induction session.'
  UNION ALL SELECT 7, 'The total cost of the repair was ___.'
  UNION ALL SELECT 8, 'Visitors should report to ___ at the main entrance.'
  UNION ALL SELECT 9, 'The course runs every ___ afternoon.'
  UNION ALL SELECT 10, 'Staff described the new manager as very ___.'
) d
WHERE t.code = 'IELTS_GM_C3_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'Name' txt,0 correct,1 ord UNION ALL SELECT 1,'B','Number',0,2 UNION ALL SELECT 1,'C','Date',1,3 UNION ALL SELECT 1,'D','Adjective',0,4
  UNION ALL SELECT 2,'A','Name',1,1 UNION ALL SELECT 2,'B','Place',0,2 UNION ALL SELECT 2,'C','Date',0,3 UNION ALL SELECT 2,'D','Noun',0,4
  UNION ALL SELECT 3,'A','Name',0,1 UNION ALL SELECT 3,'B','Place',1,2 UNION ALL SELECT 3,'C','Number',0,3 UNION ALL SELECT 3,'D','Adjective',0,4
  UNION ALL SELECT 4,'A','Name',0,1 UNION ALL SELECT 4,'B','Place',0,2 UNION ALL SELECT 4,'C','Number',1,3 UNION ALL SELECT 4,'D','Noun',0,4
  UNION ALL SELECT 5,'A','Place',1,1 UNION ALL SELECT 5,'B','Number',0,2 UNION ALL SELECT 5,'C','Date',0,3 UNION ALL SELECT 5,'D','Name',0,4
  UNION ALL SELECT 6,'A','Noun',1,1 UNION ALL SELECT 6,'B','Number',0,2 UNION ALL SELECT 6,'C','Place',0,3 UNION ALL SELECT 6,'D','Date',0,4
  UNION ALL SELECT 7,'A','Noun',0,1 UNION ALL SELECT 7,'B','Number',1,2 UNION ALL SELECT 7,'C','Name',0,3 UNION ALL SELECT 7,'D','Place',0,4
  UNION ALL SELECT 8,'A','Place/Name',1,1 UNION ALL SELECT 8,'B','Number',0,2 UNION ALL SELECT 8,'C','Date',0,3 UNION ALL SELECT 8,'D','Adjective',0,4
  UNION ALL SELECT 9,'A','Number',0,1 UNION ALL SELECT 9,'B','Day of the week',1,2 UNION ALL SELECT 9,'C','Name',0,3 UNION ALL SELECT 9,'D','Noun',0,4
  UNION ALL SELECT 10,'A','Adjective',1,1 UNION ALL SELECT 10,'B','Number',0,2 UNION ALL SELECT 10,'C','Date',0,3 UNION ALL SELECT 10,'D','Place',0,4
) d
WHERE t.code = 'IELTS_GM_C3_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 4 — Skimming vs Scanning Decision Drill (10 Q)
-- Matches class04.php's own description exactly: given a question/task,
-- decide whether it requires skimming (gist) or scanning (specific info) —
-- not a reading-comprehension quiz. Q1 is the page's own worked example.
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Find the opening hours of the library.' txt
  UNION ALL SELECT 2, 'Get a general idea of what this article is about before reading it in detail.'
  UNION ALL SELECT 3, 'Find the phone number for customer support.'
  UNION ALL SELECT 4, 'Decide whether this passage is mainly about history or mainly about science.'
  UNION ALL SELECT 5, 'Find the exact date of the event mentioned in paragraph 2.'
  UNION ALL SELECT 6, 'Get a rough sense of the writer''s overall opinion before answering detailed questions.'
  UNION ALL SELECT 7, 'Locate the price listed for the premium membership package.'
  UNION ALL SELECT 8, 'Understand the general structure and main sections of a long report quickly.'
  UNION ALL SELECT 9, 'Find the name of the person quoted in the third paragraph.'
  UNION ALL SELECT 10, 'Quickly judge whether this passage is relevant to your research topic.'
) d
WHERE t.code = 'IELTS_GM_C4_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'Skimming' txt,0 correct,1 ord UNION ALL SELECT 1,'B','Scanning',1,2
  UNION ALL SELECT 2,'A','Skimming',1,1 UNION ALL SELECT 2,'B','Scanning',0,2
  UNION ALL SELECT 3,'A','Skimming',0,1 UNION ALL SELECT 3,'B','Scanning',1,2
  UNION ALL SELECT 4,'A','Skimming',1,1 UNION ALL SELECT 4,'B','Scanning',0,2
  UNION ALL SELECT 5,'A','Skimming',0,1 UNION ALL SELECT 5,'B','Scanning',1,2
  UNION ALL SELECT 6,'A','Skimming',1,1 UNION ALL SELECT 6,'B','Scanning',0,2
  UNION ALL SELECT 7,'A','Skimming',0,1 UNION ALL SELECT 7,'B','Scanning',1,2
  UNION ALL SELECT 8,'A','Skimming',1,1 UNION ALL SELECT 8,'B','Scanning',0,2
  UNION ALL SELECT 9,'A','Skimming',0,1 UNION ALL SELECT 9,'B','Scanning',1,2
  UNION ALL SELECT 10,'A','Skimming',1,1 UNION ALL SELECT 10,'B','Scanning',0,2
) d
WHERE t.code = 'IELTS_GM_C4_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 5 — MCQ Elimination Drill (10 Q, written transcript standing in
-- for a recorded extract — no audio asset exists for this drill yet)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, stimulus_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.stim, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
    'Transcript excerpt — a phone call to a gym about membership:

Receptionist: Good morning, Riverside Gym, how can I help?
Caller: Hi, I''m calling about membership prices. I saw an ad for £25 a month.
Receptionist: Ah, that offer actually ended last week, I''m afraid — it''s £32 a month now for a standard adult membership. But if you sign up for a full year upfront, we can do it for £28 a month.
Caller: OK, and does that include the pool?
Receptionist: No, pool access is a separate add-on, it''s an extra £6 a month. Classes like yoga and spin are included in the standard membership though.
Caller: Got it. And is there a joining fee?
Receptionist: There usually is, £15, but we''re waving it for new members this month only.
Caller: Great, I''ll think about it and call back.' stim,
    'What is the CURRENT standard monthly price for adult membership?' txt
  UNION ALL SELECT 2, NULL, 'Why is the £25 price no longer available?'
  UNION ALL SELECT 3, NULL, 'What is the monthly price if the caller pays for a full year upfront?'
  UNION ALL SELECT 4, NULL, 'Is pool access included in the standard membership?'
  UNION ALL SELECT 5, NULL, 'How much extra does pool access cost per month?'
  UNION ALL SELECT 6, NULL, 'Which activities ARE included in the standard membership?'
  UNION ALL SELECT 7, NULL, 'What is the usual joining fee?'
  UNION ALL SELECT 8, NULL, 'Will the caller pay the joining fee if they sign up this month?'
  UNION ALL SELECT 9, NULL, 'What does the caller decide to do at the end of the call?'
  UNION ALL SELECT 10, NULL, 'Which word in the call is a distractor that could trick a listener into writing the wrong original price?'
) d
WHERE t.code = 'IELTS_GM_C5_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'£25' txt,0 correct,1 ord UNION ALL SELECT 1,'B','£28',0,2 UNION ALL SELECT 1,'C','£32',1,3 UNION ALL SELECT 1,'D','£6',0,4
  UNION ALL SELECT 2,'A','It was a one-day sale',0,1 UNION ALL SELECT 2,'B','The advertised offer ended last week',1,2 UNION ALL SELECT 2,'C','It was only for students',0,3 UNION ALL SELECT 2,'D','It was a misprint',0,4
  UNION ALL SELECT 3,'A','£25',0,1 UNION ALL SELECT 3,'B','£28',1,2 UNION ALL SELECT 3,'C','£32',0,3 UNION ALL SELECT 3,'D','£38',0,4
  UNION ALL SELECT 4,'A','Yes, fully included',0,1 UNION ALL SELECT 4,'B','No, it costs extra',1,2 UNION ALL SELECT 4,'C','Only on weekends',0,3 UNION ALL SELECT 4,'D','Not mentioned',0,4
  UNION ALL SELECT 5,'A','£6',1,1 UNION ALL SELECT 5,'B','£15',0,2 UNION ALL SELECT 5,'C','£25',0,3 UNION ALL SELECT 5,'D','It''s free',0,4
  UNION ALL SELECT 6,'A','Pool and spa only',0,1 UNION ALL SELECT 6,'B','Yoga and spin classes',1,2 UNION ALL SELECT 6,'C','Personal training only',0,3 UNION ALL SELECT 6,'D','Nothing is included',0,4
  UNION ALL SELECT 7,'A','£6',0,1 UNION ALL SELECT 7,'B','£15',1,2 UNION ALL SELECT 7,'C','£25',0,3 UNION ALL SELECT 7,'D','£32',0,4
  UNION ALL SELECT 8,'A','Yes, it''s always free',0,1 UNION ALL SELECT 8,'B','No, never waived',0,2 UNION ALL SELECT 8,'C','No, it''s waived this month only',1,3 UNION ALL SELECT 8,'D','Only if they book the pool add-on',0,4
  UNION ALL SELECT 9,'A','Signs up immediately',0,1 UNION ALL SELECT 9,'B','Cancels their current membership',0,2 UNION ALL SELECT 9,'C','Says they''ll think about it and call back',1,3 UNION ALL SELECT 9,'D','Asks to speak to a manager',0,4
  UNION ALL SELECT 10,'A','"£32"',0,1 UNION ALL SELECT 10,'B','"£25" (the outdated advertised price mentioned only to be corrected)',1,2 UNION ALL SELECT 10,'C','"£28"',0,3 UNION ALL SELECT 10,'D','"£15"',0,4
) d
WHERE t.code = 'IELTS_GM_C5_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 6 — TFNG vs YNNG Decision Drill (10 Q, two short texts)
-- Matches class06.php's own description exactly: decide WHICH system
-- applies (TFNG for factual text, YNNG for opinion text) THEN give the
-- correct answer — both decisions folded into one 5-option MCQ
-- (TRUE/FALSE/NOT GIVEN/YES/NO) per question, against whichever text it's
-- paired with (Text 1 = factual notice → TFNG; Text 2 = opinion letter →
-- YNNG). Text 1 reused from the original draft (a plain factual notice);
-- Text 2 is new — an opinion piece, since TFNG-only text can't test YNNG.
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, stimulus_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.stim, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
    'Text 1 (factual notice) — Company Policy Update: Flexible Working

From next month, all staff will be able to apply for flexible working arrangements, including compressed hours and remote work up to two days per week. Applications must be submitted to line managers at least four weeks in advance and will be reviewed within ten working days. Not all roles are eligible — customer-facing positions in the retail division are excluded from remote work, though compressed hours remain an option for all staff. Employees who have been with the company for less than six months are asked to wait until their probation period ends before applying. The company has not yet decided whether to extend this policy to part-time staff; a review is planned for early next year.

Text 2 (opinion letter) — Working From Home: A Reader Writes In

I believe companies that resist offering remote work options are simply behind the times. In my experience, employees who work from home at least part of the week are just as productive as those in the office, because they avoid long commutes and distracting open-plan spaces. Of course, remote work isn''t right for every job — a surgeon obviously needs to be physically present — but for most office-based roles, I think flexibility should be the default, not a special favour granted to a lucky few. Managers who worry about staff "slacking off" at home would do better to focus on results rather than hours logged at a desk.' stim,
    '[Text 1] All staff will be able to apply for flexible working from next month.' txt
  UNION ALL SELECT 2, NULL, '[Text 1] Applications are reviewed within ten working days.'
  UNION ALL SELECT 3, NULL, '[Text 1] Remote work is available to everyone in the retail division.'
  UNION ALL SELECT 4, NULL, '[Text 1] The company has decided to extend the policy to part-time staff.'
  UNION ALL SELECT 5, NULL, '[Text 1] Employees with less than six months'' service are asked to wait until probation ends before applying.'
  UNION ALL SELECT 6, NULL, '[Text 2] The writer believes companies avoiding remote work are behind the times.'
  UNION ALL SELECT 7, NULL, '[Text 2] The writer thinks remote work is suitable for every type of job.'
  UNION ALL SELECT 8, NULL, '[Text 2] The writer suggests managers should judge staff by results rather than hours worked.'
  UNION ALL SELECT 9, NULL, '[Text 2] The writer has personally worked as a surgeon.'
  UNION ALL SELECT 10, NULL, '[Text 2] The writer thinks flexibility should be an exception rather than the norm.'
) d
WHERE t.code = 'IELTS_GM_C6_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  -- Text 1 questions (TFNG): options are TRUE/FALSE/NOT GIVEN/YES/NO but
  -- only the TFNG trio is ever correct here — YES/NO are present as
  -- distractors so students must first recognise WHICH system applies.
  SELECT 1 qn,'A' lbl,'TRUE' txt,1 correct,1 ord UNION ALL SELECT 1,'B','FALSE',0,2 UNION ALL SELECT 1,'C','NOT GIVEN',0,3 UNION ALL SELECT 1,'D','YES',0,4 UNION ALL SELECT 1,'E','NO',0,5
  UNION ALL SELECT 2,'A','TRUE',1,1 UNION ALL SELECT 2,'B','FALSE',0,2 UNION ALL SELECT 2,'C','NOT GIVEN',0,3 UNION ALL SELECT 2,'D','YES',0,4 UNION ALL SELECT 2,'E','NO',0,5
  UNION ALL SELECT 3,'A','TRUE',0,1 UNION ALL SELECT 3,'B','FALSE',1,2 UNION ALL SELECT 3,'C','NOT GIVEN',0,3 UNION ALL SELECT 3,'D','YES',0,4 UNION ALL SELECT 3,'E','NO',0,5
  UNION ALL SELECT 4,'A','TRUE',0,1 UNION ALL SELECT 4,'B','FALSE',0,2 UNION ALL SELECT 4,'C','NOT GIVEN',1,3 UNION ALL SELECT 4,'D','YES',0,4 UNION ALL SELECT 4,'E','NO',0,5
  UNION ALL SELECT 5,'A','TRUE',1,1 UNION ALL SELECT 5,'B','FALSE',0,2 UNION ALL SELECT 5,'C','NOT GIVEN',0,3 UNION ALL SELECT 5,'D','YES',0,4 UNION ALL SELECT 5,'E','NO',0,5
  -- Text 2 questions (YNNG): only the YNNG trio is ever correct.
  UNION ALL SELECT 6,'A','TRUE',0,1 UNION ALL SELECT 6,'B','FALSE',0,2 UNION ALL SELECT 6,'C','NOT GIVEN',0,3 UNION ALL SELECT 6,'D','YES',1,4 UNION ALL SELECT 6,'E','NO',0,5
  UNION ALL SELECT 7,'A','TRUE',0,1 UNION ALL SELECT 7,'B','FALSE',0,2 UNION ALL SELECT 7,'C','NOT GIVEN',0,3 UNION ALL SELECT 7,'D','YES',0,4 UNION ALL SELECT 7,'E','NO',1,5
  UNION ALL SELECT 8,'A','TRUE',0,1 UNION ALL SELECT 8,'B','FALSE',0,2 UNION ALL SELECT 8,'C','NOT GIVEN',0,3 UNION ALL SELECT 8,'D','YES',1,4 UNION ALL SELECT 8,'E','NO',0,5
  UNION ALL SELECT 9,'A','TRUE',0,1 UNION ALL SELECT 9,'B','FALSE',0,2 UNION ALL SELECT 9,'C','NOT GIVEN',1,3 UNION ALL SELECT 9,'D','YES',0,4 UNION ALL SELECT 9,'E','NO',0,5
  UNION ALL SELECT 10,'A','TRUE',0,1 UNION ALL SELECT 10,'B','FALSE',0,2 UNION ALL SELECT 10,'C','NOT GIVEN',0,3 UNION ALL SELECT 10,'D','YES',0,4 UNION ALL SELECT 10,'E','NO',1,5
) d
WHERE t.code = 'IELTS_GM_C6_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- CLASS 7 — Map Orientation Drill (8 Q, text-described layout — no map
-- image asset exists yet, so the layout is described in words instead)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, stimulus_text, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.stim, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
    'You are standing at the Main Entrance of a community centre, facing North (into the building).
- The Reception desk is directly ahead of you, at the far end of the entrance hall.
- The Cafe is to your right as you enter, next to the entrance hall.
- The Library is to your left as you enter.
- Turning right at Reception and continuing to the end of that corridor brings you to the Sports Hall.
- Turning left at Reception and continuing to the end of that corridor brings you to the Meeting Rooms.
- The car park is directly behind you (South of the entrance), across the road.' stim,
    'Which room is directly ahead of you as you walk in through the Main Entrance?' txt
  UNION ALL SELECT 2, NULL, 'Which room is on your right as you enter the building?'
  UNION ALL SELECT 3, NULL, 'Which room is on your left as you enter the building?'
  UNION ALL SELECT 4, NULL, 'To reach the Sports Hall from the Main Entrance, which way do you turn at Reception?'
  UNION ALL SELECT 5, NULL, 'To reach the Meeting Rooms from the Main Entrance, which way do you turn at Reception?'
  UNION ALL SELECT 6, NULL, 'Which direction are you facing as you walk in through the Main Entrance?'
  UNION ALL SELECT 7, NULL, 'Where is the car park relative to the Main Entrance?'
  UNION ALL SELECT 8, NULL, 'If you are at Reception and want to go back outside to the car park, which direction do you walk?'
) d
WHERE t.code = 'IELTS_GM_C7_QUIZ'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'Reception' txt,1 correct,1 ord UNION ALL SELECT 1,'B','Cafe',0,2 UNION ALL SELECT 1,'C','Library',0,3 UNION ALL SELECT 1,'D','Sports Hall',0,4
  UNION ALL SELECT 2,'A','Reception',0,1 UNION ALL SELECT 2,'B','Cafe',1,2 UNION ALL SELECT 2,'C','Library',0,3 UNION ALL SELECT 2,'D','Meeting Rooms',0,4
  UNION ALL SELECT 3,'A','Library',1,1 UNION ALL SELECT 3,'B','Cafe',0,2 UNION ALL SELECT 3,'C','Sports Hall',0,3 UNION ALL SELECT 3,'D','Reception',0,4
  UNION ALL SELECT 4,'A','Left',0,1 UNION ALL SELECT 4,'B','Right',1,2 UNION ALL SELECT 4,'C','Straight ahead',0,3 UNION ALL SELECT 4,'D','Back the way you came',0,4
  UNION ALL SELECT 5,'A','Left',1,1 UNION ALL SELECT 5,'B','Right',0,2 UNION ALL SELECT 5,'C','Straight ahead',0,3 UNION ALL SELECT 5,'D','Back the way you came',0,4
  UNION ALL SELECT 6,'A','North',1,1 UNION ALL SELECT 6,'B','South',0,2 UNION ALL SELECT 6,'C','East',0,3 UNION ALL SELECT 6,'D','West',0,4
  UNION ALL SELECT 7,'A','North of the entrance',0,1 UNION ALL SELECT 7,'B','South of the entrance, across the road',1,2 UNION ALL SELECT 7,'C','Inside the building',0,3 UNION ALL SELECT 7,'D','Next to the Library',0,4
  UNION ALL SELECT 8,'A','North',0,1 UNION ALL SELECT 8,'B','South',1,2 UNION ALL SELECT 8,'C','East',0,3 UNION ALL SELECT 8,'D','West',0,4
) d
WHERE t.code = 'IELTS_GM_C7_QUIZ' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- Verify: expect 7 tests (IELTS_GM_C1..C7_QUIZ), 66 questions total (10+8+10+10+10+10+8), each with options summing is_correct=1 exactly once
-- SELECT t.code, COUNT(q.id) FROM tests t JOIN questions q ON q.test_id=t.id WHERE t.code LIKE 'IELTS_GM_%' GROUP BY t.code;
