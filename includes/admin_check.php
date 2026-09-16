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
 */

function is_platform_admin(): bool {
    $email = strtolower(trim($_SESSION['user_email'] ?? ''));
    if ($email === '') return false;

    if (str_ends_with($email, '@slslanguage.com')) return true;

    $legacyAdminEmails = ['animasahunvictor1@gmail.com'];
    return in_array($email, $legacyAdminEmails, true);
}
