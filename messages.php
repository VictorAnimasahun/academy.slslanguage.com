<?php
require_once __DIR__ . '/bootstrap.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: edu_hub_registration.php?message=Please+login+to+access+messages");
    exit();
}

$user_id = filter_var($_SESSION['user_id'], FILTER_VALIDATE_INT);
if (!$user_id) {
    header("Location: edu_hub_registration.php");
    exit();
}

// Helper for escaping HTML
if (!function_exists('e')) {
  function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

// Get all messages for this student
$query = "
    SELECT DISTINCT m.*, 
           (SELECT COUNT(*) FROM broadcast_message_reads WHERE message_id = m.id AND student_id = ?) as is_read
    FROM broadcast_messages m
    WHERE (
        m.target_all_students = 1
        OR FIND_IN_SET(?, m.target_student_ids) > 0
        OR EXISTS (
            SELECT 1 FROM enrollments e 
            WHERE e.student_id = ? 
            AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0
        )
    )
    ORDER BY m.created_at DESC
";

$messagesStmt = executeQuery($db, $query, [$user_id, $user_id, $user_id]);
$messages = $messagesStmt ? $messagesStmt->fetchAll(PDO::FETCH_ASSOC) : [];

// Count unread messages
$unread_count = 0;
foreach ($messages as $msg) {
    if ($msg['is_read'] == 0) {
        $unread_count++;
    }
}

// User display data
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>My Messages - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    
    <style>
		main.content {
			max-width: 1000px;
			margin: 0 auto;
			padding: 1rem;
		}

        .message-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .message-card.unread {
            border-left-color: #0b77ff;
            background: linear-gradient(90deg, rgba(11,119,255,0.05) 0%, var(--card-bg) 100%);
        }
        
        .message-type-badge {
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .type-announcement { background: #fed7aa; color: #9a3412; }
        .type-course_update { background: #bfdbfe; color: #1e40af; }
        .type-new_material { background: #bbf7d0; color: #166534; }
        .type-targeted { background: #e9d5ff; color: #6b21a8; }
        
        .unread-dot {
            width: 8px;
            height: 8px;
            background: #0b77ff;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .message-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0.5rem 0;
        }
        
        .message-preview {
            color: var(--muted);
            line-height: 1.5;
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }
        
        .message-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.75rem;
        }
        
        .filter-btn {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border-color);
            background: var(--card-bg);
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .filter-btn:hover {
            background: var(--hover-bg);
            border-color: var(--border-color);
        }
        
        .filter-btn.active {
            background: #0b77ff;
            color: white;
            border-color: #0b77ff;
        }
        
        .no-messages {
            text-align: center;
            padding: 4rem 1rem;
            color: var(--muted);
        }
        
        .no-messages i {
            font-size: 4rem;
            opacity: 0.4;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="light">
    <!-- Mobile Header -->
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

    <!-- Sidebar Navigation -->
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <!-- Topbar -->
    <?php include INCLUDES_PATH . '/topbar.php'; ?>

    <!-- Main Content -->
    <main class="content container-fluid">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">📬 My Messages</h2>
                        <p class="text-muted mb-0">
                            Updates, announcements, and course materials
                            <?php if ($unread_count > 0): ?>
                                <span class="badge bg-danger ms-2"><?= $unread_count ?> Unread</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="d-flex gap-2 mb-4 flex-wrap">
                    <button class="filter-btn active" data-filter="all">All Messages</button>
                    <button class="filter-btn" data-filter="announcement">📣 Announcements</button>
                    <button class="filter-btn" data-filter="course_update">📚 Course Updates</button>
                    <button class="filter-btn" data-filter="new_material">📄 New Materials</button>
                    <button class="filter-btn" data-filter="targeted">✉️ Personal</button>
                    <button class="filter-btn" data-filter="unread">Unread Only</button>
                </div>

                <!-- Messages List -->
                <div id="messagesGrid">
                    <?php if (empty($messages)): ?>
                        <div class="no-messages">
                            <i class="bi bi-envelope-open"></i>
                            <p class="mb-0">No messages yet</p>
                            <small class="text-muted">You'll see course updates and announcements here</small>
                        </div>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <a href="message_view.php?id=<?= $msg['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                                <div class="message-card <?= $msg['is_read'] == 0 ? 'unread' : '' ?>" 
                                     data-message-id="<?= $msg['id'] ?>"
                                     data-type="<?= $msg['message_type'] ?>"
                                     data-is-read="<?= $msg['is_read'] ?>">
                                    
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="message-type-badge type-<?= $msg['message_type'] ?>">
                                            <?= ucwords(str_replace('_', ' ', $msg['message_type'])) ?>
                                        </span>
                                        <?php if ($msg['is_read'] == 0): ?>
                                            <span class="unread-dot"></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <h3 class="message-title"><?= e($msg['title']) ?></h3>
                                    
                                    <p class="message-preview">
                                        <?= e(substr($msg['content'], 0, 200)) ?>
                                        <?= strlen($msg['content']) > 200 ? '...' : '' ?>
                                    </p>
                                    
                                    <div class="message-footer">
                                        <span>
                                            <i class="bi bi-calendar-event me-1"></i>
                                            <?= date('M j, Y g:i A', strtotime($msg['created_at'])) ?>
                                        </span>
                                        <span class="text-primary">
                                            Click to read →
                                        </span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile Menu Script (same as dashboard)
        const menuToggle = document.getElementById('menuToggle');
        const sidebarElement = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebarElement.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
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

        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200 && sidebarElement.classList.contains('active')) {
                    toggleMenu();
                }
            });
        });

        // Theme Toggle Functionality (same as dashboard)
        const body = document.body;
        const toggleBtn = document.getElementById('themeToggle');
        
        const savedTheme = localStorage.getItem('eduhub-theme') || 'light';
        applyTheme(savedTheme);
        
        function applyTheme(theme) {
            body.classList.remove('light', 'dark');
            body.classList.add(theme);
            
            if (toggleBtn) {
                toggleBtn.textContent = theme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
                toggleBtn.setAttribute('aria-label', `Switch to ${theme === 'dark' ? 'light' : 'dark'} mode`);
            }
        }
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const currentTheme = body.classList.contains('dark') ? 'dark' : 'light';
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                applyTheme(newTheme);
                localStorage.setItem('eduhub-theme', newTheme);
            });
        }

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.message-card');
                
                cards.forEach(card => {
                    const type = card.dataset.type;
                    const isRead = card.dataset.isRead;
                    
                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else if (filter === 'unread') {
                        card.style.display = isRead === '0' ? 'block' : 'none';
                    } else {
                        card.style.display = type === filter ? 'block' : 'none';
                    }
                });
            });
        });

        // Animation on load (same as dashboard)
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.message-card').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
</body>
</html>