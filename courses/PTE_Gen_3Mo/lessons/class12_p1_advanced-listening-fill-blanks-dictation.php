<?php
// Advanced Listening — Fill Blanks, Dictation & Highlight — PTE Academic Masterclass — 3 Months, Class 12, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>type missing words correctly while a recording plays</li><li>write from dictation with accurate spelling and word order</li><li>find the correct summary and the incorrect words</li></ul></div><h5>Fill in the Blanks (Listening)</h5>
<p>A transcript with gaps is on screen while the recording plays. Type the missing words as you hear them. Each correct word scores; <strong>spelling must be exact</strong>.</p>
<ul><li>Read ahead: look at the words around each gap and predict its form.</li><li>Do not fall behind: type what you heard and move on.</li><li>Check plural and past endings (<em>-s</em>, <em>-ed</em>).</li></ul>
<h5>Write From Dictation</h5>
<p>You hear one sentence and type it exactly. It is scored on the number of correct words. Listen for <strong>chunks</strong>, type the first half while you remember the second, and check spelling at the end.</p>
<h5>Highlight Correct Summary and Highlight Incorrect Words</h5>
<ul><li><strong>Correct Summary:</strong> the right summary gives the main idea and not a detail. Wrong options change a fact, overstate, or add something not said.</li><li><strong>Incorrect Words:</strong> follow the transcript with your mouse as the recording plays. Click a word only when you are sure it differs from what you hear. Wrong clicks cost points.</li></ul>
<div class="lc-tip"><strong>Tip.</strong> Practise dictation every day with a 10-word sentence. Spelling from memory improves fast with short daily work.</div>
<div class="lc-take"><strong>Take away.</strong> Type ahead of the recording, spell exactly, and click only when you are sure.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_3Mo', 12, 1, ob_get_clean());
