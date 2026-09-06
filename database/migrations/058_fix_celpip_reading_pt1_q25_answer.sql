-- ============================================================
-- Migration 058 — Fix wrong answer key for CELPIP_PT_R_001 Q25
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Reading/Test 1
-- IDEMPOTENT: safe to re-run.
--
-- Q25 ("Campsites are evenly spaced across the country.") was seeded in
-- migration 055 with option D marked correct, following the source answer
-- key document (CELPIP READING Test I (Answers).docx, item 6). That
-- document's own justification for D doesn't hold up: it quotes paragraph
-- D talking about campgrounds along one Trans-Canada highway stretch and
-- province-level info availability -- neither of which claims campsites
-- are EVENLY SPACED. Re-reading paragraphs A-D, none of them address
-- geographic distribution/spacing at all, so the correct answer is E
-- (Not given), not D. This is a content error in the source material,
-- caught by spot-checking the answer key against the passage itself.
-- ============================================================

SET @tid = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001' LIMIT 1);
SET @q25 = (SELECT id FROM questions WHERE test_id = @tid AND question_number = 25 LIMIT 1);

UPDATE question_options SET is_correct = 0 WHERE question_id = @q25 AND option_label = 'D';
UPDATE question_options SET is_correct = 1 WHERE question_id = @q25 AND option_label = 'E';
