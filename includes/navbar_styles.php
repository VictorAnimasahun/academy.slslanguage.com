<style>
	:root {
		/* White menu and right column (founder, 2026-10-23: "change those black backgrounds back to white"). */
		--sidebar-bg: #ffffff;
		--sidebar-ink: #475569;
		--sidebar-ink-active: #0b77ff;
		--sidebar-hover-bg: #f1f5f9;
		--sidebar-active-bg: #eff6ff;
		--accent: #0b77ff;
		--muted: #6b7280;
		--card-radius: 14px;
		--soft: #eef6ff;
		--topbar-h: 60px;
		--sidebar-w: 72px;   /* a slim rail of icons; names show on hover */
		--advert-w: 220px;
	}

	body {
		background: #f1f6fb;
		color: #0f172a;
		font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
		min-height: 100vh;
		overflow-x: hidden;
	}

	/* ── One design for every academy page (founder, 2026-10-28: no coloured left strips, no rounded edges, "use
	   the same sharp design you used in the sls admin"). Corners are 3px everywhere -- Bootstrap's own classes
	   through its radius variables, the site's own CSS rewritten to 3px at the source -- circles (50%) stay
	   round. No shadows: borders separate things. Test screens keep their exam theme (exam_theme.css). ── */
	:root {
		--bs-border-radius: 3px; --bs-border-radius-sm: 2px; --bs-border-radius-lg: 3px;
		--bs-border-radius-xl: 3px; --bs-border-radius-xxl: 3px; --bs-border-radius-pill: 3px;
		--card-radius: 3px;
	}
	body:not(.exam-page) :where(*):not(:focus-visible):not(.bell-link):not(dialog):not(.dropdown-menu):not(.ib-menu):not(.modal-content) {
		box-shadow: none !important;
	}
	/* Content starts once below the top bar (.main-wrapper already clears it, topbar.php), and fills the room
	   between the menu rail and the advert column. */
	@media (min-width: 1200px) {
		.main-wrapper > .content, .main-wrapper > main.content { margin-top: 0; }
		body:has(.advert-sidebar) .main-wrapper > .content { max-width: calc(100vw - var(--sidebar-w) - var(--advert-w)); }
		/* One 24px gutter on every page: a page whose .content brings its own padding (p-4) gets none from the
		   wrapper -- some pages sit outside a .d-flex and got 2rem more, so they started 32px further in -- and the
		   lesson-type pages (a .course-card straight inside the wrapper) get 1.5rem, not 2rem. */
		body .main-wrapper { padding: 1.5rem; }
		body .main-wrapper:has(> .content) { padding: 0; }
		/* A Bootstrap .container wrapping a page's content added 12px more inset and, above 1400px, a width cap. */
		.main-wrapper > .content > .container, .main-wrapper > .content > .container-fluid,
		main.main-wrapper > .container, main.main-wrapper > .container-fluid { max-width: none; padding-left: 0; padding-right: 0; }
	}

	/* ── Dark mode ─────────────────────────────────────────────────── */
	body.dark {
		background: #121212;
		color: #e4e6eb;
	}

	body.dark .sidebar {
		background: #1e1e1e;
		border-right-color: rgba(255,255,255,0.1);
	}

	body.dark .topbar,
	body.dark .mobile-header {
		background: #1f1f1f;
		border-bottom-color: rgba(255,255,255,0.1);
	}

	body.dark .stat-card,
	body.dark .course-card,
	body.dark .small-card {
		background: #1f1f1f;
		border: 1px solid rgba(255,255,255,0.08);
	}

	/* Course pages (assets/css/courses.css is light-only): the light info box, the breadcrumb's current page and
	   the plain headings all went dark-on-dark (founder, 2026-10-28). */
	body.dark .highlight-box {
		background: #1b2638;
		border-color: #2c3e57;
		color: #e2e8f0;
	}
	body.dark .breadcrumb-item.active,
	body.dark .breadcrumb-item + .breadcrumb-item::before { color: #94a3b8; }
	body.dark .course-card h1,
	body.dark .course-card h3,
	body.dark .course-card h4,
	body.dark .course-card h5 { color: #f1f5f9; }
	body.dark .topbar-name { color: #f1f5f9 !important; }
	body.dark .course-card .content-section h2 { color: #7cb4ff; }   /* courses.css's navy headings vanished on dark */
	body.dark .advert-sidebar .course-card { background: #1f1f1f !important; color: #e4e6eb !important; }   /* Quick Access: inline white */
	body.dark .advert-sidebar .course-card a[style*="background:#fff"] { background: transparent !important; }
	body.dark .ad-container { background: #1f1f1f; border-color: rgba(255,255,255,0.08); }

	body.dark .calendar {
		background: linear-gradient(180deg, #1f1f1f, #2a2a2a);
	}

	body.dark .advert-sidebar {
		background: #1e1e1e;
		border-left-color: rgba(255,255,255,0.08);
	}

	body.dark .ad-placeholder {
		background: #2a2a2a;
		border-color: rgba(255,255,255,0.1);
		color: #94a3b8;
	}

	body.dark .text-muted {
		color: #94a3b8 !important;
	}

	body.dark .nav-link {
		color: #cbd5e1;
	}

	body.dark .nav-link:hover,
	body.dark .nav-link.active {
		background: rgba(255,255,255,0.07);
		color: #fff;
	}

	/* ── Topbar — fixed full-width header ─────────────────────────── */
	.topbar {
		position: fixed;
		top: 0; left: 0; right: 0;
		height: var(--topbar-h);
		background: #fff;
		border-bottom: 1px solid rgba(15,23,42,0.07);
		z-index: 1001;
		padding: 0 1.25rem;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	/* Shift panels below the fixed topbar on desktop — moved to topbar.php
	   itself (inline <style>, scoped by that partial's own presence on the
	   page) instead of `body:has(.topbar)` here. :has() isn't supported in
	   every browser (older Firefox/Safari); when it silently doesn't match,
	   the sidebar/advert-sidebar stayed at top:0 and the fixed, full-width
	   topbar (z-index 1001) visually covered their top ~60px — reported as
	   "the top nav extending into the space of the right nav, hiding the
	   top of the quick links box" on some students' laptops. See
	   topbar.php for the replacement rule. */

	/* ── Mobile header ────────────────────────────────────────────── */
	.mobile-header {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		height: 70px;
		background: #fff;
		border-bottom: 1px solid rgba(15,23,42,0.1);
		z-index: 1100;
		padding: 0 1rem;
		align-items: center;
		justify-content: space-between;
	}

	.mobile-menu-toggle {
		background: transparent;
		border: none;
		color: var(--accent);
		font-size: 1.75rem;
		cursor: pointer;
		padding: 0.5rem;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	.mobile-brand {
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.mobile-brand .blue-pill {
		width: 40px;
		height: 40px;
	}

	.mobile-overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: rgba(0, 0, 0, 0.5);
		z-index: 999;
	}

	/* ── Sidebar: a slim rail of icons, each name shown on hover (founder, 2026-10-23, after Canvas) ── */
	.sidebar {
		width: var(--sidebar-w);
		background: var(--sidebar-bg);
		min-height: 100vh;
		border-right: 1px solid #e2e8f0;
		position: fixed;
		top: 0;
		left: 0;
		padding: .75rem 0;
		align-items: center;
		z-index: 1000;
		transition: transform 0.3s ease;
	}

	.sidebar .nav { align-items: center; }

	.sidebar .nav-link {
		position: relative;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 48px;
		height: 48px;
		padding: 0;
		color: var(--sidebar-ink);
		border-radius: 12px;
		transition: background .15s ease, color .15s ease;
	}

	.sidebar .nav-link:hover {
		background: var(--sidebar-hover-bg);
		color: #0f172a;
	}

	.sidebar .nav-link.active {
		background: var(--sidebar-active-bg);
		color: var(--sidebar-ink-active);
	}

	/* The name: a dark label to the right of the icon, on hover or keyboard focus. */
	.sidebar .nav-label {
		position: absolute;
		left: calc(100% + 12px);
		top: 50%;
		transform: translate(-4px, -50%);
		background: #0f172a;
		color: #fff;
		font-size: .82rem;
		font-weight: 600;
		white-space: nowrap;
		padding: .4rem .65rem;
		border-radius: 6px;
		opacity: 0;
		pointer-events: none;
		transition: opacity .12s ease, transform .12s ease;
		z-index: 1100;
	}
	.sidebar .nav-label::before {
		content: '';
		position: absolute;
		right: 100%;
		top: 50%;
		transform: translateY(-50%);
		border: 6px solid transparent;
		border-right-color: #0f172a;
	}
	.sidebar .nav-link:hover .nav-label,
	.sidebar .nav-link:focus-visible .nav-label {
		opacity: 1;
		transform: translate(0, -50%);
	}

	/* Unread count: a small badge on the bell. */
	.sidebar .navbar-badge-unread {
		position: absolute;
		top: 4px;
		right: 2px;
		font-size: .62rem;
		padding: .2em .4em;
		border-radius: 999px;
	}

	/* The old "minimise menu" state has no button any more: never hide the rail on a computer. */
	body.sidebar-collapsed .sidebar { transform: none; }
	body.sidebar-collapsed .main-wrapper { margin-left: var(--sidebar-w) !important; }

	/* ── Shared pill/logo ─────────────────────────────────────────── */
	.blue-pill {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(90deg, #0b77ff, #6f8cff);
		color: #fff;
		width: 44px;
		height: 44px;
		border-radius: 10px;
		flex-shrink: 0;
	}

	/* ── Main content area ────────────────────────────────────────── */
	.main-wrapper {
		margin-left: var(--sidebar-w);
		margin-right: 0;
		padding: 2rem;
		transition: margin-left 0.3s ease;
	}
	body:has(.advert-sidebar) .main-wrapper {
		margin-right: var(--advert-w);
	}

	/* ── Exam fullscreen ("focus mode") ───────────────────────────────
	   Toggled via #examFullscreenToggle in navbar_scripts.php on test-
	   taking pages only. Hides the left/right nav entirely. The site-wide
	   topbar ("menu bar") declutters instead of disappearing — everything
	   in it except #examFullscreenToggle carries .exam-fs-hide (see
	   topbar.php), so the bar itself stays (at its normal height, so
	   nothing needs to shift up to fill it) but only shows the one button
	   that turns Focus Mode back off. Hiding the whole .topbar would have
	   hidden that exit button along with it. The TEST's own header
	   (sticky-header/celpip-header/part-header, wherever the page has
	   one, showing progress/timer/admin banner) is untouched throughout;
	   see exam_theme.css for the matching !important overrides needed on
	   pages that also load that file. */
	body.exam-fullscreen .sidebar,
	body.exam-fullscreen .advert-sidebar {
		display: none;
	}
	body.exam-fullscreen .topbar .exam-fs-hide {
		display: none;
	}
	body.exam-fullscreen .main-wrapper {
		margin-left: 0;
		margin-right: 0;
	}

	/* ── Right advert sidebar ─────────────────────────────────────── */
	.advert-sidebar {
		width: var(--advert-w);
		position: fixed;
		top: 0;
		right: 0;
		height: 100vh;
		background: var(--sidebar-bg);
		border-left: 1px solid #e2e8f0;
		padding: 1.25rem;
		overflow-y: auto;
	}

	.advert-sidebar .text-muted {
		color: #64748b !important;
	}

	/* ── Content card styles ──────────────────────────────────────── */
	.course-card {
		background: #fff;
		border-radius: var(--card-radius);
		padding: 2rem;
		border: 1px solid rgba(15,23,42,0.08);
		margin-bottom: 1.5rem;
	}

	.media-section {
		margin: 1.5rem 0;
	}

	video, audio {
		width: 100%;
		border-radius: 8px;
		margin-top: 0.5rem;
	}

	.tips {
		background: linear-gradient(135deg, #e6f0ff 0%, #f0f7ff 100%);
		border: 1px solid #cfe0fb;
		padding: 1.5rem;
		margin: 1.5rem 0;
		border-radius: 8px;
	}

	.tips h3 {
		color: var(--accent);
		font-size: 1.1rem;
		margin-bottom: 1rem;
	}

	.nav-links {
		margin-top: 2rem;
		display: flex;
		gap: 1rem;
		flex-wrap: wrap;
	}

	.nav-links a {
		padding: 0.75rem 1.5rem;
		background: linear-gradient(90deg, #0b77ff, #6f8cff);
		color: #fff;
		text-decoration: none;
		border-radius: 8px;
		transition: all 0.3s ease;
		font-weight: 500;
	}

	.nav-links a.secondary {
		background: #fff;
		color: var(--accent);
		border: 2px solid var(--accent);
	}

	/* ── Advertisement styles ─────────────────────────────────────── */
	.ad-container {
		background: #fff;
		border-radius: var(--card-radius);
		padding: 1rem;
		margin-bottom: 1rem;
		border: 1px solid rgba(15,23,42,0.08);
		min-height: 250px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		color: var(--muted);
		font-size: 0.875rem;
	}

	.ad-container.large {
		min-height: 400px;
	}

	.ad-placeholder {
		padding: 2rem 1rem;
	}

	/* ── Responsive ───────────────────────────────────────────────── */
	@media (max-width: 1199px) {
		.advert-sidebar {
			display: none;
		}

		/* Hide desktop topbar — mobile-header takes over */
		.topbar { display: none; }

		/* Phones open the menu as a drawer with each name beside its icon (no hover on a touch screen). */
		.sidebar {
			transform: translateX(-100%);
			width: 240px;
			padding: calc(70px + 1rem) .75rem 1rem;   /* starts below the phone header, so the first item shows */
			align-items: stretch;
		}
		.sidebar .nav { align-items: stretch; }
		.sidebar .nav-link { width: auto; justify-content: flex-start; gap: .85rem; padding: 0 .85rem; }
		.sidebar .nav-label {
			position: static; transform: none; opacity: 1; background: none; color: inherit;
			padding: 0; font-size: .95rem; font-weight: 500;
		}
		.sidebar .nav-label::before { display: none; }
		.sidebar .navbar-badge-unread { position: static; margin-left: auto; }
		body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
		body.sidebar-collapsed .sidebar.active { transform: translateX(0); }

		.sidebar.active {
			transform: translateX(0);
		}

		.mobile-header {
			display: flex;
		}

		.mobile-overlay.active {
			display: block;
		}

		/* body:has(.advert-sidebar) below matches even though the advert
		   column is display:none here, and out-specifies a bare
		   .main-wrapper -- without it every page kept a phantom 220px right
		   margin on phones and its content got squeezed into a sliver. */
		.main-wrapper,
		body:has(.advert-sidebar) .main-wrapper {
			margin-left: 0;
			margin-right: 0;
			padding-top: 90px;
		}
	}

	@media (max-width: 768px) {
		/* Wide data tables (gap-fill sheets, schedules, comparison charts)
		   scroll sideways inside themselves instead of stretching the whole
		   page wider than the phone. */
		.main-wrapper table { display: block; max-width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
		.main-wrapper img, .main-wrapper video, .main-wrapper canvas { max-width: 100%; height: auto; }
		.main-wrapper {
			padding: 1rem;
			padding-top: 90px;
		}
		.nav-links {
			flex-direction: column;
		}
		.nav-links a {
			width: 100%;
			text-align: center;
		}
	}

	@keyframes pulse {
		0% { transform: scale(1); opacity:1; }
		50% { transform: scale(1.2); opacity:0.6; }
		100% { transform: scale(1); opacity:1; }
	}

	/* Global: no movement on button hover */
	button:hover,
	.btn:hover,
	a.btn:hover,
	[class*="btn-"]:hover,
	.nav-links a:hover {
		transform: none !important;
	}

</style>

<?php
// Inside the sls_mobile app (its WebView adds "SLSApp" to the user agent) the
// app supplies its own bottom tab bar, so hide the site's topbar, sidebar,
// mobile header and ad column and give the content the full width. Normal
// browser visitors never match, so their pages are unchanged.
if (strpos($_SERVER['HTTP_USER_AGENT'] ?? '', 'SLSApp') !== false): ?>
<style>
	.topbar, .sidebar, .mobile-header, .mobile-overlay, .advert-sidebar { display: none !important; }
	body .main-wrapper,
	body:has(.advert-sidebar) .main-wrapper {
		margin: 0 !important;
		padding-top: 1rem !important;
		max-width: 100% !important;
	}
	body .content {
		margin: 0 !important;
		max-width: 100% !important;
	}
</style>
<?php endif; ?>
