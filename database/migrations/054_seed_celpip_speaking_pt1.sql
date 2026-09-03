-- ============================================================
-- Migration 054 — Seed CELPIP Speaking Practice Tests 1–3
-- Source bundle: /Users/victoranimasahun/Downloads/CELPIP TASKS/Celpip Speaking
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_S_001',
       'CELPIP Speaking – Practice Test 1',
       'CELPIP Speaking practice set based on the downloaded speaking prompt bundle.',
       'CELPIP',
       16,
       8,
       1,
       0,
       'Speaking'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_S_001');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_S_002',
       'CELPIP Speaking – Practice Test 2',
       'CELPIP Speaking practice set based on the downloaded speaking prompt bundle.',
       'CELPIP',
       16,
       8,
       1,
       0,
       'Speaking'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_S_002');

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'CELPIP_PT_S_003',
       'CELPIP Speaking – Practice Test 3',
       'CELPIP Speaking practice set based on the downloaded speaking prompt bundle.',
       'CELPIP',
       16,
       8,
       1,
       0,
       'Speaking'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'CELPIP_PT_S_003');
