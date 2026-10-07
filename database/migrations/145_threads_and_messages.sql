-- ============================================================
-- Migration 145 -- real two-way Messages: threads, thread_participants, thread_messages
--
-- The web's existing "Messages" (messages.php/message_view.php) is actually a one-way admin
-- announcement broadcast (broadcast_messages) -- there has never been a real conversation feature
-- between a student and a coach. This adds one (SLS-Academy-App-Spec.md 8.10, MSG-01..08). The
-- sidebar nav is relabelled in the same commit: the broadcast feed becomes "Notifications" (it
-- already behaves like one via the bell/drawer in topbar.php/footer.php) and a new "Messages"
-- item points at the real thing built here.
--
-- Group threads (MSG-05, a class cohort chat) are [PROPOSED] in the spec and not built tonight --
-- only one-to-one coach threads. `type` is still an enum with 'group' so that can be added later
-- without a schema change.
--
-- Idempotent (MySQL 5.7-safe): CREATE TABLE IF NOT EXISTS throughout. No seed data -- a real
-- conversation only starts when a student actually writes to their coach.
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS threads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    type ENUM('coach','group') NOT NULL DEFAULT 'coach',
    title VARCHAR(255) NULL COMMENT 'set for a group thread; a coach thread''s title is the coach''s name, looked up at read time',
    tint ENUM('blue','green','orange','purple','pink','teal','yellow') NOT NULL DEFAULT 'blue',
    mentor_id INT UNSIGNED NULL COMMENT 'set for a coach thread',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_threads_mentor (mentor_id),
    CONSTRAINT fk_threads_mentor FOREIGN KEY (mentor_id) REFERENCES mentors(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS thread_participants (
    thread_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    last_read_message_id INT UNSIGNED NULL,
    PRIMARY KEY (thread_id, student_id),
    KEY idx_participants_student (student_id),
    CONSTRAINT fk_participants_thread FOREIGN KEY (thread_id) REFERENCES threads(id) ON DELETE CASCADE,
    CONSTRAINT fk_participants_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS thread_messages (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL COMMENT 'a students.id -- a coach sending is just a student row with a staff_accounts entry',
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_messages_thread (thread_id, id),
    CONSTRAINT fk_messages_thread FOREIGN KEY (thread_id) REFERENCES threads(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages_sender FOREIGN KEY (sender_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verify: DESCRIBE threads; DESCRIBE thread_participants; DESCRIBE thread_messages;
-- Rollback: DROP TABLE thread_messages, thread_participants, threads;
