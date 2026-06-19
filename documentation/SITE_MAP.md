# Academy — Site Map & Page Documentation Index

This is the top-level navigational documentation for the Academy platform (`/Applications/MAMP/htdocs/academy/`). It describes every major area of the site and links down to a dedicated documentation file for each subsystem. For deep technical internals (auth flow, API rate limiting, security, performance), see `ACADEMY_PLATFORM_DOCUMENTATION.md` in this same folder — that file is a technical reference, this file is a structural/navigational map.

Stack: plain PHP + MySQL, served by MAMP/Apache, no build step, no framework.

## Root-level pages

| File | Purpose |
|---|---|
| `index.php` | Public landing page (marketing copy, course overview) for logged-out visitors |
| `learning_dashboard.php` | Main logged-in hub: enrolled courses, progress, certificates, assignments, recommendations |
| `edu_hub_registration.php` | Combined login/signup form (tab-based) |
| `process_registration.php` | POST handler for login/registration |
| `verify_email.php` | Email verification (token-based) |
| `edu_hub_logout.php` | Logout handler |
| `analytics.php` | Student performance dashboard (test attempts, scores, progress) |
| `messages.php` / `message_view.php` / `mark_read.php` | Broadcast message inbox, detail view, read-receipt AJAX |
| `assignments.php` | Per-course assignment list |
| `events.php` / `events/gen_z_experience.php` | Event countdown/registration pages |
| `students.php` / `mentors.php` | Directory placeholders ("coming soon") |
| `db_inspector.php` / `_check_admin.php` | Localhost-only debug tools — not for production use |
| `bootstrap.php` / `paths.php` | App bootstrap and path/URL constants — loaded by every page |

## Major subsystems (each has its own documentation file)

| Subsystem | Folder | Doc |
|---|---|---|
| Shared includes (navbar, tier access, rate limiting) | `includes/` | [`includes/DOCUMENTATION.md`](../includes/DOCUMENTATION.md) |
| Learning resources hub | `resources/` | [`resources/DOCUMENTATION.md`](../resources/DOCUMENTATION.md) |
| Full mock exams | `resources/mock_tests/` | [`resources/mock_tests/DOCUMENTATION.md`](../resources/mock_tests/DOCUMENTATION.md) |
| Standalone practice tests | `resources/practice_tests/` | [`resources/practice_tests/DOCUMENTATION.md`](../resources/practice_tests/DOCUMENTATION.md) |
| Interactive exercises | `resources/exercises/` | [`resources/exercises/DOCUMENTATION.md`](../resources/exercises/DOCUMENTATION.md) |
| Knowledge-check quizzes | `resources/quizzes/` | [`resources/quizzes/DOCUMENTATION.md`](../resources/quizzes/DOCUMENTATION.md) |
| Diagnostic tests | `resources/diagnostic_tests/` | [`resources/diagnostic_tests/DOCUMENTATION.md`](../resources/diagnostic_tests/DOCUMENTATION.md) |
| Downloadable study materials | `resources/study_materials/` | [`resources/study_materials/DOCUMENTATION.md`](../resources/study_materials/DOCUMENTATION.md) |
| Courses & lesson bank | `courses/` | [`courses/DOCUMENTATION.md`](../courses/DOCUMENTATION.md) |
| AJAX/JSON API endpoints | `api/` | [`api/DOCUMENTATION.md`](../api/DOCUMENTATION.md) |
| Database migrations | `database/` | [`database/DOCUMENTATION.md`](../database/DOCUMENTATION.md) |

## Surface-level student journey

1. Visitor hits `index.php` → registers via `edu_hub_registration.php`
2. Logs in → lands on `learning_dashboard.php`
3. From the dashboard or `resources/resources_home.php`, picks a learning activity:
   - A **course** (`courses/{COURSE_FOLDER}/lessons/classNN.php`), gated by subscription tier (`includes/tier_access.php`)
   - A **diagnostic test**, **practice test**, **mock exam**, **exercise**, or **quiz** under `resources/`
   - The **essay analyzer** or **audio analyzer** (AI-graded writing/speaking feedback)
4. Progress and scores roll up into `analytics.php` and the dashboard's "recent activity" cards
5. Announcements arrive via `messages.php` (broadcast from admin, targeted by tier/course/individual)

## Known gaps / not-yet-built

- `students.php` and `mentors.php` are placeholder pages ("coming soon")
- No admin dashboard lives inside `academy/` itself — admin functions (course/test/question CRUD, mock grading, messaging) live in the separate `slslanguage.com/sls-admin/` project, documented there
