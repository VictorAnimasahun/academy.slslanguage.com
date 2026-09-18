-- ============================================================
-- Migration 098 — Replace CELPIP Reading Practice Test 1 content
-- (test_code = CELPIP_PT_R_001, file = resources/practice_tests/celpip_reading_001.php)
--
-- Instructor request 2026-09-18: replace the entire test with new content
-- ("Reading Practice Test C" — labelled C since the two Full Mocks are A
-- and B), sourced from Downloads/files (3)/Reading_Practice_Test_C.docx +
-- its Answer Key docx. Same test_code, same 38-question shape (Part 1:
-- 1-11, Part 2: 12-19, Part 3: 20-28, Part 4: 29-38) — only the actual
-- question/option content changes, so this DELETEs the old
-- questions/options/answers for this test_code and re-seeds fresh, rather
-- than UPDATE-ing in place (the number of options per question and the
-- underlying content are both completely different from the old Test 1).
--
-- The PHP file renders all passage/question/option TEXT itself from a
-- hardcoded array — this migration only needs to seed the DB answer key
-- (questions + question_options.is_correct) that loadTestAnswers() reads
-- by test_code + question_number for scoring. question_text here is a
-- short reference label only, not what students actually see.
-- ============================================================

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001' LIMIT 1);

DELETE qo FROM question_options qo JOIN questions q ON q.id = qo.question_id WHERE q.test_id = @tid;
DELETE qca FROM question_correct_answers qca JOIN questions q ON q.id = qca.question_id WHERE q.test_id = @tid;
DELETE FROM questions WHERE test_id = @tid;

-- ── Part 1: Reading Correspondence (Q1-11) ─────────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT @tid, d.qn, d.txt, 'multiple_choice_single', 1, d.qn
FROM (
  SELECT 1 qn, "Max, Sofia's cat, is now..." txt
  UNION ALL SELECT 2, 'Based on the letter, Sofia most likely wants Priya to visit...'
  UNION ALL SELECT 3, 'Overall, Sofia seems to feel...'
  UNION ALL SELECT 4, 'Devon is described as someone who...'
  UNION ALL SELECT 5, "Based on the letter, Sofia's most urgent concern is..."
  UNION ALL SELECT 6, "The overall tone of Sofia's letter is best described as..."
  UNION ALL SELECT 7, 'Blank (7) — Priya reply'
  UNION ALL SELECT 8, 'Blank (8) — Priya reply'
  UNION ALL SELECT 9, 'Blank (9) — Priya reply'
  UNION ALL SELECT 10, 'Blank (10) — Priya reply'
  UNION ALL SELECT 11, 'Blank (11) — Priya reply'
) d;

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN (
  SELECT 1 qn, 'B' letter UNION ALL SELECT 2, 'A' UNION ALL SELECT 3, 'C' UNION ALL SELECT 4, 'D'
  UNION ALL SELECT 5, 'A' UNION ALL SELECT 6, 'D' UNION ALL SELECT 7, 'C' UNION ALL SELECT 8, 'B'
  UNION ALL SELECT 9, 'A' UNION ALL SELECT 10, 'C' UNION ALL SELECT 11, 'D'
) ans ON ans.qn = q.question_number
JOIN (
  SELECT 1 qn,'A' lbl,'refusing to eat.' txt,1 ord UNION ALL SELECT 1,'B','showing signs of settling in.',2 UNION ALL SELECT 1,'C','confined to a crate at the vet.',3 UNION ALL SELECT 1,'D','staying with a neighbour while they finish unpacking.',4
  UNION ALL SELECT 2,'A','sometime after September.',1 UNION ALL SELECT 2,'B','before Labour Day.',2 UNION ALL SELECT 2,'C',"during Priya's busiest week at work.",3 UNION ALL SELECT 2,'D','once Tomas starts a new job.',4
  UNION ALL SELECT 3,'A','regretful about the move.',1 UNION ALL SELECT 3,'B','anxious about Tomas above all else.',2 UNION ALL SELECT 3,'C','quietly optimistic despite some rough patches.',3 UNION ALL SELECT 3,'D','frustrated with the moving company.',4
  UNION ALL SELECT 4,'A','hired Sofia over other candidates.',1 UNION ALL SELECT 4,'B','works remotely most days.',2 UNION ALL SELECT 4,'C','left Sofia to learn independently.',3 UNION ALL SELECT 4,'D','personally trained Sofia during her first days.',4
  UNION ALL SELECT 5,'A','whether their savings will hold up while Tomas is between jobs.',1 UNION ALL SELECT 5,'B','whether Max will fully settle into the new apartment.',2 UNION ALL SELECT 5,'C','whether Tomas regrets leaving his previous job.',3 UNION ALL SELECT 5,'D',"whether her new health plan covers Tomas's dental care.",4
  UNION ALL SELECT 6,'A','formal and reserved.',1 UNION ALL SELECT 6,'B','excited and carefree.',2 UNION ALL SELECT 6,'C','apologetic and regretful.',3 UNION ALL SELECT 6,'D','warm and candid.',4
  UNION ALL SELECT 7,'A','send flowers for the new apartment.',1 UNION ALL SELECT 7,'B','ask Devon for career advice.',2 UNION ALL SELECT 7,'C','check in properly, not just by email.',3 UNION ALL SELECT 7,'D','recommend a better moving company.',4
  UNION ALL SELECT 8,'A','reviews your work daily.',1 UNION ALL SELECT 8,'B','takes the time to show you the ropes.',2 UNION ALL SELECT 8,'C','assigns you a mentor.',3 UNION ALL SELECT 8,'D','checks in by email.',4
  UNION ALL SELECT 9,'A','shower supplies.',1 UNION ALL SELECT 9,'B','shower fixtures.',2 UNION ALL SELECT 9,'C','cat food.',3 UNION ALL SELECT 9,'D','moving boxes.',4
  UNION ALL SELECT 10,'A','Congratulations',1 UNION ALL SELECT 10,'B',"I'm sorry",2 UNION ALL SELECT 10,'C',"Don't stress",3 UNION ALL SELECT 10,'D','Good luck',4
  UNION ALL SELECT 11,'A',"hear how Tomas's interviews go.",1 UNION ALL SELECT 11,'B','help you look for a new apartment.',2 UNION ALL SELECT 11,'C','meet your new coworkers.',3 UNION ALL SELECT 11,'D','see the new place for myself.',4
) opt ON opt.qn = q.question_number
WHERE q.test_id = @tid AND q.question_number BETWEEN 1 AND 11;

