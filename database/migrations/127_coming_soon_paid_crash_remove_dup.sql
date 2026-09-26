-- ============================================================
-- Migration 127 -- Step 1 issues 1.1-1.5 (instructor decisions, 2026-09-26)
--
-- RULE: anything with nothing in it yet says "Coming Soon" -- courses, classes, lessons, tests, mocks. Nothing is
-- hidden or retired for being empty. "Coming Soon" is decided at COURSE level by courses.availability (a
-- business decision: "we are ready to sell this"), and at CLASS / PIECE level by what the page actually holds
-- (an empty lesson page, a test that has no page yet).
--
-- 1.1 / 1.2  IELTS Crash Course, CELPIP Crash Course, PTE Academic 1/2/3-Month: stay visible, availability =
--            coming_soon (no price/buy/enrol shown, enrolment refused).
-- 1.3        IELTS Academic Crash Course is PAID (a 1-month programme): sold through Selar like the 1-month
--            plans (selar_months = 1 blocks self-enrolment; the course page and module pages check it),
--            price = the 1-month price (90,000 / compare 105,000: the instructor said crash courses are all
--            one month -- confirm the amount under decision 2.4). Module 1 ("Introduction", one page holding its
--            five short lessons) stays free as the preview; every later module needs the 1-month plan and its pages
--            require enrolment (bought, or the IELTS Academic 1-Month plan). Preview rule still under decision 2.6.
-- 1.4        Duplicate CELPIP General 1-Month row removed. Guarded: only a HIDDEN row that shares its folder with a
--            VISIBLE course and has NO enrolments is deleted (modules, lessons, progress cascade).
-- 1.5        IELTS Academic 3-Month and IELTS General 2-Month become visible (unbuilt classes show Coming Soon).
--            NOTE: 3-month/2-month Selar purchases can now be redeemed for them ("choose your course").
--
-- NOT idempotent (ALTER TABLE ... ADD COLUMN; this MySQL has no ADD COLUMN IF NOT EXISTS). Run once on LOCAL, then LIVE.
-- ============================================================

ALTER TABLE courses ADD COLUMN availability ENUM('available','coming_soon') NOT NULL DEFAULT 'available' AFTER is_visible;

-- 1.1 / 1.2
UPDATE courses SET availability = 'coming_soon'
WHERE folder_name IN ('IELTS_Crash_Course','CELPIP_Crash_Course','PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo');

-- 1.3
UPDATE courses SET selar_months = 1, price = 90000.00, compare_price = 105000.00 WHERE folder_name = 'IELTS_Aca_Crash';
-- interim buy link: the shared 1-month Selar link the 1-month plans use (a course of its own gets its own link under decision 2.5)
UPDATE courses c JOIN courses o ON o.folder_name = 'IELTS_Aca_1Mo' SET c.buy_url = o.buy_url WHERE c.folder_name = 'IELTS_Aca_Crash' AND (c.buy_url IS NULL OR c.buy_url = '');
UPDATE modules m JOIN courses c ON c.id = m.course_id
   SET m.min_tier = IF(m.module_order = 1, 'beginner', 'intermediate')
 WHERE c.folder_name = 'IELTS_Aca_Crash';
UPDATE lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET l.min_tier = IF(m.module_order = 1, 'beginner', 'intermediate')
 WHERE c.folder_name = 'IELTS_Aca_Crash';

-- 1.5
UPDATE courses SET is_visible = 1 WHERE folder_name IN ('IELTS_Aca_3Mo','IELTS_Gen_2Mo');

-- 1.4 (guarded). The duplicate is found ONCE and its id kept in a variable: older MySQL / MariaDB refuse a
-- correlated reference inside a sub-query in FROM (error 1054), which the first version of this file used.
-- week_briefs has no foreign key, so its rows are removed explicitly first; modules, lessons and progress cascade.
-- If there is no such duplicate the variable is NULL and both deletes do nothing.
SET @dup_course = (SELECT c.id FROM courses c
                    WHERE c.folder_name = 'CELPIP_Gen_1Mo' AND c.is_visible = 0
                      AND NOT EXISTS (SELECT 1 FROM enrollments e WHERE e.course_id = c.id)
                      AND (SELECT COUNT(*) FROM courses v WHERE v.folder_name = 'CELPIP_Gen_1Mo' AND v.is_visible = 1) > 0
                    ORDER BY c.id LIMIT 1);
DELETE wb FROM week_briefs wb JOIN modules m ON m.id = wb.module_id WHERE m.course_id = @dup_course;
DELETE FROM courses WHERE id = @dup_course;

-- Verify
SELECT id, folder_name, is_visible, availability, selar_months, price FROM courses
 WHERE folder_name IN ('IELTS_Crash_Course','CELPIP_Crash_Course','PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo','IELTS_Aca_Crash','IELTS_Aca_3Mo','IELTS_Gen_2Mo','CELPIP_Gen_1Mo') ORDER BY folder_name;
SELECT (SELECT COUNT(*) FROM courses WHERE folder_name = 'CELPIP_Gen_1Mo') AS celpip_1mo_rows_left;
