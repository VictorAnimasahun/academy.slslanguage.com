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
    <title>CELPIP Masterclass - Module 4: Speaking Skills</title>
    
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
            <h1>Module 4: Speaking Skills</h1>
            <p>
                Welcome to the <strong>Speaking Test Overview</strong>. In this module, you’ll learn what to expect from the CELPIP Speaking Test — including its structure, timing, and key strategies for success.
            </p>

            <div class="media-section">
                <h2>🎙️ Lesson Video</h2>
                <video controls>
                    <source src="../../../media/celpip_speaking_intro.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p><em>(Placeholder video – upload your file to <code>/media/celpip_speaking_intro.mp4</code>)</em></p>
            </div>

            <h2>Speaking Test Overview</h2>
            <p>
                The CELPIP Speaking Test consists of <strong>8 speaking tasks</strong>. Before these, you’ll complete a short <strong>practice task</strong> designed to help you get comfortable — this part is not scored.
            </p>
            <p>
                Each speaking task has two key parts:
            </p>
            <ul>
                <li><strong>Preparation Time:</strong> 30–60 seconds</li>
                <li><strong>Recording Time:</strong> 60–90 seconds</li>
            </ul>
            <p>
                During the <strong>Preparation Time</strong>, you should <em>not speak</em>. This time is for organizing your ideas and planning what you’ll say. You’ll receive <strong>notepaper and a pen</strong> to jot down key words or short notes if you like — but note-taking is completely optional.
            </p>

            <h2>Recording and Scoring</h2>
            <p>
                When the <strong>Recording Time</strong> begins, that’s when you start speaking. This portion is what’s <strong>evaluated and contributes to your final score</strong>.
            </p>
            <p>
                The entire Speaking Test takes about <strong>20 minutes</strong> to complete.
            </p>

            <h2>Task Types and Visuals</h2>
            <p>
                Four of the eight scored Speaking tasks include only <strong>text prompts</strong>, while the other four combine <strong>text and images</strong>.
                In some tasks, you’ll speak directly to the computer — as if you were talking to another person. Each task focuses on different communication skills, so understanding their unique goals will help you perform better.
            </p>

            <div class="activity">
                <h3>🗣️ Practice Activity</h3>
                <p>Try this short exercise to build your confidence:</p>
                <ol>
                    <li>Choose any simple topic — for example, “Describe your favorite place to relax.”</li>
                    <li>Take 30 seconds to plan what you’ll say (without speaking).</li>
                    <li>Then, speak for 60–90 seconds, pretending you’re recording your CELPIP response.</li>
                    <li>Afterward, listen to your recording and reflect on clarity, fluency, and structure.</li>
                </ol>
                <p><em>This practice will help you simulate real CELPIP conditions and strengthen your speaking flow.</em></p>
            </div>

            <h2>Next Steps</h2>
            <p>
                This has been a brief overview of the Speaking Test. In later modules, we’ll explore each task type in detail — with tips, model responses, and scoring guidance. 
                You can also try our <strong>free online Mock Tests</strong> to get familiar with the format and scoring system.
            </p>

            <div class="nav-links">
                <a href="module3.php">⬅ Back to Module 3 – Writing Skills</a>
                <a href="summary.php">Next: Course Summary & Final Quiz ➡</a>
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
