<?php
session_start();

// Restrict access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../registration.php?message=Please+login+to+access+this+course");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS General Training - Module 5: Speaking Skills</title>
    <link rel="stylesheet" href="../../../css/EduHub.css">
    <style>
        .course-container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0px 4px 15px rgba(0,0,0,0.1);}
        h1, h2 { color: #2c3e50; }
        .media-section { margin: 1.5rem 0; }
        video, audio { width: 100%; border-radius: 8px; margin-top: 0.5rem; }
        .activity { background: #f9f9f9; border-left: 4px solid #9b59b6; padding: 1rem; margin: 1.5rem 0; }
        .nav-links { margin-top: 2rem; }
        .nav-links a { display: inline-block; margin-right: 1rem; padding: 0.7rem 1.2rem; background: #9b59b6; color: #fff; text-decoration: none; border-radius: 6px; }
        .nav-links a:hover { background: #7d3c98; }
    </style>
</head>
<body>

<div class="course-container">
    <h1>Module 5: Speaking Skills</h1>

    <p>
        The IELTS Speaking Test lasts <strong>11–14 minutes</strong> and is divided into three parts:
    </p>
    <ul>
        <li><strong>Part 1</strong> – Introduction & interview (about yourself, hobbies, background)</li>
        <li><strong>Part 2</strong> – Individual long turn (talk for 1–2 minutes about a topic)</li>
        <li><strong>Part 3</strong> – Discussion (answer deeper questions related to Part 2)</li>
    </ul>

    <div class="media-section">
        <h2>🎥 Lesson Video</h2>
        <video controls>
            <source src="../../../media/ielts_speaking_overview.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p><em>(Upload video to <code>/media/ielts_speaking_overview.mp4</code>)</em></p>
    </div>

    <div class="media-section">
        <h2>🎧 Example Audio</h2>
        <audio controls>
            <source src="../../../media/ielts_speaking_sample.mp3" type="audio/mpeg">
            Your browser does not support the audio tag.
        </audio>
        <p><em>(Upload audio to <code>/media/ielts_speaking_sample.mp3</code>)</em></p>
    </div>

    <div class="activity">
        <h3>📝 Practice Activity</h3>
        <p>Practice answering this common IELTS Speaking question:</p>
        <blockquote>
            "Describe a memorable trip you have taken. Where did you go, who did you go with, and why was it memorable?"
        </blockquote>
        <p>Speak for at least 2 minutes. Record your answer and listen to check your fluency and pronunciation.</p>
    </div>

    <div class="nav-links">
        <a href="module4.php">⬅ Back to Module 4</a>
        <a href="quiz.php">Go to Quiz ➡</a>
    </div>
</div>

</body>
</html>
