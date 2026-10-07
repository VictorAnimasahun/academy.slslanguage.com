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

1. [ ] **Mentors** -- `mentors` + `mentor_requests` tables, real `mentors.php` (list + request), a day/time
   slot picker page, a tiny sls-admin page so the founder can add coaches later. Seed with the one
   real instructor (Victor) only, clearly editable -- not fictional "Coach Scholar/Vee" personas.
2. [ ] **Students (classmates)** -- real `students.php`: cohort list (same-course enrollees), search,
   a lightweight `last_seen_at` heartbeat for "online now".
3. [ ] **Events** -- `events` + `event_registrations` tables, rebuild `events.php` from the DB (migrate
   the existing Gen-Z event into a real row), add/registration, event detail page.
4. [ ] **Messages (real threads)** -- `threads` + `thread_participants` + `thread_messages`, a real
   chat UI on the web (coach <-> student), keep `broadcast_messages` as-is (it becomes one
   Notifications source, not "Messages").
5. [ ] **Notifications** -- unify broadcast messages + assignment-due + event-reminder + mentor
   confirmation + new-message into one feed (table or computed view), a real `notifications.php`.
6. [ ] **Study sessions** -- an endpoint/page-level hook that actually writes to `students_activity`,
   so Analytics' study-time side and the streak (section 12.4) have real data instead of nothing.
7. [ ] **API layer** -- `academy/api/v1/*.php`, versioned per the spec's own base path, covering
   every one of the above plus the already-real features (courses/lesson content, assignments,
   results summary, resources wrappers for vocab/quizzes/exercises/model-answers).
8. [ ] **Then mobile** -- wire the app's existing placeholder screens to the real endpoints, and
   build the screens spec v0.3 added: Course detail redesign, Lesson, Assignment/Result/Mentor/
   Event detail, Vocabulary banks + Word, Quizzes + Quiz player, Exercises, Model answers,
   Notifications, Settings, Sign up/Verify/Forgot password.

## Rules carried over from CLAUDE.md for this work

- Every migration: idempotent where practical, tested on local 8.0 AND a throwaway MySQL 5.7
  instance before being marked done in `migration_log.md`; never run on live by me.
- No fabricated "real" content (SEC-09): seed real data only, honestly labelled as founder-editable
  where it's a stand-in (e.g. the one real mentor).
- Coming Soon, not hidden, for anything still genuinely empty.
