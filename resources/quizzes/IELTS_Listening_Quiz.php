<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>IELTS Listening Section Quiz | EduHub</title>
	<?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	

	<style>
        .main-wrapper { padding: 2rem 1.5rem; min-height: 100vh; }

        .quiz-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4A90E2 0%, #FF69B4 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .quiz-content {
            padding: 30px;
        }

        .question {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #FF69B4;
        }

        .question-text {
            font-size: 1.1em;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .options {
            list-style: none;
        }

        .option {
            margin: 10px 0;
        }

        .option label {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .option label:hover {
            background: #f0f0f0;
            border-color: #4A90E2;
        }

        .option input[type="radio"] {
            margin-right: 12px;
            cursor: pointer;
            width: 18px;
            height: 18px;
        }

        .option label.correct {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .option label.incorrect {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4A90E2 0%, #FF69B4 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.2em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .result {
            margin-top: 30px;
            padding: 25px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            display: none;
        }

        .result.show {
            display: block;
        }

        .result h2 {
            color: #2c3e50;
            margin-bottom: 15px;
        }

        .score {
            font-size: 3em;
            font-weight: bold;
            color: #4A90E2;
            margin: 20px 0;
        }

        .message {
            font-size: 1.2em;
            color: #555;
        }

        .reset-btn {
            margin-top: 20px;
            padding: 12px 30px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .reset-btn:hover {
            background: #5a6268;
        }

        .download-btn {
            margin-top: 20px;
            margin-left: 10px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #4A90E2 0%, #FF69B4 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .download-btn:hover {
            transform: translateY(-2px);
        }

        .button-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        @media (max-width: 600px) {
            .header h1 {
                font-size: 1.5em;
            }

            .quiz-content {
                padding: 20px;
            }

            .question {
                padding: 15px;
            }
        }
    </style>

</head>
	<body>
		<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
		<div class="mobile-overlay" id="mobileOverlay"></div>
		<?php include INCLUDES_PATH . '/navbar.php'; ?>
		<main class="main-wrapper">
		<div class="quiz-container">
			<div class="header">
				<h1>IELTS Listening Test Quiz</h1>
				<p>Test your knowledge about the IELTS Listening section</p>
			</div>

			<div class="quiz-content">
				<form id="quizForm">
					<div class="question">
						<div class="question-text">1. How many sections are there in the IELTS Listening test?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q1" value="A"> A) 2</label></li>
							<li class="option"><label><input type="radio" name="q1" value="B"> B) 3</label></li>
							<li class="option"><label><input type="radio" name="q1" value="C"> C) 4</label></li>
							<li class="option"><label><input type="radio" name="q1" value="D"> D) 5</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">2. How long is the Listening Test?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q2" value="A"> A) About 15 minutes with no transfer time</label></li>
							<li class="option"><label><input type="radio" name="q2" value="B"> B) About 30 minutes of listening then 10 minutes transfer time</label></li>
							<li class="option"><label><input type="radio" name="q2" value="C"> C) About 60 minutes of listening then 10 minutes transfer time</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">3. What should you do during 'transfer time'?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q3" value="A"> A) Copy answers to an answer sheet</label></li>
							<li class="option"><label><input type="radio" name="q3" value="B"> B) Write abbreviations on the answer sheet</label></li>
							<li class="option"><label><input type="radio" name="q3" value="C"> C) Prepare to leave the room</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">4. What is in each listening section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q4" value="A"> A) A recording and five paper-based questions</label></li>
							<li class="option"><label><input type="radio" name="q4" value="B"> B) A recording including instructions and five paper-based questions</label></li>
							<li class="option"><label><input type="radio" name="q4" value="C"> C) A recording including instructions and ten paper-based questions</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">5. Which accents are featured in the Listening recordings?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q5" value="A"> A) Only British</label></li>
							<li class="option"><label><input type="radio" name="q5" value="B"> B) Only American and British</label></li>
							<li class="option"><label><input type="radio" name="q5" value="C"> C) A variety of accents including British, American, Canadian, Australian, and New Zealand</label></li>
							<li class="option"><label><input type="radio" name="q5" value="D"> D) Only Australian and Canadian</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">6. What is the first section about?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q6" value="A"> A) An everyday conversation between two people</label></li>
							<li class="option"><label><input type="radio" name="q6" value="B"> B) An academic conversation between three people</label></li>
							<li class="option"><label><input type="radio" name="q6" value="C"> C) A lecture on a general topic</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">7. What is in the second section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q7" value="A"> A) An everyday conversation between two people</label></li>
							<li class="option"><label><input type="radio" name="q7" value="B"> B) A monologue on a general topic</label></li>
							<li class="option"><label><input type="radio" name="q7" value="C"> C) An academic discussion between two people</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">8. What is in the third section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q8" value="A"> A) A discussion between up to three people on an everyday topic</label></li>
							<li class="option"><label><input type="radio" name="q8" value="B"> B) A discussion between up to three people on an educational topic</label></li>
							<li class="option"><label><input type="radio" name="q8" value="C"> C) A discussion between up to four people on an educational topic</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">9. What is the fourth section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q9" value="A"> A) A lecture on a general topic</label></li>
							<li class="option"><label><input type="radio" name="q9" value="B"> B) A lecture on an academic topic</label></li>
							<li class="option"><label><input type="radio" name="q9" value="C"> C) A discussion on an academic topic</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">10. What should you focus on during Part 1 of the Listening test?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q10" value="A"> A) Understanding complex academic ideas</label></li>
							<li class="option"><label><input type="radio" name="q10" value="B"> B) Identifying main arguments</label></li>
							<li class="option"><label><input type="radio" name="q10" value="C"> C) Listening for specific details like names, numbers, and addresses</label></li>
							<li class="option"><label><input type="radio" name="q10" value="D"> D) Recognizing abstract opinions</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">11. Which is the most difficult section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q11" value="A"> A) The first</label></li>
							<li class="option"><label><input type="radio" name="q11" value="B"> B) The second</label></li>
							<li class="option"><label><input type="radio" name="q11" value="C"> C) The third</label></li>
							<li class="option"><label><input type="radio" name="q11" value="D"> D) The fourth</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">12. How many times can you listen to the recording?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q12" value="A"> A) Once</label></li>
							<li class="option"><label><input type="radio" name="q12" value="B"> B) Twice</label></li>
							<li class="option"><label><input type="radio" name="q12" value="C"> C) Three times</label></li>
							<li class="option"><label><input type="radio" name="q12" value="D"> D) As many times as needed</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">13. What should you do if you miss an answer during the test?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q13" value="A"> A) Skip all the following questions</label></li>
							<li class="option"><label><input type="radio" name="q13" value="B"> B) Write a random answer</label></li>
							<li class="option"><label><input type="radio" name="q13" value="C"> C) Stay calm and focus on the next question</label></li>
							<li class="option"><label><input type="radio" name="q13" value="D"> D) Ask the examiner to replay the recording</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">14. Is spelling important in the answers?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q14" value="A"> A) No</label></li>
							<li class="option"><label><input type="radio" name="q14" value="B"> B) Yes</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">15. What is the main strategy for short-answer questions?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q15" value="A"> A) Write a detailed paragraph</label></li>
							<li class="option"><label><input type="radio" name="q15" value="B"> B) Use one or a few words based on the recording</label></li>
							<li class="option"><label><input type="radio" name="q15" value="C"> C) Rely on general knowledge</label></li>
							<li class="option"><label><input type="radio" name="q15" value="D"> D) Ignore spelling accuracy</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">16. How many question types are there in the IELTS Listening test?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q16" value="A"> A) Five</label></li>
							<li class="option"><label><input type="radio" name="q16" value="B"> B) Six</label></li>
							<li class="option"><label><input type="radio" name="q16" value="C"> C) Seven</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">17. What is a key tip for the Listening section?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q17" value="A"> A) Answer questions without listening to the recording</label></li>
							<li class="option"><label><input type="radio" name="q17" value="B"> B) Predict the type of information needed before the recording starts</label></li>
							<li class="option"><label><input type="radio" name="q17" value="C"> C) Skip reviewing the instructions to save time</label></li>
							<li class="option"><label><input type="radio" name="q17" value="D"> D) Focus only on vocabulary questions</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">18. How are the answers marked?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q18" value="A"> A) One point per correct answer</label></li>
							<li class="option"><label><input type="radio" name="q18" value="B"> B) Two points per correct answer</label></li>
							<li class="option"><label><input type="radio" name="q18" value="C"> C) Three points per correct answer</label></li>
						</ul>
					</div>

					<div class="question">
						<div class="question-text">19. Is a half band score possible in the Listening Test (for example 6.5, 7.5)?</div>
						<ul class="options">
							<li class="option"><label><input type="radio" name="q19" value="A"> A) Yes</label></li>
							<li class="option"><label><input type="radio" name="q19" value="B"> B) No</label></li>
						</ul>
					</div>

					<button type="submit" class="submit-btn">Submit Quiz</button>
				</form>

				<div class="result" id="result">
					<h2>Quiz Results</h2>
					<div class="score" id="score"></div>
					<div class="message" id="message"></div>
					<div class="button-group">
						<button class="reset-btn" onclick="resetQuiz()">Try Again</button>
						<button class="download-btn" onclick="downloadPDF()">Download PDF Report</button>
					</div>
				</div>
			</div>
		</div>

    <script>
        // Correct answers
        const answers = {
            q1: 'C',  // 4 sections
            q2: 'B',  // About 30 minutes of listening then 10 minutes transfer time
            q3: 'A',  // Copy answers to an answer sheet
            q4: 'C',  // A recording including instructions and ten paper-based questions
            q5: 'C',  // A variety of accents
            q6: 'A',  // An everyday conversation between two people
            q7: 'B',  // A monologue on a general topic
            q8: 'B',  // A discussion between up to three people on an educational topic
            q9: 'B',  // A lecture on an academic topic
            q10: 'C', // Listening for specific details like names, numbers, and addresses
            q11: 'D', // The fourth section
            q12: 'A', // Once
            q13: 'C', // Stay calm and focus on the next question
            q14: 'B', // Yes, spelling is important
            q15: 'B', // Use one or a few words based on the recording
            q16: 'B', // Six question types (CORRECTED from Seven)
            q17: 'B', // Predict the type of information needed
            q18: 'A', // One point per correct answer
            q19: 'A'  // Yes, half band scores are possible
        };

        const questions = {
            q1: "How many sections are there in the IELTS Listening test?",
            q2: "How long is the Listening Test?",
            q3: "What should you do during 'transfer time'?",
            q4: "What is in each listening section?",
            q5: "Which accents are featured in the Listening recordings?",
            q6: "What is the first section about?",
            q7: "What is in the second section?",
            q8: "What is in the third section?",
            q9: "What is the fourth section?",
            q10: "What should you focus on during Part 1 of the Listening test?",
            q11: "Which is the most difficult section?",
            q12: "How many times can you listen to the recording?",
            q13: "What should you do if you miss an answer during the test?",
            q14: "Is spelling important in the answers?",
            q15: "What is the main strategy for short-answer questions?",
            q16: "How many question types are there in the IELTS Listening test?",
            q17: "What is a key tip for the Listening section?",
            q18: "How are the answers marked?",
            q19: "Is a half band score possible in the Listening Test?"
        };

        let quizResults = {};

        const form = document.getElementById('quizForm');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let score = 0;
            const totalQuestions = Object.keys(answers).length;
            quizResults = {};
            
            // Check each question
            Object.keys(answers).forEach(question => {
                const userAnswer = document.querySelector(`input[name="${question}"]:checked`);
                const labels = document.querySelectorAll(`input[name="${question}"]`);
                
                // Store results for PDF
                quizResults[question] = {
                    userAnswer: userAnswer ? userAnswer.value : 'Not Answered',
                    correctAnswer: answers[question],
                    isCorrect: userAnswer && userAnswer.value === answers[question],
                    userAnswerText: '',
                    correctAnswerText: ''
                };
                
                labels.forEach(input => {
                    const label = input.parentElement;
                    const answerText = label.textContent.trim();
                    
                    label.classList.remove('correct', 'incorrect');
                    
                    if (input.value === answers[question]) {
                        label.classList.add('correct');
                        quizResults[question].correctAnswerText = answerText;
                    }
                    
                    if (userAnswer && input === userAnswer) {
                        quizResults[question].userAnswerText = answerText;
                        if (input.value !== answers[question]) {
                            label.classList.add('incorrect');
                        }
                    }
                });
                
                if (userAnswer && userAnswer.value === answers[question]) {
                    score++;
                }
            });
            
            // Display results
            const percentage = Math.round((score / totalQuestions) * 100);
            document.getElementById('score').textContent = `${score} / ${totalQuestions} (${percentage}%)`;
            
            let message = '';
            if (percentage >= 90) {
                message = '🌟 Excellent! You have a strong understanding of the IELTS Listening test!';
            } else if (percentage >= 70) {
                message = '👍 Good job! You have a solid grasp of the test format.';
            } else if (percentage >= 50) {
                message = '📚 Not bad! Review the areas you missed and try again.';
            } else {
                message = '💪 Keep studying! Review the IELTS Listening test format carefully.';
            }
            
            document.getElementById('message').textContent = message;
            document.getElementById('result').classList.add('show');
            
            // Disable submit button and scroll to results
            form.querySelector('.submit-btn').disabled = true;
            document.getElementById('result').scrollIntoView({ behavior: 'smooth' });
        });

        function resetQuiz() {
            // Reset form
            form.reset();
            
            // Remove all color coding
            document.querySelectorAll('.option label').forEach(label => {
                label.classList.remove('correct', 'incorrect');
            });
            
            // Hide results
            document.getElementById('result').classList.remove('show');
            
            // Enable submit button
            form.querySelector('.submit-btn').disabled = false;
            
            // Clear quiz results
            quizResults = {};
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Calculate score
            let correctCount = 0;
            Object.keys(quizResults).forEach(q => {
                if (quizResults[q].isCorrect) correctCount++;
            });
            const totalQuestions = Object.keys(quizResults).length;
            const percentage = Math.round((correctCount / totalQuestions) * 100);
            
            // Set up colors
            const primaryBlue = [74, 144, 226];
            const accentPink = [255, 105, 180];
            const correctGreen = [40, 167, 69];
            const incorrectRed = [220, 53, 69];
            
            // Header with gradient effect (simulate with rectangles)
            doc.setFillColor(primaryBlue[0], primaryBlue[1], primaryBlue[2]);
            doc.rect(0, 0, 210, 40, 'F');
            doc.setFillColor(accentPink[0], accentPink[1], accentPink[2]);
            doc.rect(140, 0, 70, 40, 'F');
            
            // Title
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(24);
            doc.setFont(undefined, 'bold');
            doc.text('SLS IELTS Listening Test', 105, 18, { align: 'center' });
            doc.setFontSize(16);
            doc.text('Quiz Results Report', 105, 30, { align: 'center' });
            
            // Test Information Box
            doc.setFillColor(248, 249, 250);
            doc.rect(10, 45, 190, 25, 'F');
            doc.setDrawColor(primaryBlue[0], primaryBlue[1], primaryBlue[2]);
            doc.setLineWidth(0.5);
            doc.rect(10, 45, 190, 25);
            
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(12);
            doc.setFont(undefined, 'normal');
            doc.text(`Date: ${new Date().toLocaleDateString()}`, 15, 54);
            doc.text(`Time: ${new Date().toLocaleTimeString()}`, 15, 61);
            doc.setFont(undefined, 'bold');
            doc.setFontSize(14);
            doc.text(`Score: ${correctCount} / ${totalQuestions} (${percentage}%)`, 120, 57.5);
            
            // Performance indicator
            let performanceText = '';
            let performanceColor = primaryBlue;
            if (percentage >= 90) {
                performanceText = 'Excellent';
                performanceColor = correctGreen;
            } else if (percentage >= 70) {
                performanceText = 'Good';
                performanceColor = primaryBlue;
            } else if (percentage >= 50) {
                performanceText = 'Fair';
                performanceColor = [255, 193, 7];
            } else {
                performanceText = 'Needs Improvement';
                performanceColor = incorrectRed;
            }
            
            doc.setTextColor(performanceColor[0], performanceColor[1], performanceColor[2]);
            doc.text(`Performance: ${performanceText}`, 15, 68);
            
            // Results Section Header
            let yPosition = 80;
            doc.setFillColor(primaryBlue[0], primaryBlue[1], primaryBlue[2]);
            doc.rect(10, yPosition, 190, 8, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(12);
            doc.setFont(undefined, 'bold');
            doc.text('Detailed Answer Analysis', 105, yPosition + 5.5, { align: 'center' });
            
            yPosition += 15;
            
            // Column headers
            doc.setFillColor(240, 240, 240);
            doc.rect(10, yPosition, 190, 8, 'F');
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(10);
            doc.text('Q#', 15, yPosition + 5.5);
            doc.text('Your Answer', 30, yPosition + 5.5);
            doc.text('Correct Answer', 115, yPosition + 5.5);
            doc.text('Result', 185, yPosition + 5.5);
            
            yPosition += 10;
            
            // Questions and answers
            doc.setFontSize(9);
            doc.setFont(undefined, 'normal');
            
            Object.keys(quizResults).forEach((questionKey, index) => {
                const result = quizResults[questionKey];
                const questionNum = index + 1;
                
                // Check if we need a new page
                if (yPosition > 270) {
                    doc.addPage();
                    yPosition = 20;
                }
                
                // Question number
                doc.setTextColor(0, 0, 0);
                doc.text(`${questionNum}`, 15, yPosition);
                
                // Question text (wrapped)
                doc.setFontSize(8);
                const questionText = questions[questionKey];
                const wrappedQuestion = doc.splitTextToSize(questionText, 180);
                doc.text(wrappedQuestion, 15, yPosition + 5);
                
                const questionHeight = wrappedQuestion.length * 4;
                yPosition += questionHeight + 3;
                
                // Draw answer row background
                if (result.isCorrect) {
                    doc.setFillColor(212, 237, 218);
                } else {
                    doc.setFillColor(248, 215, 218);
                }
                doc.rect(10, yPosition, 190, 15, 'F');
                
                // Your answer
                doc.setFontSize(8);
                doc.setTextColor(0, 0, 0);
                const userAnswerWrapped = doc.splitTextToSize(result.userAnswerText || 'Not Answered', 80);
                doc.text(userAnswerWrapped, 30, yPosition + 4);
                
                // Correct answer
                const correctAnswerWrapped = doc.splitTextToSize(result.correctAnswerText, 65);
                doc.text(correctAnswerWrapped, 115, yPosition + 4);
                
                // Result indicator
                if (result.isCorrect) {
                    doc.setTextColor(correctGreen[0], correctGreen[1], correctGreen[2]);
                    doc.setFont(undefined, 'bold');
                    doc.text('✓', 187, yPosition + 8);
                } else {
                    doc.setTextColor(incorrectRed[0], incorrectRed[1], incorrectRed[2]);
                    doc.setFont(undefined, 'bold');
                    doc.text('✗', 187, yPosition + 8);
                }
                
                doc.setFont(undefined, 'normal');
                yPosition += 18;
                
                // Separator line
                doc.setDrawColor(200, 200, 200);
                doc.setLineWidth(0.1);
                doc.line(10, yPosition, 200, yPosition);
                yPosition += 2;
            });
            
            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(128, 128, 128);
                doc.text(`Page ${i} of ${pageCount}`, 105, 290, { align: 'center' });
                doc.text('SLS IELTS Listening Test Quiz - Generated by SLS Quiz System', 105, 285, { align: 'center' });
            }
            
            // Save the PDF
            doc.save(`SLS_IELTS_Listening_Quiz_Results_${new Date().toISOString().split('T')[0]}.pdf`);
        }
    </script>
	</div><!-- /.quiz-container -->
	</main><!-- /.main-wrapper -->
	<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
</body>

</html>