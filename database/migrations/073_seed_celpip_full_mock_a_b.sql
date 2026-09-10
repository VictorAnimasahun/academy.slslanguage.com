-- ============================================================
-- Migration 073 — Seed CELPIP Full Mock Tests A & B
--
-- Real official CELPIP General Practice Tests (Paragon Testing
-- Enterprises), sourced from the "CelpipTeacherSupportPack" provided
-- by the instructor — every Listening/Reading question, option and
-- correct answer below is transcribed verbatim from the official PDFs
-- and cross-checked against the official answer keys.
--
-- Used as the two Mock Tests for the CELPIP Masterclass — 2 Month Plan
-- (course_id=13): Test A = Mock Exam 1 (end of Month 1), Test B = Mock
-- Exam 2 (end of Month 2). See migration 074 for the course_pacing_items
-- wiring.
--
-- Container tests: CELPIP_FULL_MOCK_A / CELPIP_FULL_MOCK_B.
-- Section tests:   CELPIP_FMA_L/R/W (+S, collation only, no DB content)
--                  CELPIP_FMB_L/R/W (+S)
--
-- All Listening/Reading questions are 'multiple_choice_single', scored
-- via question_options.is_correct — including the drop-down "choose the
-- best way to complete this sentence" items (Listening Parts 4 & 6) and
-- any fill-in-blank / matching items: all of these reduce to "pick one
-- of N labelled options", so no new question_type is needed — just a
-- different render (dropdown vs radio) at the PHP layer, keyed off the
-- same data.
--
-- Idempotent — every INSERT guarded. Run on LOCAL first, then LIVE.
-- ============================================================

-- ── Container + section tests ────────────────────────────────────────────
INSERT INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT d.code, d.title, d.description, 'CELPIP_General', d.category, d.duration, d.total_q, 1, d.is_section
FROM (
  SELECT 'CELPIP_FULL_MOCK_A' code, 'CELPIP Full Mock Test A' title, 'Official CELPIP General Practice Test A (Paragon Testing Enterprises) — all 4 skills.' description, 'Full' category, 155 duration, 80 total_q, 0 is_section
  UNION ALL SELECT 'CELPIP_FULL_MOCK_B', 'CELPIP Full Mock Test B', 'Official CELPIP General Practice Test B (Paragon Testing Enterprises) — all 4 skills.', 'Full', 155, 80, 0
  UNION ALL SELECT 'CELPIP_FMA_L', 'CELPIP Full Mock A — Listening', 'Practice Test A, Listening (6 parts, 38 questions).', 'Listening', 50, 38, 1
  UNION ALL SELECT 'CELPIP_FMA_R', 'CELPIP Full Mock A — Reading', 'Practice Test A, Reading (4 parts, 38 questions).', 'Reading', 55, 38, 1
  UNION ALL SELECT 'CELPIP_FMA_W', 'CELPIP Full Mock A — Writing', 'Practice Test A, Writing (2 tasks).', 'Writing', 53, 2, 1
  UNION ALL SELECT 'CELPIP_FMB_L', 'CELPIP Full Mock B — Listening', 'Practice Test B, Listening (6 parts, 38 questions).', 'Listening', 50, 38, 1
  UNION ALL SELECT 'CELPIP_FMB_R', 'CELPIP Full Mock B — Reading', 'Practice Test B, Reading (4 parts, 38 questions).', 'Reading', 55, 38, 1
  UNION ALL SELECT 'CELPIP_FMB_W', 'CELPIP Full Mock B — Writing', 'Practice Test B, Writing (2 tasks).', 'Writing', 53, 2, 1
) d
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = d.code);

INSERT INTO mock_exams (code, exam_type, title, description, total_duration_minutes)
SELECT d.code, 'CELPIP_General', d.title, d.description, 155
FROM (
  SELECT 'CELPIP_FULL_MOCK_A' code, 'CELPIP Full Mock Test A' title, 'Official CELPIP General Practice Test A — Mock Exam 1 for the CELPIP Masterclass.' description
  UNION ALL SELECT 'CELPIP_FULL_MOCK_B', 'CELPIP Full Mock Test B', 'Official CELPIP General Practice Test B — Mock Exam 2 for the CELPIP Masterclass.'
) d
WHERE NOT EXISTS (SELECT 1 FROM mock_exams WHERE code = d.code);

