-- ============================================================
-- Migration 139 -- four pieces whose title says "Test-Day" are lessons, not practice tests; each gets its own lesson page
--
-- Founder rule (2026-09-26): each piece is a lesson / resource / practice test / mock test, and "no 'Test' in the title = lesson"
-- (migration 134 already did this for CELPIP 3-Month's "Test-Day Coaching"). The same mistake was left in four places, where the
-- word "Test" is only part of "Test-Day":
--   IELTS Academic Masterclass -- 3 Months, Class 24, piece 2  "Test-Day Coaching"
--   PTE Academic Crash Course / Masterclass 2 / Masterclass 3, Class 7, piece 1  "AI Scoring Strategies & Test-Day Preparation"
--     (these were also the "classes that hold only one practice test": they are one lesson, not a test)
-- Each becomes kind 'lesson' with its own page (an empty file on disk until the lesson is written; the AI-drafted text, when
-- present, is marked as a draft in the page).
--
-- Found by folder / class position / title, never by id. Safe to run more than once. Needs 126 and 128.
-- Written for older MySQL/MariaDB (no correlated derived tables).
-- ============================================================

SET NAMES utf8mb4;

UPDATE lesson_parts lp
  JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.kind = 'lesson',
       lp.file_path = 'courses/IELTS_Aca_3Mo/lessons/class24_p2_test-day-coaching.php'
 WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 12 AND l.lesson_order = 2 AND lp.title = 'Test-Day Coaching';

UPDATE lesson_parts lp
  JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
   SET lp.kind = 'lesson',
       lp.file_path = CONCAT('courses/', c.folder_name, '/lessons/class07_p1_ai-scoring-strategies-test-day-preparation.php')
 WHERE c.folder_name IN ('PTE_Gen_1Mo', 'PTE_Gen_2Mo', 'PTE_Gen_3Mo') AND m.module_order = 4 AND l.lesson_order = 1
   AND lp.title = 'AI Scoring Strategies & Test-Day Preparation';

-- Verify: four lessons, each with a page
SELECT c.folder_name, lp.title, lp.kind, lp.status, lp.file_path
  FROM lesson_parts lp JOIN lessons l ON l.id = lp.lesson_id JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
 WHERE lp.title IN ('Test-Day Coaching', 'AI Scoring Strategies & Test-Day Preparation') ORDER BY c.folder_name;
