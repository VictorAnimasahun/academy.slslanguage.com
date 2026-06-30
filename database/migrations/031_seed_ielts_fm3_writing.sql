-- ============================================================
-- Migration 031 — Seed IELTS_FM3_W (Writing Full Mock Test 3)
-- Cambridge IELTS GT Test 3
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
SELECT 'IELTS_FM3_W', 'IELTS Full Mock 3 — Writing',
       'Cambridge IELTS GT Test 3 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_W');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM3_W' LIMIT 1);

-- ============================================================
-- Step 2: Insert both writing tasks
-- Skipped entirely if ANY questions already exist for this test.
-- No question_options or question_correct_answers needed —
-- writing tasks are AI-graded (essay type).
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, question_text, instructions, question_type, points, display_order)
SELECT test_id, part_number, question_number, question_text, instructions, question_type, points, display_order
FROM (
    -- Task 1: Letter to a magazine editor about a book that influenced you (150+ words)
    SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
           'A magazine wants to include contributions from its readers for an article called ''The book that influenced me most''.\n\nWrite a letter to the editor of the magazine about the book that influenced you most. In your letter:\n• describe what this book was about\n• explain how this book influenced you\n• say whether this book would be likely to influence other people' AS question_text,
           'Write at least 150 words.\nYou do NOT need to write any addresses.\nBegin your letter as follows:  Dear Sir or Madam,' AS instructions,
           'essay' AS question_type,
           1.0 AS points, 10 AS display_order
    UNION ALL
    -- Task 2: Living close to where you were born essay (250+ words)
    SELECT @tid, 1, 2,
           'Some people spend most of their lives living close to where they were born.\n\nWhat might be the reasons for this?\nWhat are the advantages and disadvantages?',
           'Give reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.',
           'essay', 1.0, 20
) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- Verify: expect 2 questions
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_W');
