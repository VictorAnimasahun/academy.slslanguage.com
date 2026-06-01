-- Migration: Add CELPIP General, IELTS Academic, and PTE Academic course plans
-- Run against: useraccounts (local) / slslanguage_db (live)
-- Safe: uses INSERT only (no drops, no truncates). Run once.
-- Uses subqueries instead of session variables so phpMyAdmin runs it reliably.
-- HOW TO RUN: Select the correct database in phpMyAdmin first, then Import this file.


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'CELPIP General — 1-Month Plan',
    'CELPIP_Gen_1Mo',
    'A focused 4-week CELPIP preparation program covering all four skills: Listening, Reading, Writing, and Speaking. 8 live classes (2 per week) with practice tasks and a full mock exam.',
    'CELPIP', 25000.00, 0, 'SLS', 8, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'CELPIP_Gen_1Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & CELPIP Overview'                      t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, 'courses/CELPIP_intro/intro.php'         fp
  UNION ALL SELECT 'Listening — News Item & Conversation',         2, 90,  'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'Reading — Correspondence & Diagram',           3, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Writing — Email Task (CLB Bands)',             4, 90,  'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Speaking — Tasks 1-4 (Word Repeat to Short Answer)', 5, 90, 'intermediate', 'bi-mic',        NULL
  UNION ALL SELECT 'Reading — Extended Passage & Graph Strategies',6, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Speaking — Tasks 5-8 & Writing Survey Response',7, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Practice Mock Exam — All 4 Skills',            8, 120, 'intermediate', 'bi-clipboard-check', 'courses/CELPIP_intro/celpip_mini_mock.php'
) d
WHERE c.folder_name = 'CELPIP_Gen_1Mo' AND m.module_order = 1;


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'CELPIP General — 2-Month Plan',
    'CELPIP_Gen_2Mo',
    'An 8-week comprehensive CELPIP program. Month 1 builds foundational skills; Month 2 introduces advanced strategies, exam timing, and a second full mock exam.',
    'CELPIP', 45000.00, 0, 'SLS', 16, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & CELPIP Overview'                      t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, 'courses/CELPIP_intro/intro.php'         fp
  UNION ALL SELECT 'Listening — News Item & Conversation',         2, 90,  'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'Reading — Correspondence & Diagram',           3, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Writing — Email Task (CLB Bands)',             4, 90,  'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Speaking — Tasks 1-4',                         5, 90,  'intermediate', 'bi-mic',             NULL
  UNION ALL SELECT 'Reading — Extended Passage & Graph Strategies',6, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Speaking — Tasks 5-8 & Writing Survey Response',7, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Mock Exam 1 — All 4 Skills',                   8, 120, 'intermediate', 'bi-clipboard-check', 'courses/CELPIP_intro/celpip_mini_mock.php'
) d
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced CELPIP Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Listening — Discussion & Workplace'         t, 1 lo, 90  dur, 'advanced' ti, 'bi-headphones'      ic, NULL fp
  UNION ALL SELECT 'Advanced Reading — Speed & Accuracy Drills',   2, 90,  'advanced', 'bi-book',            NULL
  UNION ALL SELECT 'Advanced Writing — CLB 10+ Email Structures',  3, 90,  'advanced', 'bi-pencil',          NULL
  UNION ALL SELECT 'Advanced Speaking — Fluency & Coherence',      4, 90,  'advanced', 'bi-mic',             NULL
  UNION ALL SELECT 'Timed Practice — Reading & Listening',         5, 90,  'advanced', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Timed Practice — Writing & Speaking',          6, 90,  'advanced', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Test-Day Strategy & Mental Preparation',       7, 60,  'advanced', 'bi-trophy',          NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',          8, 120, 'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 2;


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'CELPIP General Masterclass — 3 Months',
    'CELPIP_Gen_3Mo',
    'The complete 3-month CELPIP Masterclass. 24 classes, 3 full mock exams, detailed written feedback, and band score optimisation across all four skills.',
    'CELPIP', 60000.00, 0, 'SLS', 24, 4.9
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'CELPIP_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & CELPIP Overview'                      t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, 'courses/CELPIP_intro/intro.php'         fp
  UNION ALL SELECT 'Listening — News Item & Conversation',         2, 90,  'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'Reading — Correspondence & Diagram',           3, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Writing — Email Task (CLB Bands)',             4, 90,  'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Speaking — Tasks 1-4',                         5, 90,  'intermediate', 'bi-mic',             NULL
  UNION ALL SELECT 'Reading — Extended Passage & Graph Strategies',6, 90,  'intermediate', 'bi-book',            NULL
  UNION ALL SELECT 'Speaking — Tasks 5-8 & Writing Survey Response',7, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Mock Exam 1 — All 4 Skills',                   8, 120, 'intermediate', 'bi-clipboard-check', 'courses/CELPIP_intro/celpip_mini_mock.php'
) d
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced CELPIP Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'CELPIP_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Listening — Discussion & Workplace'         t, 1 lo, 90  dur, 'advanced' ti, 'bi-headphones'      ic, NULL fp
  UNION ALL SELECT 'Advanced Reading — Speed & Accuracy Drills',   2, 90,  'advanced', 'bi-book',            NULL
  UNION ALL SELECT 'Advanced Writing — CLB 10+ Email Structures',  3, 90,  'advanced', 'bi-pencil',          NULL
  UNION ALL SELECT 'Advanced Speaking — Fluency & Coherence',      4, 90,  'advanced', 'bi-mic',             NULL
  UNION ALL SELECT 'Timed Practice — Reading & Listening',         5, 90,  'advanced', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Timed Practice — Writing & Speaking',          6, 90,  'advanced', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Test-Day Strategy & Mental Preparation',       7, 60,  'advanced', 'bi-trophy',          NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',          8, 120, 'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 2;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 3 — Masterclass Refinement', 3, 'fluent'
