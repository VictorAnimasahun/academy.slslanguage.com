-- ============================================================
-- COMBINED, one-time paste for LIVE -- brings every course to the
-- universal '2 classes a week' rule in one pass.
--
-- Concatenates 8 already-idempotent migrations, in the order they must
-- run (077 -> 078 -> 076 -> 083 -> 109 -> 113 -> 114 -> 115). Every one
-- of them looks courses up by folder_name and guards its own writes
-- (NOT EXISTS / INSERT IGNORE / a 'does this already look right'
-- check), so it is safe to run this even if some of them already
-- partially succeeded on live before -- each step just no-ops.
--
-- BACK UP THE DATABASE FIRST. Import connection must be utf8mb4
-- (phpMyAdmin: choose utf8mb4 on the Import tab; mysql CLI: add
-- --default-character-set=utf8mb4), same warning as every migration
-- below repeats -- otherwise this silently re-corrupts text.
--
-- After this runs, tell Claude which of 076/077/078/083/109/113/114/115
-- to tick as confirmed-on-live in migration_log.md.
-- ============================================================

-- ============================================================
-- Migration 077_seed_general_academic_pte_courses.sql
-- ============================================================
-- Migration 077: Formalize CELPIP General / IELTS Academic / PTE Academic course plans
-- (previously an untracked draft: documentation/migrations/add_celpip_ieltsaca_pte_courses.sql)
--
-- This content was applied to LOCAL at some point outside the numbered migration
-- system, which is why it was never run on LIVE -- live's course catalogue only
-- ever had the original 8 courses. This version adds NOT EXISTS guards (keyed on
-- folder_name for courses, module_order for modules, "module already has any
-- lessons" for lessons -- same guard style as migration 067) so it is safe to run
-- on an environment that already has some or all of these rows (local) as well as
-- one that has none of them (live).
--
-- Run against: useraccounts (local) / slslanguage_db (live), via phpMyAdmin import
-- (defaults to UTF-8, safe for the em-dashes in these titles/descriptions).

-- Migration: Add CELPIP General, IELTS Academic, and PTE Academic course plans
-- Run against: useraccounts (local) / slslanguage_db (live)
-- Safe: uses INSERT only (no drops, no truncates). Run once.
-- Uses subqueries instead of session variables so phpMyAdmin runs it reliably.
-- HOW TO RUN: Select the correct database in phpMyAdmin first, then Import this file.


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'CELPIP General — 1-Month Plan', 'CELPIP_Gen_1Mo', 'A focused 4-week CELPIP preparation program covering all four skills: Listening, Reading, Writing, and Speaking. 8 live classes (2 per week) with practice tasks and a full mock exam.', 'CELPIP', 25000.00, 0, 'SLS', 8, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'CELPIP_Gen_1Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_1Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_1Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'CELPIP General — 2-Month Plan', 'CELPIP_Gen_2Mo', 'An 8-week comprehensive CELPIP program. Month 1 builds foundational skills; Month 2 introduces advanced strategies, exam timing, and a second full mock exam.', 'CELPIP', 45000.00, 0, 'SLS', 16, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced CELPIP Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- CELPIP GENERAL — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'CELPIP General Masterclass — 3 Months', 'CELPIP_Gen_3Mo', 'The complete 3-month CELPIP Masterclass. 24 classes, 3 full mock exams, detailed written feedback, and band score optimisation across all four skills.', 'CELPIP', 60000.00, 0, 'SLS', 24, 4.9
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'CELPIP_Gen_3Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — CELPIP Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced CELPIP Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 3 — Masterclass Refinement', 3, 'fluent'
FROM courses c
WHERE c.folder_name = 'CELPIP_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 3)
LIMIT 1;

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
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND m.module_order = 3
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'IELTS Academic — 1-Month Plan', 'IELTS_Aca_1Mo', 'A focused 4-week IELTS Academic preparation program. 8 live classes covering Academic Reading, Task 1 (graphs/charts), Task 2 essays, Listening, and Speaking.', 'IELTS', 25000.00, 0, 'SLS', 8, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'IELTS_Aca_1Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_1Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_1Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'IELTS Academic — 2-Month Plan', 'IELTS_Aca_2Mo', 'An 8-week IELTS Academic program. Month 1 covers the core four skills; Month 2 delivers advanced academic strategies, practice test sets, and a second full mock exam.', 'IELTS', 45000.00, 0, 'SLS', 16, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'IELTS_Aca_2Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced Academic Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- IELTS ACADEMIC — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'IELTS Academic Masterclass — 3 Months', 'IELTS_Aca_3Mo', 'The complete 3-month IELTS Academic Masterclass. 24 classes, 3 full mock exams, AI essay feedback, and detailed written band score reports across all four skills.', 'IELTS', 60000.00, 0, 'SLS', 24, 4.9
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'IELTS_Aca_3Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — IELTS Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced Academic Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 3 — Masterclass Precision', 3, 'fluent'
FROM courses c
WHERE c.folder_name = 'IELTS_Aca_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 3)
LIMIT 1;

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
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 1 MONTH
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'PTE Academic — 1-Month Plan', 'PTE_Gen_1Mo', 'A focused 4-week PTE Academic preparation program. 8 live classes covering Speaking & Writing, Reading, and Listening with AI-scored practice and a full mock exam.', 'PTE', 25000.00, 0, 'SLS', 8, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'PTE_Gen_1Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_1Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_1Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 2 MONTHS
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'PTE Academic — 2-Month Plan', 'PTE_Gen_2Mo', 'An 8-week PTE Academic program. Month 1 covers all skill areas; Month 2 builds advanced PTE-specific strategies, timed practice, and a second full mock exam.', 'PTE', 45000.00, 0, 'SLS', 16, 4.8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'PTE_Gen_2Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_2Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced PTE Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_2Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_2Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);


-- ──────────────────────────────────────────────────────────────
-- PTE ACADEMIC — 3 MONTHS (MASTERCLASS)
-- ──────────────────────────────────────────────────────────────
INSERT INTO courses (title, folder_name, description, category, price, is_free, instructor_name, total_lessons, rating)
SELECT 'PTE Academic Masterclass — 3 Months', 'PTE_Gen_3Mo', 'The complete 3-month PTE Academic Masterclass. 24 classes, 3 full mock exams, and deep AI-scoring strategies to maximise your score in Speaking, Writing, Reading, and Listening.', 'PTE', 60000.00, 0, 'SLS', 24, 4.9
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM courses WHERE folder_name = 'PTE_Gen_3Mo');

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 1 — PTE Academic Foundations', 1, 'intermediate'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 1)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 1
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 2 — Advanced PTE Strategies', 2, 'advanced'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 2)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 2
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, 'Month 3 — Masterclass Precision', 3, 'fluent'
FROM courses c
WHERE c.folder_name = 'PTE_Gen_3Mo'
  AND NOT EXISTS (SELECT 1 FROM modules mm WHERE mm.course_id = c.id AND mm.module_order = 3)
