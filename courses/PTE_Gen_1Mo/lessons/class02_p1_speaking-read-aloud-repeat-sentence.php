<?php
// Speaking — Read Aloud & Repeat Sentence — PTE Academic — 1-Month Plan, Class 2, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>read a text aloud with natural stress and pauses</li><li>repeat a sentence exactly after hearing it once</li><li>avoid the habits that lower oral fluency and pronunciation scores</li></ul></div><h5>Read Aloud</h5>
<p>A short text (up to about 60 words) appears on the screen. You have around 30-40 seconds to prepare, then you read it into the microphone. It is scored on <strong>content</strong> (do you say every word), <strong>oral fluency</strong> and <strong>pronunciation</strong>.</p>
<ul><li>Use the preparation time to <strong>read the whole text silently</strong> and mark the pauses (commas, ends of phrases).</li><li>Speak at a steady, natural speed: not fast, not word by word.</li><li>If you make a mistake, <strong>keep going</strong>. Stopping and starting again hurts fluency more than the error.</li><li>Stress content words (nouns, verbs, adjectives). Small words like <em>the, of, to</em> are said quickly.</li></ul>
<h5>Repeat Sentence</h5>
<p>You hear a sentence of about 3-9 seconds, once. Then you repeat it. It is scored on content, fluency and pronunciation. You get a mark for each word you say correctly in the right order.</p>
<ul><li>Listen for <strong>meaning</strong>, not for each word: chunks of meaning are easier to remember.</li><li>Do not take notes; repeat straight away.</li><li>If you forget a word, keep going with a natural rhythm. A missing word costs less than a long pause.</li></ul>
<h5>Habits that lower scores</h5>
<table><thead><tr><th>Habit</th><th>Fix</th></tr></thead><tbody><tr><td>Long silence at the start or in the middle (the recording may end after about three seconds of silence)</td><td>Start within two seconds of the beep; use short pauses at commas</td></tr><tr><td>Rushing to finish</td><td>Practise at a pace where every word is clear</td></tr><tr><td>Reading in a flat voice</td><td>Let your voice rise and fall with meaning</td></tr><tr><td>Adding words</td><td>Say exactly what you hear or read</td></tr></tbody></table>
<h5>Daily drill (15 minutes)</h5>
<ol><li>Record yourself reading any short English paragraph.</li><li>Listen: mark places where you paused or tripped.</li><li>Read it again, fixing only those places.</li><li>Listen to a short news clip, pause it, and repeat what you heard.</li></ol>
<div class="lc-take"><strong>Take away.</strong> Read in phrases, keep moving after a slip, and repeat by meaning. Smooth and clear scores better than fast.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_1Mo', 2, 1, ob_get_clean());
