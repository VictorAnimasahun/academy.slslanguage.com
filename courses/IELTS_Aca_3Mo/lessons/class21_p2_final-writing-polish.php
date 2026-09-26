<?php
// Final Writing Polish — IELTS Academic Masterclass — 3 Months, Class 21, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>revise a Task 2 essay so that it moves up a band</li><li>edit for the four criteria in a systematic order</li><li>use the last three minutes of the test well</li></ul></div><h5>Polish in four passes</h5>
<ol><li><strong>Task Response:</strong> did I answer every part of the question, with a clear position throughout?</li><li><strong>Coherence and Cohesion:</strong> does each paragraph have one main idea, and is the link between ideas clear (but not mechanical)?</li><li><strong>Vocabulary:</strong> replace repeated words; check collocations (<em>make a decision</em>, not <em>do a decision</em>).</li><li><strong>Grammar:</strong> at least a few complex sentences, all correct; then the top three personal errors.</li></ol>
<h5>Upgrade one paragraph</h5>
<div class="lc-ex"><div class="lc-ex-t">Before and after</div><p><strong>Before:</strong> Technology is good. It helps people. It makes life easy.</p><p><strong>After:</strong> Technology has transformed daily life by saving time on routine tasks, which allows people to focus on more meaningful work.</p></div>
<h5>The last three minutes</h5>
<ul><li>Count your words (250+ for Task 2). Being 5 or 10 over is fine.</li><li>Check the introduction and conclusion first; examiners read them carefully.</li><li>Fix spelling and plurals, and make sure each sentence has a verb.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Revise in an order: response, coherence, vocabulary, grammar. Use the last minutes for the edit that is most likely to be needed.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 21, 2, ob_get_clean());
