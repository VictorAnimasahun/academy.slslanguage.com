-- ============================================================
-- Migration 079 — Speaking recordings (audio + transcript pipeline)
--
-- Backs the CELPIP Speaking Practice Test audio feature: students'
-- recorded responses are uploaded, transcribed (Groq Whisper), and the
-- transcript is kept permanently even after an admin deletes the audio
-- file itself (audio_path is nulled out, transcript/ai_feedback survive).
--
-- Scope: built first for the 3 CELPIP practice tests
-- (CELPIP_PT_S_001/002/003), with mock_session_id included now (nullable)
-- so the planned Phase 2 -- the same pipeline for the CELPIP Full Mock
-- A/B speaking sections, tied to mock_sessions -- doesn't need a second
-- schema migration. The Full Mock speaking pages don't exist online yet
-- as of this migration; mock_session_id is simply unused (NULL) until
-- Phase 2 builds them.
--
-- Idempotent — safe to re-run.
-- ============================================================

CREATE TABLE IF NOT EXISTS speaking_recordings (
    id                 INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id         INT UNSIGNED NOT NULL,
    test_code          VARCHAR(64) NOT NULL,
    mock_session_id    INT UNSIGNED NULL,
    task_number        TINYINT UNSIGNED NOT NULL,
    task_title         VARCHAR(255) NULL,
    prompt             TEXT NULL,
    audio_path         VARCHAR(255) NULL,
    audio_deleted_at   DATETIME NULL,
    transcript         MEDIUMTEXT NULL,
    transcript_status  ENUM('pending', 'transcribing', 'done', 'failed') NOT NULL DEFAULT 'pending',
    ai_feedback        MEDIUMTEXT NULL,
    graded_at          DATETIME NULL,
    created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_student (student_id),
    INDEX idx_student_test (student_id, test_code),
    INDEX idx_mock_session (mock_session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rollback:
-- DROP TABLE IF EXISTS speaking_recordings;
