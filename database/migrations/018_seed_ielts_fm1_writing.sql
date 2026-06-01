-- ============================================================
-- Migration 018 — Seed IELTS_FM1_W (Writing Full Mock Test 1)
-- Cambridge IELTS General Training Test 1
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
SELECT 'IELTS_FM1_W', 'IELTS Full Mock 1 — Writing',
       'Cambridge IELTS GT Test 1 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_W');

SET @tid = (SELECT id FROM tests WHERE code = 'IELTS_FM1_W' LIMIT 1);

-- ============================================================
-- Step 2: Insert both writing tasks
-- Skipped entirely if ANY questions already exist for this test.
-- No question_options or question_correct_answers needed —
-- writing tasks are AI-graded (essay type).
-- ============================================================
INSERT INTO questions (test_id, part_number, question_number, question_text, instructions, question_type, points, display_order)
SELECT test_id, part_number, question_number, question_text, instructions, question_type, points, display_order
FROM (
    -- Task 1: Letter to Mrs Barrett (150+ words)
    SELECT @tid AS test_id, 1 AS part_number, 1 AS question_number,
           'You have recently seen an advertisement in which Mrs Barrett, who lives near you, says she needs help in her home. You think this is a job you could do.\n\nWrite a letter to Mrs Barrett. In your letter:\n• suggest how you could help her at home\n• explain why you think you would be suitable for the work\n• say when you would be available' AS question_text,
           'Write at least 150 words.\nYou do NOT need to write any addresses.\nBegin your letter as follows:  Dear Mrs Barrett,' AS instructions,
           'essay' AS question_type,
           1.0 AS points, 10 AS display_order
    UNION ALL
    -- Task 2: Plastic environment essay (250+ words)
    SELECT @tid, 1, 2,
           'Plastic waste is damaging our environment. Some people think that individuals should take responsibility for reducing the damage. Others believe that governments and large companies should take action instead.\n\nDiscuss both these views and give your own opinion.',
           'Write at least 250 words.',
           'essay', 1.0, 20
) _qs
WHERE (SELECT COUNT(*) FROM questions WHERE test_id = @tid) = 0;

-- Verify: expect 2 questions
-- SELECT COUNT(*) FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_W');
