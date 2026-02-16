<?php
// gemini_audio_test.php
// Simple test to transcribe audio using Gemini API

require_once dirname(__DIR__) . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . ACADEMY_URL . "edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// REPLACE WITH YOUR ACTUAL API KEY
$GEMINI_API_KEY = 'AIzaSyBs_jc7z0QZzQdwo41FEQk4myoc2nP1Soc';

// Path to your audio file (supports: mp3, wav, webm, etc.)
$audioFile = 'test_audio.mp3'; // CHANGE THIS to your audio file path

if (!file_exists($audioFile)) {
    die("Error: Audio file '$audioFile' not found.\n");
}

echo "Reading audio file...\n";
$audioData = file_get_contents($audioFile);
$audioBase64 = base64_encode($audioData);

// Detect mime type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $audioFile);
finfo_close($finfo);

echo "File type: $mimeType\n";
echo "Sending to Gemini API...\n\n";

// Call Gemini API
$url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $GEMINI_API_KEY;

$data = [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                [
                    'text' => 'Transcribe this audio accurately. Only output the transcription, nothing else.'
                ],
                [
                    'inline_data' => [
                        'mime_type' => $mimeType,
                        'data' => $audioBase64
                    ]
                ]
            ]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => ['Content-Type: application/json']
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    die("CURL Error: $curlError\n");
}

if ($httpCode !== 200) {
    echo "Error (HTTP $httpCode):\n";
    echo $response . "\n";
    exit(1);
}

$result = json_decode($response, true);

if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    echo "=== TRANSCRIPTION ===\n";
    echo $result['candidates'][0]['content']['parts'][0]['text'] . "\n";
} else {
    echo "Unexpected response:\n";
    print_r($result);
}
?>