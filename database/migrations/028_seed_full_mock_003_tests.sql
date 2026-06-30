-- ============================================================
-- Migration 028 — Seed test container records for IELTS Full Mock 003
-- Cambridge IELTS GT Test 3
-- NON-DESTRUCTIVE: WHERE NOT EXISTS on every INSERT
-- Safe to re-run on LOCAL (useraccounts) and LIVE (slslanguage_db)
-- ============================================================

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FULL_MOCK_003',
       'IELTS Full Mock Test 3',
       'Complete IELTS mock exam: Listening (40Q/30min), Reading (40Q/60min), Writing (2 tasks/60min), Speaking (instructor-administered).',
       'IELTS', 'Full', 1, 190, 82, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FULL_MOCK_003');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM3_L', 'IELTS Full Mock 3 — Listening',
       'Cambridge IELTS GT Test 3 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_L');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM3_R', 'IELTS Full Mock 3 — Reading',
       'Cambridge IELTS GT Test 3 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_R');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM3_W', 'IELTS Full Mock 3 — Writing',
       'Cambridge IELTS GT Test 3 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM3_W');

-- Verify
-- SELECT code, category, total_questions, is_active FROM tests WHERE code IN ('IELTS_FULL_MOCK_003','IELTS_FM3_L','IELTS_FM3_R','IELTS_FM3_W');