-- ============================================================
-- TEST A — LISTENING (38 Q, 6 Parts)
-- Part 1: Listening to Problem Solving — woman lost in a park, bus driver helps (Q1-8)
-- Part 2: Daily Life Conversation — coworker struggling to finish a work project (Q9-13)
-- Part 3: Listening for Information — interview on workplace perfectionism (Q14-19)
-- Part 4: Listening to a News Item — medical breakthrough / bionic eye (Q20-24)
-- Part 5: Listening to a Discussion (video) — conversation club considering growth (Q25-32)
-- Part 6: Listening for Viewpoints — prison reading program bill (Q33-38)
--
-- Known simplification: Part 1 Q1's 4 official answer options are PHOTOS, not
-- text (no per-option image assets exist for this) — rendered here as short
-- text descriptions of what each photo depicts; this preserves 4 uniquely
-- distinct choices without altering question validity or the correct answer.
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.instr, d.stim, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
    'You will hear a conversation between a woman and a man. The man is a bus driver and the woman is a passenger trying to get somewhere. [Track 1] What is the woman eventually hoping to find? (Note: the 4 official answer choices are photographs, not text — see options for a text description of each.)' txt,
    'Listen to the conversation (played once). Choose the best answer.' instr,
    'Listening Part 1: Listening to Problem Solving' stim, 1 part
  UNION ALL SELECT 2, 'What best describes the driver''s response?', NULL, NULL, 1
  UNION ALL SELECT 3, 'What will the woman probably do next?', NULL, NULL, 1
  UNION ALL SELECT 4, '[Track 2] How did the woman meet the man again?', NULL, NULL, 1
  UNION ALL SELECT 5, 'Which statement is true?', NULL, NULL, 1
  UNION ALL SELECT 6, 'How should the woman get to the playground?', NULL, NULL, 1
  UNION ALL SELECT 7, '[Track 3] Why does the bus driver tell the woman he is glad?', NULL, NULL, 1
  UNION ALL SELECT 8, 'Will the woman go to the playground?', NULL, NULL, 1

  UNION ALL SELECT 9, 'You will hear a conversation between two co-workers. The woman is having some challenges finishing a work project. Why was the man concerned?', 'Listen to the conversation (played once). Choose the best answer.', 'Listening Part 2: Listening to a Daily Life Conversation', 2
  UNION ALL SELECT 10, 'What did the man suggest?', NULL, NULL, 2
  UNION ALL SELECT 11, 'Why hasn''t the woman slept lately?', NULL, NULL, 2
  UNION ALL SELECT 12, 'Why can''t the woman ask for an extension?', NULL, NULL, 2
  UNION ALL SELECT 13, 'What is her husband doing to help her finish the reports?', NULL, NULL, 2

  UNION ALL SELECT 14, 'You will hear a conversation. A man is interviewing a woman about perfectionism in the workplace. What is the woman''s occupation?', 'Listen to the conversation (played once). Choose the best answer.', 'Listening Part 3: Listening for Information', 3
  UNION ALL SELECT 15, 'What does the woman say about perfectionists in her study?', NULL, NULL, 3
  UNION ALL SELECT 16, 'According to the woman, what can perfectionism in the workplace do?', NULL, NULL, 3
  UNION ALL SELECT 17, 'Why do perfectionists feel anxious?', NULL, NULL, 3
  UNION ALL SELECT 18, 'What does the woman suggest perfectionists should do?', NULL, NULL, 3
  UNION ALL SELECT 19, 'What is the focus of the woman''s next project?', NULL, NULL, 3

  UNION ALL SELECT 20, 'You will hear a news item about a unique medical procedure. The news item is about ___', 'Choose the best way to complete each statement.', 'Listening Part 4: Listening to a News Item', 4
  UNION ALL SELECT 21, 'Before the surgery, Dianne was unable ___', NULL, NULL, 4
  UNION ALL SELECT 22, 'With the prototype, Dianne won''t be able to ___', NULL, NULL, 4
  UNION ALL SELECT 23, 'Dianne''s prototype will soon be ___', NULL, NULL, 4
  UNION ALL SELECT 24, 'The two other patients ___', NULL, NULL, 4

  UNION ALL SELECT 25, 'You will watch a discussion between three people at a café who belong to a conversation club. What is the main topic being discussed?', 'Watch the video (played once). Choose the best answer.', 'Listening Part 5: Listening to a Discussion (video)', 5
  UNION ALL SELECT 26, 'In the discussion, what were the three speakers mainly doing?', NULL, NULL, 5
  UNION ALL SELECT 27, 'Which saying would the man in the sweater probably agree with?', NULL, NULL, 5
  UNION ALL SELECT 28, 'What best describes the woman''s attitude to Marta?', NULL, NULL, 5
  UNION ALL SELECT 29, 'Who must be the best speaker of French?', NULL, NULL, 5
  UNION ALL SELECT 30, 'What is definitely true of the club''s allophones?', NULL, NULL, 5
  UNION ALL SELECT 31, 'What do the woman and the man in the sweater disagree on?', NULL, NULL, 5
  UNION ALL SELECT 32, 'What does the man wearing short sleeves agree to do?', NULL, NULL, 5

  UNION ALL SELECT 33, 'You will hear a presentation about a controversial bill proposing to reduce jail time for prisoners. This is a story about a ___', 'Choose the best way to complete each statement.', 'Listening Part 6: Listening for Viewpoints', 6
  UNION ALL SELECT 34, 'Prisoners'' access to the program would depend on their ___', NULL, NULL, 6
  UNION ALL SELECT 35, 'The purpose of the project is to ___', NULL, NULL, 6
  UNION ALL SELECT 36, 'Critics argue that the project ___', NULL, NULL, 6
  UNION ALL SELECT 37, 'Chris Kendhi, who''s worked in prison libraries for 20 years, believes the project ___', NULL, NULL, 6
  UNION ALL SELECT 38, 'If the bill becomes law, it will ___', NULL, NULL, 6
) d
WHERE t.code = 'CELPIP_FMA_L'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'[Photo] An adult and child petting/feeding goats at a petting zoo (hay visible)' txt,0 correct,1 ord UNION ALL SELECT 1,'B','[Photo] A family seated at a table eating a meal (restaurant/dining scene)',0,2 UNION ALL SELECT 1,'C','[Photo] An empty outdoor playground with climbing structures, in a park',1,3 UNION ALL SELECT 1,'D','[Photo] A courtyard fountain surrounded by town buildings (town square)',0,4
  UNION ALL SELECT 2,'A','He isn''t interested.',0,1 UNION ALL SELECT 2,'B','He makes a guess.',1,2 UNION ALL SELECT 2,'C','He refuses to help.',0,3 UNION ALL SELECT 2,'D','He shows the way.',0,4
  UNION ALL SELECT 3,'A','ask another passenger',0,1 UNION ALL SELECT 3,'B','use her smartphone',0,2 UNION ALL SELECT 3,'C','look for the restaurant',1,3 UNION ALL SELECT 3,'D','go to the water fountain',0,4
  UNION ALL SELECT 4,'A','He was taking a walk in the park.',1,1 UNION ALL SELECT 4,'B','She was going back to his bus.',0,2 UNION ALL SELECT 4,'C','They were both lost in the park.',0,3 UNION ALL SELECT 4,'D','They were both looking at a sign.',0,4
  UNION ALL SELECT 5,'A','She was looking for her smartphone.',0,1 UNION ALL SELECT 5,'B','She walked to the far side of the park.',0,2 UNION ALL SELECT 5,'C','He helped her by using a smartphone.',1,3 UNION ALL SELECT 5,'D','He spent his break walking with her.',0,4
  UNION ALL SELECT 6,'A','Follow posted maps and signs.',0,1 UNION ALL SELECT 6,'B','Take the green bus in 5 minutes.',0,2 UNION ALL SELECT 6,'C','Walk to the far side of the park.',0,3 UNION ALL SELECT 6,'D','Get on the little red shuttle bus.',1,4
  UNION ALL SELECT 7,'A','because she does not seem angry',1,1 UNION ALL SELECT 7,'B','because she didn''t take the red bus',0,2 UNION ALL SELECT 7,'C','because his other riders aren''t upset',0,3 UNION ALL SELECT 7,'D','because his bus hasn''t broken down',0,4
  UNION ALL SELECT 8,'A','No, she''ll just get another bus home.',0,1 UNION ALL SELECT 8,'B','No, she''ll just visit the petting zoo.',0,2 UNION ALL SELECT 8,'C','Yes, and she''ll arrive there on time.',1,3 UNION ALL SELECT 8,'D','Yes, but she''ll arrive there a bit late.',0,4

  UNION ALL SELECT 9,'A','because the woman looked angry',0,1 UNION ALL SELECT 9,'B','because the woman looked unwell',1,2 UNION ALL SELECT 9,'C','because the woman looked sad',0,3 UNION ALL SELECT 9,'D','because the woman didn''t finish her work',0,4
  UNION ALL SELECT 10,'A','He suggested that the woman stay after work hours.',1,1 UNION ALL SELECT 10,'B','He suggested that the woman ask her husband for help.',0,2 UNION ALL SELECT 10,'C','He suggested that the woman take her work home.',0,3 UNION ALL SELECT 10,'D','He suggested that the woman ask her boss for help.',0,4
  UNION ALL SELECT 11,'A','because her son doesn''t sleep at night',0,1 UNION ALL SELECT 11,'B','because she has to do too much housework',0,2 UNION ALL SELECT 11,'C','because her husband doesn''t help her',0,3 UNION ALL SELECT 11,'D','because she has to meet a deadline',1,4
  UNION ALL SELECT 12,'A','because her boss doesn''t like her',0,1 UNION ALL SELECT 12,'B','because she''s not competent',0,2 UNION ALL SELECT 12,'C','because she''s hoping to get a promotion',1,3 UNION ALL SELECT 12,'D','because she already asked for one',0,4
  UNION ALL SELECT 13,'A','He''s taking the son away for the weekend.',0,1 UNION ALL SELECT 13,'B','He''s bringing the son to daycare on the weekend.',0,2 UNION ALL SELECT 13,'C','He''s working during the day too.',0,3 UNION ALL SELECT 13,'D','He''s taking a day off work to look after their son.',1,4

  UNION ALL SELECT 14,'A','She is a business manager.',0,1 UNION ALL SELECT 14,'B','She is a family doctor.',0,2 UNION ALL SELECT 14,'C','She is a researcher.',1,3 UNION ALL SELECT 14,'D','She is a journalist.',0,4
  UNION ALL SELECT 15,'A','They are always persevering and successful.',0,1 UNION ALL SELECT 15,'B','Their work is better because of their high standards.',0,2 UNION ALL SELECT 15,'C','They are productive in group environments.',0,3 UNION ALL SELECT 15,'D','They often fail to complete tasks at work.',1,4
  UNION ALL SELECT 16,'A','affect job stability and social life',1,1 UNION ALL SELECT 16,'B','help people with depression',0,2 UNION ALL SELECT 16,'C','help with making friendships',0,3 UNION ALL SELECT 16,'D','reduce people''s anxiety',0,4
  UNION ALL SELECT 17,'A','because of the stressful workplace',0,1 UNION ALL SELECT 17,'B','because they have insufficient friendships',0,2 UNION ALL SELECT 17,'C','because they have poor time management',1,3 UNION ALL SELECT 17,'D','because they have incompetent workmates',0,4
  UNION ALL SELECT 18,'A','Avoid working in groups.',0,1 UNION ALL SELECT 18,'B','Focus on details.',0,2 UNION ALL SELECT 18,'C','Prioritize tasks.',1,3 UNION ALL SELECT 18,'D','Take time off.',0,4
  UNION ALL SELECT 19,'A','time management skills and lowering anxiety',1,1 UNION ALL SELECT 19,'B','the role of social isolation on anxiety',0,2 UNION ALL SELECT 19,'C','the importance of friendships on anxiety',0,3 UNION ALL SELECT 19,'D','the role of incompetence on depression',0,4

  UNION ALL SELECT 20,'A','an Australian doctor',0,1 UNION ALL SELECT 20,'B','a medical breakthrough',1,2 UNION ALL SELECT 20,'C','an eye disease',0,3 UNION ALL SELECT 20,'D','a computer program',0,4
  UNION ALL SELECT 21,'A','to see with either eye',1,1 UNION ALL SELECT 21,'B','to see with one eye',0,2 UNION ALL SELECT 21,'C','to see colour',0,3 UNION ALL SELECT 21,'D','to see black and white',0,4
  UNION ALL SELECT 22,'A','see details',1,1 UNION ALL SELECT 22,'B','see movement',0,2 UNION ALL SELECT 22,'C','see shapes',0,3 UNION ALL SELECT 22,'D','see outlines',0,4
  UNION ALL SELECT 23,'A','activated',0,1 UNION ALL SELECT 23,'B','re-installed',0,2 UNION ALL SELECT 23,'C','monitored',0,3 UNION ALL SELECT 23,'D','replaced',1,4
  UNION ALL SELECT 24,'A','are doing well with their prototypes',0,1 UNION ALL SELECT 24,'B','have not yet received their prototypes',1,2 UNION ALL SELECT 24,'C','cannot afford their prototypes',0,3 UNION ALL SELECT 24,'D','didn''t adapt to their prototypes',0,4

  UNION ALL SELECT 25,'A','what to learn',0,1 UNION ALL SELECT 25,'B','where to meet',0,2 UNION ALL SELECT 25,'C','whether to grow',1,3 UNION ALL SELECT 25,'D','whom to invite',0,4
  UNION ALL SELECT 26,'A','considering their options',1,1 UNION ALL SELECT 26,'B','disputing a decision',0,2 UNION ALL SELECT 26,'C','estimating their numbers',0,3 UNION ALL SELECT 26,'D','sharing encouragement',0,4
  UNION ALL SELECT 27,'A','Hold hands and stick together.',0,1 UNION ALL SELECT 27,'B','Small is beautiful; less is more.',1,2 UNION ALL SELECT 27,'C','The more, the merrier; bigger is better.',0,3 UNION ALL SELECT 27,'D','Two''s company; three''s a crowd.',0,4
  UNION ALL SELECT 28,'A','admiration',1,1 UNION ALL SELECT 28,'B','envy',0,2 UNION ALL SELECT 28,'C','gratitude',0,3 UNION ALL SELECT 28,'D','scorn',0,4
  UNION ALL SELECT 29,'A','The woman in the video',0,1 UNION ALL SELECT 29,'B','The man wearing short sleeves',0,2 UNION ALL SELECT 29,'C','The woman they talked about',0,3 UNION ALL SELECT 29,'D','The man wearing a sweater',1,4
  UNION ALL SELECT 30,'A','They are all trying to improve their French.',0,1 UNION ALL SELECT 30,'B','They do not like serving as translators.',0,2 UNION ALL SELECT 30,'C','They lose interest while on waitlists.',0,3 UNION ALL SELECT 30,'D','They outnumber the other two groups.',1,4
  UNION ALL SELECT 31,'A','allophone skill levels',0,1 UNION ALL SELECT 31,'B','Colleen and Liam',0,2 UNION ALL SELECT 31,'C','keeping waitlists',1,3 UNION ALL SELECT 31,'D','Marta''s first language',0,4
  UNION ALL SELECT 32,'A','form a separate club for allophones',0,1 UNION ALL SELECT 32,'B','move the whole group to a bigger restaurant',0,2 UNION ALL SELECT 32,'C','organize everyone into groups of four',0,3 UNION ALL SELECT 32,'D','start additional meetups at a second location',1,4

  UNION ALL SELECT 33,'A','law that forces prisoners to read books',0,1 UNION ALL SELECT 33,'B','proposed law to reduce some prisoners'' sentences',1,2 UNION ALL SELECT 33,'C','a man who spent 36 days reading books in prison',0,3 UNION ALL SELECT 33,'D','project that teaches prisoners to read and write',0,4
  UNION ALL SELECT 34,'A','frequency at the prison''s library',0,1 UNION ALL SELECT 34,'B','crime and behaviour in jail',1,2 UNION ALL SELECT 34,'C','knowledge of grammar and spelling',0,3 UNION ALL SELECT 34,'D','previous reading level',0,4
  UNION ALL SELECT 35,'A','diminish conflict and promote personal growth',1,1 UNION ALL SELECT 35,'B','teach prisoners to read and write better',0,2 UNION ALL SELECT 35,'C','increase prisoners'' level of education',0,3 UNION ALL SELECT 35,'D','help prisoners to find jobs when they leave',0,4
  UNION ALL SELECT 36,'A','should focus more on recent literature',0,1 UNION ALL SELECT 36,'B','penalizes those with poor English language skills',1,2 UNION ALL SELECT 36,'C','creates conflict between the educated and uneducated',0,3 UNION ALL SELECT 36,'D','only helps prisoners find jobs if they''ve read Shakespeare',0,4
  UNION ALL SELECT 37,'A','will fail because prisoners are bitter',0,1 UNION ALL SELECT 37,'B','will help prisoners earn more money',0,2 UNION ALL SELECT 37,'C','will transform some prisoners'' lives',1,3 UNION ALL SELECT 37,'D','will make prison administrators optimistic',0,4
  UNION ALL SELECT 38,'A','apply to the whole province',1,1 UNION ALL SELECT 38,'B','apply to the whole country',0,2 UNION ALL SELECT 38,'C','be debated by the general public',0,3 UNION ALL SELECT 38,'D','be discussed by government branches',0,4
) d
WHERE t.code = 'CELPIP_FMA_L' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- TEST A — READING (38 Q, 4 Parts)
-- Part 1: Reading Correspondence — letter from Maria to Mea re: Marco's move to Tokyo (Q1-11)
-- Part 2: Reading to Apply a Diagram — Peter's email to Janice + Seattle travel-options table (Q12-19)
-- Part 3: Reading for Information — narwhal article, paragraph-matching A-E (Q20-28)
-- Part 4: Reading for Viewpoints — Bradley Gordon's exercise-integrated French class (Q29-38)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.instr, d.stim, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
   'Maria''s mother is now ___' txt,'Choose the best option according to the information given in the message.' instr,
   'Reading Part 1: Reading Correspondence — Hi Mea,

I''m sorry for taking so long to reply to your email. As you know, I''ve been very busy this summer with Marco''s university graduation and my family''s visit from Chile. The graduation ceremony was great! Too bad you guys couldn''t make it, but we understand it''s a bit of a drive from Calgary. You were truly missed. My Mum remembers you well from when we were kids. After the celebrations, we took the family sightseeing here in Vancouver. They loved it! They left yesterday for Victoria, and will catch a plane back home from there in three days.

