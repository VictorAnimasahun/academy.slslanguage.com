<?php
// Advanced Listening — IELTS Academic Masterclass — 3 Months, Class 18, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>handle Section 3 and Section 4 (academic discussion and lecture) with confidence</li><li>follow a discussion between several speakers</li><li>label maps and plans and complete flow-charts</li></ul></div><h5>What is harder in Sections 3 and 4</h5>
<ul><li>Section 3 has two to four speakers, often students and a tutor, and they <strong>disagree or change their minds</strong>.</li><li>Section 4 is a monologue with no break and academic vocabulary.</li><li>Answers are often a paraphrase, not the words you heard.</li></ul>
<h5>Multiple speakers</h5>
<p>Note who holds which opinion as you listen: draw a small table of the speakers' names with a word for each opinion. Questions often ask who said what.</p>
<h5>Labelling a map or plan</h5>
<p>Before the recording, look at the layout and find fixed points (entrance, river, north arrow). Learn direction words: <em>opposite, beside, next to, behind, on the left, to the north of</em>. The speaker will move around the plan in order.</p>
<h5>Flow-chart and note completion</h5>
<p>The gaps come in the order of the recording. Use the words before and after each gap to predict its type (noun, verb, number). Keep to the word limit.</p>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Practise this week with a Section 4 lecture. After each two minutes pause and write, in six words, the lecturer's main point so far.</p></div>
<div class="lc-take"><strong>Take away.</strong> Follow who says what, use the order of the recording, and expect paraphrase.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 18, 2, ob_get_clean());
