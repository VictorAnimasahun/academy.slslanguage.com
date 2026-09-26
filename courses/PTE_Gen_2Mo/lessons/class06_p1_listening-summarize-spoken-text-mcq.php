<?php
// Listening — Summarize Spoken Text & MCQ — PTE Academic — 2-Month Plan, Class 6, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>write a 50-70 word summary of a spoken talk</li><li>take notes that help, not slow you down</li><li>answer multiple-choice listening tasks accurately</li></ul></div><h5>Summarize Spoken Text (SST)</h5>
<p>You hear a recording of about 60-90 seconds and write a summary of <strong>50 to 70 words</strong> in 10 minutes. It is scored on content, form, grammar, vocabulary and spelling.</p>
<h5>Note-taking</h5>
<ul><li>Write <strong>keywords only</strong>: nouns, numbers and names. Never full sentences.</li><li>Use short forms (&amp;, &gt;, -&gt;, ↑, ↓).</li><li>Split your page: <em>topic</em>, <em>main points</em>, <em>conclusion</em>.</li></ul>
<h5>Writing the summary</h5>
<ol><li>Sentence 1: topic and speaker's main idea.</li><li>Sentences 2-3: two or three key points.</li><li>Sentence 4: conclusion or result.</li></ol>
<div class="lc-tip"><strong>Tip.</strong> Stay inside 50-70 words. Under 40 or over 100 can score zero for form.</div>
<h5>Multiple Choice in Listening</h5>
<ul><li>Read the question before the recording starts, and mark the key words.</li><li>In multiple-answer tasks, choose only what the speaker clearly says; wrong choices lose marks.</li><li>Distractors use words from the talk but change the meaning. Match the meaning, not the words.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Take keyword notes, write 50-70 words in a clear order, and choose answers by meaning.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_2Mo', 6, 1, ob_get_clean());
