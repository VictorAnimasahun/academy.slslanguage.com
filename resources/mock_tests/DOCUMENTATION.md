# Mock Tests — Subsystem Documentation

Parent: [`../DOCUMENTATION.md`](../DOCUMENTATION.md)

Full-length IELTS mock exams: Listening (40Q/30min) → Reading (40Q/60min) → Writing (2 tasks/60min, AI-graded) → Speaking (instructor-administered). One sitting is tracked end-to-end by a single `mock_sessions` row.

## ⚠️ Two parallel systems exist here — know which one actually runs the test

This folder evolved in two layers. Both are real, both are wired up, but they do different jobs:

### Layer A — Catalog/listing (`mock_exams` + `mock_exam_sections` tables)
Used only for **browsing and launching**. `index.php` lists active mocks from `mock_exams`. Clicking one goes to `take.php?code=...`, which reads `mock_exam_sections` (via `config.php` helper functions) to show a section overview and a "Start Full Mock Test" button. That button computes the Listening URL as `strtolower($mockCode) . '.php'` — e.g. `ielts_full_mock_001.php`.

### Layer B — The actual exam engine (`mock_sessions` table + `mock_test_map.php`)
This is where questions are rendered, timed, submitted, and scored. `ielts_full_mock_{NNN}.php` is a thin **bridge file**: it creates/resumes a `mock_sessions` row, then redirects into `full_mock_{NNN}_listening.php` → `full_mock_{NNN}_reading.php` → `mock_writing.php` → `mock_speaking.php`. Every section-to-section handoff is resolved by `../../includes/mock_test_map.php`, not by Layer A.