LIMIT 1;

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
WHERE c.folder_name = 'PTE_Gen_3Mo' AND m.module_order = 3
  AND NOT EXISTS (SELECT 1 FROM lessons ll WHERE ll.module_id = m.id);

-- ============================================================
-- Migration 078_rewire_celpip_full_mock_by_folder_name.sql
-- ============================================================
-- ============================================================
-- Migration 078 — Re-wire CELPIP Full Mock A/B using stable lookups
--
-- Migration 074 wired lesson 163 -> celpip_full_mock_a.php, lesson 178 ->
-- celpip_full_mock_b.php, and 2 course_pacing_items rows, all via hardcoded
-- numeric ids (id=163, id=178, course_id=13). Those ids only happen to be
-- correct locally because course 13 ("CELPIP_Gen_2Mo") and its lessons
-- already existed there.
--
-- Investigation on 2026-09-10 (prompted by "only 8 courses in the
-- catalogue") found that course_id 9-19 -- every "General"/"Academic"/PTE
-- course track, course 13 included -- was seeded from an untracked draft
-- file that was run locally but never on live (see migration 077, which
-- formalizes that draft into the tracked, idempotent migration system).
--
-- This means migration 074, despite being checked off as run on live,
-- almost certainly did nothing there: `UPDATE lessons ... WHERE id = 163`
-- against a live DB that has no lesson with that id silently affects 0
-- rows (MySQL does not error on a no-op UPDATE), and the course_pacing_items
-- insert would either silently create orphaned rows (course_id 13 pointing
-- at nothing) or fail on a FK constraint, if one exists on that column.
-- Either way, live needs this migration -- run AFTER 077 -- to actually
-- wire the CELPIP Full Mock A/B content (seeded independently by migration
-- 073, which has no course/lesson dependency and did apply cleanly on live)
-- into course 13's lesson list and pacing schedule.
--
-- Resolves everything by folder_name + module_order + lesson_order instead
-- of raw ids, so it works correctly regardless of what auto-increment
-- values 077 produces on a given environment.
--
-- Idempotent — safe to re-run (including on local, where 074 already did
-- this correctly; this should be a no-op there).
--
-- ⚠️ CHARSET WARNING: pacing item titles contain em-dashes. If importing via
-- the `mysql` CLI, pass --default-character-set=utf8mb4 (see migration 073).
-- ============================================================

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/mock_tests/celpip_full_mock_a.php'
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order = 1
  AND l.lesson_order = 8
  AND (l.file_path IS NULL OR l.file_path <> 'resources/mock_tests/celpip_full_mock_a.php');

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/mock_tests/celpip_full_mock_b.php'
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order = 2
  AND l.lesson_order = 8
  AND (l.file_path IS NULL OR l.file_path <> 'resources/mock_tests/celpip_full_mock_b.php');

INSERT INTO course_pacing_items (course_id, lesson_id, item_type, title, test_code, offset_days, display_order)
SELECT c.id, l.id, 'mock_test',
       CASE m.module_order WHEN 1 THEN 'Class 8 — Mock Exam 1 (End of Month 1)'
                            ELSE 'Class 16 — Mock Exam 2 (End of Month 2)' END,
       CASE m.module_order WHEN 1 THEN 'CELPIP_FULL_MOCK_A' ELSE 'CELPIP_FULL_MOCK_B' END,
       CASE m.module_order WHEN 1 THEN 24 ELSE 52 END,
       CASE m.module_order WHEN 1 THEN 80 ELSE 160 END
FROM lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order IN (1, 2)
  AND l.lesson_order = 8
  AND NOT EXISTS (
    SELECT 1 FROM course_pacing_items cpi WHERE cpi.course_id = c.id AND cpi.lesson_id = l.id
  );

-- Rollback:
-- DELETE FROM course_pacing_items WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'CELPIP_Gen_2Mo') AND test_code IN ('CELPIP_FULL_MOCK_A','CELPIP_FULL_MOCK_B');
-- UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id SET l.file_path = 'courses/CELPIP_intro/celpip_mini_mock.php' WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 1 AND l.lesson_order = 8;
-- UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id SET l.file_path = NULL WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 2 AND l.lesson_order = 8;

-- ============================================================
-- Migration 076_rename_celpip_2month_to_masterclass.sql
-- ============================================================
-- ============================================================
-- Migration 076 — Rename course 13 to include "Masterclass"
--
-- Course 13's title ("CELPIP General — 2-Month Plan") and description
-- never actually said "Masterclass" anywhere, even though the instructor
-- has consistently called it "the CELPIP Masterclass" throughout this
-- project (and it's exactly what the CELPIP Full Mock A/B work in
-- migrations 073-075 was built for). This made it impossible to find by
-- that name on the Courses catalogue page, and inconsistent with course
-- 14's naming ("CELPIP General Masterclass — 3 Months").
--
-- Idempotent — safe to re-run.
--
-- ⚠️ Fixed 2026-09-10, before this ever ran on live: originally targeted
-- `WHERE id = 13`, which only happens to be course 13 locally. Discovered
-- while investigating that course_id 9-19 (all the "General"/"Academic"/PTE
-- course tracks, including this one) were never migrated to live at all --
-- see migration 077. If 077 runs on live first, auto-increment will almost
-- certainly NOT assign this course id 13, so the old hardcoded-id version
-- would have silently renamed whatever unrelated course DID land on id 13.
-- Now keyed on the stable `folder_name` instead.
-- ============================================================

