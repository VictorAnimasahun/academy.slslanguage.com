# Migration Log

Track every migration applied on each environment. Tick the checkbox and fill the date.

---

## 001 — Create `subscriptions` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live | [ ] | | |

**Rollback:** Run `001_rollback.sql` — drops the subscriptions table entirely.

---

## 002 — Add `min_tier` to `lessons`

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live | [ ] | | |

**Rollback:** Run `002_rollback.sql` — removes the min_tier column from lessons.
**Note:** All existing lessons default to 'beginner'. No data loss on rollback.

---

## 003 — Add `min_tier` to `modules`

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live | [ ] | | |

**Rollback:** Run `003_rollback.sql` — removes the min_tier column from modules.
**Note:** All existing modules default to 'beginner'. No data loss on rollback.

---

## 004 — Seed IELTS General 3-Month Masterclass course

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-05-04 | 1 course, 3 modules, 24 lessons inserted |
| Live | [ ] | | |

**Rollback:** No rollback file — delete rows directly:
```sql
DELETE FROM lessons WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst');
DELETE FROM modules WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst');
DELETE FROM courses WHERE folder_name = 'IELTS_Gen_Mst';
```

---

## 005 — Set `file_path` for IELTS General Masterclass lessons

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [SKIP] | | Superseded by migration 006 — do not run |
| Live | [SKIP] | | Superseded by migration 006 — do not run |

**Superseded by 006.** The path format changed (shared lesson bank) and tiers also needed updating in the same pass. Running 005 would set wrong paths and would need to be undone before 006.

---

## 006 — Restructure IELTS General Masterclass tiers and shared file_paths

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-05-27 | All 24 lessons updated — tiers and file_paths pointing to shared bank |
| Live | [ ] | | |

**What it does:**
- Fixes `min_tier` on IELTS_Gen_Mst modules: Month 1 → intermediate, Month 2 → advanced, Month 3 → fluent
- Fixes `min_tier` on all 24 lessons: class 1 → beginner, 2–8 → intermediate, 9–16 → advanced, 17–24 → fluent
- Sets `file_path` on all 24 lessons to the new shared bank: `courses/IELTS_Gen/lessons/classXX.php`

**Rollback:**
```sql
SET @c = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_Mst');
UPDATE modules SET min_tier = 'fluent' WHERE course_id = @c;
UPDATE lessons SET min_tier = 'fluent', file_path = NULL WHERE course_id = @c;
UPDATE lessons SET min_tier = 'beginner' WHERE course_id = @c AND lesson_order = 1 AND module_id = (SELECT id FROM modules WHERE course_id = @c AND module_order = 1);
```

---

## 007 — Seed IELTS General 1-Month Starter course

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-05-27 | 1 course, 1 module, 8 lessons inserted |
| Live | [ ] | | |

**Dependency:** Migration 006 must be applied first.

**What it does:** Inserts `IELTS_Gen_1Mo` course, 1 module, 8 lessons (copied from Mst Month 1 via INSERT SELECT).

**Rollback:**
```sql
DELETE FROM lessons WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_1Mo');
DELETE FROM modules WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_1Mo');
DELETE FROM courses WHERE folder_name = 'IELTS_Gen_1Mo';
```

---

## 008 — Seed IELTS General 2-Month Intensive course

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-05-27 | 1 course, 2 modules, 16 lessons inserted |
| Live | [ ] | | |

**Dependency:** Migration 006 must be applied first.

**What it does:** Inserts `IELTS_Gen_2Mo` course, 2 modules, 16 lessons (copied from Mst Months 1 & 2 via INSERT SELECT).

**Rollback:**
```sql
DELETE FROM lessons WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_2Mo');
DELETE FROM modules WHERE course_id = (SELECT id FROM courses WHERE folder_name = 'IELTS_Gen_2Mo');
DELETE FROM courses WHERE folder_name = 'IELTS_Gen_2Mo';
```

---

## 009 — Seed IELTS Listening Practice Test 1 (`IELTS_PT_L_001`)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-05-06 | 1 test, 40 questions, 28 options, 35 correct answers inserted |
| Live  | [ ] | | |

**Dependency:** Core tables (`tests`, `questions`, `question_options`, `question_correct_answers`) must exist — these were part of the original schema, not a migration.

**What it does:**
- Inserts 1 record into `tests` (code=`IELTS_PT_L_001`, 40 questions, 30 min, category=Listening)
- Inserts 40 records into `questions` (Parts 1–4, correct question types per IELTS format)
- Inserts MCQ options into `question_options` for Q11–14, Q27–30
- Inserts text/letter answers into `question_correct_answers` for all fill-in and matching questions
- Uses `WHERE @existing = 0` guard — safe to run twice (won't double-insert)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001'));
DELETE FROM questions  WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001');
DELETE FROM tests      WHERE code = 'IELTS_PT_L_001';
```

---

## 010 — Seed IELTS Reading Practice Test 1 (`IELTS_PT_R_001`)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live  | [ ] | | |

**Dependency:** Core tables (`tests`, `questions`, `question_options`, `question_correct_answers`) must exist — part of original schema.

**What it does:**
- Inserts 1 record into `tests` (code=`IELTS_PT_R_001`, 40 questions, 60 min, category=Reading)
- Inserts 40 records into `questions` across 3 sections (T/F/NG, matching, note/sentence completion, MCQ, section matching, summary completion)
- Inserts MCQ options into `question_options` for Q28–31 (4 options each = 16 rows)
- Inserts text/letter answers into `question_correct_answers` for all non-MCQ questions (includes alternatives for Q15, Q18, Q20)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_R_001'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_R_001'));
DELETE FROM questions  WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_R_001');
DELETE FROM tests      WHERE code = 'IELTS_PT_R_001';
```

