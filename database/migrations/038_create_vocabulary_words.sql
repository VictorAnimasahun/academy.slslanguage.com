-- Migration 038 — Create vocabulary_words table
-- Phase 1, Step 1 of the Vocabulary Banks feature.
-- Run on LOCAL first, then LIVE.
-- Safe to re-run (CREATE TABLE IF NOT EXISTS guard).

CREATE TABLE IF NOT EXISTS vocabulary_words (
    id                    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    headword              VARCHAR(100) NOT NULL,
    phonetic              VARCHAR(150) NULL         COMMENT 'IPA transcription, e.g. /ˈæn.ə.lɪ.sɪs/',
    word_class            ENUM(
                              'noun','verb','adjective','adverb',
                              'phrase','conjunction','preposition'
                          ) NOT NULL,
    cefr_level            ENUM('A2','B1','B2','C1','C2') NOT NULL DEFAULT 'B2',
    is_awl                TINYINT(1) NOT NULL DEFAULT 0  COMMENT '1 = on the Academic Word List',
    definition            TEXT NOT NULL                  COMMENT 'Primary definition',
    secondary_definitions TEXT NULL                      COMMENT 'Additional meanings, one per line',
    synonyms              TEXT NULL                      COMMENT 'Comma-separated list',
    antonyms              TEXT NULL                      COMMENT 'Comma-separated list',
    collocations          TEXT NULL                      COMMENT 'One collocation per line, e.g. significant increase',
    word_family           TEXT NULL                      COMMENT 'e.g. analysis (n.) · analyse (v.) · analytical (adj.) · analytically (adv.)',
    sort_order            INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Controls daily rotation order',
    is_active             TINYINT(1) NOT NULL DEFAULT 1,
    created_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uk_headword (headword),
    INDEX idx_word_class  (word_class),
    INDEX idx_cefr        (cefr_level),
    INDEX idx_awl         (is_awl),
    INDEX idx_active      (is_active),
    INDEX idx_sort        (sort_order)
);
