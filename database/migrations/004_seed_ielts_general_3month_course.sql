-- Migration 004: Seed IELTS General 3-Month Masterclass course
-- Inserts 1 course, 3 modules, 24 lessons
-- IMPORTANT: courses and modules tables use latin1 charset — ASCII-only in those fields
-- lessons table uses utf8mb4 — special characters fine there
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

-- ─────────────────────────────────────────────
-- STEP 1: Course
-- ─────────────────────────────────────────────

INSERT INTO `courses`
  (title, folder_name, description, category, price, is_free, instructor_name, total_lessons)
VALUES (
  'IELTS General - 3 Month Masterclass',
  'IELTS_Gen_Mst',
  '12 weeks, 24 sessions (2 per week). Covers all four IELTS General skills: Listening, Reading, Writing and Speaking. Includes 4 full practice test sets, 3 full mock exams, a quiz and take-home exercise every class.',
  'IELTS',
  0.00,
  0,
  'Scholarly Language Services',
  24
);

SET @course_id = LAST_INSERT_ID();

-- ─────────────────────────────────────────────
-- STEP 2: Modules (one per month)
-- ─────────────────────────────────────────────

INSERT INTO `modules` (course_id, module_title, module_order, min_tier) VALUES
(@course_id, 'Month 1 - Foundations',        1, 'fluent'),
(@course_id, 'Month 2 - Skill Development',  2, 'fluent'),
(@course_id, 'Month 3 - Mastery',            3, 'fluent');

SET @mod1_id = (SELECT id FROM modules WHERE course_id = @course_id AND module_order = 1);
SET @mod2_id = (SELECT id FROM modules WHERE course_id = @course_id AND module_order = 2);
SET @mod3_id = (SELECT id FROM modules WHERE course_id = @course_id AND module_order = 3);

-- ─────────────────────────────────────────────
-- STEP 3a: Lessons — Month 1 (Classes 1–8)
-- ─────────────────────────────────────────────

INSERT INTO `lessons`
  (course_id, module_id, title, content, icon, lesson_order, duration_minutes, min_tier)
VALUES

-- Class 1 — free preview so prospective students can see the programme
(
  @course_id, @mod1_id,
  'Class 1: Course Orientation & IELTS General Overview',
  'Welcome to the 3-Month IELTS General Masterclass. Covers the programme structure, the difference between IELTS General and Academic, the format of all four skills, band score calculation, and how classes, quizzes, and take-homes work. Videos: Alessandra opening, Seda opening, Speaking three-candidates intro. Take-home: self-assessment grid — rate all 4 skills 1–5.',
  'bi-mortarboard-fill', 1, 90, 'beginner'
),

-- Class 2
(
  @course_id, @mod1_id,
  'Class 2: Diagnostic Mini Mock & Band Descriptor Review',
  'Abridged diagnostic mini mock covering all four skills (00B). Writing and Speaking responses reviewed against IELTS Band Descriptors (00C). Strengths and weaknesses identified. Guided corrections and self-assessment framework (00X). Videos: IELTS Writing Section Overview, Writing Test Overview. Take-home: draft 1 informal letter + record a 2-minute Speaking Part 1 response.',
  'bi-clipboard-check', 2, 90, 'fluent'
),

-- Class 3
(
  @course_id, @mod1_id,
  'Class 3: Listening — Form Completion & Gap-fill · Speaking — Introduction & Fluency',
  'SL1 & SL2. Listening Parts 1 & 2: form completion and gap-fill strategies, prediction before the recording, handling numbers, names, and dates. Speaking: first impressions, fluency tips, coherence. Videos: L1 Part 1 Listening Class 1, Listening Question Types, Speaking Coherence, Speaking Part 1 Mind Map. Practice Test Set 1 — Listening section. Quiz: gap-fill prediction strategies (10 questions). Take-home: record Speaking Part 1 on 3 topics.',
  'bi-headphones', 3, 90, 'fluent'
),

