-- ============================================================
-- Migration 140 -- IELTS Listening Practice Tests 3 and 4 (Cambridge IELTS 17 Academic, Tests 1 and 2): answer keys
-- (test codes IELTS_PT_L_003 and IELTS_PT_L_004; pages resources/practice_tests/ielts_listening_003.php / _004.php, which were empty placeholders)
--
-- Generated from documentation/test_bank/cambridge_ielts17_academic/test1.json and test2.json. Questions typed from the printed paper.
-- "Choose TWO" pairs are stored as two questions each (15/16, 17/18, 19/20 in Test 3; 21/22 in Test 4), both carrying the same five options with the two
-- correct ones flagged, the same way Listening Practice Test 2 does it; the pair is graded as a set by the page and by save_attempt.php.
--
-- NOT wired into any course yet: the recordings (assets/audio/IELTS_PT_L_003 and IELTS_PT_L_004, part1..part4.mp4) are the instructor's and are
-- not here. Until they are, the pieces "Listening Test 3" (Class 12 / 2Mo, 3Mo) and "Listening Test 4" (3Mo Class 18) stay Coming Soon.
-- Idempotent. Run AFTER 126. Written portable for older MySQL/MariaDB.
-- ============================================================

SET NAMES utf8mb4;


-- ---- Practice Test 3 ----
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_L_003', 'IELTS Listening – Practice Test 3',
       'A full IELTS Listening test (Cambridge IELTS 17 Academic, Test 1): 4 parts, 40 questions, about 30 minutes plus transfer time. The recordings are added separately.', 'IELTS', 30, 40, 1, 'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_L_003');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Regular activities - Beach: making sure the beach does not have ___ on it' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'Beach: no ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'Nature reserve: next task is taking action to attract ___ to the place' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'Nature reserve: identifying types of ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'Nature reserve: building a new ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'Forthcoming events - Saturday: meet at Dunsmore Beach car park; walk across the sands and reach the ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'Saturday: take a picnic; wear appropriate ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'Woodwork session: suitable for ___ to participate in' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 9 qn, 'Woodwork session: making ___ out of wood' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 10 qn, 'Woodwork session: 17th, from 10 a.m. to 3 p.m.; cost of session (no camping): £___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'What is the maximum number of people who can stand on each side of the boat?' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 12 qn, 'What colour are the tour boats?' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 13 qn, 'Which lunchbox is suitable for someone who doesn''t eat meat or fish?' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 14 qn, 'What should people do with their litter?' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'Which TWO features of the lighthouse does Lou mention? (Questions 15 and 16; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'Which TWO features of the lighthouse does Lou mention? (Questions 15 and 16; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Which TWO types of creature might come close to the boat? (Questions 17 and 18; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'Which TWO types of creature might come close to the boat? (Questions 17 and 18; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Which TWO points does Lou make about the caves? (Questions 19 and 20; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Which TWO points does Lou make about the caves? (Questions 19 and 20; either order)' txt, 'multiple_choice_multiple' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'What problem did both Diana and Tim have when arranging their work experience?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 22 qn, 'Tim was pleased to be able to help' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 23 qn, 'Diana says the sheep on her farm' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 24 qn, 'What did the students learn about adding supplements to chicken feed?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 25 qn, 'What happened when Diana was working with dairy cows?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 26 qn, 'What did both farmers mention about vets and farming?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 27 qn, 'Modules on Veterinary Science course: Medical terminology' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'Modules on Veterinary Science course: Diet and nutrition' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'Modules on Veterinary Science course: Animal disease' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'Modules on Veterinary Science course: Wildlife medication' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'Labyrinths compared with mazes: Mazes are a type of ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 32 qn, '___ is needed to navigate through a maze' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 33 qn, 'the word ''maze'' is derived from a word meaning a feeling of ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 34 qn, 'Labyrinths represent a journey through life - they have frequently been used in ___ and prayer' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 35 qn, 'Early examples of the labyrinth spiral: Ancient carvings on ___ have been found across many cultures' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 36 qn, 'Ancient Greeks used the symbol on ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 37 qn, 'Walking labyrinths: The largest surviving example of a turf labyrinth once had a big ___ at its centre' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 38 qn, 'Labyrinths nowadays: walking a maze can reduce a person''s ___ rate' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 39 qn, 'patients who can''t walk can use ''finger labyrinths'' made from ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 40 qn, 'research has shown that Alzheimer''s sufferers experience less ___' txt, 'form_note_completion' qtype, 4 pn
) d
WHERE t.code = 'IELTS_PT_L_003'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'litter' ans, 0 alt
  UNION ALL SELECT 2 qn, 'dogs' ans, 0 alt
  UNION ALL SELECT 3 qn, 'insects' ans, 0 alt
  UNION ALL SELECT 4 qn, 'butterflies' ans, 0 alt
  UNION ALL SELECT 5 qn, 'wall' ans, 0 alt
  UNION ALL SELECT 6 qn, 'island' ans, 0 alt
  UNION ALL SELECT 7 qn, 'boots' ans, 0 alt
  UNION ALL SELECT 8 qn, 'beginners' ans, 0 alt
  UNION ALL SELECT 9 qn, 'spoons' ans, 0 alt
  UNION ALL SELECT 10 qn, '35' ans, 0 alt
  UNION ALL SELECT 10 qn, 'thirty five' ans, 1 alt
  UNION ALL SELECT 10 qn, 'thirty-five' ans, 1 alt
  UNION ALL SELECT 27 qn, 'A' ans, 0 alt
  UNION ALL SELECT 28 qn, 'E' ans, 0 alt
  UNION ALL SELECT 29 qn, 'F' ans, 0 alt
  UNION ALL SELECT 30 qn, 'C' ans, 0 alt
  UNION ALL SELECT 31 qn, 'puzzle' ans, 0 alt
  UNION ALL SELECT 32 qn, 'logic' ans, 0 alt
  UNION ALL SELECT 33 qn, 'confusion' ans, 0 alt
  UNION ALL SELECT 34 qn, 'meditation' ans, 0 alt
  UNION ALL SELECT 35 qn, 'stone' ans, 0 alt
  UNION ALL SELECT 36 qn, 'coins' ans, 0 alt
  UNION ALL SELECT 37 qn, 'tree' ans, 0 alt
  UNION ALL SELECT 38 qn, 'breathing' ans, 0 alt
  UNION ALL SELECT 39 qn, 'paper' ans, 0 alt
  UNION ALL SELECT 40 qn, 'anxiety' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_L_003' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 11 qn, 'A' lbl, '9' txt, 1 cor, 1 ord
  UNION ALL SELECT 11 qn, 'B' lbl, '15' txt, 0 cor, 2 ord
  UNION ALL SELECT 11 qn, 'C' lbl, '18' txt, 0 cor, 3 ord
  UNION ALL SELECT 12 qn, 'A' lbl, 'dark red' txt, 0 cor, 1 ord
  UNION ALL SELECT 12 qn, 'B' lbl, 'jet black' txt, 0 cor, 2 ord
  UNION ALL SELECT 12 qn, 'C' lbl, 'light green' txt, 1 cor, 3 ord
  UNION ALL SELECT 13 qn, 'A' lbl, 'Lunchbox 1' txt, 0 cor, 1 ord
  UNION ALL SELECT 13 qn, 'B' lbl, 'Lunchbox 2' txt, 1 cor, 2 ord
  UNION ALL SELECT 13 qn, 'C' lbl, 'Lunchbox 3' txt, 0 cor, 3 ord
  UNION ALL SELECT 14 qn, 'A' lbl, 'take it home' txt, 0 cor, 1 ord
  UNION ALL SELECT 14 qn, 'B' lbl, 'hand it to a member of staff' txt, 1 cor, 2 ord
  UNION ALL SELECT 14 qn, 'C' lbl, 'put it in the bins provided on the boat' txt, 0 cor, 3 ord
  UNION ALL SELECT 15 qn, 'A' lbl, 'why it was built' txt, 1 cor, 1 ord
  UNION ALL SELECT 15 qn, 'B' lbl, 'who built it' txt, 0 cor, 2 ord
  UNION ALL SELECT 15 qn, 'C' lbl, 'how long it took to build' txt, 0 cor, 3 ord
  UNION ALL SELECT 15 qn, 'D' lbl, 'who staffed it' txt, 1 cor, 4 ord
  UNION ALL SELECT 15 qn, 'E' lbl, 'what it was built with' txt, 0 cor, 5 ord
  UNION ALL SELECT 16 qn, 'A' lbl, 'why it was built' txt, 1 cor, 1 ord
  UNION ALL SELECT 16 qn, 'B' lbl, 'who built it' txt, 0 cor, 2 ord
  UNION ALL SELECT 16 qn, 'C' lbl, 'how long it took to build' txt, 0 cor, 3 ord
  UNION ALL SELECT 16 qn, 'D' lbl, 'who staffed it' txt, 1 cor, 4 ord
  UNION ALL SELECT 16 qn, 'E' lbl, 'what it was built with' txt, 0 cor, 5 ord
  UNION ALL SELECT 17 qn, 'A' lbl, 'sea eagles' txt, 0 cor, 1 ord
  UNION ALL SELECT 17 qn, 'B' lbl, 'fur seals' txt, 1 cor, 2 ord
  UNION ALL SELECT 17 qn, 'C' lbl, 'dolphins' txt, 1 cor, 3 ord
  UNION ALL SELECT 17 qn, 'D' lbl, 'whales' txt, 0 cor, 4 ord
  UNION ALL SELECT 17 qn, 'E' lbl, 'penguins' txt, 0 cor, 5 ord
  UNION ALL SELECT 18 qn, 'A' lbl, 'sea eagles' txt, 0 cor, 1 ord
  UNION ALL SELECT 18 qn, 'B' lbl, 'fur seals' txt, 1 cor, 2 ord
  UNION ALL SELECT 18 qn, 'C' lbl, 'dolphins' txt, 1 cor, 3 ord
  UNION ALL SELECT 18 qn, 'D' lbl, 'whales' txt, 0 cor, 4 ord
  UNION ALL SELECT 18 qn, 'E' lbl, 'penguins' txt, 0 cor, 5 ord
  UNION ALL SELECT 19 qn, 'A' lbl, 'Only large tourist boats can visit them.' txt, 0 cor, 1 ord
  UNION ALL SELECT 19 qn, 'B' lbl, 'The entrances to them are often blocked.' txt, 0 cor, 2 ord
  UNION ALL SELECT 19 qn, 'C' lbl, 'It is too dangerous for individuals to go near them.' txt, 0 cor, 3 ord
  UNION ALL SELECT 19 qn, 'D' lbl, 'Someone will explain what is inside them.' txt, 1 cor, 4 ord
  UNION ALL SELECT 19 qn, 'E' lbl, 'They cannot be reached on foot.' txt, 1 cor, 5 ord
  UNION ALL SELECT 20 qn, 'A' lbl, 'Only large tourist boats can visit them.' txt, 0 cor, 1 ord
  UNION ALL SELECT 20 qn, 'B' lbl, 'The entrances to them are often blocked.' txt, 0 cor, 2 ord
  UNION ALL SELECT 20 qn, 'C' lbl, 'It is too dangerous for individuals to go near them.' txt, 0 cor, 3 ord
  UNION ALL SELECT 20 qn, 'D' lbl, 'Someone will explain what is inside them.' txt, 1 cor, 4 ord
  UNION ALL SELECT 20 qn, 'E' lbl, 'They cannot be reached on foot.' txt, 1 cor, 5 ord
  UNION ALL SELECT 21 qn, 'A' lbl, 'making initial contact with suitable farms' txt, 1 cor, 1 ord
  UNION ALL SELECT 21 qn, 'B' lbl, 'organising transport to and from the farm' txt, 0 cor, 2 ord
  UNION ALL SELECT 21 qn, 'C' lbl, 'finding a placement for the required length of time' txt, 0 cor, 3 ord
  UNION ALL SELECT 22 qn, 'A' lbl, 'a lamb that had a broken leg.' txt, 0 cor, 1 ord
  UNION ALL SELECT 22 qn, 'B' lbl, 'a sheep that was having difficulty giving birth.' txt, 1 cor, 2 ord
  UNION ALL SELECT 22 qn, 'C' lbl, 'a newly born lamb that was having trouble feeding.' txt, 0 cor, 3 ord
  UNION ALL SELECT 23 qn, 'A' lbl, 'were of various different varieties.' txt, 0 cor, 1 ord
  UNION ALL SELECT 23 qn, 'B' lbl, 'were mainly reared for their meat.' txt, 1 cor, 2 ord
  UNION ALL SELECT 23 qn, 'C' lbl, 'had better quality wool than sheep on the hills.' txt, 0 cor, 3 ord
  UNION ALL SELECT 24 qn, 'A' lbl, 'These should only be given if specially needed.' txt, 1 cor, 1 ord
  UNION ALL SELECT 24 qn, 'B' lbl, 'It is worth paying extra for the most effective ones.' txt, 0 cor, 2 ord
  UNION ALL SELECT 24 qn, 'C' lbl, 'The amount given at one time should be limited.' txt, 0 cor, 3 ord
  UNION ALL SELECT 25 qn, 'A' lbl, 'She identified some cows incorrectly.' txt, 0 cor, 1 ord
  UNION ALL SELECT 25 qn, 'B' lbl, 'She accidentally threw some milk away.' txt, 0 cor, 2 ord
  UNION ALL SELECT 25 qn, 'C' lbl, 'She made a mistake when storing milk.' txt, 1 cor, 3 ord
  UNION ALL SELECT 26 qn, 'A' lbl, 'Vets are failing to cope with some aspects of animal health.' txt, 0 cor, 1 ord
  UNION ALL SELECT 26 qn, 'B' lbl, 'There needs to be a fundamental change in the training of vets.' txt, 0 cor, 2 ord
  UNION ALL SELECT 26 qn, 'C' lbl, 'Some jobs could be done by the farmer rather than by a vet.' txt, 1 cor, 3 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_L_003'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

-- ---- Practice Test 4 ----
INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, category)
SELECT 'IELTS_PT_L_004', 'IELTS Listening – Practice Test 4',
       'A full IELTS Listening test (Cambridge IELTS 17 Academic, Test 2): 4 parts, 40 questions, about 30 minutes plus transfer time. The recordings are added separately.', 'IELTS', 30, 40, 1, 'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_L_004');

