-- ============================================================
-- Migration 155 -- IELTS Academic and PTE 2/3-month: Class 1 = course, Class 2 = exam, Class 3 = sections
--
-- Same rule as 154 (founder, 2026-10-19), with the founder's approval of where the old Class 2
-- section content goes:
--   IELTS Academic 2/3-Month (16, 17)
--     Class 1: Course Introduction (new, built from the course's own data) + Foundational English Assessment
--     Class 2: IELTS Academic Introduction (the old "Orientation" page, which was about the exam)
--              + Writing Task Overview (exam information) + IELTS Academic Introduction Quiz (Coming Soon)
--              + IELTS Academic Diagnostic Test
--     Class 3: Listening Test 1 (moved from Class 2) + Reading Test 1 + Trend Vocabulary & Paraphrasing
--   PTE 2/3-Month (20, 21)
--     Class 1: Course Introduction (new) + Foundational English Assessment
--     Class 2: PTE Academic Introduction (the old Class 1 page) + PTE Academic Introduction Quiz (Coming Soon)
--              + PTE Academic Diagnostic Test (Coming Soon: none exists yet)
--     Class 3: Speaking -- Read Aloud & Repeat Sentence (moved from Class 2) + Speaking -- Describe Image &
--              Re-tell Lecture
-- Pages that moved were renamed to match their new class (classNN_pN_...), and their render_lesson_shell()
-- calls point at the new class and position.
--
-- Classes 1-3 of these courses are rewritten in full (delete + insert, fixed order): nothing refers to an
-- item row by id. Classes found by course + week + class order; each course's own folder via
-- courses.folder_name. One transaction. Re-runnable, MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;

-- All or nothing: if any statement fails, nothing is committed, so no class is ever left empty.
START TRANSACTION;

DELETE p FROM lesson_parts p JOIN lessons l ON l.id = p.lesson_id JOIN modules m ON m.id = l.module_id
 WHERE l.course_id IN (16, 17, 20, 21) AND (m.module_order - 1) * 2 + l.lesson_order IN (1, 2, 3);

INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path)
SELECT l.id, x.ord, x.title, x.kind, x.status,
       CASE WHEN x.file LIKE 'resources/%' OR x.file LIKE 'mini_tests/%' OR x.file IS NULL THEN x.file
            ELSE CONCAT('courses/', CONVERT(c.folder_name USING utf8mb4) COLLATE utf8mb4_unicode_ci, '/lessons/',
                        CONVERT(x.file USING utf8mb4) COLLATE utf8mb4_unicode_ci) END
  FROM lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN courses c ON c.id = l.course_id
  JOIN (
        SELECT 'aca' AS grp, 1 AS cls, 1 AS ord, 'Course Introduction' AS title, 'lesson' AS kind, 'ready' AS status, 'class01_p1_course-introduction.php' AS file
        UNION ALL SELECT 'aca', 1, 2, 'Foundational English Assessment', 'placement_test', 'ready', 'mini_tests/english-placement-test/BELStart.html'
        UNION ALL SELECT 'aca', 2, 1, 'IELTS Academic Introduction', 'lesson', 'ready', 'class02_p1_ielts-academic-introduction.php'
        UNION ALL SELECT 'aca', 2, 2, 'Writing Task Overview', 'lesson', 'ready', 'class02_p2_writing-task-overview.php'
        UNION ALL SELECT 'aca', 2, 3, 'IELTS Academic Introduction Quiz', 'quiz', 'coming_soon', NULL
        UNION ALL SELECT 'aca', 2, 4, 'IELTS Academic Diagnostic Test', 'diagnostic_test', 'ready', 'resources/mock_tests/ielts_aca_diagnostic.php'
        UNION ALL SELECT 'aca', 3, 1, 'Listening Test 1', 'practice_test', 'ready', 'resources/practice_tests/ielts_listening_001.php'
        UNION ALL SELECT 'aca', 3, 2, 'Reading Test 1', 'practice_test', 'ready', 'resources/practice_tests/ielts_reading_academic_c17_t1.php'
        UNION ALL SELECT 'aca', 3, 3, 'Trend Vocabulary & Paraphrasing', 'lesson', 'ready', 'class03_p3_trend-vocabulary-paraphrasing.php'
        UNION ALL SELECT 'pte', 1, 1, 'Course Introduction', 'lesson', 'ready', 'class01_p1_course-introduction.php'
        UNION ALL SELECT 'pte', 1, 2, 'Foundational English Assessment', 'placement_test', 'ready', 'mini_tests/english-placement-test/BELStart.html'
        UNION ALL SELECT 'pte', 2, 1, 'PTE Academic Introduction', 'lesson', 'ready', 'class02_p1_pte-academic-introduction.php'
        UNION ALL SELECT 'pte', 2, 2, 'PTE Academic Introduction Quiz', 'quiz', 'coming_soon', NULL
        UNION ALL SELECT 'pte', 2, 3, 'PTE Academic Diagnostic Test', 'diagnostic_test', 'coming_soon', NULL
        UNION ALL SELECT 'pte', 3, 1, 'Speaking — Read Aloud & Repeat Sentence', 'lesson', 'ready', 'class03_p1_speaking-read-aloud-repeat-sentence.php'
        UNION ALL SELECT 'pte', 3, 2, 'Speaking — Describe Image & Re-tell Lecture', 'lesson', 'ready', 'class03_p2_speaking-describe-image-re-tell-lecture.php'
       ) x ON x.grp = IF(l.course_id IN (16, 17), 'aca', 'pte') AND x.cls = (m.module_order - 1) * 2 + l.lesson_order
 WHERE l.course_id IN (16, 17, 20, 21);

-- Class titles: each class's items joined with " + "; the class link is its first item.
UPDATE lessons l JOIN modules m ON m.id = l.module_id
   SET l.title = CASE (m.module_order - 1) * 2 + l.lesson_order
         WHEN 1 THEN 'Course Introduction + Foundational English Assessment'
         WHEN 2 THEN IF(l.course_id IN (16, 17),
                        'IELTS Academic Introduction + Writing Task Overview + IELTS Academic Introduction Quiz + IELTS Academic Diagnostic Test',
                        'PTE Academic Introduction + PTE Academic Introduction Quiz + PTE Academic Diagnostic Test')
         ELSE IF(l.course_id IN (16, 17),
                 'Listening Test 1 + Reading Test 1 + Trend Vocabulary & Paraphrasing',
                 'Speaking — Read Aloud & Repeat Sentence + Speaking — Describe Image & Re-tell Lecture') END,
       l.file_path = (SELECT p.file_path FROM lesson_parts p WHERE p.lesson_id = l.id AND p.part_order = 1)
 WHERE l.course_id IN (16, 17, 20, 21) AND (m.module_order - 1) * 2 + l.lesson_order IN (1, 2, 3);

COMMIT;