-- ── Part 2: Reading to Apply a Diagram (Q12-19) ────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT @tid, d.qn, d.txt, 'multiple_choice_single', 2, d.qn
FROM (
  SELECT 12 qn, 'Blank (1) — retreat venue email' txt
  UNION ALL SELECT 13, 'Blank (2) — retreat venue email'
  UNION ALL SELECT 14, 'Blank (3) — retreat venue email'
  UNION ALL SELECT 15, 'Blank (4) — retreat venue email'
  UNION ALL SELECT 16, 'Blank (5) — retreat venue email'
  UNION ALL SELECT 17, 'Aisha and Marcus most likely...'
  UNION ALL SELECT 18, "The main purpose of Aisha's email is..."
  UNION ALL SELECT 19, "Aisha's tone in the email is best described as..."
) d;

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN (
  SELECT 12 qn, 'C' letter UNION ALL SELECT 13, 'B' UNION ALL SELECT 14, 'C' UNION ALL SELECT 15, 'D'
  UNION ALL SELECT 16, 'A' UNION ALL SELECT 17, 'D' UNION ALL SELECT 18, 'A' UNION ALL SELECT 19, 'B'
) ans ON ans.qn = q.question_number
JOIN (
  SELECT 12 qn,'A' lbl,'the most expensive option' txt,1 ord UNION ALL SELECT 12,'B','missing a kitchen',2 UNION ALL SELECT 12,'C','not an overnight stay',3 UNION ALL SELECT 12,'D','fully booked already',4
  UNION ALL SELECT 13,'A','no shuttle back to the lodge',1 UNION ALL SELECT 13,'B','no reliable phone signal',2 UNION ALL SELECT 13,'C','no space in the group vehicle',3 UNION ALL SELECT 13,'D','no refund for missed activities',4
  UNION ALL SELECT 14,'A',"they're both the cheapest options available",1 UNION ALL SELECT 14,'B','neither one publishes pricing upfront',2 UNION ALL SELECT 14,'C','Riverside might actually be too small for our group this year',3 UNION ALL SELECT 14,'D','Lakeside actually costs far more per person',4
  UNION ALL SELECT 15,'A','provide a private beach for guests',1 UNION ALL SELECT 15,'B','offer horseback riding',2 UNION ALL SELECT 15,'C','include unlimited meeting-room time',3 UNION ALL SELECT 15,'D','include meals as part of the stay',4
  UNION ALL SELECT 16,'A','least affordable',1 UNION ALL SELECT 16,'B','newest',2 UNION ALL SELECT 16,'C','least popular',3 UNION ALL SELECT 16,'D','hardest to book',4
  UNION ALL SELECT 17,'A','have never met.',1 UNION ALL SELECT 17,'B','are romantic partners.',2 UNION ALL SELECT 17,'C','are friends outside of work.',3 UNION ALL SELECT 17,'D','are colleagues.',4
  UNION ALL SELECT 18,'A','to walk through her reasoning and arrive at a recommendation before a deadline.',1 UNION ALL SELECT 18,'B','to cancel the retreat entirely.',2 UNION ALL SELECT 18,'C','to argue that City Loft is secretly the best choice.',3 UNION ALL SELECT 18,'D','to request a bigger budget for the retreat.',4
  UNION ALL SELECT 19,'A','undecided and anxious.',1 UNION ALL SELECT 19,'B','methodical, working through options before landing on one.',2 UNION ALL SELECT 19,'C','dismissive of the other options.',3 UNION ALL SELECT 19,'D','frustrated with the coordinator.',4
) opt ON opt.qn = q.question_number
WHERE q.test_id = @tid AND q.question_number BETWEEN 12 AND 19;

