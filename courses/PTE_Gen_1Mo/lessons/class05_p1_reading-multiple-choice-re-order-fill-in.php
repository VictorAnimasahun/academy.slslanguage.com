<?php
// Reading — Multiple Choice, Re-order & Fill in Blanks — PTE Academic — 1-Month Plan, Class 5, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>recognise the five Reading task types and how each is scored</li><li>re-order paragraphs by logic and linking words</li><li>choose the right word in fill-in-the-blank tasks by grammar and collocation</li></ul></div><h5>The five task types</h5>
<table><thead><tr><th>Task</th><th>What you do</th><th>Scoring</th></tr></thead><tbody><tr><td>Reading &amp; Writing: Fill in the Blanks</td><td>Choose a word from a drop-down list for each gap in a text</td><td>1 mark per correct gap</td></tr><tr><td>Multiple Choice, multiple answers</td><td>Choose all correct answers</td><td>Marks for correct, penalty for wrong</td></tr><tr><td>Re-order Paragraphs</td><td>Drag paragraphs into the right order</td><td>Marks for each correct adjacent pair</td></tr><tr><td>Reading: Fill in the Blanks</td><td>Drag words from a box into the gaps</td><td>1 mark per correct gap</td></tr><tr><td>Multiple Choice, single answer</td><td>Choose one answer</td><td>1 mark</td></tr></tbody></table>
<h5>Re-order Paragraphs</h5>
<ol><li>Find the <strong>opening sentence</strong>: it introduces the topic and has no reference back (<em>this, they, however</em>).</li><li>Look for <strong>pronouns and linking words</strong> that connect to the sentence before.</li><li>Look for chronology (dates), cause and effect, and a general-to-specific order.</li><li>Build pairs first, then fit the pairs together.</li></ol>
<h5>Fill in the Blanks</h5>
<ul><li>Decide the part of speech the gap needs before you look at the options.</li><li>Check <strong>collocation</strong> (which words go together) and grammar (singular/plural, tense).</li><li>Read the sentence with your answer in it before you move on.</li></ul>
<div class="lc-warn"><strong>Watch out.</strong> In multiple-answer questions, wrong answers cost points. Choose only the answers you can support from the text.</div>
<div class="lc-take"><strong>Take away.</strong> Learn the five formats, use linking words for re-ordering, and choose blanks by grammar and collocation.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_1Mo', 5, 1, ob_get_clean());
