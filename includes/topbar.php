<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<header class="topbar">

    <!-- Left: toggle + brand -->
    <div class="d-flex align-items-center gap-2">
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

    <!-- Right: welcome + controls -->
    <div class="d-flex align-items-center gap-3">

        <?php if (isset($userName)): ?>
        <span class="d-none d-xl-block text-muted" style="font-size:.85rem;">
            <?php if (isset($_GET['status']) && $_GET['status'] === 'registration_success'): ?>
                Welcome, <?= htmlspecialchars($userName) ?>! 🎉
            <?php else: ?>
                Welcome back, <?= htmlspecialchars($userName) ?>
            <?php endif; ?>
        </span>
        <?php endif; ?>

        <button id="themeToggle" class="btn btn-sm btn-outline-secondary" aria-label="Toggle dark mode">
            🌙 Dark Mode
        </button>

        <button class="btn btn-link p-0" id="bellBtn" aria-label="Notifications">
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
        <div style="width:34px;height:34px;border-radius:10px;
                    background:linear-gradient(90deg,#7c3aed,#ec4899);
                    display:flex;align-items:center;justify-content:center;
                    color:white;font-weight:700;font-size:.85rem;cursor:default;"
             title="<?= htmlspecialchars($userName) ?>">
            <?= strtoupper(substr($userName, 0, 2)) ?>
        </div>
        <?php endif; ?>

    </div>
</header>