UPDATE courses
SET title = 'CELPIP General Masterclass — 2 Months',
    description = 'The complete 2-month CELPIP Masterclass. 16 classes, 2 full mock exams, detailed written feedback, and CLB level optimisation across all four skills.'
WHERE folder_name = 'CELPIP_Gen_2Mo'
  AND title = 'CELPIP General — 2-Month Plan';

-- ============================================================
-- Migration 083_celpip_3mo_full_rebuild_live_safe.sql
-- ============================================================
-- ============================================================
-- Migration 083 — CELPIP 3-Month rebuild, live-safe (supersedes 081 + 082)
--
-- Migrations 081 and 082 rebuilt CELPIP General Masterclass 3-Month into
-- the independent 24-class/12-week "one test + one micro-lesson per class
-- day" schedule, but both hardcoded course_id=14 and module ids 44-55.
-- Those only happen to be correct locally because course 14 and its
-- modules already existed there with those exact auto-increment values.
--
-- Investigation on 2026-09-15 (prompted by a live 500 error on every
-- course_view.php page, traced to migration 072's course_pacing_items
-- table never having been successfully applied on live) surfaced that
-- migrations 069-072, 075-078, and 081-082 are all unconfirmed on live.
-- Migration 077 creates CELPIP_Gen_3Mo fresh via auto-increment on any
-- environment where it doesn't already exist -- on live this will almost
-- certainly NOT land on id 14, exactly the class of bug migration 078
-- already fixed once for migration 074's hardcoded course 13 / lesson
-- 163/178 ids. 081's DELETE/INSERT against course_id=14 on a live DB
-- where that id belongs to a different course (or nothing) would either
-- silently affect 0 rows or, worse, write this course's 24 lessons under
-- an unrelated course_id.
--
-- This migration reproduces 081 + 082's combined end state in one step,
-- resolved entirely via folder_name='CELPIP_Gen_3Mo' + module_order
-- lookups instead of raw ids, so it is correct regardless of what
-- auto-increment values 077 produces on a given environment.
--
-- Run AFTER 077 (and 078, 076 per that migration's documented ordering).
-- Idempotent: wipes and rebuilds this course's modules/lessons every run,
-- same "wipe and redo" pattern 081 used locally, so re-running is safe.
-- 081 and 082 are left as-is (historical record of what ran locally) --
-- do not run them on live; run this instead.
-- ============================================================

DELETE l FROM lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

DELETE m FROM modules m
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, x.module_title, x.module_order, 'beginner'
FROM courses c
JOIN (
    SELECT 1 AS module_order, 'Week 1 — Orientation + Diagnostics' AS module_title UNION ALL
    SELECT 2, 'Week 2 — Listening + Reading Focus' UNION ALL
    SELECT 3, 'Week 3 — Speaking + Writing Focus' UNION ALL
    SELECT 4, 'Week 4 — Listening + Reading Focus' UNION ALL
    SELECT 5, 'Week 5 — Speaking + Writing Focus' UNION ALL
    SELECT 6, 'Week 6 — Listening + Reading Focus' UNION ALL
    SELECT 7, 'Week 7 — Speaking + Writing Focus' UNION ALL
    SELECT 8, 'Week 8 — Mock 1' UNION ALL
    SELECT 9, 'Week 9 — Listening + Reading Focus' UNION ALL
    SELECT 10, 'Week 10 — Speaking + Writing Focus' UNION ALL
    SELECT 11, 'Week 11 — Mock 2' UNION ALL
    SELECT 12, 'Week 12 — Final Prep'
) x
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, min_tier)
SELECT c.id, m.id, x.title, x.file_path, x.lesson_order, x.min_tier
FROM courses c
JOIN modules m ON m.course_id = c.id
JOIN (
    SELECT 1 AS module_order, 1 AS lesson_order, 'Foundational English Assessment' AS title, 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php' AS file_path, 'beginner' AS min_tier UNION ALL
    SELECT 1, 2, 'Mini Diagnostic — All 4 Skills', 'courses/CELPIP_intro/celpip_mini_mock.php', 'beginner' UNION ALL
    SELECT 2, 1, 'Complete Listening Test + Reading Part 1 (Correspondence)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c3', 'intermediate' UNION ALL
    SELECT 2, 2, 'Complete Reading Test + Writing Task 1 & 2 Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c4', 'intermediate' UNION ALL
    SELECT 3, 1, 'Complete Speaking Test + Listening Inference & Signal Words', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c5', 'intermediate' UNION ALL
    SELECT 3, 2, 'Complete Writing Test + Speaking Clarity & Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c6', 'intermediate' UNION ALL
    SELECT 4, 1, 'Complete Listening Test + Reading Part 2 (Schedules & Diagrams)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c7', 'intermediate' UNION ALL
    SELECT 4, 2, 'Complete Reading Test + Writing Argument Vocabulary & Sentence Variety', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c8', 'intermediate' UNION ALL
    SELECT 5, 1, 'Complete Speaking Test + Listening Supporting Details', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c9', 'intermediate' UNION ALL
    SELECT 5, 2, 'Complete Writing Test + Speaking Storytelling & Predictions', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c10', 'intermediate' UNION ALL
    SELECT 6, 1, 'Complete Listening Test + Reading Part 3 (Key Ideas)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c11', 'intermediate' UNION ALL
    SELECT 6, 2, 'Complete Reading Test + Writing Transitions & Collocations', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c12', 'intermediate' UNION ALL
    SELECT 7, 1, 'Complete Speaking Test + Listening Speaker Attitude', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c13', 'intermediate' UNION ALL
    SELECT 7, 2, 'Complete Writing Test + Speaking Persuasion & Comparison', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c14', 'intermediate' UNION ALL
    SELECT 8, 1, 'Mock Test 1 — Full-Length, All 4 Sections', 'resources/mock_tests/celpip_full_mock_a.php', 'intermediate' UNION ALL
    SELECT 8, 2, 'Mock 1 Review & Band Estimate', 'courses/CELPIP_Gen/lessons/week8_mock1_review.php', 'intermediate' UNION ALL
    SELECT 9, 1, 'Complete Listening Test + Reading Part 4 (Viewpoints)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c17', 'intermediate' UNION ALL
    SELECT 9, 2, 'Complete Reading Test + Writing Self-Editing Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c18', 'intermediate' UNION ALL
    SELECT 10, 1, 'Complete Speaking Test + Listening Fast-Audio Drills', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c19', 'intermediate' UNION ALL
    SELECT 10, 2, 'Complete Writing Test + Speaking Full-Timed-Task Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c20', 'intermediate' UNION ALL
    SELECT 11, 1, 'Mock Test 2 — Final Assessment', 'resources/mock_tests/celpip_full_mock_b.php', 'intermediate' UNION ALL
    SELECT 11, 2, 'Final Performance Review', 'courses/CELPIP_Gen/lessons/week11_final_review.php', 'intermediate' UNION ALL
    SELECT 12, 1, 'Final Speaking Simulation & Listening Sprints', 'courses/CELPIP_Gen/lessons/week12_final_speaking_listening_sprints.php', 'intermediate' UNION ALL
    SELECT 12, 2, 'Test-Day Coaching', 'courses/CELPIP_Gen/lessons/week12_test_day_coaching.php', 'intermediate'
) x ON x.module_order = m.module_order
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

