<?php
// Error Review — IELTS Academic Masterclass — 3 Months, Class 15, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>find the errors in your own Writing that cost the most marks</li><li>correct them and see the pattern</li><li>write a short list of your personal top errors</li></ul></div><h5>Why review your own writing</h5>
<p>Everyone has a small set of mistakes that keep appearing. Finding <em>yours</em> is more useful than studying a long list of general rules.</p>
<h5>The seven most common errors</h5>
<table><thead><tr><th>Error</th><th>Example</th><th>Correct</th></tr></thead><tbody><tr><td>Articles</td><td>Government should build a school.</td><td>The government should build a school.</td></tr><tr><td>Subject-verb agreement</td><td>The number of cars are rising.</td><td>The number of cars is rising.</td></tr><tr><td>Singular / plural</td><td>Many student prefer ...</td><td>Many students prefer ...</td></tr><tr><td>Tense</td><td>Last year sales increase.</td><td>Last year sales increased.</td></tr><tr><td>Prepositions</td><td>depend of, interested about</td><td>depend on, interested in</td></tr><tr><td>Run-on sentences</td><td>Cars are popular, they are cheap.</td><td>Cars are popular because they are cheap.</td></tr><tr><td>Word form</td><td>a big different</td><td>a big difference</td></tr></tbody></table>
<h5>Review method</h5>
<ol><li>Reread your Writing Test 3 answer slowly, aloud if you can.</li><li>Underline each error and write the type in the margin.</li><li>Rewrite the sentence correctly.</li><li>Count the errors by type. The largest type is your priority.</li></ol>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Correct these sentences.</p><ol><li>The graph show a increase in sale.</li><li>A lot of peoples think that education are important.</li><li>In the recent years, many students goes abroad.</li></ol><details><summary>Show suggested answers</summary><ol><li>The graph shows an increase in sales.</li><li>A lot of people think that education is important.</li><li>In recent years, many students have gone abroad.</li></ol></details></div>
<div class="lc-take"><strong>Take away.</strong> Know your top three personal errors and check for them in every final read-through.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 15, 2, ob_get_clean());
