<?php
// Consolidation — Targeted Weak-Area Drilling — IELTS Academic Masterclass — 3 Months, Class 22, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>choose your weak areas from evidence, not feeling</li><li>build a focused 90-minute practice session</li><li>measure whether the drilling worked</li></ul></div><h5>Find the weak areas</h5>
<p>Use your error log and your last two mocks. List your three lowest-scoring areas, for example <em>Matching headings</em>, <em>Task 1 overview</em>, <em>Speaking fluency</em>. Then rank them by how many marks they cost.</p>
<h5>A 90-minute session</h5>
<table><thead><tr><th>Minutes</th><th>Activity</th></tr></thead><tbody><tr><td>0-10</td><td>Warm up: review your error log entries for the target area</td></tr><tr><td>10-40</td><td>Focused drill: 10-15 questions or one short task on the weak area only</td></tr><tr><td>40-60</td><td>Check every answer; write why each error happened</td></tr><tr><td>60-80</td><td>Repeat with a fresh set</td></tr><tr><td>80-90</td><td>Write what you will do differently next time</td></tr></tbody></table>
<h5>Measure it</h5>
<ul><li>Set a target before you start (for example 8/10 correct).</li><li>Record the score of every session in a table.</li><li>If two sessions do not move the score, change the method: get an explanation, then try again.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Drill what the data says is weak, in short focused sessions, and check the score to prove it is working.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 22, 1, ob_get_clean());
