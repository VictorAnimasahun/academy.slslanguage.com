-- ============================================================
-- Migration 024 — Manual writing grading override
--
-- Lets an instructor enter/override the Writing band + notes
-- instead of relying solely on the Gemini AI grader.
--
-- Run each statement one at a time in phpMyAdmin.
-- If you get "Duplicate column name" on any ALTER, that column
-- already exists — just skip it and run the next one.
-- ============================================================

-- 1. Instructor's manual writing comments (separate from AI feedback)
ALTER TABLE mock_sessions
    ADD COLUMN `writing_notes` TEXT NULL AFTER `writing_ai_feedback`;

-- 2. Tracks whether the current writing_band/feedback shown to the
--    student came from the AI or was entered/overridden by an instructor.
ALTER TABLE mock_sessions
    ADD COLUMN `writing_graded_by` ENUM('ai','instructor') NOT NULL DEFAULT 'ai' AFTER `writing_notes`;
