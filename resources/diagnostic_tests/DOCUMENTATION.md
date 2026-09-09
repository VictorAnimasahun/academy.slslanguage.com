# Diagnostic Tests — Subsystem Documentation

Parent: [`../DOCUMENTATION.md`](../DOCUMENTATION.md)

Placement/assessment tests used to gauge a new student's level before recommending a course tier.

## Entry point

`diagnostic_tests_home.php` — selection page.

## Test pages

| File | Purpose |
|---|---|
| `diagnostic_IELTS.php` | IELTS Academic diagnostic/placement test — redirect stub to `resources/mock_tests/ielts_aca_diagnostic.php` |
| `diagnostic_IELTS_GT.php` | IELTS General Training diagnostic/placement test — redirect stub to `resources/mock_tests/ielts_gt_diagnostic.php` (migration 068). Shares Listening + Writing engine with the Academic diagnostic; has its own Reading content |
| `diagnostic_basic_english.php` | General English placement test (for students not yet ready for exam-specific prep) |
| `diagnostic_celpip.php` | Authenticated entry point for the CELPIP mini mock test |

## Assets

`media/` subfolder holds any audio/image assets these tests need.

## Relationship to courses

Diagnostic results are intended to inform which course tier (`includes/tier_access.php` — beginner/intermediate/advanced/fluent) a student should start in, though the actual recommendation logic should be checked in `learning_dashboard.php` rather than assumed from this doc alone.
