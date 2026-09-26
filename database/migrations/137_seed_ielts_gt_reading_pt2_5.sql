-- ============================================================
-- Migration 137 -- IELTS General Training Reading Practice Tests 2-5 (Cambridge IELTS 15 General Training, Tests 1-4)
-- (test codes IELTS_PT_R_002 .. IELTS_PT_R_005; pages resources/practice_tests/ielts_reading_002 .. 005 .php, which were empty placeholders;
--  Practice Test 1, IELTS_PT_R_001, is unchanged)
--
-- Generated from documentation/test_bank/cambridge_ielts15_gt/test1..4.json (the answers are the ones in the bank). Seeds each test's answer key.
-- Idempotent. Run AFTER 126. Written portable for older MySQL/MariaDB. Wiring into the IELTS General course is in migration 138.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_002', 'IELTS General Training Reading – Practice Test 2',
       'A full General Training Reading test (Cambridge IELTS 15 General Training, Test 1): 3 sections, 40 questions, 60 minutes.', 'IELTS', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_002');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'You will receive a card telling you if an item has been left with a neighbour.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'It may be quicker to get a refund than a replacement for a non-delivered item.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'You are entitled to a refund if the item fails to arrive by a certain time.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'There is a time limit when using the ''chargeback'' scheme for a debit card payment.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'You can use the ''chargeback'' scheme for a credit card payment of more than £100.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'PayPal''s online resolution centre has a good reputation for efficiency.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'The handles at the side are hard to use.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'It cooks brown rice without making a mess.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'It automatically switches setting to keep the rice warm when cooked.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'It''s difficult to get the removable top really clean.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'A selection of recipes is provided with the cooker.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'It has a handle at the top for carrying the cooker safely.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'The outside of the cooker doesn''t get too hot.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'You can put the pot in the dishwasher.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 15 qn, 'Investigations show that: over half of falls are from less than ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'the majority of falls occur on ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Controls: ___ the hazard at the planning stage before the work begins if possible' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'prevent a fall by using edge protection, e.g. scaffolding or ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'reduce the likelihood of injury, e.g. by using ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Ladders should only be used for ___ which does not take a long time' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'training should be provided in their ___ and use' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'regular ___ of ladders is required' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'The maximum amount of money a woman can get each week is £___' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'Being ___ for a time does not necessarily mean that a woman will not be eligible for Maternity Allowance.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'In order to claim, a woman must send a ___ or a Small Earnings Exemption Certificate as evidence of her income.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'In order to claim, a woman may need to provide a ___ as evidence of the due date.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'Payment may be affected by differences in someone''s ___, such as a return to work, and the local Jobcentre Plus must be informed.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 28 qn, 'The writer suggests that Marshall''s discovery came at a good time for the US because' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'What was the reaction in 1848 to the news of the discovery of gold?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'What was the result of thousands of people moving to California?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'What does the writer say about using pans and rockers to find gold?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'a reference to ways of making money in California other than mining for gold' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'a suggestion that the gold that was found did not often compensate for the hard work undertaken' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'a mention of an individual who convinced many of the existence of gold in California' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'details of the pre-Gold Rush population of California' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'a contrast between shrinking revenue and increasing population' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'The most basic method used by many miners began with digging some ___ out of a river and hoping it might contain gold.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'if the miners were very lucky, there might even be some ___ too.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'Larger stones stuck in the ___, while gold dropped to the bottom.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'a process was introduced involving ___ to ensure no gold was washed out in the water.' txt, 'summary_completion' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_002'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 2 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 3 qn, 'true' ans, 0 alt
  UNION ALL SELECT 4 qn, 'true' ans, 0 alt
  UNION ALL SELECT 5 qn, 'false' ans, 0 alt
  UNION ALL SELECT 6 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 7 qn, 'd' ans, 0 alt
  UNION ALL SELECT 8 qn, 'e' ans, 0 alt
  UNION ALL SELECT 9 qn, 'b' ans, 0 alt
  UNION ALL SELECT 10 qn, 'a' ans, 0 alt
  UNION ALL SELECT 11 qn, 'd' ans, 0 alt
  UNION ALL SELECT 12 qn, 'b' ans, 0 alt
  UNION ALL SELECT 13 qn, 'e' ans, 0 alt
  UNION ALL SELECT 14 qn, 'c' ans, 0 alt
  UNION ALL SELECT 15 qn, '3 metres' ans, 0 alt
  UNION ALL SELECT 15 qn, 'three metres' ans, 1 alt
  UNION ALL SELECT 15 qn, '3 meters' ans, 1 alt
  UNION ALL SELECT 15 qn, 'three meters' ans, 1 alt
  UNION ALL SELECT 16 qn, 'residential building sites' ans, 0 alt
  UNION ALL SELECT 17 qn, 'eliminate' ans, 0 alt
  UNION ALL SELECT 18 qn, 'temporary work platforms' ans, 0 alt
  UNION ALL SELECT 18 qn, 'work platforms' ans, 1 alt
  UNION ALL SELECT 19 qn, 'safety nets' ans, 0 alt
  UNION ALL SELECT 20 qn, 'maintenance work' ans, 0 alt
  UNION ALL SELECT 20 qn, 'maintenance' ans, 1 alt
  UNION ALL SELECT 21 qn, 'selection' ans, 0 alt
  UNION ALL SELECT 22 qn, 'inspection' ans, 0 alt
  UNION ALL SELECT 23 qn, '140.98' ans, 0 alt
  UNION ALL SELECT 24 qn, 'unemployed' ans, 0 alt
  UNION ALL SELECT 25 qn, 'payslip' ans, 0 alt
  UNION ALL SELECT 26 qn, 'doctor''s letter' ans, 0 alt
  UNION ALL SELECT 27 qn, 'circumstances' ans, 0 alt
  UNION ALL SELECT 32 qn, 'd' ans, 0 alt
  UNION ALL SELECT 33 qn, 'f' ans, 0 alt
  UNION ALL SELECT 34 qn, 'b' ans, 0 alt
  UNION ALL SELECT 35 qn, 'a' ans, 0 alt
  UNION ALL SELECT 36 qn, 'g' ans, 0 alt
  UNION ALL SELECT 37 qn, 'gravel' ans, 0 alt
  UNION ALL SELECT 38 qn, 'nuggets' ans, 0 alt
  UNION ALL SELECT 39 qn, 'sieve' ans, 0 alt
  UNION ALL SELECT 40 qn, 'mercury' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_002' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 28 qn, 'A' lbl, 'the Mexican-American War was ending so there were men needing work.' txt, 0 cor, 1 ord
  UNION ALL SELECT 28 qn, 'B' lbl, 'his expertise in water power would be useful in gold mining.' txt, 0 cor, 2 ord
  UNION ALL SELECT 28 qn, 'C' lbl, 'the population of California had already begun to increase rapidly.' txt, 0 cor, 3 ord
  UNION ALL SELECT 28 qn, 'D' lbl, 'the region was about to come under the control of the US.' txt, 1 cor, 4 ord
  UNION ALL SELECT 29 qn, 'A' lbl, 'The press played a large part in convincing the public of the riches available.' txt, 0 cor, 1 ord
  UNION ALL SELECT 29 qn, 'B' lbl, 'Many men in San Francisco left immediately to check it out for themselves.' txt, 0 cor, 2 ord
  UNION ALL SELECT 29 qn, 'C' lbl, 'People needed to see physical evidence before they took it seriously.' txt, 1 cor, 3 ord
  UNION ALL SELECT 29 qn, 'D' lbl, 'Men in other mines in the US were among the first to respond to it.' txt, 0 cor, 4 ord
  UNION ALL SELECT 30 qn, 'A' lbl, 'San Francisco could not cope with the influx of people from around the world.' txt, 0 cor, 1 ord
  UNION ALL SELECT 30 qn, 'B' lbl, 'Many miners got more money than they could ever have earned at home.' txt, 0 cor, 2 ord
  UNION ALL SELECT 30 qn, 'C' lbl, 'Some of those who stayed behind had to take on unexpected roles.' txt, 1 cor, 3 ord
  UNION ALL SELECT 30 qn, 'D' lbl, 'New towns were established which became good places to live.' txt, 0 cor, 4 ord
  UNION ALL SELECT 31 qn, 'A' lbl, 'Both methods required the addition of mercury.' txt, 0 cor, 1 ord
  UNION ALL SELECT 31 qn, 'B' lbl, 'A rocker needed more than one miner to operate it.' txt, 0 cor, 2 ord
  UNION ALL SELECT 31 qn, 'C' lbl, 'Pans were the best system for novice miners to use.' txt, 0 cor, 3 ord
  UNION ALL SELECT 31 qn, 'D' lbl, 'Miners had to find a way round a design fault in one system.' txt, 1 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_002'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_003', 'IELTS General Training Reading – Practice Test 3',
       'A full General Training Reading test (Cambridge IELTS 15 General Training, Test 2): 3 sections, 40 questions, 60 minutes.', 'IELTS', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_003');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'There is an extra charge for locks and keys.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'It is possible to arrange to share a storage unit with someone else.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'You can pick up your property from the storage unit during the night-time.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'You can drive your vehicle right next to your storage unit.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'Students'' possessions can only be stored during vacation periods.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'The storage company will collect and deliver students'' property.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'There are exhibits related to the history of agriculture in the region.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'Equipment for putting out fires used to be kept in this building.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'You can find information on the rise of one type of transport.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'There are things to see both inside and outside.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'It is possible to obtain copies of old pictures and documents.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'On certain days you can see an original work by a writer of fiction.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'Someone who was interested in environmental matters lived here for a time.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'This museum has an exhibit related to a heroic achievement.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 15 qn, 'Biohazard - Examples: ___, mould, bacteria, algae' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'Confined spaces - high concentrations of harmful airborne contaminants e.g. carbon monoxide - Risks: ___' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Confined spaces - water - Risks: ___' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'Electricity - use insulated ___ and appropriate equipment' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Electricity - ensure equipment has ___ on to show it is safe' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Electricity - make sure electricity has been ___' txt, 'table_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'provide them with clear ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'initially, have a ___ of progress each day' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'make sure a ___ is accessible to give details of colleague locations' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'use a program to encourage different types of ___ between workers' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'less ___ from colleagues' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'increase in ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'greater success for the company with staff recruitment and ___' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 28 qn, 'Section A' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'Section B' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'Section C' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'Section D' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'Section E' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'Section F' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'What does the writer suggest about the flatback turtle?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'Williams-Guillen says that the poaching of sea turtle eggs in Nicaragua' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'In Section E, Williams-Guillen says that one way to encourage poachers to take the fake eggs is to' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'It is planned to use a large number of fake eggs at the beginning because' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'Unlike a bird''s egg, a turtle''s egg has a shell which is ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'Lauren Wilde has studied eggs from Californian turtles that live on ___' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'A GPS device will then be placed inside a ___ in the fake shell.' txt, 'summary_completion' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_003'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'false' ans, 0 alt
  UNION ALL SELECT 2 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 3 qn, 'true' ans, 0 alt
  UNION ALL SELECT 4 qn, 'true' ans, 0 alt
  UNION ALL SELECT 5 qn, 'false' ans, 0 alt
  UNION ALL SELECT 6 qn, 'false' ans, 0 alt
  UNION ALL SELECT 7 qn, 'c' ans, 0 alt
  UNION ALL SELECT 8 qn, 'a' ans, 0 alt
  UNION ALL SELECT 9 qn, 'c' ans, 0 alt
  UNION ALL SELECT 10 qn, 'b' ans, 0 alt
  UNION ALL SELECT 11 qn, 'c' ans, 0 alt
  UNION ALL SELECT 12 qn, 'e' ans, 0 alt
  UNION ALL SELECT 13 qn, 'b' ans, 0 alt
  UNION ALL SELECT 14 qn, 'd' ans, 0 alt
  UNION ALL SELECT 15 qn, 'sewage' ans, 0 alt
  UNION ALL SELECT 16 qn, 'poisoning' ans, 0 alt
  UNION ALL SELECT 17 qn, 'drowning' ans, 0 alt
  UNION ALL SELECT 18 qn, 'gloves' ans, 0 alt
  UNION ALL SELECT 19 qn, 'tags' ans, 0 alt
  UNION ALL SELECT 20 qn, 'disconnected' ans, 0 alt
  UNION ALL SELECT 21 qn, 'objectives' ans, 0 alt
  UNION ALL SELECT 22 qn, 'review' ans, 0 alt
  UNION ALL SELECT 23 qn, 'calendar' ans, 0 alt
  UNION ALL SELECT 24 qn, 'collaboration' ans, 0 alt
  UNION ALL SELECT 25 qn, 'distraction' ans, 0 alt
  UNION ALL SELECT 26 qn, 'creativity' ans, 0 alt
  UNION ALL SELECT 27 qn, 'retention' ans, 0 alt
  UNION ALL SELECT 28 qn, 'iv' ans, 0 alt
  UNION ALL SELECT 29 qn, 'vi' ans, 0 alt
  UNION ALL SELECT 30 qn, 'vii' ans, 0 alt
  UNION ALL SELECT 31 qn, 'i' ans, 0 alt
  UNION ALL SELECT 32 qn, 'v' ans, 0 alt
  UNION ALL SELECT 33 qn, 'ii' ans, 0 alt
  UNION ALL SELECT 38 qn, 'flexible' ans, 0 alt
  UNION ALL SELECT 39 qn, 'land' ans, 0 alt
  UNION ALL SELECT 40 qn, 'ball' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_003' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 34 qn, 'A' lbl, 'It could be as severely threatened as other turtles.' txt, 1 cor, 1 ord
  UNION ALL SELECT 34 qn, 'B' lbl, 'It has been neglected by scientists in the past.' txt, 0 cor, 2 ord
  UNION ALL SELECT 34 qn, 'C' lbl, 'It is in less danger than some other species.' txt, 0 cor, 3 ord
  UNION ALL SELECT 34 qn, 'D' lbl, 'It should be removed from the IUCN Red List.' txt, 0 cor, 4 ord
  UNION ALL SELECT 35 qn, 'A' lbl, 'is mainly carried out by local people.' txt, 0 cor, 1 ord
  UNION ALL SELECT 35 qn, 'B' lbl, 'may be encouraged by the presence of tourists.' txt, 0 cor, 2 ord
  UNION ALL SELECT 35 qn, 'C' lbl, 'sometimes has a highly organised structure.' txt, 1 cor, 3 ord
  UNION ALL SELECT 35 qn, 'D' lbl, 'can only be controlled by the use of armed guards.' txt, 0 cor, 4 ord
  UNION ALL SELECT 36 qn, 'A' lbl, 'make fake nests and put the eggs into them.' txt, 0 cor, 1 ord
  UNION ALL SELECT 36 qn, 'B' lbl, 'put them in nests with just a few real eggs.' txt, 0 cor, 2 ord
  UNION ALL SELECT 36 qn, 'C' lbl, 'distract the poachers after the fake eggs have been put in the nests.' txt, 0 cor, 3 ord
  UNION ALL SELECT 36 qn, 'D' lbl, 'put them in nests that the poachers have started to dig up.' txt, 1 cor, 4 ord
  UNION ALL SELECT 37 qn, 'A' lbl, 'some of the fake eggs may be missed by the poachers.' txt, 0 cor, 1 ord
  UNION ALL SELECT 37 qn, 'B' lbl, 'it may not be possible to continue the project indefinitely.' txt, 1 cor, 2 ord
  UNION ALL SELECT 37 qn, 'C' lbl, 'some eggs may be hidden in the sand.' txt, 0 cor, 3 ord
  UNION ALL SELECT 37 qn, 'D' lbl, 'it may not be feasible to fund long-term research.' txt, 0 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_003'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_004', 'IELTS General Training Reading – Practice Test 4',
       'A full General Training Reading test (Cambridge IELTS 15 General Training, Test 3): 3 sections, 40 questions, 60 minutes.', 'IELTS', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_004');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Participants are required to create a new item of clothing for the Young Fashion Designer UK competition.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'Participants must send information about the thoughts that led to the item they are entering for the competition.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'The shortlist will consist of a fixed number of finalists.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'Finalists can choose how to present their work to the judges on their stand.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'It is strongly recommended that finalists support their entry with additional photographs.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'Questions that the students ask the judges may count towards the final decisions.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'Extra prizes may be awarded depending on the standard of the entries submitted.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'This keyboard may not suit users who prefer the keys to be almost silent.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'This keyboard is easily portable because it can be made to fit into a small space.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'This keyboard includes a special place to put small devices.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'This keyboard is designed to prevent injury to those who spend a lot of time on the computer.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'This keyboard offers good value for money.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'This keyboard is primarily aimed at people who use their computer for entertainment.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'It shouldn''t take long for users to get used to the shape of the keys on this keyboard.' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 15 qn, 'In a small business it is easy to become ___ with colleagues and other departments.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'You may find you have ___ you were not aware of.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Finding that your work is ___ will make you enjoy doing it.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'Other people are likely to realise that you have ___.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Opportunities for ___ will come sooner than in a larger business.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'You can benefit from a small company being more ___ than a large one.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'the emotions that new employees are likely to experience at first' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'a warning to be patient at first' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'how colleagues might react to certain behaviour' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'travelling to your new workplace before you start working there' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'an example of observing an activity carried out within an organisation' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'some things that the organisation should arrange for when you begin' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'a division of jobs within an organisation into two categories' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 28 qn, 'The writer discusses marathon runners and barnacle geese to introduce the idea that' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'The writer says that human muscles' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'The writer says that in order to survive, early humans developed the ability to' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'In biological terms, when an animal is physically fit, its body changes, becoming more powerful and ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'For bears, this change may be initially caused by colder weather or a lack of ___' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'which during ___ causes certain compounds to be released into their' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 34 qn, '___ and to travel around the body.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'In the case of barnacle geese, the change may be due to a variation in ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'One belief about how animals stay fit is possibly untrue.' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'It may not be possible to train all animals to improve their speed.' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'One type of bird has demonstrated fitness when exposed to a stimulus in experimental conditions.' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'Human energy use developed in a different way from that of animals.' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'One type of bird may develop more strength when the weather becomes warmer or cooler.' txt, 'matching' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_004'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'false' ans, 0 alt
  UNION ALL SELECT 2 qn, 'true' ans, 0 alt
  UNION ALL SELECT 3 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 4 qn, 'true' ans, 0 alt
  UNION ALL SELECT 5 qn, 'false' ans, 0 alt
  UNION ALL SELECT 6 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 7 qn, 'true' ans, 0 alt
  UNION ALL SELECT 8 qn, 'b' ans, 0 alt
  UNION ALL SELECT 9 qn, 'e' ans, 0 alt
  UNION ALL SELECT 10 qn, 'c' ans, 0 alt
  UNION ALL SELECT 11 qn, 'd' ans, 0 alt
  UNION ALL SELECT 12 qn, 'a' ans, 0 alt
  UNION ALL SELECT 13 qn, 'f' ans, 0 alt
  UNION ALL SELECT 14 qn, 'c' ans, 0 alt
  UNION ALL SELECT 15 qn, 'familiar' ans, 0 alt
  UNION ALL SELECT 16 qn, 'abilities' ans, 0 alt
  UNION ALL SELECT 17 qn, 'stimulating' ans, 0 alt
  UNION ALL SELECT 18 qn, 'potential' ans, 0 alt
  UNION ALL SELECT 19 qn, 'promotion' ans, 0 alt
  UNION ALL SELECT 20 qn, 'flexible' ans, 0 alt
  UNION ALL SELECT 21 qn, 'c' ans, 0 alt
  UNION ALL SELECT 22 qn, 'f' ans, 0 alt
  UNION ALL SELECT 23 qn, 'c' ans, 0 alt
  UNION ALL SELECT 24 qn, 'a' ans, 0 alt
  UNION ALL SELECT 25 qn, 'e' ans, 0 alt
  UNION ALL SELECT 26 qn, 'b' ans, 0 alt
  UNION ALL SELECT 27 qn, 'd' ans, 0 alt
  UNION ALL SELECT 31 qn, 'efficient' ans, 0 alt
  UNION ALL SELECT 32 qn, 'food' ans, 0 alt
  UNION ALL SELECT 33 qn, 'hibernation' ans, 0 alt
  UNION ALL SELECT 34 qn, 'blood' ans, 0 alt
  UNION ALL SELECT 35 qn, 'temperature' ans, 0 alt
  UNION ALL SELECT 36 qn, 'a' ans, 0 alt
  UNION ALL SELECT 37 qn, 'd' ans, 0 alt
  UNION ALL SELECT 38 qn, 'b' ans, 0 alt
  UNION ALL SELECT 39 qn, 'c' ans, 0 alt
  UNION ALL SELECT 40 qn, 'a' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_004' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 28 qn, 'A' lbl, 'marathon runners may be using inefficient training methods.' txt, 0 cor, 1 ord
  UNION ALL SELECT 28 qn, 'B' lbl, 'the role of diet in achieving fitness has been underestimated.' txt, 0 cor, 2 ord
  UNION ALL SELECT 28 qn, 'C' lbl, 'barnacle geese spend much longer preparing to face a challenge.' txt, 0 cor, 3 ord
  UNION ALL SELECT 28 qn, 'D' lbl, 'serious training is not always necessary for physical achievement.' txt, 1 cor, 4 ord
  UNION ALL SELECT 29 qn, 'A' lbl, 'use up a lot of energy even when resting.' txt, 1 cor, 1 ord
  UNION ALL SELECT 29 qn, 'B' lbl, 'are heavier than other types of body tissue.' txt, 0 cor, 2 ord
  UNION ALL SELECT 29 qn, 'C' lbl, 'were more efficiently used by our ancestors.' txt, 0 cor, 3 ord
  UNION ALL SELECT 29 qn, 'D' lbl, 'have become weaker than they were in the past.' txt, 0 cor, 4 ord
  UNION ALL SELECT 30 qn, 'A' lbl, 'hide from their prey.' txt, 0 cor, 1 ord
  UNION ALL SELECT 30 qn, 'B' lbl, 'run long distances.' txt, 0 cor, 2 ord
  UNION ALL SELECT 30 qn, 'C' lbl, 'adapt their speeds to different situations.' txt, 1 cor, 3 ord
  UNION ALL SELECT 30 qn, 'D' lbl, 'predict different types of animal movements.' txt, 0 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_004'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_005', 'IELTS General Training Reading – Practice Test 5',
       'A full General Training Reading test (Cambridge IELTS 15 General Training, Test 4): 3 sections, 40 questions, 60 minutes.', 'IELTS', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_005');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'what still needs to be done' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'the original suggestion for creating the path' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'a reason why the path opened early' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'people who no longer need to get to the park by car' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'the route of the path' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'the length of the path' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'who paid for the path' txt, 'matching' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'The college has introduced new courses since it opened.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'The college provides training for work in the film industry.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'Students have the chance to work with relevant professionals.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'Many more people apply to study at the college than are accepted.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'Theatre 500 was created by students.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'The new building and the council building were designed by the same architects.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'Local groups will be charged for using college premises.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 15 qn, 'Bringing a personal ___ to work will make the place feel more homely.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'It is important to check the position of all ___ before use to avoid pulling any muscles.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Leaving the office in the middle of the day may help to raise ___ later on.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'It is advisable to avoid checking a ___ during breaks.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Getting involved in ___ at work may have negative results.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Having a few ___ available can help people concentrate better at work.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'First step: examine past successes and any ___ that would help gain promotion' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'how best to use your high level of ___ in future' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'or how much extra ___ you already bring to the company' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'find out which ones will be members of the ___ who decide on the promotion' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'consider how much they are aware of your ___ for the future' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'participating in the ___ of events for customers' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'take any ___ that fill in gaps in knowledge' txt, 'form_note_completion' qtype, 2 pn
  UNION ALL SELECT 28 qn, 'Wolves live in packs and it is clear that there are a number of ___ concerning their behaviour.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'Some observers believe they exhibit a sense of ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'They act as if they are ___ to the juniors' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'and even permit some gentle ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'it bends down begging for ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'coyotes' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'domestic dogs' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'elephants' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'Diana monkeys' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'rats' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'What view is expressed by Professor de Waal?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'Why does Professor Bekoff mention the experiment on Diana monkeys?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'What does the writer find most surprising about chimpanzees?' txt, 'multiple_choice_single' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_005'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'e' ans, 0 alt
  UNION ALL SELECT 2 qn, 'c' ans, 0 alt
  UNION ALL SELECT 3 qn, 'b' ans, 0 alt
  UNION ALL SELECT 4 qn, 'd' ans, 0 alt
  UNION ALL SELECT 5 qn, 'a' ans, 0 alt
  UNION ALL SELECT 6 qn, 'c' ans, 0 alt
  UNION ALL SELECT 7 qn, 'b' ans, 0 alt
  UNION ALL SELECT 8 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 9 qn, 'true' ans, 0 alt
  UNION ALL SELECT 10 qn, 'true' ans, 0 alt
  UNION ALL SELECT 11 qn, 'true' ans, 0 alt
  UNION ALL SELECT 12 qn, 'false' ans, 0 alt
  UNION ALL SELECT 13 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 14 qn, 'false' ans, 0 alt
  UNION ALL SELECT 15 qn, 'photo' ans, 0 alt
  UNION ALL SELECT 16 qn, 'screens' ans, 0 alt
  UNION ALL SELECT 17 qn, 'productivity' ans, 0 alt
  UNION ALL SELECT 18 qn, 'mobile' ans, 0 alt
  UNION ALL SELECT 19 qn, 'gossip' ans, 0 alt
  UNION ALL SELECT 20 qn, 'snacks' ans, 0 alt
  UNION ALL SELECT 21 qn, 'skills' ans, 0 alt
  UNION ALL SELECT 22 qn, 'commitment' ans, 0 alt
  UNION ALL SELECT 23 qn, 'value' ans, 0 alt
  UNION ALL SELECT 24 qn, 'panel' ans, 0 alt
  UNION ALL SELECT 25 qn, 'potential' ans, 0 alt
  UNION ALL SELECT 26 qn, 'organisation' ans, 0 alt
  UNION ALL SELECT 26 qn, 'organization' ans, 1 alt
  UNION ALL SELECT 27 qn, 'courses' ans, 0 alt
  UNION ALL SELECT 28 qn, 'rules' ans, 0 alt
  UNION ALL SELECT 29 qn, 'fairness' ans, 0 alt
  UNION ALL SELECT 30 qn, 'submissive' ans, 0 alt
  UNION ALL SELECT 31 qn, 'biting' ans, 0 alt
  UNION ALL SELECT 32 qn, 'forgiveness' ans, 0 alt
  UNION ALL SELECT 33 qn, 'e' ans, 0 alt
  UNION ALL SELECT 34 qn, 'c' ans, 0 alt
  UNION ALL SELECT 35 qn, 'g' ans, 0 alt
  UNION ALL SELECT 36 qn, 'd' ans, 0 alt
  UNION ALL SELECT 37 qn, 'f' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_005' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 38 qn, 'A' lbl, 'Apes have advanced ideas about the difference between good and evil.' txt, 0 cor, 1 ord
  UNION ALL SELECT 38 qn, 'B' lbl, 'The social manners of some animals prove that they are highly moral.' txt, 0 cor, 2 ord
  UNION ALL SELECT 38 qn, 'C' lbl, 'Some human moral beliefs developed from our animal ancestors.' txt, 1 cor, 3 ord
  UNION ALL SELECT 38 qn, 'D' lbl, 'The desire to live in peace with others is a purely human quality.' txt, 0 cor, 4 ord
  UNION ALL SELECT 39 qn, 'A' lbl, 'It shows that this species of monkey is not very easy to train.' txt, 0 cor, 1 ord
  UNION ALL SELECT 39 qn, 'B' lbl, 'It confirms his view on the value of research into certain monkeys.' txt, 0 cor, 2 ord
  UNION ALL SELECT 39 qn, 'C' lbl, 'It proves that female monkeys are generally less intelligent than males.' txt, 0 cor, 3 ord
  UNION ALL SELECT 39 qn, 'D' lbl, 'It illustrates a point he wants to make about monkeys and other creatures.' txt, 1 cor, 4 ord
  UNION ALL SELECT 40 qn, 'A' lbl, 'They can suffer from some of the same illnesses as humans.' txt, 0 cor, 1 ord
  UNION ALL SELECT 40 qn, 'B' lbl, 'They appear to treat disabled peers with consideration.' txt, 1 cor, 2 ord
  UNION ALL SELECT 40 qn, 'C' lbl, 'They have sets of social conventions that they follow.' txt, 0 cor, 3 ord
  UNION ALL SELECT 40 qn, 'D' lbl, 'The males can be quite destructive at times.' txt, 0 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_005'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

SELECT t.code, COUNT(*) AS questions FROM tests t JOIN questions q ON q.test_id = t.id WHERE t.code REGEXP '^IELTS_PT_R_00[2-5]$' GROUP BY t.code ORDER BY t.code;
