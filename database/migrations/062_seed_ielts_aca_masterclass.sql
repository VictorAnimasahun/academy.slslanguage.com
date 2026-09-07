-- Migration 062 — Build out the IELTS Academic Masterclass (course_id=4, folder IELTS_Aca_Mst)
-- The course row and its intro.php marketing copy already existed (8-week, 8-module,
-- all-4-skills plan) but had zero modules/lessons in the DB and its folder had a stray
-- trailing dot ("IELTS_Aca_Mst.") that broke every "Start Course" link. Folder renamed
-- separately (git mv, tracked in the same commit as this migration).
-- Run on LOCAL first, then LIVE.

UPDATE courses
SET description = 'The most comprehensive IELTS Academic program: 8 weeks, 8 modules, every skill and every question type, built for students targeting Band 7+.',
    total_lessons = 26
WHERE folder_name = 'IELTS_Aca_Mst';

-- ── Modules (one per week, matching intro.php's existing 8-week plan) ──────────
INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, d.t, d.o, d.ti FROM courses,
(
  SELECT 'Week 1 — Course Orientation & IELTS Deep Dive'  t, 1 o, 'beginner'     ti
  UNION ALL SELECT 'Week 2 — Listening Mastery',              2, 'intermediate'
  UNION ALL SELECT 'Week 3 — Academic Reading Mastery',       3, 'intermediate'
  UNION ALL SELECT 'Week 4 — Academic Vocabulary & Grammar',  4, 'intermediate'
  UNION ALL SELECT 'Week 5 — Writing Task 1 Mastery',         5, 'advanced'
  UNION ALL SELECT 'Week 6 — Writing Task 2 Mastery',         6, 'advanced'
  UNION ALL SELECT 'Week 7 — Speaking Mastery',                7, 'advanced'
  UNION ALL SELECT 'Week 8 — Full Mock Tests & Band Score Optimization', 8, 'fluent'
) d
WHERE folder_name = 'IELTS_Aca_Mst';

-- ── Week 1 ──────────────────────────────────────────────────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Welcome, Program Structure & the IELTS Band System' t,
    '<p>Your orientation to the Masterclass: how the 8 weeks are structured, what to expect from live sessions vs. self-paced work, and how IELTS Academic differs from General Training.</p>
<ul>
<li>Program structure and weekly study schedule</li>
<li>IELTS Academic vs. General Training — key differences in Reading and Writing</li>
<li>How the 9-band scale works, and how your four skill scores become one Overall Band Score</li>
<li>The examiner''s mindset: how Writing and Speaking are actually marked against the criteria</li>
</ul>
<p><strong>Exercise:</strong> Write down your current estimated band (if known) and your target band for each of the four skills.</p>' co,
    'bi-play-circle' ic, 1 lo, 60 dur, 'beginner' ti, NULL fp
  UNION ALL SELECT
    'Diagnostic Self-Assessment',
    '<p>Before your first real class, take a short diagnostic across all four skills. It is not scored against other students — it exists purely to show your instructor (and you) where your Writing, Reading, Listening and Speaking already stand, so the rest of the Masterclass can focus on what actually moves your band score.</p>
<ul>
<li>One short Listening clip, one Reading passage, Writing Task 1 (no Task 2), and Speaking Part 2 only</li>
<li>Results are reviewed with your instructor before Week 2 begins</li>
</ul>
<p><strong>Exercise:</strong> Complete the diagnostic test below in one sitting — treat it like the real thing.</p>',
    'bi-clipboard-data', 2, 45, 'beginner', 'resources/diagnostic_tests/diagnostic_IELTS.php'
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 1;

-- ── Week 2 — Listening Mastery ───────────────────────────────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Anatomy of the Listening Paper & Prediction Strategies' t,
    '<p>IELTS Listening has 4 sections (40 questions total), moving from an everyday conversation to an academic lecture. The biggest score gains come before the audio even starts.</p>
<ul>
<li>Section-by-section breakdown: Section 1 (everyday conversation), Section 2 (monologue), Section 3 (academic discussion), Section 4 (academic lecture)</li>
<li>Reading the question paper ahead of the audio to predict answer type (name, number, date, single word)</li>
<li>Spotting signpost language examiners use to mark where an answer is coming ("however," "the next point is," "moving on to")</li>
</ul>
<p><strong>Exercise:</strong> Given a Section 1 question set, underline what TYPE of answer each blank needs before listening.</p>' co,
    'bi-headphones' ic, 1 lo, 60 dur, 'intermediate' ti, NULL fp
  UNION ALL SELECT
    'Listening Question Types & Timed Practice',
    '<p>All Listening question types reduce to a handful of patterns. Once you recognise the pattern, the strategy is the same every time you see it.</p>
<ul>
<li>Form/note/table completion, multiple choice, matching, plan/map/diagram labelling</li>
<li>Common traps: distractors (a wrong answer mentioned first, then corrected), paraphrasing between the audio and the question</li>
<li>Transferring answers accurately — spelling, word limits ("NO MORE THAN TWO WORDS"), and plurals</li>
</ul>
<p><strong>Exercise:</strong> Complete one full timed Listening section and mark your own answers against the key.</p>',
    'bi-headphones', 2, 60, 'intermediate', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 2;

-- ── Week 3 — Academic Reading Mastery ────────────────────────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Skimming, Scanning & True/False/Not Given Logic' t,
    '<p>Academic Reading gives you 60 minutes for 3 passages and 40 questions — roughly 20 minutes per passage including reading time. Speed comes from reading differently, not reading faster.</p>
<ul>
<li>Skimming for gist (what is this paragraph about?) vs. scanning for specifics (find this exact name/number)</li>
<li>True / False / Not Given: the critical difference between "False" (the text says the opposite) and "Not Given" (the text simply does not say)</li>
<li>Yes / No / Not Given (used for writer''s opinions/claims) vs. True/False/Not Given (used for facts) — they are not interchangeable</li>
</ul>
<p><strong>Exercise:</strong> Given 6 True/False/Not Given statements against a short passage, justify each answer by quoting the exact line that proves it.</p>' co,
    'bi-book' ic, 1 lo, 60 dur, 'intermediate' ti, NULL fp
  UNION ALL SELECT
    'Matching Tasks, Summary Completion & Timed Practice',
    '<p>Matching headings and matching information are consistently the two hardest Reading question types for most students — because they test the whole paragraph, not one sentence.</p>
<ul>
<li>Matching headings: identify each paragraph''s main idea before looking at the heading options</li>
<li>Matching information/features: scanning for named details (dates, researchers, places) across the whole passage, not in order</li>
<li>Summary, note, and sentence completion: using the words directly from the passage, respecting the word limit</li>
</ul>
<p><strong>Exercise:</strong> Complete one full timed Academic Reading passage (20 minutes) and review every wrong answer against the passage text.</p>',
    'bi-book', 2, 60, 'intermediate', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 3;

-- ── Week 4 — Academic Vocabulary & Grammar ───────────────────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Academic Word List, Collocations & Paraphrasing' t,
    '<p>Lexical Resource is scored on range and accuracy — not on using the longest word you know. Precise, natural academic vocabulary consistently outscores forced "impressive" words used incorrectly.</p>
<ul>
<li>High-frequency academic vocabulary (from the Academic Word List) that appears across Reading, Writing, and Listening</li>
<li>Collocations: word partnerships that sound natural to a native reader ("conduct research," not "make research")</li>
<li>Paraphrasing without changing meaning — the core skill behind Reading matching tasks AND Writing Task 1/2 introductions</li>
</ul>
<p><strong>Exercise:</strong> Paraphrase 5 given academic sentences without changing their meaning, using different vocabulary and sentence structure.</p>' co,
    'bi-journal-text' ic, 1 lo, 60 dur, 'intermediate' ti, NULL fp
  UNION ALL SELECT
    'Complex Grammar, Cohesive Devices & Practice',
    '<p>Grammatical Range and Accuracy rewards a MIX of simple and complex sentences used correctly — not complexity for its own sake, which often introduces errors.</p>
<ul>
<li>Complex sentences: relative clauses, conditionals, and passive voice — when each genuinely improves a sentence</li>
<li>Cohesive devices and discourse markers ("furthermore," "in contrast," "as a result") — used to link ideas, not just dropped in for variety</li>
<li>Common accuracy errors at this level: article use (a/an/the), subject-verb agreement, tense consistency</li>
</ul>
<p><strong>Exercise:</strong> Rewrite 5 grammatically simple sentences using a relative clause or a conditional, keeping the meaning identical.</p>',
    'bi-pencil-square', 2, 60, 'intermediate', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 4;

-- ── Week 5 — Writing Task 1 Mastery (from the Day 1-7 Task 1 curriculum) ─────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Introduction to IELTS Academic Writing Task 1' t,
    '<ul>
<li>Overview of Task 1 types: line graph, bar chart, pie chart, table, map, process</li>
<li>Understanding the assessment criteria: Task Achievement, Coherence &amp; Cohesion, Lexical Resource, and Grammatical Range &amp; Accuracy</li>
</ul>
<p><strong>Exercise:</strong> Analyze sample responses (Band 5 vs. Band 7+) and identify what separates them against each criterion.</p>' co,
    'bi-graph-up' ic, 1 lo, 60 dur, 'advanced' ti, NULL fp
  UNION ALL SELECT
    'Describing Trends in Graphs & Charts',
    '<ul>
<li>Key vocabulary for describing trends (increase, decrease, fluctuate, plateau, etc.)</li>
<li>Sentence structures for trends (e.g. "The number of students increased significantly over five years.")</li>
</ul>
<p><strong>Exercise:</strong> Write sentences using different trend descriptions.</p>',
    'bi-graph-up', 2, 60, 'advanced', NULL
  UNION ALL SELECT
    'Comparing Data and Making Comparisons',
    '<ul>
<li>Comparative and superlative structures ("higher than," "the highest," "similar to")</li>
<li>Avoiding repetition in comparisons</li>
</ul>
<p><strong>Exercise:</strong> Write 3 comparative sentences based on a bar chart.</p>',
    'bi-bar-chart', 3, 60, 'advanced', NULL
  UNION ALL SELECT
    'Describing Maps (Before and After Changes)',
    '<ul>
<li>Key phrases for map descriptions (e.g. "was replaced by," "expanded into")</li>
<li>Using passive voice for describing changes</li>
</ul>
<p><strong>Exercise:</strong> Compare two maps showing changes in a city over time.</p>',
    'bi-map', 4, 60, 'advanced', NULL
  UNION ALL SELECT
    'Describing Processes (Flowcharts & Diagrams)',
    '<ul>
<li>Key phrases for process descriptions (e.g. "First, Next, Then, Finally")</li>
<li>Using passive voice for processes (e.g. "The materials are transported to the factory.")</li>
</ul>
<p><strong>Exercise:</strong> Describe the process of making coffee using a flowchart.</p>',
    'bi-diagram-3', 5, 60, 'advanced', NULL
  UNION ALL SELECT
    'Structuring Task 1 Reports',
    '<ul>
<li>Organizing a report (Introduction, Overview, Key Features, Details)</li>
<li>Writing a clear overview (the most important trend/change)</li>
</ul>
<p><strong>Exercise:</strong> Write a full Task 1 response for a given chart.</p>',
    'bi-file-earmark-text', 6, 60, 'advanced', NULL
  UNION ALL SELECT
    'Task 1 Practice & Feedback',
    '<ul>
<li>Timed practice: write a Task 1 response in 20 minutes</li>
<li>Self-assessment using the IELTS marking criteria</li>
</ul>
<p><strong>Exercise:</strong> Rewrite your weakest sentences for improvement.</p>',
    'bi-stopwatch', 7, 60, 'advanced', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 5;

-- ── Week 6 — Writing Task 2 Mastery (from the Day 1-7 Task 2 curriculum) ─────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Introduction to IELTS Academic Writing Task 2' t,
    '<ul>
<li>Types of essays: Opinion, Discussion, Problem-Solution, Advantage-Disadvantage, and Two-Part Questions</li>
<li>Understanding the assessment criteria</li>
</ul>
<p><strong>Exercise:</strong> Identify essay types from sample prompts.</p>' co,
    'bi-pencil' ic, 1 lo, 60 dur, 'advanced' ti, NULL fp
  UNION ALL SELECT
    'How to Write an Effective Introduction',
    '<ul>
<li>Paraphrasing the question</li>
<li>Writing a clear thesis statement</li>
</ul>
<p><strong>Exercise:</strong> Write introductions for different essay questions.</p>',
    'bi-pencil', 2, 60, 'advanced', NULL
  UNION ALL SELECT
    'Structuring Body Paragraphs',
    '<ul>
<li>Topic sentence, Explanation, Example (TEE structure)</li>
<li>Using linking words effectively ("Furthermore," "In contrast," "As a result")</li>
</ul>
<p><strong>Exercise:</strong> Write one body paragraph based on a given argument.</p>',
    'bi-list-columns', 3, 60, 'advanced', NULL
  UNION ALL SELECT
    'Developing Strong Arguments',
    '<ul>
<li>How to support ideas with reasoning and examples</li>
<li>Avoiding weak arguments and generalizations</li>
</ul>
<p><strong>Exercise:</strong> Strengthen weak arguments in sample essays.</p>',
    'bi-lightbulb', 4, 60, 'advanced', NULL
  UNION ALL SELECT
    'Writing a Balanced Conclusion',
    '<ul>
<li>Summarizing key points without repetition</li>
<li>Expressing an opinion (if required)</li>
</ul>
<p><strong>Exercise:</strong> Write conclusions for different essay types.</p>',
    'bi-check2-square', 5, 60, 'advanced', NULL
  UNION ALL SELECT
    'Common Grammar and Vocabulary Mistakes',
    '<ul>
<li>Academic vocabulary (formal words, avoiding contractions)</li>
<li>Complex sentence structures</li>
</ul>
<p><strong>Exercise:</strong> Rewrite informal sentences in an academic style.</p>',
    'bi-exclamation-triangle', 6, 60, 'advanced', NULL
  UNION ALL SELECT
    'Full Task 2 Essay Practice & Feedback',
    '<ul>
<li>Timed writing: complete a full Task 2 essay in 40 minutes</li>
<li>Self-assessment using the IELTS band descriptors</li>
</ul>
<p><strong>Exercise:</strong> Revise and improve your essay based on feedback.</p>',
    'bi-stopwatch', 7, 60, 'advanced', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 6;

-- ── Week 7 — Speaking Mastery ─────────────────────────────────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Speaking Part 1 & Part 2 — Fluency and Cue Card Strategy' t,
    '<p>Speaking is scored live, in real time, on Fluency &amp; Coherence, Lexical Resource, Grammatical Range &amp; Accuracy, and Pronunciation — there is no time to plan a perfect answer, so strategy has to become automatic.</p>
<ul>
<li>Part 1 (4-5 minutes): extending short answers naturally instead of one-word replies</li>
<li>Part 2 (the "cue card"): 1 minute to prepare notes, then speak for up to 2 minutes on a given topic</li>
<li>Building a simple note-taking system for the 1-minute prep time so you never run out of things to say</li>
</ul>
<p><strong>Exercise:</strong> Given a cue card topic, prepare notes in 1 minute and record yourself speaking for 2 minutes.</p>' co,
    'bi-mic' ic, 1 lo, 60 dur, 'advanced' ti, NULL fp
  UNION ALL SELECT
    'Speaking Part 3 & Full Mock Speaking Simulation',
    '<p>Part 3 is a 4-5 minute discussion connected to your Part 2 topic, but more abstract — this is where higher-band candidates separate themselves by justifying and comparing ideas, not just answering.</p>
<ul>
<li>Expressing, justifying, and comparing opinions on abstract questions</li>
<li>Handling a question you don''t immediately know how to answer — buying thinking time naturally, without going silent</li>
<li>Full mock speaking test (all 3 parts) as your instructor administers a live session, or a self-recorded submission</li>
</ul>
<p><strong>Exercise:</strong> Complete a full 3-part mock speaking test and review the recording against the band descriptors.</p>',
    'bi-mic', 2, 60, 'advanced', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 7;

-- ── Week 8 — Full Mock Tests & Band Score Optimization ───────────────────────
INSERT INTO lessons (module_id, course_id, title, content, icon, lesson_order, duration_minutes, min_tier, file_path)
SELECT m.id, c.id, d.t, d.co, d.ic, d.lo, d.dur, d.ti, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT
    'Full Timed Mock — Listening, Reading & Writing' t,
    '<p>Everything from Weeks 1-6, under real exam conditions and real timing: 30 minutes Listening, 60 minutes Reading, 60 minutes Writing (Task 1 + Task 2).</p>
<ul>
<li>Full timed simulation of Listening and Reading, marked immediately with your score</li>
<li>Full timed Writing (Task 1 + Task 2), submitted for instructor feedback</li>
</ul>
<p><strong>Exercise:</strong> Complete the full mock in one sitting, with no breaks beyond what the real exam allows.</p>' co,
    'bi-clipboard-check' ic, 1 lo, 120 dur, 'fluent' ti, NULL fp
  UNION ALL SELECT
    'Error Pattern Analysis, Test-Day Strategy & Final Tips',
    '<p>Your final class turns your mock results and everything from the past 7 weeks into a concrete plan for exam day.</p>
<ul>
<li>Reviewing your mock exam results to find your personal error patterns across all four skills</li>
<li>A personalized final-tips list based on what actually cost you marks this Masterclass</li>
<li>Test-day checklist and mental preparation — what to do (and not do) in the 24 hours before your exam</li>
</ul>
<p><strong>Exercise:</strong> Write your own 5-point "watch out for this" list based on your mock exam mistakes.</p>',
    'bi-trophy', 2, 60, 'fluent', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_Mst' AND m.module_order = 8;
