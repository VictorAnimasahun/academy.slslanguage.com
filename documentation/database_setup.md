# Database Setup & Migration Guide

This document covers the academy database structure, how migrations work, known quirks,
and the exact process for keeping local and live databases in sync.

---

## Environments

| Environment | Host | DB Access | MySQL Version |
|---|---|---|---|
| Local | MAMP on Mac (localhost:8888) | phpMyAdmin at localhost:8888/phpMyAdmin | 5.7.x (older — see Quirks) |
| Live | cPanel shared hosting | phpMyAdmin via cPanel | 8.0.x (newer) |

Code is deployed: **local → GitHub → `git pull` on live server terminal.**
Database changes are deployed: **local phpMyAdmin → live phpMyAdmin** (manually, using migration files).

---

## Folder Structure

```
academy/
└── database/
    └── migrations/
        ├── 001_create_subscriptions_table.sql   ← run in order
        ├── 001_rollback.sql                     ← undo 001
        ├── 002_add_min_tier_to_lessons.sql
        ├── 002_rollback.sql
        ├── 003_add_min_tier_to_modules.sql
        ├── 003_rollback.sql
        └── migration_log.md                     ← tracks what ran where & when
```

Every schema change gets its own numbered migration file and a matching rollback file.
Migration files travel with the Git repo — when you `git pull` on live, the files are already there.

---

## How to Run a Migration

### Local
1. Open phpMyAdmin → select the academy DB → SQL tab
2. Copy the SQL from the migration file (skip the comment lines if they cause issues)
3. Run it
4. Run the verify query at the bottom of the file to confirm
5. Tick the Local checkbox in `migration_log.md`

### Live
1. **Take a full DB backup first** — phpMyAdmin → Export → Quick → SQL → Download
2. Open live phpMyAdmin → SQL tab
3. Run the same SQL
4. Run the verify query to confirm
5. Tick the Live checkbox in `migration_log.md`
6. Commit and push the updated log

### If something goes wrong on Live
1. Run the matching `_rollback.sql` file in live phpMyAdmin
2. If the rollback also fails, restore from the backup export you took in step 1
3. Fix the issue locally, re-test, then retry the live migration

---

## Current Schema Overview

### Core tables (pre-existing)

| Table | Purpose | Key columns |
|---|---|---|
| `students` | All registered users | id, firstname, lastname, email, password, is_verified |
| `courses` | Course catalogue | id, title, folder_name, price, is_free |
| `modules` | Groups of lessons per course | id, course_id, module_title, module_order, **min_tier** |
| `lessons` | Individual lesson items | id, course_id, module_id, title, content, video_url, file_path, **min_tier** |
| `enrollments` | Which student enrolled in which course | student_id, course_id, progress_percentage |
| `tests` | Assessment/mock test containers | id, code, title, test_type, duration_minutes |
| `questions` | Questions linked to tests | id, test_id, question_type, question_text |
| `mock_exams` | Full mock exam definitions | id, code, exam_type, title |
| `messages` | Contact/inbox messages | id, name, email, message, status |
| `broadcast_messages` | Platform-wide announcements | id, title, content, target_* |

### Added by migrations

| Table / Column | Migration | Purpose |
|---|---|---|
| `subscriptions` table | 001 | Tracks each student's tier, payment, and expiry |
| `lessons.min_tier` | 002 | Locks individual lessons behind a minimum tier |
| `modules.min_tier` | 003 | Locks entire modules behind a minimum tier |

### Subscription tiers (numeric hierarchy for access checks)

| Tier name | Level | Subscription | Payment |
|---|---|---|---|
| beginner | 1 | Free | None |
| intermediate | 2 | 1 month | PayPal |
| advanced | 3 | 2 months | PayPal |
| fluent | 4 | 3 months | PayPal |

Access is granted when: `student_tier_level >= content_min_tier_level`

---

## Known Quirks

### Local MySQL does not support `ADD COLUMN IF NOT EXISTS`

**Symptom:** `#1064` syntax error when running ALTER TABLE migrations locally.

**Cause:** MAMP ships with MySQL 5.7.x. `ADD COLUMN IF NOT EXISTS` in ALTER TABLE
was added in MySQL 8.0.3. The live cPanel server runs MySQL 8.0.x so it works there.

**Fix:** When writing migration files, do NOT use `IF NOT EXISTS` in ALTER TABLE statements.
Use plain `ADD COLUMN` and only run each migration once per environment.
`CREATE TABLE IF NOT EXISTS` (for new tables) is fine — that syntax is supported in all versions.

**Discovered:** May 2026, migrations 002 and 003.

---

## Dependency Map

```
students ──────────────────────────────────────┐
    │                                           │
    ├── subscriptions (tier, expiry, status)    │  ← added by migration 001
    │                                           │
    └── enrollments ──── courses               │
                             │                  │
                             ├── modules        │
                             │     └── lessons ─┘
                             │
                             └── tests ── questions ── question_options
                                   └── mock_exams ── mock_exam_sections
```

---

## Tier Access Helper (PHP)

**File:** `academy/includes/tier_access.php`

Include this on any page that needs to gate content. It requires `bootstrap.php` to already be loaded.

```php
require_once INCLUDES_PATH . '/tier_access.php';

// Check access — pass the min_tier value for that content item
if (!can_access('advanced')) {
    render_upgrade_prompt('advanced', 'this video lesson');
    exit;
}

// ... render the actual content below
```

### Available functions

| Function | Returns | Description |
|---|---|---|
| `get_student_tier()` | string | Current student's tier name (e.g. `'advanced'`) |
| `get_student_tier_level()` | int (1–4) | Numeric tier level for comparisons |
| `can_access($min_tier)` | bool | True if student meets or exceeds required tier |
| `render_upgrade_prompt($tier, $label)` | void | Renders a locked-content card with upgrade CTA |

### How the access query works
- Looks up `subscriptions` for the logged-in student
- `status = 'active'` AND (`expiry_date IS NULL` OR within 3-day grace period)
- If no valid subscription found → falls back to `beginner`
- If multiple subscriptions exist → highest tier wins

---

## Adding a New Migration

1. Create `NNN_description.sql` and `NNN_rollback.sql` in `database/migrations/`
2. Number sequentially (next is `004_...`)
3. Include verify queries as comments at the bottom
4. Do NOT use `ADD COLUMN IF NOT EXISTS` — use plain `ADD COLUMN`
5. Test on local first, then live
6. Update `migration_log.md` with dates applied
7. Commit both the migration file and the updated log
