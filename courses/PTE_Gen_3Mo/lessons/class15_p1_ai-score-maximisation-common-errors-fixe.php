<?php
// AI Score Maximisation — Common Errors & Fixes — PTE Academic Masterclass — 3 Months, Class 15, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>recognise the errors that lose the most marks in each PTE task</li><li>fix them with specific habits</li><li>use your practice scores to choose what to fix first</li></ul></div><h5>Common errors by task</h5>
<table><thead><tr><th>Task</th><th>Common error</th><th>Fix</th></tr></thead><tbody><tr><td>Read Aloud</td><td>Reading in a flat voice or with long pauses</td><td>Read in phrases; mark pauses in preparation time</td></tr><tr><td>Repeat Sentence</td><td>Trying to remember every word</td><td>Remember chunks of meaning</td></tr><tr><td>Describe Image</td><td>Not using the whole 40 seconds; missing numbers</td><td>Use the frame; state highest and lowest values</td></tr><tr><td>Re-tell Lecture</td><td>Missing keywords</td><td>Write five nouns and numbers while listening</td></tr><tr><td>SWT</td><td>More than one sentence</td><td>Use one sentence with links</td></tr><tr><td>Essay</td><td>Below 200 or above 300 words; spelling errors</td><td>Plan and check the word counter</td></tr><tr><td>Listening dictation</td><td>Spelling and word endings</td><td>Check each word ending</td></tr><tr><td>Multiple answer</td><td>Guessing all options</td><td>Choose only what the text supports</td></tr></tbody></table>
<h5>Find your biggest loss</h5>
<p>Look at your last three practice results. List the task types where you lost the most marks. Fix the <strong>most frequent</strong> error first.</p>
<div class="lc-take"><strong>Take away.</strong> Match each error to a specific habit and fix the most frequent one first.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 15, 1, ob_get_clean());
