# Database — Subsystem Documentation

Parent: [`../documentation/SITE_MAP.md`](../documentation/SITE_MAP.md)

There is no ORM and no ad hoc schema changes — all schema and content changes go through numbered SQL files in `migrations/`, tracked against LOCAL and LIVE separately in `migrations/migration_log.md`. Always run a migration on LOCAL first, verify, then LIVE.

## Folder

`academy/database/migrations/*.sql` — numbered sequentially (`001_...` through the current highest number). Each migration is its own concern: schema change, course content seed, or test content seed. Most use a `WHERE NOT EXISTS` / `COUNT(*) = 0` guard so they're safe to re-run; a few (commented in the file itself) are intentionally one-shot or destructive cleanups.

## Migration categories at a glance

| Range | What it covers |
|---|---|
| 001–003 | Core schema: subscriptions table, tier columns on lessons/modules |
| 004–008 | Course content seeding (IELTS General Masterclass + 1Mo/2Mo variants) |
| 009–012 | Practice test content seeding (Listening/Reading/Writing/Speaking PT1) |
| 013–014, 021, 024 | Schema fixes/additions (`mode`/`time_spent` on test_attempts, `user_certificates`, `mock_sessions`, manual writing-grade override columns) |
| 015–020 | Full Mock Test 1 — container rows, Listening/Reading/Writing content, `mock_exams` catalog entry |
| 022, 023, 025, 026, 027 | Full Mock Test 2 — same pattern as 015–020 (container rows, Listening, Reading, Writing, `mock_exams` catalog entry). Number 024 is skipped here — it was already taken by the manual writing-grade override migration above, discovered mid-build; see the numbering note below. |

Mock Tests 3 and 4 currently exist only as **placeholder layout files** (`full_mock_003_*.php`, `full_mock_004_*.php`, `ielts_full_mock_00{3,4}.php`) plus `mock_test_map.php` entries — there is no content migration for either yet. Building one means picking the next free migration number and following the checklist in `resources/mock_tests/DOCUMENTATION.md`.

**Numbering lesson learned (FM2 build):** before claiming a migration number, check `ls migrations/` for that exact number — don't assume a range is free just because the previous mock used a contiguous block. 024 was assumed open for FM2's Reading content and turned out to already be `024_add_writing_manual_grading.sql`, forcing a renumber of Reading (→025), Writing (→026), and the `mock_exams` entry (→027) mid-build.

## Core tables (content layer)

`tests` (one row per gradable section or container) → `questions` → `question_options` (MCQ choices) / `question_correct_answers` (text-answer keys). This is the schema described in the e-learning assessment platform design doc (`documentation/e_learning_assessment_platform_database_schema_readme.md`).

## Core tables (attempt layer)

`test_attempts` (one row per practice-test or mock-section sitting) → `attempt_answers` (per-question response + score). For full mocks specifically, `mock_sessions` ties four `test_attempts` rows (L/R/W + speaking handled separately) together into one exam sitting — see `resources/mock_tests/DOCUMENTATION.md`.

## Catalog layer (full mocks only)

`mock_exams` + `mock_exam_sections` — browsing/listing metadata, separate from the content tables above. See `resources/mock_tests/DOCUMENTATION.md` for why this exists as a parallel layer.

## Known data-integrity flags (not yet cleaned up)

- **Orphaned `IELTS_GT_MOCK02_L` through `_S` rows** in `tests` (ids 13–17): a different naming convention from the `IELTS_FM{N}_{L|R|W}` standard, no migration file seeds them, and no code references them (`mock_test_map.php`, `ielts_full_mock_002.php`, etc. all use the `IELTS_FM2_*` codes instead). Likely an earlier abandoned attempt at Mock 2. Not yet confirmed whether any `questions` rows are linked — check that before deleting.
- **`IELTS_PT_L_002`**: has audio assets on disk but no migration seeds its `questions`/`tests` rows. Orphaned in the opposite direction (content exists, DB record doesn't). Flagged in `resources/practice_tests/DOCUMENTATION.md`.

## Authoritative references (don't duplicate, link to these)

- **Adding a new practice test:** `academy/documentation/ai_test_page_template.md` — the full naming convention, `question_type` ENUM reference, and migration SQL skeleton
- **Adding a new full mock:** checklist at the bottom of `resources/mock_tests/DOCUMENTATION.md`
- **Tracking what's been run where:** `migrations/migration_log.md` — update this immediately after running any migration, on both LOCAL and LIVE
- **Local vs live connection differences:** MAMP local = MySQL 5.7 via `/Applications/MAMP/tmp/mysql/mysql.sock`, database `useraccounts`; live cPanel = MySQL 8.0, database `slslanguage_db`. Local lacks `ADD COLUMN IF NOT EXISTS` support that live's MySQL 8 has — migrations touching columns must use the idempotent `information_schema` check pattern (see migration 016 for an example) to stay compatible with both.
