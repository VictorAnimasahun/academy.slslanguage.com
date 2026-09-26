<?php
// Writing — Summarize Written Text & Essay — PTE Academic Masterclass — 3 Months, Class 4, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>write a one-sentence summary of a passage in 5-75 words</li><li>write a 200-300 word essay with clear structure</li><li>meet the form requirements that the computer checks</li></ul></div><h5>Summarize Written Text (SWT)</h5>
<p>You read a passage of up to about 300 words and write <strong>one sentence</strong> of 5 to 75 words that summarises it. You have 10 minutes. It is scored on content, form, grammar and vocabulary.</p>
<ul><li><strong>One sentence only</strong>, ending with a single full stop. Use a semicolon or a linking word (<em>although, which, while</em>) to join ideas.</li><li>Include the main idea and two or three key supporting points.</li><li>Do not copy long strings from the passage; paraphrase where you can.</li><li>Aim for about 50-70 words. Check the word count under the box.</li></ul>
<div class="lc-ex"><div class="lc-ex-t">Pattern</div><p><em>Although [main topic] is [description], [main point 1], [main point 2], and [conclusion or result].</em></p></div>
<h5>Essay</h5>
<p>You write an essay of <strong>200 to 300 words</strong> in 20 minutes on a short prompt. It is scored on content, form, development and structure, grammar, vocabulary and spelling.</p>
<table><thead><tr><th>Paragraph</th><th>Content</th><th>Length</th></tr></thead><tbody><tr><td>Introduction</td><td>Paraphrase the question and state your view</td><td>30-40 words</td></tr><tr><td>Body 1</td><td>Main reason with an example</td><td>70-80 words</td></tr><tr><td>Body 2</td><td>Second reason or the opposing view, with an example</td><td>70-80 words</td></tr><tr><td>Conclusion</td><td>Restate your position</td><td>25-30 words</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Under 200 or over 300 words loses marks on form. Check the word counter before you submit, and leave two minutes for spelling.</div>
<h5>Timing plan</h5>
<p>Essay: 3 minutes planning, 14 minutes writing, 3 minutes checking. SWT: 2 minutes reading, 6 minutes writing, 2 minutes checking.</p>
<div class="lc-take"><strong>Take away.</strong> Summary: one sentence, right length, main ideas. Essay: four paragraphs, 200-300 words, checked for spelling.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 4, 1, ob_get_clean());
