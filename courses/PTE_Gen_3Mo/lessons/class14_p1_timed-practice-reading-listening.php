<?php
// Timed Practice — Reading & Listening — PTE Academic Masterclass — 3 Months, Class 14, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>do Reading and Listening tasks under real timings</li><li>learn to move on when a task takes too long</li><li>record results so you can see progress</li></ul></div><h5>Work to the clock</h5>
<p>Use the timing of each task as it is in the test. When time runs out, the answer is submitted, so there is no time to think again.</p>
<table><thead><tr><th>Task</th><th>Time guide</th></tr></thead><tbody><tr><td>Reading Fill in the Blanks</td><td>about 2 minutes</td></tr><tr><td>Re-order Paragraphs</td><td>about 2-3 minutes</td></tr><tr><td>Multiple Choice</td><td>about 1-2 minutes</td></tr><tr><td>Summarize Spoken Text</td><td>10 minutes</td></tr><tr><td>Write From Dictation</td><td>one sentence, type straight after</td></tr></tbody></table>
<h5>Session plan (60 minutes)</h5>
<ol><li>20 minutes: Reading tasks, timed.</li><li>20 minutes: Listening tasks, timed.</li><li>20 minutes: mark, and log errors by task type.</li></ol>
<div class="lc-take"><strong>Take away.</strong> Practise under the real clock and log your errors.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 14, 1, ob_get_clean());