---

## 011 — Seed IELTS Writing Task 1 + Speaking Practice Test 1

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live  | [ ] | | |

**What it does:** Inserts 2 records into `tests` — `IELTS_PT_W1_001` (Writing Task 1, 20 min) and `IELTS_PT_S_001` (Speaking, 15 min). No `questions` rows — these are AI-graded open-ended tasks.

**Rollback:**
```sql
DELETE FROM tests WHERE code IN ('IELTS_PT_W1_001', 'IELTS_PT_S_001');
```

---

## 012 — Re-seed IELTS Listening Practice Test 1 (corrected)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | Fixes broken 009 data — wipes and re-inserts all 40 questions |
| Live  | [ ] | | Run after 013 |

**Why it exists:** Migration 009 used `multiple_choice_multiple` for Q29/Q30 but stored their options incorrectly. 012 is the corrected idempotent re-seed. It wipes child records before re-inserting, so it is safe to run even if 009 already ran.

**Dependency:** Migration 013 must be applied first (adds `mode` + `time_spent` to `test_attempts`).

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001'));
DELETE FROM questions  WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_PT_L_001');
DELETE FROM tests      WHERE code = 'IELTS_PT_L_001';
```

---

## 013 — Add missing schema (`mode`, `time_spent`, `user_certificates`)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live  | [ ] | | Fixes 500 on save_attempt.php and dashboard errors |

**What it does:**
- Adds `mode ENUM('practice','mock')` to `test_attempts` — was missing, causing save_attempt.php to 500 on live
- Adds `time_spent INT UNSIGNED` to `test_attempts` — was missing, causing analytics/dashboard errors
- Creates `user_certificates` table — was missing, causing dashboard to log errors on every page load

**Rollback:**
```sql
ALTER TABLE test_attempts DROP COLUMN IF EXISTS `mode`;
ALTER TABLE test_attempts DROP COLUMN IF EXISTS `time_spent`;
DROP TABLE IF EXISTS user_certificates;
```

---

## Migration 014 — 2026-05-15
- Creates `mock_sessions` table (tracks full mock exam sittings per student)
- Seeds `tests` table with IELTS_FULL_MOCK_001 (category=Full, is_mock_section=1)
- Run order: LOCAL → LIVE

---

## 015 — Seed IELTS_FM1_L / IELTS_FM1_R / IELTS_FM1_W test records

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:** Inserts test container rows for the three graded sections of Full Mock 001.

---

## 016 — Seed IELTS_FM1_L questions (Listening Full Mock 1)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Adds `part_number` column to `questions` table (idempotent, MySQL 5.7/8.0 safe)
- Inserts 40 questions across 4 parts (form completion, MCQ, plan labelling, choose-TWO, matching, note completion)
- All correct answers loaded; ⚠️ some question stems and MCQ option text are placeholders — update from Cambridge IELTS GT Test 1 book

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_L');
```

---

## 017 — Seed IELTS_FM1_R questions (Reading Full Mock 1)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 40 questions across 3 sections (matching, T/F/NG, table/sentence/summary completion, matching headings, MCQ)
- All correct answers loaded; ⚠️ passage texts and question stems are placeholders — paste Cambridge passage text into Q1/Q15/Q28 instructions fields

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_R');
```

---

## 018 — Seed IELTS_FM1_W questions (Writing Full Mock 1)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 2 writing task records into `questions` (Task 1 = letter to Mrs Barrett, Task 2 = plastic/environment essay)
- Prompts are complete — no placeholders

**Rollback:**
```sql
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM1_W');
```

---

## 024 — Manual writing grading override

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-17 | |
| Live  | [x] | 2026-06-17 | |

**What it does:**
- Adds `writing_notes` (TEXT NULL) to `mock_sessions` — instructor's manual comments, separate from AI feedback
- Adds `writing_graded_by` (ENUM('ai','instructor') DEFAULT 'ai') to `mock_sessions` — tracks which source the displayed writing band/feedback came from
- Lets an instructor override the Writing band in `mock_session_detail.php` when the Gemini AI grader is down or wrong, instead of being stuck with an AI-only score

**Rollback:**
```sql
ALTER TABLE mock_sessions DROP COLUMN writing_graded_by;
ALTER TABLE mock_sessions DROP COLUMN writing_notes;
```

---

## 028 — Seed test container records for IELTS Full Mock 003

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 4 placeholder `tests` rows: `IELTS_FULL_MOCK_003`, `IELTS_FM3_L`, `IELTS_FM3_R`, `IELTS_FM3_W`
- Step 1 of the "adding a new full mock" checklist (see `resources/mock_tests/DOCUMENTATION.md`) — content (questions/passages/audio) follows in later migrations

**Rollback:**
```sql
DELETE FROM tests WHERE code IN ('IELTS_FULL_MOCK_003','IELTS_FM3_L','IELTS_FM3_R','IELTS_FM3_W');
```

---

## 029 — Seed IELTS_FM3_L (Listening Full Mock Test 3)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 40 questions, 46 options, 26 correct answers for `IELTS_FM3_L` (Cambridge IELTS GT Test 3 — full real content, transcribed from the book)
- Also adds an `IELTS_FM3_L` entry to `$pair_defs` in `mock_save_section.php` for the four choose-TWO question pairs (Q11–12, Q13–14, Q21–22, Q23–24) — without this they'd silently score against the wrong answer key

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_L'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_L'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_L');
```
(Also remove the `IELTS_FM3_L` line from `$pair_defs` in `mock_save_section.php`.)

