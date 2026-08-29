-- Migration skeleton: seed IELTS Speaking practice 3
-- TODO: insert test row (if not exists) and prompts/parts for speaking
BEGIN;

INSERT INTO tests (code, title, section, time_limit, created_at, updated_at)
SELECT 'IELTS_PT_S_003', 'IELTS Speaking Practice 3', 'speaking', 900, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_S_003');

-- Remove prior attempts so seeding is idempotent
DELETE FROM attempt_answers WHERE attempt_id IN (SELECT id FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_S_003'));
DELETE FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_S_003');

-- If you store prompts in questions table, clear previous entries
DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_S_003'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_S_003');

-- TODO: INSERT structured speaking parts and prompts here.

COMMIT;
