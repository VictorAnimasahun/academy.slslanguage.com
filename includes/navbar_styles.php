<style>
	:root {
		--sidebar-bg: #f8fafc;
		--accent: #0b77ff;
		--muted: #6b7280;
		--card-radius: 14px;
		--soft: #eef6ff;
		--topbar-h: 60px;
		--sidebar-w: 180px;
		--advert-w: 220px;
	}

	body {
		background: #f1f6fb;
		color: #0f172a;
		font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
		min-height: 100vh;
		overflow-x: hidden;
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
		box-shadow: 0 6px 18px rgba(0,0,0,0.3);
	}

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
		box-shadow: 0 2px 8px rgba(15,23,42,0.05);
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

	/* ── Sidebar ──────────────────────────────────────────────────── */
	.sidebar {
		width: var(--sidebar-w);
		background: var(--sidebar-bg);
		min-height: 100vh;
		border-right: 1px solid rgba(15,23,42,0.04);
		position: fixed;
		top: 0;
		left: 0;
		padding: 1.25rem;
		z-index: 1000;
		transition: transform 0.3s ease;
	}

	.sidebar .brand {
		font-weight: 700;
		font-size: 1.125rem;
		display: flex;
		align-items: center;
		gap: .75rem;
	}

	.sidebar .nav-link {
		color: #475569;
		border-radius: .75rem;
		padding: .6rem .8rem;
		transition: all 0.2s ease;
	}

	.sidebar .nav-link.active {
		background: linear-gradient(90deg, #e6f0ff, #f0f7ff);
		color: var(--accent);
		font-weight: 600;
	}

	/* ── Desktop sidebar collapse ─────────────────────────────────── */
	body.sidebar-collapsed .sidebar {
		transform: translateX(-100%);
	}
	body.sidebar-collapsed .main-wrapper {
		margin-left: 0 !important;
	}

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
		border-left: 1px solid rgba(15,23,42,0.04);
		padding: 1.25rem;
		overflow-y: auto;
	}

	/* ── Content card styles ──────────────────────────────────────── */
	.course-card {
		background: #fff;
		border-radius: var(--card-radius);
		padding: 2rem;
		box-shadow: 0 6px 18px rgba(15,23,42,0.04);
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
		border-left: 4px solid var(--accent);
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
		box-shadow: 0 2px 8px rgba(15,23,42,0.04);
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

		.sidebar {
			transform: translateX(-100%);
			box-shadow: 2px 0 10px rgba(0,0,0,0.1);
		}

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