-- Rollback (destructive — removes all of this course's modules/lessons):
-- DELETE l FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id WHERE c.folder_name='CELPIP_Gen_3Mo';
-- DELETE m FROM modules m JOIN courses c ON c.id=m.course_id WHERE c.folder_name='CELPIP_Gen_3Mo';

-- ============================================================
-- Migration 109_convert_month_courses_to_weeks.sql
-- ============================================================
-- ============================================================
-- Migration 109 -- put the month-based courses' classes under WEEKS
--
-- IELTS_Aca_1Mo / IELTS_Aca_2Mo / IELTS_Aca_3Mo and CELPIP_Gen_1Mo listed their
-- classes under "Month N" modules of 8 classes. Instructor: every course must
-- be organised by weeks. Two classes per week, in the same order:
--   week = CEIL(class / 2)   (class = (month - 1) * 8 + lesson_order)
-- 1-Month = 4 weeks, 2-Month = 8 weeks, 3-Month = 12 weeks. (CELPIP_Gen_2Mo and
-- CELPIP_Gen_3Mo were already weekly.)
--
-- Lesson ROWS AND IDS ARE UNCHANGED (only module_id / lesson_order move), so
-- lesson_progress, course_pacing_items and assignments keep working. Class
-- numbering is therefore unchanged too. Each new week keeps the min_tier of the
-- month it came from. The old "Month" module rows are deleted afterwards.
--
-- Found by folder_name (never ids: ids differ between local and live).
-- Idempotent: a course that already has "Week ..." modules is skipped.
-- Uses real helper tables (tmp_wk_*) dropped at the end, because MySQL 5.7
-- cannot use one TEMPORARY table twice in a statement.
-- Week Briefs authored against an old month module become orphaned (harmless);
-- re-author them per week in sls-admin -> Week Briefs.
-- Run on LOCAL first, then LIVE (back up first).
-- ============================================================

DROP TABLE IF EXISTS tmp_wk_spec;
DROP TABLE IF EXISTS tmp_wk_todo;
DROP TABLE IF EXISTS tmp_wk_map;

CREATE TABLE tmp_wk_spec ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AS
SELECT * FROM (
    SELECT 'IELTS_Aca_3Mo' folder, 1 week_no, 'Week 1 — Orientation, Diagnostic & Listening Start' title
    UNION ALL SELECT 'IELTS_Aca_3Mo', 2, 'Week 2 — Reading & Speaking Foundations'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 3, 'Week 3 — Writing Test 1 & Listening Test 2'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 4, 'Week 4 — Reading Test 2 & Speaking Test 2'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 5, 'Week 5 — Mock Test 1 & Review'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 6, 'Week 6 — Writing Test 2 & Listening Test 3'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 7, 'Week 7 — Reading Test 3 & Speaking Test 3'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 8, 'Week 8 — Writing Test 3 & Mock Test 2'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 9, 'Week 9 — Mock 2 Review & Listening Test 4'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 10, 'Week 10 — Reading Test 4 & Speaking Test 4'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 11, 'Week 11 — Writing Test 4 & Weak-Area Drilling'
    UNION ALL SELECT 'IELTS_Aca_3Mo', 12, 'Week 12 — Final Mock & Test-Day Coaching'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 1, 'Week 1 — Orientation, Diagnostic & Listening Start'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 2, 'Week 2 — Reading & Speaking Foundations'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 3, 'Week 3 — Writing Test 1 & Listening Test 2'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 4, 'Week 4 — Reading Test 2 & Speaking Test 2'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 5, 'Week 5 — Mock Test 1 & Review'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 6, 'Week 6 — Writing Test 2 & Listening Test 3'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 7, 'Week 7 — Reading Test 3 & Speaking Test 3'
    UNION ALL SELECT 'IELTS_Aca_2Mo', 8, 'Week 8 — Writing Test 3 & Final Mock'
    UNION ALL SELECT 'IELTS_Aca_1Mo', 1, 'Week 1 — Introduction & Academic Reading'
    UNION ALL SELECT 'IELTS_Aca_1Mo', 2, 'Week 2 — Writing Tasks 1 & 2'
    UNION ALL SELECT 'IELTS_Aca_1Mo', 3, 'Week 3 — Listening & Speaking Parts 1-2'
    UNION ALL SELECT 'IELTS_Aca_1Mo', 4, 'Week 4 — Speaking Part 3 & Mock Exam'
    UNION ALL SELECT 'CELPIP_Gen_1Mo', 1, 'Week 1 — Introduction & Listening'
    UNION ALL SELECT 'CELPIP_Gen_1Mo', 2, 'Week 2 — Reading & Writing Email'
    UNION ALL SELECT 'CELPIP_Gen_1Mo', 3, 'Week 3 — Speaking Tasks 1-4 & Extended Reading'
    UNION ALL SELECT 'CELPIP_Gen_1Mo', 4, 'Week 4 — Speaking Tasks 5-8, Survey & Practice Mock'
) s;

-- courses still to convert (exist, and have no Week modules yet)
CREATE TABLE tmp_wk_todo ENGINE=InnoDB AS
SELECT c.id AS course_id, CONVERT(c.folder_name USING utf8mb4) AS folder_name  -- CONVERT: live's courses.folder_name may be latin1
FROM courses c
WHERE c.folder_name IN (SELECT DISTINCT folder COLLATE utf8mb4_unicode_ci FROM tmp_wk_spec)
  AND NOT EXISTS (SELECT 1 FROM modules m WHERE m.course_id = c.id AND m.module_title LIKE 'Week %');

-- old lesson -> its new week / position (computed BEFORE anything moves)
CREATE TABLE tmp_wk_map ENGINE=InnoDB AS
SELECT l.id AS lesson_id, t.course_id,
       CEIL(((om.module_order - 1) * 8 + l.lesson_order) / 2) AS new_week,
       MOD(((om.module_order - 1) * 8 + l.lesson_order) - 1, 2) + 1 AS new_lesson_order
FROM tmp_wk_todo t
JOIN modules om ON om.course_id = t.course_id
JOIN lessons l  ON l.module_id = om.id;

-- new week modules, temporary order 100+N (avoids clashing with the old month orders)
INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT t.course_id, s.title, 100 + s.week_no,
       COALESCE((SELECT om.min_tier FROM modules om
                 WHERE om.course_id = t.course_id AND om.module_order = CEIL(((s.week_no - 1) * 2 + 1) / 8)), 'beginner')
FROM tmp_wk_todo t
JOIN tmp_wk_spec s ON s.folder COLLATE utf8mb4_unicode_ci = t.folder_name COLLATE utf8mb4_unicode_ci;

-- move every lesson into its week
UPDATE lessons l
JOIN tmp_wk_map mp ON mp.lesson_id = l.id
JOIN modules nm ON nm.course_id = mp.course_id AND nm.module_order = 100 + mp.new_week
SET l.module_id = nm.id,
    l.lesson_order = mp.new_lesson_order;

-- remove the old month modules (now empty) and finalise week numbering
DELETE om FROM modules om
JOIN tmp_wk_todo t ON t.course_id = om.course_id
WHERE om.module_order < 100
  AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.module_id = om.id);

