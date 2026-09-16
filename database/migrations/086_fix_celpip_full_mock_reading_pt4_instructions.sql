-- ============================================================
-- Migration 086 — Fix CELPIP Full Mock A/B Reading Part 4 instruction text
--
-- The Q29 ("choose the best option...") and Q34 (reader/visitor comment
-- group) instruction strings seeded in migration 073 were paraphrased and
-- don't match the real CELPIP wording. Corrected against official reference
-- screenshots (Practice Test B, Reading Part 4: Reading for Viewpoints):
--   Q29: "Using the drop-down menu (▾), choose the best option according to
--         the information given on the website."
--   Q34: "The following is a comment by a visitor to the website page.
--         Complete the comment by choosing the best option to fill in each
--         blank."
-- Applies to both CELPIP_FMA_R and CELPIP_FMB_R (Test A and Test B) since
-- both had the same paraphrased pattern.
-- ============================================================

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.instructions = 'Using the drop-down menu (▾), choose the best option according to the information given on the website.'
WHERE t.code IN ('CELPIP_FMA_R', 'CELPIP_FMB_R') AND q.question_number = 29;

UPDATE questions q
JOIN tests t ON t.id = q.test_id
SET q.instructions = 'The following is a comment by a visitor to the website page. Complete the comment by choosing the best option to fill in each blank.'
WHERE t.code IN ('CELPIP_FMA_R', 'CELPIP_FMB_R') AND q.question_number = 34;
