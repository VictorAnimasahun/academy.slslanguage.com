<?php
// /academy/includes/ai_client.php
// Shared AI call helpers used by the student-facing analyzer API
// (academy/api/api_handler.php) and the tutor-facing analyzer in sls-admin.

if (defined('AI_CLIENT_LOADED')) {
    return;
}
define('AI_CLIENT_LOADED', true);

/**
 * Call AI API (Claude or Gemini)
 */
function callAI($prompt, $provider = 'claude') {
    if ($provider === 'claude') {
        return callClaude($prompt);
    } else {
        return callGemini($prompt);
    }
}

/**
 * Call Claude API
 */
function callClaude($prompt) {
    $ch = curl_init('https://api.anthropic.com/v1/messages');

    $data = [
        'model' => 'claude-sonnet-4-20250514',
        'max_tokens' => 2000,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ]
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'x-api-key: ' . ANTHROPIC_API_KEY,
            'anthropic-version: 2023-06-01'
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        return [
            'success' => false,
            'error' => $error['error']['message'] ?? 'Claude API error'
        ];
    }

    $result = json_decode($response, true);
    return [
        'success' => true,
        'content' => $result['content'][0]['text']
    ];
}

/**
 * Call Gemini API
 */
function callGemini($prompt) {
    // Use gemini-2.5-flash (latest model)
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . GEMINI_API_KEY;

    $ch = curl_init($url);

    $data = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) {
        return [
            'success' => false,
            'error' => 'Network error: ' . $curlError
        ];
    }

    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        $errorMsg = 'Gemini API error';

        if (isset($error['error']['message'])) {
            $errorMsg = $error['error']['message'];
        }

        error_log("Gemini API Error (HTTP $httpCode): " . print_r($error, true));

        return [
            'success' => false,
            'error' => $errorMsg
        ];
    }

    $result = json_decode($response, true);

    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        error_log("Gemini unexpected response: " . print_r($result, true));
        return [
            'success' => false,
            'error' => 'Unexpected response format from Gemini API'
        ];
    }

    return [
        'success' => true,
        'content' => $result['candidates'][0]['content']['parts'][0]['text']
    ];
}

/**
 * Writing rubric, by exam type/task. Shared by the student-facing analyzer
 * (academy/api/api_handler.php) and the tutor-facing one (sls-admin/tutor_ai_api.php)
 * so the two can't drift out of sync the way they did before this was centralized.
 *
 * CELPIP criteria/sub-factors and CLB anchor language are transcribed from the
 * official CELPIP Teacher Support Pack: "6 - Writing Performance Standards.pdf"
 * (4 categories) and "10 - CELPIP Level Descriptors.pdf" (CLB 3-12/M wording).
 * CELPIP scores on the CLB (Canadian Language Benchmark) scale, 1-12 (or "M" for
 * minimal/insufficient) -- never an IELTS-style "band".
 */
function essayRubric(string $examType, string $taskType, ?int $wordCount = null): string {
    // LLMs count words in raw text unreliably. The word count shown live in
    // the essay textarea (JS split-on-whitespace) is exact, so it's passed in
    // and the model is told to trust it rather than recount from the text --
    // this matters directly for CELPIP/IELTS Task Fulfillment/Achievement,
    // which explicitly score whether the word count is in range.
    $wordCountNote = $wordCount !== null
        ? "\n\nThe response is exactly {$wordCount} words long (counted programmatically by the app, not by you) -- use this exact number for any word-count assessment. Do not recount the words yourself."
        : '';

    if ($examType === 'CELPIP') {
        return "You are an official CELPIP Writing examiner. Score this response using the real CELPIP Writing Performance Standards, on the CLB (Canadian Language Benchmark) scale -- NOT an IELTS band. Assess these 4 official categories:\n"
            . "1. Content/Coherence — number and quality of ideas, organization, examples/supporting details.\n"
            . "2. Vocabulary — word choice, suitable/precise use of words and phrases, range.\n"
            . "3. Readability — grammar and sentence structure, spelling and punctuation, paragraphing, connectors/transitions.\n"
            . "4. Task Fulfillment — relevance, completeness, appropriate tone/register, whether the word count is within range.\n\n"
            . "Use these official CLB anchor points to calibrate your level (each level's wording matters -- this is CELPIP's actual scoring language, distinct from IELTS bands):\n"
            . "• CLB 4 (Adequate proficiency for daily life activities): simple sentences and short paragraphs, communicates personal information, common words, some control of simple grammar.\n"
            . "• CLB 6 (Developing proficiency in workplace/community contexts): short coherent texts, a main idea with some supporting details, organizes ideas into paragraphs, good control of simple grammar/spelling/punctuation.\n"
            . "• CLB 7 (Adequate proficiency in workplace/community contexts): short moderately complex factual texts, main idea with supporting details, adequate control of complex grammatical structures.\n"
            . "• CLB 8 (Good proficiency in workplace/community contexts): moderately complex texts, well-organized paragraphs, good control of complex grammar/spelling/punctuation.\n"
            . "• CLB 9 (Effective proficiency in workplace/community contexts): texts of some complexity, key ideas supported with relevant facts/details, control of a range of complex and diverse grammatical structures.\n"
            . "• CLB 10 (Highly effective proficiency in workplace/community contexts): key ideas supported with a range of facts/details/quotations, good control of a broad range of complex/diverse grammatical structures, connects ideas across paragraphs.\n"
            . "• CLB 12 (Advanced proficiency in workplace/community contexts): complex texts for a full range of purposes, relevant and sufficient facts/extended descriptions/quotations, very good control of a very broad range of complex/diverse grammatical structures.\n\n"
            . "Format your response exactly like this: start with 'Overall CLB Level: N' (or 'Overall CLB Level: M' if below CLB 3 / insufficient to assess) on its own first line, then give a CLB level for each of the 4 categories above, then detailed bullet-point feedback per category. Use the term 'CLB Level', never 'band'."
            . $wordCountNote;
    }
    if ($taskType === 'writing_task1') {
        return "You are an official IELTS General Training Writing Task 1 examiner. The candidate has written a letter in response to the prompt. Score the letter for:\n• Task Achievement (does it cover all bullet points and use the right tone/register?)\n• Coherence and Cohesion\n• Lexical Resource\n• Grammatical Range and Accuracy\nFormat your response exactly like this: start with 'Overall Band Score: X.X' (out of 9.0, in 0.5 increments) on its own first line, then give a band for each of the 4 criteria above, then 5–7 specific improvement suggestions. Note whether the letter is formal, semi-formal, or informal and whether the register matches what the task requires. Be accurate and strict like a real examiner. Use the term 'Band', never 'CLB' or 'level'."
            . $wordCountNote;
    }
    return "You are an official IELTS Writing Task 2 examiner. Score this essay for:\n• Task Response\n• Coherence and Cohesion\n• Lexical Resource\n• Grammatical Range and Accuracy\nFormat your response exactly like this: start with 'Overall Band Score: X.X' (out of 9.0, in 0.5 increments) on its own first line, then give a band for each of the 4 criteria above, then 5–7 specific improvement suggestions. Be accurate and strict like a real examiner. Use the term 'Band', never 'CLB' or 'level'."
        . $wordCountNote;
}

