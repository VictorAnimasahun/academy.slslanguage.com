-- ============================================================
-- Migration 135 -- IELTS Academic Reading Practice Tests 1, 3 and 4 (Cambridge IELTS 17 Academic, Tests 1, 3, 4) wired to "Reading Test 1 / 3 / 4"
-- (test codes IELTS_PT_R_ACA_C17T1 / T3 / T4; pages resources/practice_tests/ielts_reading_academic_c17_t1 / t3 / t4 .php; Test 2 is migration 133)
--
-- Generated from documentation/test_bank/cambridge_ielts17_academic/test1|3|4.json (answers checked against the printed keys, pp.120 / 124 / 126).
-- Seeds each test's answer key, then points the "Reading Test N" pieces of IELTS Academic 1/2/3-Month at the page:
--   Reading Test 1 (Crash Course, 2-Month, 3-Month): replaces the compiled 30-question sample set with the full Cambridge test
--                  (the sample page ielts_reading_academic_001.php stays on disk and stays reachable);
--   Reading Test 3 (2-Month, 3-Month) and Reading Test 4 (3-Month): were Coming Soon.
-- "Choose TWO letters" pairs are two question rows each (both letters marked correct); scoring of pairs is declared per test code in save_attempt.php.
-- Idempotent. Run AFTER 126, 128 and 134 (134 creates the Crash Course's pieces). Written portable for older MySQL/MariaDB.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_ACA_C17T1', 'IELTS Academic Reading Practice Test 1',
       'A full Academic Reading test (Cambridge IELTS 17 Academic, Test 1): 3 passages, 40 questions, 60 minutes.', 'IELTS_Academic', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_ACA_C17T1');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'The London underground railway - The problem: The ___ of London increased rapidly between 1800 and 1850' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'The proposed solution: Building the railway would make it possible to move people to better housing in the ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'A number of ___ agreed with Pearson''s idea' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'The company initially had problems getting the ___ needed for the project' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'Negative articles about the project appeared in the ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'The construction: With the completion of the brick arch, the tunnel was covered with ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'Other countries had built underground railways before the Metropolitan line opened.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'More people than predicted travelled on the Metropolitan line on the first day.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'The use of ventilation shafts failed to prevent pollution in the tunnels.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'A different approach from the ''cut and cover'' technique was required in London''s central area.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'The windows on City & South London trains were at eye level.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'The City & South London Railway was a financial success.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'Trains on the ''Tuppenny Tube'' nearly always ran on time.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'a mention of negative attitudes towards stadium building projects' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'figures demonstrating the environmental benefits of a certain stadium' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'examples of the wide range of facilities available at some new stadiums' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'reference to the disadvantages of the stadiums built during a certain era' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'Roman amphitheatres - The amphitheatre of Arles, for example, was converted first into a ___,' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'then into a residential area and finally into an arena where spectators could watch ___.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Meanwhile, the arena in Verona, one of the oldest Roman amphitheatres, is famous today as a venue where ___ is performed.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'The site of Lucca''s amphitheatre has also been used for many purposes over the centuries, including the storage of ___.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'It is now a market square with ___ and homes incorporated into the remains of the Roman amphitheatre.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'When comparing twentieth-century stadiums to ancient amphitheatres in Section D, which TWO negative features does the writer mention? (first answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'When comparing twentieth-century stadiums to ancient amphitheatres in Section D, which TWO negative features does the writer mention? (second answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'Which TWO advantages of modern stadium design does the writer mention? (first answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'Which TWO advantages of modern stadium design does the writer mention? (second answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'The story behind the hunt for Charles II - Charles II''s father was executed by the Parliamentarian forces in 1649. Charles II then formed a ___ with the Scots,' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'and in order to become King of Scots, he abandoned an important ___ that was held by his father and had contributed to his father''s death.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'The battle led to a ___ for the Parliamentarians and Charles had to flee for his life.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'A ___ was offered for Charles''s capture,' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'but after six weeks spent in hiding, he eventually managed to reach the ___ of continental Europe.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'Charles chose Pepys for the task because he considered him to be trustworthy.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'Charles''s personal recollection of the escape lacked sufficient detail.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'Charles indicated to Pepys that he had planned his escape before the battle.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'The inclusion of Charles''s account is a positive aspect of the book.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'What is the reviewer''s main purpose in the first paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'Why does the reviewer include examples of the fugitives'' behaviour in the third paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'What point does the reviewer make about Charles II in the fourth paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'What does the reviewer say about Charles Spencer in the fifth paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'When the reviewer says the book ''doesn''t quite hit the mark'', she is making the point that' txt, 'multiple_choice_single' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T1'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'population' ans, 0 alt
  UNION ALL SELECT 2 qn, 'suburbs' ans, 0 alt
  UNION ALL SELECT 3 qn, 'businessmen' ans, 0 alt
  UNION ALL SELECT 4 qn, 'funding' ans, 0 alt
  UNION ALL SELECT 5 qn, 'press' ans, 0 alt
  UNION ALL SELECT 6 qn, 'soil' ans, 0 alt
  UNION ALL SELECT 7 qn, 'false' ans, 0 alt
  UNION ALL SELECT 8 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 9 qn, 'true' ans, 0 alt
  UNION ALL SELECT 10 qn, 'true' ans, 0 alt
  UNION ALL SELECT 11 qn, 'false' ans, 0 alt
  UNION ALL SELECT 12 qn, 'false' ans, 0 alt
  UNION ALL SELECT 13 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 14 qn, 'a' ans, 0 alt
  UNION ALL SELECT 15 qn, 'f' ans, 0 alt
  UNION ALL SELECT 16 qn, 'e' ans, 0 alt
  UNION ALL SELECT 17 qn, 'd' ans, 0 alt
  UNION ALL SELECT 18 qn, 'fortress' ans, 0 alt
  UNION ALL SELECT 19 qn, 'bullfights' ans, 0 alt
  UNION ALL SELECT 20 qn, 'opera' ans, 0 alt
  UNION ALL SELECT 21 qn, 'salt' ans, 0 alt
  UNION ALL SELECT 22 qn, 'shops' ans, 0 alt
  UNION ALL SELECT 27 qn, 'h' ans, 0 alt
  UNION ALL SELECT 28 qn, 'j' ans, 0 alt
  UNION ALL SELECT 29 qn, 'f' ans, 0 alt
  UNION ALL SELECT 30 qn, 'b' ans, 0 alt
  UNION ALL SELECT 31 qn, 'd' ans, 0 alt
  UNION ALL SELECT 32 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 33 qn, 'no' ans, 0 alt
  UNION ALL SELECT 34 qn, 'no' ans, 0 alt
  UNION ALL SELECT 35 qn, 'yes' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T1' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 23 qn, 'A' lbl, 'They are less imaginatively designed.' txt, 0 cor, 1 ord
  UNION ALL SELECT 23 qn, 'B' lbl, 'They are less spacious.' txt, 0 cor, 2 ord
  UNION ALL SELECT 23 qn, 'C' lbl, 'They are in less convenient locations.' txt, 1 cor, 3 ord
  UNION ALL SELECT 23 qn, 'D' lbl, 'They are less versatile.' txt, 1 cor, 4 ord
  UNION ALL SELECT 23 qn, 'E' lbl, 'They are made of less durable materials.' txt, 0 cor, 5 ord
  UNION ALL SELECT 24 qn, 'A' lbl, 'They are less imaginatively designed.' txt, 0 cor, 1 ord
  UNION ALL SELECT 24 qn, 'B' lbl, 'They are less spacious.' txt, 0 cor, 2 ord
  UNION ALL SELECT 24 qn, 'C' lbl, 'They are in less convenient locations.' txt, 1 cor, 3 ord
  UNION ALL SELECT 24 qn, 'D' lbl, 'They are less versatile.' txt, 1 cor, 4 ord
  UNION ALL SELECT 24 qn, 'E' lbl, 'They are made of less durable materials.' txt, 0 cor, 5 ord
  UNION ALL SELECT 25 qn, 'A' lbl, 'offering improved amenities for the enjoyment of sports events' txt, 0 cor, 1 ord
  UNION ALL SELECT 25 qn, 'B' lbl, 'bringing community life back into the city environment' txt, 1 cor, 2 ord
  UNION ALL SELECT 25 qn, 'C' lbl, 'facilitating research into solar and wind energy solutions' txt, 0 cor, 3 ord
  UNION ALL SELECT 25 qn, 'D' lbl, 'enabling local residents to reduce their consumption of electricity' txt, 0 cor, 4 ord
  UNION ALL SELECT 25 qn, 'E' lbl, 'providing a suitable site for the installation of renewable power generators' txt, 1 cor, 5 ord
  UNION ALL SELECT 26 qn, 'A' lbl, 'offering improved amenities for the enjoyment of sports events' txt, 0 cor, 1 ord
  UNION ALL SELECT 26 qn, 'B' lbl, 'bringing community life back into the city environment' txt, 1 cor, 2 ord
  UNION ALL SELECT 26 qn, 'C' lbl, 'facilitating research into solar and wind energy solutions' txt, 0 cor, 3 ord
  UNION ALL SELECT 26 qn, 'D' lbl, 'enabling local residents to reduce their consumption of electricity' txt, 0 cor, 4 ord
  UNION ALL SELECT 26 qn, 'E' lbl, 'providing a suitable site for the installation of renewable power generators' txt, 1 cor, 5 ord
  UNION ALL SELECT 36 qn, 'A' lbl, 'to describe what happened during the Battle of Worcester' txt, 0 cor, 1 ord
  UNION ALL SELECT 36 qn, 'B' lbl, 'to give an account of the circumstances leading to Charles II''s escape' txt, 1 cor, 2 ord
  UNION ALL SELECT 36 qn, 'C' lbl, 'to provide details of the Parliamentarians'' political views' txt, 0 cor, 3 ord
  UNION ALL SELECT 36 qn, 'D' lbl, 'to compare Charles II''s beliefs with those of his father' txt, 0 cor, 4 ord
  UNION ALL SELECT 37 qn, 'A' lbl, 'to explain how close Charles II came to losing his life' txt, 0 cor, 1 ord
  UNION ALL SELECT 37 qn, 'B' lbl, 'to suggest that Charles II''s supporters were badly prepared' txt, 0 cor, 2 ord
  UNION ALL SELECT 37 qn, 'C' lbl, 'to illustrate how the events of the six weeks are brought to life' txt, 1 cor, 3 ord
  UNION ALL SELECT 37 qn, 'D' lbl, 'to argue that certain aspects are not as well known as they should be' txt, 0 cor, 4 ord
  UNION ALL SELECT 38 qn, 'A' lbl, 'He chose to celebrate what was essentially a defeat.' txt, 1 cor, 1 ord
  UNION ALL SELECT 38 qn, 'B' lbl, 'He misunderstood the motives of his opponents.' txt, 0 cor, 2 ord
  UNION ALL SELECT 38 qn, 'C' lbl, 'He aimed to restore people''s faith in the monarchy.' txt, 0 cor, 3 ord
  UNION ALL SELECT 38 qn, 'D' lbl, 'He was driven by a desire to be popular.' txt, 0 cor, 4 ord
  UNION ALL SELECT 39 qn, 'A' lbl, 'His decision to write the book comes as a surprise.' txt, 0 cor, 1 ord
  UNION ALL SELECT 39 qn, 'B' lbl, 'He takes an unbiased approach to the subject matter.' txt, 1 cor, 2 ord
  UNION ALL SELECT 39 qn, 'C' lbl, 'His descriptions of events would be better if they included more detail.' txt, 0 cor, 3 ord
  UNION ALL SELECT 39 qn, 'D' lbl, 'He chooses language that is suitable for a twenty-first-century audience.' txt, 0 cor, 4 ord
  UNION ALL SELECT 40 qn, 'A' lbl, 'it overlooks the impact of events on ordinary people.' txt, 0 cor, 1 ord
  UNION ALL SELECT 40 qn, 'B' lbl, 'it lacks an analysis of prevalent views on monarchy.' txt, 0 cor, 2 ord
  UNION ALL SELECT 40 qn, 'C' lbl, 'it omits any references to the deceit practised by Charles II during his time in hiding.' txt, 0 cor, 3 ord
  UNION ALL SELECT 40 qn, 'D' lbl, 'it fails to address whether Charles II''s experiences had a lasting influence on him.' txt, 1 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_ACA_C17T1'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_ACA_C17T3', 'IELTS Academic Reading Practice Test 3',
       'A full Academic Reading test (Cambridge IELTS 17 Academic, Test 3): 3 passages, 40 questions, 60 minutes.', 'IELTS_Academic', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_ACA_C17T3');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'The thylacine - Appearance and behaviour: ate an entirely ___ diet' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'probably depended mainly on ___ when hunting' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'young spent first months of life inside its mother''s ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'Decline and extinction: last evidence in mainland Australia is a 3,100-year-old ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'reduction in ___ and available sources of food were partly responsible for decline in Tasmania' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'Significant numbers of thylacines were killed by humans from the 1830s onwards.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'Several thylacines were born in zoos during the late 1800s.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'John Gould''s prediction about the thylacine surprised some biologists.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'In the early 1900s, many scientists became worried about the possible extinction of the thylacine.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'T. T. Flynn''s proposal to rehome captive thylacines on an island proved to be impractical.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'There were still reasonable numbers of thylacines in existence when a piece of legislation protecting the species during their breeding season was passed.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'From 1930 to 1936, the only known living thylacines were all in captivity.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'Attempts to find living thylacines are now rarely made.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'examples of a range of potential environmental advantages of oil palm tree cultivation' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'description of an organisation which controls the environmental impact of palm oil production' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'examples of the widespread global use of palm oil' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'reference to a particular species which could benefit the ecosystem of oil palm plantations' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'figures illustrating the rapid expansion of the palm oil industry' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'an economic justification for not opposing the palm oil industry' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'examples of creatures badly affected by the establishment of oil palm plantations' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'Which TWO statements are made about the Roundtable on Sustainable Palm Oil (RSPO)? (first answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'Which TWO statements are made about the Roundtable on Sustainable Palm Oil (RSPO)? (second answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'One advantage of palm oil for manufacturers is that it stays ___ even when not refrigerated.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'The ___ is the best known of the animals suffering habitat loss as a result of the spread of oil palm plantations.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'As one of its criteria for the certification of sustainable palm oil, the RSPO insists that growers check ___ on a routine basis.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'Ellwood and his researchers are looking into whether the bird''s nest fern could restore ___ in areas where oil palm trees are grown.' txt, 'sentence_completion' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'What point does Shester make about Barr''s book in the first paragraph?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'How does Shester respond to the information in the book about tenements?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'What does Shester say about chapter six of the book?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'What does Shester suggest about the chapters focusing on the 1920s building boom?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'What impresses Shester the most about the chapter on land values?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'The description in the first chapter of how New York probably looked from the air in the early 1600s lacks interest.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'Chapters two and three prepare the reader well for material yet to come.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'The biggest problem for many nineteenth-century New York immigrant neighbourhoods was a lack of amenities.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'In the nineteenth century, New York''s immigrant neighbourhoods tended to concentrate around the harbour.' txt, 'yes_no_not_given' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'The bedrock myth - In chapter seven, Barr indicates how the lack of bedrock close to the surface does not explain why skyscrapers are absent from ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'He points out that although the cost of foundations increases when bedrock is deep below the surface, this cannot be regarded as ___,' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'especially when compared to ___.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'He describes not only how ___ are made possible by the use of caissons,' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'but he also discusses their ___.' txt, 'summary_completion' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T3'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'carnivorous' ans, 0 alt
  UNION ALL SELECT 2 qn, 'scent' ans, 0 alt
  UNION ALL SELECT 3 qn, 'pouch' ans, 0 alt
  UNION ALL SELECT 4 qn, 'fossil' ans, 0 alt
  UNION ALL SELECT 5 qn, 'habitat' ans, 0 alt
  UNION ALL SELECT 6 qn, 'true' ans, 0 alt
  UNION ALL SELECT 7 qn, 'false' ans, 0 alt
  UNION ALL SELECT 8 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 9 qn, 'false' ans, 0 alt
  UNION ALL SELECT 10 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 11 qn, 'false' ans, 0 alt
  UNION ALL SELECT 12 qn, 'true' ans, 0 alt
  UNION ALL SELECT 13 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 14 qn, 'f' ans, 0 alt
  UNION ALL SELECT 15 qn, 'g' ans, 0 alt
  UNION ALL SELECT 16 qn, 'a' ans, 0 alt
  UNION ALL SELECT 17 qn, 'h' ans, 0 alt
  UNION ALL SELECT 18 qn, 'b' ans, 0 alt
  UNION ALL SELECT 19 qn, 'e' ans, 0 alt
  UNION ALL SELECT 20 qn, 'c' ans, 0 alt
  UNION ALL SELECT 23 qn, 'solid' ans, 0 alt
  UNION ALL SELECT 24 qn, 'orangutan' ans, 0 alt
  UNION ALL SELECT 24 qn, 'sumatran orangutan' ans, 1 alt
  UNION ALL SELECT 24 qn, 'orang-utan' ans, 1 alt
  UNION ALL SELECT 24 qn, 'sumatran orang-utan' ans, 1 alt
  UNION ALL SELECT 25 qn, 'carbon stocks' ans, 0 alt
  UNION ALL SELECT 26 qn, 'biodiversity' ans, 0 alt
  UNION ALL SELECT 32 qn, 'no' ans, 0 alt
  UNION ALL SELECT 33 qn, 'yes' ans, 0 alt
  UNION ALL SELECT 34 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 35 qn, 'no' ans, 0 alt
  UNION ALL SELECT 36 qn, 'h' ans, 0 alt
  UNION ALL SELECT 37 qn, 'd' ans, 0 alt
  UNION ALL SELECT 38 qn, 'i' ans, 0 alt
  UNION ALL SELECT 39 qn, 'b' ans, 0 alt
  UNION ALL SELECT 40 qn, 'f' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T3' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 21 qn, 'A' lbl, 'Its membership has grown steadily over the course of the last decade.' txt, 0 cor, 1 ord
  UNION ALL SELECT 21 qn, 'B' lbl, 'It demands that certified producers be open and honest about their practices.' txt, 1 cor, 2 ord
  UNION ALL SELECT 21 qn, 'C' lbl, 'It took several years to establish its set of criteria for sustainable palm oil certification.' txt, 1 cor, 3 ord
  UNION ALL SELECT 21 qn, 'D' lbl, 'Its regulations regarding sustainability are stricter than those governing other industries.' txt, 0 cor, 4 ord
  UNION ALL SELECT 21 qn, 'E' lbl, 'It was formed at the request of environmentalists concerned about the loss of virgin forests.' txt, 0 cor, 5 ord
  UNION ALL SELECT 22 qn, 'A' lbl, 'Its membership has grown steadily over the course of the last decade.' txt, 0 cor, 1 ord
  UNION ALL SELECT 22 qn, 'B' lbl, 'It demands that certified producers be open and honest about their practices.' txt, 1 cor, 2 ord
  UNION ALL SELECT 22 qn, 'C' lbl, 'It took several years to establish its set of criteria for sustainable palm oil certification.' txt, 1 cor, 3 ord
  UNION ALL SELECT 22 qn, 'D' lbl, 'Its regulations regarding sustainability are stricter than those governing other industries.' txt, 0 cor, 4 ord
  UNION ALL SELECT 22 qn, 'E' lbl, 'It was formed at the request of environmentalists concerned about the loss of virgin forests.' txt, 0 cor, 5 ord
  UNION ALL SELECT 27 qn, 'A' lbl, 'It gives a highly original explanation for urban development.' txt, 0 cor, 1 ord
  UNION ALL SELECT 27 qn, 'B' lbl, 'Elements of Barr''s research papers are incorporated throughout the book.' txt, 0 cor, 2 ord
  UNION ALL SELECT 27 qn, 'C' lbl, 'Other books that are available on the subject have taken a different approach.' txt, 0 cor, 3 ord
  UNION ALL SELECT 27 qn, 'D' lbl, 'It covers a range of factors that affected the development of New York.' txt, 1 cor, 4 ord
  UNION ALL SELECT 28 qn, 'A' lbl, 'She describes the reasons for Barr''s interest.' txt, 0 cor, 1 ord
  UNION ALL SELECT 28 qn, 'B' lbl, 'She indicates a potential problem with Barr''s analysis.' txt, 1 cor, 2 ord
  UNION ALL SELECT 28 qn, 'C' lbl, 'She compares Barr''s conclusion with that of other writers.' txt, 0 cor, 3 ord
  UNION ALL SELECT 28 qn, 'D' lbl, 'She provides details about the sources Barr used for his research.' txt, 0 cor, 4 ord
  UNION ALL SELECT 29 qn, 'A' lbl, 'It contains conflicting data.' txt, 0 cor, 1 ord
  UNION ALL SELECT 29 qn, 'B' lbl, 'It focuses too much on possible trends.' txt, 0 cor, 2 ord
  UNION ALL SELECT 29 qn, 'C' lbl, 'It is too specialised for most readers.' txt, 1 cor, 3 ord
  UNION ALL SELECT 29 qn, 'D' lbl, 'It draws on research that is out of date.' txt, 0 cor, 4 ord
  UNION ALL SELECT 30 qn, 'A' lbl, 'The information should have been organised differently.' txt, 0 cor, 1 ord
  UNION ALL SELECT 30 qn, 'B' lbl, 'More facts are needed about the way construction was financed.' txt, 0 cor, 2 ord
  UNION ALL SELECT 30 qn, 'C' lbl, 'The explanation that is given for the building boom is unlikely.' txt, 0 cor, 3 ord
  UNION ALL SELECT 30 qn, 'D' lbl, 'Some parts will have limited appeal to certain people.' txt, 1 cor, 4 ord
  UNION ALL SELECT 31 qn, 'A' lbl, 'the broad time period that is covered' txt, 0 cor, 1 ord
  UNION ALL SELECT 31 qn, 'B' lbl, 'the interesting questions that Barr asks' txt, 0 cor, 2 ord
  UNION ALL SELECT 31 qn, 'C' lbl, 'the nature of the research into the topic' txt, 1 cor, 3 ord
  UNION ALL SELECT 31 qn, 'D' lbl, 'the recommendations Barr makes for the future' txt, 0 cor, 4 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_ACA_C17T3'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_R_ACA_C17T4', 'IELTS Academic Reading Practice Test 4',
       'A full Academic Reading test (Cambridge IELTS 17 Academic, Test 4): 3 passages, 40 questions, 60 minutes.', 'IELTS_Academic', 60, 40, 1, 'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_R_ACA_C17T4');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Many Madagascan forests are being destroyed by attacks from insects.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'Loss of habitat has badly affected insectivorous bats in Madagascar.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'Ricardo Rocha has carried out studies of bats in different parts of the world.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'Habitat modification has resulted in indigenous bats in Madagascar becoming useful to farmers.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'The Malagasy mouse-eared bat is more common than other indigenous bat species in Madagascar.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'Bats may feed on paddy swarming caterpillars and grass webworms.' txt, 'true_false_not_given' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'The study carried out by Rocha''s team - Method: ultrasonic recording to identify favourite feeding spots; DNA analysis of bat ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'Findings: the bats ate pests of rice, ___, sugarcane, nuts and fruit' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'the bats prevent the spread of disease by eating ___ and blackflies' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'local attitudes to bats are mixed: they provide food rich in ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'the buildings where they roost become ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 12 qn, 'they play an important role in local ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 13 qn, 'Recommendation: farmers should provide special ___ to support the bat population' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 14 qn, 'an explanation of the need for research to focus on individuals with a fairly consistent income' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'examples of the sources the database has been compiled from' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'an account of one individual''s refusal to obey an order' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'a reference to a region being particularly suited to research into the link between education and economic growth' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'examples of the items included in a list of personal possessions' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Demographic reconstruction of two German communities - The database sheds light on the lives of a range of individuals, as well as those of their ___, over a 300-year period.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Ana Regina and Magdalena Riethmüllerin were reprimanded for reading while they should have been paying attention to a ___.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'Juliana Schweickherdt came to the notice of the weavers'' guild in the year 1752 for breaking guild rules. As a punishment, she was later given a ___.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 22 qn, 'Cases like this illustrate how the guilds could prevent ___ and stop skilled people from working.' txt, 'summary_completion' qtype, 2 pn
  UNION ALL SELECT 23 qn, 'Which TWO of the following statements does the writer make about literacy rates in Section B? (first answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 24 qn, 'Which TWO of the following statements does the writer make about literacy rates in Section B? (second answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 25 qn, 'Which TWO of the following statements does the writer make in Section F about guilds in German-speaking Central Europe between 1600 and 1900? (first answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 26 qn, 'Which TWO of the following statements does the writer make in Section F about guilds in German-speaking Central Europe between 1600 and 1900? (second answer)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 27 qn, 'a reference to earlier examples of blindfold chess' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'an outline of what blindfold chess involves' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'a claim that Gareyev''s skill is limited to chess' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'why Gareyev''s skill is of interest to scientists' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'an outline of Gareyev''s priorities' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 32 qn, 'a reason why the last part of a game may be difficult' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 33 qn, 'In the forthcoming games, all the participants will be blindfolded.' txt, 'true_false_not_given' qtype, 3 pn
  UNION ALL SELECT 34 qn, 'Gareyev has won competitions in BASE jumping.' txt, 'true_false_not_given' qtype, 3 pn
  UNION ALL SELECT 35 qn, 'UCLA is the first university to carry out research into blindfold chess players.' txt, 'true_false_not_given' qtype, 3 pn
  UNION ALL SELECT 36 qn, 'Good chess players are likely to be able to play blindfold chess.' txt, 'true_false_not_given' qtype, 3 pn
  UNION ALL SELECT 37 qn, 'How the research was carried out - The researchers started by testing Gareyev''s ___;' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 38 qn, 'for example, he was required to recall a string of ___ in order and also in reverse order.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 39 qn, 'Although his performance was normal, scans showed an unusual amount of ___ within the areas of Gareyev''s brain that are concerned with directing attention.' txt, 'summary_completion' qtype, 3 pn
  UNION ALL SELECT 40 qn, 'In addition, the scans raised the possibility of unusual strength in the parts of his brain that deal with ___ input.' txt, 'summary_completion' qtype, 3 pn
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T4'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'false' ans, 0 alt
  UNION ALL SELECT 2 qn, 'false' ans, 0 alt
  UNION ALL SELECT 3 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 4 qn, 'true' ans, 0 alt
  UNION ALL SELECT 5 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 6 qn, 'true' ans, 0 alt
  UNION ALL SELECT 7 qn, 'droppings' ans, 0 alt
  UNION ALL SELECT 8 qn, 'coffee' ans, 0 alt
  UNION ALL SELECT 9 qn, 'mosquitoes' ans, 0 alt
  UNION ALL SELECT 10 qn, 'protein' ans, 0 alt
  UNION ALL SELECT 11 qn, 'unclean' ans, 0 alt
  UNION ALL SELECT 12 qn, 'culture' ans, 0 alt
  UNION ALL SELECT 13 qn, 'houses' ans, 0 alt
  UNION ALL SELECT 14 qn, 'e' ans, 0 alt
  UNION ALL SELECT 15 qn, 'a' ans, 0 alt
  UNION ALL SELECT 16 qn, 'd' ans, 0 alt
  UNION ALL SELECT 17 qn, 'f' ans, 0 alt
  UNION ALL SELECT 18 qn, 'c' ans, 0 alt
  UNION ALL SELECT 19 qn, 'descendants' ans, 0 alt
  UNION ALL SELECT 20 qn, 'sermon' ans, 0 alt
  UNION ALL SELECT 21 qn, 'fine' ans, 0 alt
  UNION ALL SELECT 22 qn, 'innovation' ans, 0 alt
  UNION ALL SELECT 27 qn, 'd' ans, 0 alt
  UNION ALL SELECT 28 qn, 'e' ans, 0 alt
  UNION ALL SELECT 29 qn, 'f' ans, 0 alt
  UNION ALL SELECT 30 qn, 'b' ans, 0 alt
  UNION ALL SELECT 31 qn, 'h' ans, 0 alt
  UNION ALL SELECT 32 qn, 'e' ans, 0 alt
  UNION ALL SELECT 33 qn, 'false' ans, 0 alt
  UNION ALL SELECT 34 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 35 qn, 'not given' ans, 0 alt
  UNION ALL SELECT 36 qn, 'true' ans, 0 alt
  UNION ALL SELECT 37 qn, 'memory' ans, 0 alt
  UNION ALL SELECT 38 qn, 'numbers' ans, 0 alt
  UNION ALL SELECT 39 qn, 'communication' ans, 0 alt
  UNION ALL SELECT 40 qn, 'visual' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_R_ACA_C17T4' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 23 qn, 'A' lbl, 'Very little research has been done into the link between high literacy rates and improved earnings.' txt, 0 cor, 1 ord
  UNION ALL SELECT 23 qn, 'B' lbl, 'Literacy rates in Germany between 1600 and 1900 were very good.' txt, 1 cor, 2 ord
  UNION ALL SELECT 23 qn, 'C' lbl, 'There is strong evidence that high literacy rates in the modern world result in economic growth.' txt, 0 cor, 3 ord
  UNION ALL SELECT 23 qn, 'D' lbl, 'England is a good example of how high literacy rates helped a country industrialise.' txt, 0 cor, 4 ord
  UNION ALL SELECT 23 qn, 'E' lbl, 'Economic growth can help to improve literacy rates.' txt, 1 cor, 5 ord
  UNION ALL SELECT 24 qn, 'A' lbl, 'Very little research has been done into the link between high literacy rates and improved earnings.' txt, 0 cor, 1 ord
  UNION ALL SELECT 24 qn, 'B' lbl, 'Literacy rates in Germany between 1600 and 1900 were very good.' txt, 1 cor, 2 ord
  UNION ALL SELECT 24 qn, 'C' lbl, 'There is strong evidence that high literacy rates in the modern world result in economic growth.' txt, 0 cor, 3 ord
  UNION ALL SELECT 24 qn, 'D' lbl, 'England is a good example of how high literacy rates helped a country industrialise.' txt, 0 cor, 4 ord
  UNION ALL SELECT 24 qn, 'E' lbl, 'Economic growth can help to improve literacy rates.' txt, 1 cor, 5 ord
  UNION ALL SELECT 25 qn, 'A' lbl, 'They helped young people to learn a skill.' txt, 0 cor, 1 ord
  UNION ALL SELECT 25 qn, 'B' lbl, 'They were opposed to people moving to an area for work.' txt, 1 cor, 2 ord
  UNION ALL SELECT 25 qn, 'C' lbl, 'They kept better records than guilds in other parts of the world.' txt, 0 cor, 3 ord
  UNION ALL SELECT 25 qn, 'D' lbl, 'They opposed practices that threatened their control over a trade.' txt, 1 cor, 4 ord
  UNION ALL SELECT 25 qn, 'E' lbl, 'They predominantly consisted of wealthy merchants.' txt, 0 cor, 5 ord
  UNION ALL SELECT 26 qn, 'A' lbl, 'They helped young people to learn a skill.' txt, 0 cor, 1 ord
  UNION ALL SELECT 26 qn, 'B' lbl, 'They were opposed to people moving to an area for work.' txt, 1 cor, 2 ord
  UNION ALL SELECT 26 qn, 'C' lbl, 'They kept better records than guilds in other parts of the world.' txt, 0 cor, 3 ord
  UNION ALL SELECT 26 qn, 'D' lbl, 'They opposed practices that threatened their control over a trade.' txt, 1 cor, 4 ord
  UNION ALL SELECT 26 qn, 'E' lbl, 'They predominantly consisted of wealthy merchants.' txt, 0 cor, 5 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_R_ACA_C17T4'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

-- The pieces (found by course folder + piece title, never by id)
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_reading_academic_c17_t1.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_1Mo', 'IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Reading Test 1'
   AND lp.kind IN ('practice_test', 'mock_test') AND (lp.file_path IS NULL OR lp.file_path = 'resources/practice_tests/ielts_reading_academic_001.php');
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_reading_academic_c17_t3.php', lp.status = 'ready'
 WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title = 'Reading Test 3' AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.file_path = 'resources/practice_tests/ielts_reading_academic_c17_t4.php', lp.status = 'ready'
 WHERE c.folder_name = 'IELTS_Aca_3Mo' AND lp.title = 'Reading Test 4' AND lp.kind IN ('practice_test', 'mock_test') AND lp.file_path IS NULL;

-- Verify: 3 tests with 40 questions each, and where the pieces point now
SELECT t.code, COUNT(*) AS questions FROM tests t JOIN questions q ON q.test_id = t.id WHERE t.code IN ('IELTS_PT_R_ACA_C17T1','IELTS_PT_R_ACA_C17T3','IELTS_PT_R_ACA_C17T4') GROUP BY t.code;
SELECT c.folder_name, lp.title, lp.file_path FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE c.folder_name IN ('IELTS_Aca_1Mo', 'IELTS_Aca_2Mo', 'IELTS_Aca_3Mo') AND lp.title REGEXP '^Reading Test [134]$' ORDER BY c.folder_name, lp.title;
