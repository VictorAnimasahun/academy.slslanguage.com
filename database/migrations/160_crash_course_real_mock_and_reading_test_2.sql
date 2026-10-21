-- ============================================================
-- Migration 160 -- CELPIP Crash Course sits the real Mock Test; IELTS Academic Crash Course gets Reading Test 2
--
-- Founder, 2026-10-21:
--  * CELPIP Crash Course (12), Class 8: "Practice Mock Exam — All 4 Skills" opened the short CELPIP Diagnostic Test.
--    "It should just open the Mock Test 1 in the 2 and 3 months courses": it now opens CELPIP Full Mock Test A
--    (resources/mock_tests/celpip_full_mock_a.php), is called "Mock Test — Full-Length, All 4 Sections" (the crash
--    course has one mock) and runs 3 hours like the other CELPIP mock classes. Week 4's title follows.
--  * IELTS Academic Crash Course (23), Class 7: "Reading Test 2" was Coming Soon; it opens the same test as the
--    Masterclasses (resources/practice_tests/ielts_reading_academic_002.php). The page's course lock now includes
--    the crash course.
--  * Every CELPIP full mock item now carries its test code, so a submitted mock counts towards its class
--    (2-Month Mock 1 already did; 2-Month Mock 2 and 3-Month Mocks 1 and 2 didn't).
-- IELTS Academic Mock Test 1 stays Coming Soon in every course: the platform has no IELTS Academic full mock yet
-- (IELTS Full Mock Tests 1-4 are General Training).
--
-- Plain updates matched by course + class + title, so re-running is safe. MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

-- CELPIP Crash Course, Class 8: the real mock.
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.title = 'Mock Test — Full-Length, All 4 Sections',
       p.file_path = 'resources/mock_tests/celpip_full_mock_a.php',
       p.test_code = 'CELPIP_FULL_MOCK_A'
 WHERE l.course_id = 12 AND (m.module_order - 1) * 2 + l.lesson_order = 8 AND p.kind = 'mock_test';

UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
   SET l.title = 'Mock Test — Full-Length, All 4 Sections',
       l.file_path = 'resources/mock_tests/celpip_full_mock_a.php',
       l.duration_minutes = 180
 WHERE l.course_id = 12 AND (m.module_order - 1) * 2 + l.lesson_order = 8;

UPDATE modules SET module_title = 'Week 4 — Speaking Tasks 5-8, Survey & Mock Test'
 WHERE course_id = 12 AND module_order = 4;

-- IELTS Academic Crash Course, Class 7: Reading Test 2.
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.status = 'ready',
       p.file_path = 'resources/practice_tests/ielts_reading_academic_002.php',
       p.test_code = 'IELTS_PT_R_ACA_002'
 WHERE l.course_id = 23 AND (m.module_order - 1) * 2 + l.lesson_order = 7 AND p.title = 'Reading Test 2';

-- CELPIP full mocks count towards their class.
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
   SET p.test_code = CASE WHEN l.file_path LIKE '%celpip_full_mock_a.php' THEN 'CELPIP_FULL_MOCK_A' ELSE 'CELPIP_FULL_MOCK_B' END
 WHERE l.course_id IN (13, 14) AND p.kind = 'mock_test' AND (p.test_code IS NULL OR p.test_code = '')
   AND (l.file_path LIKE '%celpip_full_mock_a.php' OR l.file_path LIKE '%celpip_full_mock_b.php');

COMMIT;
