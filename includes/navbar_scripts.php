<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<script>
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