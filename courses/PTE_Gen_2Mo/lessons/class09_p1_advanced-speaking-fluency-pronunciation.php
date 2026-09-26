<?php
// Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score — PTE Academic — 2-Month Plan, Class 9, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>explain how oral fluency and pronunciation are scored</li><li>improve rhythm, stress and linking</li><li>find and fix your own weak sounds</li></ul></div><h5>What is scored</h5>
<table><thead><tr><th>Score</th><th>What it looks at</th></tr></thead><tbody><tr><td>Oral fluency</td><td>Smooth, natural speech: rhythm, phrasing, few hesitations, no repetition</td></tr><tr><td>Pronunciation</td><td>Clear individual sounds, word stress and sentence stress so a listener can understand you easily</td></tr></tbody></table>
<p>The computer does not mark accent. It marks how <em>easy you are to understand</em>.</p>
<h5>Fluency</h5>
<ul><li>Speak in <strong>phrases of three to six words</strong>, with short pauses between them.</li><li>Do not fill silence with <em>um</em> and <em>er</em>. Pause instead.</li><li>If you make a mistake, do not restart: keep going.</li></ul>
<h5>Stress and linking</h5>
<ul><li>Stress the important word in each phrase: <em>a big <strong>change</strong> in <strong>climate</strong></em>.</li><li>Link words: <em>an apple</em> sounds like <em>a-napple</em>.</li><li>Say the end of each word: <em>walked, boxes, months</em>.</li></ul>
<h5>Fix your weak sounds</h5>
<ol><li>Record 60 seconds of speaking.</li><li>Listen and write the words you were unsure of.</li><li>Look each up and listen to a native pronunciation.</li><li>Say each word ten times, then in a sentence.</li></ol>
<div class="lc-take"><strong>Take away.</strong> Phrases, pauses, stress and clear word endings. Fix your own weak words every day.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_2Mo', 9, 1, ob_get_clean());
