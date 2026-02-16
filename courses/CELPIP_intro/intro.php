<?php
session_start();
// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
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
    <title>CELPIP Masterclass - Module 0: Introduction to CELPIP</title>
    
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

        .media-section {
            margin: 1.5rem 0;
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
    <!-- Mobile Header -->
    <?php include $base_url . 'includes/mobile_header.php'; ?>
    
    <!-- Sidebar Navigation -->
    <?php include $base_url . 'includes/navbar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="course-container">
            <h1>Module 0: Welcome to CELPIP Accelerate</h1>
            <p>
                Welcome to our <strong>CELPIP Masterclass!</strong> Before we dive into your preparation for the CELPIP Test, let’s take a moment to understand some important design and formatting features such as:
            </p>
            <ol>
                <li>Who the test is designed for</li>
                <li>What language skills are being measured</li>
                <li>How the results will be made available to you</li>
            </ol>

            <div class="media-section">
                <h2>🎥 Course Introduction Video</h2>
                <video controls>
                    <source src="../../../media/celpip_intro_video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p><em>(Placeholder video – upload your intro video to <code>/media/celpip_intro_video.mp4</code>)</em></p>
            </div>

            <h2>About the CELPIP Test</h2>
            <p>
                The CELPIP test is categorized into two: the <strong>CELPIP-General</strong> and the <strong>CELPIP-General LS</strong>, each serving a unique purpose.
                The CELPIP-General Test is primarily for individuals seeking <strong>Permanent Residency (PR)</strong> in Canada and assesses four language skills:
                Listening, Reading, Writing, and Speaking.  
                The CELPIP-General LS (Listening & Speaking) Test, on the other hand, measures only <strong>Listening and Speaking</strong> skills and is intended for
                individuals applying for <strong>Canadian Citizenship</strong>.
            </p>

            <p>
                The CELPIP Test is the only Canadian English test accepted by <strong>Immigration, Refugees and Citizenship Canada (IRCC)</strong> as proof of English
                language proficiency. The design and format remain the same regardless of your purpose for taking the test.
            </p>

            <p>
                All CELPIP content reflects <strong>Canadian English</strong> — all accents and vocabulary are North American.
                Unlike other English tests, you will not encounter British or Australian accents.
            </p>

            <h2>Test Format and Duration</h2>
            <p>
                CELPIP is a fully <strong>computer-based test</strong>. You will complete every section, including the Speaking component, on the computer.
                The CELPIP-General Test lasts for about <strong>3 hours</strong>, while the CELPIP-General LS takes around <strong>1 hour and 10 minutes</strong>.
                Both are completed in one sitting.
            </p>

            <h2>What the Test Measures</h2>
            <p>
                The CELPIP Test measures <strong>general English proficiency</strong> — your ability to use English effectively in real-life situations such as
                asking for directions, applying for a job, joining a community group, or communicating with colleagues.
                The goal is to demonstrate how well you can use English naturally and appropriately in everyday life.
            </p>

            <h2>Canadian Language Benchmarks (CLB)</h2>
            <p>
                CELPIP results align with the <strong>Canadian Language Benchmarks (CLB)</strong>, Canada’s national standard for assessing adult language proficiency.
                Your CELPIP score report will show your scores for each section and a <strong>performance profile</strong> outlining what you can currently understand
                and communicate at home, work, and in the community.
            </p>

            <h2>Structure of the CELPIP-General Test</h2>
            <p>
                The test is organized into four main sections:
            </p>
            <ul>
                <li><strong>Listening:</strong> 47–55 minutes</li>
                <li><strong>Reading:</strong> 55–60 minutes</li>
                <li><strong>Writing:</strong> 53–60 minutes</li>
                <li><strong>Speaking:</strong> 15–20 minutes</li>
            </ul>
            <p>
                Each section focuses on one key language skill and contains a range of question types and topics to test comprehension and communication.
            </p>

            <div class="activity">
                <h3>📝 Reflection Activity</h3>
                <p>
                    Take a few minutes to reflect:
                </p>
                <ol>
                    <li>Which version of the CELPIP Test are you planning to take — General or General LS?</li>
                    <li>Which skill do you feel most confident about?</li>
                    <li>Which skill do you think will require more practice?</li>
                </ol>
                <p><em>Write your thoughts in your study notebook — this will help you focus your preparation effectively.</em></p>
            </div>

            <div class="nav-links">
                <a href="course_overview.php">⬅ Course Overview</a>
                <a href="module1.php">Next: Module 1 – The Listening Test ➡</a>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Navbar Scripts -->
    <?php include $base_url . 'includes/navbar_scripts.php'; ?>
</body>
</html>
