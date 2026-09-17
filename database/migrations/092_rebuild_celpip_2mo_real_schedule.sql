-- Migration 092: rebuild CELPIP_Gen_2Mo (course 13) to the instructor's
-- redesigned 16-class / 8-week schedule (3 tests per section + 2 mocks).
-- Replaces the old generic 2-module/8-lesson structure (Month 1/Month 2,
-- mostly pointing at generic topic pages) with real per-class files
-- (class1.php..class16.php, same pattern as IELTS_Aca_2Mo/3Mo) grouped
-- into 8 weekly modules. Old modules/lessons for course 13 are deleted
-- and replaced — this course had no student-tracked progress worth
-- preserving beyond what's already in lesson_progress by lesson id
-- (acceptable per instructor: content build is still in active flux).

DELETE FROM lessons WHERE module_id IN (SELECT id FROM (SELECT id FROM modules WHERE course_id = 13) m2);
DELETE FROM modules WHERE course_id = 13;

INSERT INTO modules (course_id, module_title, module_order, min_tier) VALUES
(13, 'Week 1 — Orientation + Diagnostic',      1, 'beginner'),
(13, 'Week 2 — Reading + Speaking Focus',      2, 'beginner'),
(13, 'Week 3 — Writing + Listening Focus',     3, 'beginner'),
(13, 'Week 4 — Reading + Speaking Focus',      4, 'beginner'),
(13, 'Week 5 — Mock Exam + Review',            5, 'beginner'),
(13, 'Week 6 — Writing + Listening Focus',     6, 'beginner'),
(13, 'Week 7 — Reading + Speaking Focus',      7, 'beginner'),
(13, 'Week 8 — Writing + Final Mock',          8, 'beginner');

SET @w1 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 1);
SET @w2 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 2);
SET @w3 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 3);
SET @w4 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 4);
SET @w5 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 5);
SET @w6 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 6);
SET @w7 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 7);
SET @w8 = (SELECT id FROM modules WHERE course_id = 13 AND module_order = 8);

INSERT INTO lessons (course_id, module_id, title, lesson_order, min_tier, file_path, icon, duration_minutes) VALUES
(13, @w1, 'Orientation + Diagnostic Assessment',                              1, 'beginner',     'courses/CELPIP_Gen_2Mo/class1.php',  'play-circle', 0),
(13, @w1, 'Complete Listening Test 1 + Writing: Letter Tone',                 2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class2.php',  'play-circle', 0),
(13, @w2, 'Complete Reading Test 1 + Writing: Argument Vocabulary',           1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class3.php',  'play-circle', 0),
(13, @w2, 'Complete Speaking Test 1 + Writing: Transitions',                  2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class4.php',  'play-circle', 0),
(13, @w3, 'Complete Writing Test 1 + Listening: Parts 1–2 Overview',          1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class5.php',  'play-circle', 0),
(13, @w3, 'Complete Listening Test 2 + Writing: Survey Structure',            2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class6.php',  'play-circle', 0),
(13, @w4, 'Complete Reading Test 2 + Writing: Planning Strategies',           1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class7.php',  'play-circle', 0),
(13, @w4, 'Complete Speaking Test 2 + Writing: Intros & Conclusions',         2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class8.php',  'play-circle', 0),
(13, @w5, 'Mock Test 1 — Full-Length, All 4 Sections',                        1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class9.php',  'play-circle', 0),
(13, @w5, 'Mock 1 Review & Band Estimate',                                    2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class10.php', 'play-circle', 0),
(13, @w6, 'Complete Writing Test 2 + Speaking: Tasks 5–8 Review',             1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class11.php', 'play-circle', 0),
(13, @w6, 'Complete Listening Test 3 + Speaking: Tasks 1–4 Review',           2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class12.php', 'play-circle', 0),
(13, @w7, 'Complete Reading Test 3 + Listening: Fast Audio Drills',           1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class13.php', 'play-circle', 0),
(13, @w7, 'Complete Speaking Test 3 + Reading: Complex Passage Strategy',     2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class14.php', 'play-circle', 0),
(13, @w8, 'Complete Writing Test 3 + Final Polish',                          1, 'intermediate', 'courses/CELPIP_Gen_2Mo/class15.php', 'play-circle', 0),
(13, @w8, 'Mock Test 2 (Final Assessment) — All 4 Sections',                  2, 'intermediate', 'courses/CELPIP_Gen_2Mo/class16.php', 'play-circle', 0);

-- Verify:
-- SELECT m.module_order, l.lesson_order, l.title, l.file_path FROM lessons l JOIN modules m ON m.id=l.module_id WHERE m.course_id=13 ORDER BY m.module_order, l.lesson_order;
