<?php
// AI Score Review & Final Targeted Practice — PTE Academic Masterclass — 3 Months, Class 23, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>read your score profile in the right order</li><li>choose the two task types that will raise your score most</li><li>build a simple daily plan for the last days</li></ul></div><h5>Read the profile</h5>
<p>In a PTE score report you see the four skills and the six enabling skills. Read it in this order:</p>
<ol><li>Find your <strong>lowest enabling skill</strong>: it often limits several main skills.</li><li>Find the lowest main skill, and the task types inside it.</li><li>Check where practice and test scores disagree: that is usually a timing or stress problem.</li></ol>
<table><thead><tr><th>If this is low</th><th>Practise</th></tr></thead><tbody><tr><td>Oral fluency</td><td>Shadowing and reading aloud in phrases</td></tr><tr><td>Pronunciation</td><td>Stress patterns, word endings</td></tr><tr><td>Spelling</td><td>Dictation drills, personal spelling list</td></tr><tr><td>Grammar</td><td>Error log from your essays and summaries</td></tr><tr><td>Vocabulary</td><td>Topic word lists, paraphrasing</td></tr><tr><td>Written discourse</td><td>Paragraph structure and linking</td></tr></tbody></table>
<h5>The last days</h5>
<ul><li>Two task types per day, 20 minutes each.</li><li>One timed part on alternate days.</li><li>The day before: light review, no new practice.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Fix the lowest enabling skill first, keep the plan short and daily, and rest before the test.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 23, 1, ob_get_clean());
