-- Migration 094: CELPIP_FMA_L Q1 now has real photo answer choices (added
-- via resources/mock_tests/celpip_full_mock_listening.php's
-- $listeningOptionImages map + assets/img/mock_tests/CELPIP_FULL_MOCK_A/
-- listening_q1/option_[a-d].png). This removes the now-stale placeholder
-- note from the question text ("...see options for a text description of
-- each.") that only existed because the images weren't built yet.
-- Note: the question text itself is no longer shown to students at all in
-- Part 1 (Parts 1-3 now show "Choose the best answer." instead, per a
-- separate fix), but the DB text is cleaned up regardless for correctness
-- in admin views / future reuse.

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.question_text = 'You will hear a conversation between a woman and a man. The man is a bus driver and the woman is a passenger trying to get somewhere. What is the woman eventually hoping to find?'
WHERE t.code = 'CELPIP_FMA_L' AND q.question_number = 1;

-- Verify:
-- SELECT q.question_text FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code='CELPIP_FMA_L' AND q.question_number=1;
