<?php
session_start();

// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS General Training - Module 2: Reading Strategies</title>
    <link rel="stylesheet" href="../../../css/EduHub.css">
    <style>
        .course-container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
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
            background: #f9f9f9;
            border-left: 4px solid #27ae60;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        .nav-links {
            margin-top: 2rem;
        }
        .nav-links a {
            display: inline-block;
            margin-right: 1rem;
            padding: 0.7rem 1.2rem;
            background: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
        .nav-links a:hover {
            background: #1e8449;
        }
    </style>
</head>
<body>

<div class="course-container">
    <h1>Module 2: Reading Strategies</h1>

    <p>
        In this module, we’ll explore effective strategies for approaching the <strong>IELTS Reading Test</strong>.  
        You’ll learn how to manage your time, identify keywords, and use skimming and scanning techniques to locate answers quickly.
    </p>

    <div class="media-section">
        <h2>🎥 Lesson Video</h2>
        <video controls>
            <source src="../../../media/ielts_reading_strategies.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p><em>(Upload video to <code>/media/ielts_reading_strategies.mp4</code>)</em></p>
    </div>

    <h2>About the Reading Test</h2>
    <p>
        The IELTS Reading Test has <strong>40 questions</strong> based on three passages.  
        General Training focuses on:
    </p>
    <ul>
        <li>Everyday topics like notices, advertisements, and short texts.</li>
        <li>Workplace-related documents.</li>
        <li>Longer texts of general interest.</li>
    </ul>

    <div class="activity">
        <h3>📝 Practice Activity</h3>
        <p>Read the short text below and answer the questions:</p>

        <blockquote>
            <p>
                "The IELTS Reading Test is not just about understanding words.  
                It tests your ability to identify key information, analyze opinions, and recognize the writer’s purpose."
            </p>
        </blockquote>

        <ol>
            <li>What are two skills (other than understanding words) tested in the IELTS Reading Test?</li>
            <li>What does the test expect you to recognize about the writer?</li>
        </ol>

        <p><em>Write your answers in a notebook. Solutions will be shared at the end of the module.</em></p>
    </div>

    <div class="nav-links">
        <a href="module1.php">⬅ Back to Module 1</a>
        <a href="module3.php">Next: Module 3 ➡</a>
    </div>
</div>

</body>
</html>
