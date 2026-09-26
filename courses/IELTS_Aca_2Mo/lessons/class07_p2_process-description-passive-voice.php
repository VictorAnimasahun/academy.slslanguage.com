<?php
// Process Description & Passive Voice — IELTS Academic — 2-Month Plan, Class 7, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>read a process diagram and decide whether it is linear or a cycle</li><li>use sequence words and the present passive to describe each stage</li><li>write an overview that names the number of stages and the start and end</li></ul></div><h5>Two kinds of process</h5>
<table><thead><tr><th>Type</th><th>What it looks like</th><th>How to start</th></tr></thead><tbody><tr><td>Linear</td><td>A start and an end (making cement, producing electricity)</td><td>There are eight stages, beginning with ... and ending with ...</td></tr><tr><td>Cycle</td><td>No real start; it repeats (water cycle, life cycle)</td><td>The diagram shows a cycle with five stages ...</td></tr></tbody></table>
<h5>Present passive for processes</h5>
<p>A process is described in the <strong>present passive</strong> because the action matters, not who does it: <em>The clay <strong>is heated</strong> and then <strong>is mixed</strong> with water.</em> Use the active only when a natural thing does the action: <em>The water <strong>evaporates</strong>.</em></p>
<h5>Sequence words</h5>
<table><thead><tr><th>Stage</th><th>Words</th></tr></thead><tbody><tr><td>Start</td><td>First, To begin with, The process begins when ...</td></tr><tr><td>Middle</td><td>Next, Then, After that, Following this, Once this has been done</td></tr><tr><td>Overlap</td><td>While this is happening, At the same time, Meanwhile</td></tr><tr><td>End</td><td>Finally, In the last stage, The process ends with ...</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Do not use <em>First, second, third</em> for every stage. It sounds like a list. Vary the words and combine two stages in one sentence with <em>which, after which, before</em>.</div>
<h5>Structure</h5>
<ol><li><strong>Introduction:</strong> what the diagram shows.</li><li><strong>Overview:</strong> the number of main stages and the start and end products.</li><li><strong>Detail:</strong> two paragraphs following the order of the diagram.</li></ol>
<div class="lc-ex"><div class="lc-ex-t">Model overview</div><p>Overall, the production of bricks involves six stages, from digging clay to delivering the finished bricks to customers.</p></div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Turn each into a passive sentence.</p><ol><li>Workers dig the clay from the ground.</li><li>A machine crushes the clay.</li><li>They fire the bricks in a kiln.</li></ol><details><summary>Show suggested answers</summary><ol><li>The clay is dug from the ground.</li><li>The clay is crushed by a machine (or: The clay is crushed).</li><li>The bricks are fired in a kiln.</li></ol></details></div>
<div class="lc-take"><strong>Take away.</strong> Decide linear or cycle, describe each stage in the present passive, vary your sequence words, and state the number of stages in your overview.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 7, 2, ob_get_clean());
