<?php
// Speaking Part 2 Drilling — IELTS Academic — 2-Month Plan, Class 11, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>use the one minute of preparation to plan four points</li><li>speak for a full one to two minutes without a long silence</li><li>use tenses and linking words that show range</li></ul></div><h5>What happens in Part 2</h5>
<p>The examiner gives you a task card with a topic and four prompts. You have <strong>one minute</strong> to prepare (you may write notes) and then speak for <strong>one to two minutes</strong>. The examiner may ask one or two short questions after.</p>
<div class="lc-ex"><div class="lc-ex-t">A task card</div><p><strong>Describe a book that you enjoyed reading.</strong></p><p>You should say: what the book was, who wrote it, what it was about, and explain why you enjoyed it.</p></div>
<h5>Your one-minute plan</h5>
<p>Do not write sentences. Write <strong>one keyword for each prompt</strong>, plus one extra detail or example for each.</p>
<table><thead><tr><th>Prompt</th><th>Keyword</th><th>Extra detail</th></tr></thead><tbody><tr><td>What the book was</td><td>'The Alchemist'</td><td>read at 17, a gift</td></tr><tr><td>Who wrote it</td><td>Paulo Coelho</td><td>Brazilian; my sister told me</td></tr><tr><td>What it was about</td><td>shepherd, dreams</td><td>travel to Egypt</td></tr><tr><td>Why I enjoyed it</td><td>hope, simple</td><td>reread it after exams</td></tr></tbody></table>
<h5>How to fill two minutes</h5>
<ul><li>Answer each prompt with <strong>two or three sentences</strong>, not one.</li><li>Give one <strong>example or short story</strong>: a real moment is easier to talk about than an opinion.</li><li>Use a mixture of tenses: past (what happened), present (what it means to you now), and, if you can, future or conditional (<em>I'd love to read it again</em>).</li><li>If you run out of ideas, go back to the last prompt: <em>Why did I enjoy it? Because ...</em></li></ul>
<h5>Linking phrases that help you keep going</h5>
<p><em>To begin with ... / What I remember most is ... / The reason for that is ... / On top of that ... / Looking back ... / To sum up ...</em></p>
<div class="lc-warn"><strong>Watch out.</strong> Do not memorise a whole answer. Examiners recognise a rehearsed answer and it can lower your fluency score. Memorise <strong>structures and phrases</strong>, not stories.</div>
<h5>Drill routine (15 minutes, 4 days a week)</h5>
<ol><li>Pick a random topic (a person, a place, an object, an event).</li><li>Take exactly one minute to plan.</li><li>Speak for two minutes, recorded on your phone.</li><li>Listen back once: mark where you paused or repeated a word.</li><li>Redo it once, fixing only those points.</li></ol>
<div class="lc-take"><strong>Take away.</strong> Plan four keywords, tell a small story, mix your tenses, and never stop for more than two or three seconds. Two minutes is longer than you think.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 11, 2, ob_get_clean());
