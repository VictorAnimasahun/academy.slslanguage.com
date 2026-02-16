<script>
// Function to update unread indicator
function updateUnreadCount() {
    fetch('./api/get_unread_count.php')
        .then(response => response.json())
        .then(data => {
            const count = data.unread_count;
            
            // Update navbar badge (sidebar)
            updateNavbarBadge(count);
            
            // Update topbar indicator (bell icon)
            updateBellIndicator(count);
        })
        .catch(error => console.error('Error fetching unread count:', error));
}

function updateNavbarBadge(count) {
    const badgeEl = document.querySelector('.navbar-badge-unread');
    
    if (count > 0) {
        if (badgeEl) {
            badgeEl.textContent = count;
            badgeEl.style.display = 'inline-block';
        }
    } else if (badgeEl) {
        badgeEl.style.display = 'none';
    }
}

function updateBellIndicator(count) {
    const dotEl = document.querySelector('.bell-unread-dot');
    
    if (count > 0) {
        if (dotEl) {
            dotEl.style.display = 'block';
        }
    } else if (dotEl) {
        dotEl.style.display = 'none';
    }
}

// Poll every 10 seconds
updateUnreadCount(); // Run immediately on page load
setInterval(updateUnreadCount, 10000);

// Also update when user returns to browser tab
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        updateUnreadCount();
    }
});
</script>