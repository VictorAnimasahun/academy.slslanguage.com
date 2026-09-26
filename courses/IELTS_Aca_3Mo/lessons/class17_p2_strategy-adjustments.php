<?php
// Strategy Adjustments — IELTS Academic Masterclass — 3 Months, Class 17, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>turn the mock review into specific changes to your test plan</li><li>set time limits for each part of the test</li><li>decide what you will do when you are stuck</li></ul></div><h5>From review to rules</h5>
<p>Write three rules for yourself, based on your mock. Rules are short and start with a verb.</p>
<div class="lc-ex"><div class="lc-ex-t">Example rules</div><p>1. In Reading, leave any question after one minute and come back.</p><p>2. In Task 2, write a two-minute plan before the first sentence.</p><p>3. In Speaking Part 2, use all sixty seconds to write four keywords.</p></div>
<h5>A time budget you can rehearse</h5>
<table><thead><tr><th>Part</th><th>Budget</th></tr></thead><tbody><tr><td>Listening</td><td>use the prep time to underline keywords; check spelling at the end</td></tr><tr><td>Reading</td><td>20-20-20; blank answers not allowed</td></tr><tr><td>Writing</td><td>Task 1: 20 (5 plan+write, 3 check); Task 2: 40 (5 plan, 30 write, 5 check)</td></tr><tr><td>Speaking</td><td>aim for full-length answers in every part</td></tr></tbody></table>
<h5>When you are stuck</h5>
<ul><li>Listening: write a guess and move on; missing the next answer costs more.</li><li>Reading: skip and return; guess at the end.</li><li>Writing: if you have no idea, take a clear position and use the ideas you can explain best.</li><li>Speaking: rephrase the question to buy time, or say 'Let me think for a second'.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Turn your mock lessons into three rules and a time budget, and rehearse them in your next practice.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 17, 2, ob_get_clean());
