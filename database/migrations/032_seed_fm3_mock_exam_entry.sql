-- ============================================================
-- Migration 032 — Add FM3 to mock_exams and mock_exam_sections
-- Run on LOCAL first, then LIVE.
-- NON-DESTRUCTIVE: guarded by NOT EXISTS checks.
-- ============================================================

INSERT INTO mock_exams (code, exam_type, title, description, total_duration_minutes, is_active)
SELECT 'IELTS_FULL_MOCK_003', 'IELTS',
       'IELTS Full Mock Test 3',
       'Cambridge IELTS General Training Test 3. Listening (40Q/30 min), Reading (40Q/60 min), Writing AI-graded (60 min), Speaking instructor-administered.',
       190, 1
WHERE NOT EXISTS (SELECT 1 FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_003');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_003', 'Listening', 'IELTS_FM3_L', 1
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_003' AND section_type = 'Listening');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_003', 'Reading', 'IELTS_FM3_R', 2
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_003' AND section_type = 'Reading');

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT 'IELTS_FULL_MOCK_003', 'Writing_Task1', 'IELTS_FM3_W', 3
WHERE NOT EXISTS (SELECT 1 FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_003' AND section_type = 'Writing_Task1');

-- Verify
SELECT code, title FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_003';
SELECT section_type, test_code, section_order FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_003' ORDER BY section_order;