Just when we thought things would slow down, Marco broke the news that he had accepted a job offer in Tokyo. He leaves at the end of the month! He was invited to work at a top engineering firm that specializes in rebuilding cities after large disasters. Needless to say, Marco is very excited about it. The firm has been really active in the reconstruction efforts following the 2011 earthquake in Japan, and it''s a great first step into his career, not to mention the opportunity to experience a new culture, and learn a new language. He''s a little apprehensive about communicating in Japanese but the firm has a translator and a tutor to help him.

Marco is over the moon, but Jack and I are having a tougher time with it. We think he''s so young to be so far away, but we know it''s for the best. Just the other day Jack and I were talking about that trip all of us took to Disneyland when the kids were little, do you remember that? It''s been 20 years! Time flies, doesn''t it? Well, get prepared, Cindy is next!

In any event, we are planning a farewell party for friends next weekend, and Marco cannot imagine not having you, Jason and Cindy there. The party will be at his apartment. He really does not want to leave without saying goodbye to you all. We hope you can make it, after all we haven''t seen you since last Christmas. Let me know so I can get the rooms ready.

We''ll be in touch,

Love,
Maria' stim,1 part
  UNION ALL SELECT 2,'In a few weeks, Maria''s son Marco will ___',NULL,NULL,1
  UNION ALL SELECT 3,'Marco is feeling ___',NULL,NULL,1
  UNION ALL SELECT 4,'Marco''s employers are ___',NULL,NULL,1
  UNION ALL SELECT 5,'Maria and Jack are worried about ___',NULL,NULL,1
  UNION ALL SELECT 6,'Mea and Maria ___',NULL,NULL,1
  UNION ALL SELECT 7,'Complete the reply from Mea below (fill-in-the-blank). "Hi Maria, This is such wonderful news! Count us in, we would hate to miss 7.______."',
   'Choose the best way to complete each blank in Mea''s reply, based on the reply letter.',
   'Reply letter — Hi Maria,

This is such wonderful news! Count us in, we would hate to miss 7.______. We''ll leave 8.______ at 6am Saturday morning. We''ll be there by early afternoon. That way if you need any help setting up the 9.______ you''ll have some extra help. Cindy is great with decorations.

Also, we want to give Marco a graduation gift. Initially we thought about a sofa for his Vancouver apartment, but I guess that 10.______. Do you have any suggestions? Does he have everything he needs for the 11.______. Winter clothes, perhaps?

Let me know, and see you on Saturday!

Love,
Mea',1
  UNION ALL SELECT 8,'8.______ (where will Mea and her family leave from?)',NULL,NULL,1
  UNION ALL SELECT 9,'9.______ (what are they setting up?)',NULL,NULL,1
  UNION ALL SELECT 10,'10.______ (why won''t they give Marco a sofa?)',NULL,NULL,1
  UNION ALL SELECT 11,'11.______ (what does Marco need to prepare for?)',NULL,NULL,1

  UNION ALL SELECT 12,'Complete Peter''s email to Janice below (fill-in-the-blank). "Buses are the 1.______ because the fleet is old and there are no stopovers."',
   'Choose the best way to complete each blank, based on the email and travel-options diagram.',
   'Reading Part 2: Reading to Apply a Diagram — Subject: Seattle conference presentation
To: Janice Wong <jwong@ubc.ca>
From: Peter Kull <pkull@ubc.ca>

Hi Janice,

You will find attached our presentation file. It''s ready! I''ve also done some research on travel. Remember, the conference is a week away and we haven''t decided how to get there yet. Here are our options. Buses are the 1.______ because the fleet is old and there are no stopovers. The train seems more relaxed and we have plenty of time to get some work done if needed. It 2.______ than I had expected, especially when compared to airfares. Plus, the 3.______ is far from the hotel. Flying would save us time, and the airport is close to the hotel and the university in case we want to visit Dr. Kitayama. Lastly, I really wouldn''t mind 4.______ My car is economical and would give us flexibility to 5.______ After all, we have both worked so hard on this project!

Best,
Peter

TRAVEL OPTIONS TABLE (Redwood City to Seattle):
TRAIN — first-class; scenic trip along the coast; free Wi-Fi internet. Price: $260 return ticket. Duration: 4 hr 25 min.
BUS — no checked baggage allowed; no washrooms, no stops; only morning trips to Seattle. Price: $100 return ticket. Duration: 3 hr 30 min.
PLANE — in-flight snack; free movie entertainment; airport close to town. Price: $240 return ticket. Duration: 45 min.
CAR — freedom to explore the city; no need to pay for cabs to and from hotel. Price: n/a (personal vehicle). Duration: 4 hours.',2
  UNION ALL SELECT 13,'2.______ (how does the train''s price compare to what Peter expected?)',NULL,NULL,2
  UNION ALL SELECT 14,'3.______ (what is far from the hotel?)',NULL,NULL,2
  UNION ALL SELECT 15,'4.______ (what does Peter say he wouldn''t mind doing?)',NULL,NULL,2
  UNION ALL SELECT 16,'5.______ (what would the car give them flexibility to do?)',NULL,NULL,2
  UNION ALL SELECT 17,'Peter and Janice ___',NULL,NULL,2
  UNION ALL SELECT 18,'The main purpose of the trip is ___',NULL,NULL,2
  UNION ALL SELECT 19,'Peter seems ___',NULL,NULL,2

  UNION ALL SELECT 29,'This article is about ___','Choose the best option according to the information given on the website.',
   'Reading Part 4: Reading for Viewpoints — Visitors walking through Carleton High School are often surprised when they pass Bradley Gordon''s French class and see students riding on exercise bikes and sitting on yoga balls. Is it a French class, or is it a gym class? Well, it''s a bit of both!

Two major concerns in education are childhood obesity and ADHD, or Attention Deficit Hyperactivity Disorder, a disorder that results in restlessness, hyperactivity and impulsivity. With high rates of obesity and also students struggling with ADHD in classes across the country, Mr. Gordon came up with an innovative intervention to address both. He decided to infuse academic studies with physical activity in his own classroom.

The idea came to Gordon after a personal experience in university while working on his bachelor''s degree. "I didn''t have time to schedule a separate slot for exercise, and my health deteriorated rapidly," said Gordon. "After feeling sick and fatigued for months, I decided to couple my studying with my workouts. To my surprise it proved incredibly helpful. My grades started improving and so did my overall fitness and health."

Gordon implemented the approach with his students to great effect last year. Despite the students'' excitement and academic improvement, he met resistance from the school''s principal, Dawn Epstein, who was not convinced that academics and physical exercise should be amalgamated. "Although exercise is certainly important, I didn''t think it had any place in academics. I assumed exercise would exacerbate ADHD", Epstein asserted.

It turns out Mr. Epstein''s reaction is a common misconception. As Dr. John Ratney, psychiatry professor at Harvard Medical School, explains: "Exercise turns the attention system on, and helps with working memory, prioritizing and sustaining attention". Sustained physical exertion causes kids to be less impulsive and more prone to learn. That''s precisely what Mr. Gordon found a year into the intervention. "My students'' endurance, both physical and mental, has improved. They are fit, and eager to learn. Even students diagnosed with ADHD have displayed less physical agitation, which has helped them to learn better". So, when you walk by Mr Gordon''s class and see bikes spinning, know that minds are at work.

(Note: source text prints "Mr. Epstein" in one place referring to principal Dawn Epstein — preserved verbatim as printed in the official material.)',4
  UNION ALL SELECT 30,'Mr. Gordon''s intervention ___',NULL,NULL,4
  UNION ALL SELECT 31,'The intervention was not ___',NULL,NULL,4
  UNION ALL SELECT 32,'According to Dr. Ratney, exercising ___',NULL,NULL,4
  UNION ALL SELECT 33,'At the one-year mark, the intervention ___',NULL,NULL,4
  UNION ALL SELECT 34,'Complete the reader comment below (fill-in-the-blank). "I''ve been a high school teacher for fifteen years and I am quite dubious of Mr. Gordon''s attempt to 6.______."',
   'Choose the best way to complete each blank, based on the reader comment.',
   'Reader comment — Interesting article! I''ve been a high school teacher for fifteen years and I am quite dubious of Mr. Gordon''s attempt to 6.______. Although Dr. Ratney claims that this project 7.______, I often find the opposite is true. Indeed, it is the students that play soccer or basketball at lunchtime that 8.______ in the afternoon. It would be a mistake to 9.______ prior to the end of a busy school day. There is also the question of time management. It simply is not possible to get through the curriculum while the students 10.______.',4
  UNION ALL SELECT 35,'7.______ (what does Dr. Ratney claim the project will do?)',NULL,NULL,4
  UNION ALL SELECT 36,'8.______ (what happens to students who play sports at lunchtime?)',NULL,NULL,4
  UNION ALL SELECT 37,'9.______ (what would be a mistake?)',NULL,NULL,4
  UNION ALL SELECT 38,'10.______ (what makes it hard to get through the curriculum?)',NULL,NULL,4
) d
WHERE t.code = 'CELPIP_FMA_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