UPDATE modules m
JOIN tmp_wk_todo t ON t.course_id = m.course_id
SET m.module_order = m.module_order - 100
WHERE m.module_order > 100;

DROP TABLE IF EXISTS tmp_wk_spec;
DROP TABLE IF EXISTS tmp_wk_todo;
DROP TABLE IF EXISTS tmp_wk_map;

-- Verify (expect 4 / 8 / 12 / 4 weeks, 2 lessons in each, 0 lessons outside a week):
--   SELECT c.folder_name, COUNT(DISTINCT m.id) weeks, MIN(x.n) min_per_week, MAX(x.n) max_per_week
--   FROM courses c JOIN modules m ON m.course_id=c.id
--   JOIN (SELECT module_id, COUNT(*) n FROM lessons GROUP BY module_id) x ON x.module_id=m.id
--   WHERE c.folder_name IN ('IELTS_Aca_1Mo','IELTS_Aca_2Mo','IELTS_Aca_3Mo','CELPIP_Gen_1Mo') GROUP BY c.folder_name;
-- Rollback: restore the DB backup (the old Month modules are deleted).

-- ============================================================
-- Migration 113_mirror_celpip_2mo_from_3mo_first_16.sql
-- ============================================================
-- ============================================================
-- Migration 113 -- CELPIP 2-Month = the first 16 classes of the CELPIP 3-Month
--
-- Direction confirmed by the instructor 2026-09-21: the 2-month course is Weeks 1-8 of the
-- 3-month course, verbatim (same classes, same lessons). This copies title, file_path,
-- content, duration, icon and min_tier from CELPIP_Gen_3Mo Weeks 1-8 onto the existing
-- CELPIP_Gen_2Mo lesson rows (matched by week + class-in-week; lesson ids are kept, so
-- student progress rows still point at the right rows) and the week titles/tiers likewise.
--
-- Side effects, on purpose:
--   * Mock 1 is now Class 15 (Week 8) and Mock 1 Review is Class 16 -- there is no second mock
--     in the first 16 classes of the 3-month course.
--   * The 2-month pacing rows for the old Mock Exam 1 (Class 8) / Mock Exam 2 (Class 16) are
--     re-pointed: Mock A -> Class 15, Mock B removed.
--   * courses/CELPIP_Gen_2Mo/classN.php pages are retired (the lessons now open the shared
--     class-day pages in courses/CELPIP_Gen/lessons/).
-- Matches courses by folder_name only (no hardcoded ids). Idempotent.
-- Run on LOCAL first, then LIVE (pull academy first).
-- ============================================================

-- 1. Weeks: titles + tiers
UPDATE modules m2
JOIN courses c2 ON c2.id = m2.course_id AND c2.folder_name = 'CELPIP_Gen_2Mo'
JOIN courses c3 ON c3.folder_name = 'CELPIP_Gen_3Mo'
JOIN modules m3 ON m3.course_id = c3.id AND m3.module_order = m2.module_order
SET m2.module_title = m3.module_title,
    m2.min_tier     = m3.min_tier
WHERE m2.module_order BETWEEN 1 AND 8;

-- 2. Classes: everything the student sees
UPDATE lessons l2
JOIN modules m2 ON m2.id = l2.module_id
JOIN courses c2 ON c2.id = m2.course_id AND c2.folder_name = 'CELPIP_Gen_2Mo'
JOIN courses c3 ON c3.folder_name = 'CELPIP_Gen_3Mo'
JOIN modules m3 ON m3.course_id = c3.id AND m3.module_order = m2.module_order
JOIN lessons l3 ON l3.module_id = m3.id AND l3.lesson_order = l2.lesson_order
SET l2.title            = l3.title,
    l2.file_path        = l3.file_path,
    l2.content          = l3.content,
    l2.duration_minutes = l3.duration_minutes,
    l2.icon             = l3.icon,
    l2.min_tier         = l3.min_tier
WHERE m2.module_order BETWEEN 1 AND 8;

