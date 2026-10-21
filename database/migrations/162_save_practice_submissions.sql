-- ============================================================
-- Migration 162 -- practice submissions are saved, graded, and count towards a class
--
-- Founder, 2026-10-21: "If the AI analyzer provides a score, add it to the student's data. If the tutor is the
-- grader, link that grade too. All the other four should be fixed too."
-- Writing and speaking practice tests, the CELPIP Diagnostic Test and the IELTS Listening formats quiz were scored
-- or analysed on screen and never saved. Now each submission is a completed test_attempts row
-- (includes/practice_attempts.php):
--  1. test_attempts gains response_text (the essay / transcript), ai_feedback, graded_by ('ai'|'tutor'),
--     tutor_notes, graded_at. band_score is the grade the student sees; a tutor's grade replaces the AI's.
--  2. tests rows for the codes the pages already use but that were never registered (IELTS Speaking 2-8, three
--     writing tasks, CELPIP Writing 4), plus CELPIP_DIAGNOSTIC and IELTS_QUIZ_L_FORMATS.
--  3. Course items get the test codes their pages save under, so a submission counts towards its class.
--     A writing test with two task pages needs both tasks submitted.
--
-- Re-run safe: columns added only when missing (MySQL 5.7 has no ADD COLUMN IF NOT EXISTS), rows inserted only
-- when missing, codes set by page path.
-- ============================================================

SET NAMES utf8mb4;

-- ---------- 1. test_attempts columns ----------
SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'test_attempts' AND COLUMN_NAME = 'response_text');
SET @s := IF(@c = 0,
  "ALTER TABLE test_attempts
     ADD COLUMN response_text MEDIUMTEXT NULL,
     ADD COLUMN ai_feedback MEDIUMTEXT NULL,
     ADD COLUMN graded_by ENUM('ai','tutor') NULL,
     ADD COLUMN tutor_notes TEXT NULL,
     ADD COLUMN graded_at TIMESTAMP NULL DEFAULT NULL",
  'SELECT 1');
PREPARE m162 FROM @s; EXECUTE m162; DEALLOCATE PREPARE m162;

START TRANSACTION;

-- ---------- 2. tests rows ----------
INSERT INTO tests (code, title, test_type, category, is_active)
SELECT x.code, x.title, x.test_type, x.category, 1
  FROM (SELECT 'IELTS_PT_S_002' AS code, 'IELTS Speaking – Practice Test 2' AS title, 'IELTS' AS test_type, 'Speaking' AS category
        UNION ALL SELECT 'IELTS_PT_S_003', 'IELTS Speaking – Practice Test 3', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_S_004', 'IELTS Speaking – Practice Test 4', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_S_005', 'IELTS Speaking – Practice Test 5', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_S_006', 'IELTS Speaking – Practice Test 6', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_S_007', 'IELTS Speaking – Practice Test 7', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_S_008', 'IELTS Speaking – Practice Test 8', 'IELTS', 'Speaking'
        UNION ALL SELECT 'IELTS_PT_W2_001', 'IELTS General Training Writing Task 2 – Practice Test 1', 'IELTS', 'Writing'
        UNION ALL SELECT 'IELTS_PT_W1_ACA_005', 'IELTS Academic Writing Task 1 – Test 5', 'IELTS', 'Writing'
        UNION ALL SELECT 'IELTS_PT_W2_ACA_005', 'IELTS Academic Writing Task 2 – Test 5', 'IELTS', 'Writing'
        UNION ALL SELECT 'CELPIP_PT_W1_004', 'CELPIP Writing Task 1 – Practice Test 4', 'CELPIP', 'Writing'
        UNION ALL SELECT 'CELPIP_PT_W2_004', 'CELPIP Writing Task 2 – Practice Test 4', 'CELPIP', 'Writing'
        UNION ALL SELECT 'CELPIP_DIAGNOSTIC', 'CELPIP Diagnostic Test', 'CELPIP', 'Diagnostic'
        UNION ALL SELECT 'IELTS_QUIZ_L_FORMATS', 'IELTS Listening – Question Formats Quiz', 'IELTS_Academic', 'Quiz') x
 WHERE NOT EXISTS (SELECT 1 FROM tests t WHERE t.code = x.code);

-- ---------- 3. course items carry the codes their pages save under ----------
-- IELTS Academic Writing Tests 1-4: Task 1 + Task 2 pages.
UPDATE lesson_parts
   SET test_code = CONCAT('IELTS_PT_W1_ACA_', SUBSTRING(file_path, -7, 3), ',IELTS_PT_W2_ACA_', SUBSTRING(file_path, -7, 3))
 WHERE file_path REGEXP '^resources/practice_tests/ielts_writing_academic_00[1-5]\\.php$';
-- IELTS General Training single-task writing tests.
UPDATE lesson_parts
   SET test_code = CONCAT('IELTS_PT_W', SUBSTRING(file_path, -9, 1), '_', SUBSTRING(file_path, -7, 3))
 WHERE file_path REGEXP '^resources/practice_tests/ielts_writing_t[12]_00[1-5]\\.php$';
-- Speaking practice tests.
UPDATE lesson_parts
   SET test_code = CONCAT('IELTS_PT_S_', SUBSTRING(file_path, -7, 3))
 WHERE file_path REGEXP '^resources/practice_tests/ielts_speaking_00[1-8]\\.php$';
UPDATE lesson_parts
   SET test_code = CONCAT('CELPIP_PT_S_', SUBSTRING(file_path, -7, 3))
 WHERE file_path REGEXP '^resources/practice_tests/celpip_speaking_00[1-4]\\.php$';
-- CELPIP Writing Practice Tests 1-3 (class-day test pages; slot c6 = test 1, c10 = 2, c14 = 3).
UPDATE lesson_parts
   SET test_code = CASE file_path
         WHEN 'courses/CELPIP_Gen/lessons/class_day.php?slot=c6&part=test' THEN 'CELPIP_PT_W1_001,CELPIP_PT_W2_001'
         WHEN 'courses/CELPIP_Gen/lessons/class_day.php?slot=c10&part=test' THEN 'CELPIP_PT_W1_002,CELPIP_PT_W2_002'
         ELSE 'CELPIP_PT_W1_003,CELPIP_PT_W2_003' END
 WHERE file_path IN ('courses/CELPIP_Gen/lessons/class_day.php?slot=c6&part=test',
                     'courses/CELPIP_Gen/lessons/class_day.php?slot=c10&part=test',
                     'courses/CELPIP_Gen/lessons/class_day.php?slot=c14&part=test');
-- CELPIP Diagnostic Test and the IELTS Listening formats quiz.
UPDATE lesson_parts SET test_code = 'CELPIP_DIAGNOSTIC'
 WHERE kind = 'diagnostic_test' AND file_path = 'courses/CELPIP_intro/celpip_mini_mock.php';
UPDATE lesson_parts SET test_code = 'IELTS_QUIZ_L_FORMATS'
 WHERE file_path = 'resources/practice_tests/ielts_listening_formats_sample.php';

COMMIT;
