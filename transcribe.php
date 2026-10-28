<?php
// Transcribe (founder, 2026-10-28: "Need a browser version" of tools/transcribe.php). Staff only: it spends the
// platform's Groq credit. The browser does what ffmpeg does in the command-line tool: it reads the sound from any audio
// or video file it can play, turns it into 16 kHz mono and cuts it into pieces of under a minute (about 1.8 MB each,
// cut at the quietest moment near each boundary so no word is split). Each piece is posted here and sent to Groq
// Whisper (includes/ai_client.php), and the text is joined in order. So the server needs no ffmpeg and no large uploads.
require_once __DIR__ . '/bootstrap.php';
require_once INCLUDES_PATH . '/admin_check.php';
require_once INCLUDES_PATH . '/ai_client.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: edu_hub_registration.php?message=Please+login');
    exit();
}
if (!is_staff_account()) { http_response_code(404); exit('Not found'); }
if (empty($_SESSION['transcribe_token'])) $_SESSION['transcribe_token'] = bin2hex(random_bytes(16));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $f = $_FILES['piece'] ?? null;
    if (!hash_equals($_SESSION['transcribe_token'], (string) ($_POST['token'] ?? ''))) {
        echo json_encode(['ok' => false, 'error' => 'Your session expired. Reload the page.']); exit();
    }
    if (!$f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 4 * 1024 * 1024) {
        echo json_encode(['ok' => false, 'error' => 'The piece did not upload (error ' . (int) ($f['error'] ?? -1) . ').']); exit();
    }
    $tmp = sys_get_temp_dir() . '/transcribe_' . bin2hex(random_bytes(6)) . '.wav';
    move_uploaded_file($f['tmp_name'], $tmp);
    $r = transcribeWithGroq($tmp);
    @unlink($tmp);
    echo json_encode(!empty($r['success']) ? ['ok' => true, 'text' => trim($r['text'])]
        : ['ok' => false, 'error' => $r['error'] ?? 'Transcription failed', 'busy' => stripos($r['error'] ?? '', 'rate limit') !== false]);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Transcribe - EduHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
        @media (min-width:1200px) { .content { max-width:calc(100vw - var(--sidebar-w) - var(--advert-w)); margin-top:0; } }
        .tr-title { padding-bottom:1rem; border-bottom:1px solid #d6dde6; margin-bottom:1.25rem; }
        .tr-title h1 { font-size:2.4rem; font-weight:600; margin:0 0 .35rem; color:#1f2937; }
        .tr-title p { margin:0; color:#4b5563; }
        .tr-drop { display:block; border:1px dashed #9aa5b4; border-radius:3px; background:#f7f9fb; padding:2rem 1rem; text-align:center; cursor:pointer; color:#374151; }
        .tr-drop.over { border-color:#0b77ff; background:#eef5ff; }
        .tr-drop i { font-size:2rem; color:#0b77ff; display:block; margin-bottom:.4rem; }
        .tr-drop input { display:none; }
        .tr-file { border:1px solid #d6dde6; border-radius:3px; margin-top:1rem; background:#fff; }
        .tr-file header { display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; border-bottom:1px solid #d6dde6; flex-wrap:wrap; }
        .tr-file header strong { flex:1 1 220px; min-width:0; overflow-wrap:anywhere; color:#1f2937; }
        .tr-status { font-size:.93rem; color:#4b5563; }
        .tr-status.err { color:#b42318; }
        .tr-bar { height:4px; background:#e5e7eb; }
        .tr-bar span { display:block; height:100%; width:0; background:#0b77ff; transition:width .3s; }
        .tr-file textarea { display:block; width:100%; min-height:220px; border:0; padding:1rem; font-size:1rem; line-height:1.6; resize:vertical; background:transparent; color:#1f2937; }
        .tr-btn { border:1px solid #c7cdd5; border-radius:3px; background:#fff; color:#1f2937; padding:.35rem .8rem; font-size:.93rem; }
        .tr-btn:hover { background:#e8edf3; }
        .tr-note { margin-top:1rem; font-size:.93rem; color:#4b5563; }
        body.dark .tr-title h1, body.dark .tr-file header strong, body.dark .tr-file textarea, body.dark .tr-drop { color:#e5e7eb; }
        body.dark .tr-title p, body.dark .tr-status, body.dark .tr-note { color:#9ca3af; }
        body.dark .tr-drop { background:#181818; border-color:#4b5563; }
        body.dark .tr-file, body.dark .tr-btn { background:#1e1e1e; border-color:#374151; color:#e5e7eb; }
        body.dark .tr-file header, body.dark .tr-title { border-color:#374151; }
        body.dark .tr-bar { background:#374151; }
    </style>
</head>
<body class="light">
<?php include INCLUDES_PATH . '/mobile_header.php'; ?>
<div class="d-flex">
    <?php include INCLUDES_PATH . '/navbar.php'; ?>
    <div class="main-wrapper flex-grow-1">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>
        <main class="content p-4">
            <div class="tr-title">
                <h1>Transcribe</h1>
                <p>Turn a recording or video into text. Staff only.</p>
            </div>
            <label class="tr-drop" id="trDrop">
                <i class="bi bi-file-earmark-music"></i>
                <strong>Choose files</strong> or drop them here: any audio or video your browser can play (mp3, m4a, wav, mp4, mov...)
                <input type="file" id="trInput" accept="audio/*,video/*" multiple>
            </label>
            <div id="trFiles"></div>
            <p class="tr-note">Check names, numbers and spellings by ear: Whisper writes what it hears ("Height" for "Haidt"). Keep this tab open until each file says Done. Very long files (over about an hour) can be too large for the browser to read in one go; split them first.</p>
        </main>
    </div>
</div>

<?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
<?php include INCLUDES_PATH . '/footer.php'; ?>
<script>
(function () {
    const TOKEN = <?= json_encode($_SESSION['transcribe_token']) ?>;
    const RATE = 16000, PIECE = 55 * RATE, LOOK = 6 * RATE, WIN = RATE / 10;
    const drop = document.getElementById('trDrop'), input = document.getElementById('trInput'), list = document.getElementById('trFiles');
    let queue = Promise.resolve();

    input.addEventListener('change', () => { add(input.files); input.value = ''; });
    ['dragover', 'dragenter'].forEach(t => drop.addEventListener(t, e => { e.preventDefault(); drop.classList.add('over'); }));
    ['dragleave', 'drop'].forEach(t => drop.addEventListener(t, () => drop.classList.remove('over')));
    drop.addEventListener('drop', e => { e.preventDefault(); add(e.dataTransfer.files); });

    function add(files) {
        [...files].forEach(file => {
            const box = document.createElement('section');
            box.className = 'tr-file';
            box.innerHTML = '<header><strong></strong><span class="tr-status">Waiting</span>'
                + '<button type="button" class="tr-btn" data-copy hidden>Copy</button>'
                + '<button type="button" class="tr-btn" data-save hidden>Download .txt</button></header>'
                + '<div class="tr-bar"><span></span></div><textarea readonly placeholder="The text appears here as each piece is done."></textarea>';
            box.querySelector('strong').textContent = file.name;
            list.prepend(box);
            queue = queue.then(() => run(file, box));   // one file at a time, to stay inside Groq's rate limit
        });
    }

    async function run(file, box) {
        const status = box.querySelector('.tr-status'), bar = box.querySelector('.tr-bar span'), out = box.querySelector('textarea');
        const say = (t, err) => { status.textContent = t; status.classList.toggle('err', !!err); };
        let samples;
        try {
            say('Reading the sound...');
            const ctx = new OfflineAudioContext(1, RATE, RATE);   // decodes straight to 16 kHz
            const buf = await ctx.decodeAudioData(await file.arrayBuffer());
            samples = mono(buf);
        } catch (e) {
            return say('Could not read the sound from this file. Try converting it to mp3 first.', true);
        }
        const pieces = cut(samples), texts = [];
        for (let i = 0; i < pieces.length; i++) {
            say(`Transcribing piece ${i + 1} of ${pieces.length}...`);
            const r = await send(pieces[i], m => say(m));
            if (!r.ok) return say(`Stopped at piece ${i + 1} of ${pieces.length}: ${r.error}`, true);
            if (r.text) texts.push(r.text);
            out.value = texts.join(' ');
            bar.style.width = ((i + 1) / pieces.length * 100) + '%';
        }
        const mins = Math.round(samples.length / RATE / 60);
        say(`Done: ${mins < 1 ? 'under a minute' : mins + ' min'}, ${out.value.split(/\s+/).filter(Boolean).length} words.`);
        const copy = box.querySelector('[data-copy]'), save = box.querySelector('[data-save]');
        copy.hidden = save.hidden = false;
        copy.onclick = () => navigator.clipboard.writeText(out.value).then(() => { copy.textContent = 'Copied'; setTimeout(() => copy.textContent = 'Copy', 1500); });
        save.onclick = () => {
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([out.value + '\n'], { type: 'text/plain' }));
            a.download = file.name.replace(/\.[^.]+$/, '') + '.transcript.txt';
            a.click();
            setTimeout(() => URL.revokeObjectURL(a.href), 1000);
        };
    }

    function mono(buf) {
        if (buf.numberOfChannels === 1) return buf.getChannelData(0);
        const out = new Float32Array(buf.length), n = buf.numberOfChannels;
        for (let c = 0; c < n; c++) { const d = buf.getChannelData(c); for (let i = 0; i < d.length; i++) out[i] += d[i] / n; }
        return out;
    }

    // Pieces of up to 55 s, each ending at the quietest tenth of a second in its last 6 s.
    function cut(s) {
        const pieces = [];
        let start = 0;
        while (start < s.length) {
            let end = Math.min(start + PIECE, s.length);
            if (end < s.length) {
                let best = end, quiet = Infinity;
                for (let w = end - LOOK; w + WIN <= end; w += WIN / 2) {
                    let e = 0;
                    for (let i = w; i < w + WIN; i++) e += s[i] * s[i];
                    if (e < quiet) { quiet = e; best = w + WIN / 2; }
                }
                end = best;
            }
            pieces.push(s.subarray(start, end));
            start = end;
        }
        return pieces;
    }

    function wav(s) {
        const v = new DataView(new ArrayBuffer(44 + s.length * 2));
        const str = (o, t) => [...t].forEach((c, i) => v.setUint8(o + i, c.charCodeAt(0)));
        str(0, 'RIFF'); v.setUint32(4, 36 + s.length * 2, true); str(8, 'WAVEfmt ');
        v.setUint32(16, 16, true); v.setUint16(20, 1, true); v.setUint16(22, 1, true);
        v.setUint32(24, RATE, true); v.setUint32(28, RATE * 2, true); v.setUint16(32, 2, true); v.setUint16(34, 16, true);
        str(36, 'data'); v.setUint32(40, s.length * 2, true);
        for (let i = 0; i < s.length; i++) { const x = Math.max(-1, Math.min(1, s[i])); v.setInt16(44 + i * 2, x < 0 ? x * 0x8000 : x * 0x7fff, true); }
        return new Blob([v], { type: 'audio/wav' });
    }

    // Posts one piece; if Groq says it is busy (rate limit), waits and tries again, up to 6 times.
    async function send(piece, say) {
        for (let attempt = 0; attempt < 6; attempt++) {
            const fd = new FormData();
            fd.append('token', TOKEN);
            fd.append('piece', wav(piece), 'piece.wav');
            let r;
            try {
                const res = await fetch('transcribe.php', { method: 'POST', body: fd, credentials: 'same-origin' });
                r = await res.json();
            } catch (e) {
                r = { ok: false, error: 'The upload failed (connection or server limit).', busy: true };
            }
            if (r.ok || !r.busy) return r;
            say('Groq is busy, waiting 20 seconds...');
            await new Promise(ok => setTimeout(ok, 20000));
        }
        return { ok: false, error: 'Groq stayed busy. Try again in a few minutes.' };
    }
})();
</script>
</body>
</html>
