<?php
// Map Description & Passive Voice — IELTS Academic — 2-Month Plan, Class 6, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>describe how a place has changed between two maps</li><li>use the passive voice naturally for changes done to a place</li><li>organise a map answer so the overview is clear</li></ul></div><h5>What map tasks ask for</h5>
<p>You are usually given two or three maps of the same place at different times, or a map now and a planned future map. You must describe <strong>what changed</strong> and <strong>what stayed the same</strong>. There are no numbers, so vocabulary and grammar carry the score.</p>
<h5>Location language</h5>
<table><thead><tr><th>Idea</th><th>Phrases</th></tr></thead><tbody><tr><td>Where things are</td><td>in the north-east, to the west of, opposite, next to, between, along the river, at the entrance</td></tr><tr><td>Things that appear</td><td>was built, was constructed, was added, appeared, a new ... was opened</td></tr><tr><td>Things that disappear</td><td>was demolished, was knocked down, was removed, was cleared, was replaced by</td></tr><tr><td>Things that change</td><td>was converted into, was extended, was enlarged, was relocated, was divided into</td></tr><tr><td>Things that stay</td><td>remained unchanged, stayed the same, was not altered</td></tr></tbody></table>
<h5>The passive, and why maps love it</h5>
<p>In map tasks the person who built the thing is not important, so the passive is natural: <em>A car park <strong>was built</strong> to the east of the school.</em> Form it with <strong>be + past participle</strong>, and match the tense to the time in the map.</p>
<table><thead><tr><th>Time in the map</th><th>Passive form</th><th>Example</th></tr></thead><tbody><tr><td>Past change (between two past maps)</td><td>was / were + past participle</td><td>The farmland <strong>was replaced</strong> by housing.</td></tr><tr><td>Future plan</td><td>will be + past participle</td><td>A bridge <strong>will be built</strong> across the river.</td></tr><tr><td>Something in the map now</td><td>is / are + past participle</td><td>The old factory <strong>is used</strong> as a museum.</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Do not use the passive for things that happen by themselves: write <em>The village grew</em>, not <em>The village was grown</em>.</div>
<h5>Structure your answer</h5>
<ol><li><strong>Introduction:</strong> say what the maps show and the years.</li><li><strong>Overview:</strong> the big picture: <em>The area changed from mainly farmland to a residential district, and transport links improved.</em></li><li><strong>Detail 1:</strong> the north and west, comparing the maps.</li><li><strong>Detail 2:</strong> the south and east, comparing the maps.</li></ol>
<div class="lc-ex"><div class="lc-ex-t">Model sentences</div><p>The two maps show how the town of Norbiton changed between 1990 and today.</p><p>Overall, the industrial area was largely replaced by housing and leisure facilities, and access to the site improved.</p></div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Rewrite each sentence in the passive.</p><ol><li>The council built a new road to the north.</li><li>They will remove the old warehouse.</li><li>The company turned the farm into a hotel.</li></ol><details><summary>Show suggested answers</summary><ol><li>A new road was built to the north.</li><li>The old warehouse will be removed.</li><li>The farm was turned into a hotel.</li></ol></details></div>
<div class="lc-take"><strong>Take away.</strong> Say what changed and where, use the passive for things built, removed or converted, and give an overview before any detail.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 6, 2, ob_get_clean());
