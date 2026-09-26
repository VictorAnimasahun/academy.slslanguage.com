<?php
// AI Scoring Strategies & Test-Day Preparation — PTE Academic Masterclass — 2 Months, Class 7, piece 1 (lesson).
// EMPTY SHELL. To develop this lesson, put its HTML between the two LESSON BODY markers below.
// Everything around it (login, plan check, header, back link) comes from includes/lesson_shell.php.
require_once dirname(__DIR__, 3) . '/bootstrap.php';
require_once INCLUDES_PATH . '/lesson_shell.php';

ob_start(); ?>
<!-- LESSON BODY START -->
<!-- DRAFT: written by Claude on 2026-09-26 for the instructor to review and edit before students see it. Not yet checked. -->
<style>
.lc { font-size:.97rem; line-height:1.75; color:#1f2937; }
.lc h5 { margin:1.6rem 0 .5rem; font-weight:700; color:#0b4fb3; }
.lc .lc-aim { border-left:4px solid #0b77ff; background:#f0f7ff; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.25rem; }
.lc .lc-aim strong { display:block; margin-bottom:.35rem; }
.lc .lc-aim ul { margin:0; padding-left:1.1rem; }
.lc .lc-tip { border-left:4px solid #16a34a; background:#f0fdf4; border-radius:8px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc .lc-warn { border-left:4px solid #dc2626; background:#fef2f2; border-radius:8px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc .lc-ex { border:1px solid #e5e7eb; background:#fafafa; border-radius:8px; padding:.9rem 1.1rem; margin:1rem 0; }
.lc .lc-ex .lc-ex-t { font-size:.75rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#6b7280; margin-bottom:.35rem; }
.lc table { width:100%; border-collapse:collapse; margin:.75rem 0 1rem; font-size:.92rem; }
.lc th { background:#eef2ff; text-align:left; padding:.5rem .7rem; border:1px solid #dbe1f0; }
.lc td { padding:.45rem .7rem; border:1px solid #e5e7eb; vertical-align:top; }
.lc .lc-try { border:2px solid #0b77ff; border-radius:10px; padding:1rem 1.25rem; margin:1.4rem 0; }
.lc .lc-try .lc-try-t { font-weight:700; color:#0b77ff; margin-bottom:.4rem; }
.lc details { margin-top:.6rem; } .lc summary { cursor:pointer; font-weight:600; color:#0b4fb3; }
.lc .lc-take { background:#111827; color:#f9fafb; border-radius:10px; padding:1rem 1.25rem; margin-top:1.6rem; }
.lc .lc-take strong { color:#93c5fd; }
</style>
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>explain how the computer scores each PTE task</li><li>use that knowledge to choose habits that raise your score</li><li>prepare for the test-day process at the centre</li></ul></div><h5>How scoring works</h5>
<p>PTE is scored by computer. One answer can count towards several skills, so a habit like <em>speak in smooth phrases</em> improves Speaking, oral fluency and pronunciation together. The report shows a score from 10 to 90 for Speaking, Writing, Reading and Listening and for six enabling skills: grammar, oral fluency, pronunciation, spelling, vocabulary and written discourse.</p>
<h5>Strategies that follow from that</h5>
<table><thead><tr><th>The computer measures</th><th>So you should</th></tr></thead><tbody><tr><td>Content words spoken in the right order</td><td>Repeat and re-tell using the key nouns and verbs you hear</td></tr><tr><td>Oral fluency: rhythm, few hesitations</td><td>Speak in phrases with short pauses; do not restart after a mistake</td></tr><tr><td>Pronunciation: clear sounds and stress</td><td>Stress content words and say word endings</td></tr><tr><td>Length and form in writing</td><td>Keep to 5-75 words (one sentence) for SWT and 200-300 words for the essay</td></tr><tr><td>Spelling</td><td>Check every word; keep one spelling style (British or American)</td></tr><tr><td>Written discourse</td><td>One idea per paragraph with clear links</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> The computer does not reward memorised templates that ignore the question. Use a frame, then fill it with the actual content.</div>
<h5>Test-day preparation</h5>
<ul><li>Book your test and check the ID rules of your test centre, since you need the ID that matches your booking.</li><li>Expect a check-in with a photo, a palm-vein or fingerprint scan and a signature, done by the centre.</li><li>Practise wearing the headset and speaking at a steady, moderate volume; the microphone picks up other candidates when you are too quiet.</li><li>You get a noteboard: practise your note-taking format (topic, main points, numbers).</li><li>Do not stop between tasks. If a task goes badly, forget it and start the next one fresh.</li></ul>
<h5>The last day</h5>
<p>Review your error list and speak aloud for ten minutes. Do not try a full simulation. Sleep well.</p>
<div class="lc-take"><strong>Take away.</strong> Know what the AI measures, build the habits that raise several scores at once, and arrive prepared for the centre routine.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_2Mo', 7, 1, ob_get_clean());
