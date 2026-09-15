-- ============================================================
-- Migration 085 — Wire the real Listening test into CELPIP 2-Month (course 13)
--
-- Quick swap, not a full redesign: course 13 (CELPIP_Gen_2Mo) still uses the
-- old month1_*/month2_* content untouched by this session's 3-Month rebuild
-- (see migrations 081-084, [[project_celpip_3mo_rebuild]]). The user wants
-- the real Listening test now available here too, without otherwise
-- restructuring course 13. Replaces the Month 1 "Listening — News Item &
-- Conversation" lesson's file_path (previously month1_listening.php, a
-- teaching-only lesson) with the real, working celpip_listening_001.php
-- built this session — same file already wired into course 14's Week 2.
--
-- Resolved via folder_name + module_order + lesson_order (not a hardcoded
-- lesson id), matching the live-safety pattern established in migrations
-- 078 and 083 — course 13's lesson ids may differ across environments.
--
-- IDEMPOTENT: safe to re-run.
-- ============================================================

UPDATE lessons l
JOIN modules m ON m.id = l.module_id
JOIN courses c ON c.id = m.course_id
SET l.title = 'Complete Listening Test (CELPIP Practice Test 1)',
    l.file_path = 'courses/CELPIP_Gen/lessons/celpip_listening_001.php'
WHERE c.folder_name = 'CELPIP_Gen_2Mo'
  AND m.module_order = 1
  AND l.lesson_order = 2;

-- Rollback:
-- UPDATE lessons l JOIN modules m ON m.id=l.module_id JOIN courses c ON c.id=m.course_id
-- SET l.title = 'Listening — News Item & Conversation', l.file_path = 'courses/CELPIP_Gen/lessons/month1_listening.php'
-- WHERE c.folder_name = 'CELPIP_Gen_2Mo' AND m.module_order = 1 AND l.lesson_order = 2;
