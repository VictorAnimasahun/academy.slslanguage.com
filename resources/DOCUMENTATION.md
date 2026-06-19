# Resources — Subsystem Documentation

Parent: [`../documentation/SITE_MAP.md`](../documentation/SITE_MAP.md)

`resources/` is the umbrella folder for everything a student does outside of structured course lessons: diagnostics, mock exams, practice tests, exercises, quizzes, downloadable materials, and the two AI-graded analyzer tools.

## Entry point

`resources_home.php` — the landing page for this whole area. Renders a card grid linking to each subsystem below. Linked from the dashboard and the main nav.

## Subsystems (each has its own deeper documentation)

| Folder/File | What it is | Doc |
|---|---|---|
| `mock_tests/` | Full-length, multi-section IELTS mock exams (Listening → Reading → Writing → Speaking), session-tracked, partially auto-graded | [`mock_tests/DOCUMENTATION.md`](mock_tests/DOCUMENTATION.md) |
| `practice_tests/` | Standalone, single-section practice tests (IELTS + CELPIP), one generic save endpoint | [`practice_tests/DOCUMENTATION.md`](practice_tests/DOCUMENTATION.md) |
| `exercises/` | Short interactive drills (drag-and-drop, data analysis, writing scaffolds) | [`exercises/DOCUMENTATION.md`](exercises/DOCUMENTATION.md) |
| `quizzes/` | Short knowledge-check quizzes (no DB persistence, client-side only) | [`quizzes/DOCUMENTATION.md`](quizzes/DOCUMENTATION.md) |
| `diagnostic_tests/` | Pre-assessment tests used to place a new student | [`diagnostic_tests/DOCUMENTATION.md`](diagnostic_tests/DOCUMENTATION.md) |
| `study_materials/` | Downloadable vocabulary/reference documents | [`study_materials/DOCUMENTATION.md`](study_materials/DOCUMENTATION.md) |
| `model_answers/model_answers.php` | Static library of sample writing/speaking responses | — single file, see inline comments |
| `essay_analyzer.php` | AI-graded writing feedback tool (Gemini API via `api/api_handler.php`) | — single file; see `ACADEMY_PLATFORM_DOCUMENTATION.md` § Essay Analyzer for internals |
| `audio_analyzer.php` | AI-graded speaking feedback tool (records, transcribes, analyzes) | — single file; see `ACADEMY_PLATFORM_DOCUMENTATION.md` § Audio Analyzer for internals |
| `gemini_audio_test.php` | Developer utility for testing the Gemini audio pipeline — not student-facing | — |

## How mock_tests and practice_tests differ

This distinction matters and trips people up, so it's worth stating plainly:

- **practice_tests** = one section, one sitting, no concept of a multi-step session. Graded and saved by the single generic `practice_tests/save_attempt.php` endpoint regardless of which test code is submitted.
- **mock_tests** = a full exam sitting that spans four sections in sequence, tracked end-to-end by a `mock_sessions` row (one row per attempt, columns for each section's `*_attempt_id` and band score). Listening/Reading are auto-graded; Writing is AI-graded; Speaking is instructor-graded manually in the separate `sls-admin` panel. See `mock_tests/DOCUMENTATION.md` for the full session lifecycle.

## Database dependency

Every test page in this folder (mock or practice) reads its content from the shared `tests` / `questions` / `question_options` / `question_correct_answers` tables — there is no hardcoded question content in PHP for DB-driven tests. The exception is mock test **passages** for Reading, which are hardcoded directly in the PHP page (see `mock_tests/DOCUMENTATION.md` for why).
