<?php
session_start();
// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}

$base_url = "../../";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CELPIP Masterclass - Module 3: Writing Skills</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include $base_url . 'includes/navbar_styles.php'; ?>

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
            <h1>Module 3: Writing Skills</h1>
            <p>
                In this module, you’ll explore the <strong>Writing Test</strong> — its two main tasks, timing, and the tools available to help you perform your best.
            </p>

            <div class="media-section">
                <h2>✍️ Lesson Video</h2>
                <video controls>
                    <source src="../../../media/celpip_writing_intro.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <p><em>(Placeholder video – upload to <code>/media/celpip_writing_intro.mp4</code>)</em></p>
            </div>

            <h2>Writing Test Overview</h2>
            <p>
                The Writing Test includes <strong>two tasks</strong>:
            </p>
            <ul>
                <li><strong>Task 1:</strong> Write an email (about 30 minutes)</li>
                <li><strong>Task 2:</strong> Respond to a survey question (about 30 minutes)</li>
            </ul>
            <p>
                Each task requires a response of about <strong>150–200 words</strong>. Don’t stress about the details yet — we’ll cover them thoroughly in later lessons.
            </p>

            <h2>Word Count and Tools</h2>
            <p>
                The system automatically displays your <strong>word count</strong> at the bottom of the screen. You’ll also have access to helpful tools such as <strong>spellcheck, copy, and paste</strong> — similar to what you’d find in common writing programs.
            </p>

            <h2>Planning and Time Management</h2>
            <p>
                Unlike the Speaking Test, there’s no separate planning time for Writing. So, use the first few minutes to <strong>organize your ideas</strong> before typing. You’ll have <strong>notepaper and a pen</strong> — use them to outline or brainstorm briefly.
            </p>
            <p>
                A <strong>Next</strong> button appears at the top of each task, allowing you to move forward early. But remember: once you click it, you can’t go back, and unused time won’t carry over. It’s smarter to use those final minutes to review and polish your writing.
            </p>

            <div class="activity">
                <h3>📝 Practice Activity</h3>
                <p>Practice Task 1: Write a short email (150–200 words) to a friend inviting them to an event.</p>
                <p>Use the checklist below as a guide:</p>
                <ul>
                    <li>Did you greet your friend properly?</li>
                    <li>Did you clearly explain the event and details?</li>
                    <li>Did you close the email politely?</li>
                </ul>
                <p><em>Save your response and compare it later when we explore writing evaluation criteria.</em></p>
            </div>

            <div class="nav-links">
                <a href="module2.php">⬅ Back to Module 2 – Reading Skills</a>
                <a href="module4.php">Next: Module 4 – Speaking Skills ➡</a>
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
