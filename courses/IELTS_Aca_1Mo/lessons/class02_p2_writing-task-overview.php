<?php
// Writing Task Overview — IELTS Academic — 1-Month Crash Course, Class 2, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>tell Task 1 and Task 2 apart and split your 60 minutes sensibly</li><li>name what examiners look for in each task</li><li>plan a response before writing a single sentence</li></ul></div><h5>Two tasks, two jobs</h5>
<table><thead><tr><th></th><th>Task 1</th><th>Task 2</th></tr></thead><tbody><tr><td>What it is</td><td>Describe visual information: a graph, chart, table, map or process</td><td>Write an essay giving your view on a topic</td></tr><tr><td>Minimum length</td><td>150 words</td><td>250 words</td></tr><tr><td>Time to spend</td><td>about 20 minutes</td><td>about 40 minutes</td></tr><tr><td>Weight</td><td>one third of the Writing band</td><td>two thirds of the Writing band</td></tr><tr><td>Your opinion?</td><td>Never. Report only what you can see.</td><td>Yes, if the question asks for it</td></tr></tbody></table>
<p>Task 2 counts twice as much as Task 1, so if time is tight, protect the 40 minutes for Task 2. A common plan is Task 1 first (20 minutes), then Task 2 (40 minutes). Some strong candidates do Task 2 first; choose one and stick with it in every practice.</p>
<h5>What a Task 1 answer must contain</h5>
<ol><li><strong>Introduction:</strong> one or two sentences that say what the visual shows, in your own words.</li><li><strong>Overview:</strong> two or three sentences that give the big picture: the biggest changes, the highest and lowest, the overall pattern. No numbers here. This is the paragraph that separates band 6 from band 7.</li><li><strong>Detail paragraphs:</strong> the key figures and comparisons, grouped logically. You do not need to mention every number.</li></ol>
<div class="lc-warn"><strong>Watch out.</strong> Do not give reasons or opinions in Task 1 ("because people prefer..."). The data cannot tell you why.</div>
<h5>What a Task 2 answer must contain</h5>
<ol><li><strong>Introduction:</strong> paraphrase the question and state your position or your plan.</li><li><strong>Two or three body paragraphs:</strong> one main idea each, explained and supported with an example.</li><li><strong>Conclusion:</strong> restate your position in fresh words. Do not add new ideas.</li></ol>
<h5>A two-minute plan is not wasted time</h5>
<p>Before you write, spend two minutes on paper. Task 1: what are the two or three main features? How will I group the details? Task 2: what is my position? What are my two main points and one example for each?</p>
<div class="lc-ex"><div class="lc-ex-t">Sample plan for a Task 2 essay</div><p>Question: <em>Some people think children should start school at 5, others at 7. Discuss both views and give your opinion.</em></p><p>Position: earlier is better if the school is play-based. Point 1: early social skills. Point 2: language development. View 2 (7): children are not ready, pressure. My opinion: start at 5 with a gentle programme.</p></div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Look at a Task 1 question you have seen before, or use any graph in a newspaper. In five minutes write:</p><ol><li>one sentence that says what the graph shows (introduction)</li><li>two sentences that give the overall pattern (overview)</li><li>the three figures you would definitely quote</li></ol></div>
<div class="lc-take"><strong>Take away.</strong> Two tasks, two jobs: describe accurately in Task 1, argue clearly in Task 2. Plan first, protect the time for Task 2, and never skip the overview.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_1Mo', 2, 2, ob_get_clean());
