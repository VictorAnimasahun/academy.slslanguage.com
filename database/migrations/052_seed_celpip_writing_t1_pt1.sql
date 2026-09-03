-- ============================================================
-- Migration 052 — Seed CELPIP Writing Task 1 Practice Tests
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Writing
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W1_001',
       'CELPIP Writing Task 1 – Practice Test 1',
       'CELPIP Writing Task 1 email writing prompt based on the local writing task bundle.',
       'CELPIP',
       27,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W1_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W1_002',
       'CELPIP Writing Task 1 – Practice Test 2',
       'CELPIP Writing Task 1 email writing prompt based on the local writing task bundle.',
       'CELPIP',
       27,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W1_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_W1_003',
       'CELPIP Writing Task 1 – Practice Test 3',
       'CELPIP Writing Task 1 email writing prompt based on the local writing task bundle.',
       'CELPIP',
       27,
       1,
       1,
       0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_W1_003');
