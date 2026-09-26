<?php
// Mock 1 Review — IELTS Academic — 2-Month Plan, Class 10, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>turn a mock result into a short list of things to fix</li><li>sort every lost mark into a cause, not just a question number</li><li>write three specific goals for the next fortnight</li></ul></div><h5>A result is data, not a verdict</h5>
<p>Your mock gave you a band for each skill. That number tells you where you are. The <strong>errors</strong> tell you what to do next. Do not skip this lesson: learners who review a mock properly improve much faster than learners who take more mocks.</p>
<h5>Step 1: build an error log</h5>
<p>For Listening and Reading, list every wrong or blank answer and give it one cause:</p>
<table><thead><tr><th>Cause</th><th>What it looks like</th><th>Fix</th></tr></thead><tbody><tr><td>Vocabulary</td><td>You did not know a word in the text or the question</td><td>Add it to your word list with a sentence</td></tr><tr><td>Misread the task</td><td>You wrote two words when the limit was one; you missed 'NOT'</td><td>Underline the instruction before you start</td></tr><tr><td>Ran out of time</td><td>The last 5-8 questions are blank or guessed</td><td>See the time-management lesson that follows</td></tr><tr><td>Spelling / grammar</td><td>Right idea, marked wrong: 'enviroment', 'child' for 'children'</td><td>Check singular/plural and spelling in every answer</td></tr><tr><td>Distractor</td><td>The speaker corrected themselves; you wrote the first answer</td><td>Listen to the end of each section of the recording</td></tr><tr><td>Inference too far</td><td>True/False/Not Given: you decided from what you think, not what the text says</td><td>Use only the text</td></tr></tbody></table>
<h5>Step 2: check Writing with the four criteria</h5>
<p>Reread each Writing task. Give yourself a tick or a cross for each of these, then mark the weakest criterion:</p>
<ul><li>Did I answer every part of the question (Task 1: a clear overview)?</li><li>Is each paragraph about one idea, with clear linking?</li><li>Did I use a range of vocabulary and avoid repeating the same word?</li><li>Did I use some complex sentences, and are most sentences free of errors?</li></ul>
<h5>Step 3: Speaking</h5>
<p>If you recorded yourself, listen once for <strong>fluency</strong> (pauses, repeating), once for <strong>vocabulary</strong> (did you say 'good' ten times?) and once for <strong>pronunciation</strong> (words you were unsure of).</p>
<h5>Step 4: three goals</h5>
<p>Write three goals that are specific and measurable. Not <em>Improve reading</em>, but <em>Do one True/False/Not Given set every day and reach 80% by Sunday</em>.</p>
<div class="lc-tip"><strong>Tip.</strong> Keep the error log going all course. Patterns show up after three or four entries.</div>
<div class="lc-take"><strong>Take away.</strong> Sort mistakes by cause, fix the cause, and turn the results into three specific goals.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 10, 1, ob_get_clean());
