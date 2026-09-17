-- Migration 093: strip leftover "[Track N]" transcription markers from CELPIP
-- Full Mock A/B Listening question text. These were internal notes marking
-- which audio track a question belongs to, left in by mistake during
-- seeding/transcription — never meant to be shown to students. Found live
-- during Full Mock A (a student was mid-test) 2026-09-17.

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.question_text = TRIM(REGEXP_REPLACE(q.question_text, '\\[Track [0-9]+\\]\\s*', ''))
WHERE t.code IN ('CELPIP_FMA_L', 'CELPIP_FMB_L')
  AND q.question_text REGEXP '\\[Track [0-9]+\\]';

-- Verify: should return 0 rows after running
-- SELECT t.code, q.question_number, q.question_text FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code IN ('CELPIP_FMA_L','CELPIP_FMB_L') AND q.question_text REGEXP '\\[Track [0-9]+\\]';
