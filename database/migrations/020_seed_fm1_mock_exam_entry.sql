-- ============================================================
-- Migration 020 — Add FM1 to mock_exams and mock_exam_sections
-- Run on LOCAL first, then LIVE.
-- NON-DESTRUCTIVE: guarded by NOT EXISTS checks.
-- ============================================================

INSERT INTO mock_exams (code, exam_type, title, description, total_duration_minutes, is_active)
SELECT 'IELTS_FULL_MOCK_001', 'IELTS',
       'IELTS Full Mock Test 1',
       'Cambridge IELTS General Training Test 1. Listening (40Q/30 min), Reading (40Q/60 min), Writing AI-graded (60 min), Speaking instructor-administered.',
       190, 1
WHERE NOT EXISTS (SELECT 1 FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_001');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_001', 'Listening', 'IELTS_FM1_L', 1
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_001' AND section_type = 'Listening');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_001', 'Reading', 'IELTS_FM1_R', 2
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_001' AND section_type = 'Reading');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_001', 'Writing_Task1', 'IELTS_FM1_W', 3
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_001' AND section_type = 'Writing_Task1');

-- Verify
SELECT code, title FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_001';
SELECT section_type, test_code, section_order FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_001' ORDER BY section_order;