-- 3. Pacing rows: Mock A moves to Class 15; the old Class 16 Mock B row has no place any more
UPDATE course_pacing_items p
JOIN courses c ON c.id = p.course_id AND c.folder_name = 'CELPIP_Gen_2Mo'
JOIN modules m ON m.course_id = c.id AND m.module_order = 8
JOIN lessons l ON l.module_id = m.id AND l.lesson_order = 1
SET p.lesson_id = l.id,
    p.title = 'Class 15 — Mock Exam 1',
    p.offset_days = 48
WHERE p.test_code = 'CELPIP_FULL_MOCK_A';

DELETE p FROM course_pacing_items p
JOIN courses c ON c.id = p.course_id AND c.folder_name = 'CELPIP_Gen_2Mo'
WHERE p.test_code = 'CELPIP_FULL_MOCK_B';

-- Verify:
--   SELECT (m.module_order-1)*2+l.lesson_order cls, l.title, l.file_path FROM lessons l JOIN modules m ON m.id=l.module_id
--     JOIN courses c ON c.id=m.course_id WHERE c.folder_name='CELPIP_Gen_2Mo' ORDER BY cls;   -- same 16 as the 3-Month
-- Rollback: re-run migration 092 (real 2-month schedule), then restore courses/CELPIP_Gen_2Mo/class*.php from git.

-- ============================================================
-- Migration 114_weekly_structure_ielts_gen_and_pte.sql
-- ============================================================
-- ============================================================
-- Migration 114 -- universal rule: no 2- or 3-month course has more than 2 classes a week
--
-- IELTS_Gen_1Mo / IELTS_Gen_2Mo / IELTS_Gen_Mst (3-month) and PTE_Gen_1Mo / PTE_Gen_2Mo /
-- PTE_Gen_3Mo were still grouped in "Month N" modules of 8 classes. Same conversion as
-- migration 109: week = CEIL(class / 2), two classes per week, 4 / 8 / 12 weeks.
--
-- Lesson ROWS AND IDS ARE UNCHANGED (only module_id / lesson_order move), so lesson_progress,
-- course_pacing_items and assignments keep working; class numbering is unchanged. Each week keeps
-- the min_tier of the month it came from. Old "Month" module rows are deleted afterwards.
-- Found by folder_name (never ids). Idempotent: a course that already has "Week ..." modules is skipped.
-- Run on LOCAL first, then LIVE (back up first).
-- ============================================================

DROP TABLE IF EXISTS tmp_wk_spec;
DROP TABLE IF EXISTS tmp_wk_todo;
DROP TABLE IF EXISTS tmp_wk_map;

CREATE TABLE tmp_wk_spec ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AS
SELECT * FROM (
    SELECT 'IELTS_Gen_Mst' folder, 1 week_no, 'Week 1 — Orientation & Diagnostic' title
    UNION ALL SELECT 'IELTS_Gen_Mst', 2, 'Week 2 — Foundations in All Four Skills'
    UNION ALL SELECT 'IELTS_Gen_Mst', 3, 'Week 3 — Listening MCQ, Reading True/False/Not Given & Formal Letters'
    UNION ALL SELECT 'IELTS_Gen_Mst', 4, 'Week 4 — Map Listening, Speaking Part 2 & Mock Test 1'
    UNION ALL SELECT 'IELTS_Gen_Mst', 5, 'Week 5 — Matching, Plan Labelling & Speaking Part 2'
    UNION ALL SELECT 'IELTS_Gen_Mst', 6, 'Week 6 — Reading MCQ, Semi-Formal Letters & Note Completion'
    UNION ALL SELECT 'IELTS_Gen_Mst', 7, 'Week 7 — Speaking Part 3, Sentence Completion & Task 2 Introductions'
    UNION ALL SELECT 'IELTS_Gen_Mst', 8, 'Week 8 — Distractors, Cohesion & Mock Test 2'
    UNION ALL SELECT 'IELTS_Gen_Mst', 9, 'Week 9 — Abstract Speaking, Summary Completion & Essay Body Paragraphs'
    UNION ALL SELECT 'IELTS_Gen_Mst', 10, 'Week 10 — Essay Conclusions & All Five Essay Types'
    UNION ALL SELECT 'IELTS_Gen_Mst', 11, 'Week 11 — Reading & Speaking Mastery'
    UNION ALL SELECT 'IELTS_Gen_Mst', 12, 'Week 12 — Final Preparation & Mock Test 3'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 1, 'Week 1 — Orientation & Diagnostic'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 2, 'Week 2 — Foundations in All Four Skills'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 3, 'Week 3 — Listening MCQ, Reading True/False/Not Given & Formal Letters'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 4, 'Week 4 — Map Listening, Speaking Part 2 & Mock Test 1'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 5, 'Week 5 — Matching, Plan Labelling & Speaking Part 2'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 6, 'Week 6 — Reading MCQ, Semi-Formal Letters & Note Completion'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 7, 'Week 7 — Speaking Part 3, Sentence Completion & Task 2 Introductions'
    UNION ALL SELECT 'IELTS_Gen_2Mo', 8, 'Week 8 — Distractors, Cohesion & Mock Test 2'
    UNION ALL SELECT 'IELTS_Gen_1Mo', 1, 'Week 1 — Orientation & Diagnostic'
    UNION ALL SELECT 'IELTS_Gen_1Mo', 2, 'Week 2 — Foundations in All Four Skills'
    UNION ALL SELECT 'IELTS_Gen_1Mo', 3, 'Week 3 — Listening MCQ, Reading True/False/Not Given & Formal Letters'
    UNION ALL SELECT 'IELTS_Gen_1Mo', 4, 'Week 4 — Map Listening, Speaking Part 2 & Mock Test 1'
    UNION ALL SELECT 'PTE_Gen_3Mo', 1, 'Week 1 — Introduction & Read Aloud'
    UNION ALL SELECT 'PTE_Gen_3Mo', 2, 'Week 2 — Speaking Tasks & Writing Foundations'
    UNION ALL SELECT 'PTE_Gen_3Mo', 3, 'Week 3 — Reading & Listening Foundations'
    UNION ALL SELECT 'PTE_Gen_3Mo', 4, 'Week 4 — AI Scoring Strategies & Mock Exam 1'
    UNION ALL SELECT 'PTE_Gen_3Mo', 5, 'Week 5 — Advanced Speaking & Writing'
    UNION ALL SELECT 'PTE_Gen_3Mo', 6, 'Week 6 — Advanced Reading & Listening'
    UNION ALL SELECT 'PTE_Gen_3Mo', 7, 'Week 7 — Timed Practice'
    UNION ALL SELECT 'PTE_Gen_3Mo', 8, 'Week 8 — AI Score Maximisation & Mock Exam 2'
    UNION ALL SELECT 'PTE_Gen_3Mo', 9, 'Week 9 — Mastery Speaking & Writing'
    UNION ALL SELECT 'PTE_Gen_3Mo', 10, 'Week 10 — Mastery Reading & Listening'
    UNION ALL SELECT 'PTE_Gen_3Mo', 11, 'Week 11 — Full Exam Simulations'
    UNION ALL SELECT 'PTE_Gen_3Mo', 12, 'Week 12 — Score Review & Mock Exam 3'
    UNION ALL SELECT 'PTE_Gen_2Mo', 1, 'Week 1 — Introduction & Read Aloud'
    UNION ALL SELECT 'PTE_Gen_2Mo', 2, 'Week 2 — Speaking Tasks & Writing Foundations'
    UNION ALL SELECT 'PTE_Gen_2Mo', 3, 'Week 3 — Reading & Listening Foundations'
    UNION ALL SELECT 'PTE_Gen_2Mo', 4, 'Week 4 — AI Scoring Strategies & Mock Exam 1'
    UNION ALL SELECT 'PTE_Gen_2Mo', 5, 'Week 5 — Advanced Speaking & Writing'
    UNION ALL SELECT 'PTE_Gen_2Mo', 6, 'Week 6 — Advanced Reading & Listening'
    UNION ALL SELECT 'PTE_Gen_2Mo', 7, 'Week 7 — Timed Practice'
    UNION ALL SELECT 'PTE_Gen_2Mo', 8, 'Week 8 — AI Score Maximisation & Mock Exam 2'
    UNION ALL SELECT 'PTE_Gen_1Mo', 1, 'Week 1 — Introduction & Read Aloud'
    UNION ALL SELECT 'PTE_Gen_1Mo', 2, 'Week 2 — Speaking Tasks & Writing Foundations'
    UNION ALL SELECT 'PTE_Gen_1Mo', 3, 'Week 3 — Reading & Listening Foundations'
    UNION ALL SELECT 'PTE_Gen_1Mo', 4, 'Week 4 — AI Scoring Strategies & Mock Exam 1'
) s;