-- ── Part 3: Reading for Information — Octopuses (Q20-28, paragraph match A-E) ──
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT @tid, d.qn, d.txt, 'matching', 3, d.qn
FROM (
  SELECT 20 qn, 'Octopuses can change both the colour and texture of their skin.' txt
  UNION ALL SELECT 21, 'The star-sucker pygmy octopus can grow to more than four metres across.'
  UNION ALL SELECT 22, "An octopus's only rigid body part is its beak."
  UNION ALL SELECT 23, 'Octopuses sometimes carry found objects to use as makeshift hiding spots.'
  UNION ALL SELECT 24, 'An octopus can squeeze through narrow gaps because of its flexible, muscular arms.'
  UNION ALL SELECT 25, 'Octopus intelligence may have developed independently from the intelligence found in animals with backbones.'
  UNION ALL SELECT 26, 'Octopuses use their arms to sense both texture and taste.'
  UNION ALL SELECT 27, 'Every species of octopus lives exclusively in salt water.'
  UNION ALL SELECT 28, "An octopus's short lifespan makes some aspects of its behaviour puzzling to researchers."
) d;

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt,
       CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN (
  SELECT 20 qn, 'B' letter UNION ALL SELECT 21, 'E' UNION ALL SELECT 22, 'B' UNION ALL SELECT 23, 'C'
  UNION ALL SELECT 24, 'E' UNION ALL SELECT 25, 'D' UNION ALL SELECT 26, 'C' UNION ALL SELECT 27, 'A' UNION ALL SELECT 28, 'D'
) ans ON ans.qn = q.question_number
CROSS JOIN (
  SELECT 'A' lbl, 'Paragraph A' txt, 1 ord UNION ALL SELECT 'B','Paragraph B',2
  UNION ALL SELECT 'C','Paragraph C',3 UNION ALL SELECT 'D','Paragraph D',4
  UNION ALL SELECT 'E','Not given',5
) opt
WHERE q.test_id = @tid AND q.question_number BETWEEN 20 AND 28;