---

## 030 — Seed IELTS_FM3_R (Reading Full Mock Test 3)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 40 questions, 113 options, 39 correct answers for `IELTS_FM3_R` (Cambridge IELTS GT Test 3 — full real content, transcribed from the book)
- Q1–5 (paragraph matching), Q28–33 (heading matching), Q37–40 (sentence-ending matching) each get their own full copy of their options box per question_id — the Reading UI's dropdown reads options per-question, unlike Listening's shared-box + free-text input
- Two answers carry an accepted alternative: Q19 accepts "flavour" or "flavor"; Q24 accepts "agenda" or "meeting agenda"
- Reading passage text itself is NOT duplicated here — it lives in `full_mock_003_reading.php`'s `$passages` array (also updated this session, along with a small `renderPassage()` enhancement to handle lettered paragraphs that have no separate heading/body split)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_R'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_R'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_R');
```

---

## 031 — Seed IELTS_FM3_W (Writing Full Mock Test 3)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-20 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 2 writing task records into `questions` (Task 1 = letter about a book that influenced you, Task 2 = essay on living close to where you were born)
- Prompts are complete — no placeholders

**Rollback:**
```sql
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM3_W');
```

---

## 032 — Add FM3 to mock_exams and mock_exam_sections

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-23 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Adds `IELTS_FULL_MOCK_003` to `mock_exams` (Layer A catalog) and its 3 sections to `mock_exam_sections` (Listening → `IELTS_FM3_L`, Reading → `IELTS_FM3_R`, Writing_Task1 → `IELTS_FM3_W`)
- Last step of the Mock 3 build — makes it visible/launchable from the `index.php`/`take.php` browse pages
- Mock 3 is now fully complete end-to-end: migrations 028–032, `full_mock_003_reading.php`'s `$passages` filled in, audio in `assets/audio/IELTS_FULL_MOCK_003/`

**Rollback:**
```sql
DELETE FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_003';
DELETE FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_003';
```

---

## 033 — Seed test container records for IELTS Full Mock 004

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-29 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 4 `tests` container rows: `IELTS_FULL_MOCK_004`, `IELTS_FM4_L`, `IELTS_FM4_R`, `IELTS_FM4_W`

**Rollback:**
```sql
DELETE FROM tests WHERE code IN ('IELTS_FULL_MOCK_004','IELTS_FM4_L','IELTS_FM4_R','IELTS_FM4_W');
```

---

## 034 — Seed IELTS_FM4_L (Listening Full Mock Test 4)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-29 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 40 questions, 29 options, 35 correct answers for `IELTS_FM4_L` (Cambridge IELTS 16 Test 4 — full real content)
- Part 2 Q15–20 is a `diagram_map_labelling` question (recreation ground map) — image lives at `assets/img/mock_tests/IELTS_FULL_MOCK_004/part2_map.png`, wired into `full_mock_004_listening.php`'s `$partImages[2]`
- Two choose-TWO pairs: Q21–22 and Q23–24, both correct = B & C — added to `mock_save_section.php`'s `$pair_defs` under `IELTS_FM4_L`
- Three answers carry an accepted alternative: Q31 accepts "spice"/"spices", Q32 accepts "colony"/"settlement", Q36 accepts "balance"/"balancing"
- Audio placed at `assets/audio/IELTS_FULL_MOCK_004/listening_part{1-4}.mp3`

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_L'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_L'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_L');
```
(Also remove the `IELTS_FM4_L` line from `$pair_defs` in `mock_save_section.php`.)

---

