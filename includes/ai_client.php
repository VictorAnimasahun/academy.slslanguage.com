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