-- Part 3 (Q20-28): paragraph-matching, question_type='matching' — same convention as
-- migration 017 (IELTS FM1 Reading Q1-6): shared A-E legend, scored via
-- question_correct_answers, options duplicated here for the on-page legend box.
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'matching', d.txt, d.instr, d.stim, 3, 1.0, d.qn
FROM tests t,
(
  SELECT 20 qn,'There are physical differences between Narwhal males and females.' txt,
   'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.' instr,
   'Reading Part 3: Reading for Information —
A. The narwhal is an endangered type of whale found in the frigid waters of the Arctic, extending from Canada through the Norwegian waters to Russia. Narwhal means "corpse whale", and it has earned its name because of its mottled dark grey colour. Narwhals are regularly harvested for meat and ivory in northern Canada and Greenland. Narwhals share physical characteristics with Beluga whales, having similar shape and size. Both species lack dorsal fins, have short beaks, rounded heads and a thick layer of blubber to adapt to glacial conditions.

B. The narwhals'' distinctive characteristic lies in the presence of a long 2.5 meter spiraling tusk that protrudes from the males'' foreheads, resembling a unicorn. The horn-like formation, however, is a long left tooth. The right tooth remains embedded in the skull and measures roughly 30 centimeters. Female tusks have a more regularly defined morphology. They are much shorter, straighter, and do not collect as much algae on the surface, thus appearing whiter.

C. For hundreds of years the purpose of the tusk on the "unicorn whale" has puzzled scientists and local aboriginal elders alike. A northern aboriginal legend explains the narwhal''s tusk was created when a woman shooting with a harpoon rope was dragged into the ocean after the harpoon had struck a large narwhal whale. She then transformed into a narwhal herself, and her hair, which was long and twisted, became the characteristic of the spiral tusk. In academic circles, the tusk remains an evolutionary mystery that defies many of the known principles of mammalian teeth. Preliminary studies suggest the tusk enables whales to determine salinity levels and allows them to detect food in their environment.

D. Narwhal behaviour also intrigues researchers. Males frequently engage in episodes of rubbing their tusks together, or "tusking," for as-yet unknown reasons. The same behaviour is not observed in the female counterparts or between females and males. Some studies theorize about the possibility of these being mating behaviours aimed at displaying genetic superiority. Support for such a theory, however, has proved scarce since unlike other mammalian species the behaviour is not aggressive. Narwhals are a migratory species. In an attempt to learn about their migration patterns and social behavior, their populations are being observed and recorded through satellite tracking conducted by scientists in Canada and Greenland.' stim
  UNION ALL SELECT 21,'Narwhals are also referred to as death-like.',NULL,NULL
  UNION ALL SELECT 22,'There are aspects of narwhal''s anatomy that remain unexplained.',NULL,NULL
  UNION ALL SELECT 23,'Narwhals present puzzling social interactions.',NULL,NULL
  UNION ALL SELECT 24,'Narwhal whales are connected to Canadian aboriginal folklore.',NULL,NULL
  UNION ALL SELECT 25,'There is a sound understanding of narwhals'' mating behaviour.',NULL,NULL
  UNION ALL SELECT 26,'Different countries are documenting narwhal behaviour patterns.',NULL,NULL
  UNION ALL SELECT 27,'The narwhal population is in jeopardy.',NULL,NULL
  UNION ALL SELECT 28,'Narwhals'' physical characteristics are also observed in other whale groups.',NULL,NULL
) d
WHERE t.code = 'CELPIP_FMA_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt,
  CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 20 qn,'B' letter UNION ALL SELECT 21,'A' UNION ALL SELECT 22,'C' UNION ALL SELECT 23,'D'
  UNION ALL SELECT 24,'C' UNION ALL SELECT 25,'E' UNION ALL SELECT 26,'D' UNION ALL SELECT 27,'A' UNION ALL SELECT 28,'A'
) ans ON ans.qn = q.question_number,
(
  SELECT 'A' lbl,'Paragraph A — narwhal identification, naming ("corpse whale"), harvesting, and shared traits with Beluga whales' txt,1 ord
  UNION ALL SELECT 'B','Paragraph B — the tusk itself: a spiraling left tooth up to 2.5m; male vs. female tusk differences',2
  UNION ALL SELECT 'C','Paragraph C — the tusk''s unexplained purpose: aboriginal legend and the ongoing scientific mystery',3
  UNION ALL SELECT 'D','Paragraph D — narwhal behaviour ("tusking") and satellite tracking of migration by Canada and Greenland',4
  UNION ALL SELECT 'E','Not given in any of the paragraphs',5
) opt
WHERE t.code = 'CELPIP_FMA_R' AND q.question_number BETWEEN 20 AND 28
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 20 qn,'B' ans,0 alt UNION ALL SELECT 20,'b',1
  UNION ALL SELECT 21,'A',0 UNION ALL SELECT 21,'a',1
  UNION ALL SELECT 22,'C',0 UNION ALL SELECT 22,'c',1
  UNION ALL SELECT 23,'D',0 UNION ALL SELECT 23,'d',1
  UNION ALL SELECT 24,'C',0 UNION ALL SELECT 24,'c',1
  UNION ALL SELECT 25,'E',0 UNION ALL SELECT 25,'e',1
  UNION ALL SELECT 26,'D',0 UNION ALL SELECT 26,'d',1
  UNION ALL SELECT 27,'A',0 UNION ALL SELECT 27,'a',1
  UNION ALL SELECT 28,'A',0 UNION ALL SELECT 28,'a',1
) d
WHERE t.code = 'CELPIP_FMA_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'in Chile' txt,0 correct,1 ord UNION ALL SELECT 1,'B','in Calgary',0,2 UNION ALL SELECT 1,'C','in Vancouver',0,3 UNION ALL SELECT 1,'D','in Victoria',1,4
  UNION ALL SELECT 2,'A','graduate from university',0,1 UNION ALL SELECT 2,'B','visit his family in Japan',0,2 UNION ALL SELECT 2,'C','start a new job',1,3 UNION ALL SELECT 2,'D','visit Mea and her family',0,4
  UNION ALL SELECT 3,'A','unsure about the job',0,1 UNION ALL SELECT 3,'B','happy about leaving his parents',0,2 UNION ALL SELECT 3,'C','nervous about speaking Japanese',1,3 UNION ALL SELECT 3,'D','sad about moving to Japan',0,4
  UNION ALL SELECT 4,'A','not supportive of his inexperience',0,1 UNION ALL SELECT 4,'B','doing little for Marco''s adaptation',0,2 UNION ALL SELECT 4,'C','specialists in town reconstruction',1,3 UNION ALL SELECT 4,'D','inexperienced with disasters',0,4
  UNION ALL SELECT 5,'A','Marco being in an earthquake',0,1 UNION ALL SELECT 5,'B','Marco not speaking the language',0,2 UNION ALL SELECT 5,'C','never seeing Marco again',0,3 UNION ALL SELECT 5,'D','Marco''s lack of life experience',1,4
  UNION ALL SELECT 6,'A','are neighbours',0,1 UNION ALL SELECT 6,'B','are cousins',0,2 UNION ALL SELECT 6,'C','are co-workers',0,3 UNION ALL SELECT 6,'D','are old friends',1,4
  UNION ALL SELECT 7,'A','the leaving party.',1,1 UNION ALL SELECT 7,'B','the graduation.',0,2 UNION ALL SELECT 7,'C','the trip.',0,3 UNION ALL SELECT 7,'D','the flight.',0,4
  UNION ALL SELECT 8,'A','Vancouver',0,1 UNION ALL SELECT 8,'B','Victoria',0,2 UNION ALL SELECT 8,'C','Chile',0,3 UNION ALL SELECT 8,'D','Calgary',1,4
  UNION ALL SELECT 9,'A','trip,',0,1 UNION ALL SELECT 9,'B','party,',1,2 UNION ALL SELECT 9,'C','move,',0,3 UNION ALL SELECT 9,'D','graduation,',0,4
  UNION ALL SELECT 10,'A','he can''t use that now.',1,1 UNION ALL SELECT 10,'B','it''s too big for the apartment.',0,2 UNION ALL SELECT 10,'C','it''s too expensive for us.',0,3 UNION ALL SELECT 10,'D','he would prefer a bed.',0,4
  UNION ALL SELECT 11,'A','graduation ceremony?',0,1 UNION ALL SELECT 11,'B','Vancouver apartment?',0,2 UNION ALL SELECT 11,'C','move to Tokyo?',1,3 UNION ALL SELECT 11,'D','visit to Disneyland?',0,4

  UNION ALL SELECT 12,'A','most desirable',0,1 UNION ALL SELECT 12,'B','most flexible',0,2 UNION ALL SELECT 12,'C','least comfortable',1,3 UNION ALL SELECT 12,'D','least effective',0,4
  UNION ALL SELECT 13,'A','is priced lower',0,1 UNION ALL SELECT 13,'B','costs more',1,2 UNION ALL SELECT 13,'C','is less convenient',0,3 UNION ALL SELECT 13,'D','seems slower',0,4
  UNION ALL SELECT 14,'A','airport',0,1 UNION ALL SELECT 14,'B','station',1,2 UNION ALL SELECT 14,'C','parking lot',0,3 UNION ALL SELECT 14,'D','stop',0,4
  UNION ALL SELECT 15,'A','cabbing.',0,1 UNION ALL SELECT 15,'B','flying.',0,2 UNION ALL SELECT 15,'C','driving.',1,3 UNION ALL SELECT 15,'D','taking the bus.',0,4
  UNION ALL SELECT 16,'A','commute.',0,1 UNION ALL SELECT 16,'B','go sightseeing.',1,2 UNION ALL SELECT 16,'C','go to the conference.',0,3 UNION ALL SELECT 16,'D','go to the hotel.',0,4
  UNION ALL SELECT 17,'A','work together.',1,1 UNION ALL SELECT 17,'B','are friends.',0,2 UNION ALL SELECT 17,'C','are neighbours.',0,3 UNION ALL SELECT 17,'D','live together.',0,4
  UNION ALL SELECT 18,'A','to attend a business meeting.',0,1 UNION ALL SELECT 18,'B','to visit a tourist attraction.',0,2 UNION ALL SELECT 18,'C','to speak at a conference.',1,3 UNION ALL SELECT 18,'D','to visit a doctor.',0,4
  UNION ALL SELECT 19,'A','sympathetic.',0,1 UNION ALL SELECT 19,'B','apathetic.',0,2 UNION ALL SELECT 19,'C','cooperative.',1,3 UNION ALL SELECT 19,'D','unhelpful.',0,4

  UNION ALL SELECT 29,'A','a conventional high school teacher with an unconventional idea.',1,1 UNION ALL SELECT 29,'B','an ADHD expert teacher in Carleton High School.',0,2 UNION ALL SELECT 29,'C','a partnership between a gym and a French teacher.',0,3 UNION ALL SELECT 29,'D','a program introduced by Carleton High School''s principal.',0,4
  UNION ALL SELECT 30,'A','is part of a medical treatment for overweight children with ADHD.',0,1 UNION ALL SELECT 30,'B','was inspired by his own successful experience as a student.',1,2 UNION ALL SELECT 30,'C','was designed to make students lose weight in one year.',0,3 UNION ALL SELECT 30,'D','diminished exercising to focus on academic performance.',0,4
  UNION ALL SELECT 31,'A','initially well received by the school principal.',1,1 UNION ALL SELECT 31,'B','supported by medical research professionals.',0,2 UNION ALL SELECT 31,'C','appealing to his high school students.',0,3 UNION ALL SELECT 31,'D','conducted in the students'' educational setting.',0,4
  UNION ALL SELECT 32,'A','increase physical agitation and diminishes attention.',0,1 UNION ALL SELECT 32,'B','diminishes hyperactivity and increases attention.',1,2 UNION ALL SELECT 32,'C','increases ADHD symptoms, especially impulsivity.',0,3 UNION ALL SELECT 32,'D','is more important than sustaining attention.',0,4
  UNION ALL SELECT 33,'A','produced the results Mr. Gordon expected.',1,1 UNION ALL SELECT 33,'B','confirmed Mr. Epstein''s initial reaction.',0,2 UNION ALL SELECT 33,'C','contradicted Professor Ratney''s expectations.',0,3 UNION ALL SELECT 33,'D','yielded undesirable effects on Mr. Gordon''s students.',0,4
  UNION ALL SELECT 34,'A','reduce the weight of his students.',0,1 UNION ALL SELECT 34,'B','bring exercise into the classroom.',1,2 UNION ALL SELECT 34,'C','develop a cure for ADHD.',0,3 UNION ALL SELECT 34,'D','finish university while teaching.',0,4
  UNION ALL SELECT 35,'A','was implemented at Harvard for a year,',0,1 UNION ALL SELECT 35,'B','was more important than academic study,',0,2 UNION ALL SELECT 35,'C','will improve students'' concentration,',1,3 UNION ALL SELECT 35,'D','will reduce unpremeditated aggression,',0,4
  UNION ALL SELECT 36,'A','are most likely to fade',1,1 UNION ALL SELECT 36,'B','are the best students',0,2 UNION ALL SELECT 36,'C','will exercise more',0,3 UNION ALL SELECT 36,'D','have the best behaviour',0,4
  UNION ALL SELECT 37,'A','limit students'' diet',0,1 UNION ALL SELECT 37,'B','overwork the students',1,2 UNION ALL SELECT 37,'C','provide too many assignments',0,3 UNION ALL SELECT 37,'D','distract youths with ADHD',0,4
  UNION ALL SELECT 38,'A','are too unfit to focus properly.',0,1 UNION ALL SELECT 38,'B','are bouncing around on yoga balls.',1,2 UNION ALL SELECT 38,'C','really need to get more exercise.',0,3 UNION ALL SELECT 38,'D','don''t have time to do the assignments.',0,4
) d
WHERE t.code = 'CELPIP_FMA_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- TEST A — WRITING (2 tasks, essay-type, manual/AI-graded)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'essay', d.txt, d.instr, NULL, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 1 part,
   'Task 1: Writing an Email — You recently made reservations for dinner at a very famous and expensive restaurant in town. However, the meal and the service were terrible. The restaurant manager was not available to solve the problem, so you left without a resolution. Write an email to the restaurant manager. Your email should: state what problems you had with the food you ordered; complain about the service; describe how you want the restaurant to resolve the problem to your satisfaction.' txt,
   'You have 27 minutes to complete this task. Write about 150-200 words.' instr
  UNION ALL SELECT 2, 2,
   'Task 2: Responding to Survey Questions — City Development Survey. You live in a small town of 10,000 people. A large green area in the centre of town is undeveloped. The city has sent out an opinion survey to see what residents would like to have built in that area. Option A: Shopping Complex (restaurants, a large supermarket, and a movie theatre). Option B: Recreational Park (a sports complex, a large green area, and a small petting zoo). Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice.',
   'You have 26 minutes to complete this task. Write about 150-200 words.'
) d
WHERE t.code = 'CELPIP_FMA_W'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

