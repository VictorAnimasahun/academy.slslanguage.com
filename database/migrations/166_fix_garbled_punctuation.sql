-- ============================================================
-- Migration 166 -- garbled punctuation in titles and instructions
--
-- Some text was saved through the wrong character set at some point, so an en dash shows as "â€“" (e.g. the
-- student dashboard's "CELPIP Reading â€“ Practice Test 1"). Found locally 2026-10-22: 35 en dashes in
-- assignments.title, questions.instructions, tests.title and tests.description. This puts the real characters back
-- there and in the other places titles are shown, for the en dash and the other common cases (em dash, curly
-- quotes and apostrophe, ellipsis) in case live has those too. Only rows containing the garbled text are touched.
--
-- Re-run safe (once fixed, nothing matches). MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;

UPDATE assignments  SET title = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE title LIKE '%â€%';
UPDATE tests        SET title = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE title LIKE '%â€%';
UPDATE tests        SET description = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(description, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE description LIKE '%â€%';
UPDATE questions    SET instructions = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(instructions, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE instructions LIKE '%â€%';
UPDATE questions    SET question_text = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(question_text, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE question_text LIKE '%â€%';
UPDATE lesson_parts SET title = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(title, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE title LIKE '%â€%';
UPDATE modules      SET module_title = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(module_title, 'â€“', '–'), 'â€”', '—'), 'â€™', '’'), 'â€˜', '‘'), 'â€œ', '“'), 'â€¦', '…') WHERE module_title LIKE '%â€%';
