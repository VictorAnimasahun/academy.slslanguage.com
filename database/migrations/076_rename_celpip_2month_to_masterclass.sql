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
--
-- ⚠️ Fixed 2026-09-10, before this ever ran on live: originally targeted
-- `WHERE id = 13`, which only happens to be course 13 locally. Discovered
-- while investigating that course_id 9-19 (all the "General"/"Academic"/PTE
-- course tracks, including this one) were never migrated to live at all --
-- see migration 077. If 077 runs on live first, auto-increment will almost
-- certainly NOT assign this course id 13, so the old hardcoded-id version
-- would have silently renamed whatever unrelated course DID land on id 13.
-- Now keyed on the stable `folder_name` instead.
-- ============================================================

UPDATE courses
SET title = 'CELPIP General Masterclass — 2 Months',
    description = 'The complete 2-month CELPIP Masterclass. 16 classes, 2 full mock exams, detailed written feedback, and CLB level optimisation across all four skills.'
WHERE folder_name = 'CELPIP_Gen_2Mo'
  AND title = 'CELPIP General — 2-Month Plan';
