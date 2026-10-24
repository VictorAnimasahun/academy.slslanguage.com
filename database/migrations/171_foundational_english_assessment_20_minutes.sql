-- ============================================================
-- Migration 171 -- Foundational English Assessment: 20 minutes, not 60
--
-- Founder, 2026-10-24: "It's been 5 minutes and someone's finished like half already ... make it 20 minutes."
-- The page's clock is set in resources/placement/foundational_english_assessment.php (FEA_SECONDS = 1200); this keeps
-- the tests' own record in step (duration and description, shown wherever tests are listed). Re-runnable. MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;

UPDATE tests
   SET duration_minutes = 20,
       description = 'General English level check: grammar and vocabulary, language in use, and reading. 50 questions, 20 minutes.'
 WHERE code IN ('FEA50_A', 'FEA50_B');

SELECT code, duration_minutes, total_questions, description FROM tests WHERE code IN ('FEA50_A', 'FEA50_B');
