-- Migration 064 — Replace the placeholder Writing Task 1 prompt seeded in 063
-- (a text-described bar chart, written that way only because no chart image
-- asset existed yet) with the real Cambridge Academic Task 1A prompt and its
-- actual chart image, now on disk at
-- assets/img/mock_tests/IELTS_ACA_DIAGNOSTIC/writing_task1_chart.png
-- Run on LOCAL first, then LIVE.

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.question_text = 'The chart below shows the number of men and women in further education in Britain in three periods and whether they were studying full-time or part-time.

Summarise the information by selecting and reporting the main features, and make comparisons where relevant.

Write at least 150 words.',
    q.instructions = 'assets/img/mock_tests/IELTS_ACA_DIAGNOSTIC/writing_task1_chart.png'
WHERE t.code = 'IELTS_ACA_DIAG_W' AND q.question_number = 1;

-- Verify
-- SELECT q.question_number, q.question_text, q.instructions FROM questions q JOIN tests t ON t.id = q.test_id WHERE t.code = 'IELTS_ACA_DIAG_W';
