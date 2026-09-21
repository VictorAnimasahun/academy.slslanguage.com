-- ============================================================
-- Migration 111 -- Wire IELTS Listening Test 2 into Class 6 (Academic 2Mo + 3Mo)
--
-- Class 6 = "Listening Test 2 + Map Description & Passive Voice". The test page
-- resources/practice_tests/ielts_listening_002.php was rebuilt 2026-09-21 on the
-- Listening Test 1 engine (audio, exam lockdown, saves attempts, course gate).
--
-- PREREQUISITE: 020_seed_ielts_listening_pt2.sql (question bank + answer key for
-- IELTS_PT_L_002) must have been run first. It is idempotent -- safe to re-run.
-- Matches the class by title (no ids). Idempotent. Run on LOCAL first, then LIVE.
-- ============================================================

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.file_path = 'resources/practice_tests/ielts_listening_002.php',
    l.content   = REPLACE(l.content, '<p class="text-muted small mt-2"><em>No Listening Test 2 built yet.</em></p>', '')
WHERE c.folder_name IN ('IELTS_Aca_2Mo', 'IELTS_Aca_3Mo')
  AND l.title = 'Listening Test 2 + Map Description & Passive Voice';

-- Verify: SELECT c.folder_name, l.file_path FROM lessons l JOIN modules m ON m.id=l.module_id
--         JOIN courses c ON c.id=m.course_id WHERE l.title LIKE 'Listening Test 2 + Map%';   -- 2 rows, file_path set
-- Also:   SELECT COUNT(*) FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code='IELTS_PT_L_002';  -- 40
-- Rollback: UPDATE lessons SET file_path=NULL WHERE title='Listening Test 2 + Map Description & Passive Voice';
