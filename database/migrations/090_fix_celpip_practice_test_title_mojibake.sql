-- Migration 090: fix double-encoded en-dash mojibake in CELPIP practice test titles
-- Found during a CELPIP health-check sweep 2026-09-17. The en dash in titles like
-- "CELPIP Listening – Practice Test 1" was saved as UTF-8 bytes of "–" misread as
-- Windows-1252 then re-encoded to UTF-8, producing the 3-character sequence "â€œ".
-- Currently dead/unused by any student-facing page (getBreadcrumb() in
-- resources/practice_tests/functions.php is defined but never called), fixed now
-- as a landmine-removal, not an active bug.

UPDATE tests
SET title = REPLACE(
    title,
    CONVERT(0xC3A2E282ACE2809C USING utf8mb4),
    CONVERT(0xE28093 USING utf8mb4)
)
WHERE code LIKE 'CELPIP\_PT\_%' ESCAPE '\\'
  AND title LIKE CONCAT('%', CONVERT(0xC3A2E282ACE2809C USING utf8mb4), '%');

-- Verify: should return 0 rows after running
-- SELECT id, code, title, HEX(title) FROM tests WHERE code LIKE 'CELPIP\_PT\_%' ESCAPE '\\' AND title LIKE '%\xC3\xA2%';
