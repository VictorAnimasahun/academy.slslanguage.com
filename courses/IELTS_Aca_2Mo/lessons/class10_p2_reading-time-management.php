<?php
// Reading Time-Management — IELTS Academic — 2-Month Plan, Class 10, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>divide the 60 minutes so all 40 questions get an attempt</li><li>skim and scan efficiently</li><li>decide when to move on and when to guess</li></ul></div><h5>The 60-minute problem</h5>
<p>Reading has three passages, 40 questions and <strong>no extra time to transfer answers</strong>. Most lost marks in Reading come from time, not knowledge. The aim is 20 minutes per passage, and slightly less on Passage 1 so you can spend a little more on Passage 3.</p>
<h5>Three tools</h5>
<ol><li><strong>Skim</strong> (about one minute): read the title, the first sentence of each paragraph and the last sentence of the passage to see the shape of the text.</li><li><strong>Scan</strong>: look for a specific name, number, date or key word without reading everything.</li><li><strong>Read closely</strong>: only the paragraph the question points to.</li></ol>
<h5>Plan for each passage</h5>
<table><thead><tr><th>Minutes</th><th>What to do</th></tr></thead><tbody><tr><td>0-1</td><td>Skim the passage for its structure</td></tr><tr><td>1-2</td><td>Read the instructions and questions of the first group</td></tr><tr><td>2-15</td><td>Answer in the order of the questions, scanning the text for keywords</td></tr><tr><td>15-19</td><td>Second group of questions</td></tr><tr><td>19-20</td><td>Check spelling and word limits; transfer is direct in Reading, so write final answers as you go</td></tr></tbody></table>
<h5>Order of attack</h5>
<ul><li>Questions with a keyword you can find quickly (names, numbers, dates) are quick marks; do them first.</li><li><strong>Matching headings</strong> need the whole passage; do them after you have skimmed it.</li><li><strong>True/False/Not Given</strong> and Yes/No/Not Given need close reading; leave them until you have the quick marks.</li></ul>
<div class="lc-warn"><strong>Watch out.</strong> Never leave a question blank. There is no penalty for a wrong answer. If you are stuck for more than a minute, guess and mark the question to come back to.</div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Practise with a timer this week: one passage, 17 minutes, no dictionary.</p><ol><li>Note the time you finish the first question group.</li><li>Mark any question you guessed.</li><li>After the timer, look at how many of the guesses were right.</li></ol></div>
<div class="lc-take"><strong>Take away.</strong> Three passages, twenty minutes each. Skim first, answer the quick marks first, and never leave a blank.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 10, 2, ob_get_clean());
