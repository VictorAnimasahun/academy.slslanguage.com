-- ============================================================
-- Migration 087 — Replace IELTS_Aca_2Mo's placeholder curriculum with the
-- real 16-class schedule (instructor-provided, 2026-09-17)
--
-- The course already has exactly 16 lessons across 2 modules (module 1 =
-- lesson_order 1-8 = C1-C8, module 2 = lesson_order 1-8 = C9-C16) from
-- migration 077's generic placeholder seed — this UPDATEs those rows in
-- place rather than inserting new ones, so enrollments/progress tracking
-- tied to the existing lesson ids are untouched.
--
-- Content-safety note (found while writing this): four of the referenced
-- practice tests could be linked via file_path:
--   - ielts_speaking_002/003/004.php — real content, no enrollment gate.
--   - ielts_reading_001.php — General Training content (explicitly labeled
--     "GT" in its own markup), used here as a deliberate, explicit
--     stand-in per instructor request 2026-09-17 ("free the reading
--     practice test... we will wire everything to the proper programs
--     later"). Its course_lock enrollment gate was removed the same day
--     (resources/practice_tests/ielts_reading_001.php) so students in
--     this course can actually reach it. Swap for a real Academic Reading
--     Test 1 once one exists.
-- Everything else was excluded on purpose:
--   - ielts_writing_t1/t2_001.php are explicitly labeled "General
--     Training" in their own markup — wrong content type for an Academic
--     course, and not yet freed the way Reading was.
--   - ielts_listening_001.php and ielts_speaking_001.php are real and
--     content-neutral (Listening/Speaking don't differ between Academic
--     and GT) but gate on require_course_enrollment([9, 10, 11]) — the
--     IELTS General Training course family's ids, not this course's. An
--     Academic-only student would hit a locked wall. Needs that gate
--     array widened to include this course's real id before linking is
--     safe; deliberately not guessed here.
--   - ielts_listening_002.php is orphaned (no auth/login gate at all,
--     unreachable from any nav) and ielts_reading_002-004.php /
--     ielts_listening_003-004.php / ielts_writing_t1_002-004.php /
--     ielts_writing_t2_002-004.php are all ~34-line placeholder stubs.
--   - No Academic-labeled Full Mock exists at all (all 4 existing Full
--     Mocks are explicitly "IELTS GT Mock Test" per their own file
--     headers and migration 015) — so C9/C16 stay unlinked.
-- Those lessons keep file_path NULL for now and just describe the day's
-- plan via `content` — matches the instructor's own "we'll expand it
-- later" framing.
-- ============================================================

-- ── Month 1 (module_order = 1), Classes 1-8 ────────────────────────────
UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Orientation & Diagnostic Assessment',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Orientation</h5><ul><li>IELTS Academic format, band scale, scoring</li></ul><h5>Diagnostic</h5><ul><li>Mini Diagnostic — Listening, Reading, Writing, Speaking samples</li><li>Scoring and band estimate</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 1;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 1 + Writing Task Overview',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 1 (full-length)</li><li>Answer review</li></ul><h5>Writing</h5><ul><li>Task 1 &amp; Task 2 overview — chart/graph/map/process types, essay types</li><li>Assessment criteria</li><li>Band 5 vs. Band 7+ sample analysis</li></ul><p class="text-muted small mt-2"><em>Listening Test 1 link pending — ielts_listening_001.php exists but is currently gated to the IELTS General Training course family; needs that access widened first.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 2;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 1 + Trend Vocabulary & Paraphrasing',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_reading_001.php',
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 1 (full-length, 3 passages)</li><li>Answer review</li></ul><h5>Writing</h5><ul><li>Trend vocabulary and sentence structures</li><li>Paraphrasing questions and thesis statements</li><li>Exercises on both</li></ul><p class="text-muted small mt-2"><em>Temporarily using the General Training version of Reading Test 1 (course lock removed 2026-09-17) — swap for the real Academic version once it exists.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 3;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 1 + Comparative Structures & TEE',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_002.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 1 (Parts 1-3, recorded)</li><li>Immediate feedback</li></ul><h5>Writing</h5><ul><li>Comparative/superlative structures for Task 1</li><li>TEE body paragraph structure</li><li>Exercises on both</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 4;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 1 (Timed) + Listening Formats',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 1 — Task 1: 20 min timed, Task 2: 40 min timed</li><li>Self-assessment against band descriptors</li></ul><h5>Listening</h5><ul><li>Section formats and question types (Sections 1-4)</li><li>Common distractor patterns</li><li>Sample question set</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 1 exists on the platform yet — only a General Training version.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 5;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 2 + Map Description & Passive Voice',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 2</li><li>Answer review focused on weak section types</li></ul><h5>Writing</h5><ul><li>Map description phrases and passive voice</li><li>Strengthening weak arguments</li><li>Exercises on both</li></ul><p class="text-muted small mt-2"><em>No Listening Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 6;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 2 + Process Description & Passive Voice',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 2</li><li>Timing analysis</li></ul><h5>Writing</h5><ul><li>Process description phrases and passive voice</li><li>Writing balanced conclusions</li><li>Exercises on both</li></ul><p class="text-muted small mt-2"><em>No Academic Reading Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 7;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 2 + Task 1 Report Structuring',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_003.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 2 (Parts 1-3, recorded)</li><li>Feedback on lexical resource and grammar range</li></ul><h5>Writing</h5><ul><li>Structuring Task 1 reports — Introduction, Overview, Key Features, Details</li><li>Academic tone and grammar correction</li><li>Exercises on both</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 1 AND l.lesson_order = 8;

-- ── Month 2 (module_order = 2), Classes 9-16 ───────────────────────────
UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock Test 1 — Full Timed (L, R, W, S)',
    l.duration_minutes = 240,
    l.file_path = NULL,
    l.content = '<p><strong>Mock Test 1</strong> — Listening, Reading, Writing (full-length, timed, back-to-back) and Speaking (live or recorded). Nothing else scheduled this class.</p><p class="text-muted small mt-2"><em>No Academic Full Mock exists on the platform yet — all 4 existing Full Mocks are General Training. Needs a real Academic mock built before this can link out; run as an instructor-administered session until then.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 1;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock 1 Review + Reading Time-Management',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Review</h5><ul><li>Mock 1 band report</li><li>Writing corrections</li><li>Speaking oral feedback</li></ul><h5>Reading</h5><ul><li>Time-management strategy — skimming, scanning, question order</li><li>Timed practice passage</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 2;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 2 (Timed) + Speaking Part 2 Drilling',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 2 (full timed Task 1 &amp; Task 2)</li><li>Self-assessment</li></ul><h5>Speaking</h5><ul><li>Part 2 cue-card drilling (1-min prep, 2-min talk)</li><li>Feedback on structure and fluency</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 2 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 3;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Listening Test 3 + Speaking Part 3 Drilling',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Listening</h5><ul><li>Complete Listening Test 3</li><li>Answer review</li></ul><h5>Speaking</h5><ul><li>Part 3 discussion drilling — abstract questions, extended answers</li><li>Feedback on developing opinions</li></ul><p class="text-muted small mt-2"><em>No Listening Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 4;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Reading Test 3 + Listening Fast-Audio Drills',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Reading</h5><ul><li>Complete Reading Test 3</li><li>Final timing check</li></ul><h5>Listening</h5><ul><li>Fast-audio drills and note-taking technique for Sections 3-4</li></ul><p class="text-muted small mt-2"><em>No Academic Reading Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 5;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Speaking Test 3 + Complex Passage Strategy',
    l.duration_minutes = 90,
    l.file_path = 'resources/practice_tests/ielts_speaking_004.php',
    l.content = '<h5>Speaking</h5><ul><li>Complete Speaking Test 3 (full 3-part simulation)</li><li>Detailed feedback across all 4 criteria</li></ul><h5>Reading</h5><ul><li>Complex passage strategy — long sentences, academic vocabulary, implied meaning</li><li>Timed practice</li></ul>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 6;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Writing Test 3 (Timed) + Final Self-Assessment',
    l.duration_minutes = 90,
    l.file_path = NULL,
    l.content = '<h5>Writing</h5><ul><li>Complete Writing Test 3 — Task 1: 20 min timed, Task 2: 40 min timed</li><li>Self-assessment against band descriptors</li><li>Common error review across all 3 writing tests</li><li>Revision exercise</li></ul><p class="text-muted small mt-2"><em>No Academic Writing Test 3 built yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 7;

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Mock Test 2 (Final) — Full Timed (L, R, W, S)',
    l.duration_minutes = 240,
    l.file_path = NULL,
    l.content = '<p><strong>Mock Test 2 (Final Assessment)</strong> — Listening, Reading, Writing, Speaking (full-length, timed). Nothing else scheduled this class.</p><h5>Score Report</h5><ul><li>Mock 2 results vs. Mock 1</li><li>Test-day coaching</li></ul><p class="text-muted small mt-2"><em>No Academic Full Mock exists on the platform yet.</em></p>'
WHERE c.folder_name = 'IELTS_Aca_2Mo' AND m.module_order = 2 AND l.lesson_order = 8;

-- ── Verify ──────────────────────────────────────────────────────────────
-- SELECT m.module_order, l.lesson_order, l.title, l.file_path, l.duration_minutes
-- FROM lessons l JOIN modules m ON m.id = l.module_id JOIN courses c ON c.id = m.course_id
-- WHERE c.folder_name = 'IELTS_Aca_2Mo' ORDER BY m.module_order, l.lesson_order;
