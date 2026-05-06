<style>
	:root {
		--sidebar-bg: #f8fafc;
		--accent: #0b77ff;
		--muted: #6b7280;
		--card-radius: 14px;
		--soft: #eef6ff;
	}

	body {
		background: #f1f6fb;
		color: #0f172a;
		font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
		min-height: 100vh;
		overflow-x: hidden;
	}
	/* Dark mode styles */
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

	/* Mobile Header */
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

	.sidebar {
		width: 220px;
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

	.blue-pill {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		background: linear-gradient(90deg, #0b77ff, #6f8cff);
		color: #fff;
		width: 44px;
		height: 44px;
		border-radius: 10px;
	}

	.main-wrapper {
		margin-left: 260px;
		margin-right: 280px;
		padding: 2rem;
	}

	.advert-sidebar {
		width: 280px;
		position: fixed;
		top: 0;
		right: 0;
		height: 100vh;
		background: var(--sidebar-bg);
		border-left: 1px solid rgba(15,23,42,0.04);
		padding: 1.25rem;
		overflow-y: auto;
	}

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

	.nav-links a:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 12px rgba(11, 119, 255, 0.3);
	}

	.nav-links a.secondary {
		background: #fff;
		color: var(--accent);
		border: 2px solid var(--accent);
	}

	.nav-links a.secondary:hover {
		background: #f0f7ff;
	}

	/* Topbar */
	.topbar {
		margin-left: 220px;
		padding: 1rem 1.5rem;
		background: #fff;
		border-bottom: 1px solid rgba(15,23,42,0.04);
		position: sticky;
		top: 0;
		z-index: 50;
		transition: all 0.3s ease;
	}

	/* Advertisement Styles */
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

	/* On wide screens with the ad sidebar visible, pull the topbar in so it sits
	   cleanly between the left nav and the right ad column */
	@media (min-width: 1400px) {
		.topbar {
			margin-right: 280px;
		}
	}

	/* Responsive Design */
	@media (max-width: 1399px) {
		.advert-sidebar {
			display: none;
		}
		.main-wrapper {
			margin-right: 0;
		}
	}

	@media (max-width: 1199px) {
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

		.main-wrapper {
			margin-left: 0;
			margin-right: 0;
			padding-top: 90px;
		}
	}

	@media (max-width: 768px) {
		.main-wrapper {
			padding: 1rem;
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