-- ============================================================
-- TEST B — LISTENING (38 Q, 6 Parts)
-- Part 1: Listening to Problem Solving — temp worker Matthew's first day at an office (Q1-8)
-- Part 2: Daily Life Conversation — night-shift coworkers in the break room (Q9-13)
-- Part 3: Daily Life Conversation — customer buying pillows at a bedding store (Q14-19)
-- Part 4: Listening to a News Item — community power-line incident, Inesh Chandra (Q20-24)
-- Part 5: Listening to a Discussion (video) — Anna/Paul/George, office relocation (Q25-32)
-- Part 6: Listening for Viewpoints — Kathy Chen vs. Ralph Greenman on advertising to children (Q33-38)
--
-- Known simplification: Part 1 Q2's 4 official answer options are PHOTOS, not
-- text — rendered here as short text descriptions of what each photo depicts.
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.instr, d.stim, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
    'You will hear a conversation between a man and a woman. The conversation takes place in an office. [Track 1] Which word best describes the man''s task?' txt,
    'Listen to the conversation (played once). Choose the best answer.' instr,
    'Listening Part 1: Listening to Problem Solving' stim, 1 part
  UNION ALL SELECT 2, 'What is at the man''s workstation? (Note: the 4 official answer choices are photographs, not text — see options for a text description of each.)', NULL, NULL, 1
  UNION ALL SELECT 3, '[Track 2] What is the man''s role in the company?', NULL, NULL, 1
  UNION ALL SELECT 4, 'What does the man intend to do at lunchtime?', NULL, NULL, 1
  UNION ALL SELECT 5, 'What can we tell from this conversation?', NULL, NULL, 1
  UNION ALL SELECT 6, '[Track 3] What is the man''s response to the woman''s announcement?', NULL, NULL, 1
  UNION ALL SELECT 7, 'What does this part of the conversation imply?', NULL, NULL, 1
  UNION ALL SELECT 8, 'Why does the woman tell him to find her later?', NULL, NULL, 1

  UNION ALL SELECT 9, 'You are about to hear a conversation between two colleagues, a man and a woman. They are in the break room at work. What are the man and woman talking about?', 'Listen to the conversation (played once). Choose the best answer.', 'Listening Part 2: Listening to a Daily Life Conversation', 2
  UNION ALL SELECT 10, 'Which statement is most likely true?', NULL, NULL, 2
  UNION ALL SELECT 11, 'What does the man miss?', NULL, NULL, 2
  UNION ALL SELECT 12, 'Why does the man ask if the woman drives or takes the bus?', NULL, NULL, 2
  UNION ALL SELECT 13, 'What does the man do at the end to help the woman?', NULL, NULL, 2

  UNION ALL SELECT 14, 'You are about to hear a conversation between a customer and a worker at a mattress and bedding store. Why does the woman want to buy new pillows?', 'Listen to the conversation (played once). Choose the best answer.', 'Listening Part 3: Listening to a Daily Life Conversation', 3
  UNION ALL SELECT 15, 'Why did the woman have a buckwheat pillow in the past?', NULL, NULL, 3
  UNION ALL SELECT 16, 'According to the man, which of these is true of buckwheat?', NULL, NULL, 3
  UNION ALL SELECT 17, 'According to the man, which of these is true of memory foam?', NULL, NULL, 3
  UNION ALL SELECT 18, 'Which of these options best describes the man''s opinion?', NULL, NULL, 3
  UNION ALL SELECT 19, 'What will likely happen next?', NULL, NULL, 3

  UNION ALL SELECT 20, 'You will hear a news item about a community power issue. In her backyard, Inesh Chandra found ___', 'Choose the best way to complete each statement.', 'Listening Part 4: Listening to a News Item', 4
  UNION ALL SELECT 21, 'Chandra''s first clue that something was wrong was ___', NULL, NULL, 4
  UNION ALL SELECT 22, 'Mayor Pine implied that community members will have to ___', NULL, NULL, 4
  UNION ALL SELECT 23, 'Mayor Pine''s comments suggest that he is ___', NULL, NULL, 4
  UNION ALL SELECT 24, 'The news item ends with ___', NULL, NULL, 4

  UNION ALL SELECT 25, 'You will watch a discussion among three people. One woman, Anna, and two men, Paul and George, are on break at work. What did management announce to the employees?', 'Watch the video (played once). Choose the best answer.', 'Listening Part 5: Listening to a Discussion (video)', 5
  UNION ALL SELECT 26, 'What would Paul, the man on the right, have done if he''d known about management''s decision sooner?', NULL, NULL, 5
  UNION ALL SELECT 27, 'What happens if Anna is running late after work?', NULL, NULL, 5
  UNION ALL SELECT 28, 'What does Paul, the man on the right, believe?', NULL, NULL, 5
  UNION ALL SELECT 29, 'What does Anna believe?', NULL, NULL, 5
  UNION ALL SELECT 30, 'What do they all agree on?', NULL, NULL, 5
  UNION ALL SELECT 31, 'What was Paul trying to convince Anna to do?', NULL, NULL, 5
  UNION ALL SELECT 32, 'What is Anna hoping to achieve by speaking to management?', NULL, NULL, 5

  UNION ALL SELECT 33, 'You will hear a presentation about advertising and children. Kathy Chen is responsible for ___', 'Choose the best way to complete each statement.', 'Listening Part 6: Listening for Viewpoints', 6
  UNION ALL SELECT 34, 'Kathy Chen is ___', NULL, NULL, 6
  UNION ALL SELECT 35, 'Ralph Greenman believes that ___', NULL, NULL, 6
  UNION ALL SELECT 36, 'Greenman argues that advertising children''s products ___', NULL, NULL, 6
  UNION ALL SELECT 37, 'Greenman believes that ___', NULL, NULL, 6
  UNION ALL SELECT 38, 'Chen believes that ___', NULL, NULL, 6
) d
WHERE t.code = 'CELPIP_FMB_L'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'boring' txt,1 correct,1 ord UNION ALL SELECT 1,'B','exciting',0,2 UNION ALL SELECT 1,'C','difficult',0,3 UNION ALL SELECT 1,'D','enjoyable',0,4
  UNION ALL SELECT 2,'A','[Photo] A yellow sponge on a black background',0,1 UNION ALL SELECT 2,'B','[Photo] Two stacks/sets of open cardboard boxes (4 boxes total)',1,2 UNION ALL SELECT 2,'C','[Photo] A clear glass cup of water on a small glass saucer',0,3 UNION ALL SELECT 2,'D','[Photo] A black office desk telephone with keypad and display screen',0,4
  UNION ALL SELECT 3,'A','He is a temporary worker.',1,1 UNION ALL SELECT 3,'B','He is a new full-time employee.',0,2 UNION ALL SELECT 3,'C','He is a Human Resources officer?',0,3 UNION ALL SELECT 3,'D','He is the woman''s colleague.',0,4
  UNION ALL SELECT 4,'A','ask about job openings',1,1 UNION ALL SELECT 4,'B','have his lunch in the break room',0,2 UNION ALL SELECT 4,'C','report his progress to the woman',0,3 UNION ALL SELECT 4,'D','keep working at his task',0,4
  UNION ALL SELECT 5,'A','Temporary work can be a stepping stone.',1,1 UNION ALL SELECT 5,'B','It is better to work without breaks.',0,2 UNION ALL SELECT 5,'C','Temporary work is better than full-time work.',0,3 UNION ALL SELECT 5,'D','The woman works at the Human Resources department.',0,4
  UNION ALL SELECT 6,'A','relief',1,1 UNION ALL SELECT 6,'B','anxiety',0,2 UNION ALL SELECT 6,'C','confusion',0,3 UNION ALL SELECT 6,'D','disinterest',0,4
  UNION ALL SELECT 7,'A','There are others working near Matthew.',1,1 UNION ALL SELECT 7,'B','Matthew is unlikely to finish his task.',0,2 UNION ALL SELECT 7,'C','Matthew will not listen to music.',0,3 UNION ALL SELECT 7,'D','Human Resources is closed today.',0,4
  UNION ALL SELECT 8,'A','She wants to tell him he may use her as a reference.',1,1 UNION ALL SELECT 8,'B','She wants to confirm that he''ll return the next day.',0,2 UNION ALL SELECT 8,'C','She would like to pay him for his work.',0,3 UNION ALL SELECT 8,'D','She would like to get her radio back.',0,4

  UNION ALL SELECT 9,'A','problems working the night shift',1,1 UNION ALL SELECT 9,'B','their duties as night cleaners',0,2 UNION ALL SELECT 9,'C','good articles they have read',0,3 UNION ALL SELECT 9,'D','the loud noise at work',0,4
  UNION ALL SELECT 10,'A','The man is not married.',1,1 UNION ALL SELECT 10,'B','The woman is not married.',0,2 UNION ALL SELECT 10,'C','The man drives to work.',0,3 UNION ALL SELECT 10,'D','The woman operates machinery.',0,4
  UNION ALL SELECT 11,'A','riding the bus',0,1 UNION ALL SELECT 11,'B','drinking coffee',0,2 UNION ALL SELECT 11,'C','going grocery shopping',0,3 UNION ALL SELECT 11,'D','socializing with his friends',1,4
  UNION ALL SELECT 12,'A','He needs a ride home.',0,1 UNION ALL SELECT 12,'B','He wants to offer her a ride.',0,2 UNION ALL SELECT 12,'C','He wants to make a suggestion.',1,3 UNION ALL SELECT 12,'D','He is making small talk.',0,4
  UNION ALL SELECT 13,'A','drives her home',0,1 UNION ALL SELECT 13,'B','talks to the bus driver',0,2 UNION ALL SELECT 13,'C','fixes the chair for her',0,3 UNION ALL SELECT 13,'D','makes her a cup of coffee',1,4

  UNION ALL SELECT 14,'A','She wants to get herself a gift.',0,1 UNION ALL SELECT 14,'B','She wants a new feather pillow.',0,2 UNION ALL SELECT 14,'C','Her husband needs a new pillow.',1,3 UNION ALL SELECT 14,'D','Her husband has shoulder pain.',0,4
  UNION ALL SELECT 15,'A','She liked it better than other pillows.',0,1 UNION ALL SELECT 15,'B','She needed it due to an injury.',1,2 UNION ALL SELECT 15,'C','She was allergic to feather pillows.',0,3 UNION ALL SELECT 15,'D','Her husband bought her one as a gift.',0,4
  UNION ALL SELECT 16,'A','It''s a good pillow for relieving soreness.',1,1 UNION ALL SELECT 16,'B','It requires a special pillowcase.',0,2 UNION ALL SELECT 16,'C','It is a softer pillow than the others.',0,3 UNION ALL SELECT 16,'D','It''s the most popular pillow at the moment.',0,4
  UNION ALL SELECT 17,'A','It can lose its shape over time.',0,1 UNION ALL SELECT 17,'B','It is the store''s best-selling pillow.',1,2 UNION ALL SELECT 17,'C','It costs the same as micro bead pillows.',0,3 UNION ALL SELECT 17,'D','It is recommended by physical therapists.',0,4
  UNION ALL SELECT 18,'A','The memory foam pillow is the best for her.',1,1 UNION ALL SELECT 18,'B','The woman should purchase two pillows.',0,2 UNION ALL SELECT 18,'C','The woman also needs new bed sheets.',0,3 UNION ALL SELECT 18,'D','The memory foam pillow is easy to clean.',0,4
  UNION ALL SELECT 19,'A','The woman will purchase two pillows.',1,1 UNION ALL SELECT 19,'B','The woman will check out other stores.',0,2 UNION ALL SELECT 19,'C','The man will special order the pillows.',0,3 UNION ALL SELECT 19,'D','The man will show the woman more pillows.',0,4

  UNION ALL SELECT 20,'A','a lost dog.',0,1 UNION ALL SELECT 20,'B','scorched grass.',0,2 UNION ALL SELECT 20,'C','a live power line.',1,3 UNION ALL SELECT 20,'D','several fallen trees.',0,4
  UNION ALL SELECT 21,'A','her dog''s behaviour.',1,1 UNION ALL SELECT 21,'B','a strange smell.',0,2 UNION ALL SELECT 21,'C','her dog''s barking.',0,3 UNION ALL SELECT 21,'D','a loud noise.',0,4
  UNION ALL SELECT 22,'A','continue to deal with such events.',1,1 UNION ALL SELECT 22,'B','pay more taxes in the following year.',0,2 UNION ALL SELECT 22,'C','cut down the trees on their properties.',0,3 UNION ALL SELECT 22,'D','reduce their electricity use during storms.',0,4
  UNION ALL SELECT 23,'A','ashamed of the news that he heard.',0,1 UNION ALL SELECT 23,'B','aware of the problems and solutions.',1,2 UNION ALL SELECT 23,'C','angry about the news that he heard.',0,3 UNION ALL SELECT 23,'D','confused by the problems and solutions.',0,4
  UNION ALL SELECT 24,'A','analysis.',0,1 UNION ALL SELECT 24,'B','criticism.',0,2 UNION ALL SELECT 24,'C','questions.',0,3 UNION ALL SELECT 24,'D','instructions.',1,4

  UNION ALL SELECT 25,'A','The company is experiencing financial troubles.',0,1 UNION ALL SELECT 25,'B','In three weeks, they will begin to lay off employees.',0,2 UNION ALL SELECT 25,'C','They are considering relocating the office across town.',0,3 UNION ALL SELECT 25,'D','In less than a month, the office will be moving.',1,4
  UNION ALL SELECT 26,'A','purchased a new car',0,1 UNION ALL SELECT 26,'B','worked to increase his sales',0,2 UNION ALL SELECT 26,'C','stayed in his previous apartment',1,3 UNION ALL SELECT 26,'D','begun to look for a new job',0,4
  UNION ALL SELECT 27,'A','She is charged a fee by the daycare.',1,1 UNION ALL SELECT 27,'B','Her children stay with her neighbour.',0,2 UNION ALL SELECT 27,'C','The kids arrive home to an empty house.',0,3 UNION ALL SELECT 27,'D','Her husband picks up the kids instead.',0,4
  UNION ALL SELECT 28,'A','The company treats its employees poorly.',0,1 UNION ALL SELECT 28,'B','Their company is a good place to work.',1,2 UNION ALL SELECT 28,'C','No job is worth a long commute each day.',0,3 UNION ALL SELECT 28,'D','He will have to sell his car.',0,4
  UNION ALL SELECT 29,'A','Her husband spends too much time working.',0,1 UNION ALL SELECT 29,'B','Her child will not adjust to a new daycare.',1,2 UNION ALL SELECT 29,'C','Cheaper daycare might be available elsewhere.',0,3 UNION ALL SELECT 29,'D','It will be easy to find a different job.',0,4
  UNION ALL SELECT 30,'A','The announcement was a surprise.',1,1 UNION ALL SELECT 30,'B','Quitting the company may be necessary.',0,2 UNION ALL SELECT 30,'C','Management had a good reason for their decision.',0,3 UNION ALL SELECT 30,'D','The company is having financial trouble.',0,4
  UNION ALL SELECT 31,'A','give the new situation a chance',1,1 UNION ALL SELECT 31,'B','find a different childcare provider',0,2 UNION ALL SELECT 31,'C','commute into work together',0,3 UNION ALL SELECT 31,'D','tell management how angry she is',0,4
  UNION ALL SELECT 32,'A','convince them to reverse their decision',0,1 UNION ALL SELECT 32,'B','obtain a reference letter for future job applications',0,2 UNION ALL SELECT 32,'C','be able to arrange her work schedule differently',1,3 UNION ALL SELECT 32,'D','get compensation for her extra commute time',0,4

  UNION ALL SELECT 33,'A','creating a children''s advertisement-free television network.',0,1 UNION ALL SELECT 33,'B','beginning a group that advocates for more children''s TV programs.',0,2 UNION ALL SELECT 33,'C','banning children''s advertising from state broadcasters.',0,3 UNION ALL SELECT 33,'D','pressuring for limits on advertising to young children.',1,4
  UNION ALL SELECT 34,'A','an elected politician.',0,1 UNION ALL SELECT 34,'B','a child care worker.',0,2 UNION ALL SELECT 34,'C','a social activist.',1,3 UNION ALL SELECT 34,'D','a child development specialist',0,4
  UNION ALL SELECT 35,'A','advertising empowers children.',1,1 UNION ALL SELECT 35,'B','children are overwhelmed with too many choices.',0,2 UNION ALL SELECT 35,'C','advertising corrupts children.',0,3 UNION ALL SELECT 35,'D','children struggle to make good choices.',0,4
  UNION ALL SELECT 36,'A','encourages children to improve themselves.',0,1 UNION ALL SELECT 36,'B','raises the quality of life for most children.',0,2 UNION ALL SELECT 36,'C','helps parents make the best possible decisions.',0,3 UNION ALL SELECT 36,'D','encourages stronger relationships among peers.',1,4
  UNION ALL SELECT 37,'A','children are better informed than parents about product choices.',0,1 UNION ALL SELECT 37,'B','good parents should decide what to buy for their children.',1,2 UNION ALL SELECT 37,'C','children should be free to make their own decisions.',0,3 UNION ALL SELECT 37,'D','parents make more impulse purchases than children.',0,4
  UNION ALL SELECT 38,'A','children cannot cope with too much information.',0,1 UNION ALL SELECT 38,'B','fewer but higher quality children''s programs would be a good thing.',1,2 UNION ALL SELECT 38,'C','children''s advertising is fine, as long as it promotes healthy products.',0,3 UNION ALL SELECT 38,'D','children are not naturally greedy and selfish.',0,4
) d
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- TEST B — READING (38 Q, 4 Parts)
-- Part 1: Reading Correspondence — Adam Stevenson's thank-you letter about a bus driver (Q1-11)
-- Part 2: Reading to Apply a Diagram — B&C Business Cards flyer/order form (Q12-19)
-- Part 3: Reading for Information — dragonfly article, paragraph-matching A-E (Q20-28)
-- Part 4: Reading for Viewpoints — arts funding debate (Donahue/Bakir/Horvath) + reader comment (Q29-38)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'multiple_choice_single', d.txt, d.instr, d.stim, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn,
   'Adam now lives ___' txt,'Choose the best option according to the information given in the message.' instr,
   'Reading Part 1: Reading Correspondence — To Whom It May Concern,