FROM courses WHERE folder_name = 'CELPIP_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Precision Listening — Accent Variants & Fast Speech'  t, 1 lo, 90  dur, 'fluent' ti, 'bi-headphones'      ic, NULL fp
  UNION ALL SELECT 'Mastery Reading — Inference & Complex Structures', 2, 90, 'fluent', 'bi-book',            NULL
  UNION ALL SELECT 'Mastery Writing — CLB 10+ Email & Survey',        3, 90, 'fluent', 'bi-pencil',          NULL
  UNION ALL SELECT 'Mastery Speaking — Lexical Precision & Delivery', 4, 90, 'fluent', 'bi-mic',             NULL
  UNION ALL SELECT 'Full Exam Simulation — Reading & Listening',      5, 90, 'fluent', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Full Exam Simulation — Writing & Speaking',       6, 90, 'fluent', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Score Analysis & Targeted Improvement Plan',      7, 60, 'fluent', 'bi-graph-up',        NULL
  UNION ALL SELECT 'Mock Exam 3 — Final Full Timed Exam',             8, 120,'fluent', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 3;


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'IELTS Academic — 1-Month Plan',
    'IELTS_Aca_1Mo',
    'A focused 4-week IELTS Academic preparation program. 8 live classes covering Academic Reading, Task 1 (graphs/charts), Task 2 essays, Listening, and Speaking.',
    'IELTS', 25000.00, 0, 'SLS', 8, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'IELTS_Aca_1Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & IELTS Academic Overview'               t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'  ic, 'courses/IELTS_Aca_Crash/intro.php'    fp
  UNION ALL SELECT 'Academic Reading — Skimming, Scanning & Strategies', 2, 90, 'intermediate', 'bi-book',       'courses/IELTS_Aca_Crash/module2.php'
  UNION ALL SELECT 'Writing Task 1 — Graphs, Charts & Diagrams',         3, 90, 'intermediate', 'bi-graph-up',   'courses/IELTS_Aca_Crash/module3.php'
  UNION ALL SELECT 'Writing Task 2 — Essay Types & Band Descriptors',    4, 90, 'intermediate', 'bi-pencil',     'courses/IELTS_Aca_Crash/module4.php'
  UNION ALL SELECT 'Listening — Completing Notes, Forms & Diagrams',     5, 90, 'intermediate', 'bi-headphones', 'courses/IELTS_Aca_Crash/module5.php'
  UNION ALL SELECT 'Speaking — Parts 1 & 2 (Introduction & Long Turn)',  6, 90, 'intermediate', 'bi-mic',        'courses/IELTS_Aca_Crash/module6.php'
  UNION ALL SELECT 'Speaking — Part 3 & Advanced Fluency Strategies',    7, 90, 'intermediate', 'bi-chat-dots',  'courses/IELTS_Aca_Crash/module7.php'
  UNION ALL SELECT 'Mock Exam 1 — All 4 Skills',                         8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_1Mo' AND m.module_order = 1;


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'IELTS Academic — 2-Month Plan',
    'IELTS_Aca_2Mo',
    'An 8-week IELTS Academic program. Month 1 covers the core four skills; Month 2 delivers advanced academic strategies, practice test sets, and a second full mock exam.',
    'IELTS', 45000.00, 0, 'SLS', 16, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'IELTS_Aca_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & IELTS Academic Overview'               t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'  ic, 'courses/IELTS_Aca_Crash/intro.php'    fp
  UNION ALL SELECT 'Academic Reading — Skimming, Scanning & Strategies', 2, 90, 'intermediate', 'bi-book',       'courses/IELTS_Aca_Crash/module2.php'
  UNION ALL SELECT 'Writing Task 1 — Graphs, Charts & Diagrams',         3, 90, 'intermediate', 'bi-graph-up',   'courses/IELTS_Aca_Crash/module3.php'
  UNION ALL SELECT 'Writing Task 2 — Essay Types & Band Descriptors',    4, 90, 'intermediate', 'bi-pencil',     'courses/IELTS_Aca_Crash/module4.php'
  UNION ALL SELECT 'Listening — Completing Notes, Forms & Diagrams',     5, 90, 'intermediate', 'bi-headphones', 'courses/IELTS_Aca_Crash/module5.php'
  UNION ALL SELECT 'Speaking — Parts 1 & 2',                             6, 90, 'intermediate', 'bi-mic',        'courses/IELTS_Aca_Crash/module6.php'
  UNION ALL SELECT 'Speaking — Part 3 & Advanced Fluency',               7, 90, 'intermediate', 'bi-chat-dots',  'courses/IELTS_Aca_Crash/module7.php'
  UNION ALL SELECT 'Mock Exam 1 — All 4 Skills',                         8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced Academic Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'IELTS_Aca_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Reading — Inference & Difficult Passage Types'  t, 1 lo, 90  dur, 'advanced' ti, 'bi-book'            ic, 'courses/IELTS_Aca_Crash/module8.php' fp
  UNION ALL SELECT 'Advanced Task 1 — Maps, Processes & Mixed Charts',   2, 90, 'advanced', 'bi-graph-up',   NULL
  UNION ALL SELECT 'Advanced Task 2 — Coherence, Cohesion & Lexis',      3, 90, 'advanced', 'bi-pencil',     NULL
  UNION ALL SELECT 'Advanced Listening — Section 3 & 4 (Academic Focus)',4, 90, 'advanced', 'bi-headphones', NULL
  UNION ALL SELECT 'Timed Practice — Reading & Writing Under Exam Conditions', 5, 90, 'advanced', 'bi-stopwatch', NULL
  UNION ALL SELECT 'Timed Practice — Listening & Speaking',               6, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Band Score Analysis & Targeted Improvement',          7, 60, 'advanced', 'bi-bar-chart',  NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',                 8, 180,'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2;


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'IELTS Academic Masterclass — 3 Months',
    'IELTS_Aca_3Mo',
    'The complete 3-month IELTS Academic Masterclass. 24 classes, 3 full mock exams, AI essay feedback, and detailed written band score reports across all four skills.',
    'IELTS', 60000.00, 0, 'SLS', 24, 4.9
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'IELTS_Aca_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & IELTS Academic Overview'               t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'  ic, 'courses/IELTS_Aca_Crash/intro.php'    fp
  UNION ALL SELECT 'Academic Reading — Skimming, Scanning & Strategies', 2, 90, 'intermediate', 'bi-book',       'courses/IELTS_Aca_Crash/module2.php'
  UNION ALL SELECT 'Writing Task 1 — Graphs, Charts & Diagrams',         3, 90, 'intermediate', 'bi-graph-up',   'courses/IELTS_Aca_Crash/module3.php'
  UNION ALL SELECT 'Writing Task 2 — Essay Types & Band Descriptors',    4, 90, 'intermediate', 'bi-pencil',     'courses/IELTS_Aca_Crash/module4.php'
  UNION ALL SELECT 'Listening — Completing Notes, Forms & Diagrams',     5, 90, 'intermediate', 'bi-headphones', 'courses/IELTS_Aca_Crash/module5.php'
  UNION ALL SELECT 'Speaking — Parts 1 & 2',                             6, 90, 'intermediate', 'bi-mic',        'courses/IELTS_Aca_Crash/module6.php'
  UNION ALL SELECT 'Speaking — Part 3 & Advanced Fluency',               7, 90, 'intermediate', 'bi-chat-dots',  'courses/IELTS_Aca_Crash/module7.php'
  UNION ALL SELECT 'Mock Exam 1 — All 4 Skills',                         8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced Academic Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'IELTS_Aca_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Reading — Inference & Difficult Passage Types'  t, 1 lo, 90  dur, 'advanced' ti, 'bi-book'            ic, 'courses/IELTS_Aca_Crash/module8.php' fp
  UNION ALL SELECT 'Advanced Task 1 — Maps, Processes & Mixed Charts',   2, 90, 'advanced', 'bi-graph-up',   NULL
  UNION ALL SELECT 'Advanced Task 2 — Coherence, Cohesion & Lexis',      3, 90, 'advanced', 'bi-pencil',     NULL
  UNION ALL SELECT 'Advanced Listening — Section 3 & 4',                 4, 90, 'advanced', 'bi-headphones', NULL
  UNION ALL SELECT 'Timed Practice — Reading & Writing',                  5, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Timed Practice — Listening & Speaking',               6, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Band Score Analysis & Targeted Improvement',          7, 60, 'advanced', 'bi-bar-chart',  NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',                 8, 180,'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 3 — Masterclass Precision', 3, 'fluent'
