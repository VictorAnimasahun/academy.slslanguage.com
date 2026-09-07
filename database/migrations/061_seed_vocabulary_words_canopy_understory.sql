-- Migration 061 — Seed "canopy" and "understory" into vocabulary_words
-- Phase 1 follow-up to the Vocabulary Banks feature (continues from migration 060).
-- Run on LOCAL first, then LIVE.
-- INSERT IGNORE: safe to re-run — skips rows that already exist (headword has UNIQUE KEY).

INSERT IGNORE INTO vocabulary_words
    (headword, phonetic, word_class, cefr_level, is_awl, definition, secondary_definitions, synonyms, antonyms, collocations, word_family, sort_order)
VALUES

('canopy',
 '/ˈkæn.ə.pi/',
 'noun', 'B2', 0,
 'The uppermost layer of a forest, formed by the spreading branches and leaves of the tallest trees.',
 'A decorative covering hung over a bed, throne, or entrance; also, the fabric part of a parachute.',
 'tree cover, forest ceiling, treetop layer',
 'understory, forest floor',
 'dense canopy, forest canopy, canopy cover, canopy walkway',
 'canopy (n.) · canopied (adj.)',
 35),

('understory',
 '/ˈʌn.dəˌstɔː.ri/',
 'noun', 'C1', 0,
 'The layer of vegetation, shrubs, and small trees growing beneath the main canopy of a forest.',
 NULL,
 'undergrowth, underbrush, understorey (British spelling)',
 'canopy, forest ceiling',
 'dense understory, forest understory, understory vegetation, understory species',
 'understory (n.) · understorey (n., BrE variant)',
 36);