-- Class 4
(
  @course_id, @mod1_id,
  'Class 4: Reading — Skimming & Scanning · Writing Task 1 — Vocabulary & Registers',
  'SL3 & SL4. Reading: skimming for gist vs scanning for specific information, managing 3 passages in 60 minutes. Writing Task 1: formal vs informal register, salutations, sign-offs, tone for different letter types. Videos: R1 GT Skimming Scanning Class 1, Writing Task 1 Overview. Practice Test Set 1 — Reading section. Quiz: skimming/scanning multiple choice drill. Take-home: write 1 formal + 1 informal letter.',
  'bi-book', 4, 90, 'fluent'
),

-- Class 5
(
  @course_id, @mod1_id,
  'Class 5: Listening — Multiple Choice · Speaking Part 1 — Common Topics & Questions',
  'SL5 & SL6. Listening: MCQ elimination strategy, how IELTS paraphrases in options, identifying distractors early. Speaking Part 1: common topic areas (family, travel, food, work, free time), extending short answers naturally using the IDEA framework. Videos: L1 Part 1 Listening Class 2, General Speaking Test Overview. Practice Test Set 1 — Writing Task 1 section (20 min timed). Quiz: MCQ elimination drill. Take-home: practise 10 common Part 1 questions.',
  'bi-headphones', 5, 90, 'fluent'
),

-- Class 6
(
  @course_id, @mod1_id,
  'Class 6: Reading — True/False/Not Given · Writing Task 1 — Formal Letters',
  'SL7 & SL8. Reading: True/False/Not Given vs Yes/No/Not Given — the critical difference and common traps. Writing Task 1: formal letter types (complaints, requests, applications), structure, vocabulary, appropriate tone. Videos: R4 Paraphrasing Class 1. Practice Test Set 1 — Speaking Part 1 (timed, 4–5 min). Quiz: TFNG vs YNNG decision drill (10 statements). Take-home: write a formal complaint letter (150+ words).',
  'bi-book', 6, 90, 'fluent'
),

-- Class 7
(
  @course_id, @mod1_id,
  'Class 7: Listening — Map Listening · Speaking Part 2 — Topic Development',
  'SL9 & SL10. Listening: map listening — orienting yourself, following directions, compass points, Parts 2 & 3 plan labelling. Speaking Part 2: understanding the cue card, 1-minute planning technique, common Part 2 categories. Videos: S4 Part 2 Speaking Class 1, Speaking Holidays Vocabulary. Practice Test Set 2 — Listening section (Parts 1–3 timed). Quiz: map orientation drill + Part 2 cue card planning. Take-home: prepare 3 Part 2 cue card outlines.',
  'bi-headphones', 7, 90, 'fluent'
),

-- Class 8 — Mock Test 1
(
  @course_id, @mod1_id,
  'Class 8: MOCK TEST 1 — Full Timed Exam (End of Month 1)',
  'Full timed mock exam. Listening (30 min + 10 min transfer), Reading (60 min, 3 passages), Writing Task 1 + Task 2 (60 min), Speaking simulation (Parts 1, 2 & 3). Written feedback on all four skills after class. Band score estimates provided. Month 2 targeted improvement plan.',
  'bi-journal-richtext', 8, 180, 'fluent'
);

-- ─────────────────────────────────────────────
-- STEP 3b: Lessons — Month 2 (Classes 9–16)
-- ─────────────────────────────────────────────

INSERT INTO `lessons`
  (course_id, module_id, title, content, icon, lesson_order, duration_minutes, min_tier)
VALUES

