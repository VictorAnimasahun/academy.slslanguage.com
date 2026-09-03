-- ============================================================
-- Migration 053 — Seed CELPIP Writing Task 2 Practice Tests
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Writing
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W2_001',
       'CELPIP Writing Task 2 – Practice Test 1',
       'CELPIP Writing Task 2 survey response prompt based on the local writing task bundle.',
       'CELPIP',
       26,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W2_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W2_002',
       'CELPIP Writing Task 2 – Practice Test 2',
       'CELPIP Writing Task 2 survey response prompt based on the local writing task bundle.',
       'CELPIP',
       26,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W2_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W2_003',
       'CELPIP Writing Task 2 – Practice Test 3',
       'CELPIP Writing Task 2 survey response prompt based on the local writing task bundle.',
       'CELPIP',
       26,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W2_003');
