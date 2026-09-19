-- ============================================================
-- Migration 102 -- Repair non-ASCII characters mangled to '?' in vocabulary_words
--
-- Symptom: Word of the Day showed "/ri???t.?.re?t/" instead of "/riːˈɪt.ə.reɪt/".
-- Cause: seed migrations 041/059/060/061 were imported through a client
-- connection that was not utf8mb4, so IPA symbols (and typographic
-- characters such as the middle dot in word_family) were stored as literal '?'.
-- The seed files themselves are correct UTF-8.
--
-- This re-applies the correct text from those seeds, but ONLY into columns
-- that currently contain '?' -- a phonetic or definition edited later through
-- sls-admin (which would not contain '?') is never overwritten. Idempotent.
--
-- HOW TO RUN: the import connection MUST be utf8mb4, or this will just
-- re-corrupt the text. Use `mysql --default-character-set=utf8mb4 ...`, or in
-- phpMyAdmin choose "utf8mb4" as the file character set on the Import tab.
-- Run on LOCAL first, then LIVE (take a backup first).
-- ============================================================

SET NAMES utf8mb4;

UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌæb.səntˈmaɪn.dɪd.li/', phonetic), word_family = IF(word_family LIKE '%?%', 'absentminded (adj.) · absentmindedness (n.)', word_family) WHERE headword = 'absentmindedly';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɑːˈtɪk.jə.lət/', phonetic), word_family = IF(word_family LIKE '%?%', 'articulate (v.) · articulation (n.) · articulate (adj.)', word_family) WHERE headword = 'articulate';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈtʃɑː.mɪŋ/', phonetic), word_family = IF(word_family LIKE '%?%', 'charm (n./v.) · charmed (adj.) · charmingly (adv.)', word_family) WHERE headword = 'charming';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/kəmˈpleks/', phonetic), word_family = IF(word_family LIKE '%?%', 'complex (adj./n.) · complexity (n.) · complexly (adv.)', word_family) WHERE headword = 'complex';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌkɒz.məˈpɒl.ɪ.tən/', phonetic), word_family = IF(word_family LIKE '%?%', 'cosmopolitan (adj.) · cosmopolitanism (n.)', word_family) WHERE headword = 'cosmopolitan';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈdek.ə.dənt/', phonetic), word_family = IF(word_family LIKE '%?%', 'decadent (adj.) · decadence (n.)', word_family) WHERE headword = 'decadent';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/dɪsˈkwaɪ.ət/', phonetic), word_family = IF(word_family LIKE '%?%', 'disquiet (n.) · disquieting (adj.)', word_family) WHERE headword = 'disquiet';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪkˈsen.trɪk/', phonetic), word_family = IF(word_family LIKE '%?%', 'eccentric (adj./n.) · eccentricity (n.)', word_family) WHERE headword = 'eccentric';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈen.vi.əs/', phonetic), word_family = IF(word_family LIKE '%?%', 'envious (adj.) · envy (n./v.)', word_family) WHERE headword = 'envious';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ɪˈvɒk.ə.tɪv/', phonetic), word_family = IF(word_family LIKE '%?%', 'evocative (adj.) · evoke (v.) · evocation (n.)', word_family) WHERE headword = 'evocative';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈfleɪɡ.rənt/', phonetic), word_family = IF(word_family LIKE '%?%', 'flagrant (adj.) · flagrantly (adv.)', word_family) WHERE headword = 'flagrant';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌdʒen.trɪ.fɪˈkeɪ.ʃən/', phonetic), word_family = IF(word_family LIKE '%?%', 'gentrify (v.) · gentrified (adj.)', word_family) WHERE headword = 'gentrification';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈɡrʌm.bəl.ɪŋ/', phonetic), word_family = IF(word_family LIKE '%?%', 'grumble (v.) · grumbler (n.)', word_family) WHERE headword = 'grumbling';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkɜː.fʌf.əl/', phonetic) WHERE headword = 'kerfuffle';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌlæk.əˈdeɪ.zɪ.kəl/', phonetic), word_family = IF(word_family LIKE '%?%', 'lackadaisical (adj.) · lackadaisicality (n.)', word_family) WHERE headword = 'lackadaisical';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈnjuː.ɑːns/', phonetic), word_family = IF(word_family LIKE '%?%', 'nuanced (adj.) · nuancedly (adv.)', word_family) WHERE headword = 'nuance';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/aʊtˈstrɪp/', phonetic), word_family = IF(word_family LIKE '%?%', 'outstrip (v.) · outstripping (n.)', word_family) WHERE headword = 'outstrip';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈpes.tər/', phonetic), word_family = IF(word_family LIKE '%?%', 'pester (v.) · pestering (n./adj.)', word_family) WHERE headword = 'pester';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/prɪˈkeə.ri.əs/', phonetic), word_family = IF(word_family LIKE '%?%', 'precarious (adj.) · precariousness (n.)', word_family) WHERE headword = 'precarious';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈpred.ɪ.ses.ər/', phonetic), word_family = IF(word_family LIKE '%?%', 'predecessor (n.) · predecessor model', word_family) WHERE headword = 'predecessor';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌkwɪn.tɪˈsen.ʃəl/', phonetic), word_family = IF(word_family LIKE '%?%', 'quintessential (adj.) · quintessentially (adv.)', word_family) WHERE headword = 'quintessential';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/riːˈɪt.ə.reɪt/', phonetic), word_family = IF(word_family LIKE '%?%', 'reiterate (v.) · reiteration (n.)', word_family) WHERE headword = 'reiterate';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈrel.ə.vənt/', phonetic), word_family = IF(word_family LIKE '%?%', 'relevant (adj.) · relevance (n.)', word_family) WHERE headword = 'relevant';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/rʌɡ pʊl/', phonetic) WHERE headword = 'rug pull';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˌself.dep.rəˈkeɪ.ʃən/', phonetic) WHERE headword = 'self-deprecation';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/stɪf ˈʌp.ər lɪp/', phonetic) WHERE headword = 'stiff-upper lip';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈtæn.təl.aɪ.zɪŋ/', phonetic), word_family = IF(word_family LIKE '%?%', 'tantalise (v.) · tantalisingly (adv.)', word_family) WHERE headword = 'tantalising';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ʌn.kənˈven.ʃən.əl/', phonetic), word_family = IF(word_family LIKE '%?%', 'unconventional (adj.) · unconventionality (n.)', word_family) WHERE headword = 'unconventional';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈvɪv.ɪd/', phonetic), word_family = IF(word_family LIKE '%?%', 'vivid (adj.) · vividly (adv.) · vividness (n.)', word_family) WHERE headword = 'vivid';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈwɪm.zɪ.kəl/', phonetic), word_family = IF(word_family LIKE '%?%', 'whimsical (adj.) · whimsy (n.)', word_family) WHERE headword = 'whimsical';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkʌl.ər ɪn/', phonetic), word_family = IF(word_family LIKE '%?%', 'colour (n./v.) · colourful (adj.) · colouring (n.)', word_family) WHERE headword = 'colour in';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈləʊn sɔːst/', phonetic), word_family = IF(word_family LIKE '%?%', 'loan (n./v.) · source (n./v.) · sourced (adj.)', word_family) WHERE headword = 'loan-sourced';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈslæp ɒn/', phonetic), word_family = IF(word_family LIKE '%?%', 'slap (n./v.) · slap on (phrasal v.)', word_family) WHERE headword = 'slap-on';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/kənˈsjuː.mər.ɪ.zəm/', phonetic), word_family = IF(word_family LIKE '%?%', 'consume (v.) · consumer (n.) · consumption (n.) · consumerism (n.) · consumerist (adj.)', word_family) WHERE headword = 'consumerism';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈkæn.ə.pi/', phonetic), word_family = IF(word_family LIKE '%?%', 'canopy (n.) · canopied (adj.)', word_family) WHERE headword = 'canopy';
UPDATE vocabulary_words SET phonetic = IF(phonetic LIKE '%?%', '/ˈʌn.dəˌstɔː.ri/', phonetic), word_family = IF(word_family LIKE '%?%', 'understory (n.) · understorey (n., BrE variant)', word_family) WHERE headword = 'understory';

-- Verify (both should return no rows / 0):
--   SELECT headword, phonetic FROM vocabulary_words WHERE phonetic LIKE '%?%';
--   SELECT headword FROM vocabulary_words WHERE word_family LIKE '%?%';
-- Spot check: SELECT phonetic FROM vocabulary_words WHERE headword='reiterate';  -- /riːˈɪt.ə.reɪt/
