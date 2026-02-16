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

// Ensure valid ID
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($message_id <= 0) {
    header("Location: messages.php");
    exit();
}

// Helper for escaping HTML
if (!function_exists('e')) {
  function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}

// Fetch message and check if student has access to it
$query = "
    SELECT m.* 
    FROM broadcast_messages m
    WHERE m.id = ?
    AND (
        m.target_all_students = 1
        OR FIND_IN_SET(?, m.target_student_ids) > 0
        OR EXISTS (
            SELECT 1 FROM enrollments e 
            WHERE e.student_id = ? 
            AND FIND_IN_SET(e.course_id, m.target_course_ids) > 0
        )
    )
";

$messageStmt = executeQuery($db, $query, [$message_id, $user_id, $user_id]);
$message = $messageStmt ? $messageStmt->fetch(PDO::FETCH_ASSOC) : null;

if (!$message) {
    header("Location: messages.php");
    exit();
}

// Check if already read
$checkReadSQL = "SELECT id FROM broadcast_message_reads WHERE message_id = ? AND student_id = ?";
$checkStmt = executeQuery($db, $checkReadSQL, [$message_id, $user_id]);
$is_read = $checkStmt && $checkStmt->fetch() ? true : false;

// If unread, mark as read
if (!$is_read) {
    $markReadSQL = "INSERT INTO broadcast_message_reads (message_id, student_id) VALUES (?, ?)";
    executeQuery($db, $markReadSQL, [$message_id, $user_id]);
    $is_read = true;
}

// User display data
$userName = isset($_SESSION['user_firstname']) ? htmlspecialchars($_SESSION['user_firstname']) : 'Learner';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= e($message['title']); ?> - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- External Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Marked.js for Markdown rendering -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    
    <style>

		main.content {
			max-width: 1000px;
			margin: 0 auto;
			padding: 1rem;
		}
        .message-view-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #0b77ff;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: background 0.2s;
            margin-bottom: 1.5rem;
        }
        
        .back-link:hover {
            background: rgba(11, 119, 255, 0.1);
        }
        
        .message-type-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1rem;
        }
        
        .type-announcement { background: #fed7aa; color: #9a3412; }
        .type-course_update { background: #bfdbfe; color: #1e40af; }
        .type-new_material { background: #bbf7d0; color: #166534; }
        .type-targeted { background: #e9d5ff; color: #6b21a8; }
        
        .message-view-card h1 {
            margin: 0 0 1rem 0;
            color: var(--text-dark);
            font-size: 2rem;
            line-height: 1.3;
        }
        
        .message-meta {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1rem 0;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 2rem;
            color: var(--muted);
            font-size: 0.9rem;
            flex-wrap: wrap;
        }
        
        .message-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .message-body {
            line-height: 1.8;
            color: var(--text-dark);
            font-size: 1.05rem;
            white-space: pre-wrap;
            margin-bottom: 2rem;
        }
        
        .message-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #0b77ff;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.2s;
        }
        
        .message-link:hover {
            background: #0960d4;
            color: white;
        }
        
        .read-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: #dcfce7;
            color: #166534;
            padding: 0.3rem 0.75rem;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
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
            <div class="col-lg-9 mx-auto">
                <a href="messages.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Back to Messages
                </a>
                
                <div class="message-view-card">
                    <span class="message-type-badge type-<?= $message['message_type'] ?>">
                        <?= ucwords(str_replace('_', ' ', $message['message_type'])) ?>
                    </span>
                    
                    <h1><?= e($message['title']); ?></h1>
                    
                    <div class="message-meta">
                        <span>
                            <i class="bi bi-calendar-event"></i>
                            <?= date('F j, Y', strtotime($message['created_at'])); ?>
                        </span>
                        <span>
                            <i class="bi bi-clock"></i>
                            <?= date('g:i A', strtotime($message['created_at'])); ?>
                        </span>
                        <?php if ($is_read): ?>
                            <span class="read-badge">
                                <i class="bi bi-check-circle-fill"></i>
                                Read
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="message-body" id="messageBody">
                        <!-- Content will be rendered here by JavaScript -->
                    </div>
                    
                    <script>
                        // Render markdown content
                        const messageContent = <?= json_encode($message['content']) ?>;
                        document.getElementById('messageBody').innerHTML = marked.parse(messageContent);
                    </script>
                    
                    <?php if ($message['link_url']): ?>
                        <a href="<?= e($message['link_url']); ?>" class="message-link">
                            <?= e($message['link_text'] ?: 'View More'); ?>
                            <i class="bi bi-arrow-right"></i>
                        </a>
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

        // Animation on load
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.message-view-card');
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }
        });
    </script>
</body>
</html>