-- Class 9
(
  @course_id, @mod2_id,
  'Class 9: Reading — Matching Paragraphs & Headings · Listening — Plan Labelling Parts 3 & 4',
  'SL11 & SL13. Reading: matching paragraph information — scanning for synonyms and paraphrases; matching headings — understanding paragraph topic vs detail. Listening Parts 3 & 4: academic discussions, lectures, plan labelling strategies. Videos: L4 Part 4 Listening Class 1, R3 Detailed Reading Class 1. Practice Test Set 2 — Reading section (all 3 passages timed). Quiz: heading matching strategy drill (8 headings, 6 paragraphs). Take-home: 2 map/plan labelling exercises + summary paragraph for each passage.',
  'bi-book', 1, 90, 'fluent'
),

-- Class 10
(
  @course_id, @mod2_id,
  'Class 10: Speaking Part 2 — Long Turn · Writing Task 1 — Informal Letters',
  'SL14 & SL12. Speaking Part 2: note-taking during 1-minute prep, fluency drills, speaking naturally without long pauses. Writing Task 1: informal letters — register, tone, vocabulary, structure (opening, body, closing). Videos: S4 Part 2 Speaking Class 2, Band 9 Part 2 FC1 LR1-5. Practice Test Set 2 — Writing Task 1 section (informal letter, 20 min timed). Quiz: long turn coherence checklist self-assessment. Take-home: record a full Part 2 response + write an informal letter to a relative.',
  'bi-mic', 2, 90, 'fluent'
),

-- Class 11
(
  @course_id, @mod2_id,
  'Class 11: Reading — Multiple Choice · Writing Task 1 — Semi-Formal Letters',
  'SL15 & SL16. Reading: MCQ — underlining keywords before reading options, eliminating wrong answers using the text. Writing Task 1: semi-formal letters (colleague, landlord, neighbour) — tone between formal and informal, what changes. Videos: R6 Underlining and Keywords Class 1. Practice Test Set 2 — Speaking section (Parts 1 & 2 timed). Quiz: MCQ answer elimination + keyword spotting drill (10 questions). Take-home: write a semi-formal letter to a landlord + a semi-formal email to a colleague.',
  'bi-book', 3, 90, 'fluent'
),

-- Class 12
(
  @course_id, @mod2_id,
  'Class 12: Listening — Note Completion, Table & Flowchart · Bonus: Form & Gap Fill Flow',
  'SL17 & Bonus SL9 extended. Listening: note completion (following argument structure), table completion (predicting information type for each cell), flowchart completion (tracking sequence and cause/effect), gap fill and form flow. Synonym substitution in Listening bonus. Videos: R6 Underlining and Keywords Class 2, R4 Paraphrasing Class 2. Practice Test Set 3 — Listening section (Parts 1–4 full timed). Quiz: flowchart completion prediction drill (8 gaps). Take-home: complete a full note/table/flowchart practice set.',
  'bi-headphones', 4, 90, 'fluent'
),

-- Class 13
(
  @course_id, @mod2_id,
  'Class 13: Speaking Part 3 — A Complete Guide · Critical Thinking & Extended Responses',
  'SL18 & Bonus SL18 extended. Part 3: abstract discussion and societal topics, expressing and hedging opinions, extending responses with reasons, examples, and consequences. Developing critical thinking: both sides, hypothetical scenarios. Videos: IELTS Speaking Test Overview, S3 Part 1 Speaking Class 2. Practice Test Set 3 — Reading section (all 3 passages timed). Quiz: Part 3 opinion extension framework — 5 questions, write a 3-sentence extended answer for each. Take-home: answer 8 Part 3 abstract questions in writing then practise orally.',
  'bi-mic', 5, 90, 'fluent'
),

-- Class 14
(
  @course_id, @mod2_id,
  'Class 14: Reading — Sentence Completion · Writing Task 2 — Effective Introduction',
  'SL19 & SL20. Reading: sentence completion and sentence endings — locating and paraphrasing within the text. Writing Task 2: identifying the question type before writing, introduction structure (background sentence + thesis statement), paraphrasing the question without copying. Videos: WT2 Marking Criteria Class 1. Practice Test Set 3 — Writing Task 1 section (semi-formal letter, 20 min timed). Quiz: introduction paraphrase drill (rewrite 5 Task 2 prompts). Take-home: write 3 Task 2 introductions — opinion, discussion, problem-solution.',
  'bi-pencil', 6, 90, 'fluent'
),

