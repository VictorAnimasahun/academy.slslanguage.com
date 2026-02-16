// Quiz functionality
const quizData = [
    {
        question: "In the IELTS Listening test, you are given 10 minutes at the end to transfer your answers. During this time, what else can you do?",
        options: [
            "Review and change your answers",
            "Listen to the recordings again",
            "Only transfer answers, no changes allowed",
            "Check spelling and grammar only"
        ],
        correct: 0
    },
    {
        question: "A student receives these scores: Listening 8.0, Reading 8.5, Writing 6.0, Speaking 6.5. What is their overall band score?",
        options: ["7.0", "7.25", "7.5", "6.5"],
        correct: 1
    },
    {
        question: '"Many people believe online learning is the future of education. Discuss both views and give your own opinion." This is a:',
        options: [
            "Opinion essay",
            "Discussion essay",
            "Problem-Solution essay",
            "Two-part question"
        ],
        correct: 1
    },
    {
        question: "In Speaking Part 2, you are given 1 minute to prepare. Which of the following is the BEST strategy?",
        options: [
            "Write full sentences for your speech",
            "Make brief notes using keywords and bullet points",
            "Memorize your entire response word-for-word",
            "Think silently without writing anything"
        ],
        correct: 1
    },
    {
        question: 'In Reading, what is the difference between "False" and "Not Given"?',
        options: [
            "They mean the same thing",
            '"False" contradicts the passage; "Not Given" means no information provided',
            '"False" is for facts; "Not Given" is for opinions',
            "There is no difference in IELTS Academic"
        ],
        correct: 1
    },
    {
        question: "Which Writing Task 1 type requires you to use passive voice most frequently?",
        options: [
            "Line graph",
            "Process diagram",
            "Pie chart",
            "Table"
        ],
        correct: 1
    },
    {
        question: "How much time should you ideally spend on Writing Task 1 vs Task 2?",
        options: [
            "30 minutes each",
            "20 minutes Task 1, 40 minutes Task 2",
            "25 minutes Task 1, 35 minutes Task 2",
            "15 minutes Task 1, 45 minutes Task 2"
        ],
        correct: 1
    },
    {
        question: "In Listening Section 4, what type of recording will you hear?",
        options: [
            "Conversation between two people in a social context",
            "Monologue on an academic subject",
            "Discussion among students about an assignment",
            "Speech about community services"
        ],
        correct: 1
    },
    {
        question: "Which of these is NOT a criterion for Speaking assessment?",
        options: [
            "Fluency and Coherence",
            "Lexical Resource (vocabulary)",
            "Speed of response",
            "Grammatical Range and Accuracy"
        ],
        correct: 2
    },
    {
        question: '"What are the causes of youth unemployment? What solutions would you suggest?" This is a:',
        options: [
            "Opinion essay",
            "Discussion essay",
            "Two-part question",
            "Problem-Solution essay"
        ],
        correct: 2
    },
    {
        question: 'In Speaking Part 3, the examiner asks: "How has technology changed the way families communicate?" What level of answer is expected?',
        options: [
            "Simple yes/no with brief reason",
            "Personal example only",
            "Abstract discussion with analysis and examples",
            "Description of your own family"
        ],
        correct: 2
    },
    {
        question: "You write 235 words for Writing Task 2 (minimum is 250). What happens?",
        options: [
            "You lose 1 band score automatically",
            "You are penalized in Task Achievement",
            "Nothing, as long as you answer the question",
            "Your essay is not scored"
        ],
        correct: 1
    },
    {
        question: 'Which Reading question type typically appears with a word limit instruction? (e.g., "NO MORE THAN THREE WORDS")',
        options: [
            "True/False/Not Given",
            "Multiple Choice",
            "Short Answer Questions and Sentence Completion",
            "Matching Headings"
        ],
        correct: 2
    },
    {
        question: "In our crash course, how many total hours will be spent specifically on Writing practice?",
        options: [
            "6 hours",
            "8 hours (Classes 1, 2, 5, 6)",
            "10 hours",
            "12 hours"
        ],
        correct: 1
    },
    {
        question: "Your IELTS certificate is valid for:",
        options: [
            "1 year",
            "2 years",
            "3 years",
            "It never expires"
        ],
        correct: 1
    }
];

let currentQuestion = 0;
let userAnswers = [];
let score = 0;

// Toggle quiz visibility
function toggleQuiz() {
    const quizSection = document.getElementById('quizSection');
    const quizBtn = document.getElementById('quizToggleBtn');
    
    if (quizSection.style.display === 'none') {
        quizSection.style.display = 'block';
        quizBtn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Close Quiz';
        quizBtn.classList.remove('btn-warning');
        quizBtn.classList.add('btn-secondary');
        
        // Scroll to quiz
        quizSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Initialize quiz if not started
        if (currentQuestion === 0 && userAnswers.length === 0) {
            initQuiz();
        }
    } else {
        quizSection.style.display = 'none';
        quizBtn.innerHTML = '<i class="bi bi-question-circle me-2"></i>Take Knowledge Quiz';
        quizBtn.classList.remove('btn-secondary');
        quizBtn.classList.add('btn-warning');
    }
}

