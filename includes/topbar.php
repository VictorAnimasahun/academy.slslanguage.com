<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
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

    <!-- Left: toggle + brand -->
    <div class="d-flex align-items-center gap-2 exam-fs-hide">
        <button id="sidebarToggle" class="btn btn-link p-1"
                aria-label="Toggle sidebar"
                style="color:#334155;font-size:1.45rem;line-height:1;text-decoration:none;">
            <i class="bi bi-list"></i>
        </button>
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
                Welcome, <?= htmlspecialchars($userName) ?>! 🎉
            <?php else: ?>
                Welcome back, <?= htmlspecialchars($userName) ?>
            <?php endif; ?>
        </span>
        <?php endif; ?>

        <button id="themeToggle" class="btn btn-sm btn-outline-secondary exam-fs-hide" aria-label="Toggle dark mode">
            🌙 Dark Mode
        </button>

        <!-- Only shown on test/mock pages — see navbar_scripts.php, which
             detects an exam page generically and reveals this. Deliberately
             NOT exam-fs-hide — this is the only way back out of Focus Mode. -->
        <button id="examFullscreenToggle" class="btn btn-sm btn-outline-secondary" aria-label="Toggle focus mode" style="display:none;">
            <i class="bi bi-arrows-fullscreen"></i> Focus Mode
        </button>

        <button class="btn btn-link p-0 exam-fs-hide" id="bellBtn" aria-label="Notifications">
            <div style="position:relative;">
                <i class="bi bi-bell" style="font-size:1.2rem;color:#334155;"></i>
                <span class="bell-unread-dot" style="
                    position:absolute; top:-4px; right:-6px;
                    width:10px; height:10px;
                    background:#0b77ff; border-radius:50%;
                    animation:pulse 1.8s infinite;
                    box-shadow:0 0 6px rgba(11,119,255,0.6);
                    display:none;"></span>
            </div>
        </button>

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
