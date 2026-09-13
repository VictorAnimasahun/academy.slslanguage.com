-- Migration 082: Redesign CELPIP 3-Month (course_id=14) classes C3-C14 and
-- C17-C20 from "paired complete tests" (checkpoint_hub.php) to "one complete
-- test + one focused micro-lesson on a different skill per class day"
-- (class_day.php). Supersedes part of migration 081 -- the weeks and
-- module structure (12 weeks, ids 44-55) stay the same; only the content
-- and module titles for the non-diagnostic/non-mock/non-final-week modules
-- change. C1, C2 (module 44), C15, C16 (module 51), C21, C22 (module 54),
-- C23, C24 (module 55) are untouched.
--
-- Real practice tests are still used wherever they exist: Reading/Writing/
-- Speaking each get 3 real sittings (celpip_reading/writing/speaking_00N)
-- across their first 3 occurrences, with the 4th (Weeks 9-10) an honest
-- "not built yet" placeholder via class_day.php, matching the still-real
-- resource gap (only 3 practice tests exist per skill). Listening has zero
-- real practice tests at all, so all 4 Listening slots (C3, C7, C11, C17)
-- are placeholders.

-- Module titles: drop "Checkpoint N" / "Targeted Correction" / "Strategy
-- Refinement" labels (concepts that no longer exist) in favor of a plain
-- skill-focus label, since every week 2-10 now has the same one-test/
-- one-lesson shape and alternates which skill pair is tested that week.
UPDATE modules SET module_title = 'Week 2 — Listening + Reading Focus' WHERE id = 45;
UPDATE modules SET module_title = 'Week 3 — Speaking + Writing Focus' WHERE id = 46;
UPDATE modules SET module_title = 'Week 4 — Listening + Reading Focus' WHERE id = 47;
UPDATE modules SET module_title = 'Week 5 — Speaking + Writing Focus' WHERE id = 48;
UPDATE modules SET module_title = 'Week 6 — Listening + Reading Focus' WHERE id = 49;
UPDATE modules SET module_title = 'Week 7 — Speaking + Writing Focus' WHERE id = 50;
UPDATE modules SET module_title = 'Week 9 — Listening + Reading Focus' WHERE id = 52;
UPDATE modules SET module_title = 'Week 10 — Speaking + Writing Focus' WHERE id = 53;

-- Replace the 16 lesson rows under those 8 modules.
DELETE FROM lessons WHERE course_id = 14 AND module_id IN (45, 46, 47, 48, 49, 50, 52, 53);

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, min_tier) VALUES
-- Week 2 (module 45)
(14, 45, 'Complete Listening Test + Reading Part 1 (Correspondence)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c3', 1, 'intermediate'),
(14, 45, 'Complete Reading Test + Writing Task 1 & 2 Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c4', 2, 'intermediate'),

-- Week 3 (module 46)
(14, 46, 'Complete Speaking Test + Listening Inference & Signal Words', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c5', 1, 'intermediate'),
(14, 46, 'Complete Writing Test + Speaking Clarity & Structure', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c6', 2, 'intermediate'),

-- Week 4 (module 47)
(14, 47, 'Complete Listening Test + Reading Part 2 (Schedules & Diagrams)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c7', 1, 'intermediate'),
(14, 47, 'Complete Reading Test + Writing Argument Vocabulary & Sentence Variety', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c8', 2, 'intermediate'),

-- Week 5 (module 48)
(14, 48, 'Complete Speaking Test + Listening Supporting Details', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c9', 1, 'intermediate'),
(14, 48, 'Complete Writing Test + Speaking Storytelling & Predictions', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c10', 2, 'intermediate'),

-- Week 6 (module 49)
(14, 49, 'Complete Listening Test + Reading Part 3 (Key Ideas)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c11', 1, 'intermediate'),
(14, 49, 'Complete Reading Test + Writing Transitions & Collocations', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c12', 2, 'intermediate'),

-- Week 7 (module 50)
(14, 50, 'Complete Speaking Test + Listening Speaker Attitude', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c13', 1, 'intermediate'),
(14, 50, 'Complete Writing Test + Speaking Persuasion & Comparison', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c14', 2, 'intermediate'),

-- Week 9 (module 52)
(14, 52, 'Complete Listening Test + Reading Part 4 (Viewpoints)', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c17', 1, 'intermediate'),
(14, 52, 'Complete Reading Test + Writing Self-Editing Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c18', 2, 'intermediate'),

-- Week 10 (module 53)
(14, 53, 'Complete Speaking Test + Listening Fast-Audio Drills', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c19', 1, 'intermediate'),
(14, 53, 'Complete Writing Test + Speaking Full-Timed-Task Strategy', 'courses/CELPIP_Gen/lessons/class_day.php?slot=c20', 2, 'intermediate');

-- Rollback:
-- DELETE FROM lessons WHERE course_id = 14 AND module_id IN (45,46,47,48,49,50,52,53);
-- UPDATE modules SET module_title = 'Week 2 — Core Teaching' WHERE id = 45;
-- UPDATE modules SET module_title = 'Week 3 — Core Teaching' WHERE id = 46;
-- UPDATE modules SET module_title = 'Week 4 — Checkpoint 1' WHERE id = 47;
-- UPDATE modules SET module_title = 'Week 5 — Targeted Correction' WHERE id = 48;
-- UPDATE modules SET module_title = 'Week 6 — Checkpoint 2' WHERE id = 49;
-- UPDATE modules SET module_title = 'Week 7 — Strategy Refinement (pre-Mock 1)' WHERE id = 50;
-- UPDATE modules SET module_title = 'Week 9 — Checkpoint 3' WHERE id = 52;
-- UPDATE modules SET module_title = 'Week 10 — Checkpoint 4' WHERE id = 53;
-- -- Then re-run migration 081's lesson INSERT block for these 8 modules from source control history.
