-- ============================================================
-- Migration 161 -- crash courses sit Practice Test Set 1 + one mock; IELTS General mocks open the real Full Mocks
--
-- Founder, 2026-10-21:
--  * "Every crash course should also use the first practice tests of their corresponding masterclass courses ...
--    the first set of practice tests and the first mock test are the only tests the crash courses will have."
--      CELPIP Crash Course (12) had no practice tests: it gets the Masterclasses' Practice Test 1 in each skill, in
--        the class that finishes teaching that skill -- Listening in Class 2, Reading in Class 6 (after the extended
--        passages), Writing and Speaking in Class 7 (after the Survey task and Speaking Tasks 5-8).
--      IELTS Academic Crash Course (23): Listening Test 2 (Class 6) and Reading Test 2 (Class 7) come out.
--      IELTS General Crash Course (10): Practice Test Set 2 — Listening (Class 7) comes out.
--      Each crash course's one mock drops its "1": "Mock Test", "Mock Exam".
--    The test pages' course locks follow (Set 2+ and Mock 2+ no longer let crash-course students in).
--  * IELTS General mocks opened a briefing page with no test in it. They now open the real IELTS General Training
--    full mocks: Mock Test 1 = IELTS Full Mock Test 1, Mock Test 2 = Full Mock Test 2, Mock Test 3 = Full Mock
--    Test 4 (Full Mock Test 3's content is reused by the IELTS General Training Diagnostic Test, so a student would
--    have seen it). Each carries its test code, so a submitted mock counts towards its class.
--    See documentation/CONTENT_BANK_MAP.md for which content serves which course and test.
--
-- One transaction. Inserts only add a test that isn't there; deletes and renames are matched by course, class and
-- title, so re-running is safe. MySQL 5.7-safe.
-- ============================================================

SET NAMES utf8mb4;
START TRANSACTION;

-- ---------- CELPIP Crash Course: Practice Test 1 in each skill ----------
INSERT INTO lesson_parts (lesson_id, part_order, title, kind, status, file_path, test_code)
SELECT l.id, x.po, x.title, 'practice_test', 'ready', x.fp, x.code
  FROM lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN (SELECT 2 AS cls, 2 AS po, 'Complete Listening Test (Practice Test 1)' AS title,
               'courses/CELPIP_Gen/lessons/celpip_listening_001.php' AS fp, 'CELPIP_PT_L_001' AS code
        UNION ALL SELECT 6, 2, 'Complete Reading Test (Practice Test 1)', 'resources/practice_tests/celpip_reading_001.php', 'CELPIP_PT_R_001'
        UNION ALL SELECT 7, 2, 'Complete Writing Test (Practice Test 1)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c6&part=test', NULL
        UNION ALL SELECT 7, 3, 'Complete Speaking Test (Practice Test 1)', 'resources/practice_tests/celpip_speaking_001.php', NULL) x
    ON (m.module_order - 1) * 2 + l.lesson_order = x.cls
 WHERE l.course_id = 12
   AND NOT EXISTS (SELECT 1 FROM lesson_parts p WHERE p.lesson_id = l.id AND p.title = x.title);

-- ---------- IELTS Academic Crash Course: no Test 2s ----------
DELETE p FROM lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
 WHERE l.course_id = 23 AND (m.module_order - 1) * 2 + l.lesson_order IN (6, 7)
   AND p.title IN ('Listening Test 2', 'Reading Test 2');
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.part_order = 1
 WHERE l.course_id = 23 AND (m.module_order - 1) * 2 + l.lesson_order IN (6, 7) AND p.kind = 'lesson' AND p.part_order = 2;

-- ---------- IELTS General Crash Course: no Set 2 ----------
DELETE p FROM lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
 WHERE l.course_id = 10 AND (m.module_order - 1) * 2 + l.lesson_order = 7 AND p.title = 'Practice Test Set 2 — Listening';
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.part_order = 3
 WHERE l.course_id = 10 AND (m.module_order - 1) * 2 + l.lesson_order = 7 AND p.kind = 'quiz' AND p.part_order = 4;

