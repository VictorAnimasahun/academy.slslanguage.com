<?php
// Advanced Reading — Speed & Complex Item Types — PTE Academic — 2-Month Plan, Class 11, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>read faster while keeping accuracy</li><li>handle complex Re-order Paragraphs and multi-answer items</li><li>budget time across the Reading part</li></ul></div><h5>Time in the Reading part</h5>
<p>Reading is short (about 30 minutes). The fill-in-the-blanks tasks are quick marks; Re-order Paragraphs and multi-answer questions are slower. Aim for about one minute for a Multiple Choice item and two to three minutes for each Re-order task.</p>
<h5>Ways to read faster</h5>
<ul><li>Read in phrases, not words. Practise with short news articles and a timer.</li><li>Skim the whole passage first, then answer.</li><li>In gap-fill, do the easy gaps first and return to the harder ones.</li></ul>
<h5>Complex items</h5>
<table><thead><tr><th>Item</th><th>Approach</th></tr></thead><tbody><tr><td>Re-order with five paragraphs</td><td>Find the first paragraph, then link pairs by pronouns and linking words</td></tr><tr><td>Multiple answers</td><td>Eliminate options that the text contradicts, then choose the ones it supports</td></tr><tr><td>Fill in blanks with a word box</td><td>Use each word once; match by grammar first, then meaning</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Speed without accuracy loses points. Keep a record of the item types you get wrong and slow down on those.</div>
<div class="lc-take"><strong>Take away.</strong> Skim first, take quick marks first, and use logic on the complex items.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_2Mo', 11, 1, ob_get_clean());
