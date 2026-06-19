-- ============================================================
-- Migration 026 — Seed IELTS_FM2_W (Writing Full Mock Test 2)
-- Cambridge IELTS GT Test 2
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
SELECT 'IELTS_FM2_W', 'IELTS Full Mock 2 — Writing',
       'Cambridge IELTS GT Test 2 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM2_W');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM2_W' LIMIT 1);

-- ============================================================
-- Step 2: Insert both writing tasks
-- Skipped entirely if ANY questions already exist for this test.
-- No question_options or question_correct_answers needed —
-- writing tasks are AI-graded (essay type).
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, question_text, instructions, question_type, points, display_order)
SELECT test_id, part_number, question_number, question_text, instructions, question_type, points, display_order
FROM (
    -- Task 1: Letter to the editor about town centres looking similar (150+ words)
    SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
           'You have just read an article in a national newspaper which claims that town centres in your country all look very similar to each other. You don''t fully agree with this opinion.\n\nWrite a letter to the editor of the newspaper. In your letter:\n• say which points in the article you agree with\n• explain ways in which your town centre is different from most other town centres\n• offer to give a guided tour of your town to the writer of the article' AS question_text,
           'Write at least 150 words.\nYou do NOT need to write any addresses.\nBegin your letter as follows:  Dear Sir or Madam,' AS instructions,
           'essay' AS question_type,
           1.0 AS points, 10 AS display_order
    UNION ALL
    -- Task 2: Trying new things vs. familiar routine essay (250+ words)
    SELECT @tid, 1, 2,
           'Some people like to try new things, for example, places to visit and types of food. Other people prefer to keep doing things they are familiar with.\n\nDiscuss both these attitudes and give your own opinion.',
           'Give reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.',
           'essay', 1.0, 20
) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- Verify: expect 2 questions
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM2_W');
