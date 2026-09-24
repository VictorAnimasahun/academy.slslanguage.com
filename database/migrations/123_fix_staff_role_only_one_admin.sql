-- ============================================================
-- Migration 123 -- Exactly one admin: v.animasahun@slslanguage.com
--
-- Instructor confirmed 2026-09-24 (documentation/ACCESS_CONTROL_MODEL.md):
-- exactly one person is admin. Migration 104's original backfill set
-- 'admin' for both v.animasahun@slslanguage.com AND the legacy QA/preview
-- bypass account (animasahunvictor1@gmail.com -- a personal Gmail, not
-- even an official @slslanguage.com address). Corrects everyone except
-- v.animasahun to 'staff' -- functionally identical access either way
-- (is_platform_admin() doesn't distinguish role), this is about the DATA
-- being accurate, not a behavior change.
-- Matches by email, not hardcoded id. Idempotent.
-- Run on LOCAL first, then LIVE.
-- ============================================================

UPDATE staff_accounts sa
JOIN students s ON s.id = sa.student_id
SET sa.role = 'staff'
WHERE s.email != 'v.animasahun@slslanguage.com' AND sa.role = 'admin';

-- Verify: SELECT sa.role, s.email FROM staff_accounts sa JOIN students s ON s.id=sa.student_id;
-- Rollback: no clean rollback (which rows were 'admin' before isn't recorded) --
--   restore from a pre-migration DB backup if this needs undoing.