-- ── Part 4: Reading for Viewpoints (Q29-38) ────────────────────────────
INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT @tid, d.qn, d.txt, 'multiple_choice_single', 4, d.qn
FROM (
  SELECT 29 qn, 'The article is mainly about...' txt
  UNION ALL SELECT 30, 'Paragraph one provides...'
  UNION ALL SELECT 31, "Renata Price's concerns would most likely be shared by..."
  UNION ALL SELECT 32, "According to Dr. Osei, the rise in output per employee is best explained by..."
  UNION ALL SELECT 33, "The author's tone throughout the article is best described as..."
  UNION ALL SELECT 34, 'Blank (6) — visitor comment'
  UNION ALL SELECT 35, 'Blank (7) — visitor comment'
  UNION ALL SELECT 36, 'Blank (8) — visitor comment'
  UNION ALL SELECT 37, 'Blank (9) — visitor comment'
  UNION ALL SELECT 38, 'Blank (10) — visitor comment'
) d;

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN (
  SELECT 29 qn, 'A' letter UNION ALL SELECT 30, 'C' UNION ALL SELECT 31, 'B' UNION ALL SELECT 32, 'C'
  UNION ALL SELECT 33, 'D' UNION ALL SELECT 34, 'A' UNION ALL SELECT 35, 'C' UNION ALL SELECT 36, 'D'
  UNION ALL SELECT 37, 'C' UNION ALL SELECT 38, 'A'
) ans ON ans.qn = q.question_number
JOIN (
  SELECT 29 qn,'A' lbl,'whether the four-day work week is a realistic option for more businesses.' txt,1 ord UNION ALL SELECT 29,'B','why manufacturing companies should avoid schedule changes.',2 UNION ALL SELECT 29,'C',"a labour economist's year-long research project.",3 UNION ALL SELECT 29,'D',"how Jordan Kessler's results prove Dr. Osei's stress findings apply to every industry.",4
  UNION ALL SELECT 30,'A','a detailed case study.',1 UNION ALL SELECT 30,'B','a historical account of workplace scheduling.',2 UNION ALL SELECT 30,'C','a brief framing of the ongoing debate.',3 UNION ALL SELECT 30,'D',"a summary of Dr. Osei's findings.",4
  UNION ALL SELECT 31,'A','a marketing firm owner with a small, flexible team.',1 UNION ALL SELECT 31,'B','a hospital administrator responsible for round-the-clock staffing.',2 UNION ALL SELECT 31,'C','an economist studying self-reported stress levels.',3 UNION ALL SELECT 31,'D','an employee hoping for more scheduled time off.',4
  UNION ALL SELECT 32,'A','employees receiving higher pay for the same hours.',1 UNION ALL SELECT 32,'B','employees being given new equipment and tools.',2 UNION ALL SELECT 32,'C','staff becoming more efficient once their working hours are constrained.',3 UNION ALL SELECT 32,'D','employees being monitored more closely by managers.',4
  UNION ALL SELECT 33,'A','firmly in favour of the four-day work week.',1 UNION ALL SELECT 33,'B','dismissive of businesses that have tried it.',2 UNION ALL SELECT 33,'C',"mocking Renata Price's professional judgment.",3 UNION ALL SELECT 33,'D','cautiously balanced, presenting both promise and uncertainty.',4
  UNION ALL SELECT 34,'A','overreaches',1 UNION ALL SELECT 34,'B','is right',2 UNION ALL SELECT 34,'C','hesitates',3 UNION ALL SELECT 34,'D','apologizes',4
  UNION ALL SELECT 35,'A','struggled to keep up',1 UNION ALL SELECT 35,'B','reduced their client base',2 UNION ALL SELECT 35,'C','kept pace with their workload',3 UNION ALL SELECT 35,'D','hired additional staff',4
  UNION ALL SELECT 36,'A','staff shortages',1 UNION ALL SELECT 36,'B','client complaints',2 UNION ALL SELECT 36,'C','government regulation',3 UNION ALL SELECT 36,'D','deadline pressure',4
  UNION ALL SELECT 37,'A','Similarly,',1 UNION ALL SELECT 37,'B','Because of this,',2 UNION ALL SELECT 37,'C','That said,',3 UNION ALL SELECT 37,'D','In other words,',4
  UNION ALL SELECT 38,'A','Still,',1 UNION ALL SELECT 38,'B','Therefore,',2 UNION ALL SELECT 38,'C','Meanwhile,',3 UNION ALL SELECT 38,'D','In other words,',4
) opt ON opt.qn = q.question_number
WHERE q.test_id = @tid AND q.question_number BETWEEN 29 AND 38;

-- Verify (should show 38 questions, each with the right number of options,
-- and exactly one is_correct=1 per question):
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code='CELPIP_PT_R_001');
-- SELECT q.question_number, COUNT(*) opts, SUM(qo.is_correct) correct_count FROM questions q JOIN question_options qo ON qo.question_id=q.id WHERE q.test_id=(SELECT id FROM tests WHERE code='CELPIP_PT_R_001') GROUP BY q.question_number ORDER BY q.question_number;
