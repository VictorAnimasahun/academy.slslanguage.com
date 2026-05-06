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

    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const isDark = document.body.classList.contains('dark');
                const newTheme = isDark ? 'light' : 'dark';
                document.body.classList.remove('light', 'dark');
                document.body.classList.add(newTheme);
                toggleBtn.textContent = newTheme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
                toggleBtn.setAttribute('aria-label', `Switch to ${newTheme === 'dark' ? 'light' : 'dark'} mode`);
                localStorage.setItem('eduhub-theme', newTheme);
            });
        }
    });

    // Mobile Menu Toggle
    (function() {
        const menuToggle = document.getElementById('menuToggle');
        const sidebarElement = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            if (!sidebarElement || !mobileOverlay) return;
            
            sidebarElement.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
            // Change icon
            const icon = menuToggle.querySelector('i');
            if (sidebarElement.classList.contains('active')) {
                icon.className = 'bi bi-x-lg';
            } else {
                icon.className = 'bi bi-list';
            }
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', toggleMenu);
        }
        
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', toggleMenu);
        }

        // Close menu when a nav link is clicked on mobile
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200 && sidebarElement && sidebarElement.classList.contains('active')) {
                    toggleMenu();
                }
            });
        });
    })();
</script>