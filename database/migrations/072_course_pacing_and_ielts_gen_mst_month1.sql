-- ============================================================
-- Migration 072 — Enrollment-anchored course pacing (platform feature)
--
-- Instructor: assignment due dates should start counting "when a student
-- registers for the course now — every course should work like that",
-- not from a fixed shared calendar date. Today `assignments` has no
-- student_id at all (one row = one due date for every enrolled student,
-- and the table is essentially unused — only 1 stray row exists platform-
-- wide). This migration adds the pieces needed for real, per-student,
-- enrollment-anchored due dates as a general mechanism, then seeds Month 1
-- (Classes 1-8) of course_id=9 (IELTS General 3-Month Masterclass) as the
-- first real user of it.
--
-- Design:
--   - `course_pacing_items` is the TEMPLATE: for a given course, what quiz/
--     practice-test/mock-test is due, how many days after enrollment, and
--     which `tests.code` it launches. Authored once per course (like any
--     other seeded content), independent of any individual student.
--   - `assignments.student_id` (nullable) — NULL keeps today's existing
--     behaviour (one shared row/due date for every enrolled student, for
--     any assignment created by hand via sls-admin); a value personalizes
--     a row to one student's own enrollment-anchored due date.
--   - includes/course_pacing.php's generateAssignmentsForEnrollment() reads
--     course_pacing_items for a course and materializes one personalized
--     `assignments` row per item at the moment a student enrolls (hooked
--     into courses/courses_detail.php's enroll handler) — due_date =
--     enrollment date + offset_days. Missing test content (test_code not
--     yet seeded) is skipped, not fatal, so enrollment never breaks.
--
-- Month 1 offsets follow the syllabus's "2 classes/week" cadence, every
-- pair of classes 3 days apart, each week 7 days after the last:
-- Class 1=day0, 2=day3, 3=day7, 4=day10, 5=day14, 6=day17, 7=day21, 8=day24.
--
-- Idempotent — safe to re-run. Run on LOCAL first, then LIVE.
-- ============================================================

-- ── assignments.student_id ──────────────────────────────────────────────
SET @existing = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'assignments' AND COLUMN_NAME = 'student_id'
);
SET @sql = IF(
    @existing = 0,
    'ALTER TABLE assignments ADD COLUMN student_id INT UNSIGNED NULL DEFAULT NULL AFTER course_id, ADD INDEX idx_assignments_student (student_id)',
    'SELECT ''assignments.student_id already exists — skipping'' AS info'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ── course_pacing_items (the template) ──────────────────────────────────
-- Explicit charset/collation — a bare CREATE TABLE inherits the DATABASE's
-- default, which on live turned out to still be latin1_swedish_ci (an old
-- per-database default that predates this app's utf8mb4 connection charset)
-- even though every other table here (tests, courses, questions...) is
-- utf8mb4. That mismatch caused #1267 "Illegal mix of collations" the
-- moment this table's test_code/title columns were compared against the
-- utf8mb4 string literals in the INSERT below. The CONVERT statement after
-- also fixes it if migration 072 already partially ran on an environment
-- and left this table sitting there with the wrong (empty, since the
-- INSERT never succeeded) collation — safe to re-run either way.
CREATE TABLE IF NOT EXISTS course_pacing_items (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id     INT UNSIGNED NOT NULL,
    lesson_id     INT UNSIGNED NULL,
    item_type     ENUM('quiz','practice_test','mock_test') NOT NULL,
    title         VARCHAR(255) NOT NULL,
    test_code     VARCHAR(64) NOT NULL,
    offset_days   INT UNSIGNED NOT NULL,
    display_order INT NOT NULL DEFAULT 0,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pacing_course (course_id)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE course_pacing_items CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ── Seed: IELTS General 3-Month Masterclass (course_id=9), Month 1 ─────
-- Class 7's practice test (PT Set 2 — Listening) has no seeded test_code
-- yet (resources/practice_tests/ielts_listening_002.php exists on disk but
-- was never wired to a DB `tests` row — a pre-existing content gap, not
-- something this migration creates) — the row is added anyway so pacing
-- "just works" the moment that content is finished; until then
-- generateAssignmentsForEnrollment() silently skips it (missing test_code
-- is never fatal to enrollment).
INSERT INTO course_pacing_items (course_id, lesson_id, item_type, title, test_code, offset_days, display_order)
SELECT 9, d.lesson_id, d.item_type, d.title, d.test_code, d.offset_days, d.ord
FROM (
  SELECT 63 lesson_id, 'quiz' item_type, 'Class 1 Quiz — IELTS Format Knowledge' title, 'IELTS_GM_C1_QUIZ' test_code, 0 offset_days, 10 ord
  UNION ALL SELECT 64, 'quiz', 'Class 2 Quiz — Band Descriptor Matching', 'IELTS_GM_C2_QUIZ', 3, 20
  UNION ALL SELECT 65, 'practice_test', 'Class 3 — PT Set 1 Listening', 'IELTS_PT_L_001', 7, 30
  UNION ALL SELECT 65, 'quiz', 'Class 3 Quiz — Gap-Fill Prediction Strategies', 'IELTS_GM_C3_QUIZ', 7, 31
  UNION ALL SELECT 66, 'practice_test', 'Class 4 — PT Set 1 Reading', 'IELTS_PT_R_001', 10, 40
  UNION ALL SELECT 66, 'quiz', 'Class 4 Quiz — Skimming vs Scanning Decision Drill', 'IELTS_GM_C4_QUIZ', 10, 41
  UNION ALL SELECT 67, 'practice_test', 'Class 5 — PT Set 1 Writing Task 1', 'IELTS_PT_W1_001', 14, 50
  UNION ALL SELECT 67, 'quiz', 'Class 5 Quiz — MCQ Elimination Drill', 'IELTS_GM_C5_QUIZ', 14, 51
  UNION ALL SELECT 68, 'practice_test', 'Class 6 — PT Set 1 Speaking', 'IELTS_PT_S_001', 17, 60
  UNION ALL SELECT 68, 'quiz', 'Class 6 Quiz — TFNG vs YNNG Decision Drill', 'IELTS_GM_C6_QUIZ', 17, 61
  UNION ALL SELECT 69, 'practice_test', 'Class 7 — PT Set 2 Listening', 'IELTS_PT_L_002', 21, 70
  UNION ALL SELECT 69, 'quiz', 'Class 7 Quiz — Map Orientation Drill', 'IELTS_GM_C7_QUIZ', 21, 71
  UNION ALL SELECT 70, 'mock_test', 'Class 8 — Mock Test 1 (End of Month 1)', 'IELTS_FULL_MOCK_003', 24, 80
) d
WHERE NOT EXISTS (
  SELECT 1 FROM course_pacing_items cpi
  WHERE cpi.course_id = 9 AND cpi.test_code = d.test_code AND cpi.title = d.title
);

-- Verify: expect 13 rows for course_id=9
-- SELECT * FROM course_pacing_items WHERE course_id=9 ORDER BY display_order;
