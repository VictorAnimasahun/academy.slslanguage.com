-- Migration 040 — Create word_test_usages table
-- Phase 1, Step 3 of the Vocabulary Banks feature.
-- Stores per-word example sentences keyed by exam type, skill, and sub-section.
-- Run on LOCAL first, then LIVE.
-- Safe to re-run (CREATE TABLE IF NOT EXISTS guard).

CREATE TABLE IF NOT EXISTS word_test_usages (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    word_id          INT UNSIGNED NOT NULL,
    exam_type        ENUM('IELTS','CELPIP','PTE','General') NOT NULL DEFAULT 'IELTS',
    skill            ENUM('Listening','Reading','Writing','Speaking') NOT NULL,
    sub_section      VARCHAR(100) NOT NULL  COMMENT 'e.g. Task 1, Task 2, Part 2, Part 3, Section 2, Summarize Written Text, Read Aloud',
    example_sentence TEXT NOT NULL          COMMENT 'Sentence showing the word in this context',
    context_note     VARCHAR(255) NULL      COMMENT 'Optional tip, e.g. "common in Academic Reading passages"',
    sort_order       TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_word       (word_id),
    INDEX idx_exam_skill (exam_type, skill)
);
