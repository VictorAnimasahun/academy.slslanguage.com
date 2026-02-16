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
    <title>IELTS General Training - Quiz</title>
    <link rel="stylesheet" href="../../../css/EduHub.css">
    <style>
        .course-container { max-width: 900px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0px 4px 15px rgba(0,0,0,0.1);}
        h1, h2 { color: #2c3e50; }
        form { margin-top: 1.5rem; }
        .question { margin-bottom: 1.5rem; }
        .question p { font-weight: bold; }
        button { padding: 0.7rem 1.2rem; background: #16a085; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #138d75; }
        .result { margin-top: 2rem; padding: 1rem; background: #ecf0f1; border-radius: 8px; }
    </style>
</head>
<body>

<div class="course-container">
    <h1>Course Quiz: IELTS Intro</h1>
    <p>Test your knowledge from Modules 1–5.</p>

    <form method="post">
        <div class="question">
            <p>1. How many sections are in the IELTS Listening Test?</p>
            <label><input type="radio" name="q1" value="2"> 2</label><br>
            <label><input type="radio" name="q1" value="3"> 3</label><br>
            <label><input type="radio" name="q1" value="4"> 4</label>
        </div>

        <div class="question">
            <p>2. What is the word limit for Writing Task 1 (General Training)?</p>
            <label><input type="radio" name="q2" value="100"> 100 words</label><br>
            <label><input type="radio" name="q2" value="150"> 150 words</label><br>
            <label><input type="radio" name="q2" value="250"> 250 words</label>
        </div>

        <div class="question">
            <p>3. In Writing Task 2, what should you include in your conclusion?</p>
            <label><input type="radio" name="q3" value="New ideas"> New ideas</label><br>
            <label><input type="radio" name="q3" value="Summary and restatement"> Summary and restatement</label><br>
            <label><input type="radio" name="q3" value="Questions"> Questions</label>
        </div>

        <div class="question">
            <p>4. The IELTS Speaking Test lasts:</p>
            <label><input type="radio" name="q4" value="5-7"> 5–7 minutes</label><br>
            <label><input type="radio" name="q4" value="11-14"> 11–14 minutes</label><br>
            <label><input type="radio" name="q4" value="20-25"> 20–25 minutes</label>
        </div>

        <div class="question">
            <p>5. Skimming and scanning are strategies for which IELTS section?</p>
            <label><input type="radio" name="q5" value="Listening"> Listening</label><br>
            <label><input type="radio" name="q5" value="Reading"> Reading</label><br>
            <label><input type="radio" name="q5" value="Writing"> Writing</label>
        </div>

        <button type="submit" name="submit">Submit Quiz</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $answers = [
            'q1' => '4',
            'q2' => '150',
            'q3' => 'Summary and restatement',
            'q4' => '11-14',
            'q5' => 'Reading'
        ];

        $score = 0;
        $total = count($answers);

        foreach ($answers as $key => $correct) {
            if (isset($_POST[$key]) && $_POST[$key] === $correct) {
                $score++;
            }
        }

        echo "<div class='result'><h2>Your Score: $score / $total</h2>";

        if ($score == $total) {
            echo "<p>🎉 Excellent! You mastered this module.</p>";
        } elseif ($score >= 3) {
            echo "<p>👍 Good job! Review weak areas to improve further.</p>";
        } else {
            echo "<p>⚠️ Keep practicing. Go back through the lessons for better understanding.</p>";
        }

        echo "</div>";
    }
    ?>
</div>

</body>
</html>
