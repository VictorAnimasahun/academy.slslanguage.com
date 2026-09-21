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
