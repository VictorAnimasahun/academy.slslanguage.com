-- ============================================================
-- Migration 075 — Register CELPIP Full Mock A/B in mock_exam_sections
--
-- Fixes: "No sections configured for this mock yet." shown when opening
-- CELPIP_FULL_MOCK_A/B from the general Resources > Mock Tests catalog
-- (resources/mock_tests/index.php -> take.php). Migration 073 created a
-- `mock_exams` catalog row for each (following the IELTS_ACA_DIAGNOSTIC/
-- IELTS_GT_DIAGNOSTIC precedent for the catalog card itself), but that
-- precedent deliberately does NOT register mock_exam_sections, since
-- diagnostics are only ever reached via their course lesson, never
-- browsed to directly. CELPIP_FULL_MOCK_A/B turned out to need both:
-- reached via course 13's lessons (already working, migration 074) AND
-- apparently browsed to directly via the general catalog -- so unlike
-- the diagnostics, they need this table populated too, matching the
-- IELTS_FULL_MOCK_001-004 pattern exactly (3 rows: Listening, Reading,
-- Writing_Task1 -- Speaking is deliberately absent from this table for
-- every full mock, IELTS included, since it's instructor-scheduled
-- outside the timed flow).
--
-- take.php's "Start Full Mock Test" button resolves the first section's
-- URL as strtolower(mock_code) . '.php' for section_type='Listening' --
-- i.e. exactly 'celpip_full_mock_a.php' / 'celpip_full_mock_b.php',
-- which already exist (migration 074) and already ignore the `?code=`
-- query param they're invoked with (they do their own hardcoded lookup,
-- same as ielts_full_mock_003.php does) -- so no PHP changes needed here,
-- only this data.
--
-- Idempotent — safe to re-run.
-- ============================================================

INSERT INTO mock_exam_sections (mock_code, section_type, test_code, section_order)
SELECT d.mock_code, d.section_type, d.test_code, d.section_order
FROM (
  SELECT 'CELPIP_FULL_MOCK_A' mock_code, 'Listening' section_type, 'CELPIP_FMA_L' test_code, 1 section_order
  UNION ALL SELECT 'CELPIP_FULL_MOCK_A', 'Reading',        'CELPIP_FMA_R', 2
  UNION ALL SELECT 'CELPIP_FULL_MOCK_A', 'Writing_Task1',  'CELPIP_FMA_W', 3
  UNION ALL SELECT 'CELPIP_FULL_MOCK_B', 'Listening',       'CELPIP_FMB_L', 1
  UNION ALL SELECT 'CELPIP_FULL_MOCK_B', 'Reading',         'CELPIP_FMB_R', 2
  UNION ALL SELECT 'CELPIP_FULL_MOCK_B', 'Writing_Task1',   'CELPIP_FMB_W', 3
) d
WHERE NOT EXISTS (
  SELECT 1 FROM mock_exam_sections mes
  WHERE mes.mock_code = d.mock_code AND mes.section_type = d.section_type
);
