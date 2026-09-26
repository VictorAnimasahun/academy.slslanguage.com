<?php
// Mastery Writing — Band 90 Essay Structures — PTE Academic Masterclass — 3 Months, Class 18, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>use a structure that fits every prompt type</li><li>develop each paragraph with a reason and an example</li><li>check the form, structure and language points the computer scores</li></ul></div><h5>The four-paragraph structure</h5>
<table><thead><tr><th>Paragraph</th><th>Job</th><th>About</th></tr></thead><tbody><tr><td>Introduction</td><td>Paraphrase the prompt and state your position</td><td>35 words</td></tr><tr><td>Body 1</td><td>Reason 1 with explanation and example</td><td>75 words</td></tr><tr><td>Body 2</td><td>Reason 2 or the opposing view, with example</td><td>75 words</td></tr><tr><td>Conclusion</td><td>Restate your position and the main reasons</td><td>30 words</td></tr></tbody></table>
<h5>Fits every prompt</h5>
<ul><li><strong>Opinion</strong> (Do you agree?): position in the introduction, both bodies support it.</li><li><strong>Discuss both views:</strong> Body 1 = view A, Body 2 = view B, your position in the conclusion.</li><li><strong>Advantages and disadvantages:</strong> Body 1 = advantages, Body 2 = disadvantages.</li></ul>
<h5>What the computer checks</h5>
<table><thead><tr><th>Criterion</th><th>What to do</th></tr></thead><tbody><tr><td>Form</td><td>200-300 words; one essay; no bullet points</td></tr><tr><td>Development and structure</td><td>Clear paragraphs; each with a topic sentence</td></tr><tr><td>Grammar</td><td>A mix of simple and complex sentences, all correct</td></tr><tr><td>Vocabulary</td><td>Precise words; avoid repeating the same one</td></tr><tr><td>Spelling</td><td>One consistent spelling style; check every word</td></tr></tbody></table>
<div class="lc-tip"><strong>Tip.</strong> Keep a list of ten topic-specific words for common themes (education, technology, environment, health, work) so you are not searching for words in the test.</div>
<div class="lc-take"><strong>Take away.</strong> One structure, developed ideas, and a final check of length, grammar and spelling.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 18, 1, ob_get_clean());