**Practical consequence:** adding a new full mock requires touching *both* layers — seeding `mock_exams`/`mock_exam_sections` (so it's browsable) **and** creating its own `ielts_full_mock_{NNN}.php` bridge file (so the "Start" button actually launches the real engine). Missing the bridge file means the mock shows up in the list but 404s or dead-ends when started. See the checklist at the bottom of this doc.

## File-by-file reference

| File | Layer | Purpose |
|---|---|---|
| `index.php` | A | Lists active mocks from `mock_exams`, grouped by `exam_type` |
| `take.php` | A | Mock overview page; reads `mock_exam_sections`; "Start" button routes to the per-mock bridge file |
| `config.php` | A | Helper functions: `getMockExam()`, `getMockExamSections()`, `getTest()`, `getSectionUrl()`, `getFirstSectionUrl()`, icon/display-name helpers |
| `ielts_full_mock_001.php` | A→B bridge | FM1 launcher. Looks up `tests` row for `IELTS_FULL_MOCK_001`, creates/resumes a `mock_sessions` row, redirects to whichever section is next |
| `ielts_full_mock_002.php` | A→B bridge | FM2 launcher — same pattern, hardcoded to `IELTS_FULL_MOCK_002` / `full_mock_002_*` files |
| `mock_start.php` | B (fallback) | Generic resume page, keyed by `test_id` GET/POST param. Not linked from the real entry flow (Layer A bridges go straight to the listening file); exists as a fallback/dead-end target referenced by `mock_test_map.php`'s `?? 'mock_start.php'` defaults |
| `full_mock_001_listening.php` | B | FM1 Listening renderer — loads questions/options from DB by test code, renders per-part tabs, embedded audio player, submits to `mock_save_section.php` |
| `full_mock_001_reading.php` | B | FM1 Reading renderer — same DB-driven question rendering, but **passages are hardcoded in a PHP array** (`$passages`, keyed by first question number of each passage), not pulled from `stimulus_text`. See "Why passages are hardcoded" below |
| `full_mock_002_listening.php` / `full_mock_002_reading.php` | B | Cambridge IELTS GT Test 2 content. Improved generic pattern vs. FM1 (see below) — this is now the template to copy for new mocks, not FM1 |
| `full_mock_003_listening.php` / `full_mock_003_reading.php`, `ielts_full_mock_003.php` | A→B bridge + B | **Placeholder only** — copied from FM2's files (no FM2-specific hardcoding needed since FM2 already generalized the pattern), `$passages = []`, `mock_test_map.php` entry points at `IELTS_FM3_L/R/W`. No content migrations exist yet |
| `full_mock_004_listening.php` / `full_mock_004_reading.php`, `ielts_full_mock_004.php` | A→B bridge + B | Same placeholder status as Mock 3, pointing at `IELTS_FM4_L/R/W` |
| `mock_writing.php` | B | Shared writing section for **any** full mock — loads Task 1 + Task 2 from DB by test code, no mock-specific hardcoding |
| `mock_speaking.php` | B | Submission/collation page, not an actual test — packages L/R/W results for instructor review; speaking itself is administered live and graded later in `sls-admin` |
| `mock_save_section.php` | B | The scoring engine. One endpoint handles all three machine-graded sections (`listening`, `reading`, `writing`) via a `section` POST param. See "Scoring details" below |

## FM2's generalized pattern — the new standard to copy

FM2's listening/reading files removed several FM1 hardcodings, making them copy-paste-ready for future mocks with near-zero changes:

- **Audio path** is derived from `$session['mock_code']` at runtime (`assets/audio/{$session['mock_code']}/...`), not hardcoded to `IELTS_FULL_MOCK_001`. A new mock's listening file needs no audio-path edit at all.
- **Match-box title** (the heading shown above a matching-type question's options) pulls from the question's `stimulus_text` in the DB, not a hardcoded string.
- **`$partImages`** (used for Part 2-style diagram/photo questions) is a configurable array, empty by default, instead of FM1's hardcoded `if ($partNum === 2)` check — only populate it for mocks that actually have an image-labelling part.
- **`renderPassage()`'s** lettered-paragraph regex was extended from `[A-G]` to `[A-H]` to handle passages with 8 lettered sections.

Because of this, Mock 3 and 4's placeholder files (above) are near-identical copies of FM2's — only the admin-preview nav `href`s and `$passages`/migration content differ. **When building Mock 3/4's real content, keep this pattern; do not regress to FM1-style hardcoding.**

## Why Reading passages are hardcoded, not DB-driven

`stimulus_text` on the first question of each passage in the DB **does** contain the full passage text (it's stored there for completeness/future use), but `full_mock_NNN_reading.php` does **not** read it. Instead each reading page has its own `$passages` array, keyed by first-question-number, with a small regex-based `renderPassage()` function that detects paragraph-letter prefixes (`A  Some paragraph...`) and pupil-name lines for nicer formatting. This was a deliberate choice made when building FM1 — duplicating the passage text between the migration SQL and the PHP page — and the same convention was kept for FM2 for consistency. If you add a new full mock, you must paste the passage text into **both** places.

## Scoring details (`mock_save_section.php`)

- **Listening/Reading:** straight per-question comparison against `question_correct_answers` (text questions) or `question_options.is_correct` (MCQ). Band conversion via `listeningBand()` / `readingBand()` raw-score tables.
- **Choose-TWO pair questions** (e.g. "choose TWO letters" where two question numbers share one answer pair): scored by a `$pair_defs` array **keyed by test code**, not by section name — each full mock can have its pair question at a different position with different correct letters. Currently:
  ```php
  $pair_defs = match ($testCode) {
      'IELTS_FM1_L' => [[21, 22, ['c', 'e']], [23, 24, ['b', 'e']]],
      'IELTS_FM2_L' => [[19, 20, ['b', 'c']]],
      default => [],
  };
  ```
  **If you add a mock with a choose-TWO listening question, add its entry here.** This was a real bug caught while building FM2 — the original code keyed this only by `section === 'listening'`, which would have silently scored FM2's pair question against FM1's answer key.
- **Writing:** both tasks sent to Gemini (`gemini-2.0-flash`) via `gradeMockEssay()`, one call per task, JSON response parsed for band + feedback. `set_time_limit(120)` and a 45s cURL timeout are set because two sequential AI calls can take a while; switching away from `gemini-2.5-flash` was a deliberate fix for thinking-mode timeouts (see migration history / project memory for the postmortem).

## `mock_sessions` lifecycle

One row per attempt. Columns `listening_attempt_id`, `reading_attempt_id`, `writing_attempt_id` start NULL and are filled in as each section completes — this is how every page in the chain knows what to do next (resume vs. redirect vs. block out-of-order access). `status` moves `in_progress` → (something instructor-set after speaking is graded). Full release-to-student flow (speaking grading, banding, email/message notification) lives in `sls-admin/mock_session_detail.php`, documented in that project's own docs.

## Checklist: adding a new full mock test (e.g. Mock 3, Mock 4)

This is the exact sequence followed for FM2 — treat it as the spec. FM2's real migration numbers are shown as a worked example: **022** (tests container rows), **023** (Listening), **025** (Reading — not 024, see numbering note below), **026** (Writing), **027** (`mock_exams` catalog entry).

1. **Migrations** (in `academy/database/migrations/`, sequential numbering):
   - Seed `tests` container rows: `IELTS_FULL_MOCK_{NNN}`, `IELTS_FM{N}_L`, `IELTS_FM{N}_R`, `IELTS_FM{N}_W`
   - Seed Listening questions/options/answers for `IELTS_FM{N}_L`
   - Seed Reading questions/options/answers for `IELTS_FM{N}_R`
   - Seed Writing tasks (2 `essay`-type questions) for `IELTS_FM{N}_W`
   - Seed `mock_exams` + `mock_exam_sections` rows (Layer A catalog entry)
   - **Numbering lesson from FM2:** don't assume the next number after the previous mock's block is free — `024` was assumed open and turned out to be taken by an unrelated schema migration (`024_add_writing_manual_grading.sql`). Always `ls migrations/` for the exact number before writing the file.
2. **`includes/mock_test_map.php`** — add an entry for `IELTS_FULL_MOCK_{NNN}` mapping each section to its file + test code
3. **`full_mock_{NNN}_listening.php`** — copy FM2's file (not FM1's — see "FM2's generalized pattern" above), update admin-preview nav links. Audio path needs no edit since it derives from `$session['mock_code']` automatically; only populate `$partImages` if this mock has a diagram-labelling part
4. **`full_mock_{NNN}_reading.php`** — copy FM2's file, replace the `$passages` array with the new passages, extend the paragraph-letter regex past `[A-H]` if a passage uses more than 8 lettered paragraphs
5. **`ielts_full_mock_{NNN}.php`** — copy the previous mock's bridge launcher, swap the hardcoded test code and filenames (**easy to forget — without this, Layer A's "Start" button has nowhere real to go**)
6. **`mock_save_section.php`** — if this mock has a choose-TWO pair question anywhere, add its entry to `$pair_defs`
7. **Audio assets** — `academy/assets/audio/IELTS_FULL_MOCK_{NNN}/listening_part{1-4}.mp3`
8. Run migrations on LOCAL, verify question counts, then LIVE. Update `database/migrations/migration_log.md`.

**Mock 3 and 4 currently have steps 2, 3, 5 done (placeholders) — steps 1, 4 (real `$passages`), 6, 7, 8 remain** once Cambridge Test 3/4 content and audio are available.
