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
