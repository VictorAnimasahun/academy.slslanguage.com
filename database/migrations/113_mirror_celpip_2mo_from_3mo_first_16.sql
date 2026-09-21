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
