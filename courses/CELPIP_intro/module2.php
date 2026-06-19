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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Masterclass - Module 2: Reading Skills</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Navbar Styles -->
    <?php include $base_url . 'includes/navbar_styles.php'; ?>

    <!-- Page-specific styles -->
    <style>
        body {
            background: #f1f6fb;
            color: #0f172a;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .course-container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 14px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #2c3e50;
        }

        video {
            width: 100%;
            border-radius: 8px;
            margin-top: 0.5rem;
        }

        .activity {
            background: #f0f7ff;
            border-left: 4px solid #0b77ff;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
        }

        .nav-links {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .nav-links a {
            display: inline-block;
            padding: 0.7rem 1.2rem;
            background: linear-gradient(90deg, #0b77ff, #6f8cff);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11, 119, 255, 0.3);
        }
    </style>
</head>
<body>
    <?php include $base_url . 'includes/mobile_header.php'; ?>
    <?php include $base_url . 'includes/navbar.php'; ?>

    <main class="main-content">
        <div class="course-container">
            <h1>Module 2: Reading Skills</h1>
            <p>
                In this module, you’ll learn about the <strong>Reading Test</strong> structure, timing, and features — plus essential strategies to manage your time and accuracy.
            </p>

            <div class="media-section">
                <h2>📘 Lesson Video</h2>
                <video controls>
                    <source src="../../../media/celpip_reading_intro.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p><em>(Placeholder video – upload your file to <code>/media/celpip_reading_intro.mp4</code>)</em></p>
            </div>

            <h2>Reading Test Overview</h2>
            <p>
                The Reading Test begins with one <strong>unscored practice task</strong> followed by <strong>four scored parts</strong>. Sometimes, you may also see a few extra questions — don’t worry! These are <strong>unscored</strong> and included for research purposes.
            </p>
            <p>
                The entire test takes about <strong>one hour</strong> to complete. You’ll dive deeper into strategies and examples in later modules, but let’s start with a few key points that all Reading parts have in common.
            </p>

            <h2>Common Features of All Reading Parts</h2>
            <ul>
                <li>The passage appears on one side of the screen, with questions on the other — making it easy to refer back as you answer.</li>
                <li>All questions are <strong>multiple-choice</strong> and appear in <strong>drop-down menus</strong>.</li>
                <li>A <strong>Next</strong> button allows you to move ahead if you finish early. When time runs out, the test automatically continues.</li>
            </ul>

            <h2>Timing and Navigation Tips</h2>
            <p>
                Avoid clicking <strong>Next</strong> too quickly — once you move forward, you can’t return, and any extra time won’t carry over. Use your remaining seconds to review your answers carefully.
            </p>
            <p>
                You’ll also be provided with <strong>notepaper and a pen</strong>. You don’t have to use them, but jotting down key points or keywords can help with understanding.
            </p>

            <div class="activity">
                <h3>📝 Practice Activity</h3>
                <p>Read a short news article or online post, then try answering:</p>
                <ol>
                    <li>What is the main idea of the text?</li>
                    <li>Which words or phrases helped you identify that idea?</li>
                    <li>Could you summarize the text in one or two sentences?</li>
                </ol>
                <p><em>Write your answers in your study notebook to reflect on your reading strategy.</em></p>
            </div>

            <div class="nav-links">
                <a href="module1.php">⬅ Back to Module 1 – Listening Skills</a>
                <a href="module3.php">Next: Module 3 – Writing Skills ➡</a>
            </div>
        </div>
    </main>

	<!-- Right Advertisement Sidebar -->
    <aside class="advert-sidebar">
        <h6 class="mb-3 text-muted">
            <i class="bi bi-megaphone me-2"></i>Sponsored
        </h6>

        <div class="ad-container large" id="ad-slot-1">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 2rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x400</small>
            </div>
        </div>

        <div class="ad-container" id="ad-slot-2">
            <div class="ad-placeholder">
                <i class="bi bi-badge-ad" style="font-size: 1.5rem; opacity: 0.3;"></i>
                <p class="mt-2 mb-0">Advertisement Space</p>
                <small>300x250</small>
            </div>
        </div>

        <div class="course-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <h6 class="mb-2">🎓 Explore More Courses</h6>
            <p class="small mb-3">Enhance your skills with our comprehensive course catalog.</p>
            <a href="../courses_catalogue.php" class="btn btn-light btn-sm w-100">
                Browse Courses
            </a>
        </div>
    </aside>

    <?php include $base_url . 'includes/navbar_scripts.php'; ?>
</body>
</html>
