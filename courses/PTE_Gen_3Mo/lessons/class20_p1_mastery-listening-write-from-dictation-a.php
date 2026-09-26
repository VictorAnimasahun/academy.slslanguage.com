<?php
// Mastery Listening — Write From Dictation & All Types — PTE Academic Masterclass — 3 Months, Class 20, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>reach high accuracy on Write From Dictation</li><li>switch quickly between all eight listening task types</li><li>keep spelling exact under pressure</li></ul></div><h5>Write From Dictation: the biggest single source of points</h5>
<p>You hear one sentence (about 3-5 seconds) and type it. Each correct word scores, so it is one of the most reliable tasks in the test, and it also feeds Writing and Listening. Around ten of these appear in the test.</p>
<ol><li><strong>Listen for chunks</strong>, not words. Group the sentence into two or three meaning units.</li><li><strong>Type while you still hear it</strong> in your head: first chunk, then the second.</li><li><strong>Spell every word</strong> and check word endings: <em>-s, -ed, -ing</em>.</li><li>Do not stop to fix one word: a missing word costs one mark, a missed sentence costs more.</li></ol>
<table><thead><tr><th>Often lost</th><th>Example</th><th>Check</th></tr></thead><tbody><tr><td>Plural</td><td>The students was ...</td><td>Match the verb</td></tr><tr><td>Past tense</td><td>She walk to school</td><td>Did I hear /t/ or /d/ at the end?</td></tr><tr><td>Small words</td><td>of, the, a, to</td><td>They are quiet but count</td></tr><tr><td>Homophones</td><td>their / there</td><td>Decide by grammar</td></tr></tbody></table>
<h5>All the types, mixed</h5>
<table><thead><tr><th>Task</th><th>Key habit</th></tr></thead><tbody><tr><td>Summarize Spoken Text</td><td>Keywords only; 50-70 words</td></tr><tr><td>Multiple Choice (multiple)</td><td>Choose only what you are sure about</td></tr><tr><td>Fill in the Blanks</td><td>Type ahead; exact spelling</td></tr><tr><td>Highlight Correct Summary</td><td>Main idea, not a detail</td></tr><tr><td>Multiple Choice (single)</td><td>Read the question first</td></tr><tr><td>Select Missing Word</td><td>Predict what comes next (usually the last idea)</td></tr><tr><td>Highlight Incorrect Words</td><td>Click only if sure</td></tr><tr><td>Write From Dictation</td><td>Chunks and word endings</td></tr></tbody></table>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Dictation drill: have someone read these aloud or use a text-to-speech tool, then check your spelling.</p><ol><li>The library closes early on public holidays.</li><li>Students who registered late will receive their timetable by email.</li><li>Rising temperatures have changed the way farmers plan their year.</li></ol><details><summary>Show suggested answers</summary><ol><li>The library closes early on public holidays.</li><li>Students who registered late will receive their timetable by email.</li><li>Rising temperatures have changed the way farmers plan their year.</li></ol></details></div>
<div class="lc-take"><strong>Take away.</strong> Dictation is worth the daily practice: chunk, spell, check endings. Mix all eight types weekly.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 20, 1, ob_get_clean());
