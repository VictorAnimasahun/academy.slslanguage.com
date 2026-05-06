<?php
/**
 * Tiered Content Access Helper
 *
 * Include this file on any page that gates content by subscription tier.
 * Requires: bootstrap.php already loaded ($db available, session started).
 *
 * Usage:
 *   require_once INCLUDES_PATH . '/tier_access.php';
 *
 *   if (!can_access('advanced')) {
 *       include INCLUDES_PATH . '/upgrade_prompt.php';
 *       exit;
 *   }
 *
 * Tier hierarchy:  beginner(1) < intermediate(2) < advanced(3) < fluent(4)
 * Grace period:    Expired subscriptions stay active for 3 days before dropping to beginner.
 */

// Prevent multiple inclusions
if (defined('TIER_ACCESS_LOADED')) return;
define('TIER_ACCESS_LOADED', true);

// ── Tier map ────────────────────────────────────────────────────────────────

const TIER_LEVELS = [
    'beginner'     => 1,
    'intermediate' => 2,
    'advanced'     => 3,
    'fluent'       => 4,
];

const TIER_LABELS = [
    'beginner'     => 'Beginner (Free)',
    'intermediate' => 'Intermediate — 1 Month',
    'advanced'     => 'Advanced — 2 Months',
    'fluent'       => 'Fluent — 3 Months',
];

const TIER_PRICES = [
    'beginner'     => null,
    'intermediate' => '£X/month',
    'advanced'     => '£X/2 months',
    'fluent'       => '£X/3 months',
];

// ── Core lookup ─────────────────────────────────────────────────────────────

/**
 * Returns the active tier name for the current student.
 * Falls back to 'beginner' if not logged in, no subscription, or expired > 3 days.
 */
function get_student_tier(): string {
    global $db;

    if (!isset($_SESSION['user_id'])) {
        return 'beginner';
    }

    $student_id = (int) $_SESSION['user_id'];

    // Active subscription OR within 3-day grace period after expiry
    $stmt = $db->prepare("
        SELECT tier
        FROM subscriptions
        WHERE student_id = ?
          AND status = 'active'
          AND (
              expiry_date IS NULL
              OR expiry_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
          )
        ORDER BY
            FIELD(tier, 'fluent', 'advanced', 'intermediate', 'beginner'),
            expiry_date DESC
        LIMIT 1
    ");

    try {
        $stmt->execute([$student_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return ($row && isset(TIER_LEVELS[$row['tier']])) ? $row['tier'] : 'beginner';
    } catch (PDOException $e) {
        error_log('tier_access.php get_student_tier(): ' . $e->getMessage());
        return 'beginner';
    }
}

/**
 * Returns the numeric level (1–4) for the current student.
 */
function get_student_tier_level(): int {
    return TIER_LEVELS[get_student_tier()] ?? 1;
}

// ── Access check ────────────────────────────────────────────────────────────

/**
 * Returns true if the current student meets or exceeds the required tier.
 *
 * @param string $min_tier  One of: beginner | intermediate | advanced | fluent
 */
function can_access(string $min_tier): bool {
    $required = TIER_LEVELS[$min_tier] ?? 1;
    return get_student_tier_level() >= $required;
}

// ── Upgrade prompt HTML ─────────────────────────────────────────────────────

/**
 * Renders a locked-content card with an upgrade CTA.
 * Call this instead of the content when can_access() returns false.
 *
 * @param string $required_tier  The tier the content requires.
 * @param string $content_label  Short description shown on the lock card (e.g. "this video").
 */
function render_upgrade_prompt(string $required_tier, string $content_label = 'this content'): void {
    $label = TIER_LABELS[$required_tier] ?? ucfirst($required_tier);
    $current = get_student_tier();
    $current_label = TIER_LABELS[$current] ?? ucfirst($current);
    ?>
    <div style="
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 2.5rem 2rem;
        text-align: center;
        margin: 1.5rem 0;
        max-width: 560px;
    ">
        <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔒</div>
        <h4 style="color: #1e293b; margin-bottom: 0.5rem;">
            This content requires the <strong><?= htmlspecialchars($label) ?></strong> plan
        </h4>
        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">
            You're currently on the <strong><?= htmlspecialchars($current_label) ?></strong> plan.
            Upgrade to unlock <?= htmlspecialchars($content_label) ?> and everything at this level.
        </p>
        <a href="<?= defined('ACADEMY_URL') ? ACADEMY_URL : '/academy/' ?>upgrade.php?required=<?= urlencode($required_tier) ?>"
           style="
               display: inline-block;
               background: linear-gradient(90deg, #0b77ff, #6366f1);
               color: #fff;
               padding: 0.75rem 2rem;
               border-radius: 10px;
               font-weight: 700;
               text-decoration: none;
               font-size: 0.95rem;
           ">
            Upgrade to <?= htmlspecialchars($label) ?> →
        </a>
        <div style="margin-top: 1rem;">
            <a href="<?= defined('ACADEMY_URL') ? ACADEMY_URL : '/academy/' ?>courses/courses_catalogue.php"
               style="color: #64748b; font-size: 0.85rem;">
                ← Back to Courses
            </a>
        </div>
    </div>
    <?php
}
