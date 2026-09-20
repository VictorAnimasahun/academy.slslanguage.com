-- ============================================================
-- Migration 110 -- IELTS Academic Writing Test 1 (first full Academic writing test)
--
-- Content: official IELTS.org Academic Writing sample tasks 1A (bar chart, 20 min)
-- and 2A (essay, 40 min), supplied by the instructor 2026-09-20. Pages:
--   resources/practice_tests/ielts_writing_academic_001.php      (hub)
--   resources/practice_tests/ielts_writing_academic_t1_001.php   (Task 1)
--   resources/practice_tests/ielts_writing_academic_t2_001.php   (Task 2)
--   resources/model_answers/model_answers_academic_writing_1.php (Band 5/6 scripts)
-- Both tasks are open-ended and AI-marked (essay_analyzer.php), so no question rows.
-- Wires Class 5 ("Writing Test 1 (Timed) + Listening Formats") of IELTS_Aca_2Mo and
-- IELTS_Aca_3Mo to the hub and drops the "no Academic Writing test yet" note.
-- Matches the class by title (no ids, no dependence on module layout).
-- Idempotent. Run on LOCAL first, then LIVE.
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_ACA_001',
       'IELTS Academic Writing Task 1 – Test 1',
       'A 20-minute timed IELTS Academic Writing Task 1 (bar chart). AI-graded via essay_analyzer.',
       'IELTS', 20, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_ACA_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W2_ACA_001',
       'IELTS Academic Writing Task 2 – Test 1',
       'A 40-minute timed IELTS Academic Writing Task 2 essay. AI-graded via essay_analyzer.',
       'IELTS', 40, 1, 1, 0, 'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_ACA_001');

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/practice_tests/ielts_writing_academic_001.php',
    l.content   = REPLACE(l.content, '<em>No Academic Writing Test 1 exists on the platform yet — only a General Training version.</em>', '')
WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo')
  AND l.title = 'Writing Test 1 (Timed) + Listening Formats';

-- Verify: SELECT code, duration_minutes FROM tests WHERE code LIKE 'IELTS_PT_W%_ACA_001';   -- 2 rows
--         SELECT c.folder_name, l.file_path FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
--         WHERE l.title = 'Writing Test 1 (Timed) + Listening Formats';   -- both -> the hub path
-- Rollback: DELETE FROM tests WHERE code IN ('IELTS_PT_W1_ACA_001','IELTS_PT_W2_ACA_001'); UPDATE lessons SET file_path=NULL WHERE title='Writing Test 1 (Timed) + Listening Formats' AND file_path LIKE '%ielts_writing_academic_001%';
