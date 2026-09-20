# Brief for coding models: Selar payments in the academy platform

You have no prior context. Before writing anything, read: `academy/includes/tier_access.php`, `academy/includes/admin_check.php`, `academy/includes/course_pacing.php`, `slslanguage.com/sls-admin/student_view.php` (the `set_tier` and `enroll_course` actions), `config/edu_hub_registration_handler.php`, `academy/courses/courses_detail.php`, `academy/learning_dashboard.php`, `config/db_connect.php`, and `academy/bootstrap.php` (has the CSRF helper). Copy their patterns for DB access, sessions, admin auth and CSRF. Do not refactor unrelated code.

## Rules

- PHP 8.2, MySQL, PDO. Prepared statements only. Escape all output. No hard-coded credentials.
- Every new table or column goes in a numbered migration in `academy/database/migrations/` (next free number is **106**; 105 already exists). There is no migration runner: migrations are run by hand in phpMyAdmin and recorded in `academy/database/migrations/migration_log.md`. Append a log entry (Local/Live unticked, what it does, rollback) for each migration you write.
- Migrations must be safe to re-run. `CREATE TABLE IF NOT EXISTS ... ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci` (always state the charset: a table without one inherits the database's latin1 and silently turns non-ASCII text into `?`). For columns, check `information_schema.COLUMNS` first, because MySQL 5.7 (local) has no `ADD COLUMN IF NOT EXISTS`. No foreign keys (live rejected them).
- **Safety switch.** `FREE_ACCESS_FOR_ALL = true` in `tier_access.php` must stay `true`. Never change its value. New enforcement (the gate in step 6) must do nothing while it is true and apply only when it is false.
- **Bypass.** Staff, admins and testers must never be blocked by any new gate. Call `is_platform_admin()` (from `admin_check.php`) first and return early for them.
- Put all Selar logic in ONE shared file, `config/selar_purchases.php`. On live, academy and sls-admin are separate hosting folders that both reach the shared `/config/` folder, so this is the only place both can include it. The admin form, the registration hook, the dashboard and the webhook all call these functions. Nothing else may write Selar subscriptions.

## What already exists

- `subscriptions` (student_id, tier `intermediate|advanced|fluent`, start_date, expiry_date, status `active|expired|cancelled`, text column `paypal_txn_id` which we reuse to store the Selar order reference).
- `enrollments` (student_id, course_id, enrolled_at, progress_percentage), documented with `UNIQUE KEY unique_enrollment (student_id, course_id)`. Verify it exists; only add it in a migration if missing.
- `courses.selar_months` (migration 105): 1, 2 or 3 for the courses sold on Selar, NULL otherwise. Read this column for the paid duration. Never parse `courses.buy_url`.
- `get_student_tier()` in `tier_access.php` already implements "active, or within a 3-day grace period after expiry". Reuse it; do not write a second expiry check.
- `generateAssignmentsForEnrollment($db, $studentId, $courseId)` in `course_pacing.php` must be called whenever an enrolment is created (courses_detail.php does this).
- Selar sells three combined IELTS/CELPIP products: 1 month = tier `intermediate`, 2 months = `advanced`, 3 months = `fluent`. The buyer's email is the only link to `students.email`.

## How it works (confirmed decisions)

Payment and course choice are separate steps. **The subscription clock starts when the student chooses their course, not when they pay.** So a payment only creates a purchase and attaches it to a student. Choosing a course (redeeming) creates the subscription and the enrolment together, in one transaction. One purchase unlocks exactly one course, and the course's `selar_months` must equal the purchase's `duration_months` (exact match, not "or higher").

Purchase status flow: `pending` (no matching student yet) -> `claimed` (attached to a student, not yet used) -> `redeemed` (course chosen, subscription + enrolment created) or `refunded`.

## Steps (do them in order; finish and test each before starting the next)

**1. Migration 106: `pending_purchases`.** Columns: id, buyer_email (stored normalized), duration_months (1/2/3), selar_order_ref (UNIQUE), status ENUM('pending','claimed','redeemed','refunded') DEFAULT 'pending', student_id (NULL until matched), subscription_id (NULL until redeemed), redeemed_course_id (NULL), redeemed_at (NULL), created_at. Index on buyer_email and student_id.

**2. Shared functions in `config/selar_purchases.php`** (all take a PDO):
- `selar_normalize_email(string): string`: trim and lowercase.
- `selar_record_purchase($pdo, $email, $months, $orderRef): array`: validates months in 1..3 and a non-empty order ref, inserts as `pending`, catches the duplicate-order-ref error and returns a friendly result (never an SQL exception), then looks up a student by normalized email and, if found, calls `selar_claim_purchase`.
- `selar_claim_purchase($pdo, $purchaseId, $studentId): bool`: `UPDATE ... SET status='claimed', student_id=? WHERE id=? AND status='pending'`. If 0 rows changed, stop and return false, so a double click or a race can never claim twice. Do NOT create a subscription here.
- `selar_redeem_purchase($pdo, $purchaseId, $studentId, $courseId): array`: in one transaction: (a) `UPDATE ... SET status='redeemed', redeemed_course_id=?, redeemed_at=NOW() WHERE id=? AND student_id=? AND status='claimed'`, stop if 0 rows; (b) check the course exists, is visible (`is_visible=1`) and `selar_months` equals the purchase's `duration_months`, else roll back; (c) map months to tier; start = today, but if the student already has an active unexpired subscription, start at its latest expiry so renewals stack; expiry = start plus N months using `DateTime::modify`; (d) insert the `subscriptions` row the same way `set_tier` does, storing the order ref in `paypal_txn_id`, and save its id on the purchase; (e) insert the `enrollments` row (a duplicate enrolment is not an error: treat it as already enrolled and continue). After commit, call `generateAssignmentsForEnrollment` (wrapped in try/catch; a failure there must not undo the purchase).
- `selar_refund_purchase($pdo, $purchaseId): bool`: status -> `refunded`; if the purchase has a `subscription_id`, set that subscription's status to `cancelled`. Leave the enrolment row alone; access comes from the subscription.
- `selar_assign_purchase($pdo, $purchaseId, $studentId)`: admin fallback for when the buyer used a different email than their academy account; same as claim but for an admin-chosen student.

**3. Admin pages in sls-admin** (admin auth via `redirectIfNotAuthorized()` plus CSRF): a "Record Selar purchase" form (buyer email, duration 1/2/3, order reference) and a table of purchases with status filter tabs. Per row: "Mark refunded", and for unmatched pending rows an "Assign to student" action (pick by email search). Add the page to `sls-admin/sidebar.php` and use the shared classes in `css/admin.css` (`.tabs`, `.data-table`, `.form-card`, `.btn`, `.alert`) so it matches the rest of the admin.

**4. Registration hook.** In `config/edu_hub_registration_handler.php`, after the student row is created: find `pending` purchases for the normalized email and call `selar_claim_purchase` for each. Wrap it in try/catch and log failures; it must NEVER block or break registration.

**5. Dashboard: "Choose your course"** on `learning_dashboard.php`. If the student has one or more `claimed` purchases, show a panel per purchase listing the visible courses whose `selar_months` equals that purchase's `duration_months`. Choosing one is a POST with CSRF that calls `selar_redeem_purchase`; the server re-checks everything (purchase belongs to this student and is still `claimed`, course matches). Show a clear message after success with a link to the course.

**6. Server-side gate in `courses_detail.php`.** When `FREE_ACCESS_FOR_ALL` is **false** and the user is not `is_platform_admin()`: the "Enrol Now" POST must be rejected on the server for any course where `selar_months IS NOT NULL` (enrolment for these courses happens only through step 5). Show "Buy this course on Selar, then choose it from your dashboard." Hiding the button is not enough; the POST handler itself must refuse. When `FREE_ACCESS_FOR_ALL` is true, behave exactly as today.

**7. Phase 2 (only if Selar offers webhooks).** Read Selar's documentation for the real signature or secret mechanism; do not invent one. Build `academy/api/selar_webhook.php`: verify the secret with `hash_equals`, keep the secret in config or environment outside the web root, treat a duplicate `selar_order_ref` as success (HTTP 200, no second insert), call `selar_record_purchase`, and log failures. A refund event calls `selar_refund_purchase`.

## Deliverable per step

Changed files with full code, the migration (and its `migration_log.md` entry), and a short manual test checklist. For step 2 the checklist must include: duplicate order ref, two simultaneous claims, redeeming with the wrong duration, redeeming twice, a renewal that stacks on an active subscription, and a refund after redemption.
