-- ============================================================
-- Migration 051 — Seed CELPIP Reading Practice Tests 1–3
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Reading
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_R_001',
       'CELPIP Reading – Practice Test 1',
       'CELPIP Reading practice set based on the downloaded reading task bundle and answer file.',
       'CELPIP',
       55,
       4,
       1,
       0,
       'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_R_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_R_002',
       'CELPIP Reading – Practice Test 2',
       'CELPIP Reading practice set based on the downloaded reading task bundle and answer file.',
       'CELPIP',
       55,
       4,
       1,
       0,
       'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_R_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_R_003',
       'CELPIP Reading – Practice Test 3',
       'CELPIP Reading practice set based on the downloaded reading task bundle and answer file.',
       'CELPIP',
       55,
       4,
       1,
       0,
       'Reading'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_R_003');
