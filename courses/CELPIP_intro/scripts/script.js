// ==================
// Answer keys (Updated for CELPIP format)
// ==================

// Listening — 10 answers (multiple choice radio buttons)
const LISTENING_KEY = [
  "B", // She is a journalist.
  "C", // A new government chose to end it.
  "C", // Three years
  "A", // Comprehensive research data about UBI impacts
  "B", // They felt disappointed about lost opportunities.
  "C", // Guelph, Niagara Falls, and Waterloo
  "B", // Some communities are considering municipal programs.
  "C", // Creation of a framework for basic income
  "C", // Payments are reduced based on employment income.
  "B"  // Senate committee decisions and disability benefit discussions
];

// Reading — 11 answers (Q1-6 radio buttons, Q7-11 text/multiple choice)
const READING_KEY = [
  "A", // approved by the city
  "A", // start growing vegetables
  "C", // enthusiastic about the project
  "B", // paying for individual plots
  "A", // are neighbors
  "B", // are cousins
  "C", // the garden plot
  "C", // two plots
  "B", // meeting
  "A", // isn't practical for outdoors
  "A"  // planting season
];

// ==================
// State for answers
// ==================
let savedAnswers = {
  listening: [],
  reading: [],
  writing: ""
};

// Audio management for listening section
let audioStarted = false;
let audioUpdateInterval = null;

// ==================
// Database Functions (New Integration)
// ==================

// Generate unique test ID
function generateTestId() {
    return 'test_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

// Database submission function
async function submitWritingToDatabase(writingContent, testId = null) {
    try {
        const response = await fetch('/api/submit-writing', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                content: writingContent,
                testId: testId || generateTestId(),
                timestamp: new Date().toISOString(),
                wordCount: writingContent.trim().split(/\s+/).length
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to submit writing');
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error submitting writing:', error);
        // Still allow test to continue even if database fails
        return { success: false, error: error.message };
    }
}

// Submit audio to database
async function submitAudioToDatabase(audioBlob) {
    const formData = new FormData();
    formData.append('audio', audioBlob, 'speaking_response.wav');
    formData.append('testId', generateTestId());
    formData.append('timestamp', new Date().toISOString());
    
    try {
        const response = await fetch('/api/submit-audio', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            console.log('Audio submitted successfully');
        }
    } catch (error) {
        console.error('Error submitting audio:', error);
    }
}

// ==================
// Audio Recording Functions
// ==================

// Audio recording functionality - updated for multiple recorders
let mediaRecorders = {};
let audioChunks = {};
let recordingStartTimes = {};
let recordingIntervals = {};

async function initializeAudioRecorder() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        
        // Initialize all 8 recorders
        for (let i = 1; i <= 8; i++) {
            setupRecorderForTask(i, stream);
        }
        
    } catch (error) {
        console.error('Error accessing microphone:', error);
        // Update all status indicators
        for (let i = 1; i <= 8; i++) {
            const statusSpan = document.getElementById(`recordingStatus${i}`);
            if (statusSpan) {
                statusSpan.textContent = 'Microphone access denied';
            }
        }
    }
}

function setupRecorderForTask(taskNum, stream) {
    mediaRecorders[taskNum] = new MediaRecorder(stream);
    audioChunks[taskNum] = [];
    
    mediaRecorders[taskNum].ondataavailable = (event) => {
        audioChunks[taskNum].push(event.data);
    };
    
    mediaRecorders[taskNum].onstop = () => {
        const audioBlob = new Blob(audioChunks[taskNum], { type: 'audio/wav' });
        const audioUrl = URL.createObjectURL(audioBlob);
        
        const playback = document.getElementById(`recordingPlayback${taskNum}`);
        playback.src = audioUrl;
        playback.style.display = 'block';
        
        document.getElementById(`playRecording${taskNum}`).disabled = false;
        
        submitAudioToDatabase(audioBlob, taskNum);
    };
    
    setupRecordingControlsForTask(taskNum);
}

function setupRecordingControlsForTask(taskNum) {
    const startBtn = document.getElementById(`startRecording${taskNum}`);
    const stopBtn = document.getElementById(`stopRecording${taskNum}`);
    const statusSpan = document.getElementById(`recordingStatus${taskNum}`);
    const timerSpan = document.getElementById(`recordingTimer${taskNum}`);
    
    if (!startBtn || !stopBtn || !statusSpan || !timerSpan) {
        console.error(`Missing elements for task ${taskNum}`);
        return;
    }
    
    startBtn.addEventListener('click', () => {
        audioChunks[taskNum] = [];
        mediaRecorders[taskNum].start();
        
        startBtn.disabled = true;
        stopBtn.disabled = false;
        statusSpan.textContent = 'Recording...';
        statusSpan.style.color = '#dc3545';
        
        recordingStartTimes[taskNum] = Date.now();
        recordingIntervals[taskNum] = setInterval(() => updateRecordingTimer(taskNum), 1000);
    });
    
    stopBtn.addEventListener('click', () => {
        mediaRecorders[taskNum].stop();
        
        startBtn.disabled = false;
        stopBtn.disabled = true;
        statusSpan.textContent = 'Recording stopped';
        statusSpan.style.color = '#28a745';
        
        clearInterval(recordingIntervals[taskNum]);
    });
}

