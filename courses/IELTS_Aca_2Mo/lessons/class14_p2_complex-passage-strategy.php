<?php
// Complex Passage Strategy — IELTS Academic — 2-Month Plan, Class 14, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>map a dense passage quickly, paragraph by paragraph</li><li>match headings and identify True/False/Not Given accurately</li><li>work out unknown words from context rather than stopping</li></ul></div><h5>Paragraph mapping</h5>
<p>For every long passage, spend a minute writing a <strong>five-word label</strong> beside each paragraph. It is a map: when a question names an idea, you know where to look.</p>
<div class="lc-ex"><div class="lc-ex-t">Example labels</div><p>Para A: history of the method. Para B: two main problems. Para C: a case study. Para D: expert criticism. Para E: future outlook.</p></div>
<h5>Matching headings</h5>
<ul><li>Read the <strong>first and last sentence</strong> of the paragraph; the main idea is usually there.</li><li>Cross out headings you have used.</li><li>Beware of headings that share a word with the paragraph but describe a different idea.</li></ul>
<h5>True / False / Not Given</h5>
<table><thead><tr><th>Answer</th><th>The rule</th></tr></thead><tbody><tr><td>True</td><td>The text says the same thing in other words</td></tr><tr><td>False</td><td>The text says the opposite</td></tr><tr><td>Not Given</td><td>The text does not say, however likely the statement seems</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Not Given is the biggest trap. If you must use your own knowledge to decide, it is probably Not Given.</div>
<h5>Unknown words</h5>
<p>You do not need to understand every word. Ask: is it a noun, verb or adjective? Is the tone positive or negative? Does the next sentence explain it? Move on if the question does not depend on it.</p>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Read the paragraph beginning of any reading passage and write a five-word label for each of the first three paragraphs. Compare with the text's own headings.</p></div>
<div class="lc-take"><strong>Take away.</strong> Label every paragraph, use topic sentences for headings, and judge True/False/Not Given only from the words on the page.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 14, 2, ob_get_clean());