I don''t usually write emails like this, but I had an experience with one of your bus drivers this morning that has stayed with me all day, and I felt I should share it with you.

Having recently moved from Gloucester to the downtown area, it has taken me some time to learn the routes of the local buses. I first tried looking at the map at the station and asking my apartment manager, but I still wasn''t clear about how to get around town. I also couldn''t find any information kiosks near to my home. Nevertheless, I finally figured it out using your online maps and schedules at www.catchmybus.com.

Over the weekend, I planned the best routes to my new office. I knew that I would get on the #2 at Bank and Queen, then transfer to the #42 once I got to McLeod. However, the weekend was a busy one for me, and I didn''t get caught up on my sleep. I was up early enough to get ready for my first day at work. I even made my way to the stop at Bank and Queen by 7:30 to catch the 7:37 bus. The problem was, I closed my eyes for a moment at the bus stop and ended up nodding off. Fortunately, when the bus approached, the driver saw me sitting there and honked his horn at me. I was so pleased that he stopped and took a moment to wake me up. If it weren''t for him, I would have been late on my first day! He then greeted me with a big smile and said, "Good morning!" as I got on the bus.

In my sleepy haze, I forgot to look at the driver''s nametag (and I almost left my bag on the bus), but perhaps the shift supervisor can look at the schedule. Could you please pass along my thanks to the driver and make sure his excellent customer service is recognized?

Regards,

Adam Stevenson' stim,1 part
  UNION ALL SELECT 2,'The man is writing about something that happened on ___',NULL,NULL,1
  UNION ALL SELECT 3,'Adam figured out how to get to work by ___',NULL,NULL,1
  UNION ALL SELECT 4,'Adam''s problem that morning was that he ___',NULL,NULL,1
  UNION ALL SELECT 5,'The most important detail in Adam''s message is that ___',NULL,NULL,1
  UNION ALL SELECT 6,'The letter expresses ___',NULL,NULL,1
  UNION ALL SELECT 7,'Complete the response letter below (fill-in-the-blank). "We truly appreciate your taking the time to 7.______."',
   'Choose the best way to complete each blank, based on the response letter.',
   'Response letter — Dear Mr. Stevenson,

We truly appreciate your taking the time to 7.______. Surveys and forms are all very good, but a personal note is even better!

I''m not surprised that you found the downtown bus routes 8.______ than those you''ve been used to. For your reference, the new 9.______ #616 will operate from Bank and Queen during peak periods.

Yes, the shift supervisor will definitely be able to 10.______. However, the drivers are assigned set routes and schedules until October. You will most likely be able to 11.______.

Thank you again for writing!

Sincerely,
Bill Liu',1
  UNION ALL SELECT 8,'8.______ (how did Adam find the downtown bus routes compared to what he was used to?)',NULL,NULL,1
  UNION ALL SELECT 9,'9.______ (what new #616 will operate from Bank and Queen?)',NULL,NULL,1
  UNION ALL SELECT 10,'10.______ (what can the shift supervisor definitely do?)',NULL,NULL,1
  UNION ALL SELECT 11,'11.______ (what will Adam most likely be able to do, since drivers keep set schedules until October?)',NULL,NULL,1

  UNION ALL SELECT 12,'Complete Natalie Moreau''s email below (fill-in-the-blank). "To: 1.______"',
   'Choose the best way to complete each blank, based on the email and the business-card diagram.',
   'Reading Part 2: Reading to Apply a Diagram — To: 1.______