function updateRecordingTimer(taskNum) {
    const elapsed = Date.now() - recordingStartTimes[taskNum];
    const minutes = Math.floor(elapsed / 60000);
    const seconds = Math.floor((elapsed % 60000) / 1000);
    
    const timerSpan = document.getElementById(`recordingTimer${taskNum}`);
    if (timerSpan) {
        timerSpan.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
}

async function submitAudioToDatabase(audioBlob, taskNum) {
    const formData = new FormData();
    formData.append('audio', audioBlob, `speaking_task_${taskNum}.wav`);
    formData.append('testId', generateTestId());
    formData.append('taskNumber', taskNum);
    formData.append('timestamp', new Date().toISOString());
    
    try {
        const response = await fetch('/api/submit-audio', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            console.log(`Audio for task ${taskNum} submitted successfully`);
        }
    } catch (error) {
        console.error(`Error submitting audio for task ${taskNum}:`, error);
    }
}

// ==================
// Utility Functions
// ==================

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

// Timer variables
let timeLeft = 3600; // 60 minutes in seconds
let timerInterval;

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
    
    // Initialize recorder when reaching speaking section
    if (sectionNum === 4) {
        setTimeout(initializeAudioRecorder, 500);
    }
}

// Modified finishQuiz function with database integration
function finishQuiz() {
    clearInterval(timerInterval);
    saveCurrentAnswers();
    
    // Submit writing to database (New Integration)
    if (savedAnswers.writing && savedAnswers.writing.trim()) {
        submitWritingToDatabase(savedAnswers.writing)
            .then(result => {
                if (result.success) {
                    console.log('Writing submitted successfully:', result);
                } else {
                    console.warn('Writing submission failed:', result.error);
                }
            });
    }
    
    gradeAndShowResults();
    showPage('results');
}

