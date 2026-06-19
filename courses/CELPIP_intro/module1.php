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
    <title>CELPIP Masterclass - Module 1: Listening Skills</title>

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

        video, audio {
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
            <h1>Module 1: Listening Skills</h1>
            <p>
                In this module, you’ll explore how the <strong>Listening Test</strong> works, the structure of its six parts, and the best strategies to approach each section with confidence.
            </p>

            <div class="media-section">
                <h2>🎥 Lesson Video</h2>
                <video controls>
                    <source src="../../../media/celpip_listening_intro.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p><em>(Placeholder video – upload your lesson video to <code>/media/celpip_listening_intro.mp4</code>)</em></p>
            </div>

            <h2>Listening Test Overview</h2>
            <p>
                The CELPIP Listening Test begins with a short <strong>unscored practice task</strong> to help you get comfortable. After that, you’ll complete <strong>six scored parts</strong>.
                Occasionally, you may encounter a few <strong>extra questions</strong>; these are for research and development and do not affect your score.
            </p>
            <p>
                In total, the Listening Test takes <strong>just under one hour</strong> to complete. Each part is unique, but all sections share several common features.
            </p>

            <h2>Common Features of All Listening Parts</h2>
            <ul>
                <li>Every question is <strong>multiple-choice</strong> with four possible answers.</li>
                <li>Each listening passage is either:
                    <ul>
                        <li>a <strong>conversation</strong> between two or three people, or</li>
                        <li>a <strong>short report</strong> presented by a single speaker.</li>
                    </ul>
                </li>
                <li>One section includes a <strong>video conversation</strong> among three people.</li>
                <li>A <strong>Next</strong> button lets you move forward before time runs out, though it’s optional.</li>
            </ul>

            <h2>Timing and Navigation Tips</h2>
            <p>
                Be careful with the <strong>Next</strong> button! Once you move forward, you can’t return, and unused time won’t carry over. 
                Use your time wisely to double-check your answers before clicking <em>Next</em>.
            </p>
            <p>
                You’ll hear each audio recording <strong>only once</strong>. Stay focused throughout the test. You’ll be given <strong>notepaper and a pen</strong> to jot down key words or ideas.
                Note-taking isn’t mandatory, but it’s highly recommended, especially for more challenging parts.
            </p>

            <div class="media-section">
                <h2>🎧 Listening Practice Audio</h2>
                <audio controls>
                    <source src="../../../media/celpip_listening_practice1.mp3" type="audio/mp3">
                    Your browser does not support the audio element.
                </audio>
                <p><em>(Placeholder audio – upload your practice file to <code>/media/celpip_listening_practice1.mp3</code>)</em></p>
            </div>

            <div class="activity">
                <h3>📝 Practice Activity</h3>
                <p>Listen to the practice audio above and answer the following self-check questions:</p>
                <ol>
                    <li>What is the main purpose of the conversation or report?</li>
                    <li>What key details can you note down from the speakers?</li>
                    <li>Did you understand the speaker’s tone and intent?</li>
                </ol>
                <p><em>Record your answers in a notebook and compare them with the transcript provided later.</em></p>
            </div>

            <h2>Next Steps</h2>
            <p>
                You’ll explore each part of the Listening Test in more detail throughout this CELPIP course — including advanced tips, timing strategies, and realistic practice tasks.
            </p>

            <div class="nav-links">
                <a href="intro.php">⬅ Back to Introduction</a>
                <a href="module2.php">Next: Module 2 – Reading Skills ➡</a>
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
