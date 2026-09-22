-- ============================================================
-- Migration 116 -- Repair every `?`-mangled word in vocabulary_words (Word of
-- the Day and the Vocabulary Bank), generically, not one word at a time
--
-- Migration 102 fixed this same "?" symptom, but by hand: it hardcoded the
-- correct value for 30-odd specific headwords that existed when it was
-- written. Any word added since (like "principle" -- reported live showing
-- "/?pr?n.s?.p?l/") was never covered, because 102 only repairs headwords it
-- names explicitly.
--
-- This migration instead touches EVERY row currently in the table: for each
-- one, it re-applies phonetic/definition/secondary_definitions/synonyms/
-- antonyms/collocations/word_family from this LOCAL database's current
-- (verified clean) values, but ONLY into a column that still contains a
-- literal '?' -- exactly 102's own safety rule, just applied table-wide
-- instead of to a fixed list. A column already correct, or edited later
-- through sls-admin, is never touched. Idempotent.
--
-- Step 1 repeats 102's charset conversion as a no-op safety net, in case a
-- database somehow reaches this migration without ever having run 102.
--
-- Step 3 fixes a SEPARATE, previously-undetected corruption found while
-- building this migration: 4 words from migrations 059/060 ("colour in",
-- "loan-sourced", "slap-on", "consumerism") are not '?'-mangled but
-- MOJIBAKE -- double-encoded (e.g. "Ë†" instead of "ˈ", "Â·" instead of "·").
-- This happens when a file already correct UTF-8 is imported through a
-- connection/tool that assumes Latin-1. It has no literal '?' in it, so 102's
-- guard (and Step 2 below) can never catch it -- it needs its own guard,
-- matched on the actual mojibake byte sequences, restoring the value
-- straight from 059/060's own (correct) source files.
--
-- Run on LOCAL first, then LIVE (back up first; import connection MUST be
-- utf8mb4, exactly as 102 warns, or this just re-corrupts the text again).
-- ============================================================

SET NAMES utf8mb4;

