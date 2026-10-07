# Building spec v0.3's new screens: web first, then mobile

Founder's instruction (2026-10-07 night): before building any of spec v0.3's new mobile screens,
build the matching feature on the web platform first (G5: mobile reuses the existing backend and
content, it does not duplicate it). This file is the checklist, audited against the real database
and the real `academy/` pages before writing it — not assumed from the spec alone.

## What's already real on the web (just needs an API wrapper for mobile)

| Spec area | Web reality |
|---|---|
| Resources: Vocabulary banks + Word | `resources/vocabulary_banks/` -- real, `vocabulary_words`/`word_test_usages`/`week_vocab_words` tables (36 words) |
| Resources: Quizzes + Quiz player | `resources/quizzes/` -- 6 real quiz pages (IELTS Listening, IELTS Mastery, Maps, Process, class quiz) |
| Resources: Exercises | `resources/exercises/` -- 6 real pages, including `sentence_sequencing.php` (spec's own pattern exercise) and `letter_parts.php` (= Letter Parts Sorter), `data_detective.php`, `build_your_evidence.php`, `thin_to_thick.php` |
| Resources: Model answers | `resources/model_answers/` -- real, Cambridge scripts wired in |
| Resources: Study materials | `resources/study_materials/` -- real, index + view |
| Assignments | `assignments.php` -- real, joins `assignments` + `test_attempts`, has `includes/assignment_helpers.php` |
| My results / Analytics (test-score side) | `analytics.php` -- real, built on `test_attempts` |
| Course structure, lessons | `lessons`/`lesson_parts`/`modules` -- real, already API'd (`mobile_courses.php`/`mobile_course.php`) |
| Goal (exam/target band/test date) | Migration 141 + `mobile_update_goal.php` -- done tonight |

None of the above need new web features. They need a thin JSON API layer (section below) and, for
Resources, honouring D11 by keeping tests/analysers off that layer entirely.

## What's actually missing or fake on the web -- build these first

| # | Feature | Current state | Found by |
|---|---|---|---|
| 1 | **Mentors** | `mentors.php` is a static "Coming Soon" page. No `mentors` or `mentor_requests` table. Only 2 real staff accounts exist (the founder, as QA and as admin) -- no roster of coaches to seed. | Read the file; `SHOW TABLES`; queried `staff_accounts` |
| 2 | **Students (classmates)** | `students.php` is a static "Coming Soon" page. No online-status tracking (no `last_seen_at` anywhere). | Read the file |
| 3 | **Events** | `events.php` is one hand-written HTML event card (the Gen-Z LinkedIn Live) plus a "more coming soon" placeholder. No `events` or `event_registrations` table -- nothing is data-driven, there is no registration. | Read the file; `SHOW TABLES` |
| 4 | **Messages (two-way, coach <-> student)** | `messages.php` / `message_view.php` are real, but they are a **one-way announcement broadcast** (`broadcast_messages`, admin to all/some students), not a conversation. There is no thread/chat system anywhere. | Read both files; their SQL only touches `broadcast_messages` |
| 5 | **Notifications (unified feed)** | Only the broadcast messages above exist as a feed. Assignment-due, event-reminder, feedback-ready and streak notifications don't exist as a concept. | Same as above |
| 6 | **Study time / Analytics (time side)** | `students_activity` (`student_id, course_id, type: study|test, duration_minutes`) is exactly a study-session table -- and has **zero rows**. Nothing writes to it. `analytics.php` only covers test-score analytics, not study minutes. | Row count query; grepped for writers |

## Build order (tonight, web first)

1. [x] **Mentors** -- migration 142 (`mentors`, `mentor_availability`, `mentor_requests`), real
   `mentors.php` + `mentor_book.php` (day/time slot picker, request/cancel, re-validated
   server-side against double-booking). Seeded with the one real instructor (Victor) only. No
   sls-admin management page yet -- founder adds more mentors by inserting rows directly for now.
2. [x] **Students (classmates)** -- migration 143 (`last_seen_at`, stamped in `bootstrap.php`),
   real `students.php`: cohort list, search, "online now" dot.
3. [x] **Events** -- migration 144 (`events`, `event_registrations`), real `events.php`: upcoming
   list, past-events section, Join/Going/"I can't make it". Gen-Z event seeded as a real row.
4. [x] **Messages (real threads)** -- migration 145 (`threads`, `thread_participants`,
   `thread_messages`), new `threads.php` + `thread_view.php`. `includes/navbar.php` relabelled:
   old "Messages" (broadcast_messages) is now "Notifications", the new item is the real "Messages".
   Coach threads only -- group threads (MSG-05) not built.
5. [x] **Notifications** -- new `notifications.php`: unifies broadcast announcements + unread
   message threads + assignments due within 2 days + registered events within 24h, grouped
   Today/Earlier, Mark all as read. `get_unread_count.php` now counts threads too.
6. [ ] **Study sessions -- blocked, not a quick add.** `students_activity` is real but has zero
   rows and nothing writes to it, because nothing writes to `lesson_progress` either -- lesson
   completion tracking (video watch time + 70% on exercises) was already identified as "not built"
   before tonight (see memory: Class completion rules). Study-time analytics and the streak
   (section 12.4) both depend on this. This is its own feature, not a side effect of tonight's work.
7. [x] **API layer (partial)** -- `academy/api/v1/*.php`: `classmates.php`, `mentors.php` +
   `mentor_requests.php`, `events.php` + `event_registrations.php`, `threads.php` +
   `thread_messages.php` + `thread_read.php`. Flat files, not nested REST paths -- there is no URL
   rewriting anywhere on this site, so `mentors/{id}/requests`-style paths aren't servable without
   untested Apache rewrite infra; the mobile app's `api.ts` was adjusted to match (see below).
   **Not yet wrapped:** assignments, results summary, resources (vocab/quizzes/exercises/model
   answers), analytics -- all real on the web already, just no `/v1` endpoint yet.
8. [ ] **Then mobile** -- wire the app's Students/Mentors/Events/Messages screens to the endpoints
   above (api.ts updated to call the real flat paths), and build the screens spec v0.3 added:
   Course detail redesign, Lesson, Assignment/Result/Mentor/Event detail, Vocabulary banks + Word,
   Quizzes + Quiz player, Exercises, Model answers, Settings, Sign up/Verify/Forgot password.

## Rules carried over from CLAUDE.md for this work

- Every migration: idempotent where practical, tested on local 8.0 AND a throwaway MySQL 5.7
  instance before being marked done in `migration_log.md`; never run on live by me.
- No fabricated "real" content (SEC-09): seed real data only, honestly labelled as founder-editable
  where it's a stand-in (e.g. the one real mentor).
- Coming Soon, not hidden, for anything still genuinely empty.
