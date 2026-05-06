-- Migration 005: Set file_path for IELTS General 3-Month Masterclass lessons
-- Each lesson maps to a PHP file in courses/IELTS_Gen_Mst/
-- Class 1  → intro.php
-- Classes 2–24 → class02.php … class24.php
-- Applied on LOCAL:  [ ] Date: ___________
-- Applied on LIVE:   [ ] Date: ___________

SET @course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst' LIMIT 1);

UPDATE lessons l
JOIN modules m ON l.module_id = m.id
SET l.file_path = CASE
    WHEN (m.module_order - 1) * 8 + l.lesson_order = 1
        THEN 'intro.php'
    ELSE CONCAT('class', LPAD((m.module_order - 1) * 8 + l.lesson_order, 2, '0'), '.php')
END
WHERE l.course_id = @course_id;

-- VERIFY
-- SELECT l.id, l.title, l.lesson_order, m.module_order,
--        (m.module_order - 1) * 8 + l.lesson_order AS global_class, l.file_path
-- FROM lessons l
-- JOIN modules m ON l.module_id = m.id
-- WHERE l.course_id = @course_id
-- ORDER BY m.module_order, l.lesson_order;
-- Expected: 24 rows, file_path = intro.php, class02.php ... class24.php
