<?php
/**
 * Transcribe audio or video files to text (founder, 2026-10-28: "I need that skill for other audios and videos").
 * Command line, on the Mac (the browser version, for staff on the site, is transcribe.php):
 *
 *   php academy/tools/transcribe.php ~/Downloads/lecture.mp4 ~/Downloads/"part 2.mp3"
 *
 * For each file it writes <name>.transcript.txt next to the file and prints the text. How it works:
 *   1. ffmpeg (Homebrew) turns the file into a small mono MP3 of its soundtrack. This handles any audio or video
 *      format, files whose extension lies about their format (the Class 2 recordings were AAC named .mp3), and
 *      keeps the upload small (whole videos failed to upload).
 *   2. Groq's Whisper speech-to-text, through the academy's own client (includes/ai_client.php, the same key the
 *      platform uses for speaking recordings). Limit: about 25 MB per file after step 1, roughly 1 hour of speech.
 * Whisper writes names as it hears them ("Height" for Haidt): check names, numbers and spellings by ear.
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if ($argc < 2) { fwrite(STDERR, "Usage: php academy/tools/transcribe.php <audio-or-video> [more files]\n"); exit(1); }

$_SERVER['HTTP_HOST'] = 'localhost';
require dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/includes/ai_client.php';

$ffmpeg = is_executable('/opt/homebrew/bin/ffmpeg') ? '/opt/homebrew/bin/ffmpeg' : trim((string) shell_exec('command -v ffmpeg'));
if ($ffmpeg === '') { fwrite(STDERR, "ffmpeg not found (brew install ffmpeg)\n"); exit(1); }

foreach (array_slice($argv, 1) as $file) {
    if (!is_file($file)) { fwrite(STDERR, "Not found: $file\n"); continue; }
    $tmp = sys_get_temp_dir() . '/transcribe_' . bin2hex(random_bytes(6)) . '.mp3';
    exec(escapeshellarg($ffmpeg) . ' -loglevel error -y -i ' . escapeshellarg($file) . ' -vn -ac 1 -ar 16000 -b:a 48k ' . escapeshellarg($tmp), $o, $rc);
    if ($rc !== 0 || !is_file($tmp)) { fwrite(STDERR, "Could not read the sound from: $file\n"); continue; }
    $r = transcribeWithGroq($tmp);
    @unlink($tmp);
    if (empty($r['success'])) { fwrite(STDERR, "Transcription failed for $file: " . ($r['error'] ?? 'unknown error') . "\n"); continue; }
    $text = trim($r['text']);
    $out = preg_replace('/\.[^.\/]+$/', '', $file) . '.transcript.txt';
    file_put_contents($out, $text . "\n");
    echo "===== $file\n(saved to $out)\n\n$text\n\n";
}
