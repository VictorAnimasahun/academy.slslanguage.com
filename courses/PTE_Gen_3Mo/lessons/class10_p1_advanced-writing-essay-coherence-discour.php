<?php
// Advanced Writing — Essay Coherence & Discourse Markers — PTE Academic Masterclass — 3 Months, Class 10, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>organise an essay so each paragraph has one job</li><li>use discourse markers accurately</li><li>raise the written-discourse score</li></ul></div><h5>Coherence</h5>
<p><strong>Written discourse</strong> is scored on how well the ideas connect: a clear position, one main idea per paragraph, and links between sentences.</p>
<h5>Discourse markers</h5>
<table><thead><tr><th>To...</th><th>Use</th></tr></thead><tbody><tr><td>Add</td><td>In addition, Moreover, Furthermore</td></tr><tr><td>Contrast</td><td>However, On the other hand, Whereas</td></tr><tr><td>Give a reason</td><td>Because, Since, Due to</td></tr><tr><td>Give a result</td><td>Therefore, As a result, Consequently</td></tr><tr><td>Give an example</td><td>For instance, For example, Such as</td></tr><tr><td>Conclude</td><td>In conclusion, To sum up, Overall</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Do not put a marker at the start of every sentence. Two or three per paragraph is enough.</div>
<div class="lc-ex"><div class="lc-ex-t">Paragraph pattern</div><p><strong>Claim:</strong> Online learning is more flexible. <strong>Reason:</strong> Students can study at any time. <strong>Example:</strong> For instance, working adults can attend lessons in the evening. <strong>Link:</strong> As a result, more people can continue their education.</p></div>
<div class="lc-take"><strong>Take away.</strong> One idea per paragraph, clear links, and markers used with care.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 10, 1, ob_get_clean());
