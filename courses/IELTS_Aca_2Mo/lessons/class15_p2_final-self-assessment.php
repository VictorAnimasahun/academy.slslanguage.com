<?php
// Final Self-Assessment — IELTS Academic — 2-Month Plan, Class 15, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>judge your level in each skill honestly</li><li>name the three things that will most raise your score</li><li>build a simple plan for the final days before the test</li></ul></div><h5>Rate yourself against the descriptors</h5>
<p>For each row, give yourself a score from 1 (rarely) to 4 (almost always). Be strict.</p>
<table><thead><tr><th>Skill</th><th>Question</th><th>1-4</th></tr></thead><tbody><tr><td>Listening</td><td>I can follow a whole lecture and write answers without missing the next question</td><td></td></tr><tr><td>Listening</td><td>I check spelling and singular/plural in every answer</td><td></td></tr><tr><td>Reading</td><td>I finish all 40 questions in 60 minutes</td><td></td></tr><tr><td>Reading</td><td>I get True/False/Not Given right most of the time</td><td></td></tr><tr><td>Writing 1</td><td>I always write a clear overview</td><td></td></tr><tr><td>Writing 2</td><td>Each paragraph has one main idea with an example</td><td></td></tr><tr><td>Speaking</td><td>I can speak for two minutes on Part 2 without long pauses</td><td></td></tr><tr><td>Speaking</td><td>I use a range of vocabulary, not the same few words</td><td></td></tr></tbody></table>
<h5>Choose three priorities</h5>
<p>Look at your lowest scores. Choose the <strong>three</strong> that are also worth the most: a Task 2 problem is worth twice a Task 1 problem; a time problem in Reading affects 10 questions at once.</p>
<h5>The last week</h5>
<ul><li>Do not learn new material. Review your error log and your word list.</li><li>Take one full timed test and one timed Writing task, and mark them carefully.</li><li>Practise Speaking aloud every day, even for five minutes.</li><li>Sleep, eat and travel plans: know where the test is and how long it takes to get there.</li></ul>
<div class="lc-take"><strong>Take away.</strong> An honest audit beats a hopeful guess. Fix the three biggest gaps, revise instead of cramming, and arrive rested.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 15, 2, ob_get_clean());
