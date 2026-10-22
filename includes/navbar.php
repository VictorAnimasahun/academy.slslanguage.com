<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar d-flex flex-column" role="navigation">

    <nav class="nav flex-column gap-1">
        <a class="nav-link <?php echo ($current_page == 'learning_dashboard.php') ? 'active' : ''; ?>" 
           href="<?php echo ACADEMY_URL; ?>learning_dashboard.php">
            Dashboard
        </a>

        <a class="nav-link <?php echo (strpos($current_page, 'courses') !== false) ? 'active' : ''; ?>" 
           href="<?php echo ACADEMY_URL; ?>courses/courses_catalogue.php">
            Courses
        </a>

        <a class="nav-link <?php echo ($current_page == 'students.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>students.php">
            Students
        </a>
        <a class="nav-link <?php echo ($current_page == 'assignments.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>assignments.php">
            Assignments
        </a>
        <a class="nav-link <?php echo ($current_page == 'mentors.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>mentors.php">
            Mentors
        </a>

        <a class="nav-link <?php echo ($current_page == 'my_results.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>resources/practice_tests/my_results.php">
            My Results
        </a>

        <a class="nav-link <?php echo (strpos($_SERVER['PHP_SELF'], '/resources/') !== false && $current_page !== 'my_results.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>resources/resources_home.php">
            Resources
        </a>


		<a class="nav-link <?php echo (in_array($current_page, ['threads.php', 'thread_view.php'])) ? 'active' : ''; ?>"
		   href="<?php echo ACADEMY_URL; ?>threads.php">
            Messages
		</a>

		<!-- notifications.php is a unified feed (announcements + unread messages + assignment-due +
		     event reminders); this used to point straight at messages.php (the broadcast
		     announcement list), which is still where an announcement row itself opens
		     (message_view.php). get_unread_count.php now counts across both sources. -->
		<a class="nav-link <?php echo (in_array($current_page, ['notifications.php', 'messages.php', 'message_view.php'])) ? 'active' : ''; ?>"
		   href="<?php echo ACADEMY_URL; ?>notifications.php"
		   id="msgNavLink" style="display:flex;align-items:center;white-space:nowrap;">
            Notifications
				<span class="navbar-badge-unread badge bg-danger ms-1"
					style="font-size:0.7rem; animation:pulse 1.8s infinite; display:none;">
					0
				</span>
		</a>

	
        <a class="nav-link <?php echo ($current_page == 'analytics.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>analytics.php">
            Analytics
        </a>
        <a class="nav-link <?php echo ($current_page == 'events.php') ? 'active' : ''; ?>"
           href="<?php echo ACADEMY_URL; ?>events.php">
            Events
        </a>

        <a class="nav-link mt-2" href="<?php echo ACADEMY_URL; ?>edu_hub_logout.php">
            Logout
        </a>
    </nav>
</aside>
<?php
// Every page that has the sidebar also gets the top bar (logo, dark mode, notifications) and the menu script,
// once each (both files guard against being included twice).
include __DIR__ . '/topbar.php';
include __DIR__ . '/navbar_scripts.php';
?>
