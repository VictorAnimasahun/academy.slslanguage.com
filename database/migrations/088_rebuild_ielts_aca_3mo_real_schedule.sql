-- ============================================================
-- Migration 088 — Replace IELTS_Aca_3Mo's placeholder curriculum with the
-- real 24-class schedule (instructor-provided, 2026-09-17), extending
-- migration 087's 2-month build-out with a 4th practice test round and a
-- 3rd dedicated mock day.
--
-- The course already has exactly 24 lessons across 3 modules (module 1 =
-- lesson_order 1-8 = C1-C8, module 2 = lesson_order 1-8 = C9-C16, module 3
-- = lesson_order 1-8 = C17-C24) from migration 077's generic placeholder
-- seed — this UPDATEs those rows in place, same convention as 087.
--
-- Content-safety note — same policy as migration 087, extended for the 4th
-- round. Five practice tests link out via file_path:
--   - ielts_listening_001.php (Class 2) — content-neutral, permanent fit.
--   - ielts_reading_001.php (Class 3) — General Training content used as
--     an explicit, deliberate stand-in per instructor request 2026-09-17,
--     same as the 2-month course's Class 3. Swap once a real Academic
--     Reading test exists.
--   - ielts_speaking_002/003/004.php (Classes 4, 8, 14) — real, content-
--     neutral, no enrollment gate.
--   - ielts_speaking_001.php (Class 20, "Speaking Test 4") — the 4th
--     Speaking slot this longer course needs; only 4 Speaking practice
--     tests exist in total, so this is the one left over. Content-
--     neutral, permanent fit, not a stand-in.
-- All three of ielts_reading_001.php / ielts_listening_001.php /
-- ielts_speaking_001.php had their require_course_enrollment() arrays
-- widened to include course 17 (IELTS_Aca_3Mo's real id) the same day —
-- see those files directly for the comments.
--
-- Nothing else links out: Listening/Reading/Writing Tests 2-4 don't exist
-- yet (only Test 1 of each is real; Reading/Listening/Writing 002-004 are
-- ~34-line stubs), Writing Test 1 is General Training content not yet
-- freed the way Reading was, and no Academic Full Mock exists at all (all
-- 4 existing Full Mocks are General Training). Those classes keep
-- file_path NULL and just describe the day's plan via `content`.
-- ============================================================

-- ── Month 1 (module_order = 1), Classes 1-8 ────────────────────────────
UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Orientation & Diagnostic Assessment',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Orientation</h5><ul><li>IELTS Academic format, band scale, scoring</li></ul><h5>Diagnostic</h5><ul><li>Mini Diagnostic — Listening, Reading, Writing, Speaking samples</li><li>Scoring and band estimate</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 1 + Writing Task Overview',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_listening_001.php',
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 1 (full-length)</li><li>Answer review</li></ul><h5>Writing</h5><ul><li>Task 1 &amp; Task 2 overview</li><li>Assessment criteria</li><li>Band 5 vs. Band 7+ sample analysis</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 1 + Trend Vocabulary & Paraphrasing',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_reading_001.php',
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 1 (full-length, 3 passages)</li><li>Answer review</li></ul><h5>Writing</h5><ul><li>Trend vocabulary and sentence structures</li><li>Paraphrasing questions and thesis statements</li></ul><p class="text-muted small mt-2"><em>Temporarily using the General Training version of Reading Test 1 — swap for the real Academic version once it exists.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 3;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 1 + Comparative Structures & TEE',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_002.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 1 (Parts 1-3, recorded)</li><li>Immediate feedback</li></ul><h5>Writing</h5><ul><li>Comparative/superlative structures for Task 1</li><li>TEE body paragraph structure</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 4;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 1 (Timed) + Listening Formats',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 1 — Task 1: 20 min timed, Task 2: 40 min timed</li><li>Self-assessment against band descriptors</li></ul><h5>Listening</h5><ul><li>Section formats and question types (Sections 1-4)</li><li>Distractor patterns</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 1 exists on the platform yet — only a General Training version.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 5;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 2 + Map Description & Passive Voice',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 2</li><li>Answer review focused on weak section types</li></ul><h5>Writing</h5><ul><li>Map description phrases and passive voice</li><li>Strengthening weak arguments</li></ul><p class="text-muted small mt-2"><em>No Listening Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 6;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 2 + Process Description & Passive Voice',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 2</li><li>Timing analysis</li></ul><h5>Writing</h5><ul><li>Process description phrases and passive voice</li><li>Writing balanced conclusions</li></ul><p class="text-muted small mt-2"><em>No Academic Reading Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 7;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 2 + Task 1 Report Structuring',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_003.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 2 (Parts 1-3, recorded)</li><li>Feedback on lexical resource and grammar range</li></ul><h5>Writing</h5><ul><li>Structuring Task 1 reports — Introduction, Overview, Key Features, Details</li><li>Academic tone and grammar correction</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 1 AND l.lesson_order = 8;

-- ── Month 2 (module_order = 2), Classes 9-16 ───────────────────────────
UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock Test 1 — Full Timed (L, R, W, S)',
    l.duration_minutes = 240,
    l.file_path = NULL,
    l.content = '<p><strong>Mock Test 1</strong> — Listening, Reading, Writing, Speaking (full-length, timed). Nothing else scheduled this class.</p><p class="text-muted small mt-2"><em>No Academic Full Mock exists on the platform yet — all 4 existing Full Mocks are General Training. Run as an instructor-administered session until a real one is built.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 1;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock 1 Review + Reading Time-Management',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Review</h5><ul><li>Mock 1 band report</li><li>Writing corrections</li><li>Speaking oral feedback</li></ul><h5>Reading</h5><ul><li>Time-management strategy — skimming, scanning, question order</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 2;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 2 (Timed) + Speaking Part 2 Drilling',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 2 (full timed Task 1 &amp; Task 2)</li><li>Self-assessment</li></ul><h5>Speaking</h5><ul><li>Part 2 cue-card drilling</li><li>Feedback on structure and fluency</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 3;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 3 + Speaking Part 3 Drilling',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 3</li><li>Answer review</li></ul><h5>Speaking</h5><ul><li>Part 3 discussion drilling — abstract questions, extended answers</li><li>Feedback on developing opinions</li></ul><p class="text-muted small mt-2"><em>No Listening Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 4;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 3 + Listening Fast-Audio Drills',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 3</li><li>Final timing check</li></ul><h5>Listening</h5><ul><li>Fast-audio drills and note-taking technique for Sections 3-4</li></ul><p class="text-muted small mt-2"><em>No Academic Reading Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 5;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 3 + Complex Passage Strategy',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_004.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 3 (full 3-part simulation)</li><li>Detailed feedback across all 4 criteria</li></ul><h5>Reading</h5><ul><li>Complex passage strategy — long sentences, implied meaning</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 6;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 3 (Timed) + Error Review',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 3 — Task 1: 20 min timed, Task 2: 40 min timed</li></ul><h5>Writing</h5><ul><li>Self-assessment, common error review across all writing tests so far</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 7;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock Test 2 — Full Timed (L, R, W, S)',
    l.duration_minutes = 240,
    l.file_path = NULL,
    l.content = '<p><strong>Mock Test 2</strong> — Listening, Reading, Writing, Speaking (full-length, timed). Nothing else scheduled this class.</p><p class="text-muted small mt-2"><em>No Academic Full Mock exists on the platform yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 2 AND l.lesson_order = 8;

-- ── Month 3 (module_order = 3), Classes 17-24 ──────────────────────────
UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock 2 Review + Strategy Adjustments',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Review</h5><ul><li>Mock 2 band report vs. Mock 1</li><li>Corrections</li></ul><h5>Strategy</h5><ul><li>Adjustments based on remaining weak areas</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 1;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 4 + Advanced Listening',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 4</li><li>Answer review</li></ul><h5>Advanced Listening</h5><ul><li>High-speed audio</li><li>Unfamiliar accents</li></ul><p class="text-muted small mt-2"><em>No Listening Test 4 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 2;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 4 + Advanced Reading',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 4</li><li>Answer review</li></ul><h5>Advanced Reading</h5><ul><li>Dense academic passages</li><li>Matching sentence endings</li></ul><p class="text-muted small mt-2"><em>No Academic Reading Test 4 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 3;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 4 + Advanced Speaking',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_001.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 4 (full 3-part simulation)</li><li>Feedback</li></ul><h5>Advanced Speaking</h5><ul><li>Extending answers, idiomatic language</li><li>Natural hesitation devices</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 4;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 4 + Final Writing Polish',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 4 — Task 1: 20 min timed, Task 2: 40 min timed</li></ul><h5>Final Writing Polish</h5><ul><li>Sentence variety, cohesion</li><li>Self-editing under time pressure</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 4 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 5;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Consolidation — Targeted Weak-Area Drilling',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Consolidation</h5><ul><li>Targeted drilling on each student''s weakest section across all 4 test rounds so far</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 6;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock Test 3 (Final Assessment) — Full Timed (L, R, W, S)',
    l.duration_minutes = 240,
    l.file_path = NULL,
    l.content = '<p><strong>Mock Test 3 (Final Assessment)</strong> — Listening, Reading, Writing, Speaking (full-length, timed). Nothing else scheduled this class.</p><p class="text-muted small mt-2"><em>No Academic Full Mock exists on the platform yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 7;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Final Review + Test-Day Coaching',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Final Review</h5><ul><li>Score report, comparison across all 3 mocks</li></ul><h5>Test-Day Coaching</h5><ul><li>Timing strategy</li><li>Exam-day checklist</li><li>Nerves management</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_3Mo' AND m.module_order = 3 AND l.lesson_order = 8;

-- ── Verify ──────────────────────────────────────────────────────────────
-- SELECT m.module_order, l.lesson_order, l.title, l.file_path, l.duration_minutes
-- FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
-- WHERE c.folder_name = 'IELTS_Aca_3Mo' ORDER BY m.module_order, l.lesson_order;
