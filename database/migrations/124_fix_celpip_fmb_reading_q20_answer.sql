-- ============================================================
-- Migration 124 -- Fix CELPIP Full Mock B (Mock 2) Reading, Part 3, Q20 answer key
--
-- Q20 "Dragonflies can be used as a form of pest and illness control."
-- Migration 073 keyed it A. The passage says otherwise: paragraph A is about
-- the dragonfly's age, species count and habitats; paragraph D says
-- dragonflies were introduced in Myanmar to eat mosquito larvae, "an effective
-- strategy for controlling mosquito-transmitted diseases such as dengue fever".
-- Correct answer: D (confirmed by the instructor, 2026-09-25).
--
-- Fixes the KEY only. Attempts already saved were scored against the old key
-- and are NOT rescored here (that would silently change released results);
-- correct individual students with sls-admin's score-correction feature
-- (mock_session_detail.php / student_view.php, migration 125's audit log).
-- The SELECT at the bottom lists who is affected.
-- IDEMPOTENT: safe to re-run.
-- ============================================================

SET @qid = (SELECT q.id FROM questions q JOIN tests t ON t.id = q.test_id
            WHERE t.code = 'CELPIP_FMB_R' AND q.question_number = 20 LIMIT 1);

UPDATE question_options SET is_correct = (option_label = 'D') WHERE question_id = @qid;

UPDATE question_correct_answers
   SET answer_text = IF(is_alternative = 0, 'D', 'd')
 WHERE question_id = @qid;

-- Verify: exactly one correct option (D) and answers D / d.
SELECT option_label, is_correct FROM question_options WHERE question_id = @qid ORDER BY option_label;
SELECT answer_text, is_alternative FROM question_correct_answers WHERE question_id = @qid;

-- Who is affected (saved attempts scored against the old key):
SELECT aa.attempt_id, ta.student_id, aa.answer_text AS chose, aa.score_awarded, ta.score, ta.band_score
FROM attempt_answers aa JOIN test_attempts ta ON ta.id = aa.attempt_id
WHERE aa.question_id = @qid;
