
	// Audio management for listening section
// Audio management for listening section
let audioStarted = false;
let audioUpdateInterval = null;

/* Utility: format seconds to MM:SS */
function formatTime(sec) {
    if (isNaN(sec) || !isFinite(sec)) return '00:00';
    const m = Math.floor(sec / 60);
    const s = Math.floor(sec % 60);
    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
}

function updateAudioTime() {
    const audio = document.getElementById('listeningAudio');
    const display = document.getElementById('audioTimeDisplay');
    if (!audio || !display) return;
    display.textContent = `${formatTime(audio.currentTime)} / ${audio.duration ? formatTime(audio.duration) : '--:--'}`;
}

function initializeListeningAudio() {
    const audio = document.getElementById('listeningAudio');
    const audioIndicator = document.getElementById('audioIndicator');
    const audioText = document.getElementById('audioText');
    const audioStatusText = document.getElementById('audioStatusText');
    const startBtn = document.getElementById('startAudioBtn');

    if (!audio || !audioIndicator || !audioText || !audioStatusText || !startBtn) {
        console.error('Audio: missing required DOM elements');
        return;
    }
    if (audioStarted) return;

    // Disable button immediately
    startBtn.disabled = true;
    startBtn.textContent = 'Preparing...';

    // Initial status
    audioIndicator.textContent = '🟡';
    audioText.textContent = 'Loading audio...';
    audioStatusText.textContent = 'Loading…';

    function onLoaded() {
        audioIndicator.textContent = '🟢';
        audioText.textContent = 'Audio ready — starting in 3 seconds...';
        audioStatusText.textContent = 'Starting soon…';

        setTimeout(() => {
            audio.play().then(() => {
                audioStarted = true;
                audioIndicator.textContent = '🔴';
                audioText.textContent = 'Audio playing — Listen carefully!';
                audioText.classList.add('playing-indicator');
                audioStatusText.textContent = 'Audio playing (cannot pause or replay)';
                startBtn.textContent = 'Playing…';
                audioUpdateInterval = setInterval(updateAudioTime, 250);
            }).catch(err => {
                console.error('Playback failed:', err);
                audioIndicator.textContent = '⚠️';
                audioText.textContent = 'Playback blocked or failed';
                audioStatusText.textContent = 'Audio error — try clicking to interact with the page first';
                startBtn.disabled = false;
                startBtn.textContent = '▶ Start Listening';
            });
        }, 3000);
    }

    function onEnded() {
        audioIndicator.textContent = '✅';
        audioText.textContent = 'Audio completed';
        audioText.classList.remove('playing-indicator');
        audioStatusText.textContent = 'Audio finished — you may proceed';
        clearInterval(audioUpdateInterval);

        const nextBtn = document.getElementById('nextSectionBtn');
        if (nextBtn) {
            nextBtn.disabled = false;
            nextBtn.style.background = 'linear-gradient(135deg, green, blue)';
            nextBtn.textContent = 'Next Section → (Audio Complete)';
        }
    }

    function onError() {
        audioIndicator.textContent = '❌';
        audioText.textContent = 'Audio failed to load';
        audioStatusText.textContent = 'Audio unavailable';
        startBtn.disabled = false;
        startBtn.textContent = '▶ Start Listening';
    }

    // Attach listeners (remove first to avoid duplication)
    audio.removeEventListener('loadeddata', onLoaded);
    audio.removeEventListener('ended', onEnded);
    audio.removeEventListener('error', onError);

    audio.addEventListener('loadeddata', onLoaded);
    audio.addEventListener('ended', onEnded);
    audio.addEventListener('error', onError);
    audio.addEventListener('contextmenu', e => e.preventDefault());
    // A pause from outside the page (media keys, the phone's or computer's media controls) is undone: the recording
    // plays once, without stopping, as in the real test.
    audio.addEventListener('pause', () => { if (audioStarted && !audio.ended) audio.play().catch(() => {}); });

    // If already cached, fire loaded handler immediately
    if (audio.readyState >= 2) {
        onLoaded();
    } else {
        audio.load();
    }
}

// Attach listener to Start button
document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('startAudioBtn');
    if (startBtn) {
        startBtn.addEventListener('click', initializeListeningAudio);
    }
});