-- courses still to convert (exist, and have no Week modules yet)
CREATE TABLE tmp_wk_todo ENGINE=InnoDB AS
SELECT c.id AS course_id, CONVERT(c.folder_name USING utf8mb4) AS folder_name  -- CONVERT: live's courses.folder_name may be latin1
FROM courses c
WHERE c.folder_name IN (SELECT DISTINCT folder COLLATE utf8mb4_unicode_ci FROM tmp_wk_spec)
  AND NOT EXISTS (SELECT 1 FROM modules m WHERE m.course_id = c.id AND m.module_title LIKE 'Week %');

-- old lesson -> its new week / position (computed BEFORE anything moves)
CREATE TABLE tmp_wk_map ENGINE=InnoDB AS
SELECT l.id AS lesson_id, t.course_id,
       CEIL(((om.module_order - 1) * 8 + l.lesson_order) / 2) AS new_week,
       MOD(((om.module_order - 1) * 8 + l.lesson_order) - 1, 2) + 1 AS new_lesson_order
FROM tmp_wk_todo t
JOIN modules om ON om.course_id = t.course_id
JOIN lessons l  ON l.module_id = om.id;

-- new week modules, temporary order 100+N (avoids clashing with the old month orders)
INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT t.course_id, s.title, 100 + s.week_no,
       COALESCE((SELECT om.min_tier FROM modules om
                 WHERE om.course_id = t.course_id AND om.module_order = CEIL(((s.week_no - 1) * 2 + 1) / 8)), 'beginner')
FROM tmp_wk_todo t
JOIN tmp_wk_spec s ON s.folder COLLATE utf8mb4_unicode_ci = t.folder_name COLLATE utf8mb4_unicode_ci;

-- move every lesson into its week
UPDATE lessons l
JOIN tmp_wk_map mp ON mp.lesson_id = l.id
JOIN modules nm ON nm.course_id = mp.course_id AND nm.module_order = 100 + mp.new_week
SET l.module_id = nm.id,
    l.lesson_order = mp.new_lesson_order;

-- remove the old month modules (now empty) and finalise week numbering
DELETE om FROM modules om
JOIN tmp_wk_todo t ON t.course_id = om.course_id
WHERE om.module_order < 100
  AND NOT EXISTS (SELECT 1 FROM lessons l WHERE l.module_id = om.id);

UPDATE modules m
JOIN tmp_wk_todo t ON t.course_id = m.course_id
SET m.module_order = m.module_order - 100
WHERE m.module_order > 100;

DROP TABLE IF EXISTS tmp_wk_spec;
DROP TABLE IF EXISTS tmp_wk_todo;
DROP TABLE IF EXISTS tmp_wk_map;

-- Verify (expect 4 / 8 / 12 weeks, exactly 2 classes in each week):
--   SELECT c.folder_name, COUNT(DISTINCT m.id) weeks, MIN(x.n) min_per_week, MAX(x.n) max_per_week
--   FROM courses c JOIN modules m ON m.course_id=c.id
--   JOIN (SELECT module_id, COUNT(*) n FROM lessons GROUP BY module_id) x ON x.module_id=m.id
--   WHERE c.folder_name IN ('IELTS_Gen_1Mo','IELTS_Gen_2Mo','IELTS_Gen_Mst','PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo') GROUP BY c.folder_name;
-- Rollback: restore the DB backup (the old Month modules are deleted).

