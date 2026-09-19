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

## Week Briefs (per-week summary, vocab, resources, tests) -- site-wide

Every course "week" is a `modules` row. A **Week Brief** hangs optional content off it: what the week covers, the vocab list, exercises/readings, and the tests due -- with a "rest easy, you have no tests this week" / "buckle up, it's raining tests" line based on the count. Works for **every course that has modules**; nothing is course-specific.

- **Schema (migration 100):** `week_briefs` (summary), `week_resources` (typed items, `planned` = shown as "Coming soon", or `ready` + link), `week_vocab_words` (words picked from `vocabulary_words`). No foreign keys (live rejected them).
- **Logic:** `includes/week_brief.php` -- `weekBriefLoad/Render/Text`, plus site-wide helpers: `weekModules`, `weekCurrentModuleId` (first week with an incomplete lesson via `lesson_progress`), `weekIntroUrl`, `weekBriefButton`, `renderWeekPanel`, and `weekIsUnlocked()` (see below). Tests already attached via `course_pacing_items` are counted automatically; don't re-enter them.
- **Week introduction page:** `courses/week.php?module=ID` -- one generic page for all courses (course derived from the module). Sections: summary, classes (with lock/complete state), tests + tone line, full vocab sheet (meaning + collocations), resources, week switcher and prev/next.
- **Right pane:** in every course overview (`courses/*/course_overview.php`, 13 of them) the old static "Quick Access" box was replaced by `renderWeekPanel(...)`: the week the student is currently in, abridged, class links kept, and a link under each line to the matching section of the week page. `week.php` shows the same panel for the week being viewed. Each accordion week also has a "Week introduction →" button.
- **Not covered:** overviews with their own older layout -- `IELTS_Aca_Crash`, and `BEL`/`CELPIP_intro`/`IELTS_intro` (no modules) -- and `IELTS_Aca_Mst` got the accordion button only (no advert pane).
- **Week locking (planned):** "finish last week to unlock the next" is a single hook, `weekIsUnlocked($db,$courseId,$studentId,$moduleId)` in `week_brief.php`; it returns `true` today. `week.php` already redirects when it returns false; the panel/accordion can grey out locked weeks then.
- **Authoring:** sls-admin -> Content -> **Week Briefs** (`sls-admin/week_briefs.php`): course -> week -> summary, tick vocab, add placeholder/ready resources; live preview + plain-text version.
- **Not built yet:** the scheduled "day before the week starts" message/email. The body exists (`weekBriefText`); missing: a cron on live and each student's week-start date (enrollment + pacing offsets).
