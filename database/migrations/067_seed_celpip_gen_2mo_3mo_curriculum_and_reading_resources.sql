-- Migration 067 — Seed CELPIP_Gen_2Mo / CELPIP_Gen_3Mo class curriculum,
-- then wire in the 4 protected CELPIP Reading Part 1-4 slide-deck resources.
--
-- Both courses' `courses`/`modules` rows already existed (modules: Month 1/2
-- for the 2-month plan, Month 1/2/3 for the 3-month Masterclass), but had
-- ZERO lessons ("classes") under them -- the 24/16-class curriculum only
-- existed as unrun draft SQL in documentation/migrations/add_celpip_ieltsaca_pte_courses.sql.
-- This migration actually applies that draft to course_id 13 and 14 (the
-- clean, non-duplicated CELPIP General course rows -- course_id 12/19 for
-- the 1-month plan are left untouched; 12 has a pre-existing duplicate
-- module row and 19 is an orphan duplicate course, neither part of this task).
--
-- Each lessons INSERT is guarded so it's a no-op if that course already has
-- lessons (re-run safe).

-- ── Step 1: CELPIP_Gen_2Mo (course_id via folder_name) — Month 1 (8 classes) ──
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
  AND NOT EXISTS (SELECT 1 FROM lessons l2 WHERE l2.course_id = c.id);

-- ── Step 2: CELPIP_Gen_2Mo — Month 2 (8 classes) ──────────────────────────────
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
  AND NOT EXISTS (SELECT 1 FROM lessons l2 WHERE l2.course_id = c.id AND l2.title = 'Advanced Listening — Discussion & Workplace');

-- ── Step 3: CELPIP_Gen_3Mo — Month 1 (8 classes) ──────────────────────────────
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
  AND NOT EXISTS (SELECT 1 FROM lessons l2 WHERE l2.course_id = c.id);

-- ── Step 4: CELPIP_Gen_3Mo — Month 2 (8 classes) ──────────────────────────────
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
  AND NOT EXISTS (SELECT 1 FROM lessons l2 WHERE l2.course_id = c.id AND l2.title = 'Advanced Listening — Discussion & Workplace');

-- ── Step 5: CELPIP_Gen_3Mo — Month 3 (8 classes) ──────────────────────────────
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
  AND NOT EXISTS (SELECT 1 FROM lessons l2 WHERE l2.course_id = c.id AND l2.title = 'Precision Listening — Accent Variants & Fast Speech');

-- ── Step 6: wire the 4 protected Reading resources into the Reading classes ──
-- Part 1 & Part 2 sit at the same title in both courses (Month 1 is
-- identical between the 2-month and 3-month plans), so one UPDATE each
-- covers both. Part 3 (Month 2) diverges: the 3-month plan gets Part 3 only
-- (its own Month 3 class hosts Part 4); the 2-month plan has no Month 3, so
-- its Month-2 Reading class links to a small hub page listing both Part 3
-- and Part 4, so 2-month students -- most students, per instructor -- still
-- get all four parts somewhere.
UPDATE lessons l JOIN courses c ON c.id = l.course_id
SET l.file_path = 'resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt1'
WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo') AND l.title = 'Reading — Correspondence & Diagram';

UPDATE lessons l JOIN courses c ON c.id = l.course_id
SET l.file_path = 'resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt2'
WHERE c.folder_name IN ('CELPIP_Gen_2Mo', 'CELPIP_Gen_3Mo') AND l.title = 'Reading — Extended Passage & Graph Strategies';

UPDATE lessons l JOIN courses c ON c.id = l.course_id
SET l.file_path = 'resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt3'
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND l.title = 'Advanced Reading — Speed & Accuracy Drills';

UPDATE lessons l JOIN courses c ON c.id = l.course_id
SET l.file_path = 'resources/protected_viewer/reading_hub.php?parts=celpip_reading_pt3,celpip_reading_pt4'
WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND l.title = 'Advanced Reading — Speed & Accuracy Drills';

UPDATE lessons l JOIN courses c ON c.id = l.course_id
SET l.file_path = 'resources/protected_viewer/celpip_reading.php?part=celpip_reading_pt4'
WHERE c.folder_name = 'CELPIP_Gen_3Mo' AND l.title = 'Mastery Reading — Inference & Complex Structures';

-- Verify: expect 16 lessons for CELPIP_Gen_2Mo, 24 for CELPIP_Gen_3Mo, and
-- the 5 file_path UPDATEs above to have touched exactly 1/1/1/1/1 rows.
-- SELECT c.folder_name, COUNT(*) FROM lessons l JOIN courses c ON c.id=l.course_id WHERE c.folder_name IN ('CELPIP_Gen_2Mo','CELPIP_Gen_3Mo') GROUP BY c.folder_name;
-- SELECT c.folder_name, l.title, l.file_path FROM lessons l JOIN courses c ON c.id=l.course_id WHERE l.file_path LIKE 'resources/protected_viewer%' ORDER BY c.folder_name, l.lesson_order;
