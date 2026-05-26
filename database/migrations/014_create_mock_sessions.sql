-- Migration 014 — Create mock_sessions table + seed IELTS Full Mock 1
-- Run on LOCAL first, then LIVE.
-- Safe to re-run (idempotent).

CREATE TABLE IF NOT EXISTS mock_sessions (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mock_test_id          INT UNSIGNED NOT NULL,
    student_id            INT UNSIGNED NOT NULL,
    status                ENUM('in_progress','awaiting_speaking_grade','results_released') NOT NULL DEFAULT 'in_progress',
    listening_attempt_id  INT UNSIGNED NULL,
    reading_attempt_id    INT UNSIGNED NULL,
    writing_attempt_id    INT UNSIGNED NULL,
    writing_band          DECIMAL(3,1) NULL,
    writing_ai_feedback   TEXT NULL,
    speaking_band         DECIMAL(3,1) NULL,
    speaking_notes        TEXT NULL,
    overall_band          DECIMAL(3,1) NULL,
    released_at           TIMESTAMP NULL,
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_student (student_id),
    INDEX idx_status (status),
    INDEX idx_mock_test (mock_test_id)
);

INSERT INTO tests (code, title, description, test_type, category, is_mock_section, duration_minutes, total_questions, is_active)
SELECT 'IELTS_FULL_MOCK_001',
       'IELTS Full Mock Test 1',
       'Complete IELTS mock exam: Listening (40Q/30min), Reading (40Q/60min), Writing (2 tasks/60min), Speaking (instructor-administered).',
       'IELTS', 'Full', 1, 190, 82, 1
WHERE NOT EXISTS (SELECT 1 FROM tests WHERE code = 'IELTS_FULL_MOCK_001');