## 035 — Seed IELTS_FM4_R (Reading Full Mock Test 4)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-29 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 40 questions, 96 options, 36 correct answers for `IELTS_FM4_R` (Cambridge IELTS 16 Test 4 — full real content)
- Q1–8 (boot review matching), Q32–37 (organisation matching) each get their own full copy of their options box per question_id, per the FM1/FM2/FM3 convention
- `full_mock_004_reading.php`'s `$passages` array filled in with all 5 texts (boots, beekeeping, CV writing, new job, women's football history)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_R'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_R'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_R');
```

---

## 036 — Seed IELTS_FM4_W (Writing Full Mock Test 4)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-29 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Inserts 2 writing task records into `questions` (Task 1 = email to a friend advising on student accommodation, Task 2 = essay on the best time in history to live)
- Prompts are complete — no placeholders

**Rollback:**
```sql
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_FM4_W');
```

---

## 037 — Add FM4 to mock_exams and mock_exam_sections

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-06-29 | |
| Live  | [x] | 2026-07-20 | |

**What it does:**
- Adds `IELTS_FULL_MOCK_004` to `mock_exams` (Layer A catalog) and its 3 sections to `mock_exam_sections` (Listening → `IELTS_FM4_L`, Reading → `IELTS_FM4_R`, Writing_Task1 → `IELTS_FM4_W`)
- Last step of the Mock 4 build — makes it visible/launchable from the `index.php`/`take.php` browse pages
- Mock 4 is now fully complete end-to-end: migrations 033–037, `full_mock_004_reading.php`'s `$passages` filled in, audio in `assets/audio/IELTS_FULL_MOCK_004/`, map image in `assets/img/mock_tests/IELTS_FULL_MOCK_004/`

**Rollback:**
```sql
DELETE FROM mock_exam_sections WHERE mock_code = 'IELTS_FULL_MOCK_004';
DELETE FROM mock_exams WHERE code = 'IELTS_FULL_MOCK_004';
```

---

## 038 — Create `vocabulary_words` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | 15 columns, all indexes confirmed |
| Live  | [x] | 2026-07-01 | |

**What it does:**
- Creates the `vocabulary_words` table — the core store for the Vocabulary Banks feature
- Fields: headword, phonetic (IPA), word_class, cefr_level, is_awl flag, definition,
  secondary_definitions, synonyms, antonyms, collocations, word_family, sort_order, is_active
- UNIQUE key on `headword` prevents duplicates
- Indexes on word_class, cefr_level, is_awl, is_active, sort_order

**Rollback:**
```sql
DROP TABLE IF EXISTS vocabulary_words;
```

---

## 039 — Add `word_id` (nullable FK) to `questions` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | Column + idx_word_id index confirmed |
| Live  | [x] | 2026-07-01 | |

**What it does:**
- Adds nullable `word_id INT UNSIGNED` column to `questions`, positioned after `test_id`
- Adds `idx_word_id` index for fast per-word question lookups
- Fully backwards-compatible — all existing questions keep `word_id = NULL`

**Rollback:**
```sql
ALTER TABLE questions DROP INDEX idx_word_id;
ALTER TABLE questions DROP COLUMN word_id;
```

---

## 040 — Create `word_test_usages` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | 9 columns, indexes on word_id + exam_type/skill confirmed |
| Live  | [x] | 2026-07-01 | |

**What it does:**
- Creates `word_test_usages` — stores per-word example sentences keyed by exam type (IELTS/CELPIP/PTE/General), skill (Listening/Reading/Writing/Speaking), and sub-section (e.g. Task 1, Part 3, Summarize Written Text)
- Each row: one example sentence + optional context note for one word × one test context
- `word_id` links back to `vocabulary_words`

**Rollback:**
```sql
DROP TABLE IF EXISTS word_test_usages;
```

---

## 041 — Seed first 30 vocabulary words (batch 1)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | 30 rows confirmed — AWL Sublist 1 + high-frequency test words |
| Live  | [x] | 2026-07-01 | |

**What it does:**
- Seeds 30 words into `vocabulary_words`: analyse, approach, assess, benefit, concept, context,
  contribute, crucial, demonstrate, environment, establish, evaluate, factor, focus, identify,
  impact, indicate, individual, involve, issue, maintain, method, policy, principle, process,
  significant, structure, suggest, therefore, vary
- Each row includes phonetic, word_class, CEFR level, AWL flag, definition, secondary definitions,
  synonyms, antonyms, collocations, and word family
- INSERT IGNORE — safe to re-run

**Rollback:**
```sql
DELETE FROM vocabulary_words WHERE sort_order BETWEEN 1 AND 30;
```

---

## 042 — Seed test usage examples for first 30 vocabulary words

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | 60 rows — IELTS (Writing×30, Reading×8, Speaking×7, Listening×3), CELPIP Writing×5, PTE Writing×7 |
| Live  | [x] | 2026-07-01 | |

**What it does:**
- Seeds 60 rows into `word_test_usages` — 2 examples per word across different exam types and skills
- Each row includes an authentic example sentence and an examiner context note
- DELETE + re-INSERT pattern — safe to re-run

**Rollback:**
```sql
DELETE FROM word_test_usages
WHERE word_id IN (SELECT id FROM vocabulary_words WHERE sort_order BETWEEN 1 AND 30);
```

---

## 043 — Seed vocab quiz test containers (first 30 words)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-01 | 30 rows — VOCAB_WORD_001 to VOCAB_WORD_030 confirmed |
| Live  | [x] | 2026-07-02 | |

**What it does:**
- Inserts one row into `tests` per vocabulary word (codes VOCAB_WORD_001–VOCAB_WORD_030)
- test_type = 'Vocabulary', category = 'Word Exercise', duration = 5 min, total_questions = 0 (updated when questions are seeded in migration 044)
- Uses INSERT IGNORE + NOT EXISTS guard — safe to re-run

**Rollback:**
```sql
DELETE FROM tests WHERE code LIKE 'VOCAB_WORD_%';
```

---

## 044 — Seed vocab quiz questions for first 30 words

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-02 | 90 questions, 240 option rows, 31 answer rows — all 30 tests show total_questions=3 |
| Live  | [x] | 2026-07-02 | |

**What it does:**
- Clears existing vocab questions (idempotent) then re-inserts
- 3 questions per word: (1) Definition MCQ, (2) Gap-fill sentence, (3) Word form MCQ
- Populates `questions` (word_id set), `question_options` (4 per MCQ), `question_correct_answers` (gap-fill answers)
- Updates `tests.total_questions` to 3 for all VOCAB_WORD_XXX containers

---

## 045 — Extend `assignments` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-02 | |
| Live  | [ ] | | |

**What it does:**
- Adds `test_id INT UNSIGNED NULL FK → tests(id) ON DELETE SET NULL`
- Adds `type ENUM('test','quiz','vocabulary','task') NOT NULL DEFAULT 'task'`
- Adds `created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP`

**Rollback:**
```sql
DELETE qca FROM question_correct_answers qca JOIN questions q ON qca.question_id=q.id JOIN tests t ON q.test_id=t.id WHERE t.test_type='Vocabulary';
DELETE qo FROM question_options qo JOIN questions q ON qo.question_id=q.id JOIN tests t ON q.test_id=t.id WHERE t.test_type='Vocabulary';
DELETE q FROM questions q JOIN tests t ON q.test_id=t.id WHERE t.test_type='Vocabulary';
UPDATE tests SET total_questions=0 WHERE test_type='Vocabulary';
```

---

## 046 — Create `api_tokens` table

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-07-25 | |
| Live  | [ ] | | |

**What it does:**
- Creates `api_tokens` table for bearer-token auth (sls_mobile app, future API clients)
- `student_id` FK → `students(id)` ON DELETE CASCADE
- `token` is a unique 64-char random hex string; `expires_at` required, `revoked_at` nullable for manual logout/invalidation

**Rollback:**
```sql
DROP TABLE IF EXISTS api_tokens;
```

---

## 050-054 — Seed CELPIP practice test containers (Listening/Reading/Writing T1+T2/Speaking, PT1-3)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-04 | Ran for the first time this session -- these 5 files existed but were never actually applied on local before now. 15 `tests` rows created (CELPIP_PT_[L\|R\|W1\|W2\|S]_00[1-3]). |
| Live  | [ ] | | |

**What it does:** Container rows only (title/duration/category) for 3 CELPIP practice test sets across all 5 sections. Writing T1/T2 pages already had real prompts hardcoded in PHP; Listening/Reading/Speaking pages were stub placeholders until migration 055+ below fills in real content per test.

---

## 055 — Seed real question content for CELPIP Reading Practice Test 1

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-04 | 38 questions, 161 options, all scored -- verified via direct query and a PHP CLI render smoke test. Corrected same day (see note below). |
| Live  | [ ] | | |

**What it does:**
- Transcribes CELPIP Reading Test 1 (Downloads/CELPIP TASKS/Celpip Reading/Test 1, 4 PDFs + answer key docx) into `questions`/`question_options` for `CELPIP_PT_R_001`
- Updates `tests.total_questions` to 38 (was a placeholder 4)
- All 38 questions are real graded MCQ. **Correction:** the migration originally shipped Q29-33 (Part 4, viewpoint questions 1-5) as ungraded `short_answer` rows, believing the source PDF never printed their options. That was a misread on first pass -- the PDF lists Questions 1-5's four options in a block *after* the Questions 6-10 cloze text instead of directly under each stem like every other part in this test, so they were missed. Caught by the user and fixed same day: Q29-33 are now real `multiple_choice_single` questions with full option sets, matching the rest of the test.
- Companion page `resources/practice_tests/celpip_reading_001.php` replaced its stub with the full 4-part renderer (mcq / paragraph_match / diagram section types)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001');
UPDATE tests SET total_questions = 4 WHERE code = 'CELPIP_PT_R_001';
```

