-- ============================================================
-- Migration 025 — Seed IELTS_FM2_R (Reading Full Mock Test 2)
-- Cambridge IELTS GT Test 2 — full real content
--
-- NON-DESTRUCTIVE: each INSERT is guarded by a COUNT = 0 check.
-- Run on LOCAL (useraccounts) first, then LIVE (slslanguage_db)
--
-- Scoring paths (matches mock_save_section.php precedence):
--   Q1–7   true_false_not_given  → question_correct_answers
--   Q8–14  matching (A–H)        → question_correct_answers + question_options
--   Q15–20 form_note_completion  → question_correct_answers
--   Q21–27 sentence_completion   → question_correct_answers
--   Q28–32 multiple_choice_single→ question_options.is_correct=1 only
--   Q33–36 matching (A–G)        → question_correct_answers + question_options
--   Q37–40 summary_completion    → question_correct_answers (up to 2 words)
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM2_R', 'IELTS Full Mock 2 — Reading',
       'Cambridge IELTS GT Test 2 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM2_R');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM2_R' LIMIT 1);

-- ============================================================
-- Step 2: Insert all 40 questions
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order)
SELECT test_id, part_number, question_number, stimulus_text, question_text, question_type, instructions, points, display_order FROM (

-- ── Section 1 · Passage 1: How to choose your builder (Q1–7) ─────────────
-- true_false_not_given
SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
    'How to choose your builder' AS stimulus_text,
    'After selecting a builder, you should decide on the design of your new house.' AS question_text,
    'true_false_not_given' AS question_type,
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.' AS instructions,
    1.0 AS points, 10 AS display_order
UNION ALL SELECT @tid, 1, 2, NULL,
    'In Australia, you can make sure that a builder has the appropriate licence.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 20
UNION ALL SELECT @tid, 1, 3, NULL,
    'The best builders usually belong to the Housing Industry Association.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 30
UNION ALL SELECT @tid, 1, 4, NULL,
    'The HIA gives an award to builders whose standards of customer service are very high.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 40
UNION ALL SELECT @tid, 1, 5, NULL,
    'Builders who work on smaller projects are more likely to have display homes.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 50
UNION ALL SELECT @tid, 1, 6, NULL,
    'It is advisable to have a contract which is in accordance with the Domestic Building Contracts Act.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 60
UNION ALL SELECT @tid, 1, 7, NULL,
    'A contract is legally binding from the time it has been signed.',
    'true_false_not_given',
    'Do the following statements agree with the information given in the text? Write TRUE, FALSE or NOT GIVEN.',
    1.0, 70

-- ── Section 1 · Passage 2: Island adventure activities (Q8–14) ───────────
-- matching: write letter A–H for which sport the statement describes
UNION ALL SELECT @tid, 1, 8, 'Island adventure activities',
    'You will be provided with safety equipment.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 80
UNION ALL SELECT @tid, 1, 9, NULL,
    'You may get some minor injuries doing this activity.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 90
UNION ALL SELECT @tid, 1, 10, NULL,
    'You can see a disused, isolated building.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 100
UNION ALL SELECT @tid, 1, 11, NULL,
    'You can relax and look down from above in an unusual location.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 110
UNION ALL SELECT @tid, 1, 12, NULL,
    'You will take an exciting trip in rough water close to big ships.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 120
UNION ALL SELECT @tid, 1, 13, NULL,
    'You can choose easy options or more difficult ones.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 130
UNION ALL SELECT @tid, 1, 14, NULL,
    'You may find this more difficult than you expect.',
    'matching',
    'Look at the eight advertisements for adventure sports on an island, A–H. For which adventure sport are the following statements true? Write the correct letter, A–H. NB You may use any letter more than once.',
    1.0, 140

-- ── Section 2 · Passage 1: Barrington Music Service (Q15–20) ─────────────
-- form_note_completion, ONE WORD ONLY
UNION ALL SELECT @tid, 2, 15, 'Barrington Music Service',
    'events such as ___ for local and visiting schools',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 150
UNION ALL SELECT @tid, 2, 16, NULL,
    'be responsible for keeping to the ___',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 160
UNION ALL SELECT @tid, 2, 17, NULL,
    'build ___ with other organisations',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 170
UNION ALL SELECT @tid, 2, 18, NULL,
    'increase the focus on ___ in school music lessons (e.g., international styles)',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 180
UNION ALL SELECT @tid, 2, 19, NULL,
    'make sure records and a ___ is kept up-to-date',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 190
UNION ALL SELECT @tid, 2, 20, NULL,
    'basic knowledge of ___',
    'form_note_completion',
    'Complete the notes below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 200

-- ── Section 2 · Passage 2: Health and safety in small businesses (Q21–27) ─
-- sentence_completion, ONE WORD ONLY
UNION ALL SELECT @tid, 2, 21, 'Health and safety in small businesses',
    'One cause of health and safety problems in small businesses is that managers do not have enough relevant ___.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 210
UNION ALL SELECT @tid, 2, 22, NULL,
    'Managers complain they have too many ___ to deal with.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 220
UNION ALL SELECT @tid, 2, 23, NULL,
    'Managers may not fully understand their ___.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 230
UNION ALL SELECT @tid, 2, 24, NULL,
    'Businesses sometimes feel that inspectors give them far too many ___.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 240
UNION ALL SELECT @tid, 2, 25, NULL,
    'Businesses above a certain size must produce a written ___ of their health and safety policy.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 250
UNION ALL SELECT @tid, 2, 26, NULL,
    'A company''s health and safety policy is relevant to both its employees and its ___.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 260
UNION ALL SELECT @tid, 2, 27, NULL,
    'The Health and Safety Executive can advise small businesses on problems of ___ among their employees.',
    'sentence_completion',
    'Complete the sentences below. Choose ONE WORD ONLY from the text for each answer.',
    1.0, 270

-- ── Section 3 · Jobs in ancient Egypt (Q28–32 MCQ) ───────────────────────
-- multiple_choice_single — scored via question_options.is_correct only
UNION ALL SELECT @tid, 3, 28, 'Jobs in ancient Egypt',
    'What does the writer say about scribes in ancient Egypt?',
    'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.',
    1.0, 280
UNION ALL SELECT @tid, 3, 29, NULL,
    'What is the writer doing in the second paragraph?',
    'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.',
    1.0, 290
UNION ALL SELECT @tid, 3, 30, NULL,
    'What is the writer doing in the fifth paragraph?',
    'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.',
    1.0, 300
UNION ALL SELECT @tid, 3, 31, NULL,
    'The writer refers to the value of ma''at in order to explain',
    'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.',
    1.0, 310
UNION ALL SELECT @tid, 3, 32, NULL,
    'Which word best describes the attitude of the Egyptian government toward its workers?',
    'multiple_choice_single',
    'Choose the correct letter, A, B, C or D.',
    1.0, 320

-- ── Section 3 · Job matching (Q33–36) ────────────────────────────────────
-- matching: match statement to job A–G
UNION ALL SELECT @tid, 3, 33, 'List of Jobs — A: scribe  B: reed cutter  C: farmer  D: potter  E: stonemason  F: overseer  G: sculptor',
    'was unable to work at certain times',
    'matching',
    'Look at the following statements and the list of jobs below. Match each statement with the correct job, A–G. Write the correct letter, A–G.',
    1.0, 330
UNION ALL SELECT @tid, 3, 34, NULL,
    'divided workers into groups',
    'matching',
    'Look at the following statements and the list of jobs below. Match each statement with the correct job, A–G. Write the correct letter, A–G.',
    1.0, 340
UNION ALL SELECT @tid, 3, 35, NULL,
    'faced daily hazards',
    'matching',
    'Look at the following statements and the list of jobs below. Match each statement with the correct job, A–G. Write the correct letter, A–G.',
    1.0, 350
UNION ALL SELECT @tid, 3, 36, NULL,
    'underwent a long period of training',
    'matching',
    'Look at the following statements and the list of jobs below. Match each statement with the correct job, A–G. Write the correct letter, A–G.',
    1.0, 360

-- ── Section 3 · Summary completion Q37–40 (NO MORE THAN TWO WORDS) ───────
UNION ALL SELECT @tid, 3, 37, 'The king''s building projects',
    'Labourers who worked on the king''s buildings were local people who chose to participate in ___ or who received payment.',
    'summary_completion',
    'Complete the summary below. Choose NO MORE THAN TWO WORDS from the text for each answer.',
    1.0, 370
UNION ALL SELECT @tid, 3, 38, NULL,
    'The large pieces of stone were then transported to another site on sleds, which moved easily over the ___.',
    'summary_completion',
    'Complete the summary below. Choose NO MORE THAN TWO WORDS from the text for each answer.',
    1.0, 380
UNION ALL SELECT @tid, 3, 39, NULL,
    'Here, the blocks could be cut and shaped using tools made of ___ and wood.',
    'summary_completion',
    'Complete the summary below. Choose NO MORE THAN TWO WORDS from the text for each answer.',
    1.0, 390
UNION ALL SELECT @tid, 3, 40, NULL,
    'Eventually, the stone was moved into place to create a building. The job of moving the stone was often done by ___ or other unskilled workers.',
    'summary_completion',
    'Complete the summary below. Choose NO MORE THAN TWO WORDS from the text for each answer.',
    1.0, 400

) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- ============================================================
-- Step 3: Resolve question IDs
-- ============================================================
SET @q1  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  1);
SET @q2  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  2);
SET @q3  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  3);
SET @q4  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  4);
SET @q5  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  5);
SET @q6  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  6);
SET @q7  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  7);
SET @q8  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  8);
SET @q9  = (SELECT id FROM questions WHERE test_id = @tid AND question_number =  9);
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
-- Step 4: Insert MCQ options (Q28–32) and matching options (Q8–14, Q33–36)
-- ============================================================
INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT question_id, option_label, option_text, is_correct, display_order FROM (

-- ── Q8–14: Island adventure activities — shared option set A–H ───────────
-- Each matching question gets its own full copy of the A–H options (same as
-- how FM1 reading handles matching — picker needs options per question_id).
-- Q8
SELECT @q8 AS question_id, 'A' AS option_label, 'Rib riding'       AS option_text, 0 AS is_correct, 10 AS display_order
UNION ALL SELECT @q8,  'B', 'Horse riding',    0, 20
UNION ALL SELECT @q8,  'C', 'Kayaking',        0, 30
UNION ALL SELECT @q8,  'D', 'Cycling',         0, 40
UNION ALL SELECT @q8,  'E', 'Segway riding',   0, 50
UNION ALL SELECT @q8,  'F', 'Tree climbing',   1, 60
UNION ALL SELECT @q8,  'G', 'Coasteering',     0, 70
UNION ALL SELECT @q8,  'H', 'Mountain boarding',0, 80
-- Q9
UNION ALL SELECT @q9,  'A', 'Rib riding',      0, 10
UNION ALL SELECT @q9,  'B', 'Horse riding',    0, 20
UNION ALL SELECT @q9,  'C', 'Kayaking',        0, 30
UNION ALL SELECT @q9,  'D', 'Cycling',         0, 40
UNION ALL SELECT @q9,  'E', 'Segway riding',   0, 50
UNION ALL SELECT @q9,  'F', 'Tree climbing',   0, 60
UNION ALL SELECT @q9,  'G', 'Coasteering',     0, 70
UNION ALL SELECT @q9,  'H', 'Mountain boarding',1, 80
-- Q10
UNION ALL SELECT @q10, 'A', 'Rib riding',      0, 10
UNION ALL SELECT @q10, 'B', 'Horse riding',    0, 20
UNION ALL SELECT @q10, 'C', 'Kayaking',        1, 30
UNION ALL SELECT @q10, 'D', 'Cycling',         0, 40
UNION ALL SELECT @q10, 'E', 'Segway riding',   0, 50
UNION ALL SELECT @q10, 'F', 'Tree climbing',   0, 60
UNION ALL SELECT @q10, 'G', 'Coasteering',     0, 70
UNION ALL SELECT @q10, 'H', 'Mountain boarding',0, 80
-- Q11
UNION ALL SELECT @q11, 'A', 'Rib riding',      0, 10
UNION ALL SELECT @q11, 'B', 'Horse riding',    0, 20
UNION ALL SELECT @q11, 'C', 'Kayaking',        0, 30
UNION ALL SELECT @q11, 'D', 'Cycling',         0, 40
UNION ALL SELECT @q11, 'E', 'Segway riding',   0, 50
UNION ALL SELECT @q11, 'F', 'Tree climbing',   1, 60
UNION ALL SELECT @q11, 'G', 'Coasteering',     0, 70
UNION ALL SELECT @q11, 'H', 'Mountain boarding',0, 80
-- Q12
UNION ALL SELECT @q12, 'A', 'Rib riding',      1, 10
UNION ALL SELECT @q12, 'B', 'Horse riding',    0, 20
UNION ALL SELECT @q12, 'C', 'Kayaking',        0, 30
UNION ALL SELECT @q12, 'D', 'Cycling',         0, 40
UNION ALL SELECT @q12, 'E', 'Segway riding',   0, 50
UNION ALL SELECT @q12, 'F', 'Tree climbing',   0, 60
UNION ALL SELECT @q12, 'G', 'Coasteering',     0, 70
UNION ALL SELECT @q12, 'H', 'Mountain boarding',0, 80
-- Q13
UNION ALL SELECT @q13, 'A', 'Rib riding',      0, 10
UNION ALL SELECT @q13, 'B', 'Horse riding',    0, 20
UNION ALL SELECT @q13, 'C', 'Kayaking',        0, 30
UNION ALL SELECT @q13, 'D', 'Cycling',         1, 40
UNION ALL SELECT @q13, 'E', 'Segway riding',   0, 50
UNION ALL SELECT @q13, 'F', 'Tree climbing',   0, 60
UNION ALL SELECT @q13, 'G', 'Coasteering',     0, 70
UNION ALL SELECT @q13, 'H', 'Mountain boarding',0, 80
-- Q14
UNION ALL SELECT @q14, 'A', 'Rib riding',      0, 10
UNION ALL SELECT @q14, 'B', 'Horse riding',    0, 20
UNION ALL SELECT @q14, 'C', 'Kayaking',        0, 30
UNION ALL SELECT @q14, 'D', 'Cycling',         0, 40
UNION ALL SELECT @q14, 'E', 'Segway riding',   1, 50
UNION ALL SELECT @q14, 'F', 'Tree climbing',   0, 60
UNION ALL SELECT @q14, 'G', 'Coasteering',     0, 70
UNION ALL SELECT @q14, 'H', 'Mountain boarding',0, 80

-- ── Q28: scribes (correct: B) ─────────────────────────────────────────────
UNION ALL SELECT @q28, 'A', 'Their working days were very long.',              0, 10
UNION ALL SELECT @q28, 'B', 'The topics they wrote about were very varied.',   1, 20
UNION ALL SELECT @q28, 'C', 'Many of them were once ordinary working people.', 0, 30
UNION ALL SELECT @q28, 'D', 'Few of them realised the true value of their occupation.', 0, 40

-- ── Q29: second paragraph (correct: A) ───────────────────────────────────
UNION ALL SELECT @q29, 'A', 'explaining why jobs were plentiful in ancient Egypt',           1, 10
UNION ALL SELECT @q29, 'B', 'pointing out how honest workers were in ancient Egypt',         0, 20
UNION ALL SELECT @q29, 'C', 'comparing manual and professional work in ancient Egypt',       0, 30
UNION ALL SELECT @q29, 'D', 'noting the range of duties an individual worker had in ancient Egypt', 0, 40

-- ── Q30: fifth paragraph (correct: C) ────────────────────────────────────
UNION ALL SELECT @q30, 'A', 'explaining a problem',      0, 10
UNION ALL SELECT @q30, 'B', 'describing a change',       0, 20
UNION ALL SELECT @q30, 'C', 'rejecting a popular view',  1, 30
UNION ALL SELECT @q30, 'D', 'criticising a past activity',0, 40

-- ── Q31: ma'at (correct: B) ──────────────────────────────────────────────
UNION ALL SELECT @q31, 'A', 'how the work of artists reflected beliefs in ancient Egypt.',    0, 10
UNION ALL SELECT @q31, 'B', 'how ancient Egyptians viewed their role in society.',            1, 20
UNION ALL SELECT @q31, 'C', 'why the opinions of certain people were valued in ancient Egypt.',0, 30
UNION ALL SELECT @q31, 'D', 'why ancient Egyptians expressed their views so readily.',        0, 40

-- ── Q32: government attitude (correct: D) ────────────────────────────────
UNION ALL SELECT @q32, 'A', 'strict',       0, 10
UNION ALL SELECT @q32, 'B', 'patient',      0, 20
UNION ALL SELECT @q32, 'C', 'negligent',    0, 30
UNION ALL SELECT @q32, 'D', 'appreciative', 1, 40

-- ── Q33–36: job matching options A–G (one copy per question) ─────────────
-- Q33
UNION ALL SELECT @q33, 'A', 'scribe',      0, 10
UNION ALL SELECT @q33, 'B', 'reed cutter', 0, 20
UNION ALL SELECT @q33, 'C', 'farmer',      1, 30
UNION ALL SELECT @q33, 'D', 'potter',      0, 40
UNION ALL SELECT @q33, 'E', 'stonemason',  0, 50
UNION ALL SELECT @q33, 'F', 'overseer',    0, 60
UNION ALL SELECT @q33, 'G', 'sculptor',    0, 70
-- Q34
UNION ALL SELECT @q34, 'A', 'scribe',      0, 10
UNION ALL SELECT @q34, 'B', 'reed cutter', 0, 20
UNION ALL SELECT @q34, 'C', 'farmer',      0, 30
UNION ALL SELECT @q34, 'D', 'potter',      0, 40
UNION ALL SELECT @q34, 'E', 'stonemason',  0, 50
UNION ALL SELECT @q34, 'F', 'overseer',    1, 60
UNION ALL SELECT @q34, 'G', 'sculptor',    0, 70
-- Q35
UNION ALL SELECT @q35, 'A', 'scribe',      0, 10
UNION ALL SELECT @q35, 'B', 'reed cutter', 1, 20
UNION ALL SELECT @q35, 'C', 'farmer',      0, 30
UNION ALL SELECT @q35, 'D', 'potter',      0, 40
UNION ALL SELECT @q35, 'E', 'stonemason',  0, 50
UNION ALL SELECT @q35, 'F', 'overseer',    0, 60
UNION ALL SELECT @q35, 'G', 'sculptor',    0, 70
-- Q36
UNION ALL SELECT @q36, 'A', 'scribe',      1, 10
UNION ALL SELECT @q36, 'B', 'reed cutter', 0, 20
UNION ALL SELECT @q36, 'C', 'farmer',      0, 30
UNION ALL SELECT @q36, 'D', 'potter',      0, 40
UNION ALL SELECT @q36, 'E', 'stonemason',  0, 50
UNION ALL SELECT @q36, 'F', 'overseer',    0, 60
UNION ALL SELECT @q36, 'G', 'sculptor',    0, 70

) _opts
WHERE (SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Step 5: Insert correct answers
-- ============================================================
-- Covers: Q1–7 (T/F/NG), Q8–14 (matching A–H), Q15–27 (word completion),
--         Q33–36 (job matching), Q37–40 (summary, ≤2 words).
-- Q28–32 MCQ scored via question_options.is_correct — no rows needed here.
-- ============================================================
INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT question_id, answer_text, is_case_sensitive, is_alternative FROM (

-- Q1–7: TRUE / FALSE / NOT GIVEN
SELECT @q1  AS question_id, 'FALSE'     AS answer_text, 0 AS is_case_sensitive, 0 AS is_alternative
UNION ALL SELECT @q2,  'TRUE',      0, 0
UNION ALL SELECT @q3,  'NOT GIVEN', 0, 0
UNION ALL SELECT @q4,  'TRUE',      0, 0
UNION ALL SELECT @q5,  'FALSE',     0, 0
UNION ALL SELECT @q6,  'TRUE',      0, 0
UNION ALL SELECT @q7,  'FALSE',     0, 0

-- Q8–14: island adventure matching (letter answers, case-insensitive)
UNION ALL SELECT @q8,  'F', 0, 0  UNION ALL SELECT @q8,  'f', 0, 1
UNION ALL SELECT @q9,  'H', 0, 0  UNION ALL SELECT @q9,  'h', 0, 1
UNION ALL SELECT @q10, 'C', 0, 0  UNION ALL SELECT @q10, 'c', 0, 1
UNION ALL SELECT @q11, 'F', 0, 0  UNION ALL SELECT @q11, 'f', 0, 1
UNION ALL SELECT @q12, 'A', 0, 0  UNION ALL SELECT @q12, 'a', 0, 1
UNION ALL SELECT @q13, 'D', 0, 0  UNION ALL SELECT @q13, 'd', 0, 1
UNION ALL SELECT @q14, 'E', 0, 0  UNION ALL SELECT @q14, 'e', 0, 1

-- Q15–20: Barrington Music Service (ONE WORD ONLY)
UNION ALL SELECT @q15, 'festivals',    0, 0
UNION ALL SELECT @q16, 'budget',       0, 0
UNION ALL SELECT @q17, 'partnerships', 0, 0
UNION ALL SELECT @q18, 'diversity',    0, 0
UNION ALL SELECT @q19, 'database',     0, 0
UNION ALL SELECT @q20, 'accounting',   0, 0

-- Q21–27: Health and safety in small businesses (ONE WORD ONLY)
UNION ALL SELECT @q21, 'knowledge',        0, 0
UNION ALL SELECT @q22, 'regulations',      0, 0
UNION ALL SELECT @q23, 'responsibilities', 0, 0
UNION ALL SELECT @q24, 'leaflets',         0, 0
UNION ALL SELECT @q25, 'statement',        0, 0
UNION ALL SELECT @q26, 'contractors',      0, 0
UNION ALL SELECT @q27, 'stress',           0, 0

-- Q33–36: job matching (letter answers, case-insensitive)
UNION ALL SELECT @q33, 'C', 0, 0  UNION ALL SELECT @q33, 'c', 0, 1
UNION ALL SELECT @q34, 'F', 0, 0  UNION ALL SELECT @q34, 'f', 0, 1
UNION ALL SELECT @q35, 'B', 0, 0  UNION ALL SELECT @q35, 'b', 0, 1
UNION ALL SELECT @q36, 'A', 0, 0  UNION ALL SELECT @q36, 'a', 0, 1

-- Q37–40: summary completion (NO MORE THAN TWO WORDS)
UNION ALL SELECT @q37, 'community service', 0, 0
UNION ALL SELECT @q38, 'shifting sand',     0, 0
UNION ALL SELECT @q39, 'copper',            0, 0
UNION ALL SELECT @q40, 'farmers',           0, 0

) _ans
WHERE (SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid) = 0;

-- ============================================================
-- Verify
-- SELECT COUNT(*) FROM questions WHERE test_id = @tid;                                                                 -- expect 40
-- SELECT COUNT(*) FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;          -- expect 104 (7×8 matching + 5×4 MCQ + 4×7 job matching)
-- SELECT COUNT(*) FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;-- expect 46 (7 T/F/NG + 7×2 matching + 6 word + 7 sentence + 4×2 job matching + 4 summary)
-- ============================================================
