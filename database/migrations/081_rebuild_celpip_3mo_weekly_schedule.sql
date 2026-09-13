-- Migration 081: Rebuild CELPIP General Masterclass — 3 Months (course_id=14)
-- as an independent 24-class / 12-week schedule, decoupled from the shared
-- month1_*/month2_* content used by courses 12 (1-Month) and 13 (2-Month).
--
-- Each Listening/Reading/Writing/Speaking skill gets 4 complete practice-test
-- sittings via 4 paired checkpoint classes (Weeks 4, 6, 9, 10), in addition to
-- a Week 1 diagnostic and 2 full mocks (Weeks 8, 11). Real, already-built
-- practice tests are wired in directly for checkpoints 1-3 (Reading/Writing/
-- Speaking) and both mocks; Listening (no real practice tests exist yet) and
-- checkpoint 4's Reading/Writing/Speaking (only 3 real tests exist) render as
-- honest "not built yet" placeholders via the shared checkpoint_hub.php page.
--
-- Part 1: delete course 14's old 3 modules / 24 lessons (done in a prior
-- session step; the 12 new empty modules, IDs 44-55, already exist). This
-- migration only adds the missing lesson rows.

INSERT INTO lessons (course_id, module_id, title, file_path, lesson_order, min_tier) VALUES
-- Week 1 — Orientation + Diagnostics (module 44)
(14, 44, 'Foundational English Assessment', 'courses/CELPIP_Gen/lessons/week1_foundational_assessment.php', 1, 'beginner'),
(14, 44, 'Mini Diagnostic — All 4 Skills', 'courses/CELPIP_intro/celpip_mini_mock.php', 2, 'beginner'),

-- Week 2 — Core Teaching (module 45)
(14, 45, 'Listening & Speaking Overview', 'courses/CELPIP_Gen/lessons/week2_listening_speaking_overview.php', 1, 'intermediate'),
(14, 45, 'Reading & Writing Structure', 'courses/CELPIP_Gen/lessons/week2_reading_writing_structure.php', 2, 'intermediate'),

-- Week 3 — Core Teaching (module 46)
(14, 46, 'Listening & Speaking, Deeper', 'courses/CELPIP_Gen/lessons/week3_listening_speaking_deeper.php', 1, 'intermediate'),
(14, 46, 'Reading & Writing: Argument & Variety', 'courses/CELPIP_Gen/lessons/week3_reading_writing_vocab.php', 2, 'intermediate'),

-- Week 4 — Checkpoint 1 (module 47)
(14, 47, 'Checkpoint 1 — Complete Listening + Speaking Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c7', 1, 'intermediate'),
(14, 47, 'Checkpoint 1 — Complete Reading + Writing Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c8', 2, 'intermediate'),

-- Week 5 — Targeted Correction (module 48)
(14, 48, 'Listening/Speaking Weak-Point Drilling', 'courses/CELPIP_Gen/lessons/week5_listening_speaking_drilling.php', 1, 'intermediate'),
(14, 48, 'Reading/Writing Weak-Point Drilling', 'courses/CELPIP_Gen/lessons/week5_reading_writing_drilling.php', 2, 'intermediate'),

-- Week 6 — Checkpoint 2 (module 49)
(14, 49, 'Checkpoint 2 — Complete Listening + Speaking Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c11', 1, 'intermediate'),
(14, 49, 'Checkpoint 2 — Complete Reading + Writing Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c12', 2, 'intermediate'),

-- Week 7 — Strategy Refinement, pre-Mock 1 (module 50)
(14, 50, 'Listening/Speaking Timing & Strategy', 'courses/CELPIP_Gen/lessons/week7_listening_speaking_strategy.php', 1, 'intermediate'),
(14, 50, 'Reading/Writing Timing & Strategy', 'courses/CELPIP_Gen/lessons/week7_reading_writing_strategy.php', 2, 'intermediate'),

-- Week 8 — Mock 1 (module 51)
(14, 51, 'Mock Test 1 — Full-Length, All 4 Sections', 'resources/mock_tests/celpip_full_mock_a.php', 1, 'intermediate'),
(14, 51, 'Mock 1 Review & Band Estimate', 'courses/CELPIP_Gen/lessons/week8_mock1_review.php', 2, 'intermediate'),

-- Week 9 — Checkpoint 3 (module 52)
(14, 52, 'Checkpoint 3 — Complete Listening + Speaking Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c17', 1, 'intermediate'),
(14, 52, 'Checkpoint 3 — Complete Reading + Writing Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c18', 2, 'intermediate'),

-- Week 10 — Checkpoint 4 (module 53)
(14, 53, 'Checkpoint 4 — Complete Listening + Speaking Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c19', 1, 'intermediate'),
(14, 53, 'Checkpoint 4 — Complete Reading + Writing Test', 'courses/CELPIP_Gen/lessons/checkpoint_hub.php?slot=c20', 2, 'intermediate'),

-- Week 11 — Mock 2 (module 54)
(14, 54, 'Mock Test 2 — Final Assessment', 'resources/mock_tests/celpip_full_mock_b.php', 1, 'intermediate'),
(14, 54, 'Final Performance Review', 'courses/CELPIP_Gen/lessons/week11_final_review.php', 2, 'intermediate'),

-- Week 12 — Final Prep (module 55)
(14, 55, 'Final Speaking Simulation & Listening Sprints', 'courses/CELPIP_Gen/lessons/week12_final_speaking_listening_sprints.php', 1, 'intermediate'),
(14, 55, 'Test-Day Coaching', 'courses/CELPIP_Gen/lessons/week12_test_day_coaching.php', 2, 'intermediate');

-- Rollback:
-- DELETE FROM lessons WHERE course_id = 14 AND module_id BETWEEN 44 AND 55;
