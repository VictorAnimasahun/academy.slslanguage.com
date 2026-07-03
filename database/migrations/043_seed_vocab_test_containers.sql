-- Migration 043 — Seed vocab quiz test containers (one per word, first 30 words)
-- Phase 1, Step 6 of the Vocabulary Banks feature.
-- Adds one row to `tests` per vocabulary word so the existing attempt/scoring
-- infrastructure works for vocab quizzes without any new code.
-- Run on LOCAL first, then LIVE.
-- INSERT IGNORE: safe to re-run — skips rows whose code already exists (UNIQUE KEY on code).

INSERT IGNORE INTO tests (code, title, description, test_type, category, duration_minutes, total_questions, is_active, is_mock_section)
SELECT
    CONCAT('VOCAB_WORD_', LPAD(v.sort_order, 3, '0')),
    CONCAT('Word Exercise: ', v.headword),
    CONCAT('Practice quiz for the vocabulary word "', v.headword, '". Tests definition, word form, collocations, and usage in context.'),
    'Vocabulary',
    'Word Exercise',
    5,
    0,
    1,
    0
FROM vocabulary_words v
WHERE v.sort_order BETWEEN 1 AND 30
  AND NOT EXISTS (
      SELECT 1 FROM tests t
      WHERE t.code = CONCAT('VOCAB_WORD_', LPAD(v.sort_order, 3, '0'))
  );