/**
 * Speaking rubric, by exam type. See essayRubric() above for the shared-file
 * rationale. CELPIP criteria/sub-factors and CLB anchors are transcribed from
 * the official "5 - Speaking Performance Standards.pdf" and
 * "10 - CELPIP Level Descriptors.pdf" (Speaking table).
 */
function speakingRubric(string $examType): string {
    if ($examType === 'CELPIP') {
        return "You are an official CELPIP Speaking examiner. Score this response using the real CELPIP Speaking Performance Standards, on the CLB (Canadian Language Benchmark) scale -- NOT an IELTS band. Assess these 4 official categories:\n"
            . "1. Content/Coherence — number and quality of ideas, organization, examples/supporting details.\n"
            . "2. Vocabulary — word choice, suitable/precise use of words and phrases, range.\n"
            . "3. Listenability — rhythm, pronunciation, and intonation; pauses/interjections/self-correction; grammar and sentence structure/variety.\n"
            . "4. Task Fulfillment — relevance, completeness, appropriate tone/register, whether the response is long enough.\n\n"
            . "Use these official CLB anchor points to calibrate your level (this is CELPIP's actual scoring language, distinct from IELTS bands):\n"
            . "• CLB 4 (Adequate proficiency for daily life activities): limited control of simple grammatical structures, some self-corrections/repetitions/inappropriate pauses.\n"
            . "• CLB 6 (Developing proficiency in workplace/community contexts): control of simple grammatical structures, usually speaks with understandable rhythm/pronunciation/intonation, some self-corrections.\n"
            . "• CLB 7 (Adequate proficiency in workplace/community contexts): good control of simple grammatical structures, speaks clearly with understandable rhythm/pronunciation/intonation, very few self-corrections.\n"
            . "• CLB 8 (Good proficiency in workplace/community contexts): some control of complex grammatical structures, consistently fluent rhythm/pronunciation/intonation.\n"
            . "• CLB 9 (Effective proficiency in workplace/community contexts): some control of complex grammatical structures, mostly fluent rhythm/pronunciation/intonation.\n"
            . "• CLB 10 (Highly effective proficiency in workplace/community contexts): good control of a broad range of complex grammatical structures, mostly fluent rhythm/pronunciation/intonation.\n"
            . "• CLB 12 (Advanced proficiency in workplace/community contexts): very good control of a very broad range of complex and diverse grammatical structures, fluent rhythm/pronunciation/intonation.\n\n"
            . "Format your response exactly like this: start with 'Overall CLB Level: N' (or 'Overall CLB Level: M' if below CLB 3 / insufficient to assess) on its own first line, then give a CLB level for each of the 4 categories above, then detailed feedback. Use the term 'CLB Level', never 'band'.";
    }
    return "You are an official IELTS Speaking examiner. Score this response for:\n• Fluency and Coherence\n• Lexical Resource\n• Grammatical Range and Accuracy\n• Pronunciation\nFormat your response exactly like this: start with 'Overall Band Score: X.X' (out of 9.0, in 0.5 increments) on its own first line, then give a band for each of the 4 criteria above, then 5–7 specific improvement suggestions. Use the term 'Band', never 'CLB' or 'level'.";
}

/**
 * Transcribe audio with OpenAI Whisper
 */
function transcribeWithWhisper($audioFilePath) {
    $ch = curl_init('https://api.openai.com/v1/audio/transcriptions');

    $postFields = [
        'file' => new CURLFile($audioFilePath, 'audio/webm', 'recording.webm'),
        'model' => 'whisper-1'
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postFields,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . OPENAI_API_KEY
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        $error = json_decode($response, true);
        return [
            'success' => false,
            'error' => $error['error']['message'] ?? 'Whisper API error'
        ];
    }

    $result = json_decode($response, true);
    return [
        'success' => true,
        'text' => $result['text']
    ];
}
