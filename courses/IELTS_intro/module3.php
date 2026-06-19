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
    <title>IELTS General Training - Module 3: Writing Task 1</title>
    <link rel="stylesheet" href="../../../css/EduHub.css">
    <style>
        .course-container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0px 4px 15px rgba(0,0,0,0.1);}
        h1, h2 { color: #2c3e50; }
        .media-section { margin: 1.5rem 0; }
        video { width: 100%; border-radius: 8px; margin-top: 0.5rem; }
        .activity { background: #f9f9f9; border-left: 4px solid #2980b9; padding: 1rem; margin: 1.5rem 0; }
        .nav-links { margin-top: 2rem; }
        .nav-links a { display: inline-block; margin-right: 1rem; padding: 0.7rem 1.2rem; background: #2980b9; color: #fff; text-decoration: none; border-radius: 6px; }
        .nav-links a:hover { background: #1f6391; }
    </style>
</head>
<body>

<div class="course-container">
    <h1>Module 3: Writing Task 1</h1>

    <p>
        IELTS General Training Writing Task 1 requires you to write a <strong>letter</strong>.  
        The situation will be given, and you’ll need to respond appropriately in <strong>150 words</strong>.
    </p>

    <div class="media-section">
        <h2>🎥 Lesson Video</h2>
        <video controls>
            <source src="../../../media/ielts_writing_task1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <p><em>(Upload video to <code>/media/ielts_writing_task1.mp4</code>)</em></p>
    </div>

    <h2>Types of Letters</h2>
    <ul>
        <li>Formal (e.g., writing to your boss, a company, an institution)</li>
        <li>Semi-formal (e.g., writing to your landlord, a colleague)</li>
        <li>Informal (e.g., writing to a friend or family member)</li>
    </ul>

    <div class="activity">
        <h3>📝 Practice Activity</h3>
        <p>
            You recently moved to a new city and need to write to your friend, describing your new place and inviting them to visit.
        </p>
        <p>Write at least 150 words. Remember to:</p>
        <ul>
            <li>Use the correct tone (informal in this case)</li>
            <li>Structure your letter with an opening, body, and closing</li>
            <li>Use linking words to make your writing smooth</li>
        </ul>
    </div>

    <div class="nav-links">
        <a href="module2.php">⬅ Back to Module 2</a>
        <a href="module4.php">Next: Module 4 ➡</a>
    </div>
</div>

</body>
</html>
