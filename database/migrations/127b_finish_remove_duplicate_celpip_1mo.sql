-- ============================================================
-- Migration 127b -- finish migration 127 (only step 1.4: remove the duplicate CELPIP General 1-Month row)
--
-- WHY THIS FILE EXISTS: the first version of 127 stopped with "#1054 Unknown column 'c.folder_name' in 'WHERE'" on the
-- week_briefs DELETE (older MySQL/MariaDB do not allow that construct). Everything BEFORE that statement had already run
-- (the availability column, Coming Soon courses, the paid Academic Crash Course, the two courses shown). Do NOT re-run 127
-- (its ALTER TABLE would fail: the column exists). Run only this file. It is safe to run more than once.
--
-- Deletes the hidden CELPIP_Gen_1Mo row that shares its folder with a visible course and has no enrolments.
-- ============================================================

SET @dup_course = (SELECT c.id FROM courses c
                    WHERE c.folder_name = 'CELPIP_Gen_1Mo' AND c.is_visible = 0
                      AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
                      AND (SELECT COUNT(*) FROM courses v WHERE v.folder_name = 'CELPIP_Gen_1Mo' AND v.is_visible = 1) > 0
                    ORDER BY c.id LIMIT 1);
DELETE wb FROM week_briefs wb JOIN modules m ON m.id = wb.module_id WHERE m.course_id = @dup_course;
DELETE FROM courses WHERE id = @dup_course;

-- Verify: exactly one CELPIP_Gen_1Mo row should remain, and it should be visible
SELECT id, folder_name, is_visible, availability FROM courses WHERE folder_name = 'CELPIP_Gen_1Mo';
