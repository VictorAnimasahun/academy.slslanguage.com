<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Theme persistence — runs before paint to avoid flash
    (function() {
        const saved = localStorage.getItem('eduhub-theme') || 'light';
        document.body.classList.remove('light', 'dark');
        document.body.classList.add(saved);
        const btn = document.getElementById('themeToggle');
        if (btn) btn.textContent = saved === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
    })();

    // Restore sidebar-collapsed state before first paint (desktop only)
    (function() {
        if (window.innerWidth >= 1200 && localStorage.getItem('eduhub-sidebar-collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    })();

    document.addEventListener('DOMContentLoaded', function() {

        // ── Theme toggle ────────────────────────────────────────────
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isDark = document.body.classList.contains('dark');
                const newTheme = isDark ? 'light' : 'dark';
                document.body.classList.remove('light', 'dark');
                document.body.classList.add(newTheme);
                toggleBtn.textContent = newTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
                localStorage.setItem('eduhub-theme', newTheme);
            });
        }

        // ── Sidebar toggles ─────────────────────────────────────────
        const menuToggle    = document.getElementById('menuToggle');    // mobile header
        const sidebarToggle = document.getElementById('sidebarToggle'); // topbar (desktop)
        const sidebarEl     = document.querySelector('.sidebar');
        const overlay       = document.getElementById('mobileOverlay');

        function toggleMobile() {
            if (!sidebarEl) return;
            const open = sidebarEl.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active', open);
            const icon = menuToggle?.querySelector('i');
            if (icon) icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
        }

        function toggleDesktop() {
            const collapsed = document.body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('eduhub-sidebar-collapsed', collapsed);
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', toggleMobile);
        }

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth >= 1200) {
                    toggleDesktop();
                } else {
                    toggleMobile();
                }
            });
        }

        if (overlay) {
            overlay.addEventListener('click', toggleMobile);
        }

        // Close mobile sidebar when a nav link is clicked
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200 && sidebarEl?.classList.contains('active')) {
                    toggleMobile();
                }
            });
        });

        // ── Exam fullscreen ("Focus Mode") toggle — hides the left
        // (.sidebar) and right (.advert-sidebar) nav on test-taking pages
        // only, keeping the top nav (progress steps, timer, admin banner)
        // visible exactly as-is. The button itself lives in topbar.php
        // (next to Dark Mode), hidden by default; revealed here only when
        // the page looks like a test, detected generically so it works on
        // every test engine (mock + practice, old + new page templates)
        // without editing each test file individually. State is
        // deliberately NOT persisted across page loads — a fresh page load
        // (e.g. right after submitting) always starts back in the normal
        // view, satisfying "revert on submit" for free.
        (function() {
            const btn = document.getElementById('examFullscreenToggle');
            if (!btn) return;

            const isExamPage = document.querySelector(
                '.sticky-header, .celpip-header, .part-header, #timerEl, #inlineTimer, #timerDisplay, .timer-display, .speaking-task-card, .celpip-shell'
            );
            if (!isExamPage) return;

            btn.style.display = 'inline-flex';
            btn.style.alignItems = 'center';
            btn.style.gap = '.4rem';
            btn.addEventListener('click', function() {
                const active = document.body.classList.toggle('exam-fullscreen');
                btn.innerHTML = active
                    ? '<i class="bi bi-fullscreen-exit"></i> Exit Focus Mode'
                    : '<i class="bi bi-arrows-fullscreen"></i> Focus Mode';
            });
        })();
    });
</script>
