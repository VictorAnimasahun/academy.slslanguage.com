<?php
// Comparative Structures & TEE — IELTS Academic — 2-Month Plan, Class 4, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>compare two or more items with a range of accurate comparative structures</li><li>build a detail paragraph with a topic sentence, evidence and explanation (TEE)</li><li>avoid the errors that keep Task 1 at band 5 or 6</li></ul></div><h5>Comparing in Task 1</h5>
<p>Task 1 is full of comparisons: one year against another, one country against another, the highest against the lowest. Learn these structures and mix them.</p>
<table><thead><tr><th>Structure</th><th>Example</th></tr></thead><tbody><tr><td>Comparative adjective + than</td><td>Fuel prices were <strong>higher than</strong> food prices.</td></tr><tr><td>as ... as / not as ... as</td><td>Spending on travel was <strong>almost as high as</strong> spending on food.</td></tr><tr><td>twice / three times as ... as</td><td>Japan spent <strong>twice as much as</strong> Italy.</td></tr><tr><td>the most / the least</td><td>Rent was <strong>the largest</strong> expense; heating was <strong>the smallest</strong>.</td></tr><tr><td>While / Whereas / By contrast</td><td><strong>Whereas</strong> exports rose, imports fell.</td></tr><tr><td>Similarly / In the same way</td><td><strong>Similarly</strong>, the figure for women increased.</td></tr><tr><td>A greater / smaller proportion of ... than</td><td>A <strong>greater proportion of</strong> men than women worked full-time.</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Compare like with like. Write <em>the population of Tokyo was larger than <strong>that of</strong> Paris</em>, not <em>larger than Paris</em>.</div>
<h5>TEE: the detail paragraph</h5>
<p>A good detail paragraph is not a list of numbers. Give it a shape:</p>
<ul><li><strong>T – Topic sentence.</strong> Say what this paragraph is about: <em>Turning to the leisure categories, ...</em></li><li><strong>E – Evidence.</strong> Give the key figures that support it.</li><li><strong>E – Explain / Compare.</strong> Say what the figures show by comparing them or naming the pattern.</li></ul>
<div class="lc-ex"><div class="lc-ex-t">One TEE paragraph</div><p><strong>T</strong> Looking at the four cities, Lagos had by far the fastest growth. <strong>E</strong> Its population rose from 8 million in 1990 to 15 million in 2020. <strong>E</strong> That is nearly double, whereas the other three cities grew by less than 30%.</p></div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Write one sentence for each pair using the structure in brackets.</p><ol><li>Cars: 40% of journeys. Buses: 20% of journeys. (twice as ... as)</li><li>Men: 55%. Women: 45%. (a greater proportion ... than)</li><li>Coal fell. Solar rose. (whereas)</li></ol><details><summary>Show suggested answers</summary><ol><li>Cars were used for twice as many journeys as buses.</li><li>A greater proportion of men than women were employed.</li><li>Coal use fell, whereas solar power rose.</li></ol></details></div>
<div class="lc-take"><strong>Take away.</strong> Vary your comparisons, compare like with like, and give every detail paragraph a topic, its evidence and a sentence that explains what the numbers mean.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 4, 2, ob_get_clean());
