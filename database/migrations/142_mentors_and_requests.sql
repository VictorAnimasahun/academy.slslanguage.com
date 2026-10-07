-- ============================================================
-- Migration 142 -- real Mentors: mentors, weekly availability, booking requests
--
-- Replaces mentors.php's static "Coming Soon" page with a real, bookable feature
-- (SLS-Academy-App-Spec.md 8.7/8.22, MEN-01..04, MBK-01..04). There are no coach accounts
-- beyond the founder's own staff_accounts rows yet, so this seeds exactly ONE real mentor
-- (the founder) with an honest placeholder bio -- not the spec's sample "Coach Scholar" /
-- "Coach Vee" personas, which are prototype sample content, not real people (SEC-09).
--
-- Availability is a simple weekly template (weekday + time range + slot length), not a real
-- calendar integration. A request is only valid against the mentor's own template; nothing here
-- checks a real external calendar. Good enough for a slot picker; revisit if a real scheduling
-- tool is wanted later.
--
-- Idempotent (MySQL 5.7-safe): CREATE TABLE IF NOT EXISTS throughout; the seed INSERTs are
-- guarded by NOT EXISTS so re-running never duplicates rows.
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS mentors (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    staff_account_id INT UNSIGNED NULL COMMENT 'links to staff_accounts.id when the mentor is a real staff login; NULL for a mentor with no app login yet',
    display_name VARCHAR(150) NOT NULL,
    focus_area VARCHAR(255) NOT NULL,
    bio TEXT NULL,
    tint ENUM('blue','green','orange','purple','pink','teal','yellow') NOT NULL DEFAULT 'purple',
    photo_url VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_mentors_staff (staff_account_id),
    CONSTRAINT fk_mentors_staff FOREIGN KEY (staff_account_id) REFERENCES staff_accounts(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mentor_availability (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mentor_id INT UNSIGNED NOT NULL,
    weekday TINYINT UNSIGNED NOT NULL COMMENT '0 = Sunday .. 6 = Saturday',
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    slot_minutes SMALLINT UNSIGNED NOT NULL DEFAULT 60,
    PRIMARY KEY (id),
    KEY idx_availability_mentor (mentor_id, weekday),
    CONSTRAINT fk_availability_mentor FOREIGN KEY (mentor_id) REFERENCES mentors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mentor_requests (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mentor_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    preferred_at DATETIME NULL COMMENT 'the chosen day+time slot, in server local time',
    note TEXT NULL,
    status ENUM('requested','confirmed','declined','cancelled') NOT NULL DEFAULT 'requested',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_requests_mentor (mentor_id),
    KEY idx_requests_student (student_id, status),
    CONSTRAINT fk_requests_mentor FOREIGN KEY (mentor_id) REFERENCES mentors(id) ON DELETE CASCADE,
    CONSTRAINT fk_requests_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed: the one real instructor, linked to his existing admin staff_accounts row.
INSERT INTO mentors (staff_account_id, display_name, focus_area, bio, tint, is_active)
SELECT sa.id, 'Victor Animasahun', 'IELTS and CELPIP coaching', 'Founder and lead instructor. Bio to be updated.', 'purple', 1
FROM staff_accounts sa
JOIN students s ON s.id = sa.student_id
WHERE s.email = 'v.animasahun@slslanguage.com'
  AND NOT EXISTS (SELECT 1 FROM mentors m WHERE m.staff_account_id = sa.id);

-- Seed: a default Mon-Fri 9am-5pm availability template, one-hour slots, for that mentor.
INSERT INTO mentor_availability (mentor_id, weekday, start_time, end_time, slot_minutes)
SELECT m.id, wd.weekday, '09:00:00', '17:00:00', 60
FROM mentors m
JOIN (SELECT 1 AS weekday UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) wd
WHERE m.display_name = 'Victor Animasahun'
  AND NOT EXISTS (SELECT 1 FROM mentor_availability a WHERE a.mentor_id = m.id);

-- Verify: SELECT * FROM mentors; SELECT * FROM mentor_availability; DESCRIBE mentor_requests;
-- Rollback: DROP TABLE mentor_requests, mentor_availability, mentors;
