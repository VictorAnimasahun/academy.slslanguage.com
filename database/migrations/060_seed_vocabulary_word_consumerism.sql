-- Migration 060 — Seed "consumerism" into vocabulary_words
-- Phase 1 follow-up to the Vocabulary Banks feature (continues from migration 059's batch 2).
-- Run on LOCAL first, then LIVE.
-- INSERT IGNORE: safe to re-run — skips rows that already exist (headword has UNIQUE KEY).

INSERT IGNORE INTO vocabulary_words
    (headword, phonetic, word_class, cefr_level, is_awl, definition, secondary_definitions, synonyms, antonyms, collocations, word_family, sort_order)
VALUES

('consumerism',
 '/kənˈsjuː.mər.ɪ.zəm/',
 'noun', 'C1', 0,
 'The belief that it is good to spend money and buy a large number of goods and services, or the theory that a rising level of consumption is beneficial to an economy.',
 'The protection of the rights and interests of consumers.',
 'materialism, consumption, spending',
 'frugality, minimalism, austerity',
 'rampant consumerism, rise of consumerism, consumerism and the environment',
 'consume (v.) · consumer (n.) · consumption (n.) · consumerism (n.) · consumerist (adj.)',
 34);
