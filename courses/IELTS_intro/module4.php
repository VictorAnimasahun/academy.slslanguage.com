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
    <title>IELTS General Training - Module 4: Writing Task 2</title>
    <link rel="stylesheet" href="../../../css/EduHub.css">
    <style>
        .course-container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0px 4px 15px rgba(0,0,0,0.1);}
        h1, h2 { color: #2c3e50; }
        .media-section { margin: 1.5rem 0; }
        video { width: 100%; border-radius: 8px; margin-top: 0.5rem; }
        .activity { background: #f9f9f9; border-left: 4px solid #e67e22; padding: 1rem; margin: 1.5rem 0; }
        .nav-links { margin-top: 2rem; }
        .nav-links a { display: inline-block; margin-right: 1rem; padding: 0.7rem 1.2rem; background: #e67e22; color: #fff; text-decoration: none; border-radius: 6px; }
        .nav-links a:hover { background: #ca6f1e; }
    </style>
</head>
<body>

<div class="course-container">
    <h1>Module 4: Writing Task 2</h1>

    <p>
        IELTS Writing Task 2 asks you to write an <strong>essay of at least 250 words</strong>.  
        You will be asked to present an argument, provide solutions, or discuss an opinion.
    </p>

    <div class="media-section">
        <h2>🎥 Lesson Video</h2>
        <video controls>
            <source src="../../../media/ielts_writing_task2.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p><em>(Upload video to <code>/media/ielts_writing_task2.mp4</code>)</em></p>
    </div>

    <h2>Essay Structure</h2>
    <ol>
        <li><strong>Introduction</strong> – Paraphrase the question, give your opinion</li>
        <li><strong>Body Paragraphs</strong> – Present arguments with examples</li>
        <li><strong>Conclusion</strong> – Summarize and restate opinion</li>
    </ol>

    <div class="activity">
        <h3>📝 Practice Activity</h3>
        <p>
            Some people think children should start school at a very early age, while others believe they should begin at least at 7 years old.
        </p>
        <p>Discuss both views and give your opinion. Write at least 250 words.</p>
    </div>

    <div class="nav-links">
        <a href="module3.php">⬅ Back to Module 3</a>
        <a href="module5.php">Next: Module 5 ➡</a>
    </div>
</div>

</body>
</html>
