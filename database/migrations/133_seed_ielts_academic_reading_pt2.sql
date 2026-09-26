-- ============================================================
-- Migration 133 -- IELTS Academic Reading Practice Test 2 (Cambridge IELTS 17 Academic, Test 2, Reading), wired to "Reading Test 2"
-- (test_code = IELTS_PT_R_ACA_002, page = resources/practice_tests/ielts_reading_academic_002.php)
--
-- Source: documentation/test_bank/cambridge_ielts17_academic/test2.json (`reading`), transcribed from the book text, answers checked
-- against the printed key (p.122). 3 passages, 40 questions, 60 minutes. The page draws the passages and questions itself; this migration
-- seeds the answer key (questions + question_correct_answers / question_options), which loadTestAnswers() reads by test_code + number.
--
-- It also points the "Reading Test 2" piece of IELTS Academic 2-Month and 3-Month at the page (lesson_parts.file_path), like 128 did for
-- Reading Test 1, so the class shows a real test instead of Coming Soon.
-- Idempotent. Run AFTER 126 and 128. Written portable for older MySQL/MariaDB.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_ACA_002', 'IELTS Academic Reading — Practice Test 2',
       'A full Academic Reading test: 3 passages (The Dead Sea Scrolls; A second attempt at domesticating the tomato; Insight or evolution?), 40 questions, 60 minutes.',
       'IELTS_Academic', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_ACA_002');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'The Dead Sea Scrolls - Discovery (Qumran, 1946/7): three Bedouin shepherds in their teens were near an opening on the side of a cliff; heard a noise of breaking when one teenager threw a ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'teenagers went into the ___ and found a number of containers' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'containers made of ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'The scrolls: thought to have been written by group of people known as the ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'written mainly in the ___ language' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'The Bedouin teenagers who found the scrolls were disappointed by how little money they received for them.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'There is agreement among academics about the origin of the Dead Sea Scrolls.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'Most of the books of the Bible written on the scrolls are incomplete.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'The information on the Copper Scroll is written in an unusual way.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'Mar Samuel was given some of the scrolls as a gift.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'In the early 1950s, a number of educational establishments in the US were keen to buy scrolls from Mar Samuel.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'The scroll that was pieced together in 2017 contains information about annual occasions in the Qumran area 2,000 years ago.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'Academics at the University of Haifa are currently researching how to decipher the final scroll.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'a reference to a type of tomato that can resist a dangerous infection' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'an explanation of how problems can arise from focusing only on a certain type of tomato plant' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'a number of examples of plants that are not cultivated at present but could be useful as food sources' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'a comparison between the early domestication of the tomato and more recent research' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'a personal reaction to the flavour of a tomato that has been genetically edited' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Domestication of certain plants could allow them to adapt to future environmental challenges.' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'The idea of growing and eating unusual plants may not be accepted on a large scale.' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'It is not advisable for the future direction of certain research to be made public.' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'Present efforts to domesticate one wild fruit are limited by the costs involved.' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'Humans only make use of a small proportion of the plant food available on Earth.' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'An undesirable trait such as loss of ___ may be caused by a mutation in a tomato gene.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'By modifying one gene in a tomato plant, researchers made the tomato three times its original ___.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'A type of tomato which was not badly affected by ___, and was rich in vitamin C, was produced by a team of researchers in China.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'The purpose of the first paragraph is to' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'What are the writers doing in the second paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'In the third paragraph, what do the writers suggest about Darwin and Einstein?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'John Nicholson is an example of a person whose idea' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'What is the key point of interest about the ''acey-deucy'' stirrup placement?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'Acknowledging people such as Plato or da Vinci as geniuses will help us understand the process by which great minds create new ideas.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'The Law of Effect was discovered at a time when psychologists were seeking a scientific reason why creativity occurs.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'The Law of Effect states that no planning is involved in the behaviour of organisms.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'The Law of Effect sets out clear explanations about the sources of new ideas and behaviours.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'Many scientists are now turning away from the notion of intelligent design and genius.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'The origins of creative behaviour - The traditional view of scientific discovery is that breakthroughs happen when a single great mind has sudden ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'In some cases, this process involves ___, such as Nicholson''s theory about proto-elements.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'There is also often an element of ___, for example, the coincidence of ideas that led to the invention of the Post-It note.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'With both the Law of Natural Selection and the Law of Effect, there may be no clear ___ involved, but merely a process of variation and selection.' txt, 'summary_completion' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_ACA_002'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'rock' ans, 0 alt
  UNION ALL SELECT 2 qn, 'cave' ans, 0 alt
  UNION ALL SELECT 3 qn, 'clay' ans, 0 alt
  UNION ALL SELECT 4 qn, 'essenes' ans, 0 alt
  UNION ALL SELECT 5 qn, 'hebrew' ans, 0 alt
  UNION ALL SELECT 6 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 7 qn, 'false' ans, 0 alt
  UNION ALL SELECT 8 qn, 'true' ans, 0 alt
  UNION ALL SELECT 9 qn, 'true' ans, 0 alt
  UNION ALL SELECT 10 qn, 'false' ans, 0 alt
  UNION ALL SELECT 11 qn, 'false' ans, 0 alt
  UNION ALL SELECT 12 qn, 'true' ans, 0 alt
  UNION ALL SELECT 13 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 14 qn, 'c' ans, 0 alt
  UNION ALL SELECT 15 qn, 'b' ans, 0 alt
  UNION ALL SELECT 16 qn, 'e' ans, 0 alt
  UNION ALL SELECT 17 qn, 'a' ans, 0 alt
  UNION ALL SELECT 18 qn, 'c' ans, 0 alt
  UNION ALL SELECT 19 qn, 'b' ans, 0 alt
  UNION ALL SELECT 20 qn, 'd' ans, 0 alt
  UNION ALL SELECT 21 qn, 'a' ans, 0 alt
  UNION ALL SELECT 22 qn, 'c' ans, 0 alt
  UNION ALL SELECT 23 qn, 'a' ans, 0 alt
  UNION ALL SELECT 24 qn, 'flavour' ans, 0 alt
  UNION ALL SELECT 24 qn, 'flavor' ans, 1 alt
  UNION ALL SELECT 25 qn, 'size' ans, 0 alt
  UNION ALL SELECT 26 qn, 'salt' ans, 0 alt
  UNION ALL SELECT 32 qn, 'no' ans, 0 alt
  UNION ALL SELECT 33 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 34 qn, 'yes' ans, 0 alt
  UNION ALL SELECT 35 qn, 'no' ans, 0 alt
  UNION ALL SELECT 36 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 37 qn, 'f' ans, 0 alt
  UNION ALL SELECT 38 qn, 'd' ans, 0 alt
  UNION ALL SELECT 39 qn, 'e' ans, 0 alt
  UNION ALL SELECT 40 qn, 'b' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_ACA_002' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 27 qn, 'D' letter
  UNION ALL SELECT 28 qn, 'A' letter
  UNION ALL SELECT 29 qn, 'A' letter
  UNION ALL SELECT 30 qn, 'C' letter
  UNION ALL SELECT 31 qn, 'A' letter
) ans ON ans.qn = q.question_number
JOIN (
  SELECT 27 qn, 'A' lbl, 'defend particular ideas.' txt, 1 ord
  UNION ALL SELECT 27 qn, 'B' lbl, 'compare certain beliefs.' txt, 2 ord
  UNION ALL SELECT 27 qn, 'C' lbl, 'disprove a widely held view.' txt, 3 ord
  UNION ALL SELECT 27 qn, 'D' lbl, 'outline a common assumption.' txt, 4 ord
  UNION ALL SELECT 28 qn, 'A' lbl, 'criticising an opinion' txt, 1 ord
  UNION ALL SELECT 28 qn, 'B' lbl, 'justifying a standpoint' txt, 2 ord
  UNION ALL SELECT 28 qn, 'C' lbl, 'explaining an approach' txt, 3 ord
  UNION ALL SELECT 28 qn, 'D' lbl, 'supporting an argument' txt, 4 ord
  UNION ALL SELECT 29 qn, 'A' lbl, 'They represent an exception to a general rule.' txt, 1 ord
  UNION ALL SELECT 29 qn, 'B' lbl, 'Their way of working has been misunderstood.' txt, 2 ord
  UNION ALL SELECT 29 qn, 'C' lbl, 'They are an ideal which others should aspire to.' txt, 3 ord
  UNION ALL SELECT 29 qn, 'D' lbl, 'Their achievements deserve greater recognition.' txt, 4 ord
  UNION ALL SELECT 30 qn, 'A' lbl, 'established his reputation as an influential scientist.' txt, 1 ord
  UNION ALL SELECT 30 qn, 'B' lbl, 'was only fully understood at a later point in history.' txt, 2 ord
  UNION ALL SELECT 30 qn, 'C' lbl, 'laid the foundations for someone else''s breakthrough.' txt, 3 ord
  UNION ALL SELECT 30 qn, 'D' lbl, 'initially met with scepticism from the scientific community.' txt, 4 ord
  UNION ALL SELECT 31 qn, 'A' lbl, 'the simple reason why it was invented' txt, 1 ord
  UNION ALL SELECT 31 qn, 'B' lbl, 'the enthusiasm with which it was adopted' txt, 2 ord
  UNION ALL SELECT 31 qn, 'C' lbl, 'the research that went into its development' txt, 3 ord
  UNION ALL SELECT 31 qn, 'D' lbl, 'the cleverness of the person who first used it' txt, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_ACA_002'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

-- The page for the "Reading Test 2" piece of IELTS Academic 2-Month and 3-Month (found by course folder + piece title, never by id)
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_reading_academic_002.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Reading Test 2'
   AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;

-- Verify: 40 questions, 40 answers keyed, and the two pieces now have a page
SELECT COUNT(*) AS questions FROM questions q JOIN tests t ON t.id = q.test_id WHERE t.code = 'IELTS_PT_R_ACA_002';
SELECT c.folder_name, lp.title, lp.file_path, lp.status FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Reading Test 2';
