-- ============================================================
-- Migration 033 — Seed test container records for IELTS Full Mock 004
-- Cambridge IELTS GT Test 4
-- NON-DESTRUCTIVE: WHERE NOT EXISTS on every INSERT
-- Safe to re-run on LOCAL (useraccounts) and LIVE (slslanguage_db)
-- ============================================================

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FULL_MOCK_004',
       'IELTS Full Mock Test 4',
       'Complete IELTS mock exam: Listening (40Q/30min), Reading (40Q/60min), Writing (2 tasks/60min), Speaking (instructor-administered).',
       'IELTS', 'Full', 1, 190, 82, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FULL_MOCK_004');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_L', 'IELTS Full Mock 4 — Listening',
       'Cambridge IELTS GT Test 4 — Listening section (40Q/30 min)',
       'IELTS', 'Listening', 1, 30, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_L');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_R', 'IELTS Full Mock 4 — Reading',
       'Cambridge IELTS GT Test 4 — Reading section (40Q/60 min)',
       'IELTS', 'Reading', 1, 60, 40, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_R');

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FM4_W', 'IELTS Full Mock 4 — Writing',
       'Cambridge IELTS GT Test 4 — Writing section (2 tasks/60 min)',
       'IELTS', 'Writing', 1, 60, 2, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FM4_W');

-- Verify
-- SELECT code, category, total_questions, is_active FROM tests WHERE code IN ('IELTS_FULL_MOCK_004','IELTS_FM4_L','IELTS_FM4_R','IELTS_FM4_W');
