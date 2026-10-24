# Overnight 2026-10-24 → 25 (academy)

Branch: `overnight-2026-10-24-academy` (pushed, not merged, nothing run on live). `main` is untouched.

## What was asked

1. IELTS Academic Mock 1, from the Cambridge IELTS 13 Academic book in Downloads.
2. IELTS Mini Mock: Speaking self-administered (Part 2 cue card only), so a student gets a full result without a tutor.

## Commits on the branch

| Commit | What | Live needs |
|---|---|---|
| `0b9bb23` | **IELTS Mini Mock Speaking** is a self-recorded Part 2: cue card, 1 minute to prepare (notes box), one recording of up to 2 minutes in the browser. Results show Speaking as "Recorded (m:ss)" with a player and a download button. Nothing is uploaded (the page has no server). | Sync `mini_tests/ielts-mini-mock-test/index.html` and `scripts/script.js`. |
| `2e91f08` | **Fix: full mocks and diagnostics never counted as done** on the course page. The check matched `mock_sessions.mock_test_id` against `mock_exams.id`, but every launcher stores `tests.id`. Every Class 1 holds a diagnostic, so Class 1 could never complete. | Sync `includes/item_progress.php`. Worth merging soon. |
| `2c088cd` | **Exam pages: no left strips.** The shared exam theme (26 test pages) drew a coloured bar down the left of instruction bars, the audio notice and the Writing prompt. Now a thin even border. | Sync `assets/css/exam_theme.css`. |
| `f9be4ab` | **IELTS Academic Mock 1** (migration 172). | See below. |

## IELTS Academic Mock 1

- Source: Cambridge IELTS 13 Academic, Test 1 (PDF pages 133-154; answer key 241-242). Answers = the book's key.
- Same engine as the GT Full Mocks. Listening plays once, sections are locked, and Section 2 shows the map. Reading has 3 passages. Writing is AI-marked, with the hospital maps on Task 1. Speaking is tutor-administered.
- Opens from Class 8 "Mock Test 1" in the IELTS Academic Crash Course, 2-Month and 3-Month courses (found by folder).
- Content lives in `database/mocks/aca_mock_001_content.py`; `build_aca_mock_001.py` generates the migration and the passages file.
- Tested on MySQL 5.7 (twice) and end to end as a student: official answers 40/40, band 9, in both sections. Alternatives, lower case, wrong answers and blanks are all marked exactly (37/40 test). In Chrome, Part 1 autoplays, a pause is undone, and Sections 2-4 stay locked.
- **To go live:** merge, sync the files listed in the migration log (pages, map entry, 4 audio files about 20 MB, 2 images), then run `172_ielts_academic_mock_1.sql`.
- **Check by ear:** the four tracks are assumed to be Test 1 Sections 1-4 in CD order (`ielts13-tests1-4cd1track_01-04`), going by their lengths. Listen to the first few seconds of each.

## For the founder to decide

1. **General Training Reading is marked on the Academic band table** (`readingBand()` in `mock_save_section.php`: 30 = 7, 23 = 6). GT needs about 34 for 7 and 30 for 6. So GT mocks and GT practice tests give generous Reading bands. Not changed, because it moves real students' results; `sls-admin/attempt_scoring.php` keeps a copy of the table too.
2. **Writing Task 1 AI marking can't see the image.** The AI gets the question text only, so for maps and charts it judges the language, not accuracy against the picture. Same as the existing Academic diagnostic.
3. **Mocks 2 and 3** need the Cambridge 13 audio for Tests 2-3: CD1 tracks 5-8, and CD2.

## Before syncing live

The local academy checkout was switched back to `main` at the end of the night, so the usual sync command only ships what's on main. Merge the branch first if you want tonight's work live. Until then, the local database has migration 172 applied, and its Class 8 items point at pages that only exist on the branch.
