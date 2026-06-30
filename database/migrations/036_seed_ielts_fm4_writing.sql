-- ============================================================
-- Migration 036 — Seed IELTS_FM4_W (Writing Full Mock Test 4)
-- Cambridge IELTS 16 Test 4
--
-- Writing prompts are COMPLETE — no placeholders needed.
-- Task 1 and Task 2 loaded into questions table; mock_writing.php
-- reads them by question_number (1 = Task 1, 2 = Task 2).
--
-- NON-DESTRUCTIVE: INSERT is skipped if questions already exist.
-- Safe to re-run on LOCAL (useraccounts) and LIVE (slslanguage_db).
-- ============================================================

-- Step 1: Ensure test record exists
INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_W', 'IELTS Full Mock 4 — Writing',
       'Cambridge IELTS 16 Test 4 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_W');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM4_W' LIMIT 1);

-- ============================================================
-- Step 2: Insert both writing tasks
-- Skipped entirely if ANY questions already exist for this test.
-- No question_options or question_correct_answers needed —
-- writing tasks are AI-graded (essay type).
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, question_text, instructions, question_type, points, display_order)
SELECT test_id, part_number, question_number, question_text, instructions, question_type, points, display_order
FROM (
    -- Task 1: Email to a friend advising on student accommodation (150+ words)
    SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
           'Your friend has been offered a place on a course at the university where you studied. He/She would like your advice about finding a place to live.\n\nWrite an email to your friend. In your email:\n• describe where you lived when you were a student at the university\n• recommend the best way for him/her to look for accommodation\n• warn him/her of mistakes students make when choosing accommodation' AS question_text,
           'Write at least 150 words.\nYou do NOT need to write any addresses.\nBegin your email as follows:  Dear ___,' AS instructions,
           'essay' AS question_type,
           1.0 AS points, 10 AS display_order
    UNION ALL
    -- Task 2: Best time in history to be living essay (250+ words)
    SELECT @tid, 1, 2,
           'Some people say that now is the best time in history to be living.\n\nWhat is your opinion about this?\nWhat other time in history would be interesting to live in?',
           'Give reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.',
           'essay', 1.0, 20
) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- Verify: expect 2 questions
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_W');
