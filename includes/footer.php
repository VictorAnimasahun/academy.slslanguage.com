<!-- Messages Drawer -->
<div id="msgDrawer" style="
    position:fixed; top:0; right:-380px; width:360px; height:100vh;
    background:#fff; box-shadow:-4px 0 20px rgba(0,0,0,.15);
    z-index:2000; transition:right .3s ease; display:flex; flex-direction:column;
    border-left:1px solid #e2e8f0;">
    <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;
                display:flex; align-items:center; justify-content:space-between;">
        <strong style="font-size:1rem;">📨 Messages</strong>
        <button id="msgDrawerClose" style="background:none;border:none;font-size:1.4rem;
            cursor:pointer;color:#64748b;line-height:1;">&times;</button>
    </div>
    <div id="msgDrawerBody" style="flex:1;overflow-y:auto;padding:1rem;"></div>
    <div style="padding:1rem; border-top:1px solid #e2e8f0; text-align:center;">
        <a href="<?php echo defined('ACADEMY_URL') ? ACADEMY_URL : '/academy/'; ?>messages.php"
           style="color:#0b77ff;font-size:.9rem;text-decoration:none;">
            View all messages →
        </a>
    </div>
</div>
<div id="msgOverlay" style="
    display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1999;"></div>

<style>
    #msgDrawer.open { right: 0; }
    body.dark #msgDrawer { background:#1e1e1e; border-left-color:#334155; }
    body.dark #msgDrawer strong { color:#e2e8f0; }
    body.dark #msgDrawer [style*="border-bottom"],
    body.dark #msgDrawer [style*="border-top"] {
        border-color:#334155 !important;
    }
    .msg-item { padding:.75rem; border-radius:10px; margin-bottom:.5rem;
        background:#f8fafc; cursor:pointer; border-left:3px solid #e2e8f0;
        transition:background .15s; }
    .msg-item.unread { border-left-color:#0b77ff; background:#eff6ff; }
    .msg-item:hover { background:#e2e8f0; }
    body.dark .msg-item { background:#2a2a2a; border-left-color:#334155; }
    body.dark .msg-item.unread { background:#1e3a5f; border-left-color:#0b77ff; }
    body.dark .msg-item:hover { background:#374151; }
    .msg-item-title { font-weight:600; font-size:.88rem; margin-bottom:.2rem; }
    .msg-item-preview { font-size:.8rem; color:#64748b; line-height:1.4; }
    .msg-item-date { font-size:.72rem; color:#94a3b8; margin-top:.3rem; }
    .msg-empty { text-align:center; padding:2rem; color:#94a3b8; }
</style>

<script>
// ── Unread count polling ──────────────────────────────────────────────
const ACADEMY_URL = '<?php echo defined('ACADEMY_URL') ? ACADEMY_URL : '/academy/'; ?>';

function updateUnreadCount() {
    fetch(ACADEMY_URL + 'api/get_unread_count.php')
        .then(r => r.json())
        .then(data => {
            updateNavbarBadge(data.unread_count);
            updateBellIndicator(data.unread_count);
        })
        .catch(() => {});
}

function updateNavbarBadge(count) {
    const el = document.querySelector('.navbar-badge-unread');
    if (!el) return;
    el.textContent = count;
    el.style.display = count > 0 ? 'inline-block' : 'none';
}

function updateBellIndicator(count) {
    const dot = document.querySelector('.bell-unread-dot');
    if (!dot) return;
    dot.style.display = count > 0 ? 'block' : 'none';
}

updateUnreadCount();
setInterval(updateUnreadCount, 10000);
document.addEventListener('visibilitychange', () => {
    if (!document.hidden) updateUnreadCount();
});

// ── Messages drawer ──────────────────────────────────────────────────
const drawer  = document.getElementById('msgDrawer');
const overlay = document.getElementById('msgOverlay');
const body    = document.getElementById('msgDrawerBody');

function openDrawer() {
    drawer.classList.add('open');
    overlay.style.display = 'block';
    loadMessages();
}

function closeDrawer() {
    drawer.classList.remove('open');
    overlay.style.display = 'none';
}

function loadMessages() {
    body.innerHTML = '<div class="msg-empty">Loading…</div>';
    fetch(ACADEMY_URL + 'api/get_messages_preview.php')
        .then(r => r.json())
        .then(data => renderMessages(data.messages))
        .catch(() => { body.innerHTML = '<div class="msg-empty">Could not load messages.</div>'; });
}

function renderMessages(msgs) {
    if (!msgs || msgs.length === 0) {
        body.innerHTML = '<div class="msg-empty"><i class="bi bi-inbox" style="font-size:2rem;"></i><br>No messages yet.</div>';
        return;
    }
    body.innerHTML = msgs.map(m => `
        <div class="msg-item ${m.is_read ? '' : 'unread'}"
             onclick="window.location='${ACADEMY_URL}message_view.php?id=${m.id}'">
            <div class="msg-item-title">${escHtml(m.title)}</div>
            <div class="msg-item-preview">${escHtml(m.preview)}…</div>
            <div class="msg-item-date">${escHtml(m.created_at)}</div>
        </div>`).join('');
}

function escHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, c =>
        ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}

// Use event delegation so these work regardless of when the DOM settles
document.addEventListener('click', function(e) {
    // Bell button (or anything inside it)
    if (e.target.closest('#bellBtn')) {
        openDrawer();
        return;
    }
    // Messages nav link — intercept unless already on messages.php
    if (e.target.closest('#msgNavLink') && !window.location.pathname.endsWith('messages.php')) {
        e.preventDefault();
        openDrawer();
        return;
    }
    // Drawer close button
    if (e.target.closest('#msgDrawerClose')) {
        closeDrawer();
        return;
    }
    // Overlay click
    if (e.target === overlay) {
        closeDrawer();
    }
});
</script>
