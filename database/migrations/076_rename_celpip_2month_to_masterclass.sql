-- ============================================================
-- Migration 076 — Rename course 13 to include "Masterclass"
--
-- Course 13's title ("CELPIP General — 2-Month Plan") and description
-- never actually said "Masterclass" anywhere, even though the instructor
-- has consistently called it "the CELPIP Masterclass" throughout this
-- project (and it's exactly what the CELPIP Full Mock A/B work in
-- migrations 073-075 was built for). This made it impossible to find by
-- that name on the Courses catalogue page, and inconsistent with course
-- 14's naming ("CELPIP General Masterclass — 3 Months").
--
-- Idempotent — safe to re-run.
-- ============================================================

UPDATE courses
SET title = 'CELPIP General Masterclass — 2 Months',
    description = 'The complete 2-month CELPIP Masterclass. 16 classes, 2 full mock exams, detailed written feedback, and CLB level optimisation across all four skills.'
WHERE id = 13
  AND title = 'CELPIP General — 2-Month Plan';
