# Practice Tests — Subsystem Documentation

Parent: [`../DOCUMENTATION.md`](../DOCUMENTATION.md)

Standalone, single-section practice tests — IELTS and CELPIP, one test = one section = one sitting. Unlike `mock_tests/`, there's no multi-section session to track; every test page saves through the same generic endpoint.

## Entry points

| File | Purpose |
|---|---|
| `index.php` | Catalog page — lists every practice test grouped by exam type and section |
| `practice_tests_home.php` | Alternate landing page (defers to `index.php`) |
| `my_results.php` | Student's own results history — also shows mock test results (see `mock_tests/DOCUMENTATION.md`); this is the page linked from the dashboard as "My Results" |

## Shared infrastructure (do not duplicate per test)

| File | Purpose |
|---|---|
| `save_attempt.php` | **Generic** save/score endpoint — resolves the test by `test_code` in the POST body, works for any test without modification. New tests must reuse this, never write a new save endpoint. |
| `config.php` | `getTest()`, `getTestsByType()`, `getMediaPath()` — DB lookups shared across all test pages |
| `functions.php` | Shared scoring/utility functions |
| `writing_template.php` | Reusable writing-task renderer used by Task 1/Task 2 pages |
| `styles.php` | Shared CSS for all practice test pages |

## Test pages

| File | Test | Format |
|---|---|---|
| `ielts_listening_001.php` | IELTS Listening PT1 | 40Q, 4 parts, 30 min, audio |
| `ielts_reading_001.php` | IELTS Reading PT1 (GT) | 40Q, 3 sections, 60 min |
| `ielts_writing_t1_001.php` | IELTS Writing Task 1 (Letter) | 20 min, 150+ words, AI-graded |
| `ielts_writing_t2_001.php` | IELTS Writing Task 2 (Essay) | 40 min, 250+ words, AI-graded |
| `ielts_speaking_001.php` – `004.php` | IELTS Speaking PT1–4 (Hotels / Online Shopping / Famous People / Science) | 15 min, Parts 1–3 |
| `celpip_listening_001.php` | CELPIP Listening PT1 | 47 min, 8 parts |
| `celpip_reading_001.php` | CELPIP Reading PT1 | 55 min, 4 parts |
| `celpip_writing_t1_001.php` | CELPIP Writing Task 1 (Email) | 27 min |
| `celpip_writing_t2_001.php` | CELPIP Writing Task 2 (Survey) | 26 min |
| `celpip_speaking_001.php` | CELPIP Speaking PT1 | 16 min, 8 tasks |

## Test code naming convention

```
IELTS_PT_[SECTION]_[NNN]     e.g. IELTS_PT_L_001, IELTS_PT_W1_001
CELPIP_PT_[SECTION]_[NNN]    (same pattern, different prefix)
```
`SECTION` ∈ `L | R | W1 | W2 | S`. `NNN` is zero-padded to 3 digits. This is the **formally documented** convention — see `academy/documentation/ai_test_page_template.md`, which is the authoritative brief for generating any new practice test page + its migration.

## ⚠️ Known data-integrity risk: untracked test content

`IELTS_PT_L_002` has an audio asset folder (`academy/assets/audio/IELTS_PT_L_002/`) but **no migration file** seeds its questions into the DB. This means its content (if it exists in the live/local DB at all) was added directly through the `sls-admin` question editor, bypassing the migrations folder entirely — so it isn't reproducible from source and isn't tracked in `migration_log.md`. This is the same risk pattern that caused FM1's Listening/Reading questions to be wrong for a period (manually re-entered from the wrong source test). Worth auditing in `sls-admin` before treating `IELTS_PT_L_002` as real content.

## Practice-test setup activities

- [x] Stage IELTS Listening PT1–PT4 media. PT1 was already present; PT2–PT4 now each have four validated MP3 files named `part1.mp3` through `part4.mp3` in their respective `academy/assets/audio/IELTS_PT_L_00N/` folders.
- [ ] Seed and validate the `tests`, `questions`, options, and answer keys for every PT2–PT4 section through new, uniquely numbered migrations.
- [ ] Replace the PT2–PT4 placeholder pages with complete test renderers and verify each student submission flow locally before deploying it.

The completed media task does not by itself make PT2–PT4 runnable: their pages and database content are still pending.

## Adding a new practice test

Follow `academy/documentation/ai_test_page_template.md` exactly — it's a self-contained brief (test code convention, PHP `$parts` array structure per question type, the `$answers` format, the migration SQL skeleton, and the full `question_type` ENUM reference) designed to be handed wholesale to an AI or a developer to generate both files. Always reuse `save_attempt.php` — never write a new save endpoint per test.
