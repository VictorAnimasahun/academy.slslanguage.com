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
| Local | [ ] | | |
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
| Local | [ ] | | |
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
| Local | [ ] | | |
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

## Rules

- Never run a migration on LIVE without running it on LOCAL first.
- Always take a DB backup on LIVE before running any migration.
- If a migration fails halfway on LIVE, run the rollback SQL, then restore from backup.
- Mark the checkbox and date immediately after running — don't do it later from memory.
