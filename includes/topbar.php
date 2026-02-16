<?php
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="topbar d-flex align-items-center justify-content-between">
	<div class="d-flex align-items-center gap-3">
		<?php if (isset($_GET['status']) && $_GET['status'] === 'registration_success'): ?>
			<h1 class="h4 mb-0">Welcome to EduHub, <?php echo $userName; ?>! 🎉</h1>
		<?php else: ?>
			<h1 class="h4 mb-0">Welcome back, <?php echo $userName; ?>! 👋</h1>
		<?php endif; ?>
	</div>

	<div class="d-flex align-items-center gap-3">
		<button id="themeToggle" class="btn btn-sm btn-outline-secondary" aria-label="Toggle dark mode">
			🌙 Dark Mode
		</button>
		<div class="d-flex align-items-center me-3">
			<button class="btn btn-link p-0 me-2" aria-label="Notifications">
				<div style="position:relative;">
					<i class="bi bi-bell" style="font-size:1.25rem;color:#334155;"></i>
					<span class="bell-unread-dot" style="
						position:absolute;
						top:-4px;
						right:-6px;
						width:10px;
						height:10px;
						background:#0b77ff;
						border-radius:50%;
						animation:pulse 1.8s infinite;
						box-shadow:0 0 6px rgba(11,119,255,0.6);
						display:none;
					"></span>
				</div>
			</button>
			<button class="btn btn-link p-0 me-3" aria-label="Global menu">
				<i class="bi bi-globe" style="font-size:1.2rem;color:#334155;"></i>
			</button>
			<div class="d-flex align-items-center gap-2">
				<div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(90deg,#7c3aed,#ec4899);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">
					<?php echo strtoupper(substr($userName, 0, 2)); ?>
				</div>
			</div>
		</div>
	</div>
</header>