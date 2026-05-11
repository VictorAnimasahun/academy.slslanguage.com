-- ============================================================
-- Migration 011 — Seed IELTS_PT_W1_001 and IELTS_PT_S_001
-- IDEMPOTENT: safe to re-run.
-- Writing Task 1 and Speaking Practice Test 1 are open-ended;
-- no question rows are needed — grading is done by AI via api_handler.php.
-- ============================================================

-- ── Writing Task 1 ──────────────────────────────────────────

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_W1_001',
       'IELTS General Training Writing Task 1 – Practice Test 1',
       'A 20-minute timed IELTS General Training Writing Task 1 letter task. AI-graded via essay_analyzer.',
       'IELTS',
       20,
       1,
       1, 0,
       'Writing'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_W1_001');

-- ── Speaking ────────────────────────────────────────────────

INSERT INTO tests (code, title, description, test_type, duration_minutes, total_questions, is_active, is_mock_section, category)
SELECT 'IELTS_PT_S_001',
       'IELTS Speaking – Practice Test 1',
       'A full IELTS Speaking practice test (Parts 1–3, ~15 minutes). AI-graded via audio_analyzer batch mode.',
       'IELTS',
       15,
       3,
       1, 0,
       'Speaking'
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_PT_S_001');