From: Natalie Moreau <moreaun@xmail.com>

Date: March 12, 2015
I happen to be shopping for business cards, so I was happy to see your flyer in my inbox this morning! I like the "Dynamic" and "Unique" cards as they both 2.______. I appreciate the appeal of the "Unique" card, but I worry that it is so unique as to be distracting! So I was leaning towards the "Dynamic" card, but I 3.______. Bottom line is I''m not too keen on any of the featured templates, and, actually, I''m looking for a 4.______ card, so I thought I''d check out your other templates (especially since 5.______). However, when I clicked on the link for your website, I landed on the order form. This happened when I manually input your website address as well. Thought you should know!

Cheers,
Natalie Moreau

DIAGRAM — B&C Business Cards flyer, February Sale, 20% off (order online or by phone; no email orders; various shipping options incl. next-day; custom-designed cards available):
#1 BASIC — "Looking for just the basics?" Glossy or matte finish; heavy stock for durability; choice of geometric backgrounds; several colours available. Price: 100 for $14.99 / 250 for $18.99 / 500 for $22.99.
#2 DYNAMIC — "A dynamic design for dynamic companies!" Glossy finish only; plenty of room for text; raised print; available in black/blue. Price: 500 for $29.99.
#3 UNIQUE — "Do you want to stand out?" Glossy finish only; substantial space for descriptions; no image substitutions for logo; strong and lightweight. Price: 250 for $19.99 / 500 for $24.99.
#4 HYBRID — combines a basic design with an emphasis on contact information and custom content. Matte finish only; several colour themes available; high resolution image required. Price: 100 for $14.99 / 250 for $18.99 / 500 for $22.99.' stim,2 part
  UNION ALL SELECT 13,'2.______ (what do the "Dynamic" and "Unique" cards both offer?)',NULL,NULL,2
  UNION ALL SELECT 14,'3.______ (why did Natalie hesitate on the "Dynamic" card?)',NULL,NULL,2
  UNION ALL SELECT 15,'4.______ (what kind of card is Natalie actually looking for?)',NULL,NULL,2
  UNION ALL SELECT 16,'5.______ (why did Natalie want to check out the other templates?)',NULL,NULL,2
  UNION ALL SELECT 17,'Moreau is most likely ___',NULL,NULL,2
  UNION ALL SELECT 18,'What would best describe Moreau''s email?',NULL,NULL,2
  UNION ALL SELECT 19,'Moreau probably received the flyer as which of the following?',NULL,NULL,2

  UNION ALL SELECT 29,'The article is mainly about ___','Choose the best option according to the information given on the website.',
   'Reading Part 4: Reading for Viewpoints — The Canadian government has a long history of funding visual and performing arts across the country. Unfortunately, in the face of difficult fiscal choices, the government has increasingly withdrawn its support. Meanwhile, some influential groups have recently claimed that funding for the arts is a waste of public money and falls outside the purview of the government.

In an interview, Myriam Donahue of the Canadian Taxpayers League expanded on this subject. "Society doesn''t accept government intervention in other economic sectors; the arts should be no different. Government intervention distorts the market. Artists should be more entrepreneurial and obtain money from the private sector," claimed Donahue. "We appreciate a work of art the same way we appreciate a good meal or a sports game. Just as the government doesn''t tell us what to eat for dinner or what sports to watch, it shouldn''t be in the business of selecting which artist is fittest for public consumption. When that happens, taxpayers subsidize the leisure pursuits of society''s wealthiest people."

Understandably, artists have resisted. "It''s not realistic to imagine that private sponsorship and philanthropy are a panacea to replace government funding," explained community arts organizer Katarina Bakir. "Businesses see government support of the arts as a stamp of approval ensuring high standards of quality and integrity. With government cutbacks, the private sector has actually been more reluctant to sponsor events and artists. Earnings for most professional Canadian artists are already hovering around the poverty level. If funding cuts continue, artists will have to choose between falling further into poverty or changing professions."

Eastern University sociologist Dr. Peter Horvath agrees that artists need entrepreneurial skills and sees many social benefits to public patronage for the arts. "The arts are often accused of being elitist, but surveys show that Canadians actually prefer artistic events to live sports. By funding the arts, governments create a shared meaning and a joint understanding of our country''s values. Supporting the arts provides a common good in the form of public shows and events, but it also enhances our cohesiveness as a community. It fosters a healthy society."',4
  UNION ALL SELECT 30,'Paragraph one provides a ___',NULL,NULL,4
  UNION ALL SELECT 31,'Myriam Donahue''s views would likely be supported by ___',NULL,NULL,4
  UNION ALL SELECT 32,'Bakir thinks that implementing Donahue''s ideas would lead to ___',NULL,NULL,4
  UNION ALL SELECT 33,'The author''s tone indicates support for ___',NULL,NULL,4
  UNION ALL SELECT 34,'Complete the visitor comment below (fill-in-the-blank). "So Myriam Donahue strikes a chord with me when she says governments that fund the arts 6.______."',
   'Choose the best way to complete each blank, based on the visitor comment.',
   'Visitor comment — I''m an alternative rock musician, and my band has been trying for years to get government funds to go on tour. We''ve been denied money by bureaucrats who seem willing to fund only classical ballet, operas, and symphony orchestras.

So Myriam Donahue strikes a chord with me when she says governments that fund the arts 6.______. However, 7.______, I''m wary of Ms. Donahue''s advice. Our brand of heavy metal music is a very small niche and doesn''t appeal to the mass market. It''s hard to imagine it would appeal to the "suits" who are your typical corporate donors. That''s why I question Ms. Donahue''s opinion that 8.______.

9.______ Ms. Bakir''s prediction. That is, to survive economically, I may genuinely have to 10.______.',4
  UNION ALL SELECT 35,'7.______ (whose position does the commenter say they share on this point?)',NULL,NULL,4
  UNION ALL SELECT 36,'8.______ (what does the commenter question about Donahue''s opinion?)',NULL,NULL,4
  UNION ALL SELECT 37,'9.______ (how does the commenter introduce their agreement with Bakir''s prediction?)',NULL,NULL,4
  UNION ALL SELECT 38,'10.______ (what might the commenter genuinely have to do to survive economically?)',NULL,NULL,4
) d
WHERE t.code = 'CELPIP_FMB_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

-- Part 3 (Q20-28): paragraph-matching, question_type='matching' — dragonfly article.
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'matching', d.txt, d.instr, d.stim, 3, 1.0, d.qn
FROM tests t,
(
  SELECT 20 qn,'Dragonflies can be used as a form of pest and illness control.' txt,
   'Decide which paragraph, A to D, has the information given in each statement below. Select E if the information is not given in any of the paragraphs.' instr,
   'Reading Part 3: Reading for Information —
A. Belonging to an order of carnivorous insects called Odonates (a word which derives from the Greek word for "tooth"), dragonflies have existed for more than 300 million years, yet scientists have only recently begun researching them. The exact number of dragonfly species is not known, but scientists believe that there are over 5000 worldwide, with more than 450 in North America, including over 80 in British Columbia alone. Dragonflies are unique creatures with a diverse range of habitats. They have been detected in both acidic bogs and alkaline lakes. Although dragonflies can be found in a wide range of environments, they all live near water.

B. Dragonflies lay their fertilized eggs in ponds or marshes during the spring and summer. From these eggs emerge nymphs, immature water-dwelling insects. Depending on the species, a dragonfly may spend anywhere from 1 to 4 years in the nymph stage, living just below the water surface before becoming a fully developed adult, mature enough to climb out of its watery home, shed its skin, and fly away. Relative to its entire life cycle, the time a dragonfly spends as an adult is short, lasting only several months.

C. Dragonflies are skilled predators whose hunting techniques are specific to each phase of their life cycle. As nymphs, they stalk their quarry on low-lying greenery or shoots; lie in the mud at the bottom of a pond to wait for prey; or bury themselves in the mud to search for food. They eat aquatic insects and, once they grow big enough, sometimes consume tadpoles and even small fish. As adults, though, dragonflies hunt while flying through the air, positioning their legs to form a basket that captures mosquitoes, small midges, and other tiny flying insects.

D. Researchers are continually uncovering interesting facts about the dragonfly. An adult can fly at top speeds of up to 100 kilometres per hour, making it one of the fastest insects in the world. Each dragonfly eye has a 360-degree field of vision and 30,000 lenses that help it detect motion. In Myanmar, a country in Southeast Asia, dragonflies have been introduced to watery locations for their habit of eating mosquito larvae — an effective strategy for controlling mosquito-transmitted diseases such as dengue fever. Who knows what future research will reveal about this fascinating creature!' stim
  UNION ALL SELECT 21,'The dragonfly''s hunting tactics vary depending on its life stage.',NULL,NULL
  UNION ALL SELECT 22,'Close proximity to water is necessary for dragonfly habitation.',NULL,NULL
  UNION ALL SELECT 23,'Dragonflies'' eyes have inspired new vision technology.',NULL,NULL
  UNION ALL SELECT 24,'Dragonflies have been in existence since prehistoric times.',NULL,NULL
  UNION ALL SELECT 25,'A nymph''s maturation rate depends on its nutrient intake.',NULL,NULL
  UNION ALL SELECT 26,'New information about dragonflies is still being discovered.',NULL,NULL
  UNION ALL SELECT 27,'Canadian dragonflies have unique hunting techniques.',NULL,NULL
  UNION ALL SELECT 28,'Dragonflies spend less time in full maturity than in early life phases.',NULL,NULL
) d
WHERE t.code = 'CELPIP_FMB_R'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, opt.lbl, opt.txt,
  CASE WHEN opt.lbl = ans.letter THEN 1 ELSE 0 END, opt.ord