-- ============================================================
-- Migration 115_regroup_ielts_aca_masterclass_weeks_5_6.sql
-- ============================================================
-- ============================================================
-- Migration 115 -- IELTS Academic Masterclass (IELTS_Aca_Mst): 2 classes a week, always
--
-- Universal rule (instructor 2026-09-21): every week has exactly 2 classes; anything more than
-- that is a lesson UNDER a class. Weeks 5 and 6 of IELTS_Aca_Mst had 7 separate "classes" each
-- (the Task 1 / Task 2 writing series). They become 2 classes per week:
--   Week 5  Class A = lessons 1-3 (Intro, Trends, Comparing)   Class B = lessons 4-7 (Maps, Processes, Structuring, Practice)
--   Week 6  Class A = lessons 1-4 (Intro, Introductions, Body, Arguments)   Class B = lessons 5-7 (Conclusion, Mistakes, Full essay)
-- The first lesson row of each group is kept as the class; its title becomes the lessons joined
-- with " + " (storage separator only -- always displayed one lesson per line, see
-- includes/lesson_title.php), its content the lessons' content one after another, its duration
-- the sum. The other lesson rows are removed. Students who had completed EVERY lesson of a
-- group are marked complete on the merged class; pacing rows are re-pointed.
-- Found by folder_name; idempotent (only acts on weeks that still hold more than 2 lessons).
-- Run on LOCAL first, then LIVE (back up first).
-- ============================================================

SET SESSION group_concat_max_len = 1000000;

DROP TABLE IF EXISTS tmp_mst_map;
DROP TABLE IF EXISTS tmp_mst_group;

CREATE TABLE tmp_mst_map ENGINE=InnoDB AS
SELECT l.id AS lesson_id, m.id AS module_id, m.module_order AS wk, l.lesson_order AS old_order,
       CASE WHEN (m.module_order = 5 AND l.lesson_order <= 3)
              OR (m.module_order = 6 AND l.lesson_order <= 4) THEN 1 ELSE 2 END AS class_no
FROM lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'IELTS_Aca_Mst'
  AND m.module_order IN (5, 6)
  AND (SELECT COUNT(*) FROM lessons x WHERE x.module_id = m.id) > 2;

CREATE TABLE tmp_mst_group ENGINE=InnoDB AS
SELECT mp.module_id, mp.class_no,
       (SELECT l2.id FROM lessons l2 JOIN tmp_mst_map m2 ON m2.lesson_id = l2.id
         WHERE m2.module_id = mp.module_id AND m2.class_no = mp.class_no ORDER BY m2.old_order LIMIT 1) AS keeper_id,
       COUNT(*) AS member_count,
       GROUP_CONCAT(l.title ORDER BY mp.old_order SEPARATOR ' + ') AS new_title,
       GROUP_CONCAT(CONCAT('<h5>', l.title, '</h5>', IFNULL(l.content, '')) ORDER BY mp.old_order SEPARATOR '\n') AS new_content,
       SUM(IFNULL(l.duration_minutes, 0)) AS new_duration
FROM tmp_mst_map mp
JOIN lessons l ON l.id = mp.lesson_id
GROUP BY mp.module_id, mp.class_no;

-- 1. students who finished every lesson of a group are complete on the merged class
INSERT INTO lesson_progress (student_id, lesson_id, completed, completed_at)
SELECT lp.student_id, g.keeper_id, 1, MAX(lp.completed_at)
FROM lesson_progress lp
JOIN tmp_mst_map mp ON mp.lesson_id = lp.lesson_id
JOIN tmp_mst_group g ON g.module_id = mp.module_id AND g.class_no = mp.class_no
WHERE lp.completed = 1
  AND NOT EXISTS (SELECT 1 FROM lesson_progress e WHERE e.student_id = lp.student_id AND e.lesson_id = g.keeper_id)
GROUP BY lp.student_id, g.keeper_id, g.member_count
HAVING COUNT(DISTINCT lp.lesson_id) = g.member_count;

-- 2. pacing rows follow their lesson to the merged class
UPDATE course_pacing_items p
JOIN tmp_mst_map mp ON mp.lesson_id = p.lesson_id
JOIN tmp_mst_group g ON g.module_id = mp.module_id AND g.class_no = mp.class_no
SET p.lesson_id = g.keeper_id;

-- 3. the class = first lesson row, carrying every lesson
UPDATE lessons l
JOIN tmp_mst_group g ON g.keeper_id = l.id
SET l.title = g.new_title,
    l.content = g.new_content,
    l.duration_minutes = g.new_duration,
    l.lesson_order = g.class_no;

-- 4. remove the other lesson rows (and any progress on them)
DELETE lp FROM lesson_progress lp
JOIN tmp_mst_map mp ON mp.lesson_id = lp.lesson_id
JOIN tmp_mst_group g ON g.module_id = mp.module_id AND g.class_no = mp.class_no
WHERE mp.lesson_id <> g.keeper_id;

DELETE l FROM lessons l
JOIN tmp_mst_map mp ON mp.lesson_id = l.id
JOIN tmp_mst_group g ON g.module_id = mp.module_id AND g.class_no = mp.class_no
WHERE mp.lesson_id <> g.keeper_id;

DROP TABLE IF EXISTS tmp_mst_map;
DROP TABLE IF EXISTS tmp_mst_group;

-- Verify (expect 26 -> 16 classes, exactly 2 in every week):
--   SELECT m.module_order, COUNT(*) FROM modules m JOIN courses c ON c.id=m.course_id JOIN lessons l ON l.module_id=m.id
--   WHERE c.folder_name='IELTS_Aca_Mst' GROUP BY m.id ORDER BY 1;
-- Rollback: restore the DB backup (the individual lesson rows are deleted).

-- ============================================================
-- Verify: every 1/2/3-month course should show exactly 2 lessons
-- per week module (IELTS_Aca_Crash is the one deliberate exception).
-- ============================================================
SELECT c.folder_name, COUNT(DISTINCT m.id) weeks, MAX(x.n) max_per_week, MIN(x.n) min_per_week
FROM courses c JOIN modules m ON m.course_id=c.id
JOIN (SELECT module_id, COUNT(*) n FROM lessons GROUP BY module_id) x ON x.module_id=m.id
GROUP BY c.id ORDER BY c.folder_name;
