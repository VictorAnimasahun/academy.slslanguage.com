-- Migration 095: CELPIP_FMB_L Q2 now has real photo answer choices (added
-- via resources/mock_tests/celpip_full_mock_listening.php's
-- $listeningOptionImages map + assets/img/mock_tests/CELPIP_FULL_MOCK_B/
-- listening_q2/option_[a-d].png). Same treatment as migration 094 for
-- CELPIP_FMA_L Q1 — removes the now-stale placeholder note from the
-- question text.

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.question_text = 'What is at the man''s workstation?'
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2;

-- Verify:
-- SELECT q.question_text FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code='CELPIP_FMB_L' AND q.question_number=2;
