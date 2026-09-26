<?php
// /academy/includes/course_lock.php
//
// Locks a standalone practice-test page to the specific course(s) that
// actually teach it, so it can't be taken "solo" via direct URL by a student
// who never enrolled in that course. This is a different check from
// tier_access.php's can_access() — tier is a platform-wide subscription
// level, independent of which specific course(s) a student is enrolled in,
// so a matching tier alone does not mean the student is enrolled in the
// course this test belongs to.
//
// Practice tests with no course pointing at them are intentionally left
// alone by this file — they're the free-range tier (open to any logged-in
// user), not an oversight. See project memory for the full list.

if (defined('COURSE_LOCK_LOADED')) return;
define('COURSE_LOCK_LOADED', true);

require_once INCLUDES_PATH . '/admin_check.php';

/**
 * Course ids for folder names. Ids differ between local and live, so a page that must accept "any of these
 * courses" names the folders and looks the ids up here rather than typing ids into the page.
 *
 * @param string[] $folders
 * @return int[]
 */
function course_ids_for_folders(array $folders): array
{
    global $db;
    if (empty($folders)) return [];
    $stmt = $db->prepare("SELECT id FROM courses WHERE folder_name IN (" . implode(',', array_fill(0, count($folders), '?')) . ")");
    $stmt->execute(array_values($folders));
    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

/**
 * True if the current student has an enrollment row in any of the given
 * course ids, or is a platform admin (admins always pass, matching the
 * existing tier_access.php / can_access() convention).
 *
 * @param int[] $course_ids
 */
function is_enrolled_in_any_course(array $course_ids): bool
{
    global $db;
    if (is_platform_admin()) return true;
    if (empty($course_ids) || !isset($_SESSION['user_id'])) return false;

    $studentId    = (int) $_SESSION['user_id'];
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $db->prepare("
        SELECT 1 FROM enrollments
        WHERE student_id = ? AND course_id IN ($placeholders)
        LIMIT 1
    ");
    $stmt->execute([$studentId, ...$course_ids]);
    return (bool) $stmt->fetchColumn();
}

/**
 * Call at the top of a practice-test page, right after the login check.
 * If the student isn't enrolled in any of $course_ids (and isn't an admin),
 * renders a full "enroll to unlock" locked page — naming the course(s) this
 * test belongs to, with a button to enroll and an automatic redirect to the
 * first course's overview page after a few seconds — then exits. Admins and
 * enrolled students pass through silently and the calling page continues
 * rendering as normal.
 *
 * @param int[]  $course_ids     Course id(s) this test is taught in — enrollment
 *                                in ANY one of them is sufficient (matches the
 *                                content-sharing pattern across course variants,
 *                                e.g. CELPIP/IELTS 1/2/3-month plans).
 * @param string $content_label  Short name for the locked card, e.g. "this CELPIP practice test".
 */
function require_course_enrollment(array $course_ids, string $content_label = 'this practice test'): void
{
    if (is_enrolled_in_any_course($course_ids)) return;

    global $db;
    $placeholders = implode(',', array_fill(0, count($course_ids), '?'));
    $stmt = $db->prepare("SELECT id, title, folder_name FROM courses WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)");
    $stmt->execute([...$course_ids, ...$course_ids]);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $primary      = $courses[0] ?? null;
    $primaryUrl   = $primary ? ACADEMY_URL . 'courses/' . $primary['folder_name'] . '/course_overview.php' : ACADEMY_URL . 'courses/courses_catalogue.php';
    $primaryTitle = $primary['title'] ?? 'the course';
    $otherNames   = array_map(fn($c) => $c['title'], array_slice($courses, 1));
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Enroll to Unlock | EduHub</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="6;url=<?= htmlspecialchars($primaryUrl) ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    </head>
    <body>
        <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
        <div class="mobile-overlay" id="mobileOverlay"></div>
        <?php include INCLUDES_PATH . '/navbar.php'; ?>
        <div class="main-wrapper flex-grow-1" style="flex:1;">
            <main class="content p-4">
                <div class="container">
                    <div style="background:#f8fafc;border:2px dashed #cbd5e1;border-radius:16px;padding:2.5rem 2rem;text-align:center;margin:1.5rem auto;max-width:560px;">
                        <div style="font-size:2.5rem;margin-bottom:1rem;">🔒</div>
                        <h4 style="color:#1e293b;margin-bottom:.5rem;">
                            Enroll to unlock <?= htmlspecialchars($content_label) ?>
                        </h4>
                        <p style="color:#64748b;font-size:.9rem;margin-bottom:1.5rem;">
                            This test is part of <strong><?= htmlspecialchars($primaryTitle) ?></strong><?php if ($otherNames): ?>
                            (also included in <?= htmlspecialchars(implode(' and ', $otherNames)) ?>)<?php endif; ?>.
                            Enroll in the course to take it as part of your program.
                        </p>
                        <a href="<?= htmlspecialchars($primaryUrl) ?>"
                           style="display:inline-block;background:linear-gradient(90deg,#0b77ff,#6366f1);color:#fff;padding:.75rem 2rem;border-radius:10px;font-weight:700;text-decoration:none;font-size:.95rem;">
                            View <?= htmlspecialchars($primaryTitle) ?> →
                        </a>
                        <div style="margin-top:1rem;">
                            <a href="<?= ACADEMY_URL ?>courses/courses_catalogue.php" style="color:#64748b;font-size:.85rem;">← Back to Courses</a>
                        </div>
                        <p style="color:#94a3b8;font-size:.78rem;margin-top:1.5rem;margin-bottom:0;">Redirecting you there automatically…</p>
                    </div>
                </div>
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    </body>
    </html>
    <?php
    exit();
}
