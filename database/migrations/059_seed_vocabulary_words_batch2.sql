-- Migration 059 — Seed batch 2 of vocabulary words (idiomatic/compound expressions)
-- Phase 1 follow-up to the Vocabulary Banks feature (continues from migration 041's batch 1).
-- Run on LOCAL first, then LIVE.
-- INSERT IGNORE: safe to re-run — skips rows that already exist (headword has UNIQUE KEY).

INSERT IGNORE INTO vocabulary_words
    (headword, phonetic, word_class, cefr_level, is_awl, definition, secondary_definitions, synonyms, antonyms, collocations, word_family, sort_order)
VALUES

('colour in',
 '/ˈkʌl.ər ɪn/',
 'phrase', 'A2', 0,
 'To fill an outlined picture or shape with colour, typically using crayons, pencils, or paint.',
 NULL,
 'shade in, fill in',
 NULL,
 'colour in a picture, colour in the outline, colour in neatly',
 'colour (n./v.) · colourful (adj.) · colouring (n.)',
 31),

('loan-sourced',
 '/ˈləʊn sɔːst/',
 'adjective', 'C1', 0,
 'Obtained or financed by means of a loan, rather than from savings, grants, or other funds.',
 NULL,
 'debt-financed, borrowed',
 'self-funded, equity-financed',
 'loan-sourced capital, loan-sourced funding, loan-sourced investment',
 'loan (n./v.) · source (n./v.) · sourced (adj.)',
 32),

('slap-on',
 '/ˈslæp ɒn/',
 'adjective', 'B2', 0,
 'Done or applied quickly and carelessly, without much thought or attention to detail.',
 NULL,
 'hasty, careless, slapdash',
 'careful, meticulous, thorough',
 'a slap-on approach, a slap-on repair, a slap-on solution',
 'slap (n./v.) · slap on (phrasal v.)',
 33);
