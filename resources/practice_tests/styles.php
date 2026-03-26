<!-- Shared CSS Styles for All Practice Tests -->
<style>
/* ==================== COMMON LAYOUT ==================== */
.test-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 1.5rem;
}

/* ==================== TEST HEADER ==================== */
.test-header {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.test-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.test-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.95rem;
    color: #6b7280;
    flex-wrap: wrap;
}

.test-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* ==================== TIMER ==================== */
.timer-display {
    font-size: 2.5rem;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    text-align: center;
    color: #3b82f6;
    margin-bottom: 1.5rem;
}

.timer-display.warning {
    color: #f59e0b;
}

.timer-display.danger {
    color: #ef4444;
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* ==================== WORD COUNT ==================== */
.word-count-container {
    display: flex;
    justify-content: space-between;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.word-count-item {
    text-align: center;
}

.word-count-label {
    font-size: 0.8rem;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.word-count-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
}

.word-count-value.below-target {
    color: #ef4444;
}

.word-count-value.at-target {
    color: #10b981;
}

/* ==================== WRITING AREA ==================== */
.writing-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.essay-textarea {
    width: 100%;
    min-height: 400px;
    padding: 1.5rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    line-height: 1.8;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    resize: vertical;
    transition: border-color 0.3s;
}

.essay-textarea:focus {
    outline: none;
    border-color: #3b82f6;
}

/* ==================== MEDIA DISPLAY ==================== */
.media-section {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.media-section img {
    width: 100%;
    max-width: 800px;
    height: auto;
    display: block;
    margin: 0 auto;
    border-radius: 8px;
}

.media-section audio {
    width: 100%;
    margin: 1rem 0;
}

.media-section video {
    width: 100%;
    max-width: 800px;
    margin: 0 auto;
    display: block;
    border-radius: 8px;
}

/* ==================== QUESTION DISPLAY ==================== */
.question-box {
    background: #f0f9ff;
    border-left: 4px solid #3b82f6;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    white-space: pre-line;
    line-height: 1.8;
}

.question-box strong {
    color: #1e40af;
}

/* ==================== CUE CARD (Speaking) ==================== */
.cue-card {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 30px rgba(251, 191, 36, 0.3);
    border: 3px solid #fbbf24;
}

.cue-card h3 {
    color: #92400e;
    margin-bottom: 1.5rem;
}

.cue-card-points {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.cue-card-points li {
    margin-bottom: 0.75rem;
    color: #374151;
}

/* ==================== AUDIO RECORDING ==================== */
.recording-area {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.record-button {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: none;
    font-size: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    transition: all 0.3s;
    cursor: pointer;
}

.record-button.ready {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: white;
}

.record-button.ready:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 30px rgba(59, 130, 246, 0.4);
}

.record-button.recording {
    background: linear-gradient(135deg, #ef4444, #f87171);
    color: white;
    animation: recordPulse 1.5s infinite;
}

@keyframes recordPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    50% { box-shadow: 0 0 0 20px rgba(239, 68, 68, 0); }
}

/* ==================== READING QUESTIONS ==================== */
.question-item {
    background: #f9fafb;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3b82f6;
}

.question-number {
    display: inline-block;
    width: 30px;
    height: 30px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    text-align: center;
    line-height: 30px;
    font-weight: 700;
    margin-right: 1rem;
}

.answer-input {
    width: 100%;
    max-width: 400px;
    padding: 0.75rem;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    margin-top: 0.5rem;
}

.answer-input:focus {
    outline: none;
    border-color: #3b82f6;
}

.answer-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1rem;
}

.answer-option {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.answer-option:hover {
    border-color: #3b82f6;
    background: #f0f9ff;
}

.answer-option input[type="radio"],
.answer-option input[type="checkbox"] {
    margin-right: 1rem;
}

/* ==================== CONTROLS SIDEBAR ==================== */
.controls-sidebar {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    position: sticky;
    top: 20px;
}

/* ==================== BUTTONS ==================== */
.btn-primary-gradient {
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s;
    cursor: pointer;
}

.btn-primary-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    color: white;
}

.btn-submit {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #3b82f6, #60a5fa);
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

.btn-reset {
    width: 100%;
    padding: 0.75rem;
    background: #f3f4f6;
    color: #6b7280;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    margin-top: 0.5rem;
    cursor: pointer;
}

.btn-reset:hover {
    background: #e5e7eb;
}

/* ==================== INSTRUCTIONS ==================== */
.instructions-box {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.instructions-box h5 {
    color: #1e40af;
    margin-bottom: 1rem;
}

.instructions-box ol, .instructions-box ul {
    margin-bottom: 0;
}

.instructions-box li {
    margin-bottom: 0.5rem;
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 768px) {
    .test-container {
        padding: 1rem 0.5rem;
    }
    
    .test-header {
        padding: 1.5rem;
    }
    
    .timer-display {
        font-size: 2rem;
    }
    
    .controls-sidebar {
        position: static;
        margin-bottom: 2rem;
    }
    
    .test-meta {
        flex-direction: column;
        gap: 0.75rem;
    }
}

/* ==================== UTILITY CLASSES ==================== */
.text-center {
    text-align: center;
}

.mb-1 { margin-bottom: 0.5rem; }
.mb-2 { margin-bottom: 1rem; }
.mb-3 { margin-bottom: 1.5rem; }
.mb-4 { margin-bottom: 2rem; }

.mt-1 { margin-top: 0.5rem; }
.mt-2 { margin-top: 1rem; }
.mt-3 { margin-top: 1.5rem; }
.mt-4 { margin-top: 2rem; }
</style>