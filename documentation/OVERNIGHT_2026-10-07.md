# Overnight work — 2026-10-07 → 08

Founder's instruction: a new mobile app spec (v0.3) arrived with many new screens. Before building
any of them on mobile, build the matching feature on the web first (G5: mobile reuses the existing
backend and content). "Create a list and go through it." Everything below was built, tested end to
end with throwaway data (always cleaned up afterward), committed, and pushed to `main` on both
`academy` and (for the mobile pieces) committed locally in `sls_app`. Nothing was run on live.

Full plan and checklist: `documentation/WEB_FIRST_FOR_MOBILE_SPEC_v0.3.md`.

## What was actually missing, found by reading the real code (not assumed from the spec)

`mentors.php`, `students.php` were static "Coming Soon" pages. `events.php` was one hand-written
HTML event card. The web's "Messages" was always `broadcast_messages` -- a one-way admin
announcement feed, never a real conversation. Vocabulary banks, Quizzes, Exercises, Model answers
and Study materials, by contrast, were already real and just needed an API wrapper.

## Built tonight (academy, pushed to main)

| # | Commit | What |
|---|---|---|
| 1 | `f427dd4` | **Mentors**: migration 142, real `mentors.php` + new `mentor_book.php` (day/time slot picker, double-booking rejected server-side). Seeded with the one real instructor (Victor) -- not the spec's fictional "Coach Scholar/Vee". |
| 2 | `dfb425c` | **Students**: migration 143 (`last_seen_at`), real `students.php` classmates list with online status and search. |
| 3 | `2193b80` | **Events**: migration 144, real `events.php` (upcoming/past, Join/Going registration). The old hardcoded Gen-Z event is now a real seeded row. |
| 4 | `c512938` | **Messages**: migration 145, new `threads.php` + `thread_view.php` -- real two-way coach conversations. `navbar.php` relabelled: the old "Messages" is now "Notifications" (same page, same badge wiring), the new item is the real Messages. |
| 5 | `5d6dcc9` | **Notifications**: new `notifications.php` -- a real unified feed (announcements + unread messages + assignments due soon + events starting soon), Mark all as read. |
| 6 | `0612999` | **API layer**: `academy/api/v1/*.php` -- flat files (no URL rewriting exists on this site), token-authenticated, for everything above: classmates, mentors + mentor_requests, events + event_registrations, threads + thread_messages + thread_read. Added `academyUtcExpr()` to `mobile_auth.php`, because MySQL's own clock here runs SYSTEM time, not UTC, while PHP is UTC -- every timestamp these endpoints return is now genuinely normalized to UTC, not silently off by the live offset. |

Migrations 142-145 are LOCAL ONLY. Each was run twice on local 8.0 and twice on a throwaway MySQL
5.7.44 instance (confirmed idempotent both times) before being committed. **None have been run on
live.** Run them in order (142, 143, 144, 145) after pulling.

## Not built, and why

- **Study time / streak tracking (section 12.4 / Analytics)**: `students_activity` is a real table
  but has zero rows, because nothing writes to `lesson_progress` either -- lesson completion
  tracking (video watch time + 70% on exercises) was already flagged as "not built" before tonight.
  This is its own feature, not a side effect of tonight's work.
- **Assignments, results summary, resources (vocab/quizzes/exercises/model answers), analytics**:
  all real on the web already; no `/v1` wrapper yet. Lower urgency than the four built tonight,
  since the mobile screens for these already degrade gracefully (section 13 error states).
- **Mentor admin UI**: the founder adds more mentors by inserting rows directly for now.
- **Group threads** (a class cohort chat, MSG-05): coach threads only.

## Built tonight (sls_app, committed locally -- no remote configured for this repo)

| Commit | What |
|---|---|
| `3785d98` | `api.ts` now calls the real `/v1` endpoints above (classmates, mentors, events, threads) instead of the spec's aspirational nested REST paths. No screen changes needed -- the TypeScript types didn't change. |
| `b94f515` | **Resources rebuilt** to match the spec's fixed 5-row hub (was a generic file list) -- Vocabulary banks, Quizzes, Exercises, Model answers, Study materials, with the D11 web-only note. None of the four content screens are native yet, so each row opens the real web page, signed in automatically via `api/mobile_session.php` (the token-to-session bridge built for the old WebView wrapper, reused rather than duplicated). |
| `5e9c217` | D11 compliance: Home's empty progress ring now says "No mock yet" (not "Take a mock test"), My results' empty state drops its action button. |

No new APK was built tonight -- the last one (with the goal-date feature) is still the current
build; these changes need a rebuild before they can be tested on a device.

## What's left from spec v0.3 (not started)

Course detail redesign (week accordion, Up next card) and the real Lesson screen (the actual
course player -- Home's "Continue" currently still opens the old course outline), Assignment
detail, Result detail, Mentor profile and booking (the app's Mentors screen still does a one-tap
request with no day/time picker -- the web's is far richer), Event detail, Sign up/Check your
email/Forgot password (still "coming soon" toasts), Settings (appearance/notifications/privacy/
delete account). All still `[NOT DESIGNED]`-adjacent or fully unbuilt on mobile; web counterparts
for Assignment/Result detail already have real data (`assignments.php`/`test_attempts`) waiting
for a `/v1` wrapper.

## Verification note

Everything above was tested with throwaway students/mentors/events/threads created and deleted in
the same session -- login via the real `mobile_login.php`, real tokens, real HTTP requests against
the local server, not just reading the code. Specific checks are in each commit message.
