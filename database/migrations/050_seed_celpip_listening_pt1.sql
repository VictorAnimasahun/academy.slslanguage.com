-- ============================================================
-- Migration 050 — Seed CELPIP Listening Practice Tests 1–3
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Listening
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_L_001',
       'CELPIP Listening – Practice Test 1',
       'CELPIP Listening practice set based on the downloaded CELPIP Listening task bundle. Audio and prompts follow the source package in the local Downloads folder.',
       'CELPIP',
       47,
       8,
       1,
       0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_L_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_L_002',
       'CELPIP Listening – Practice Test 2',
       'CELPIP Listening practice set based on the downloaded task pack in the local Downloads folder.',
       'CELPIP',
       47,
       8,
       1,
       0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_L_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_L_003',
       'CELPIP Listening – Practice Test 3',
       'CELPIP Listening practice set based on the downloaded task pack in the local Downloads folder.',
       'CELPIP',
       47,
       8,
       1,
       0,
       'Listening'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_L_003');