-- ---------- IELTS General: the real full mocks ----------
UPDATE lesson_parts p
  JOIN lessons l ON l.id = p.lesson_id
  JOIN modules m ON m.id = l.module_id
   SET p.file_path = CASE (m.module_order - 1) * 2 + l.lesson_order
                       WHEN 8 THEN 'resources/mock_tests/ielts_full_mock_001.php'
                       WHEN 16 THEN 'resources/mock_tests/ielts_full_mock_002.php'
                       ELSE 'resources/mock_tests/ielts_full_mock_004.php' END,
       p.test_code = CASE (m.module_order - 1) * 2 + l.lesson_order
                       WHEN 8 THEN 'IELTS_FULL_MOCK_001'
                       WHEN 16 THEN 'IELTS_FULL_MOCK_002'
                       ELSE 'IELTS_FULL_MOCK_004' END
 WHERE l.course_id IN (9, 10, 11) AND p.kind = 'mock_test' AND (m.module_order - 1) * 2 + l.lesson_order IN (8, 16, 24);
UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
   SET l.file_path = CASE (m.module_order - 1) * 2 + l.lesson_order
                       WHEN 8 THEN 'resources/mock_tests/ielts_full_mock_001.php'
                       WHEN 16 THEN 'resources/mock_tests/ielts_full_mock_002.php'
                       ELSE 'resources/mock_tests/ielts_full_mock_004.php' END
 WHERE l.course_id IN (9, 10, 11) AND (m.module_order - 1) * 2 + l.lesson_order IN (8, 16, 24)
   AND l.file_path LIKE 'courses/IELTS_Gen/lessons/class%';

-- ---------- A crash course's one mock has no number ----------
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.title = 'MOCK TEST — Full Timed Exam' WHERE l.course_id = 10 AND p.kind = 'mock_test' AND p.title = 'MOCK TEST 1 — Full Timed Exam (End of Month 1)';
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.title = 'Mock Test — Full Timed (L, R, W, S)' WHERE l.course_id = 23 AND p.kind = 'mock_test' AND p.title = 'Mock Test 1 — Full Timed (L, R, W, S)';
UPDATE lesson_parts p JOIN lessons l ON l.id = p.lesson_id
   SET p.title = 'Mock Exam — Full PTE Simulation' WHERE l.course_id = 18 AND p.kind = 'mock_test' AND p.title = 'Mock Exam 1 — Full PTE Simulation';

-- ---------- Class titles = their items; week titles follow ----------
UPDATE lessons l
  JOIN modules m ON m.id = l.module_id
  JOIN (SELECT lesson_id, GROUP_CONCAT(title ORDER BY part_order SEPARATOR ' + ') AS t FROM lesson_parts GROUP BY lesson_id) x
    ON x.lesson_id = l.id
   SET l.title = x.t
 WHERE (l.course_id = 12 AND (m.module_order - 1) * 2 + l.lesson_order IN (2, 6, 7))
    OR (l.course_id = 23 AND (m.module_order - 1) * 2 + l.lesson_order IN (6, 7, 8))
    OR (l.course_id = 10 AND (m.module_order - 1) * 2 + l.lesson_order IN (7, 8))
    OR (l.course_id = 18 AND (m.module_order - 1) * 2 + l.lesson_order = 8);

UPDATE modules SET module_title = 'Week 3 — Speaking Tasks 1-4, Extended Reading & Reading Test'
 WHERE course_id = 12 AND module_order = 3;
UPDATE modules SET module_title = 'Week 4 — Speaking Tasks 5-8, Survey, Writing & Speaking Tests, Mock Test'
 WHERE course_id = 12 AND module_order = 4;
UPDATE modules SET module_title = 'Week 3 — Writing Test 1 & Map Description'
 WHERE course_id = 23 AND module_order = 3;
UPDATE modules SET module_title = 'Week 4 — Process Description & Mock Test'
 WHERE course_id = 23 AND module_order = 4;
UPDATE modules SET module_title = 'Week 4 — Map Listening, Speaking Part 2 & Mock Test'
 WHERE course_id = 10 AND module_order = 4;
UPDATE modules SET module_title = 'Week 4 — AI Scoring Strategies & Mock Exam'
 WHERE course_id = 18 AND module_order = 4;

COMMIT;