INSERT INTO questions (test_id, question_number, question_text, question_type, part_number, display_order)
SELECT t.id, d.qn, d.txt, d.qtype, d.pn, d.qn
FROM tests t,
(
  SELECT 1 qn, 'Library: Help with ___ books (times to be arranged)' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 2 qn, 'Library: Help needed to keep ___ of books up to date' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 3 qn, 'Library: Library is in the ___ Room in the village hall' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 4 qn, 'Lunch club: Help by providing ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 5 qn, 'Lunch club: Help with hobbies such as ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 6 qn, 'Help for individuals needed next week: Taking Mrs Carroll to ___' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 7 qn, 'Help for individuals needed next week: Work in the ___ at Mr Selsbury''s house' txt, 'form_note_completion' qtype, 1 pn
  UNION ALL SELECT 8 qn, 'Village social events - 19 Oct | ___ | Village hall | providing refreshments' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 9 qn, '18 Nov | dance | Village hall | checking ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 10 qn, '31 Dec | New Year''s Eve party | Mountfort Hotel | designing the ___' txt, 'table_completion' qtype, 1 pn
  UNION ALL SELECT 11 qn, 'Many past owners made changes to' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 12 qn, 'Sir Edward Downes built Oniton Hall because he wanted' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 13 qn, 'Visitors can learn about the work of servants in the past from' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 14 qn, 'What is new for children at Oniton Hall?' txt, 'multiple_choice_single' qtype, 2 pn
  UNION ALL SELECT 15 qn, 'Locations on the farm: dairy' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 16 qn, 'Locations on the farm: large barn' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 17 qn, 'Locations on the farm: small barn' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 18 qn, 'Locations on the farm: stables' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 19 qn, 'Locations on the farm: shed' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 20 qn, 'Locations on the farm: parkland' txt, 'matching' qtype, 2 pn
  UNION ALL SELECT 21 qn, 'Which TWO things do the students agree they need to include in their reviews of Romeo and Juliet? (Questions 21 and 22; either order)' txt, 'multiple_choice_multiple' qtype, 3 pn
  UNION ALL SELECT 22 qn, 'Which TWO things do the students agree they need to include in their reviews of Romeo and Juliet? (Questions 21 and 22; either order)' txt, 'multiple_choice_multiple' qtype, 3 pn
  UNION ALL SELECT 23 qn, 'Aspects of the production: the set' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 24 qn, 'Aspects of the production: the lighting' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 25 qn, 'Aspects of the production: the costume design' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 26 qn, 'Aspects of the production: the music' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 27 qn, 'Aspects of the production: the actors'' delivery' txt, 'matching' qtype, 3 pn
  UNION ALL SELECT 28 qn, 'The students think the story of Romeo and Juliet is still relevant for young people today because' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 29 qn, 'The students found watching Romeo and Juliet in another language' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 30 qn, 'Why do the students think Shakespeare''s plays have such international appeal?' txt, 'multiple_choice_single' qtype, 3 pn
  UNION ALL SELECT 31 qn, 'The Icelandic language has approximately ___ speakers' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 32 qn, 'has a ___ that is still growing' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 33 qn, 'has its own words for computer-based concepts, such as web browser and ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 34 qn, 'Young speakers are big users of digital technology, such as ___' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 35 qn, 'are becoming ___ very quickly' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 36 qn, 'are having discussions using only English while they are in the ___ at school' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 37 qn, 'are better able to identify the content of a ___ in English than Icelandic' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 38 qn, 'Technology and internet companies write very little in Icelandic because of how complicated its ___ is' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 39 qn, 'The Icelandic government is worried that young Icelanders may lose their ___ as Icelanders' txt, 'form_note_completion' qtype, 4 pn
  UNION ALL SELECT 40 qn, 'is worried about the consequences of children not being ___ in either Icelandic or English' txt, 'form_note_completion' qtype, 4 pn
) d
WHERE t.code = 'IELTS_PT_L_004'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_correct_answers (question_id, answer_text, is_alternative)
SELECT q.id, d.ans, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn, 'collecting' ans, 0 alt
  UNION ALL SELECT 2 qn, 'records' ans, 0 alt
  UNION ALL SELECT 3 qn, 'West' ans, 0 alt
  UNION ALL SELECT 4 qn, 'transport' ans, 0 alt
  UNION ALL SELECT 5 qn, 'art' ans, 0 alt
  UNION ALL SELECT 6 qn, 'hospital' ans, 0 alt
  UNION ALL SELECT 7 qn, 'garden' ans, 0 alt
  UNION ALL SELECT 8 qn, 'quiz' ans, 0 alt
  UNION ALL SELECT 9 qn, 'tickets' ans, 0 alt
  UNION ALL SELECT 10 qn, 'poster' ans, 0 alt
  UNION ALL SELECT 15 qn, 'D' ans, 0 alt
  UNION ALL SELECT 16 qn, 'C' ans, 0 alt
  UNION ALL SELECT 17 qn, 'G' ans, 0 alt
  UNION ALL SELECT 18 qn, 'A' ans, 0 alt
  UNION ALL SELECT 19 qn, 'E' ans, 0 alt
  UNION ALL SELECT 20 qn, 'F' ans, 0 alt
  UNION ALL SELECT 23 qn, 'D' ans, 0 alt
  UNION ALL SELECT 24 qn, 'C' ans, 0 alt
  UNION ALL SELECT 25 qn, 'A' ans, 0 alt
  UNION ALL SELECT 26 qn, 'E' ans, 0 alt
  UNION ALL SELECT 27 qn, 'F' ans, 0 alt
  UNION ALL SELECT 31 qn, '321,000' ans, 0 alt
  UNION ALL SELECT 31 qn, '321000' ans, 1 alt
  UNION ALL SELECT 32 qn, 'vocabulary' ans, 0 alt
  UNION ALL SELECT 33 qn, 'podcast' ans, 0 alt
  UNION ALL SELECT 34 qn, 'smartphones' ans, 0 alt
  UNION ALL SELECT 35 qn, 'bilingual' ans, 0 alt
  UNION ALL SELECT 36 qn, 'playground' ans, 0 alt
  UNION ALL SELECT 37 qn, 'picture' ans, 0 alt
  UNION ALL SELECT 38 qn, 'grammar' ans, 0 alt
  UNION ALL SELECT 39 qn, 'identity' ans, 0 alt
  UNION ALL SELECT 40 qn, 'fluent' ans, 0 alt
) d
WHERE t.code = 'IELTS_PT_L_004' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt, opt.cor, opt.ord
FROM questions q JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 11 qn, 'A' lbl, 'the gardens.' txt, 0 cor, 1 ord
  UNION ALL SELECT 11 qn, 'B' lbl, 'the house.' txt, 1 cor, 2 ord
  UNION ALL SELECT 11 qn, 'C' lbl, 'the farm.' txt, 0 cor, 3 ord
  UNION ALL SELECT 12 qn, 'A' lbl, 'a place for discussing politics.' txt, 0 cor, 1 ord
  UNION ALL SELECT 12 qn, 'B' lbl, 'a place to display his wealth.' txt, 0 cor, 2 ord
  UNION ALL SELECT 12 qn, 'C' lbl, 'a place for artists and writers.' txt, 1 cor, 3 ord
  UNION ALL SELECT 13 qn, 'A' lbl, 'audio guides.' txt, 0 cor, 1 ord
  UNION ALL SELECT 13 qn, 'B' lbl, 'photographs.' txt, 0 cor, 2 ord
  UNION ALL SELECT 13 qn, 'C' lbl, 'people in costume.' txt, 1 cor, 3 ord
  UNION ALL SELECT 14 qn, 'A' lbl, 'clothes for dressing up' txt, 0 cor, 1 ord
  UNION ALL SELECT 14 qn, 'B' lbl, 'mini tractors' txt, 1 cor, 2 ord
  UNION ALL SELECT 14 qn, 'C' lbl, 'the adventure playground' txt, 0 cor, 3 ord
  UNION ALL SELECT 21 qn, 'A' lbl, 'analysis of the text' txt, 0 cor, 1 ord
  UNION ALL SELECT 21 qn, 'B' lbl, 'a summary of the plot' txt, 0 cor, 2 ord
  UNION ALL SELECT 21 qn, 'C' lbl, 'a description of the theatre' txt, 0 cor, 3 ord
  UNION ALL SELECT 21 qn, 'D' lbl, 'a personal reaction' txt, 1 cor, 4 ord
  UNION ALL SELECT 21 qn, 'E' lbl, 'a reference to particular scenes' txt, 1 cor, 5 ord
  UNION ALL SELECT 22 qn, 'A' lbl, 'analysis of the text' txt, 0 cor, 1 ord
  UNION ALL SELECT 22 qn, 'B' lbl, 'a summary of the plot' txt, 0 cor, 2 ord
  UNION ALL SELECT 22 qn, 'C' lbl, 'a description of the theatre' txt, 0 cor, 3 ord
  UNION ALL SELECT 22 qn, 'D' lbl, 'a personal reaction' txt, 1 cor, 4 ord
  UNION ALL SELECT 22 qn, 'E' lbl, 'a reference to particular scenes' txt, 1 cor, 5 ord
  UNION ALL SELECT 28 qn, 'A' lbl, 'it illustrates how easily conflict can start.' txt, 0 cor, 1 ord
  UNION ALL SELECT 28 qn, 'B' lbl, 'it deals with problems that families experience.' txt, 1 cor, 2 ord
  UNION ALL SELECT 28 qn, 'C' lbl, 'it teaches them about relationships.' txt, 0 cor, 3 ord
  UNION ALL SELECT 29 qn, 'A' lbl, 'frustrating.' txt, 0 cor, 1 ord
  UNION ALL SELECT 29 qn, 'B' lbl, 'demanding.' txt, 0 cor, 2 ord
  UNION ALL SELECT 29 qn, 'C' lbl, 'moving.' txt, 1 cor, 3 ord
  UNION ALL SELECT 30 qn, 'A' lbl, 'The stories are exciting.' txt, 0 cor, 1 ord
  UNION ALL SELECT 30 qn, 'B' lbl, 'There are recognisable characters.' txt, 0 cor, 2 ord
  UNION ALL SELECT 30 qn, 'C' lbl, 'They can be interpreted in many ways.' txt, 1 cor, 3 ord
) opt ON opt.qn = q.question_number
WHERE t.code = 'IELTS_PT_L_004'
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

-- Verify: 40 questions each
SELECT t.code, COUNT(*) AS questions FROM tests t JOIN questions q ON q.test_id = t.id WHERE t.code IN ('IELTS_PT_L_003','IELTS_PT_L_004') GROUP BY t.code;