FROM courses WHERE folder_name = 'IELTS_Aca_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Mastery Reading — Speed, Accuracy & Exam Timing'       t, 1 lo, 90  dur, 'fluent' ti, 'bi-book'            ic, NULL fp
  UNION ALL SELECT 'Mastery Task 1 — All Visual Types Under Timed Conditions', 2, 90, 'fluent', 'bi-graph-up',  NULL
  UNION ALL SELECT 'Mastery Task 2 — Band 8-9 Essay Writing',               3, 90, 'fluent', 'bi-pencil',     NULL
  UNION ALL SELECT 'Mastery Listening — Sections 1-4 Full Simulation',       4, 90, 'fluent', 'bi-headphones', NULL
  UNION ALL SELECT 'Mastery Speaking — Fluency, Lexis & Pronunciation',      5, 90, 'fluent', 'bi-mic',        NULL
  UNION ALL SELECT 'Full Exam Simulation Day 1',                              6, 180,'fluent', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Essay AI Feedback Review & Final Refinements',            7, 60, 'fluent', 'bi-robot',      NULL
  UNION ALL SELECT 'Mock Exam 3 — Final Full Timed Exam',                     8, 180,'fluent', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3;


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'PTE Academic — 1-Month Plan',
    'PTE_Gen_1Mo',
    'A focused 4-week PTE Academic preparation program. 8 live classes covering Speaking & Writing, Reading, and Listening with AI-scored practice and a full mock exam.',
    'PTE', 25000.00, 0, 'SLS', 8, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'PTE_Gen_1Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & PTE Academic Overview'                  t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, NULL fp
  UNION ALL SELECT 'Speaking — Read Aloud & Repeat Sentence',        2, 90, 'intermediate', 'bi-mic',             NULL
  UNION ALL SELECT 'Speaking — Describe Image & Re-tell Lecture',    3, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Writing — Summarize Written Text & Essay',       4, 90, 'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Reading — Multiple Choice, Re-order & Fill in Blanks', 5, 90, 'intermediate', 'bi-book',      NULL
  UNION ALL SELECT 'Listening — Summarize Spoken Text & MCQ',        6, 90, 'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'AI Scoring Strategies & Test-Day Preparation',   7, 60, 'intermediate', 'bi-robot',           NULL
  UNION ALL SELECT 'Mock Exam 1 — Full PTE Simulation',              8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_1Mo' AND m.module_order = 1;


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'PTE Academic — 2-Month Plan',
    'PTE_Gen_2Mo',
    'An 8-week PTE Academic program. Month 1 covers all skill areas; Month 2 builds advanced PTE-specific strategies, timed practice, and a second full mock exam.',
    'PTE', 45000.00, 0, 'SLS', 16, 4.8
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'PTE_Gen_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & PTE Academic Overview'                  t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, NULL fp
  UNION ALL SELECT 'Speaking — Read Aloud & Repeat Sentence',        2, 90, 'intermediate', 'bi-mic',             NULL
  UNION ALL SELECT 'Speaking — Describe Image & Re-tell Lecture',    3, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Writing — Summarize Written Text & Essay',       4, 90, 'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Reading — Multiple Choice, Re-order & Fill in Blanks', 5, 90, 'intermediate', 'bi-book',      NULL
  UNION ALL SELECT 'Listening — Summarize Spoken Text & MCQ',        6, 90, 'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'AI Scoring Strategies & Test-Day Preparation',   7, 60, 'intermediate', 'bi-robot',           NULL
  UNION ALL SELECT 'Mock Exam 1 — Full PTE Simulation',              8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_2Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced PTE Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'PTE_Gen_2Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score' t, 1 lo, 90  dur, 'advanced' ti, 'bi-mic'             ic, NULL fp
  UNION ALL SELECT 'Advanced Writing — Essay Coherence & Discourse Markers',   2, 90, 'advanced', 'bi-pencil',     NULL
  UNION ALL SELECT 'Advanced Reading — Speed & Complex Item Types',            3, 90, 'advanced', 'bi-book',       NULL
  UNION ALL SELECT 'Advanced Listening — Fill Blanks, Dictation & Highlight',  4, 90, 'advanced', 'bi-headphones', NULL
  UNION ALL SELECT 'Timed Practice — Speaking & Writing',                       5, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Timed Practice — Reading & Listening',                      6, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'AI Score Maximisation — Common Errors & Fixes',             7, 60, 'advanced', 'bi-robot',      NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',                       8, 180,'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_2Mo' AND m.module_order = 2;


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
VALUES (
    'PTE Academic Masterclass — 3 Months',
    'PTE_Gen_3Mo',
    'The complete 3-month PTE Academic Masterclass. 24 classes, 3 full mock exams, and deep AI-scoring strategies to maximise your score in Speaking, Writing, Reading, and Listening.',
    'PTE', 60000.00, 0, 'SLS', 24, 4.9
);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses WHERE folder_name = 'PTE_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Introduction & PTE Academic Overview'                  t, 1 lo, 90  dur, 'beginner'     ti, 'bi-play-circle'     ic, NULL fp
  UNION ALL SELECT 'Speaking — Read Aloud & Repeat Sentence',        2, 90, 'intermediate', 'bi-mic',             NULL
  UNION ALL SELECT 'Speaking — Describe Image & Re-tell Lecture',    3, 90, 'intermediate', 'bi-chat-dots',       NULL
  UNION ALL SELECT 'Writing — Summarize Written Text & Essay',       4, 90, 'intermediate', 'bi-pencil',          NULL
  UNION ALL SELECT 'Reading — Multiple Choice, Re-order & Fill in Blanks', 5, 90, 'intermediate', 'bi-book',      NULL
  UNION ALL SELECT 'Listening — Summarize Spoken Text & MCQ',        6, 90, 'intermediate', 'bi-headphones',      NULL
  UNION ALL SELECT 'AI Scoring Strategies & Test-Day Preparation',   7, 60, 'intermediate', 'bi-robot',           NULL
  UNION ALL SELECT 'Mock Exam 1 — Full PTE Simulation',              8, 180,'intermediate', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 1;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 2 — Advanced PTE Strategies', 2, 'advanced'