// Prevent students from using browser audio controls
document.addEventListener('keydown', function(e) {
    if (document.getElementById('section1')?.classList.contains('active')) {
        if (['Space','ArrowLeft','ArrowRight'].includes(e.code)) {
            e.preventDefault();
        }
    }
});






        // Answer keys
        const LISTENING_KEY = ["irrigation","women","wire","seeds","posts","transport","preservation","fish","bees","design"];
        const READING_KEY = ["temptations","completion","reference","disruption","failings","skills"];

        // Timer variables
        let timeLeft = 3600; // 60 minutes in seconds
        let timerInterval;

        // Helper function to normalize answers
        function normalizeStr(s) {
            return String(s || '').trim().toLowerCase().replace(/[^a-z]/g, '');
        }

        // Timer functions
        function startTimer() {
            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    alert('Time is up! The test will now be submitted.');
                    finishQuiz();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            document.getElementById('time').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
            // Change color when time is running low
            if (timeLeft <= 300) { // 5 minutes
                document.getElementById('time').style.color = '#c92a2a';
            } else if (timeLeft <= 600) { // 10 minutes
                document.getElementById('time').style.color = '#ffc107';
            }
        }

        // Navigation functions
        function showPage(pageId) {
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });
            document.getElementById(pageId).classList.add('active');
        }

        function startQuiz() {
            showPage('section1');
            startTimer();
        }

        function goToPage(pageId) {
            showPage(pageId);
        }

        function goToSection(sectionNum) {
            // Save current answers before moving
            saveCurrentAnswers();
            showPage(`section${sectionNum}`);
        }

        function finishQuiz() {
            clearInterval(timerInterval);
            saveCurrentAnswers();
            gradeAndShowResults();
            showPage('results');
        }

        // Save answers to memory
        let savedAnswers = {
            listening: [],
            reading: [],
            writing: ''
        };

        function saveCurrentAnswers() {
            // Save listening answers
            for (let i = 1; i <= 10; i++) {
                const input = document.getElementById(`l${i}`);
                if (input) {
                    savedAnswers.listening[i-1] = input.value;
                }
            }

            // Save reading answers
            for (let i = 1; i <= 6; i++) {
                const input = document.getElementById(`r${i}`);
                if (input) {
                    savedAnswers.reading[i-1] = input.value;
                }
            }

            // Save writing answer
            const writingInput = document.getElementById('writingResponse');
            if (writingInput) {
                savedAnswers.writing = writingInput.value;
            }
        }

        // Word count functionality
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('writingResponse');
            const wordCountSpan = document.getElementById('wordCount');
            
            if (textarea && wordCountSpan) {
                textarea.addEventListener('input', function() {
                    const text = this.value.trim();
                    const wordCount = text === '' ? 0 : text.split(/\s+/).length;
                    wordCountSpan.textContent = wordCount;
                    
                    // Change color based on word count
                    if (wordCount >= 150) {
                        wordCountSpan.style.color = 'var(--success-color)';
                    } else {
                        wordCountSpan.style.color = 'var(--error-color)';
                    }
                });
            }
        });

        // Results and grading functions
        function gradeAndShowResults() {
            const listening = savedAnswers.listening || [];
            const reading = savedAnswers.reading || [];

            // Grade listening
            let listenScore = 0;
            const listenRows = [];
            for (let i = 0; i < LISTENING_KEY.length; i++) {
                const userRaw = listening[i] || '';
                const userNorm = normalizeStr(userRaw);
                const correctNorm = normalizeStr(LISTENING_KEY[i]);
                const ok = userNorm === correctNorm;
                if (ok) listenScore++;
                listenRows.push({
                    num: i + 1, 
                    user: userRaw || '(empty)', 
                    correct: LISTENING_KEY[i], 
                    ok: ok
                });
            }

            // Grade reading
            let readScore = 0;
            const readRows = [];
            for (let i = 0; i < READING_KEY.length; i++) {
                const userRaw = reading[i] || '';
                const userNorm = normalizeStr(userRaw);
                const correctNorm = normalizeStr(READING_KEY[i]);
                const ok = userNorm === correctNorm;
                if (ok) readScore++;
                readRows.push({
                    num: i + 1, 
                    user: userRaw || '(empty)', 
                    correct: READING_KEY[i], 
                    ok: ok
                });
            }

            // Writing assessment (basic word count check)
            const writingText = savedAnswers.writing || '';
            const wordCount = writingText.trim() === '' ? 0 : writingText.trim().split(/\s+/).length;
            const writingScore = wordCount >= 150 ? 'Complete' : 'Incomplete';

            // Display results
            displayResults(listenScore, readScore, writingScore, wordCount, listenRows, readRows);
        }

        function displayResults(listenScore, readScore, writingScore, wordCount, listenRows, readRows) {
            // Show summary boxes
            const summaryEl = document.getElementById('summary');
            summaryEl.innerHTML = '';

            const listeningBox = document.createElement('div');
            listeningBox.className = 'scoreBox';
            listeningBox.innerHTML = `
                <div class="score-label">Listening</div>
                <div class="score-value">${listenScore} / ${LISTENING_KEY.length}</div>
            `;

            const readingBox = document.createElement('div');
            readingBox.className = 'scoreBox';
            readingBox.innerHTML = `
                <div class="score-label">Reading</div>
                <div class="score-value">${readScore} / ${READING_KEY.length}</div>
            `;

            const writingBox = document.createElement('div');
            writingBox.className = 'scoreBox';
            writingBox.innerHTML = `
                <div class="score-label">Writing</div>
                <div class="score-value">${writingScore}</div>
                <div style="font-size: 12px; margin-top: 5px;">${wordCount} words</div>
            `;

            const speakingBox = document.createElement('div');
            speakingBox.className = 'scoreBox';
            speakingBox.innerHTML = `
                <div class="score-label">Speaking</div>
                <div class="score-value">Examiner Assessed</div>
            `;

            summaryEl.appendChild(listeningBox);
            summaryEl.appendChild(readingBox);
            summaryEl.appendChild(writingBox);
            summaryEl.appendChild(speakingBox);

            // Total score
            const totalEl = document.getElementById('totalArea');
            const total = listenScore + readScore;
            const percentage = Math.round((total / (LISTENING_KEY.length + READING_KEY.length)) * 100);
            totalEl.innerHTML = `
                <div>Total Score: <span style="color: var(--primary-color)">${total} / ${LISTENING_KEY.length + READING_KEY.length}</span></div>
                <div style="font-size: 16px; margin-top: 10px;">Accuracy: ${percentage}%</div>
            `;

            // Detailed breakdown
            const detailsEl = document.getElementById('detailsArea');
            detailsEl.innerHTML = '';

            // Listening details
            const listenTitle = document.createElement('div');
            listenTitle.className = 'sectionTitle';
            listenTitle.textContent = 'Listening — Question by Question';
            detailsEl.appendChild(listenTitle);

            const tableL = document.createElement('table');
            tableL.innerHTML = `
                <thead>
                    <tr>
                        <th style="width: 60px">Q#</th>
                        <th>Your Answer</th>
                        <th>Correct Answer</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
            const tbodyL = tableL.querySelector('tbody');
            listenRows.forEach(row => {
                const tr = document.createElement('tr');
                tr.className = row.ok ? 'correct' : 'wrong';
                tr.innerHTML = `
                    <td>${row.num}</td>
                    <td class="user">${escapeHtml(row.user)}</td>
                    <td class="correctAns">${escapeHtml(row.correct)}</td>
                `;
                tbodyL.appendChild(tr);
            });
            detailsEl.appendChild(tableL);

            // Reading details
            const readTitle = document.createElement('div');
            readTitle.className = 'sectionTitle';
            readTitle.textContent = 'Reading — Question by Question';
            detailsEl.appendChild(readTitle);

            const tableR = document.createElement('table');
            tableR.innerHTML = `
                <thead>
                    <tr>
                        <th style="width: 60px">Q#</th>
                        <th>Your Answer</th>
                        <th>Correct Answer</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
            const tbodyR = tableR.querySelector('tbody');
            readRows.forEach(row => {
                const tr = document.createElement('tr');
                tr.className = row.ok ? 'correct' : 'wrong';
                tr.innerHTML = `
                    <td>${row.num}</td>
                    <td class="user">${escapeHtml(row.user)}</td>
                    <td class="correctAns">${escapeHtml(row.correct)}</td>
                `;
                tbodyR.appendChild(tr);
            });
            detailsEl.appendChild(tableR);

            // Writing assessment
            const writingTitle = document.createElement('div');
            writingTitle.className = 'sectionTitle';
            writingTitle.textContent = 'Writing Assessment';
            detailsEl.appendChild(writingTitle);

            const writingAssessment = document.createElement('div');
            writingAssessment.style.background = '#f8f9fa';
            writingAssessment.style.padding = '20px';
            writingAssessment.style.borderRadius = '8px';
            writingAssessment.style.margin = '15px 0';
            
            let writingFeedback = '';
            if (wordCount >= 150) {
                writingFeedback = `
                    <div style="color: var(--success-color); font-weight: bold;">✓ Word Count: ${wordCount} words (Meets requirement)</div>
                    <p style="margin-top: 10px;">Your writing meets the minimum word count requirement. For a complete assessment, this would be evaluated by a qualified examiner on:</p>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <li>Task Achievement</li>
                        <li>Coherence and Cohesion</li>
                        <li>Lexical Resource</li>
                        <li>Grammatical Range and Accuracy</li>
                    </ul>
                `;
            } else {
                writingFeedback = `
                    <div style="color: var(--error-color); font-weight: bold;">✗ Word Count: ${wordCount} words (Below minimum requirement)</div>
                    <p style="margin-top: 10px;">Your response is below the minimum 150-word requirement. This would significantly impact your Task Achievement score in the actual IELTS exam.</p>
                `;
            }
            
            writingAssessment.innerHTML = writingFeedback;
            detailsEl.appendChild(writingAssessment);
        }

        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Restart functionality
        document.addEventListener('DOMContentLoaded', function() {
            const restartBtn = document.getElementById('restartBtn');
            if (restartBtn) {
                restartBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to restart the test? This will clear all your answers.')) {
                        restartQuiz();
                    }
                });
            }
        });

        function restartQuiz() {
            // Clear all answers
            savedAnswers = {
                listening: [],
                reading: [],
                writing: ''
            };

            // Clear all input fields
            for (let i = 1; i <= 10; i++) {
                const input = document.getElementById(`l${i}`);
                if (input) input.value = '';
            }

            for (let i = 1; i <= 6; i++) {
                const input = document.getElementById(`r${i}`);
                if (input) input.value = '';
            }

            const writingInput = document.getElementById('writingResponse');
            if (writingInput) writingInput.value = '';

            // Reset timer
            clearInterval(timerInterval);
            timeLeft = 3600;
            updateTimerDisplay();
            document.getElementById('time').style.color = 'var(--primary-color)';

            // Reset word count
            const wordCountSpan = document.getElementById('wordCount');
            if (wordCountSpan) {
                wordCountSpan.textContent = '0';
                wordCountSpan.style.color = 'var(--error-color)';
            }

            // Go back to intro
            showPage('intro');
        }

        // PDF Download functionality (basic implementation)
        document.addEventListener('DOMContentLoaded', function() {
            const downloadSummaryBtn = document.getElementById('downloadSummaryPDF');
            const downloadFullBtn = document.getElementById('downloadFullPDF');

            if (downloadSummaryBtn) {
                downloadSummaryBtn.addEventListener('click', function() {
                    // This would require a proper PDF library implementation
                    alert('PDF download feature would be implemented with a proper PDF generation library in a full application.');
                });
            }

            if (downloadFullBtn) {
                downloadFullBtn.addEventListener('click', function() {
                    // This would require a proper PDF library implementation
                    alert('PDF download feature would be implemented with a proper PDF generation library in a full application.');
                });
            }
        });

        // Auto-save functionality
        setInterval(saveCurrentAnswers, 30000); // Auto-save every 30 seconds

        // Load saved answers on page load (if any)
        document.addEventListener('DOMContentLoaded', function() {
            // This could be enhanced to load from localStorage if needed
            // For now, we keep everything in memory during the session
        });