-- Migration skeleton: seed IELTS listening practice 3
-- TODO: fill question rows, options and correct answers
BEGIN;

INSERT INTO tests (code, title, section, time_limit, created_at, updated_at)
SELECT 'IELTS_PT_L_003', 'IELTS Listening Practice 3', 'listening', 1800, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_L_003');

DELETE FROM attempt_answers WHERE attempt_id IN (SELECT id FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_003'));
DELETE FROM test_attempts WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_003');

DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_003'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_003');

-- TODO: INSERT questions and options here following the project's conventions

COMMIT;