// Initialize quiz
function initQuiz() {
    currentQuestion = 0;
    userAnswers = [];
    score = 0;
    document.getElementById('questionsContainer').style.display = 'block';
    document.getElementById('resultsContainer').classList.remove('active');
    renderQuestion();
}

// Render current question
function renderQuestion() {
    const container = document.getElementById('questionsContainer');
    const question = quizData[currentQuestion];
    const letters = ['A', 'B', 'C', 'D'];
    
    let html = `
        <div class="question-card active">
            <div class="question-number">Question ${currentQuestion + 1} of ${quizData.length}</div>
            <div class="question-text">${question.question}</div>
            <div class="options">
    `;
    
    question.options.forEach((option, index) => {
        const isSelected = userAnswers[currentQuestion] === index;
        html += `
            <div class="option ${isSelected ? 'selected' : ''}" onclick="selectOption(${index})">
                <span class="option-letter">${letters[index]}</span>
                ${option}
            </div>
        `;
    });
    
    html += `
            </div>
            <div class="quiz-navigation">
                <button class="btn-prev" onclick="prevQuestion()" ${currentQuestion === 0 ? 'disabled' : ''}>
                    <i class="bi bi-arrow-left me-2"></i>Previous
                </button>
                ${currentQuestion < quizData.length - 1 
                    ? '<button class="btn-next" onclick="nextQuestion()">Next<i class="bi bi-arrow-right ms-2"></i></button>'
                    : '<button class="btn-submit" onclick="submitQuiz()">Submit Quiz<i class="bi bi-check-circle ms-2"></i></button>'
                }
            </div>
        </div>
    `;
    
    container.innerHTML = html;
    updateProgress();
}

// Select an option
function selectOption(index) {
    userAnswers[currentQuestion] = index;
    renderQuestion();
}

// Navigate to next question
function nextQuestion() {
    if (currentQuestion < quizData.length - 1) {
        currentQuestion++;
        renderQuestion();
    }
}

// Navigate to previous question
function prevQuestion() {
    if (currentQuestion > 0) {
        currentQuestion--;
        renderQuestion();
    }
}

// Update progress bar
function updateProgress() {
    const progress = ((currentQuestion + 1) / quizData.length) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
}

// Submit quiz and show results
function submitQuiz() {
    score = 0;
    quizData.forEach((question, index) => {
        if (userAnswers[index] === question.correct) {
            score++;
        }
    });
    
    document.getElementById('questionsContainer').style.display = 'none';
    document.getElementById('resultsContainer').classList.add('active');
    document.getElementById('progressBar').style.width = '100%';
    document.getElementById('scoreDisplay').textContent = `${score}/15`;
    
    const scoreCircle = document.getElementById('scoreCircle');
    const performanceTitle = document.getElementById('performanceTitle');
    const feedbackBox = document.getElementById('feedbackBox');
    
    if (score >= 13) {
        scoreCircle.className = 'score-circle score-excellent';
        performanceTitle.textContent = 'Excellent! 🌟';
        performanceTitle.style.color = '#10b981';
        feedbackBox.innerHTML = '<p><strong>Outstanding performance!</strong> You have a strong understanding of IELTS Academic. You\'re ready for the crash course!</p>';
        feedbackBox.style.borderColor = '#10b981';
    } else if (score >= 10) {
        scoreCircle.className = 'score-circle score-good';
        performanceTitle.textContent = 'Good! 👍';
        performanceTitle.style.color = '#f59e0b';
        feedbackBox.innerHTML = '<p><strong>Well done!</strong> You know the basics well. The crash course will help solidify your knowledge.</p>';
        feedbackBox.style.borderColor = '#f59e0b';
    } else if (score >= 7) {
        scoreCircle.className = 'score-circle score-fair';
        performanceTitle.textContent = 'Fair 📚';
        performanceTitle.style.color = '#f97316';
        feedbackBox.innerHTML = '<p><strong>Good effort!</strong> You have some knowledge gaps. Review the course content and pay extra attention during classes.</p>';
        feedbackBox.style.borderColor = '#f97316';
    } else {
        scoreCircle.className = 'score-circle score-poor';
        performanceTitle.textContent = 'Needs Improvement ⚠️';
        performanceTitle.style.color = '#ef4444';
        feedbackBox.innerHTML = '<p><strong>Keep studying!</strong> Review all course materials carefully before starting. Consider additional study resources.</p>';
        feedbackBox.style.borderColor = '#ef4444';
    }
}

// Restart quiz
function restartQuiz() {
    initQuiz();
}