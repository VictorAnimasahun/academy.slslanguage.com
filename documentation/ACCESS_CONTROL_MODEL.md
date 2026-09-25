# Access Control Model

The authoritative reference for who gets what access on this platform. Stated directly by the instructor, 2026-09-24 — don't ask again, read this first.

## The four roles

| Role | Who | Course access | Exam-timing bypass (skip/untimed/PREVIOUS) |
|---|---|---|---|
| **Admin** | Exactly one person: `v.animasahun@slslanguage.com` | Everything | Yes |
| **Staff** | Anyone else with an official `@slslanguage.com` email address, granted a `staff_accounts` row | Everything | Yes |
| **Tester** | Named accounts flagged `students.is_tester = 1` | Everything | **No** |
| **Everyone else** | A regular student, no flags | Only courses named "Introduction" (e.g. `CELPIP_intro`, `IELTS_intro`, `BEL`) — nothing else, unless they hold a real paid subscription tier that covers a specific course | No |

**Testers are not admin and not staff.** They get full course content access (same as admin/staff, so the instructor can watch real features work daily) but they do NOT get exam-taking shortcuts — no skipping ahead, no ignoring timers, no admin-only PREVIOUS button. That distinction is the one most likely to be implemented wrong, because both behaviors currently flow through the same helper function (see "Known gap" below) — check that a change hasn't quietly given testers admin exam privileges (or taken away staff's) before touching this code.

## How it's implemented

- `includes/admin_check.php` — the single source of truth for detection:
  - `is_staff_account()`: true if there's a `staff_accounts` row for this student AND their email is verified. `staff_accounts.role` is `'admin'` or `'staff'` (informational only right now — both roles get identical access, since admin and staff behave the same everywhere except that there's only ever one admin).
  - `is_access_tester()`: true if `students.is_tester = 1`.
  - `is_platform_admin()`: `is_staff_account() || is_access_tester()` — **this conflates "gets full course access" with "gets exam-timing bypass too."** That's correct for course-tier gating (`includes/tier_access.php`) but WRONG for exam-timing bypass — see below.
- `includes/tier_access.php` — `get_student_tier_level()` grants the top tier (`fluent`) to anyone `is_platform_admin()` returns true for. Correct: this is course *access*, and testers should get it.
- Exam-timing bypass (skip controls, untimed mode, the admin-only PREVIOUS button, jumping straight to a later section without completing earlier ones) gates on `is_staff_account()` — admin + staff only, NOT testers. **Fixed 2026-09-24** (previously used `is_platform_admin()`, which wrongly included testers) in `courses/CELPIP_Gen/lessons/celpip_listening_001.php`, `resources/practice_tests/celpip_listening_002.php`, `celpip_listening_003.php`, `celpip_listening_004.php`, `celpip_speaking_001.php`, `resources/practice_tests/ielts_listening_001.php`, `resources/mock_tests/celpip_full_mock_speaking.php`. Verified via real HTTP with a tester and a staff account: tester gets `IS_ADMIN=false` (real content, no skip controls, and is correctly forced through the normal Listening→Reading→Writing→Speaking sequence on a Full Mock) while staff still gets `IS_ADMIN=true`.

## Target model at cutover (instructor, 2026-09-25)

The tier system below is EduHub's *current* behavior and stays until `academy-laravel` replaces it. The model it is being replaced with: **buy a course and you own it for its duration plus one month.** Introduction courses are free; admin/staff and testers keep the access in the table above. There are no month-block tiers in the new system — each purchase creates an entitlement (student, course, start, end = start + course length + 1 month), and at cutover every active tier subscription is converted into entitlements. Full requirement list: `academy-laravel/docs/spec/REQUIREMENTS.md` (L3).

## Course-length tiers (EduHub today)

Separate from the role model above, but related: a course's own duration name IS the subscription tier required to unlock it *past* its free Week-1 preview. `TIER_LABELS` (`includes/tier_access.php`): `intermediate` = 1 month, `advanced` = 2 months, `fluent` = 3 months. A 3-month course splits into three 4-week blocks, each gated one tier higher than the last (see migration 122's write-up in `migration_log.md` for the CELPIP fix, and `IELTS_Aca_2Mo`/`IELTS_Aca_3Mo` for the original correct reference pattern). This is unrelated to admin/staff/tester — it's what a *paying, non-privileged* student's access looks like.

## FREE_ACCESS_FOR_ALL

`includes/tier_access.php`'s `FREE_ACCESS_FOR_ALL` constant, when `true`, overrides all of the above and gives every logged-in student full access regardless of role or tier. It was a deliberate temporary testing phase (2026-09-15 to 2026-09-24) and is now `false` — real gating, per this document, is active. Don't flip it back to `true` without asking; if a future testing phase needs it, that's a real, explicit decision each time, not a default.

## Where NOT to look for this rule

There is no email-domain-text check (`str_ends_with($email, '@slslanguage.com')` or similar) anywhere in the codebase for granting access — that was the *old* way, replaced by `staff_accounts` (migration 104) specifically because email text proves nothing on its own. If you ever find a stray hardcoded email check granting admin/staff behavior, it's a bug — remove it and make sure the affected account has a real `staff_accounts` row instead.
