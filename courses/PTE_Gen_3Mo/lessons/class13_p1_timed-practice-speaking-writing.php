<?php
// Timed Practice — Speaking & Writing — PTE Academic Masterclass — 3 Months, Class 13, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>do Speaking and Writing tasks with the real preparation and answer times</li><li>record yourself and review</li><li>practise writing SWT and Essay under time</li></ul></div><h5>Speaking under time</h5>
<table><thead><tr><th>Task</th><th>Prepare</th><th>Speak</th></tr></thead><tbody><tr><td>Read Aloud</td><td>30-40 s</td><td>up to 40 s</td></tr><tr><td>Describe Image</td><td>25 s</td><td>40 s</td></tr><tr><td>Re-tell Lecture</td><td>10 s</td><td>40 s</td></tr></tbody></table>
<p>Use a timer. Record every answer and listen to it once.</p>
<h5>Writing under time</h5>
<ul><li>SWT: 10 minutes, one sentence, 5-75 words.</li><li>Essay: 20 minutes, 200-300 words.</li></ul>
<h5>Session plan</h5>
<ol><li>30 minutes Speaking: six tasks, recorded.</li><li>30 minutes Writing: one SWT and one essay.</li><li>Check word counts, spelling and structure.</li></ol>
<div class="lc-take"><strong>Take away.</strong> Real times, recorded speaking, and word counts checked.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 13, 1, ob_get_clean());
