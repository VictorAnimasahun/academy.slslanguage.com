<?php
$current_page = basename($_SERVER['PHP_SELF']);
require_once __DIR__ . '/nav_icons.php';

// The left menu: a slim rail of icons; each shows its name on hover (founder, 2026-10-23, after Canvas's menu).
// On phones it opens from the header's menu button and shows each name beside its icon (there is no hover there).
$navItems = [
    ['dashboard',     'Dashboard',     'learning_dashboard.php',                     $current_page == 'learning_dashboard.php'],
    ['courses',       'Courses',       'courses/courses_catalogue.php',              strpos($current_page, 'courses') !== false],
    ['students',      'Students',      'students.php',                               $current_page == 'students.php'],
    ['assignments',   'Assignments',   'assignments.php',                            $current_page == 'assignments.php'],
    ['mentors',       'Mentors',       'mentors.php',                                $current_page == 'mentors.php'],
    ['results',       'My Results',    'resources/practice_tests/my_results.php',    $current_page == 'my_results.php'],
    ['resources',     'Resources',     'resources/resources_home.php',               strpos($_SERVER['PHP_SELF'], '/resources/') !== false && $current_page !== 'my_results.php'],
    ['messages',      'Messages',      'messages.php',                               in_array($current_page, ['messages.php', 'message_view.php', 'threads.php', 'thread_view.php'])],
    // notifications.php is a unified feed (announcements + unread messages + assignment-due + event reminders);
    // get_unread_count.php counts across both sources and fills the badge.
    ['notifications', 'Notifications', 'notifications.php',                          $current_page == 'notifications.php'],
    ['analytics',     'Analytics',     'analytics.php',                              $current_page == 'analytics.php'],
    ['events',        'Events',        'events.php',                                 $current_page == 'events.php'],
];
?>

<aside class="sidebar d-flex flex-column" role="navigation" aria-label="Main menu">

    <nav class="nav flex-column gap-1">
        <?php foreach ($navItems as [$icon, $label, $href, $active]): ?>
            <a class="nav-link <?= $active ? 'active' : '' ?>" href="<?= ACADEMY_URL . $href ?>" aria-label="<?= $label ?>"
               <?= $icon === 'notifications' ? 'id="msgNavLink"' : '' ?>>
                <?= nav_icon($icon) ?>
                <span class="nav-label"><?= $label ?></span>
                <?php if ($icon === 'notifications'): ?>
                    <span class="navbar-badge-unread badge bg-danger" style="display:none;">0</span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <nav class="nav flex-column mt-auto">
        <a class="nav-link" href="<?= ACADEMY_URL ?>edu_hub_logout.php" aria-label="Log out">
            <?= nav_icon('logout') ?>
            <span class="nav-label">Log out</span>
        </a>
    </nav>
</aside>
<?php
// Every page that has the sidebar also gets the top bar (logo, dark mode, notifications) and the menu script,
// once each (both files guard against being included twice).
include __DIR__ . '/topbar.php';
include __DIR__ . '/navbar_scripts.php';
?>
