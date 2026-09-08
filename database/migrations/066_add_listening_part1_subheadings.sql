-- Migration 066 — Add note-group sub-headings to Listening Part 1
-- ("Children's Engineering Workshops") on both IELTS_FM1_L and IELTS_ACA_DIAG_L.
--
-- The note-completion form has three natural groups (Tiny Engineers activities
-- Q1-3, Junior Engineers activities Q4-7, logistics Q8-10) but the content
-- seeded by migrations 016/065 only carries one stimulus_text on Q1, so the
-- template renders it as one flat, undifferentiated list of ten sentences.
--
-- full_mock_001_listening.php / diagnostic_aca_listening.php already render a
-- .ff-title box whenever stimulus_text changes mid-list — this migration just
-- populates that field on Q4 and Q8 so the existing template produces proper
-- grouped notes instead of a flat block. Q1's stimulus_text is extended with
-- a "||Sub Heading" suffix (new convention, see updated template comment) so
-- the top-level form title and the first group heading both render.
--
-- Safe to re-run: plain UPDATEs keyed on test code + question_number.

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.stimulus_text = 'Children''s Engineering Workshops||Tiny Engineers'
WHERE t.code IN ('IELTS_FM1_L', 'IELTS_ACA_DIAG_L') AND q.question_number = 1;

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.stimulus_text = 'Junior Engineers'
WHERE t.code IN ('IELTS_FM1_L', 'IELTS_ACA_DIAG_L') AND q.question_number = 4;

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.stimulus_text = 'Additional information'
WHERE t.code IN ('IELTS_FM1_L', 'IELTS_ACA_DIAG_L') AND q.question_number = 8;

-- Verify: expect 3 rows per test code, stimulus_text populated as above
-- SELECT t.code, q.question_number, q.stimulus_text FROM questions q JOIN tests t ON t.id = q.test_id
-- WHERE t.code IN ('IELTS_FM1_L','IELTS_ACA_DIAG_L') AND q.question_number IN (1,4,8) ORDER BY t.code, q.question_number;
