<?php
require_once (dirname(dirname(__DIR__))) . '/bootstrap.php';

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
    <title>IELTS 20-Minute Practice Tasks</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: white;
            font-size: 2.5em;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .task-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .task-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .task-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .task-card h2 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 15px;
        }

        .task-card p {
            color: #666;
            font-size: 1.1em;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .task-card button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .task-card button:hover {
            transform: scale(1.05);
        }

        .task-content {
            display: none;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .task-content.active {
            display: block;
        }

        .timer {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            font-weight: bold;
        }

        .task-header {
            border-left: 5px solid #667eea;
            padding-left: 20px;
            margin-bottom: 30px;
        }

        .task-header h2 {
            color: #667eea;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .task-instructions {
            background: #f0f4ff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .task-instructions h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.4em;
        }

        .task-instructions ul {
            list-style-position: inside;
            color: #444;
            font-size: 1.1em;
            line-height: 2;
        }

        .task-prompt {
            background: #fff9e6;
            padding: 30px;
            border-radius: 15px;
            border-left: 5px solid #ffc107;
            margin-bottom: 30px;
        }

        .task-prompt h3 {
            color: #f57c00;
            margin-bottom: 15px;
            font-size: 1.5em;
        }

        .task-prompt p {
            color: #333;
            font-size: 1.2em;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .response-area {
            margin-bottom: 30px;
        }

        .response-area textarea {
            width: 100%;
            min-height: 300px;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1.1em;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            resize: vertical;
        }

        .word-count {
            text-align: right;
            color: #666;
            font-size: 1.1em;
            margin-top: 10px;
        }

        .speaking-prompts {
            display: grid;
            gap: 20px;
        }

        .speaking-part {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #667eea;
        }

        .speaking-part h4 {
            color: #667eea;
            font-size: 1.3em;
            margin-bottom: 15px;
        }

        .speaking-part ul {
            list-style-position: inside;
            color: #444;
            font-size: 1.1em;
            line-height: 2;
        }

        .timer-controls {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .timer-controls button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .timer-controls button:hover {
            transform: scale(1.05);
        }

        .timer-controls button.danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .back-button {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background: #5a6268;
        }

        .tips-box {
            background: #e8f5e9;
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #4caf50;
            margin-top: 20px;
        }

        .tips-box h4 {
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .tips-box ul {
            color: #444;
            font-size: 1em;
            line-height: 1.8;
        }

        .chart-placeholder {
            background: #f5f5f5;
            border: 2px dashed #999;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 1.2em;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2em;
            }
            
            .task-card {
                padding: 20px;
            }
            
            .task-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
	

	<!--Main Content-->
    <div class="container">
        <h1>📝 IELTS 20-Minute Practice Tasks</h1>
        
        <!-- Task Selection -->
        <div id="taskSelector" class="task-selector">
            <div class="task-card">
                <h2>✍️ Writing Task 1</h2>
                <p><strong>Time: 20 minutes</strong></p>
                <p>Describe visual data (graph, chart, table, or diagram). Practice analyzing and reporting key features with appropriate vocabulary.</p>
                <button onclick="startTask('writing1')">Start Task 1</button>
            </div>

            <div class="task-card">
                <h2>📄 Writing Task 2</h2>
                <p><strong>Time: 20 minutes</strong></p>
                <p>Write an essay responding to a prompt. Practice structuring arguments, developing ideas, and using academic language effectively.</p>
                <button onclick="startTask('writing2')">Start Task 2</button>
            </div>

            <div class="task-card">
                <h2>🎤 Speaking Practice</h2>
                <p><strong>Time: 20 minutes</strong></p>
                <p>Complete all three parts of the Speaking test. Practice fluency, vocabulary range, and coherent responses to various question types.</p>
                <button onclick="startTask('speaking')">Start Speaking</button>
            </div>
        </div>

        <!-- Writing Task 1 -->
        <div id="writing1Task" class="task-content">
            <button class="back-button" onclick="backToSelection()">← Back to Tasks</button>
            
            <div class="timer" id="timer1">20:00</div>
            
            <div class="timer-controls">
                <button onclick="startTimer('timer1', 20)">Start Timer</button>
                <button onclick="pauseTimer()" class="danger">Pause</button>
                <button onclick="resetTimer('timer1', 20)" class="danger">Reset</button>
            </div>

            <div class="task-header">
                <h2>Writing Task 1</h2>
                <p style="color: #666;">Minimum 150 words | Recommended time: 20 minutes</p>
            </div>

            <div class="task-instructions">
                <h3>Instructions</h3>
                <ul>
                    <li>You should spend about 20 minutes on this task</li>
                    <li>Write at least 150 words</li>
                    <li>Describe the main features and make comparisons where relevant</li>
                    <li>Do NOT give your opinion</li>
                </ul>
            </div>

            <div class="task-prompt">
                <h3>Task</h3>
                <p><strong>The bar chart below shows the percentage of adults who participated in seven different sports activities in a particular city in 2010 and 2020.</strong></p>
                <p><strong>Summarize the information by selecting and reporting the main features, and make comparisons where relevant.</strong></p>
                
                <div class="chart-placeholder">
                    <p><strong>📊 Bar Chart</strong></p>
                    <p>Sports Participation by Adults (%)</p>
                    <p style="margin-top: 15px; font-size: 0.9em;">
                        Swimming: 2010 (45%) → 2020 (52%)<br>
                        Running: 2010 (38%) → 2020 (48%)<br>
                        Cycling: 2010 (35%) → 2020 (42%)<br>
                        Gym: 2010 (32%) → 2020 (55%)<br>
                        Tennis: 2010 (25%) → 2020 (22%)<br>
                        Football: 2010 (28%) → 2020 (25%)<br>
                        Basketball: 2010 (20%) → 2020 (18%)
                    </p>
                </div>
            </div>

            <div class="response-area">
                <textarea id="writing1Response" placeholder="Type your response here..." oninput="updateWordCount('writing1Response', 'wordCount1')"></textarea>
                <div class="word-count">Words: <span id="wordCount1">0</span></div>
            </div>

            <div class="tips-box">
                <h4>💡 Quick Tips for Task 1:</h4>
                <ul>
                    <li>Start with an overview sentence paraphrasing the task</li>
                    <li>Include an overview paragraph stating the main trends</li>
                    <li>Use comparing language: "whereas," "while," "in contrast"</li>
                    <li>Include specific data from the chart</li>
                    <li>Use variety in your vocabulary and sentence structures</li>
                </ul>
            </div>
        </div>

        <!-- Writing Task 2 -->
        <div id="writing2Task" class="task-content">
            <button class="back-button" onclick="backToSelection()">← Back to Tasks</button>
            
            <div class="timer" id="timer2">20:00</div>
            
            <div class="timer-controls">
                <button onclick="startTimer('timer2', 20)">Start Timer</button>
                <button onclick="pauseTimer()" class="danger">Pause</button>
                <button onclick="resetTimer('timer2', 20)" class="danger">Reset</button>
            </div>

            <div class="task-header">
                <h2>Writing Task 2</h2>
                <p style="color: #666;">Minimum 250 words | Recommended time: 20 minutes (shortened for practice)</p>
            </div>

            <div class="task-instructions">
                <h3>Instructions</h3>
                <ul>
                    <li>You should spend about 20 minutes on this task (normally 40 minutes)</li>
                    <li>Write at least 250 words</li>
                    <li>Give reasons for your answer and include relevant examples</li>
                    <li>Present a clear position throughout your response</li>
                </ul>
            </div>

            <div class="task-prompt">
                <h3>Essay Question</h3>
                <p><strong>Some people believe that social media has brought people closer together, while others think it has made people more isolated.</strong></p>
                <p><strong>Discuss both views and give your own opinion.</strong></p>
                <p style="margin-top: 20px; font-size: 0.95em; color: #666;">Give reasons for your answer and include any relevant examples from your own knowledge or experience.</p>
            </div>

            <div class="response-area">
                <textarea id="writing2Response" placeholder="Type your essay here..." oninput="updateWordCount('writing2Response', 'wordCount2')"></textarea>
                <div class="word-count">Words: <span id="wordCount2">0</span></div>
            </div>

            <div class="tips-box">
                <h4>💡 Quick Tips for Task 2:</h4>
                <ul>
                    <li>Plan your essay structure: Introduction, Body 1, Body 2, Conclusion</li>
                    <li>State your opinion clearly in the introduction and conclusion</li>
                    <li>Use topic sentences to start each body paragraph</li>
                    <li>Support your ideas with examples and explanations</li>
                    <li>Use cohesive devices: "Furthermore," "However," "In addition"</li>
                    <li>Avoid informal language and contractions</li>
                </ul>
            </div>
        </div>

        <!-- Speaking Task -->
        <div id="speakingTask" class="task-content">
            <button class="back-button" onclick="backToSelection()">← Back to Tasks</button>
            
            <div class="timer" id="timer3">20:00</div>
            
            <div class="timer-controls">
                <button onclick="startTimer('timer3', 20)">Start Timer</button>
                <button onclick="pauseTimer()" class="danger">Pause</button>
                <button onclick="resetTimer('timer3', 20)" class="danger">Reset</button>
            </div>

            <div class="task-header">
                <h2>Speaking Test Practice</h2>
                <p style="color: #666;">Complete all 3 parts | Total time: ~20 minutes</p>
            </div>

            <div class="task-instructions">
                <h3>Instructions</h3>
                <ul>
                    <li>Practice speaking out loud - record yourself if possible</li>
                    <li>Part 1: Answer each question (30-40 seconds each)</li>
                    <li>Part 2: Take 1 minute to prepare notes, then speak for 2 minutes</li>
                    <li>Part 3: Answer abstract questions (1-2 minutes each)</li>
                    <li>Focus on fluency, vocabulary range, and pronunciation</li>
                </ul>
            </div>

            <div class="speaking-prompts">
                <div class="speaking-part">
                    <h4>Part 1: Introduction & Interview (4-5 minutes)</h4>
                    <p style="margin-bottom: 15px; color: #666;">Answer these questions about yourself:</p>
                    <ul>
                        <li>What do you do? Do you work or are you a student?</li>
                        <li>What do you like most about your job/studies?</li>
                        <li>Do you prefer to study/work in the morning or evening? Why?</li>
                        <li>What are your hobbies in your free time?</li>
                        <li>Have you taken up any new hobbies recently?</li>
                        <li>Do you prefer indoor or outdoor activities? Why?</li>
                        <li>What type of music do you enjoy listening to?</li>
                        <li>Has your taste in music changed over the years?</li>
                    </ul>
                </div>

                <div class="speaking-part">
                    <h4>Part 2: Long Turn (3-4 minutes total)</h4>
                    <p style="margin-bottom: 15px; color: #666;"><strong>Preparation time: 1 minute | Speaking time: 2 minutes</strong></p>
                    <div style="background: white; padding: 20px; border-radius: 10px; border: 2px solid #667eea;">
                        <p style="font-size: 1.1em; margin-bottom: 15px;"><strong>Describe a skill you learned that you found useful.</strong></p>
                        <p style="color: #666; margin-bottom: 10px;">You should say:</p>
                        <ul style="color: #444; line-height: 2;">
                            <li>What the skill is</li>
                            <li>How you learned it</li>
                            <li>When you use this skill</li>
                            <li>And explain why you think it is useful</li>
                        </ul>
                    </div>
                    <p style="margin-top: 15px; color: #666;"><strong>Follow-up questions (answer briefly):</strong></p>
                    <ul>
                        <li>Do you think you'll continue using this skill in the future?</li>
                        <li>Would you recommend others to learn this skill?</li>
                    </ul>
                </div>

                <div class="speaking-part">
                    <h4>Part 3: Discussion (4-5 minutes)</h4>
                    <p style="margin-bottom: 15px; color: #666;">Discuss these abstract questions related to skills and learning:</p>
                    <ul>
                        <li>What skills do you think are most important for young people to learn today?</li>
                        <li>How has technology changed the way people learn new skills?</li>
                        <li>Do you think people learn better in groups or individually? Why?</li>
                        <li>Some people say practical skills are more valuable than academic knowledge. What's your view?</li>
                        <li>How important is lifelong learning in today's society?</li>
                        <li>What role should schools play in teaching life skills versus academic subjects?</li>
                    </ul>
                </div>
            </div>

            <div class="tips-box">
                <h4>💡 Quick Tips for Speaking:</h4>
                <ul>
                    <li><strong>Part 1:</strong> Give extended answers (3-4 sentences), not just yes/no</li>
                    <li><strong>Part 2:</strong> Use all your preparation time to make notes (keywords only!)</li>
                    <li><strong>Part 2:</strong> Cover all bullet points and speak for the full 2 minutes</li>
                    <li><strong>Part 3:</strong> Give in-depth answers with explanations and examples</li>
                    <li>Use a range of vocabulary and grammatical structures</li>
                    <li>Don't worry about small mistakes - keep talking fluently</li>
                    <li>Use discourse markers: "Well," "Actually," "In my opinion"</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        let timerInterval;
        let isPaused = false;
        let currentTime = 0;

        function startTask(taskName) {
            document.getElementById('taskSelector').style.display = 'none';
            document.getElementById(taskName + 'Task').classList.add('active');
        }

        function backToSelection() {
            document.querySelectorAll('.task-content').forEach(task => {
                task.classList.remove('active');
            });
            document.getElementById('taskSelector').style.display = 'grid';
            pauseTimer();
        }

        function startTimer(timerId, minutes) {
            clearInterval(timerInterval);
            isPaused = false;
            currentTime = minutes * 60;
            
            timerInterval = setInterval(() => {
                if (!isPaused) {
                    currentTime--;
                    
                    const mins = Math.floor(currentTime / 60);
                    const secs = currentTime % 60;
                    
                    document.getElementById(timerId).textContent = 
                        `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    
                    if (currentTime <= 0) {
                        clearInterval(timerInterval);
                        document.getElementById(timerId).textContent = "TIME'S UP!";
                        document.getElementById(timerId).style.background = 
                            'linear-gradient(135deg, #f5576c 0%, #f093fb 100%)';
                        alert("⏰ Time's up! Great job completing the task!");
                    }
                }
            }, 1000);
        }

        function pauseTimer() {
            isPaused = !isPaused;
        }

        function resetTimer(timerId, minutes) {
            clearInterval(timerInterval);
            isPaused = false;
            currentTime = minutes * 60;
            document.getElementById(timerId).textContent = `${minutes}:00`;
            document.getElementById(timerId).style.background = 
                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)';
        }

        function updateWordCount(textareaId, countId) {
            const text = document.getElementById(textareaId).value;
            const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
            document.getElementById(countId).textContent = words;
            
            // Change color based on word count requirements
            const countElement = document.getElementById(countId);
            if (textareaId === 'writing1Response') {
                countElement.style.color = words >= 150 ? '#4caf50' : '#f5576c';
            } else if (textareaId === 'writing2Response') {
                countElement.style.color = words >= 250 ? '#4caf50' : '#f5576c';
            }
        }
    </script>
</body>
</html>