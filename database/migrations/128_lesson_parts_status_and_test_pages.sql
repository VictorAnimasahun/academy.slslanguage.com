-- ============================================================
-- Migration 128 -- Coming Soon at class / piece level (follows migration 127, which does it at course level)
--
-- lesson_parts.status: 'ready' | 'coming_soon'. It only matters for a piece with NO page of its own:
--   - a piece that has a page (file_path) is judged by the page: an EMPTY lesson shell is Coming Soon (worked
--     out live from the file, so developing the lesson needs no data change); any other page is ready;
--   - a piece with no page is Coming Soon when status = 'coming_soon' (tests/mocks not built yet; all of PTE).
-- Test pieces of IELTS Academic 2/3-Month that already have a test page now point straight at it (file_path),
-- so the overview links the test itself, not the class page.
-- A class is Coming Soon when every one of its pieces is.
--
-- NOT idempotent (ALTER TABLE ... ADD COLUMN). Needs migration 126 first. Run on LOCAL, then LIVE.
-- ============================================================

SET NAMES utf8mb4;

ALTER TABLE lesson_parts ADD COLUMN status ENUM('ready','coming_soon') NOT NULL DEFAULT 'ready' AFTER kind;

-- IELTS_Aca_2Mo: test pieces that already have a test page
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2
   AND lp.title='Listening Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_reading_academic_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1
   AND lp.title='Reading Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_002.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2
   AND lp.title='Speaking Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_writing_academic_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1
   AND lp.title='Writing Test 1 (Timed)' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_formats_sample.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1
   AND lp.title='Listening Formats' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_002.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2
   AND lp.title='Listening Test 2' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_003.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2
   AND lp.title='Speaking Test 2' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_004.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_2Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2
   AND lp.title='Speaking Test 3' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;

-- IELTS_Aca_3Mo: test pieces that already have a test page
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=1 AND l.lesson_order=2
   AND lp.title='Listening Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_reading_academic_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=1
   AND lp.title='Reading Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_002.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=2 AND l.lesson_order=2
   AND lp.title='Speaking Test 1' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_writing_academic_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1
   AND lp.title='Writing Test 1 (Timed)' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_formats_sample.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=1
   AND lp.title='Listening Formats' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_listening_002.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=3 AND l.lesson_order=2
   AND lp.title='Listening Test 2' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_003.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=4 AND l.lesson_order=2
   AND lp.title='Speaking Test 2' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_004.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=7 AND l.lesson_order=2
   AND lp.title='Speaking Test 3' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.file_path='resources/practice_tests/ielts_speaking_001.php'
 WHERE c.id=(SELECT id FROM courses WHERE folder_name='IELTS_Aca_3Mo' ORDER BY is_visible DESC, id LIMIT 1) AND m.module_order=10 AND l.lesson_order=2
   AND lp.title='Speaking Test 4' AND lp.kind IN ('practice_test','mock_test') AND lp.file_path IS NULL;

-- Pieces with no page yet: tests and mocks that are not built (IELTS Academic 2/3-Month), and every piece of PTE
UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.status='coming_soon'
 WHERE lp.file_path IS NULL AND lp.kind IN ('practice_test','mock_test') AND c.folder_name IN ('IELTS_Aca_2Mo','IELTS_Aca_3Mo');

UPDATE lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
   SET lp.status='coming_soon'
 WHERE lp.file_path IS NULL AND c.folder_name IN ('PTE_Gen_1Mo','PTE_Gen_2Mo','PTE_Gen_3Mo');

-- Verify
SELECT c.folder_name, lp.kind, COUNT(*) pieces, SUM(lp.file_path IS NOT NULL) with_page, SUM(lp.status='coming_soon') coming_soon_no_page
  FROM lesson_parts lp JOIN lessons l ON l.id=lp.lesson_id JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
 WHERE c.folder_name IN ('IELTS_Aca_2Mo','IELTS_Aca_3Mo','PTE_Gen_1Mo') GROUP BY c.folder_name, lp.kind ORDER BY 1,2;
