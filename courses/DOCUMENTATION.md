# Courses — Subsystem Documentation

Parent: [`../documentation/SITE_MAP.md`](../documentation/SITE_MAP.md)

Folder-per-course structure. Each subfolder is one sellable course package; lesson content for the IELTS General track lives in a **shared lesson bank** (see below) rather than being duplicated per package.

## Catalog pages (loose files at this level, not inside a course folder)

| File | Purpose |
|---|---|
| `courses_catalogue.php` | Browse/search all courses, filter free vs. paid, shows enrollment status |
| `courses_detail.php` | Single course detail page (description, instructor, pricing, enroll CTA) |

## Course packages

| Folder | Track |
|---|---|
| `IELTS_Gen_Mst/` | IELTS General — full 3-month Masterclass |
| `IELTS_Gen_1Mo/` | IELTS General — 1-Month Starter (subset of Masterclass content) |
| `IELTS_Gen_2Mo/` | IELTS General — 2-Month Intensive (subset of Masterclass content) |
| `IELTS_Gen/` | Shared **lesson bank** for the General track — see below, not a sellable package itself |
| `IELTS_intro/` | IELTS Introduction (own self-contained lessons, not shared bank) |
| `IELTS_Aca_1Mo/`, `IELTS_Aca_2Mo/`, `IELTS_Aca_3Mo/`, `IELTS_Aca_Crash/`, `IELTS_Aca_Mst.` ⚠️ | IELTS Academic tracks at various durations |
| `CELPIP_intro/`, `CELPIP_Gen_1Mo/`, `CELPIP_Gen_2Mo/`, `CELPIP_Gen_3Mo/` | CELPIP tracks |
| `PTE_Gen_1Mo/`, `PTE_Gen_2Mo/`, `PTE_Gen_3Mo/` | PTE tracks |
| `BEL/` | Basic English Language (lowest tier, often free/entry-level) |

⚠️ **`IELTS_Aca_Mst.`** has a trailing period in its actual folder name on disk — almost certainly an unintentional typo from when it was created. Don't "fix" it casually; anything (DB `file_path` columns, includes) currently pointing at this folder expects the period. Worth a deliberate cleanup pass (rename + update all references) rather than an accidental fix.

## The shared lesson bank pattern (`IELTS_Gen/`)

`IELTS_Gen_Mst`, `IELTS_Gen_1Mo`, and `IELTS_Gen_2Mo` are three different **subscription packages** sold at different durations/prices, but they all render lessons from the same physical files in `IELTS_Gen/lessons/`. The `lessons` DB table's `file_path` column points every package's lesson rows at `courses/IELTS_Gen/lessons/classNN.php` regardless of which package the student bought — only `min_tier` differs per package/lesson row, gating how far a given subscription tier can progress. This was a deliberate restructuring (migration 006) specifically to avoid maintaining 3 copies of the same lesson content. **If you edit lesson content, edit it once in `IELTS_Gen/lessons/`** — do not fork copies into the `_1Mo`/`_2Mo`/`_Mst` folders.

## Lesson file naming & anatomy

- `intro.php`, `course_context.php` — intro/context loader files
- `classNN.php` (NN = 02–24) — numbered lesson content

Every lesson file follows the same boilerplate: session/auth check → tier access check (`includes/tier_access.php`, e.g. `can_access('intermediate')`) → course context load → navbar/footer includes → lesson HTML.

## Tier gating

Tiers: `beginner` → `intermediate` → `advanced` → `fluent`, defined and checked in `includes/tier_access.php` (includes a grace-period allowance). `min_tier` is set per-module and per-lesson in the DB (migrations 002, 003, 006) — a student whose subscription tier is below a lesson's `min_tier` is blocked from it.

## Adding lesson content

Per project convention: use `php artisan make:...`-equivalent discipline even though this is plain PHP — i.e. don't hand-create DB rows. New courses/lessons are seeded via numbered migration files in `database/migrations/`, following the pattern of migrations 004–008.

## Week Briefs (per-week summary, vocab, resources, tests)

Every course "week" is a `modules` row. A **Week Brief** hangs optional content off it so students see what the week covers, the vocab list, exercises/readings, and the tests due -- with a "rest easy, you have no tests this week" / "buckle up, it's raining tests" line based on the count.

- **Schema (migration 100):** `week_briefs` (summary), `week_resources` (typed items, each `planned` = shown as "Coming soon" or `ready` + link), `week_vocab_words` (words picked from `vocabulary_words`).
- **Logic/render:** `includes/week_brief.php` -- `weekBriefLoad()`, `weekBriefRender()`, `weekBriefText()`. Tests already attached via `course_pacing_items` to that week's lessons are counted automatically; don't re-enter them.
- **Authoring:** sls-admin -> Content -> **Week Briefs** (`sls-admin/week_briefs.php`): pick course -> week -> summary, tick vocab words, add placeholder/ready resources. Includes a live student preview and the plain-text version.
- **Student view:** rendered at the top of each week's accordion in `courses/CELPIP_Gen_3Mo/course_overview.php`. **To enable on another course:** `require_once INCLUDES_PATH . '/week_brief.php';`, keep each module's `id` in the overview's `$modules` array, and echo `weekBriefRender(weekBriefLoad($db, $module['id']), $color)` above the lesson list. Empty weeks render cleanly, so it is safe to turn on before any content exists.
- **Not built yet:** the scheduled "day before the week starts" message/email. The plain-text body already exists (`weekBriefText`); what's missing is a cron job on live and each student's week-start date (enrollment date + `course_pacing` offsets). Nothing on live runs on a timer today.
