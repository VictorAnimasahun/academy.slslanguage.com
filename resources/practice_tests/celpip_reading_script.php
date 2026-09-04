<script>
const CORRECT   = <?= json_encode($answers) ?>;
const MAX_SCORE = <?= $maxScore ?>;
const TEST_CODE = <?= json_encode($testCode) ?>;
const TOTAL_PARTS = <?= count($parts) ?>;
const startTime = Date.now();
let userAnswers = {}, timeLeft = <?= $timeLimit ?>, submitted = false, currentPart = 1;

const timerInterval = setInterval(() => {
    if (submitted) return;
    timeLeft--;
    const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
    const text = `${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`;
    document.querySelectorAll('.timerDisplayShared').forEach(el => {
        el.textContent = text;
        el.style.color = timeLeft <= 300 ? '#f87171' : '';
    });
    if (timeLeft <= 0) { clearInterval(timerInterval); handleSubmit(true); }
}, 1000);

function showPart(n) {
    document.querySelectorAll('.celpip-screen').forEach(s => s.style.display = 'none');
    document.querySelector('.celpip-screen[data-part="' + n + '"]').style.display = '';
    for (let i = 1; i <= TOTAL_PARTS; i++) {
        const dot = document.getElementById('progressDot-' + i);
        if (!dot) continue;
        dot.classList.remove('current', 'done');
        if (i < n) dot.classList.add('done');
        else if (i === n) dot.classList.add('current');
    }
    currentPart = n;
    document.querySelector('.celpip-body').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function celpipGoBack() {
    if (currentPart > 1) showPart(currentPart - 1);
}

function celpipGoNext() {
    if (currentPart < TOTAL_PARTS) {
        showPart(currentPart + 1);
    } else {
        handleSubmit();
    }
}

function collectAnswers() {
    document.querySelectorAll('select[data-q]').forEach(el => {
        userAnswers[el.dataset.q] = el.value.trim().toLowerCase();
    });
}

function gradeAnswers() {
    let score = 0;
    for (let q in CORRECT) {
        const given = (userAnswers[q] || '').toLowerCase().trim();
        if (CORRECT[q].includes(given)) score++;
    }
    return score;
}

function showFeedback() {
    document.querySelectorAll('select[data-q]').forEach(el => {
        const q = el.dataset.q, given = el.value.trim().toLowerCase(), correct = CORRECT[q] || [];
        const isCorrect = correct.includes(given);
        el.classList.remove('correct', 'incorrect');
        el.classList.add(isCorrect ? 'correct' : 'incorrect');
        let hint = el.parentElement.querySelector('.celpip-feedback-hint');
        if (!hint) {
            hint = document.createElement('div');
            hint.className = 'celpip-feedback-hint';
            el.after(hint);
        }
        hint.className = 'celpip-feedback-hint ' + (isCorrect ? 'ok' : 'bad');
        hint.textContent = isCorrect ? '✓ Correct' : ('✗ Correct answer: ' + (correct[0] || '').toUpperCase());
    });
}

function saveAttempt(score, timeSpent) {
    fetch('save_attempt.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            test_code:  TEST_CODE,
            score,
            max_score:  MAX_SCORE,
            band_score: null,
            time_spent: timeSpent,
            answers:    userAnswers,
        }),
    }).catch(err => console.error('save_attempt:', err));
}

async function handleSubmit(auto = false) {
    if (submitted) return;
    if (!auto) {
        const r = await Swal.fire({
            title: 'Submit Test?', text: 'You cannot change your answers after submitting.', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Yes, submit', cancelButtonText: 'Continue working', confirmButtonColor: '#9c1f2e',
        });
        if (!r.isConfirmed) return;
    }
    submitted = true;
    clearInterval(timerInterval);
    document.querySelectorAll('.celpip-screen').forEach(s => s.style.display = '');
    collectAnswers();
    const score     = gradeAnswers();
    const timeSpent = Math.round((Date.now() - startTime) / 1000);
    showFeedback();
    saveAttempt(score, timeSpent);
    document.querySelectorAll('select').forEach(el => el.disabled = true);
    document.querySelectorAll('.celpip-next-btn, .celpip-back-btn').forEach(el => el.disabled = true);
    document.querySelectorAll('.celpip-progress').forEach(el => el.style.display = 'none');
    Swal.fire({
        title: 'Test Complete!',
        html: `<div class="text-center">
                    <div class="result-badge" style="background:#9c1f2e;">Score: ${score} / ${MAX_SCORE}</div>
                    <p class="mt-3 text-muted small">Scroll through each part below to see correct answers.</p>
                </div>`,
        icon: 'success', confirmButtonText: 'View Feedback', confirmButtonColor: '#9c1f2e',
    }).then(() => {
        document.querySelector('.celpip-screen[data-part="1"]').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
}
</script>
