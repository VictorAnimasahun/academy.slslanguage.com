# Overnight work — 2026-09-26 → 27

Everything below was done while the founder slept. **Nothing was put on `main`** (the live server pulls `main`) and **nothing was run on live**. Each app has its own branch named `overnight-2026-09-26-*`; to accept a branch, merge it yourself. Nothing here has been seen by a student.

## What is where

| App | Branch | What changed | Tested how |
|---|---|---|---|
| `academy` | `overnight-2026-09-26-content` | Cambridge 17 Academic Reading Tests 1/3/4 + Writing Tests 2–4 (migrations **135, 136**); IELTS General Training Reading 2–5 + Writing 2–5, wired into the General Masterclass classes 20–23 (migrations **137, 138**); four "Test-Day" pieces corrected from test to lesson (**139**); IELTS Listening Tests 3–4 from Cambridge 17 Tests 1–2, built and checked but **not wired** until the recordings exist (**140**); **81 AI-drafted lesson pages** (IELTS Academic 1/2/3-Month, PTE 1/2/3-Month) | Local DB + a throwaway MySQL 5.7 copy, each migration run twice; pages opened as a temp verified student; answer-key grading run in node; lesson pages rendered as an enrolled temp student |
| `academy-laravel` | `overnight-2026-09-26-laravel` | **L3 access & entitlements**: `users.role`, `entitlements` table, one `CourseAccess` service, enrol and "mark done" gated, course page shows locked classes, 20 access-matrix tests (all 45 tests pass) | Pest |
| `blog.slslanguage.com` | `overnight-2026-09-26-blog` | Fixed the **editor role** (the admin screen assigns "editor" but the gate only let "admin"/"author" in, so an editor was locked out); fixed a **500 on public posts with no category**; brought 4 stale tests up to date | Pest: 50 pass (was 43 pass / 4 fail) |
| `sls_mobile` | `overnight-2026-09-26-mobile` | A failed page load now retries by itself when the app comes back to the foreground | Lint clean; **not tried on a device** |
| `word_master`, `slslanguage.com`, `form_backend` | — | Read-only look, nothing changed (see findings) | — |

### Also in `academy-laravel` (same branch): course structure, L4
- `php artisan academy:import-courses` copies courses, weeks, classes and pieces from EduHub (read-only, same ids, repeatable; needs `EDUHUB_DB_*` in `.env`). It never deletes anything that holds student data. Run against a copy of the local EduHub database: 15 courses / 96 weeks / 192 classes / 295 pieces imported.
- `php artisan course:audit [folder]` checks the structure rules (4 x months weeks, 2 classes a week, mocks = months, every class has pieces, kind agrees with title). On the imported real data: **0 errors, 0 warnings** for all 12 plans. Found while building it: EduHub allows NULL where Laravel does not (category, price...) and the importer handles that.
- 58 tests pass.

### Also in `academy-laravel`: the admin panel (Filament, L9) and roles (IDN-06)
- Filament 4 installed at `/admin`: only **verified staff and the admin** get in (students, testers and unverified staff get 403). Screens: **Entitlements** (grant with an automatic end date, extend, revoke: no edit, no delete), **Courses** (admin edits price / availability / free-preview classes / buy link; staff can look; structure comes only from the import), **Accounts** (admin sets role and tester flag).
- Roles change only through `AccountChanges`: admin-only, exactly one admin, staff must be a verified @slslanguage.com address, every change logged in `account_changes`. The very first admin is named at the server console with `php artisan academy:make-admin <email>` (refuses if an admin exists).
- 75 tests pass. **Nothing here has been opened in a browser** — only tested through Livewire/HTTP tests; look at `/admin` on your first run (needs `php artisan migrate`, then `academy:make-admin`, then log in at `/admin/login`).

## To put the academy content live (you, in this order)

1. Merge `overnight-2026-09-26-content` into `main` in `academy`, then on live: pull.
2. Live needs 126, 128 and 134 first (134 is still pending on live from earlier).
3. Run **135, 136, 137, 138, 139, 140** in that order (140 = keys for the new IELTS Listening Tests 3 and 4 from Cambridge 17 Tests 1-2; the pages exist and are checked, but they are **not wired into any class** until you add the recordings `assets/audio/IELTS_PT_L_003|004/part1..4.mp4`) (139 makes the four "Test-Day" pieces lessons, not tests, each with its own page; it also settles half of the one-test-class question: the PTE Class 7s hold a lesson, not a test). Each is safe to run twice.
4. Review before students see them: the four new Reading tests (Reading Test 2 is already live), the Academic Writing **figures** (redrawn by hand from the book; check them against the original), and the lesson drafts.

### The lesson drafts (81 pages)
Every page carries a hidden HTML comment `DRAFT: written by Claude on 2026-09-26 ... Not yet checked`. They only filled pages that were completely empty; nothing that already had text was touched. Generator and source text: `academy/tools/lesson_drafts/` (`apply.py` is safe to re-run: it never overwrites a page that has content). The PTE ones follow the PTE Academic task list from memory of the public test format; **check the timings and word limits against Pearson's current guide before students rely on them**. The four "Mastery" and two "Simulation" PTE pages are the thinnest.

## Findings (nothing done about these — your call)

1. **`form_backend` tracks its database login in git** (`config.php`, `admin/config.php`). Same kind of issue as the `config` repo. Not touched.
2. **`slslanguage.com` has no `server.js`** (it was removed on purpose earlier), so Express/Multer in its `package.json` are unused, and `npm audit` reports 4 vulnerabilities in them (2 high). Nothing runs them. CLAUDE.md still describes it as a Node app — out of date.
3. **`academy-laravel` does have a GitHub remote** (`VictorAnimasahun/academy-laravel`); CLAUDE.md says "no remote". The branch was pushed there.
4. `sls_mobile`: a real offline banner needs a new package (NetInfo) — not added because it can't be checked without a device. The AI-assistant screen needs a backend endpoint and an API key decision.
5. **Still open from before:** the one-piece classes (PTE 1/2/3-Month class 7 is now one lesson; IELTS General 3-Month class 23 is one test); the credentials in the `config` repo history; Cambridge 17 Test 1 Writing (maps) not wired.

## What the L3 work does and does not cover

Done and tested: ACC-01…05 (intro, ownership for length + 1 month, staff, tester without exam-rule bypass, expired = locked), ACC-10's "replay is idempotent" half, ACC-11's grant/extend/revoke as code.
Not done: media/audio URL protection (ACC-06), the Selar claim-and-choose flow, staff screens for grants (Filament, L9), a change log. Lesson pages themselves do not exist in the new app yet, so "access to a class" is enforced on the course page, enrol and progress only.

