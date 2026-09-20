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
