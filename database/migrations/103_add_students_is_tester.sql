-- ============================================================
-- Migration 103 -- students.is_tester: named testers who bypass all access rules
--
-- Course features (sequential class gating, paid-only course access, course
-- locks on practice tests) are being built continuously, and the instructor
-- wants to see them working every day. is_platform_admin() (admin_check.php)
-- is the single bypass used by all those gates; it already covers every
-- @slslanguage.com address (staff/admins). This adds a per-student flag for
-- specific CURRENT STUDENTS who should also be exempt while testing.
-- Toggled from sls-admin -> Students -> a student's profile.
--
-- Idempotent (MySQL 5.7-safe). Run on LOCAL first, then LIVE.
-- ============================================================

SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'students'
      AND COLUMN_NAME  = 'is_tester'
);

SET @sql = IF(
    @existing = 0,
    'ALTER TABLE students ADD COLUMN is_tester TINYINT(1) NOT NULL DEFAULT 0',
    'SELECT ''is_tester column already exists -- skipping'' AS info'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verify: SELECT id, email, is_tester FROM students WHERE is_tester = 1;
-- Rollback: ALTER TABLE students DROP COLUMN is_tester;
