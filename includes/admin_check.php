<?php
/**
 * Single source of truth for "is this logged-in user an admin/instructor,
 * for the purpose of previewing student-facing content." Replaces the
 * hardcoded $adminEmails list that used to be duplicated verbatim across 15
 * files under resources/mock_tests/ — a new @slslanguage.com hire needed a
 * code change in 15 places to get preview access before this existed.
 *
 * Rule (per instructor): any @slslanguage.com email is an admin, plus one
 * legacy personal address (the platform owner's) that predates that rule.
 * ashonibarevik@gmail.com was removed 2026-09-16 per instructor request.
 *
 * Also true for named testers (students.is_tester) -- see is_access_tester().
 */

function is_platform_admin(): bool {
    $email = strtolower(trim($_SESSION['user_email'] ?? ''));
    if ($email === '') return false;

    if (str_ends_with($email, '@slslanguage.com')) return true;

    $legacyAdminEmails = ['animasahunvictor1@gmail.com'];
    if (in_array($email, $legacyAdminEmails, true)) return true;

    return is_access_tester();
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