FROM questions q
JOIN tests t ON t.id = q.test_id
JOIN (
  SELECT 20 qn,'A' letter UNION ALL SELECT 21,'C' UNION ALL SELECT 22,'A' UNION ALL SELECT 23,'E'
  UNION ALL SELECT 24,'A' UNION ALL SELECT 25,'E' UNION ALL SELECT 26,'D' UNION ALL SELECT 27,'E' UNION ALL SELECT 28,'B'
) ans ON ans.qn = q.question_number,
(
  SELECT 'A' lbl,'Paragraph A — dragonfly taxonomy, species diversity, and habitats (300+ million years old; over 5000 species; live near water)' txt,1 ord
  UNION ALL SELECT 'B','Paragraph B — dragonfly life cycle (eggs, nymph stage of 1-4 years, short adult phase of several months)',2
  UNION ALL SELECT 'C','Paragraph C — dragonfly hunting techniques as nymphs vs. as adults',3
  UNION ALL SELECT 'D','Paragraph D — interesting facts: flight speed, 360-degree vision, mosquito control in Myanmar',4
  UNION ALL SELECT 'E','Not given in any of the paragraphs',5
) opt
WHERE t.code = 'CELPIP_FMB_R' AND q.question_number BETWEEN 20 AND 28
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = opt.lbl);

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, d.ans, 0, d.alt
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 20 qn,'A' ans,0 alt UNION ALL SELECT 20,'a',1
  UNION ALL SELECT 21,'C',0 UNION ALL SELECT 21,'c',1
  UNION ALL SELECT 22,'A',0 UNION ALL SELECT 22,'a',1
  UNION ALL SELECT 23,'E',0 UNION ALL SELECT 23,'e',1
  UNION ALL SELECT 24,'A',0 UNION ALL SELECT 24,'a',1
  UNION ALL SELECT 25,'E',0 UNION ALL SELECT 25,'e',1
  UNION ALL SELECT 26,'D',0 UNION ALL SELECT 26,'d',1
  UNION ALL SELECT 27,'E',0 UNION ALL SELECT 27,'e',1
  UNION ALL SELECT 28,'B',0 UNION ALL SELECT 28,'b',1
) d
WHERE t.code = 'CELPIP_FMB_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_correct_answers qca WHERE qca.question_id = q.id AND qca.answer_text = d.ans);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, d.lbl, d.txt, d.correct, d.ord
FROM questions q JOIN tests t ON t.id = q.test_id,
(
  SELECT 1 qn,'A' lbl,'in Gloucester.' txt,0 correct,1 ord UNION ALL SELECT 1,'B','in the downtown area.',1,2 UNION ALL SELECT 1,'C','outside the city.',0,3 UNION ALL SELECT 1,'D','near McLeod Street.',0,4
  UNION ALL SELECT 2,'A','Sunday.',0,1 UNION ALL SELECT 2,'B','Monday.',1,2 UNION ALL SELECT 2,'C','Wednesday.',0,3 UNION ALL SELECT 2,'D','Friday.',0,4
  UNION ALL SELECT 3,'A','checking with his apartment manager.',0,1 UNION ALL SELECT 3,'B','using the transit information kiosks.',0,2 UNION ALL SELECT 3,'C','asking one of the bus drivers.',0,3 UNION ALL SELECT 3,'D','visiting the transit web page.',1,4
  UNION ALL SELECT 4,'A','didn''t wake up on time.',0,1 UNION ALL SELECT 4,'B','was late for work.',0,2 UNION ALL SELECT 4,'C','missed the bus.',0,3 UNION ALL SELECT 4,'D','was very tired.',1,4
  UNION ALL SELECT 5,'A','he waited in a bus shelter.',0,1 UNION ALL SELECT 5,'B','the bus driver honked at him.',1,2 UNION ALL SELECT 5,'C','he intended to transfer to Bus #42.',0,3 UNION ALL SELECT 5,'D','he was at Bank and Queen at 7:30.',0,4
  UNION ALL SELECT 6,'A','gratitude.',1,1 UNION ALL SELECT 6,'B','concern.',0,2 UNION ALL SELECT 6,'C','sadness.',0,3 UNION ALL SELECT 6,'D','excitement.',0,4
  UNION ALL SELECT 7,'A','tell us about your transit experience',1,1 UNION ALL SELECT 7,'B','let us know about the route confusion',0,2 UNION ALL SELECT 7,'C','return the bag you found on the bus',0,3 UNION ALL SELECT 7,'D','report a problem with our website',0,4
  UNION ALL SELECT 8,'A','less numerous',0,1 UNION ALL SELECT 8,'B','much slower',0,2 UNION ALL SELECT 8,'C','more complicated',1,3 UNION ALL SELECT 8,'D','more stop-and-go',0,4
  UNION ALL SELECT 9,'A','bus shelter map',0,1 UNION ALL SELECT 9,'B','shuttle bus',1,2 UNION ALL SELECT 9,'C','information kiosk',0,3 UNION ALL SELECT 9,'D','online mapping tool',0,4
  UNION ALL SELECT 10,'A','make a change in schedule',0,1 UNION ALL SELECT 10,'B','determine your driver''s name',1,2 UNION ALL SELECT 10,'C','identify your forgotten bag',0,3 UNION ALL SELECT 10,'D','monitor customer service',0,4
  UNION ALL SELECT 11,'A','consider establishing an information kiosk',0,1 UNION ALL SELECT 11,'B','thank your driver when you next catch the bus',1,2 UNION ALL SELECT 11,'C','suggest that our drivers wear name tags',0,3 UNION ALL SELECT 11,'D','remind all our bus drivers to be on time',0,4

  UNION ALL SELECT 12,'A','University Admissions',0,1 UNION ALL SELECT 12,'B','B & C Business Cards',1,2 UNION ALL SELECT 12,'C','Best Business Cards, Inc.',0,3 UNION ALL SELECT 12,'D','Mr. Andrews, Supervisor',0,4
  UNION ALL SELECT 13,'A','allow for substantial descriptive content',1,1 UNION ALL SELECT 13,'B','are available with raised print',0,2 UNION ALL SELECT 13,'C','are available in a matte card',0,3 UNION ALL SELECT 13,'D','feature several eye-catching colours',0,4
  UNION ALL SELECT 14,'A','want a card that is partially black',0,1 UNION ALL SELECT 14,'B','want my name to be prominent',0,2 UNION ALL SELECT 14,'C','need to order only 100 cards',1,3 UNION ALL SELECT 14,'D','prefer a card with a glossy finish',0,4
  UNION ALL SELECT 15,'A','white',0,1 UNION ALL SELECT 15,'B','durable',0,2 UNION ALL SELECT 15,'C','one-sided',1,3 UNION ALL SELECT 15,'D','multi-coloured',0,4
  UNION ALL SELECT 16,'A','you don''t offer custom design',0,1 UNION ALL SELECT 16,'B','you accept orders by email',0,2 UNION ALL SELECT 16,'C','you offer a discount on all orders',0,3 UNION ALL SELECT 16,'D','you will ship to Canada',1,4
  UNION ALL SELECT 17,'A','applying to post-secondary school.',0,1 UNION ALL SELECT 17,'B','seeking to improve her business presence.',1,2 UNION ALL SELECT 17,'C','retiring soon from her job.',0,3 UNION ALL SELECT 17,'D','working for a business card manufacturer.',0,4
  UNION ALL SELECT 18,'A','confused and annoyed',0,1 UNION ALL SELECT 18,'B','interested and helpful',1,2 UNION ALL SELECT 18,'C','relieved and grateful',0,3 UNION ALL SELECT 18,'D','angry and critical',0,4
  UNION ALL SELECT 19,'A','a mis-addressed email',0,1 UNION ALL SELECT 19,'B','a reply to a specific query',0,2 UNION ALL SELECT 19,'C','an email advertisement',1,3 UNION ALL SELECT 19,'D','an attachment to an electronic receipt',0,4

  UNION ALL SELECT 29,'A','what measurable value the arts brings to our society.',0,1 UNION ALL SELECT 29,'B','why the private sector should financially support artists.',0,2 UNION ALL SELECT 29,'C','whether artists should rely on themselves or on charities.',0,3 UNION ALL SELECT 29,'D','whether working artists deserve government sponsorship.',1,4
  UNION ALL SELECT 30,'A','description of various types of performing arts.',0,1 UNION ALL SELECT 30,'B','few pieces of background information.',1,2 UNION ALL SELECT 30,'C','good example of the problem that follows.',0,3 UNION ALL SELECT 30,'D','brief historical overview of Canadian artists.',0,4
  UNION ALL SELECT 31,'A','administrators working for a government-funded arts council.',0,1 UNION ALL SELECT 31,'B','corporations dedicated to funding overseas health initiatives.',0,2 UNION ALL SELECT 31,'C','dance organizations reliant on government sponsorships.',0,3 UNION ALL SELECT 31,'D','Canadian residents interested mainly in spectator sports.',1,4
  UNION ALL SELECT 32,'A','a decrease in the number of working artists.',1,1 UNION ALL SELECT 32,'B','a decrease in government income tax rates.',0,2 UNION ALL SELECT 32,'C','an increase in the quality and integrity of art.',0,3 UNION ALL SELECT 32,'D','an increase in private funding for the arts.',0,4
  UNION ALL SELECT 33,'A','eliminating public funding of visual and performing arts.',0,1 UNION ALL SELECT 33,'B','government commitment to supporting Canadian artists.',1,2 UNION ALL SELECT 33,'C','increased public participation in local arts organizations.',0,3 UNION ALL SELECT 33,'D','private sector funding for the arts across Canada.',0,4
  UNION ALL SELECT 34,'A','eliminating do so without economic intervention',0,1 UNION ALL SELECT 34,'B','pay for the pastimes of the elite',1,2 UNION ALL SELECT 34,'C','undervalue the work of modern musicians',0,3 UNION ALL SELECT 34,'D','support administrators rather than artists',0,4
  UNION ALL SELECT 35,'A','due to her lack of information on the issue',0,1 UNION ALL SELECT 35,'B','similar to Ms. Katarina Bakir',1,2 UNION ALL SELECT 35,'C','based on Dr. Horvath''s strong opposition',0,3 UNION ALL SELECT 35,'D','despite her statistical evidence',0,4
  UNION ALL SELECT 36,'A','artists should produce practical things',0,1 UNION ALL SELECT 36,'B','free handouts distort the true meaning of art',0,2 UNION ALL SELECT 36,'C','governments should have good taste',0,3 UNION ALL SELECT 36,'D','private philanthropy can sustain the arts',1,4
  UNION ALL SELECT 37,'A','Moreover, I am also doubtful of',0,1 UNION ALL SELECT 37,'B','Thus, I could become an example of',1,2 UNION ALL SELECT 37,'C','Unfortunately, I have to disagree with',0,3 UNION ALL SELECT 37,'D','Indeed, Ms. Donahue does share',0,4
  UNION ALL SELECT 38,'A','cut back on my unnecessary spending',0,1 UNION ALL SELECT 38,'B','give up on my dream just to pay the rent',1,2 UNION ALL SELECT 38,'C','produce music of a lower quality standard',0,3 UNION ALL SELECT 38,'D','seek funds from more conservative donors',0,4
) d
WHERE t.code = 'CELPIP_FMB_R' AND q.question_number = d.qn
  AND NOT EXISTS (SELECT 1 FROM question_options qo WHERE qo.question_id = q.id AND qo.option_label = d.lbl);

-- ============================================================
-- TEST B — WRITING (2 tasks, essay-type, manual/AI-graded)
-- ============================================================
INSERT INTO questions (test_id, question_number, question_type, question_text, instructions, stimulus_text, part_number, points, display_order)
SELECT t.id, d.qn, 'essay', d.txt, d.instr, NULL, d.part, 1.0, d.qn
FROM tests t,
(
  SELECT 1 qn, 1 part,
   'Task 1: Writing an Email — You and your family visit the local shopping mall every week. However, it has become more and more difficult to find a parking spot recently. You would like to let the shopping mall manager know about this problem. Write an email to the mall manager. Your email should: describe the problem you are having with the mall''s parking; explain what you and your family have to do in order to visit the shopping mall now; provide some suggestions for how the mall manager can solve this problem.' txt,
   'You have 27 minutes to complete this task. Write about 150-200 words.' instr
  UNION ALL SELECT 2, 2,
   'Task 2: Responding to Survey Questions — Childcare Survey. You work in a very big office. There is a popular and cheap restaurant in the building. The boss is thinking of removing the restaurant and replacing it with a childcare facility for the working parents in the office. You have been asked to respond to an opinion survey. Option A: I think we should keep the restaurant. Option B: I think we should replace the restaurant with a childcare facility. Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice.',
   'You have 26 minutes to complete this task. Write about 150-200 words.'
) d
WHERE t.code = 'CELPIP_FMB_W'
  AND NOT EXISTS (SELECT 1 FROM questions q2 WHERE q2.test_id = t.id AND q2.question_number = d.qn);