---

## 056 — Seed real question content for CELPIP Reading Practice Test 2

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-04 | 38 questions, 161 options -- verified via direct query and a PHP CLI render smoke test |
| Live  | [ ] | | |

**What it does:**
- Transcribes CELPIP Reading Test 2 into `questions`/`question_options` for `CELPIP_PT_R_002`, all 38 questions scored (no ungraded items this time)
- Part 1 uses the instructor's own rewritten passage + 11 questions (sent directly, replacing the source PDF's broken English and its own answer key)
- Parts 2-4 are cleaned-up rewrites of the source PDFs -- same topics and same correct answers as the original material, wording polished because that source read as non-native/broken English throughout (unlike Test 1's source). See `project_celpip_practice_tests.md` memory for the full quality note.
- Companion page `resources/practice_tests/celpip_reading_002.php` replaced its stub with a 4-part renderer (mcq / paragraph_match / schedule-diagram section types)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_002'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_002'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_002');
UPDATE tests SET total_questions = 4 WHERE code = 'CELPIP_PT_R_002';
```

---

## 057 — Seed real question content for CELPIP Reading Practice Test 3

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-04 | 38 questions, 161 options -- verified via direct query and a PHP CLI render smoke test |
| Live  | [ ] | | |

**What it does:**
- Transcribes CELPIP Reading Test 3 (`CELPIP READING Test III.pdf` -- all 4 parts in one file, easy to miss since Tests 1-2 split theirs across 4 separate PDFs) into `questions`/`question_options` for `CELPIP_PT_R_003`, all 38 scored
- Answer key was `READING TEST 3 Answers.docx`, sitting directly in `Celpip Reading/` rather than inside `Test 3/` -- also easy to miss
- Source prose was already native-quality (unlike Test 2), so this is a faithful transcription with only light polish -- one broken question stem in Part 4 Q3 ("Pallister does not [X]" didn't grammatically fit its own answer key text) was fixed by dropping "does not"
- The answer key document's leading digit index was unreliable in several places (pointed at the wrong list position while the answer text itself was correct) -- answers were matched by text content against the source options, not by the printed digit
- Companion page `resources/practice_tests/celpip_reading_003.php` replaced its stub with a 4-part renderer (mcq / paragraph_match / brochure-diagram section types)

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_003'));
DELETE FROM question_options          WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_003'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_003');
UPDATE tests SET total_questions = 4 WHERE code = 'CELPIP_PT_R_003';
```

---

## 058 — Fix wrong answer key for CELPIP_PT_R_001 Q25

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-05 | Verified via direct query: option D → is_correct=0, option E → is_correct=1 |
| Live  | [ ] | | |

**What it does:**
- Q25 ("Campsites are evenly spaced across the country.") was seeded in migration 055 with option D marked correct, following the source answer key document. That document's justification for D doesn't hold up on re-reading the passage — none of paragraphs A-D address geographic distribution/spacing. Correct answer is E (Not given).
- Content error in the source material, caught by spot-checking the answer key against the passage itself.

**Rollback:**
```sql
UPDATE question_options SET is_correct = 1 WHERE question_id = (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001') AND question_number = 25) AND option_label = 'D';
UPDATE question_options SET is_correct = 0 WHERE question_id = (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'CELPIP_PT_R_001') AND question_number = 25) AND option_label = 'E';
```

---

## 059 — Seed batch 2 of vocabulary words (idiomatic/compound expressions)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-06 | 3 rows confirmed — sort_order 31-33 |
| Live  | [ ] | | |

**What it does:**
- Adds 3 words to `vocabulary_words`, continuing from batch 1 (migration 041): colour in (phrase),
  loan-sourced (adjective), slap-on (adjective)
- `word_class` has no dedicated `idiom` value in the schema (migration 038) — these were classed as
  `phrase`/`adjective`, the closest existing fit for phrasal-verb and compound-adjective style entries
- Each row includes phonetic, word_class, CEFR level, AWL flag, definition, synonyms, antonyms,
  collocations, and word family
- INSERT IGNORE — safe to re-run

**Rollback:**
```sql
DELETE FROM vocabulary_words WHERE sort_order BETWEEN 31 AND 33;
```

---

## 060 — Seed "consumerism" into vocabulary_words

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-06 | 1 row confirmed — sort_order 34, id 34 |
| Live  | [ ] | | |

**What it does:**
- Adds "consumerism" (noun, C1) to `vocabulary_words`, continuing from batch 2 (migration 059)
- Includes phonetic, definition, secondary definition, synonyms, antonyms, collocations, and word family
- INSERT IGNORE — safe to re-run

**Rollback:**
```sql
DELETE FROM vocabulary_words WHERE sort_order = 34;
```

---

## 061 — Seed "canopy" and "understory" into vocabulary_words

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-07 | 2 rows confirmed — sort_order 35-36, ids 35-36 |
| Live  | [ ] | | |

**What it does:**
- Adds 2 words to `vocabulary_words`, continuing from migration 060: canopy (noun, B2), understory (noun, C1)
- Paired as antonyms of each other (forest canopy vs. understory layer) for matching/antonym quiz questions
- Includes phonetic, definition, secondary definition (canopy only), synonyms, antonyms, collocations, and word family
- INSERT IGNORE — safe to re-run

**Rollback:**
```sql
DELETE FROM vocabulary_words WHERE sort_order BETWEEN 35 AND 36;
```

---

## 062 — Build out the IELTS Academic Masterclass (course_id=4)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-07 | 8 modules, 26 lessons confirmed |
| Live  | [ ] | | |

**What it does:**
- The course row and its `intro.php` marketing copy already existed (8-week, 8-module, all-4-skills plan) but had zero modules/lessons in the DB, and its folder had a stray trailing dot (`IELTS_Aca_Mst.`) that broke every "Start Course" link — fixed via `git mv` in the same commit, not this migration.
- Seeds all 8 weekly modules and 26 lessons: Week 1 (orientation + diagnostic test lesson), Weeks 2-4 (Listening/Reading/Vocab & Grammar, new content), Weeks 5-6 (Writing Task 1 / Task 2, using the user's Day 1-7 curriculum), Week 7 (Speaking), Week 8 (mock + review).
- New pages `courses/IELTS_Aca_Mst/course_overview.php` and `lesson.php` render this content (generic DB-driven lesson viewer — first course to actually use the `lessons.content` column, which had never been rendered anywhere before).
- Lesson 2 (Diagnostic Self-Assessment) links out via `file_path` to the diagnostic test — see migration 063.

**Rollback:**
```sql
DELETE FROM lessons WHERE course_id = 4;
DELETE FROM modules WHERE course_id = 4;
UPDATE courses SET description = 'Advanced academic training for IELTS test-takers aiming for high band scores.', total_lessons = 8 WHERE id = 4;
```

---

## 063 — Rebuild the IELTS Academic Diagnostic Test on the real mock architecture

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-07 | Full session flow (Listening → Reading → Writing → Speaking handoff) verified end-to-end with real scoring. **Listening content itself was WRONG — see correction below and migration 065.** |
| Live  | [ ] | | Blocked on first INSERT — see "Live re-run" note below. Content-wise, run the corrected/idempotent version of this file (065 fixes Listening on top of it). |

> **Correction (2026-09-08):** the Listening section below reused the old `diagnostic_IELTS.php` page's audio + questions, which were never verified against real IELTS material. The original instruction was to reuse a Full Mock's already-confirmed audio + content instead. Fixed in migration 065 (deletes this section's Listening content and reseeds from `IELTS_FM1_L` Part 1). Reading and Writing content in this migration are unaffected and correct as written below.
>
> **Live re-run note:** the original (non-idempotent) version of this file failed live with `Duplicate entry 'IELTS_ACA_DIAGNOSTIC' for key 'code'` — a prior partial attempt had already inserted the container `tests` row. The file on disk now has every `INSERT` guarded with `WHERE NOT EXISTS`, so it's safe to paste and re-run as-is; it only fills in what's missing. Run in order on live: 063 (idempotent) → 064 → 065.

**What it does:**
- Replaces the old `diagnostic_IELTS.php` — a hardcoded, single-file page with fake client-side JS grading, mislabeled "IELTS Full Practice Test" — with the same DB-driven `tests`/`questions`/`mock_sessions` architecture the Full Mock tests use. `diagnostic_IELTS.php` is now a thin redirect stub for old bookmarks.
- New container test `IELTS_ACA_DIAGNOSTIC` + 3 section tests (`IELTS_ACA_DIAG_L/R/W`), registered in `includes/mock_test_map.php`.
- ~~Listening reuses the real audio + questions/answers that were already hardcoded in the old page (copied to `assets/audio/IELTS_ACA_DIAGNOSTIC/`).~~ **Superseded by migration 065** — see correction note above. The old Reading passage was an unfinished stub with no answer key, and the old Writing task was a General Training letter — wrong for an Academic course — so both were replaced with real, complete, Academic-appropriate content (a short passage + summary-completion questions; an Academic Task 1 bar-chart prompt, described in text since no chart image asset exists yet).
- New launcher `resources/mock_tests/ielts_aca_diagnostic.php` and section runners `diagnostic_aca_listening.php` / `diagnostic_aca_reading.php`, modeled directly on the Full Mock 1 files. Writing and Speaking reuse the existing `mock_writing.php`/`mock_speaking.php` unmodified (already generic).
- **Bug fixes made to shared `mock_save_section.php` while verifying this end-to-end** (backward-compatible — no-op for the existing 40-question Full Mocks):
  - Listening/Reading band-score tables are calibrated for 40 questions; short sections now scale up first instead of producing nonsense bands (a perfect 10/10 was mapping to "Band 4").
  - Writing band was unconditionally averaging Task 1 and Task 2 — an empty, never-configured Task 2 (as in this Task-1-only diagnostic) was dragging a real Task 1 Band 9 down to 4.5. Now only averages when Task 2 is actually configured for that mock.
  - `gradeMockEssay()` was calling `gemini-2.0-flash`, which now 404s (deprecated) — **this was silently breaking Writing AI-grading for the Full Mock tests too**, not just the diagnostic. Updated to `gemini-3.6-flash`.

**Rollback:**
```sql
DELETE FROM questions WHERE test_id IN (SELECT id FROM tests WHERE code LIKE 'IELTS_ACA_DIAG%');
DELETE FROM tests WHERE code LIKE 'IELTS_ACA_DIAG%';
DELETE FROM mock_exams WHERE code = 'IELTS_ACA_DIAGNOSTIC';
```

---

## 064 — Replace diagnostic Writing Task 1 placeholder with real prompt + chart image

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-07 | Question row updated, chart renders on diagnostic_aca_writing.php |
| Live  | [ ] | | |

**What it does:**
- Updates the `IELTS_ACA_DIAG_W` Task 1 question row seeded in 063 (which described a bar chart in text, since no image asset existed yet) with the real Cambridge Academic Task 1A prompt and its actual chart image, now on disk at `assets/img/mock_tests/IELTS_ACA_DIAGNOSTIC/writing_task1_chart.png`. The image path is stored in `questions.instructions`, the same convention `mock_writing.php` already used for Full Mock Task 1 visuals.
- `diagnostic_aca_writing.php` now reads that path and renders the chart next to the prompt.
- `slslanguage.com/sls-admin/mock_session_detail.php`'s Writing review modal now renders the same chart image (it was already selecting `instructions AS visual_note` but never displaying it) so instructors grading Task 1 can see the chart, not just the text prompt.

**Rollback:**
```sql
-- Re-run migration 063's original Writing Task 1 INSERT values (text-described chart),
-- or restore question_text/instructions from a pre-064 backup.
```

---

## 065 — Fix IELTS Academic Diagnostic Listening: use real Full Mock 1 content, not the old page's

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-08 | Verified: 10 questions, 13 answer rows (3 with a spelling/case alternative), matches FM1 Part 1 exactly |
| Live  | [ ] | | Run after 063 (idempotent) and 064 |

**What it does:**
- Migration 063 wired the diagnostic's Listening section to `diagnostic_IELTS.php`'s old hardcoded audio + questions — content that was never verified against real IELTS material, just carried over during the architecture rebuild. The actual instruction was to reuse a Full Mock's audio + written content, since the Full Mocks (1-4) are already confirmed correct.
- Deletes whatever Listening content exists for `IELTS_ACA_DIAG_L` (the old page's Q1-10 form/matching content locally; nothing on live, since 063 never got past its first `INSERT` there) and reseeds it from `IELTS_FM1_L` Part 1 — "Children's Engineering Workshops", 10 note-completion questions, real Cambridge content, already used and confirmed correct by the Full Mock 1 listening test.
- Swapped `assets/audio/IELTS_ACA_DIAGNOSTIC/listening_part1.mp3` for a copy of `assets/audio/IELTS_FULL_MOCK_001/listening_part1.mp3` (checksum-verified identical). `diagnostic_aca_listening.php` needed no code change — same file path, same page template (plain form/note completion, no matching UI needed now).

**Rollback:**
```sql
DELETE FROM question_correct_answers WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L'));
DELETE FROM question_options WHERE question_id IN (SELECT id FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L'));
DELETE FROM questions WHERE test_id = (SELECT id FROM tests WHERE code = 'IELTS_ACA_DIAG_L');
-- then re-run migration 063's original (now-superseded) Listening INSERTs if reverting to the old page's content.
```

---

## 066 — Add note-group sub-headings to Listening Part 1 ("Children's Engineering Workshops")

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [x] | 2026-09-08 | Verified: stimulus_text updated on Q1/Q4/Q8 for both IELTS_FM1_L and IELTS_ACA_DIAG_L |
| Live  | [ ] | | |

**What it does:**
- Redesign of the Part 1 note-completion layout on both `full_mock_001_listening.php` and `diagnostic_aca_listening.php`. Previously the whole 10-question form ("Children's Engineering Workshops") rendered as one flat, undifferentiated block under a single title — the real form has three natural groups (Tiny Engineers activities Q1-3, Junior Engineers activities Q4-7, logistics Q8-10) that weren't visually distinguished.
- Template change: `stimulus_text` now supports a `"Main Title||Sub Heading"` convention — the first `||`-part renders as the existing boxed `.ff-title` only on the section's first stimulus, everything else (including later stimulus_text changes) renders as a new left-accented `.ff-subtitle`. `full_mock_001_listening.php`'s stimulus block was also scoped to `qtype === 'form_note_completion'` to match a fix `diagnostic_aca_listening.php` already had (matching questions carry stimulus_text too and would otherwise double up on the title box).
- This migration just populates the data side: Q1's stimulus_text becomes `"Children's Engineering Workshops||Tiny Engineers"`, Q4 becomes `"Junior Engineers"`, Q8 becomes `"Additional information"` — no other template changes needed to get grouped, indented notes instead of a flat list.
- Also made `.audio-box` `position: sticky` (top offset matches `.section-content`'s existing padding-top) on both pages, so the audio player stays reachable while scrolling through the blanks instead of scrolling out of view — same complaint pattern as the Writing Task 1 image-scroll issue.

**Rollback:**
```sql
UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.stimulus_text = 'Children''s Engineering Workshops'
WHERE t.code IN ('IELTS_FM1_L', 'IELTS_ACA_DIAG_L') AND q.question_number = 1;
UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.stimulus_text = NULL
WHERE t.code IN ('IELTS_FM1_L', 'IELTS_ACA_DIAG_L') AND q.question_number IN (4, 8);
```

---

## Rules

- Never run a migration on LIVE without running it on LOCAL first.
- Always take a DB backup on LIVE before running any migration.
- If a migration fails halfway on LIVE, run the rollback SQL, then restore from backup.
- Mark the checkbox and date immediately after running — don't do it later from memory.
