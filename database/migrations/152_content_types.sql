-- ============================================================
-- Migration 152 -- content types: every item and every content file says what it is
--
-- Founder, 2026-10-19: "we obviously have different content types. Lessons, tests, quizzes,
-- exercises, etc. ... tag every file thus, allowing you to write targeted behavioural code."
-- Behaviour (what may open on a phone, button wording, labels) now reads the type, never a guess
-- from a title or a folder name. includes/content_types.php is the one place that says what each
-- type does; the app gets the same answer from api/v1/content_types.php.
--
--   lesson           teaching content                                  phone: yes
--   quiz             short check (e.g. a class quiz)                   phone: yes
--   exercise         practice activity (drills, take-home tasks)       phone: yes
--   resource         reference material                                phone: yes
--   practice_test    a timed test in one or more skills                phone: no (web only, spec D11)
--   mock_test        a full mock exam                                  phone: no
--   diagnostic_test  current EXAM level (informally the "Mini Mock")   phone: no
--   placement_test   general ENGLISH level (Foundational English       phone: no
--                    Assessment)
--   analyser         AI Essay / Speaking analysers                     phone: no
--
-- 1. lesson_parts.kind gains quiz, exercise, diagnostic_test, placement_test.
-- 2. content_types: the type of every content FILE, as exact paths or folder prefixes (ending in
--    '/'); the longest match wins, so one file can override its folder.
-- 3. Existing items re-tagged with the new types.
--
-- MySQL 5.7-safe and re-runnable: MODIFY of an ENUM (only adds values), CREATE TABLE IF NOT EXISTS,
-- INSERT ... ON DUPLICATE KEY UPDATE, plain UPDATEs.
-- ============================================================

SET NAMES utf8mb4;

ALTER TABLE lesson_parts
  MODIFY kind ENUM('lesson','resource','practice_test','mock_test','quiz','exercise','diagnostic_test','placement_test')
  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lesson';

-- The types themselves, with the category the founder asked for (every practice test and mock is a
-- "test") and whether it must be done on a computer. Code reads this; it never lists types itself.
CREATE TABLE IF NOT EXISTS content_kinds (
    kind VARCHAR(32) COLLATE utf8mb4_unicode_ci NOT NULL,
    label VARCHAR(64) COLLATE utf8mb4_unicode_ci NOT NULL,
    category ENUM('lesson','practice','test','resource','tool') COLLATE utf8mb4_unicode_ci NOT NULL,
    web_only TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1 = computer only, never on the phone (spec D11)',
    PRIMARY KEY (kind)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO content_kinds (kind, label, category, web_only) VALUES
  ('lesson',          'Lesson',             'lesson',   0),
  ('quiz',            'Quiz',               'practice', 0),
  ('exercise',        'Exercise',           'practice', 0),
  ('resource',        'Resource',           'resource', 0),
  ('practice_test',   'Practice test',      'test',     1),
  ('mock_test',       'Mock test',          'test',     1),
  ('diagnostic_test', 'Diagnostic test',    'test',     1),
  ('placement_test',  'English assessment', 'test',     1),
  ('analyser',        'AI analyser',        'tool',     1)
ON DUPLICATE KEY UPDATE label = VALUES(label), category = VALUES(category), web_only = VALUES(web_only);

CREATE TABLE IF NOT EXISTS content_types (
    path VARCHAR(191) NOT NULL COMMENT 'path from the academy root; ends in / for a whole folder',
    kind ENUM('lesson','resource','practice_test','mock_test','quiz','exercise','diagnostic_test','placement_test','analyser')
         COLLATE utf8mb4_unicode_ci NOT NULL,
    note VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (path)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO content_types (path, kind, note) VALUES
  ('courses/',                                              'lesson',          'course lesson pages, unless listed below'),
  ('resources/quizzes/',                                    'quiz',            NULL),
  ('resources/exercises/',                                  'exercise',        NULL),
  ('resources/vocabulary_banks/',                           'resource',        NULL),
  ('resources/model_answers/',                              'resource',        NULL),
  ('resources/study_materials/',                            'resource',        NULL),
  ('resources/practice_tests/',                             'practice_test',   NULL),
  ('resources/practice_tests/ielts_listening_formats_sample.php', 'quiz',      'a quiz on Listening question types, used as a class item'),
  ('resources/practice_tests/my_results.php',               'resource',        'a student''s own results'),
  ('resources/practice_tests/my_speaking_result.php',       'resource',        'a student''s own results'),
  ('resources/practice_tests/practice_tests_home.php',      'resource',        'list of tests'),
  ('resources/mock_tests/',                                 'mock_test',       NULL),
  ('resources/mock_tests/ielts_aca_diagnostic.php',         'diagnostic_test', NULL),
  ('resources/mock_tests/ielts_gt_diagnostic.php',          'diagnostic_test', NULL),
  ('resources/mock_tests/diagnostic_',                      'diagnostic_test', 'diagnostic section pages (prefix)'),
  ('resources/diagnostic_tests/',                           'diagnostic_test', NULL),
  ('courses/CELPIP_intro/celpip_mini_mock.php',             'diagnostic_test', 'CELPIP Diagnostic Test'),
  ('courses/CELPIP_Gen/lessons/celpip_listening_001.php',   'practice_test',   'CELPIP Listening Practice Test 1'),
  ('mini_tests/',                                           'diagnostic_test', 'public mini mock tests'),
  ('mini_tests/english-placement-test/',                    'placement_test',  'Foundational English Assessment'),
  ('resources/essay_analyzer.php',                          'analyser',        NULL),
  ('resources/audio_analyzer.php',                          'analyser',        NULL),
  ('resources/speaking_test.html',                          'analyser',        NULL)
ON DUPLICATE KEY UPDATE kind = VALUES(kind), note = VALUES(note);

-- Re-tag existing items with the new, more exact types.
UPDATE lesson_parts SET kind = 'placement_test' WHERE title = 'Foundational English Assessment';
UPDATE lesson_parts SET kind = 'diagnostic_test'
 WHERE title IN ('CELPIP Diagnostic Test', 'IELTS General Training Diagnostic Test', 'IELTS Academic Diagnostic Test');
UPDATE lesson_parts SET kind = 'quiz'
 WHERE file_path = 'resources/practice_tests/ielts_listening_formats_sample.php';