FROM courses WHERE folder_name = 'PTE_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score' t, 1 lo, 90  dur, 'advanced' ti, 'bi-mic'             ic, NULL fp
  UNION ALL SELECT 'Advanced Writing — Essay Coherence & Discourse Markers',   2, 90, 'advanced', 'bi-pencil',     NULL
  UNION ALL SELECT 'Advanced Reading — Speed & Complex Item Types',            3, 90, 'advanced', 'bi-book',       NULL
  UNION ALL SELECT 'Advanced Listening — Fill Blanks, Dictation & Highlight',  4, 90, 'advanced', 'bi-headphones', NULL
  UNION ALL SELECT 'Timed Practice — Speaking & Writing',                       5, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'Timed Practice — Reading & Listening',                      6, 90, 'advanced', 'bi-stopwatch',  NULL
  UNION ALL SELECT 'AI Score Maximisation — Common Errors & Fixes',             7, 60, 'advanced', 'bi-robot',      NULL
  UNION ALL SELECT 'Mock Exam 2 — Full Timed Simulation',                       8, 180,'advanced', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 2;

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT id, 'Month 3 — Masterclass Precision', 3, 'fluent'
FROM courses WHERE folder_name = 'PTE_Gen_3Mo' LIMIT 1;

INSERT INTO lessons (module_id, course_id, title, lesson_order, duration_minutes, min_tier, icon, file_path)
SELECT m.id, c.id, d.t, d.lo, d.dur, d.ti, d.ic, d.fp
FROM modules m JOIN courses c ON m.course_id = c.id,
(
  SELECT 'Mastery Speaking — Perfect Pronunciation Patterns'     t, 1 lo, 90  dur, 'fluent' ti, 'bi-mic'             ic, NULL fp
  UNION ALL SELECT 'Mastery Writing — Band 90 Essay Structures',     2, 90, 'fluent', 'bi-pencil',          NULL
  UNION ALL SELECT 'Mastery Reading — Accuracy Under Pressure',      3, 90, 'fluent', 'bi-book',            NULL
  UNION ALL SELECT 'Mastery Listening — Write From Dictation & All Types', 4, 90, 'fluent', 'bi-headphones', NULL
  UNION ALL SELECT 'Full Exam Simulation Day 1',                     5, 180,'fluent', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'Full Exam Simulation Day 2',                     6, 180,'fluent', 'bi-stopwatch',       NULL
  UNION ALL SELECT 'AI Score Review & Final Targeted Practice',      7, 60, 'fluent', 'bi-robot',           NULL
  UNION ALL SELECT 'Mock Exam 3 — Final Full Timed Exam',            8, 180,'fluent', 'bi-clipboard-check', NULL
) d
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 3;
