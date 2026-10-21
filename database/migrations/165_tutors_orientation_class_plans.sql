-- ============================================================
-- Migration 165 -- course tutors, the orientation session, students' class plans, the tutor's class log
--
-- Founder, 2026-10-21: "I want to organise my classes and students ... no missing classes or struggling to find
-- resources for a class." Built in SLS Admin now (academy-laravel later):
--  * courses.tutor_employee_id -- each course has a designated, named tutor (an SLS Admin login, `employees`).
--  * student_orientation -- the orientation session (platform tour + quiz), once per student.
--  * student_class_plans -- the form and commitment that ends it, one per enrolled course: one-to-one or group,
--    class days, time and time zone, hours per class, start date, weekly study hours promised, target score.
--  * class_slot_log -- the tutor's record of each scheduled class day: held (and which class) or cancelled.
--    A cancelled day doesn't use up a class, so the schedule moves on without skipping one.
--
-- Re-run safe: tables created only if missing, the column added only when missing (MySQL 5.7 has no
-- ADD COLUMN IF NOT EXISTS). Not in a transaction: MySQL commits DDL immediately anyway.
-- ============================================================

SET NAMES utf8mb4;

SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'courses' AND COLUMN_NAME = 'tutor_employee_id');
SET @s := IF(@c = 0, 'ALTER TABLE courses ADD COLUMN tutor_employee_id INT NULL DEFAULT NULL', 'SELECT 1');
PREPARE m165 FROM @s; EXECUTE m165; DEALLOCATE PREPARE m165;

CREATE TABLE IF NOT EXISTS student_orientation (
    student_id    INT UNSIGNED NOT NULL,
    quiz_score    TINYINT UNSIGNED NOT NULL,
    quiz_total    TINYINT UNSIGNED NOT NULL,
    completed_at  TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS student_class_plans (
    id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id        INT UNSIGNED NOT NULL,
    course_id         INT UNSIGNED NOT NULL,
    class_format      ENUM('one_to_one','group') NOT NULL,
    class_days        VARCHAR(40) NOT NULL COMMENT 'comma-separated: mon,tue,wed,thu,fri,sat,sun',
    class_time        TIME NOT NULL,
    timezone          VARCHAR(64) NOT NULL,
    class_hours       DECIMAL(3,1) NOT NULL,
    start_date        DATE NOT NULL,
    study_hours_week  DECIMAL(4,1) NOT NULL,
    target_score      VARCHAR(30) NOT NULL,
    test_date         DATE NULL DEFAULT NULL,
    signed_name       VARCHAR(150) NOT NULL,
    submitted_at      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_plan_student_course (student_id, course_id),
    KEY idx_plan_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS class_slot_log (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id    INT UNSIGNED NOT NULL,
    course_id     INT UNSIGNED NOT NULL,
    slot_date     DATE NOT NULL,
    status        ENUM('held','cancelled') NOT NULL,
    class_number  SMALLINT UNSIGNED NULL DEFAULT NULL COMMENT 'the class taught, when held',
    employee_id   INT NULL DEFAULT NULL,
    note          VARCHAR(500) NULL DEFAULT NULL,
    created_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_slot (student_id, course_id, slot_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
