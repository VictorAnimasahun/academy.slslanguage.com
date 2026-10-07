-- ============================================================
-- Migration 144 -- real Events: events, event_registrations
--
-- Replaces events.php's hand-written single event card with a real, data-driven list
-- (SLS-Academy-App-Spec.md 8.12/8.23, EVT-01..06). The existing Gen-Z Experience event becomes a
-- real seeded row instead of hardcoded HTML/JS, pointing at its existing detail page
-- (events/gen_z_experience.php, left as-is -- not rebuilt tonight).
--
-- Idempotent (MySQL 5.7-safe): CREATE TABLE IF NOT EXISTS; the seed INSERT is guarded by
-- NOT EXISTS (matched by title, since there is no natural external id for this old hardcoded event).
--
-- Timestamps here are in MySQL's own clock, not PHP's: found while writing this that PHP is set to
-- UTC but MySQL's NOW() currently reads about 1 hour ahead (SYSTEM time zone) -- a pre-existing
-- mismatch, not something this migration fixes. The seeded time below matches MySQL's clock so it
-- lines up with the original hardcoded "4:00 PM WAT".
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    host VARCHAR(255) NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NULL,
    join_url VARCHAR(500) NULL COMMENT 'the meeting link, or a detail page; EVD-01: mobile only shows/enables it from 15 min before start',
    detail_url VARCHAR(500) NULL COMMENT 'an existing static detail page for this event, if one exists',
    tint ENUM('blue','green','orange','purple','pink','teal','yellow') NOT NULL DEFAULT 'purple',
    is_live_badge_label VARCHAR(60) NULL COMMENT 'e.g. "LinkedIn Live"; shown as a badge, null = no badge',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_events_starts (starts_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS event_registrations (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_event_student (event_id, student_id),
    KEY idx_reg_student (student_id),
    CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    CONSTRAINT fk_reg_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed: the one real event that was hardcoded into events.php's HTML/JS.
INSERT INTO events (title, description, host, starts_at, ends_at, join_url, detail_url, tint, is_live_badge_label)
SELECT
    'The Gen-Z Experience',
    'What does it really feel like to enter the corporate world as a Gen-Z professional? Join host Scholar Mfeseer Alibo and guest speaker Georgina Ijachi alongside four panelists for an unfiltered live conversation about workplace culture, communication, and career growth in today''s world.',
    'Scholar Mfeseer Alibo',
    '2026-05-09 16:00:00',
    '2026-05-09 17:30:00',
    NULL,
    'events/gen_z_experience.php',
    'purple',
    'LinkedIn Live'
WHERE NOT EXISTS (SELECT 1 FROM events WHERE title = 'The Gen-Z Experience');

-- Verify: SELECT * FROM events; DESCRIBE event_registrations;
-- Rollback: DROP TABLE event_registrations, events;
