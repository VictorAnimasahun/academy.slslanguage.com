-- ============================================================
-- Migration 104 -- staff_accounts: who is staff/admin, decided by the database
--
-- Until now is_platform_admin() trusted the EMAIL TEXT in the session: any
-- address ending @slslanguage.com counted as admin. Anyone who can register
-- with a made-up address like fake@slslanguage.com (and get past email
-- verification, or exploit a gap in it) would have got admin preview access.
-- The address alone proves nothing; a row that only an admin can create does.
--
-- Rule after this migration: staff/admin = a row here for that student's id
-- AND the account's email is verified. The @slslanguage.com ending no longer
-- grants anything by itself.
--
-- Backfill: every existing account that the OLD rule treated as admin
-- (an @slslanguage.com address, or the owner's legacy address) gets a row,
-- so nobody loses access when the code switches over.
--
-- No foreign key (live rejected FKs on other tables: errno 150). Idempotent.
-- Run on LOCAL first, then LIVE. Run this BEFORE pulling the new
-- includes/admin_check.php, or staff briefly lose preview access.
-- ============================================================

CREATE TABLE IF NOT EXISTS staff_accounts (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id  INT UNSIGNED NOT NULL,
    role        ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    granted_by  VARCHAR(150) NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_staff_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO staff_accounts (student_id, role, granted_by)
SELECT id, 'admin', 'migration 104 backfill'
FROM students
WHERE LOWER(email) LIKE '%@slslanguage.com'
   OR LOWER(email) = 'animasahunvictor1@gmail.com';

-- Verify (expect your real staff; check is_verified = 1 for each, because
-- the check requires a verified email):
--   SELECT sa.student_id, s.email, sa.role, s.is_verified
--   FROM staff_accounts sa JOIN students s ON s.id = sa.student_id;
-- Rollback: DROP TABLE staff_accounts;  (code then treats nobody as staff)
