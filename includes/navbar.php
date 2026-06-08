<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar d-flex flex-column" role="navigation">

    <nav class="nav flex-column gap-1">
        <a class="nav-link <?php echo ($current_page == 'learning_dashboard.php') ? 'active' : ''; ?>" 
           href="<?php echo ACADEMY_URL; ?>learning_dashboard.php">
            <i class="bi bi-grid-3x3-gap-fill me-2"></i>Dashboard
        </a>

        <a class="nav-link <?php echo (strpos($current_page, 'courses') !== false) ? 'active' : ''; ?>" 
           href="<?php echo ACADEMY_URL; ?>courses/courses_catalogue.php">
            <i class="bi bi-journal-bookmark-fill me-2"></i>Courses
        </a>

        <a class="nav-link <?php echo ($current_page == 'students.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>students.php">
            <i class="bi bi-people-fill me-2"></i>Students
        </a>
        <a class="nav-link <?php echo ($current_page == 'assignments.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>assignments.php">
            <i class="bi bi-stickies-fill me-2"></i>Assignments
        </a>
        <a class="nav-link <?php echo ($current_page == 'mentors.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>mentors.php">
            <i class="bi bi-person-badge-fill me-2"></i>Mentors
        </a>

        <a class="nav-link <?php echo ($current_page == 'my_results.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>resources/practice_tests/my_results.php">
            <i class="bi bi-clipboard-data-fill me-2"></i>My Results
        </a>

        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/resources/') !== false && $current_page !== 'my_results.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>resources/resources_home.php">
            <i class="bi bi-collection-fill me-2"></i>Resources
        </a>

        
		<a class="nav-link <?php echo ($current_page == 'messages.php') ? 'active' : ''; ?>"
		   href="<?php echo ACADEMY_URL; ?>messages.php"
		   id="msgNavLink">
				<i class="bi bi-chat-dots-fill me-2"></i>
				Messages
				<span class="navbar-badge-unread badge bg-danger ms-2"
					style="font-size:0.7rem; animation:pulse 1.8s infinite; display:none;">
					0
				</span>
		</a>

	
        <a class="nav-link <?php echo ($current_page == 'analytics.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>analytics.php">
            <i class="bi bi-bar-chart-fill me-2"></i>Analytics
        </a>
        <a class="nav-link <?php echo ($current_page == 'events.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>events.php">
            <i class="bi bi-calendar-event-fill me-2"></i>Events
        </a>

        <a class="nav-link mt-2" href="<?php echo ACADEMY_URL; ?>edu_hub_logout.php">
            <i class="bi bi-box-arrow-right me-2"></i>Logout
        </a>
    </nav>
</aside>
