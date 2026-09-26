<?php
/**
 * "Coming Soon" -- one rule, one look, every level (instructor, 2026-09-26).
 * Anything that has nothing in it yet says Coming Soon: course, class, lesson, resource, test, mock.
 * Nothing is hidden or retired for being empty.
 *
 *  - COURSE level is a business decision stored in courses.availability ('coming_soon' | 'available').
 *  - CLASS / PIECE level follows what the page really holds: an empty lesson page, a test with no page yet.
 */
if (!function_exists('course_is_coming_soon')) {

    function course_is_coming_soon(array $course): bool {
        return ($course['availability'] ?? 'available') === 'coming_soon';
    }

    /** The standard box. $what is only used in the sentence: "This lesson isn't ready yet." */
    function coming_soon_box(string $what = 'class'): string {
        return '<div class="highlight-box"><h4 style="color:var(--accent);"><i class="bi bi-hourglass-split me-2"></i>Coming Soon</h4>'
             . '<p class="mb-0">This ' . htmlspecialchars($what) . ' isn\'t ready yet. Check back soon.</p></div>';
    }

    /** One-line version for lists (a test or lesson row that has no page yet). */
    function coming_soon_line(): string {
        return '<p class="text-muted mb-0"><i class="bi bi-hourglass-split me-1"></i>Coming Soon</p>';
    }

    /** Small pill for cards and accordion rows. */
    function coming_soon_pill(): string {
        return '<span style="display:inline-block;padding:.12rem .55rem;border-radius:999px;font-size:.66rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;background:#f1f5f9;color:#475569;"><i class="bi bi-hourglass-split me-1"></i>Coming Soon</span>';
    }
}