-- Class 15
(
  @course_id, @mod2_id,
  'Class 15: Listening — Distractors & Synonyms · Bonus: Cohesive Devices in Letters',
  'SL21 & Bonus: Cohesive Devices in IELTS Letters. Listening: how IELTS plants distractors — the answer changes mid-recording; synonym substitution — spotting paraphrased answers. Writing revision: cohesive devices in letters (sequencing, contrasting, adding information), recap of all three letter types. Videos: General Reading Test Overview. No practice test section this class. Quiz: distractor identification drill (10 questions from a recording). Take-home: rewrite 2 previous letters using improved cohesive devices.',
  'bi-journal-text', 7, 90, 'fluent'
),

-- Class 16 — Mock Test 2
(
  @course_id, @mod2_id,
  'Class 16: MOCK TEST 2 — Full Timed Exam (End of Month 2)',
  'Full exam simulation under timed conditions. Listening, Reading, Writing Task 1 + Task 2, Speaking. Written feedback with band score comparison to Mock Test 1 scores. Targeted Month 3 improvement plan per skill.',
  'bi-journal-richtext', 8, 180, 'fluent'
);

-- ─────────────────────────────────────────────
-- STEP 3c: Lessons — Month 3 (Classes 17–24)
-- ─────────────────────────────────────────────

INSERT INTO `lessons`
  (course_id, module_id, title, content, icon, lesson_order, duration_minutes, min_tier)
VALUES

-- Class 17
(
  @course_id, @mod3_id,
  'Class 17: Speaking Part 3 — Opinions & Abstract Topics · Reading — Summary/Note/Table Completion',
  'SL22 & SL23. Speaking Part 3 advanced: societal, global, and abstract question types — speculating, comparing, and justifying positions. Reading: summary/note/table completion — reading for gist plus detail, managing time across all three passages. Videos: R4 Paraphrasing Class 2. Practice Test Set 3 — Speaking section (Parts 1, 2 & 3 timed). Quiz: abstract topic opinion framework drill (5 questions, structure a full 3-part answer). Take-home: summarise 3 passages in 50 words each + record 5 Part 3 responses.',
  'bi-mic', 1, 90, 'fluent'
),

-- Class 18
(
  @course_id, @mod3_id,
  'Class 18: Writing Task 2 — Body Paragraphs · Bonus: Opinion Essays',
  'SL24 & Bonus: Opinion essays — structuring arguments and supporting evidence. PEEL method: Point, Evidence, Explanation, Link. Developing and supporting arguments with specific examples. Academic vocabulary and hedging language for formal essays. Videos: WT2 Introductions Class 1. Practice Test Set 4 — Listening section (Parts 1–4 full timed). Quiz: PEEL paragraph builder — 3 topic sentences given, write the full paragraph. Take-home: write a full opinion essay under timed conditions (40 min, 250+ words).',
  'bi-pencil', 2, 90, 'fluent'
),

-- Class 19
(
  @course_id, @mod3_id,
  'Class 19: Writing Task 2 — Balanced Conclusions · Bonus: Discussion Essays',
  'SL28 & Bonus: Discussion essays — presenting multiple viewpoints. Balanced conclusions: summarising both sides without introducing new ideas, avoiding repetition. Discussion essays: presenting arguments for each side fairly, signposting and discourse markers. Videos: WT2 Introductions Class 2. Practice Test Set 4 — Reading section (all 3 passages timed). Quiz: conclusion quality assessment — rate 5 sample conclusions against band descriptors and explain why. Take-home: write 2 full Task 2 essays — one opinion, one discussion (timed, different days).',
  'bi-pencil', 3, 90, 'fluent'
),

