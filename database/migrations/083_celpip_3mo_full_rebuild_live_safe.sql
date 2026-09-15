-- ============================================================
-- Migration 083 — CELPIP 3-Month rebuild, live-safe (supersedes 081 + 082)
--
-- Migrations 081 and 082 rebuilt CELPIP General Masterclass 3-Month into
-- the independent 24-class/12-week "one test + one micro-lesson per class
-- day" schedule, but both hardcoded course_id=14 and module ids 44-55.
-- Those only happen to be correct locally because course 14 and its
-- modules already existed there with those exact auto-increment values.
--
-- Investigation on 2026-09-15 (prompted by a live 500 error on every
-- course_view.php page, traced to migration 072's course_pacing_items
-- table never having been successfully applied on live) surfaced that
-- migrations 069-072, 075-078, and 081-082 are all unconfirmed on live.
-- Migration 077 creates CELPIP_Gen_3Mo fresh via auto-increment on any
-- environment where it doesn't already exist -- on live this will almost
-- certainly NOT land on id 14, exactly the class of bug migration 078
-- already fixed once for migration 074's hardcoded course 13 / lesson
-- 163/178 ids. 081's DELETE/INSERT against course_id=14 on a live DB
-- where that id belongs to a different course (or nothing) would either
-- silently affect 0 rows or, worse, write this course's 24 lessons under
-- an unrelated course_id.
--
-- This migration reproduces 081 + 082's combined end state in one step,
-- resolved entirely via folder_name='CELPIP_Gen_3Mo' + module_order
-- lookups instead of raw ids, so it is correct regardless of what
-- auto-increment values 077 produces on a given environment.
--
-- Run AFTER 077 (and 078, 076 per that migration's documented ordering).
-- Idempotent: wipes and rebuilds this course's modules/lessons every run,
-- same "wipe and redo" pattern 081 used locally, so re-running is safe.
-- 081 and 082 are left as-is (historical record of what ran locally) --
-- do not run them on live; run this instead.
-- ============================================================

DELETE l FROM lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

DELETE m FROM modules m
JOIN courses c ON c.id = m.course_id
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

INSERT INTO modules (course_id, module_title, module_order, min_tier)
SELECT c.id, x.module_title, x.module_order, 'beginner'
FROM courses c
JOIN (
    SELECT 1 AS module_order, 'Week 1 — Orientation + Diagnostics' AS module_title UNION ALL
    SELECT 2, 'Week 2 — Listening + Reading Focus' UNION ALL
    SELECT 3, 'Week 3 — Speaking + Writing Focus' UNION ALL
    SELECT 4, 'Week 4 — Listening + Reading Focus' UNION ALL
    SELECT 5, 'Week 5 — Speaking + Writing Focus' UNION ALL
    SELECT 6, 'Week 6 — Listening + Reading Focus' UNION ALL
    SELECT 7, 'Week 7 — Speaking + Writing Focus' UNION ALL
    SELECT 8, 'Week 8 — Mock 1' UNION ALL
    SELECT 9, 'Week 9 — Listening + Reading Focus' UNION ALL
    SELECT 10, 'Week 10 — Speaking + Writing Focus' UNION ALL
    SELECT 11, 'Week 11 — Mock 2' UNION ALL
    SELECT 12, 'Week 12 — Final Prep'
) x
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, min_tier)
SELECT c.id, m.id, x.title, x.file_path, x.lesson_order, x.min_tier
FROM courses c
JOIN modules m ON m.course_id = c.id
JOIN (
    SELECT 1 AS module_order, 1 AS lesson_order, 'Foundational English Assessment' AS title, 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php' AS file_path, 'beginner' AS min_tier UNION ALL
    SELECT 1, 2, 'Mini Diagnostic — All 4 Skills', 'courses/CELPIP_intro/celpip_mini_mock.php', 'beginner' UNION ALL
    SELECT 2, 1, 'Complete Listening Test + Reading Part 1 (Correspondence)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c3', 'intermediate' UNION ALL
    SELECT 2, 2, 'Complete Reading Test + Writing Task 1 & 2 Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c4', 'intermediate' UNION ALL
    SELECT 3, 1, 'Complete Speaking Test + Listening Inference & Signal Words', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c5', 'intermediate' UNION ALL
    SELECT 3, 2, 'Complete Writing Test + Speaking Clarity & Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c6', 'intermediate' UNION ALL
    SELECT 4, 1, 'Complete Listening Test + Reading Part 2 (Schedules & Diagrams)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c7', 'intermediate' UNION ALL
    SELECT 4, 2, 'Complete Reading Test + Writing Argument Vocabulary & Sentence Variety', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c8', 'intermediate' UNION ALL
    SELECT 5, 1, 'Complete Speaking Test + Listening Supporting Details', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c9', 'intermediate' UNION ALL
    SELECT 5, 2, 'Complete Writing Test + Speaking Storytelling & Predictions', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c10', 'intermediate' UNION ALL
    SELECT 6, 1, 'Complete Listening Test + Reading Part 3 (Key Ideas)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c11', 'intermediate' UNION ALL
    SELECT 6, 2, 'Complete Reading Test + Writing Transitions & Collocations', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c12', 'intermediate' UNION ALL
    SELECT 7, 1, 'Complete Speaking Test + Listening Speaker Attitude', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c13', 'intermediate' UNION ALL
    SELECT 7, 2, 'Complete Writing Test + Speaking Persuasion & Comparison', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c14', 'intermediate' UNION ALL
    SELECT 8, 1, 'Mock Test 1 — Full-Length, All 4 Sections', 'resources/mock_tests/celpip_full_mock_a.php', 'intermediate' UNION ALL
    SELECT 8, 2, 'Mock 1 Review & Band Estimate', 'courses/CELPIP_Gen/lessons/week8_mock1_review.php', 'intermediate' UNION ALL
    SELECT 9, 1, 'Complete Listening Test + Reading Part 4 (Viewpoints)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c17', 'intermediate' UNION ALL
    SELECT 9, 2, 'Complete Reading Test + Writing Self-Editing Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c18', 'intermediate' UNION ALL
    SELECT 10, 1, 'Complete Speaking Test + Listening Fast-Audio Drills', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c19', 'intermediate' UNION ALL
    SELECT 10, 2, 'Complete Writing Test + Speaking Full-Timed-Task Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c20', 'intermediate' UNION ALL
    SELECT 11, 1, 'Mock Test 2 — Final Assessment', 'resources/mock_tests/celpip_full_mock_b.php', 'intermediate' UNION ALL
    SELECT 11, 2, 'Final Performance Review', 'courses/CELPIP_Gen/lessons/week11_final_review.php', 'intermediate' UNION ALL
    SELECT 12, 1, 'Final Speaking Simulation & Listening Sprints', 'courses/CELPIP_Gen/lessons/week12_final_speaking_listening_sprints.php', 'intermediate' UNION ALL
    SELECT 12, 2, 'Test-Day Coaching', 'courses/CELPIP_Gen/lessons/week12_test_day_coaching.php', 'intermediate'
) x ON x.module_order = m.module_order
WHERE c.folder_name = 'CELPIP_Gen_3Mo';

-- Rollback (destructive — removes all of this course's modules/lessons):
-- DELETE l FROM lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id WHERE c.folder_name='CELPIP_Gen_3Mo';
-- DELETE m FROM modules m JOIN courses c ON c.id=m.course_id WHERE c.folder_name='CELPIP_Gen_3Mo';
