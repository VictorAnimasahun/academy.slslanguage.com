-- Migration 091: make CELPIP_Gen_3Mo (course 14) class titles explicitly name
-- which Practice Test number each class's complete test is, and flag classes
-- whose test isn't built yet ("Coming Soon") — mirrors the clarity already
-- built into the IELTS Academic 2Mo/3Mo class titles. Previously titles like
-- "Complete Listening Test + Reading Part 1" gave no indication of the PT
-- number or whether the test link is even real; ground truth pulled directly
-- from courses/CELPIP_Gen/lessons/class_day.php's $SLOTS array (c3-c20),
-- not just the pre-existing DB titles, since those disagreed in places
-- (e.g. Listening only has ONE real test — PT1 — used at Class 3; Classes
-- 7/11/17 are NOT repeats, they're "not built yet" placeholders).

UPDATE lessons SET title = 'Complete Listening Test (Practice Test 1) + Reading Part 1 Lesson (Correspondence)' WHERE id = 470;
UPDATE lessons SET title = 'Complete Reading Test (Practice Test 1) + Writing Task 1 & 2 Structure Lesson' WHERE id = 471;
UPDATE lessons SET title = 'Complete Speaking Test (Practice Test 1) + Listening Inference & Signal Words Lesson' WHERE id = 472;
UPDATE lessons SET title = 'Complete Writing Test (Practice Test 1) + Speaking Clarity & Structure Lesson' WHERE id = 473;
UPDATE lessons SET title = 'Listening Test (Coming Soon) + Reading Part 2 Lesson (Schedules & Diagrams)' WHERE id = 474;
UPDATE lessons SET title = 'Complete Reading Test (Practice Test 2) + Writing Argument Vocabulary Lesson' WHERE id = 475;
UPDATE lessons SET title = 'Complete Speaking Test (Practice Test 2) + Listening Supporting Details Lesson' WHERE id = 476;
UPDATE lessons SET title = 'Complete Writing Test (Practice Test 2) + Speaking Storytelling & Predictions Lesson' WHERE id = 477;
UPDATE lessons SET title = 'Listening Test (Coming Soon) + Reading Part 3 Lesson (Key Ideas)' WHERE id = 478;
UPDATE lessons SET title = 'Complete Reading Test (Practice Test 3) + Writing Transitions & Collocations Lesson' WHERE id = 479;
UPDATE lessons SET title = 'Complete Speaking Test (Practice Test 3) + Listening Speaker Attitude Lesson' WHERE id = 480;
UPDATE lessons SET title = 'Complete Writing Test (Practice Test 3) + Speaking Persuasion & Comparison Lesson' WHERE id = 481;
UPDATE lessons SET title = 'Listening Test (Coming Soon) + Reading Part 4 Lesson (Viewpoints)' WHERE id = 484;
UPDATE lessons SET title = 'Reading Test (Practice Test 4 — Coming Soon) + Writing Self-Editing Strategy Lesson' WHERE id = 485;
UPDATE lessons SET title = 'Speaking Test (Practice Test 4 — Coming Soon) + Listening Fast-Audio Drills Lesson' WHERE id = 486;
UPDATE lessons SET title = 'Writing Test (Practice Test 4 — Coming Soon) + Speaking Full-Timed-Task Strategy Lesson' WHERE id = 487;

-- Verify:
-- SELECT l.id, m.module_order, l.lesson_order, l.title FROM lessons l JOIN modules m ON m.id = l.module_id WHERE m.course_id = 14 AND m.module_order BETWEEN 2 AND 10 ORDER BY m.module_order, l.lesson_order;
