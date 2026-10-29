-- ============================================================
-- Migration 180 -- IELTS Listening Practice Test 1 (IELTS_PT_L_001): its answer key and its options
--
-- Found 2026-10-29 (academy-laravel audit): the test's 40 questions are in the bank but it has no answer key and no
-- options (migration 012, which holds them, never ran). Classic's page shows the options from its own PHP, but marks
-- from the bank, so every answer is marked wrong; academy-laravel builds the paper from the bank, so Q11-14 and
-- Q27-30 have no choices at all.
--
-- Adds, from 012 (and the page's own Q29-30 options A-E), only what is missing: a question that already has answers or
-- options is left alone. Questions are not deleted or re-created, so attempts already taken keep their answers.
-- Safe to run more than once. MySQL 5.7 and 8.0.
-- ============================================================

SET NAMES utf8mb4;

INSERT INTO question_correct_answers (question_id, answer_text, is_case_sensitive, is_alternative)
SELECT q.id, k.a, 0, k.alt
  FROM questions q
  JOIN tests t ON t.id = q.test_id AND t.code = 'IELTS_PT_L_001'
  JOIN (
    SELECT 1 AS n, 'jamieson' AS a, 0 AS alt
    UNION ALL SELECT 2 AS n, 'afternoon' AS a, 0 AS alt
    UNION ALL SELECT 3 AS n, 'communication' AS a, 0 AS alt
    UNION ALL SELECT 4 AS n, 'week' AS a, 0 AS alt
    UNION ALL SELECT 5 AS n, '10' AS a, 0 AS alt
    UNION ALL SELECT 5 AS n, 'ten' AS a, 1 AS alt
    UNION ALL SELECT 6 AS n, 'suit' AS a, 0 AS alt
    UNION ALL SELECT 7 AS n, 'passport' AS a, 0 AS alt
    UNION ALL SELECT 8 AS n, 'personality' AS a, 0 AS alt
    UNION ALL SELECT 9 AS n, 'feedback' AS a, 0 AS alt
    UNION ALL SELECT 10 AS n, 'time' AS a, 0 AS alt
    UNION ALL SELECT 15 AS n, 'river' AS a, 0 AS alt
    UNION ALL SELECT 16 AS n, '1422' AS a, 0 AS alt
    UNION ALL SELECT 17 AS n, 'top' AS a, 0 AS alt
    UNION ALL SELECT 18 AS n, 'pass' AS a, 0 AS alt
    UNION ALL SELECT 19 AS n, 'steam' AS a, 0 AS alt
    UNION ALL SELECT 20 AS n, 'capital' AS a, 0 AS alt
    UNION ALL SELECT 21 AS n, 'g' AS a, 0 AS alt
    UNION ALL SELECT 22 AS n, 'f' AS a, 0 AS alt
    UNION ALL SELECT 23 AS n, 'a' AS a, 0 AS alt
    UNION ALL SELECT 24 AS n, 'e' AS a, 0 AS alt
    UNION ALL SELECT 25 AS n, 'b' AS a, 0 AS alt
    UNION ALL SELECT 26 AS n, 'c' AS a, 0 AS alt
    UNION ALL SELECT 29 AS n, 'b' AS a, 0 AS alt
    UNION ALL SELECT 29 AS n, 'd' AS a, 1 AS alt
    UNION ALL SELECT 30 AS n, 'b' AS a, 0 AS alt
    UNION ALL SELECT 30 AS n, 'd' AS a, 1 AS alt
    UNION ALL SELECT 31 AS n, 'shelter' AS a, 0 AS alt
    UNION ALL SELECT 32 AS n, 'oil' AS a, 0 AS alt
    UNION ALL SELECT 33 AS n, 'roads' AS a, 0 AS alt
    UNION ALL SELECT 34 AS n, 'insects' AS a, 0 AS alt
    UNION ALL SELECT 35 AS n, 'grass' AS a, 0 AS alt
    UNION ALL SELECT 35 AS n, 'grasses' AS a, 1 AS alt
    UNION ALL SELECT 36 AS n, 'water' AS a, 0 AS alt
    UNION ALL SELECT 37 AS n, 'soil' AS a, 0 AS alt
    UNION ALL SELECT 38 AS n, 'dry' AS a, 0 AS alt
    UNION ALL SELECT 39 AS n, 'simple' AS a, 0 AS alt
    UNION ALL SELECT 40 AS n, 'nest' AS a, 0 AS alt
    UNION ALL SELECT 40 AS n, 'nests' AS a, 1 AS alt
  ) k ON k.n = q.question_number
 WHERE NOT EXISTS (SELECT 1 FROM question_correct_answers c WHERE c.question_id = q.id);

INSERT INTO question_options (question_id, option_label, option_text, is_correct, display_order)
SELECT q.id, k.l, k.t, k.c, k.o
  FROM questions q
  JOIN tests t ON t.id = q.test_id AND t.code = 'IELTS_PT_L_001'
  JOIN (
    SELECT 11 AS n, 'A' AS l, 'has been in business for longer than most of its competitors.' AS t, 1 AS c, 10 AS o
    UNION ALL SELECT 11 AS n, 'B' AS l, 'arranges holidays to more destinations than its competitors.' AS t, 0 AS c, 20 AS o
    UNION ALL SELECT 11 AS n, 'C' AS l, 'has more customers than its competitors.' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 12 AS n, 'A' AS l, 'Liverpool' AS t, 0 AS c, 10 AS o
    UNION ALL SELECT 12 AS n, 'B' AS l, 'Heysham' AS t, 1 AS c, 20 AS o
    UNION ALL SELECT 12 AS n, 'C' AS l, 'Luton' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 13 AS n, 'A' AS l, 'three' AS t, 1 AS c, 10 AS o
    UNION ALL SELECT 13 AS n, 'B' AS l, 'four' AS t, 0 AS c, 20 AS o
    UNION ALL SELECT 13 AS n, 'C' AS l, 'five' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 14 AS n, 'A' AS l, 'guaranteeing themselves a larger room.' AS t, 0 AS c, 10 AS o
    UNION ALL SELECT 14 AS n, 'B' AS l, 'booking at short notice.' AS t, 0 AS c, 20 AS o
    UNION ALL SELECT 14 AS n, 'C' AS l, 'transferring to another date.' AS t, 1 AS c, 30 AS o
    UNION ALL SELECT 27 AS n, 'A' AS l, 'There is conflicting evidence about whether oldest children perform best in intelligence tests.' AS t, 0 AS c, 10 AS o
    UNION ALL SELECT 27 AS n, 'B' AS l, 'There is little doubt that birth order has less influence on academic achievement than socio-economic status.' AS t, 0 AS c, 20 AS o
    UNION ALL SELECT 27 AS n, 'C' AS l, 'Some studies have neglected to include important factors such as family size.' AS t, 1 AS c, 30 AS o
    UNION ALL SELECT 28 AS n, 'A' AS l, 'It is mainly thanks to their roles as teachers for their younger siblings.' AS t, 1 AS c, 10 AS o
    UNION ALL SELECT 28 AS n, 'B' AS l, 'The advantages they have only lead to a slightly higher level of achievement.' AS t, 0 AS c, 20 AS o
    UNION ALL SELECT 28 AS n, 'C' AS l, 'The extra parental attention they receive at a young age makes little difference.' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 29 AS n, 'A' AS l, 'learning to share' AS t, 0 AS c, 10 AS o
    UNION ALL SELECT 29 AS n, 'B' AS l, 'learning to stand up for oneself' AS t, 1 AS c, 20 AS o
    UNION ALL SELECT 29 AS n, 'C' AS l, 'learning to be a good loser' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 29 AS n, 'D' AS l, 'learning to be tolerant' AS t, 1 AS c, 40 AS o
    UNION ALL SELECT 29 AS n, 'E' AS l, 'learning to say sorry' AS t, 0 AS c, 50 AS o
    UNION ALL SELECT 30 AS n, 'A' AS l, 'learning to share' AS t, 0 AS c, 10 AS o
    UNION ALL SELECT 30 AS n, 'B' AS l, 'learning to stand up for oneself' AS t, 1 AS c, 20 AS o
    UNION ALL SELECT 30 AS n, 'C' AS l, 'learning to be a good loser' AS t, 0 AS c, 30 AS o
    UNION ALL SELECT 30 AS n, 'D' AS l, 'learning to be tolerant' AS t, 1 AS c, 40 AS o
    UNION ALL SELECT 30 AS n, 'E' AS l, 'learning to say sorry' AS t, 0 AS c, 50 AS o
  ) k ON k.n = q.question_number
 WHERE NOT EXISTS (SELECT 1 FROM question_options x WHERE x.question_id = q.id);

-- Check: 40 questions, every one with a key (answers or a correct option).
SELECT COUNT(*) AS questions,
       SUM(EXISTS (SELECT 1 FROM question_correct_answers c WHERE c.question_id = q.id)
           OR EXISTS (SELECT 1 FROM question_options o WHERE o.question_id = q.id AND o.is_correct = 1)) AS keyed
  FROM questions q JOIN tests t ON t.id = q.test_id AND t.code = 'IELTS_PT_L_001';
