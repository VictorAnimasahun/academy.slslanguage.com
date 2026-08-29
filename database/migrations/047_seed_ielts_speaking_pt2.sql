-- ============================================================
-- Migration 047 — Seed IELTS Speaking Practice Test 2
-- Test code: IELTS_PT_S_002
-- Real source content: academy/resources/practice_tests/ielts_speaking_002.php
-- IDEMPOTENT: safe to re-run.
-- ============================================================

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_S_002',
       'IELTS Speaking – Practice Test 2',
       'A full IELTS Speaking practice test (Parts 1–3, ~15 minutes) on languages, online shopping, and consumer habits. AI-graded via the speaking feedback flow.',
       'IELTS',
       15,
       3,
       1,
       0,
       'Speaking'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_S_002');