-- Class 20
(
  @course_id, @mod3_id,
  'Class 20: Writing Task 2 Masterclass — All Five Essay Types',
  'Full masterclass covering all five Task 2 essay types end-to-end: Opinion, Discussion, Problem-Solution, Advantage-Disadvantage, and Two-Part Question. Cohesive devices and hedging language full review. Common mistakes at each band level. 5-minute planning technique before writing. Videos: Writing Task 2 Overview, Rosiane task 3 complete (model answer). No practice test section — full focus on content. Quiz: essay type identification + 3-point outline drill (5 prompts). Take-home: mind map plans for one prompt of each of the 5 essay types.',
  'bi-pencil-square', 4, 90, 'fluent'
),

-- Class 21
(
  @course_id, @mod3_id,
  'Class 21: Reading Mastery — All Question Types Rapid Review',
  'Full rapid review of every reading question type from the programme. Paraphrasing and keyword underlining technique consolidated. Time management strategies for the 60-minute paper. Handling unknown vocabulary in academic passages. Videos: R6 Underlining and Keywords Class 1 (revision). Practice Test Set 4 — Writing Task 2 section (40 min timed). Quiz: all reading question types mixed drill (20 questions from one passage). Take-home: 2 timed reading passages — self-mark with provided answer key, note which question types caused errors.',
  'bi-book', 5, 90, 'fluent'
),

-- Class 22
(
  @course_id, @mod3_id,
  'Class 22: Speaking Mastery — Parts 1, 2 & 3 End-to-End',
  'Full run-through of all three Speaking parts. Part 1 fluency, Part 2 long turn, Part 3 abstract discussion. Pronunciation and intonation final tips. How to self-correct without losing fluency. Final vocabulary booster for common Part 3 topics. Videos: S4 Part 2 Speaking Class 1 (revision), Speaking Holidays Vocabulary (revision). Practice Test Set 4 — Speaking section (full Parts 1, 2 & 3 timed). Quiz: speaking self-assessment — record a Part 3 response and mark yourself against the 4 band descriptors. Take-home: record a complete mock Speaking test using provided prompts.',
  'bi-mic', 6, 90, 'fluent'
),

-- Class 23
(
  @course_id, @mod3_id,
  'Class 23: Final Preparation — Listening Masterclass & Test-Day Strategy',
  'Full Listening review — all parts, all question types, final strategies consolidated. Developing extended responses in Speaking (bonus topic). Exam-day checklist: what to bring, timing, mental preparation. Last-minute vocabulary and phrase consolidation. Videos: L1 Part 1 Listening Class 1 (revision). No practice test section — focus on consolidation and confidence. Quiz: IELTS strategy final quiz — 20 questions across all 4 skills. Take-home: complete the test-day readiness checklist + review all personal feedback notes from the programme.',
  'bi-journal-text', 7, 90, 'fluent'
),

-- Class 24 — Mock Test 3
(
  @course_id, @mod3_id,
  'Class 24: MOCK TEST 3 — Final Full Exam Simulation (End of Month 3)',
  'Final full exam simulation under real exam conditions. Listening, Reading, Writing Task 1 + Task 2, Speaking. Comprehensive written feedback on all four skills. Final band score prediction. Certificate of completion awarded. Personalised post-programme study plan provided.',
  'bi-journal-richtext', 8, 180, 'fluent'
);

-- ─────────────────────────────────────────────
-- VERIFY
-- ─────────────────────────────────────────────
-- SELECT id, title, folder_name, total_lessons FROM courses WHERE folder_name = 'IELTS_Gen_Mst';
-- SELECT id, module_title, module_order, min_tier FROM modules WHERE course_id = @course_id ORDER BY module_order;
-- SELECT id, title, lesson_order, duration_minutes, min_tier FROM lessons WHERE course_id = @course_id ORDER BY module_id, lesson_order;
-- Expected: 1 course, 3 modules, 24 lessons
