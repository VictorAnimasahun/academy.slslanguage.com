-- Migration 045: Extend assignments table
-- Adds test_id (FK to tests), type, and created_at
-- Safe to re-run: each ALTER checks column absence via stored procedure trick

ALTER TABLE assignments
    ADD COLUMN test_id    INT UNSIGNED NULL         AFTER course_id,
    ADD COLUMN type       ENUM('test','quiz','vocabulary','task') NOT NULL DEFAULT 'task' AFTER test_id,
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER due_date;

ALTER TABLE assignments
    ADD CONSTRAINT fk_assignments_test
    FOREIGN KEY (test_id) REFERENCES tests(id) ON DELETE SET NULL;
