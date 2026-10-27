<?php
// Rendered once per page: includes/navbar.php brings it in for every page (2026-10-21 -- most course and
// lesson pages never included it, so on a computer they had no logo, dark-mode switch or menu button).
if (defined('TOPBAR_RENDERED')) return;
define('TOPBAR_RENDERED', true);
$current_page = basename($_SERVER['PHP_SELF']); ?>
<style>
/* Shift the sidebar/advert-sidebar/main-wrapper below this fixed topbar on
   desktop. Scoped by this partial's own presence in the page (not
   `body:has(.topbar)` in navbar_styles.php) so it works in every browser,
   including ones without :has() support — see navbar_styles.php for why
   that mattered. */
@media (min-width: 1200px) {
    .sidebar {
        top: var(--topbar-h);
        height: calc(100vh - var(--topbar-h));
        min-height: 0;   /* or the rail runs past the bottom of the screen and Log out is hidden */
    }
    .advert-sidebar {
        top: var(--topbar-h);
        height: calc(100vh - var(--topbar-h));
    }
    .main-wrapper {
        margin-top: var(--topbar-h);
    }
}
</style>
<header class="topbar">

    <!-- Left: brand (the menu is a slim icon rail now, so there is nothing to minimise) -->
    <div class="d-flex align-items-center gap-2 exam-fs-hide">
        <a href="<?php echo ACADEMY_URL; ?>learning_dashboard.php"
           class="text-decoration-none d-flex align-items-center gap-2">
            <div class="blue-pill"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="d-none d-md-block">
                <div style="font-size:.95rem;font-weight:700;color:#0f172a;">EduHub</div>
                <div style="font-size:.78rem;color:var(--muted);">Learning Platform</div>
            </div>
        </a>
    </div>

    <!-- Right: welcome + controls. Everything here except
         #examFullscreenToggle carries exam-fs-hide so Focus Mode can hide
         the rest of the topbar while leaving the button that turns it back
         off visible — see the .exam-fs-hide rule in navbar_styles.php. -->
    <div class="d-flex align-items-center gap-3">

        <?php if (isset($userName)): ?>
        <span class="d-none d-xl-block text-muted exam-fs-hide" style="font-size:.85rem;">
            <?php if (isset($_GET['status']) && $_GET['status'] === 'registration_success'): ?>
                Welcome, <?= htmlspecialchars($userName) ?>
            <?php else: ?>
                Welcome back, <?= htmlspecialchars($userName) ?>
            <?php endif; ?>
        </span>
        <?php endif; ?>

        <button id="themeToggle" class="btn btn-sm btn-outline-secondary exam-fs-hide" aria-label="Toggle dark mode">
            Dark Mode
        </button>

        <!-- Only shown on test/mock pages — see navbar_scripts.php, which
             detects an exam page generically and reveals this. Deliberately
             NOT exam-fs-hide — this is the only way back out of Focus Mode. -->
        <button id="examFullscreenToggle" class="btn btn-sm btn-outline-secondary" aria-label="Toggle focus mode" style="display:none;">
            <i class="bi bi-arrows-fullscreen"></i> Focus Mode
        </button>

        <?php
        // The bell opens the inbox and glows while anything there is unread (founder, 2026-10-27). Counted here as
        // the page is built, so it works on every page; the script below keeps it fresh while the page is open.
        require_once INCLUDES_PATH . '/inbox.php';
        // Never let the count take a page down: on any error the bell just shows no count.
        try { $bellUnread = isset($_SESSION['user_id']) ? inbox_unread_count($db, (int) $_SESSION['user_id']) : 0; }
        catch (Throwable $e) { error_log('Inbox bell: ' . $e->getMessage()); $bellUnread = 0; }
        ?>
        <a href="<?= ACADEMY_URL ?>inbox.php" class="bell-link exam-fs-hide <?= $bellUnread ? 'has-unread' : '' ?>" id="bellBtn"
           aria-label="Inbox<?= $bellUnread ? ', ' . $bellUnread . ' unread' : '' ?>" title="Inbox<?= $bellUnread ? ' (' . $bellUnread . ' unread)' : '' ?>">
            <i class="bi bi-bell<?= $bellUnread ? '-fill' : '' ?>"></i>
            <span class="bell-count"><?= $bellUnread > 99 ? '99+' : ($bellUnread ?: '') ?></span>
        </a>
        <style>
            .bell-link { position:relative; display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; color:#334155; text-decoration:none; }
            .bell-link i { font-size:1.2rem; }
            .bell-link:hover { background:#f1f5f9; color:#0b77ff; }
            .bell-link .bell-count { display:none; position:absolute; top:1px; right:-2px; min-width:17px; height:17px; padding:0 4px; border-radius:9px;
                background:#0b77ff; color:#fff; font-size:.66rem; font-weight:700; line-height:17px; text-align:center; }
            .bell-link.has-unread { color:#0b77ff; animation:bellGlow 2s ease-in-out infinite; }
            .bell-link.has-unread .bell-count { display:block; }
            @keyframes bellGlow {
                0%, 100% { box-shadow:0 0 0 0 rgba(11,119,255,.45); background:rgba(11,119,255,.08); }
                50%      { box-shadow:0 0 14px 4px rgba(11,119,255,.35); background:rgba(11,119,255,.16); }
            }
            @media (prefers-reduced-motion: reduce) { .bell-link.has-unread { animation:none; box-shadow:0 0 10px 2px rgba(11,119,255,.35); } }
            body.dark .bell-link { color:#cbd5e1; }
            body.dark .bell-link:hover { background:#2a2a2a; }
        </style>
        <script>
        (function () {
            const bell = document.getElementById('bellBtn');
            if (!bell) return;
            function show(n) {
                bell.classList.toggle('has-unread', n > 0);
                bell.querySelector('i').className = n > 0 ? 'bi bi-bell-fill' : 'bi bi-bell';
                bell.querySelector('.bell-count').textContent = n > 99 ? '99+' : (n || '');
                bell.setAttribute('aria-label', 'Inbox' + (n ? ', ' + n + ' unread' : ''));
                bell.title = 'Inbox' + (n ? ' (' + n + ' unread)' : '');
                const badge = document.querySelector('.navbar-badge-unread');
                if (badge) { badge.textContent = n; badge.style.display = n > 0 ? 'inline-block' : 'none'; }
            }
            function refresh() {
                fetch('<?= ACADEMY_URL ?>api/get_unread_count.php', { credentials: 'same-origin' })
                    .then(r => r.json()).then(d => show(+d.unread_count || 0)).catch(() => {});
            }
            show(<?= (int) $bellUnread ?>);
            setInterval(refresh, 30000);
            document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); });
        })();
        </script>

        <?php if (isset($userName)): ?>
        <div class="exam-fs-hide" style="width:34px;height:34px;border-radius:10px;
                    background:linear-gradient(90deg,#7c3aed,#ec4899);
                    display:flex;align-items:center;justify-content:center;
                    color:white;font-weight:700;font-size:.85rem;cursor:default;"
             title="<?= htmlspecialchars($userName) ?>">
            <?= strtoupper(substr($userName, 0, 2)) ?>
        </div>
        <?php endif; ?>

    </div>
</header>
