<?php
/**
 * Single source of truth for "is this logged-in user an admin/instructor/staff
 * member (or named tester), for the purpose of previewing student-facing
 * content and bypassing access gates." Every gate calls this one function.
 *
 * Decided by the DATABASE, never by the email text: staff/admin = a row in
 * staff_accounts (migration 104) for the logged-in student AND a verified
 * email. An address that merely ends in @slslanguage.com proves nothing, so
 * it no longer grants anything on its own. Testers = students.is_tester.
 * Grant/revoke from sls-admin (staff_accounts rows are created by an admin
 * only). Everything fails CLOSED: missing table/column or DB error = no bypass.
 */

function is_platform_admin(): bool {
    return is_staff_account() || is_access_tester();
}

/** Staff/admin: a staff_accounts row for this student, and a verified email. */
function is_staff_account(): bool {
    static $cache = [];
    $uid = (int) ($_SESSION['user_id'] ?? 0);
    if ($uid <= 0) return false;
    if (array_key_exists($uid, $cache)) return $cache[$uid];

    global $db;
    $cache[$uid] = false;
    if (!isset($db) || !($db instanceof PDO)) return false;
    try {
        $stmt = $db->prepare("
            SELECT 1 FROM staff_accounts sa
            JOIN students s ON s.id = sa.student_id
            WHERE sa.student_id = ? AND s.is_verified = 1
            LIMIT 1
        ");
        $stmt->execute([$uid]);
        $cache[$uid] = (bool) $stmt->fetchColumn();
    } catch (PDOException $e) {
        // staff_accounts missing (migration 104 not run) or DB error: stay closed.
    }
    return $cache[$uid];
}

/**
 * Named testers: current students flagged in sls-admin (students.is_tester,
 * migration 103) who bypass every access gate exactly like an admin, so the
 * instructor can watch features work daily while real students get the rules.
 * Cached per request. Fails CLOSED -- if the column doesn't exist yet (migration
 * not run) or the lookup errors, nobody is treated as a tester.
 */
function is_access_tester(): bool {
    static $cache = [];
    $uid = (int) ($_SESSION['user_id'] ?? 0);
    if ($uid <= 0) return false;
    if (array_key_exists($uid, $cache)) return $cache[$uid];

    global $db;
    $cache[$uid] = false;
    if (!isset($db) || !($db instanceof PDO)) return false;
    try {
        $stmt = $db->prepare("SELECT is_tester FROM students WHERE id = ? LIMIT 1");
        $stmt->execute([$uid]);
        $cache[$uid] = ((int) $stmt->fetchColumn()) === 1;
    } catch (PDOException $e) {
        // Column missing (migration 103 not run) or DB hiccup: stay closed.
    }
    return $cache[$uid];
}
