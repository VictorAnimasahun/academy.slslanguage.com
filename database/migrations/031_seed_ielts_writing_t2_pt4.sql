-- Migration skeleton: seed IELTS Writing Task 2 practice 4
-- TODO: insert test row (if not exists) and any metadata required for the essay task
BEGIN;

INSERT INTO tests (code, title, section, time_limit, created_at, updated_at)
SELECT 'IELTS_PT_W2_004', 'IELTS Writing Task 2 Practice 4', 'writing_t2', 2400, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W2_004');

-- Remove prior attempts so seeding is idempotent
DELETE FROM attempt_answers WHERE attempt_id IN (SELECT id FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_W2_004'));
DELETE FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_W2_004');

-- If you store prompts in questions table, clear previous entries
DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_W2_004'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_W2_004');

-- TODO: INSERT prompt as a question or into a prompts table according to existing schema

COMMIT;
