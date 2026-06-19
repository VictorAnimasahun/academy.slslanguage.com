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
| Local | [ ] | | |
| Live  | [ ] | | |

**What it does:** Inserts test container rows for the three graded sections of Full Mock 001.

---

## 016 — Seed IELTS_FM1_L questions (Listening Full Mock 1)

| Environment | Applied | Date | Notes |
|---|---|---|---|
| Local | [ ] | | |
| Live  | [ ] | | |

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
| Local | [ ] | | |
| Live  | [ ] | | |

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
| Local | [ ] | | |
| Live  | [ ] | | |

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

## Rules

- Never run a migration on LIVE without running it on LOCAL first.
- Always take a DB backup on LIVE before running any migration.
- If a migration fails halfway on LIVE, run the rollback SQL, then restore from backup.
- Mark the checkbox and date immediately after running — don't do it later from memory.