// ==================
// Save answers (Updated for CELPIP format)
// ==================
function saveCurrentAnswers() {
  // Save listening answers (radio buttons l1–l10)
  savedAnswers.listening = [];
  for (let i = 1; i <= 10; i++) {
    const checked = document.querySelector(`input[name="l${i}"]:checked`);
    savedAnswers.listening[i - 1] = checked ? checked.value : '';
  }

  // Save reading answers
  savedAnswers.reading = [];
  // Q1–6 are radio buttons
  for (let i = 1; i <= 6; i++) {
    const checked = document.querySelector(`input[name="r${i}"]:checked`);
    savedAnswers.reading[i - 1] = checked ? checked.value : '';
  }
  // Q7–11 are text inputs
  for (let i = 7; i <= 11; i++) {
    const input = document.getElementById(`r${i}`);
    savedAnswers.reading[i - 1] = input ? input.value.trim() : '';
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

// ==================
// Grading (Updated for CELPIP format)
// ==================
function gradeAndShowResults() {
  const listening = savedAnswers.listening || [];
  const reading = savedAnswers.reading || [];

  // Grade listening (radio button values)
  let listenScore = 0;
  const listenRows = [];
  for (let i = 0; i < LISTENING_KEY.length; i++) {
    const userRaw = listening[i] || '';
    const userNorm = userRaw.trim().toUpperCase();
    const correct = LISTENING_KEY[i];
    const ok = userNorm === correct;
    if (ok) listenScore++;
    listenRows.push({
      num: i + 1,
      user: userRaw || '(empty)',
      correct: correct,
      ok: ok
    });
  }

  // Grade reading (mixed radio buttons and text inputs)
  let readScore = 0;
  const readRows = [];
  for (let i = 0; i < READING_KEY.length; i++) {
    const userRaw = reading[i] || '';
    let userNorm, correct;
    
    if (i < 6) {
      // Q1-6: radio button values (A, B, C, D)
      userNorm = userRaw.trim().toUpperCase();
      correct = READING_KEY[i];
    } else {
      // Q7-11: text inputs (normalize for comparison)
      userNorm = userRaw.trim().toUpperCase();
      correct = READING_KEY[i];
    }
    
    const ok = userNorm === correct;
    if (ok) readScore++;
    readRows.push({
      num: i + 1,
      user: userRaw || '(empty)',
      correct: correct,
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
            <p style="margin-top: 10px;">Your response is below the minimum 150-word requirement. This would significantly impact your Task Achievement score in the actual CELPIP exam.</p>
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

// ==================
// Restart quiz (Updated for CELPIP format)
// ==================
function restartQuiz() {
  // Clear listening radio buttons
  for (let i = 1; i <= 10; i++) {
    const radios = document.querySelectorAll(`input[name="l${i}"]`);
    radios.forEach(r => r.checked = false);
  }

  // Clear reading radios (Q1–6)
  for (let i = 1; i <= 6; i++) {
    const radios = document.querySelectorAll(`input[name="r${i}"]`);
    radios.forEach(r => r.checked = false);
  }

  // Clear reading text inputs (Q7–11)
  for (let i = 7; i <= 11; i++) {
    const input = document.getElementById(`r${i}`);
    if (input) input.value = "";
  }

  // Clear writing response
  const writingInput = document.getElementById('writingResponse');
  if (writingInput) writingInput.value = "";

  // Reset saved answers
  savedAnswers = { listening: [], reading: [], writing: "" };

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

// PDF Download functionality (working implementation)
document.addEventListener('DOMContentLoaded', function() {
    const downloadSummaryBtn = document.getElementById('downloadSummaryPDF');
    const downloadFullBtn = document.getElementById('downloadFullPDF');

    if (downloadSummaryBtn) {
        downloadSummaryBtn.addEventListener('click', function() {
            generateSummaryPDF();
        });
    }

    if (downloadFullBtn) {
        downloadFullBtn.addEventListener('click', function() {
            generateFullResultsPDF();
        });
    }
});

function generateSummaryPDF() {
    const summaryContent = document.getElementById('summary');
    const totalArea = document.getElementById('totalArea');
    
    // Create a temporary container for PDF content
    const pdfContent = document.createElement('div');
    pdfContent.style.padding = '20px';
    pdfContent.style.fontFamily = 'Arial, sans-serif';
    
    pdfContent.innerHTML = `
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">CELPIP Mock Test - Summary Results</h1>
        <div style="margin: 20px 0;">
            ${summaryContent.innerHTML}
        </div>
        <div style="margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
            ${totalArea.innerHTML}
        </div>
        <div style="margin-top: 30px; font-size: 12px; color: #666; text-align: center;">
            <p>Generated on: ${new Date().toLocaleDateString()}</p>
            <p>This is a practice test. Your actual CELPIP score may vary.</p>
        </div>
    `;
    
    // Temporarily add to body
    document.body.appendChild(pdfContent);
    
    const opt = {
        margin: 1,
        filename: `CELPIP_Summary_${new Date().toISOString().split('T')[0]}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(pdfContent).save().then(() => {
        document.body.removeChild(pdfContent);
    });
}

function generateFullResultsPDF() {
    const resultsSection = document.getElementById('quiz-results');
    
    // Create a clean version for PDF
    const pdfContent = resultsSection.cloneNode(true);
    
    // Remove download buttons from the PDF version
    const downloadButtons = pdfContent.querySelector('.download-buttons');
    if (downloadButtons) {
        downloadButtons.remove();
    }
    
    // Remove restart button
    const restartBtn = pdfContent.querySelector('#restartBtn');
    if (restartBtn) {
        restartBtn.remove();
    }
    
    // Add header and footer
    const header = document.createElement('div');
    header.style.textAlign = 'center';
    header.style.marginBottom = '20px';
    header.innerHTML = `
        <h1 style="color: #333;">CELPIP Mock Test - Detailed Results</h1>
        <p style="color: #666;">Generated on: ${new Date().toLocaleDateString()}</p>
    `;
    pdfContent.insertBefore(header, pdfContent.firstChild);
    
    const footer = document.createElement('div');
    footer.style.marginTop = '30px';
    footer.style.textAlign = 'center';
    footer.style.fontSize = '12px';
    footer.style.color = '#666';
    footer.innerHTML = `
        <p>This is a practice test. Your actual CELPIP score may vary.</p>
        <p>For official CELPIP testing, visit celpip.ca</p>
    `;
    pdfContent.appendChild(footer);
    
    // Temporarily add to body
    document.body.appendChild(pdfContent);
    
    const opt = {
        margin: 0.5,
        filename: `CELPIP_Full_Results_${new Date().toISOString().split('T')[0]}.pdf`,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    
    html2pdf().set(opt).from(pdfContent).save().then(() => {
        document.body.removeChild(pdfContent);
    });
}

// Auto-save functionality
setInterval(saveCurrentAnswers, 30000); // Auto-save every 30 seconds

// Load saved answers on page load (if any)
document.addEventListener('DOMContentLoaded', function() {
    // This could be enhanced to load from localStorage if needed
    // For now, we keep everything in memory during the session
});