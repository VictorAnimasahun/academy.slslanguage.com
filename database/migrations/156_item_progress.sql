-- ============================================================
-- Migration 156 -- per-item progress: a class is done when everything in it is done
--
-- Founder, 2026-10-19: "A class is only completed when all its tests and quizzes and exercises have been
-- completed and (if required, passed by the required %) ... Only lessons can be manually completed", and
-- only after the student has spent a set time on the page (5 minutes, agreed).
--
--   lesson_item_progress  one row per student + class + lesson page: when it was first opened and when it
--                         was marked complete. The server refuses "complete" until 5 minutes after opening.
--   lesson_parts.test_code     the test code(s) (tests.code / mock_exams.code, comma-separated when every
--                              one is required, e.g. Writing Task 1 + Task 2) whose RECORDED result
--                              completes a quiz/test item.
--   lesson_parts.pass_percent  score needed (best attempt); NULL = completing is enough. Quizzes: 70.
--
-- Only results the platform actually records are used: reading and listening tests and class quizzes
-- (test_attempts), mocks and diagnostics (mock_sessions, once submitted). Writing and speaking practice
-- tests, the Foundational English Assessment, the CELPIP Diagnostic Test and exercises record nothing per
-- student yet, so those items are "not tracked" and don't hold a class back until they do.
-- includes/item_progress.php decides each item's state and keeps lesson_progress (the class tick every
-- page already reads) in step.
--
-- Also: six CELPIP class-day tests titled "(Coming Soon)" were marked ready -- now coming_soon.
--
-- MySQL 5.7-safe and re-runnable: CREATE TABLE IF NOT EXISTS; columns added only if missing (checked in
-- information_schema, since 5.7 has no ADD COLUMN IF NOT EXISTS); UPDATEs by file path, never by id.
-- ============================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS lesson_item_progress (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    lesson_id INT UNSIGNED NOT NULL COMMENT 'the class (lessons.id)',
    item_key VARCHAR(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'the lesson page path, without ?from=',
    opened_at DATETIME NOT NULL,
    completed_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY lip_student_item (student_id, lesson_id, item_key),
    KEY lip_lesson (lesson_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lesson_parts' AND COLUMN_NAME = 'test_code');
SET @s := IF(@c = 0, 'ALTER TABLE lesson_parts ADD COLUMN test_code VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL AFTER file_path', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

SET @c := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lesson_parts' AND COLUMN_NAME = 'pass_percent');
SET @s := IF(@c = 0, 'ALTER TABLE lesson_parts ADD COLUMN pass_percent TINYINT UNSIGNED DEFAULT NULL AFTER test_code', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- Codes read from each item's own page (generated 2026-10-19; only kinds whose results are recorded).
UPDATE lesson_parts SET test_code = 'CELPIP_PT_L_001', pass_percent = NULL WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c3&part=test';
UPDATE lesson_parts SET test_code = 'CELPIP_PT_R_001', pass_percent = NULL WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c4&part=test';
UPDATE lesson_parts SET test_code = 'CELPIP_PT_R_002', pass_percent = NULL WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c8&part=test';
UPDATE lesson_parts SET test_code = 'CELPIP_PT_R_003', pass_percent = NULL WHERE file_path = 'courses/CELPIP_Gen/lessons/class_day.php?slot=c12&part=test';
UPDATE lesson_parts SET test_code = 'IELTS_ACA_DIAGNOSTIC', pass_percent = NULL WHERE file_path = 'resources/mock_tests/ielts_aca_diagnostic.php';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C1_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C1_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C2_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C2_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C3_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C3_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C4_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C4_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C5_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C5_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C6_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C6_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GM_C7_QUIZ', pass_percent = 70 WHERE file_path = 'resources/quizzes/class_quiz.php?test_code=IELTS_GM_C7_QUIZ';
UPDATE lesson_parts SET test_code = 'IELTS_GT_DIAGNOSTIC', pass_percent = NULL WHERE file_path = 'resources/mock_tests/ielts_gt_diagnostic.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_L_001', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_listening_001.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_L_002', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_listening_002.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_001', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_001.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_002', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_002.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_ACA_002', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_academic_002.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_ACA_C17T1', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_academic_c17_t1.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_ACA_C17T3', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_academic_c17_t3.php';
UPDATE lesson_parts SET test_code = 'IELTS_PT_R_ACA_C17T4', pass_percent = NULL WHERE file_path = 'resources/practice_tests/ielts_reading_academic_c17_t4.php';

-- Mocks have no page of their own; the course schedule already says which mock is in which class.
UPDATE lesson_parts p JOIN course_pacing_items ci ON ci.lesson_id = p.lesson_id AND ci.item_type = 'mock_test'
   SET p.test_code = ci.test_code
 WHERE p.kind = 'mock_test' AND p.test_code IS NULL;

-- Titled "Coming Soon" but marked ready.
UPDATE lesson_parts SET status = 'coming_soon' WHERE status = 'ready' AND title LIKE '%Coming Soon%';
