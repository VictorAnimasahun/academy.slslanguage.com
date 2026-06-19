<?php
session_start();
// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}

// Base URL to reach /includes/ from /courses/CELPIP_intro/
$base_url = "../../";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>IELTS General Training - Introduction - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-bg: #f8fafc;
            --accent: #0b77ff;
            --muted: #6b7280;
            --card-radius: 14px;
        }

        body {
            background: #f1f6fb;
            color: #0f172a;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .sidebar {
            width: 260px;
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
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <?php include $base_url . 'includes/mobile_header.php'; ?>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar Navigation -->
    <?php include $base_url . 'includes/navbar.php'; ?>


    <!-- Main Content Area (Centered) -->
    <main class="main-wrapper">
        <div class="course-card">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../courses_catalogue.php" class="text-decoration-none">Courses</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="course_overview.php" class="text-decoration-none">IELTS General Training</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Introduction</li>
                </ol>
            </nav>

            <h1 class="mb-4">Welcome to IELTS General Training - Introduction</h1>
            
            <p class="lead">
                Welcome to our introductory session once again.
                I'm elated to be a part of your preparatory journey towards the IELTS Exams.
            </p>

            <div class="media-section">
                <h2><i class="bi bi-play-circle me-2"></i>Course Introduction Video</h2>
                <video controls>
                    <source src="../../../media/ielts_intro.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p class="text-muted mt-2">
                    <em><i class="bi bi-info-circle me-1"></i>Placeholder video – upload your actual intro video to <code>/media/ielts_intro.mp4</code></em>
                </p>
            </div>

            <div class="media-section">
                <h2><i class="bi bi-headphones me-2"></i>Listen to the Introduction</h2>
                <audio controls>
                    <source src="../../../media/ielts_intro.mp3" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
                <p class="text-muted mt-2">
                    <em><i class="bi bi-info-circle me-1"></i>Placeholder audio – upload your actual intro audio to <code>/media/ielts_intro.mp3</code></em>
                </p>
            </div>

            <h2 class="mt-4">About This Course</h2>
            <p>
                This class is for the <strong>IELTS General Training Test</strong>.
                Officially, our sessions are going to be held on this platform using texts, video uploads, and audio sessions.
                In some cases, we might have a switch between platforms (Telegram, WhatsApp, or Google Meet) if the need arises.
            </p>
            <p>
                This is sometimes required due to an extra congestion on the network traffic of one platform, or poor reception which can be observed from one or both ends.
            </p>

            <h2 class="mt-4">Learning Approach</h2>
            <p>
                In our lessons, I'll provide comprehensive explanations of all the concepts, question types, and skills required for the IELTS General Training Test.
                I'll ensure you have all the necessary information to excel. However, your personal dedication and effort will be the key to achieving success.
            </p>

            <div class="tips">
                <h3>💡 Tips to Maximize Your Progress:</h3>
                <ul class="mb-0">
                    <li>Set aside time to practice outside of class.</li>
                    <li>Expand your vocabulary.</li>
                    <li>Incorporate English language practice into your daily interactions.</li>
                </ul>
            </div>

            <div class="nav-links">
                <a href="module1.php">
                    <i class="bi bi-arrow-right-circle me-2"></i>Start Module 1
                </a>
                <a href="../../learning_dashboard.php" class="secondary">
                    <i class="bi bi-arrow-left-circle me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </main>

    <!-- Right Advertisement Sidebar -->
    <aside class="advert-sidebar">
        <h6 class="mb-3 text-muted">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>

        <!-- Ad Container 1 - Large Banner -->
        <div class="ad-container large" id="ad-slot-1">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 2rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x400</small>
            </div>
        </div>

        <!-- Ad Container 2 - Medium Banner -->
        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>

        <!-- Internal Promo Section (Example) -->
        <div class="course-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h6 class="mb-2">🎓 Explore More Courses</h6>
            <p class="small mb-3">Enhance your skills with our comprehensive course catalog.</p>
            <a href="../courses_catalogue.php" class="btn btn-light btn-sm w-100">
                Browse Courses
            </a>
        </div>
    </aside>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu Script -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        function toggleMenu() {
            sidebar.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            
            // Change icon
            const icon = menuToggle.querySelector('i');
            if (sidebar.classList.contains('active')) {
                icon.className = 'bi bi-x-lg';
            } else {
                icon.className = 'bi bi-list';
            }
        }

        menuToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);

        // Close menu when a nav link is clicked on mobile
        const navLinks = document.querySelectorAll('.sidebar .nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1200) {
                    toggleMenu();
                }
            });
        });
    </script>
    
    <!-- Google Ads Integration Example -->
    <script>
       
    </script>
</body>
</html>