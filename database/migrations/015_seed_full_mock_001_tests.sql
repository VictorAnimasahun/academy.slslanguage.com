-- Migration 015: Seed test records for IELTS Full Mock 001 section tests
-- These are the scoring + question containers for the three auto-graded sections.
-- Run on: local (useraccounts DB) and live (slslanguage_db)

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM1_L', 'IELTS Full Mock 1 — Listening', 'Listening section for IELTS Full Mock Test 001', 'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_L');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM1_R', 'IELTS Full Mock 1 — Reading', 'Reading section for IELTS Full Mock Test 001', 'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_R');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM1_W', 'IELTS Full Mock 1 — Writing', 'Writing section for IELTS Full Mock Test 001 (Task 1 + Task 2)', 'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM1_W');
