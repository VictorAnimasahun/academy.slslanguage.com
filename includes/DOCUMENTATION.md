# Includes — Subsystem Documentation

Parent: [`../documentation/SITE_MAP.md`](../documentation/SITE_MAP.md)

Shared PHP fragments included by nearly every page in the platform. Not a "page" subsystem itself, but foundational — most other docs in this tree reference files here.

| File | Purpose |
|---|---|
| `navbar.php` | Main sidebar navigation (dashboard, courses, students, assignments, mentors, resources) |
| `navbar_styles.php` | CSS/icon `<link>` tags the navbar needs |
| `navbar_scripts.php` | JS the navbar needs (mobile toggle, active-link highlighting, etc.) |
| `topbar.php` | Top header bar (user menu, message preview) |
| `mobile_header.php` | Responsive mobile-only header |
| `footer.php` | Site footer |
| `adverts.php` | Ad/promo sidebar container — included on most test-taking pages |
| `mock_test_map.php` | **Config, not a UI fragment** — the routing table mapping each full mock's code to its section files and DB test codes. See `resources/mock_tests/DOCUMENTATION.md` for how this is used. Editing this file is required whenever a new full mock is added. |
| `tier_access.php` | Subscription tier gating (`beginner`/`intermediate`/`advanced`/`fluent`, with a grace-period allowance). Every lesson file calls into this. |
| `rate_limiter.php` | Per-user daily/hourly rate limiting for the AI-backed features (essay analysis, speaking feedback) — works with `MAX_REQUESTS_PER_USER_PER_DAY`/`_HOUR` constants from `config/api_keys.php` |

## Include order convention

Pages typically include in this order: `bootstrap.php` (root-level, not in this folder) → `navbar_styles.php` → page-specific `<head>` content → `mobile_header.php` → `navbar.php` → `topbar.php` (inside main content area) → page body → `navbar_scripts.php` → `footer.php`. Deviating from this order has caused "headers already sent" issues in the past (see `ACADEMY_PLATFORM_DOCUMENTATION.md` § Common Issues).