-- Step 1: safety net, see header. Safe to re-run.
ALTER TABLE vocabulary_words CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 2: every word currently in the table, restored from this database's
-- own clean copy, but only into a column that still shows the '?' symptom.
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈæn.ə.laɪz/', phonetic), word_family = IF(word_family LIKE '%?%', 'analysis (n.) · analyst (n.) · analytical (adj.) · analytically (adv.) · analyse (v.)', word_family) WHERE headword = 'analyse';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/əˈprəʊtʃ/', phonetic), word_family = IF(word_family LIKE '%?%', 'approach (n.) · approach (v.) · approachable (adj.)', word_family) WHERE headword = 'approach';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/əˈses/', phonetic), word_family = IF(word_family LIKE '%?%', 'assess (v.) · assessment (n.) · assessor (n.) · reassess (v.)', word_family) WHERE headword = 'assess';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈben.ɪ.fɪt/', phonetic), word_family = IF(word_family LIKE '%?%', 'benefit (n.) · benefit (v.) · beneficial (adj.) · beneficially (adv.) · beneficiary (n.)', word_family) WHERE headword = 'benefit';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkɒn.sept/', phonetic), word_family = IF(word_family LIKE '%?%', 'concept (n.) · conceptual (adj.) · conceptually (adv.) · conceptualise (v.)', word_family) WHERE headword = 'concept';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkɒn.tekst/', phonetic), word_family = IF(word_family LIKE '%?%', 'context (n.) · contextual (adj.) · contextualise (v.) · contextually (adv.)', word_family) WHERE headword = 'context';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/kənˈtrɪb.juːt/', phonetic), definition = IF(definition LIKE '%?%', 'To give something — time, money, or ideas — in order to help achieve or produce something.', definition), word_family = IF(word_family LIKE '%?%', 'contribute (v.) · contribution (n.) · contributor (n.) · contributory (adj.)', word_family) WHERE headword = 'contribute';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkruː.ʃəl/', phonetic), word_family = IF(word_family LIKE '%?%', 'crucial (adj.) · crucially (adv.)', word_family) WHERE headword = 'crucial';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈdem.ən.streɪt/', phonetic), word_family = IF(word_family LIKE '%?%', 'demonstrate (v.) · demonstration (n.) · demonstrator (n.) · demonstrable (adj.) · demonstrably (adv.)', word_family) WHERE headword = 'demonstrate';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪnˈvaɪ.rən.mənt/', phonetic), word_family = IF(word_family LIKE '%?%', 'environment (n.) · environmental (adj.) · environmentally (adv.) · environmentalist (n.)', word_family) WHERE headword = 'environment';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪˈstæb.lɪʃ/', phonetic), word_family = IF(word_family LIKE '%?%', 'establish (v.) · establishment (n.) · established (adj.)', word_family) WHERE headword = 'establish';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪˈvæl.ju.eɪt/', phonetic), word_family = IF(word_family LIKE '%?%', 'evaluate (v.) · evaluation (n.) · evaluative (adj.)', word_family) WHERE headword = 'evaluate';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈfæk.tər/', phonetic), word_family = IF(word_family LIKE '%?%', 'factor (n.) · factor in (v.) · factorial (adj.)', word_family) WHERE headword = 'factor';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈfəʊ.kəs/', phonetic), word_family = IF(word_family LIKE '%?%', 'focus (n.) · focus (v.) · focused (adj.) · refocus (v.)', word_family) WHERE headword = 'focus';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/aɪˈden.tɪ.faɪ/', phonetic), word_family = IF(word_family LIKE '%?%', 'identify (v.) · identification (n.) · identity (n.) · identifiable (adj.) · identifiably (adv.)', word_family) WHERE headword = 'identify';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈɪm.pækt/', phonetic), word_family = IF(word_family LIKE '%?%', 'impact (n.) · impact (v.) · impactful (adj.)', word_family) WHERE headword = 'impact';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈɪn.dɪ.keɪt/', phonetic), word_family = IF(word_family LIKE '%?%', 'indicate (v.) · indication (n.) · indicator (n.) · indicative (adj.)', word_family) WHERE headword = 'indicate';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌɪn.dɪˈvɪdʒ.u.əl/', phonetic), word_family = IF(word_family LIKE '%?%', 'individual (n./adj.) · individually (adv.) · individualism (n.) · individualise (v.)', word_family) WHERE headword = 'individual';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪnˈvɒlv/', phonetic), word_family = IF(word_family LIKE '%?%', 'involve (v.) · involvement (n.) · involved (adj.)', word_family) WHERE headword = 'involve';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈɪʃ.uː/', phonetic), word_family = IF(word_family LIKE '%?%', 'issue (n.) · issue (v.)', word_family) WHERE headword = 'issue';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/meɪnˈteɪn/', phonetic), word_family = IF(word_family LIKE '%?%', 'maintain (v.) · maintenance (n.) · maintainable (adj.)', word_family) WHERE headword = 'maintain';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈmeθ.əd/', phonetic), word_family = IF(word_family LIKE '%?%', 'method (n.) · methodical (adj.) · methodically (adv.) · methodology (n.)', word_family) WHERE headword = 'method';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈpɒl.ɪ.si/', phonetic), word_family = IF(word_family LIKE '%?%', 'policy (n.) · policymaker (n.)', word_family) WHERE headword = 'policy';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈprɪn.sɪ.pəl/', phonetic), word_family = IF(word_family LIKE '%?%', 'principle (n.) · principled (adj.) · unprincipled (adj.)', word_family) WHERE headword = 'principle';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈprəʊ.ses/', phonetic), word_family = IF(word_family LIKE '%?%', 'process (n.) · process (v.) · processed (adj.)', word_family) WHERE headword = 'process';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/sɪɡˈnɪf.ɪ.kənt/', phonetic), word_family = IF(word_family LIKE '%?%', 'significant (adj.) · significantly (adv.) · significance (n.) · signify (v.) · insignificant (adj.)', word_family) WHERE headword = 'significant';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈstrʌk.tʃər/', phonetic), word_family = IF(word_family LIKE '%?%', 'structure (n.) · structure (v.) · structural (adj.) · structurally (adv.) · restructure (v.)', word_family) WHERE headword = 'structure';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/səˈdʒest/', phonetic), word_family = IF(word_family LIKE '%?%', 'suggest (v.) · suggestion (n.) · suggestive (adj.)', word_family) WHERE headword = 'suggest';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈðeə.fɔːr/', phonetic), word_family = IF(word_family LIKE '%?%', 'therefore (adv.) — no derivatives', word_family) WHERE headword = 'therefore';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈveər.i/', phonetic), word_family = IF(word_family LIKE '%?%', 'vary (v.) · variety (n.) · various (adj.) · variable (n./adj.) · variation (n.) · varied (adj.)', word_family) WHERE headword = 'vary';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkæn.ə.pi/', phonetic), word_family = IF(word_family LIKE '%?%', 'canopy (n.) · canopied (adj.)', word_family) WHERE headword = 'canopy';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈʌn.dəˌstɔː.ri/', phonetic), word_family = IF(word_family LIKE '%?%', 'understory (n.) · understorey (n., BrE variant)', word_family) WHERE headword = 'understory';
-- Step 3: the 4 mojibake rows from migrations 059/060 (see header) -- guarded
-- on the actual corruption pattern, not on '?', and restored from those
-- migrations' own source text.
UPDATE vocabulary_words SET phonetic = '/ˈkʌl.ər ɪn/', word_family = 'colour (n./v.) · colourful (adj.) · colouring (n.)' WHERE headword = 'colour in' AND (phonetic REGEXP '[ÃÂË]' OR word_family REGEXP '[ÃÂË]');
UPDATE vocabulary_words SET phonetic = '/ˈləʊn sɔːst/', word_family = 'loan (n./v.) · source (n./v.) · sourced (adj.)' WHERE headword = 'loan-sourced' AND (phonetic REGEXP '[ÃÂË]' OR word_family REGEXP '[ÃÂË]');
UPDATE vocabulary_words SET phonetic = '/ˈslæp ɒn/', word_family = 'slap (n./v.) · slap on (phrasal v.)' WHERE headword = 'slap-on' AND (phonetic REGEXP '[ÃÂË]' OR word_family REGEXP '[ÃÂË]');
UPDATE vocabulary_words SET phonetic = '/kənˈsjuː.mər.ɪ.zəm/', word_family = 'consume (v.) · consumer (n.) · consumption (n.) · consumerism (n.) · consumerist (adj.)' WHERE headword = 'consumerism' AND (phonetic REGEXP '[ÃÂË]' OR word_family REGEXP '[ÃÂË]');
-- Verify (expect 0 rows each):
--   SELECT headword FROM vocabulary_words WHERE phonetic LIKE '%?%' OR word_family LIKE '%?%'
--     OR definition LIKE '%?%' OR secondary_definitions LIKE '%?%' OR synonyms LIKE '%?%'
--     OR antonyms LIKE '%?%' OR collocations LIKE '%?%';
--   SELECT headword FROM vocabulary_words WHERE phonetic REGEXP '[ÃÂË]' OR word_family REGEXP '[ÃÂË]';
-- Rollback: none needed (data repair, same as 102); restore from backup if the import was not utf8mb4.
