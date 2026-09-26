<?php
// Orientation & Diagnostic Assessment — IELTS Academic Masterclass — 3 Months, Class 1, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>explain the four parts of IELTS Academic and how each is scored</li><li>take the diagnostic test and read your result honestly</li><li>set a target band and a weekly study rhythm for this course</li></ul></div><h5>The test in one table</h5>
<table><thead><tr><th>Part</th><th>Time</th><th>What you do</th><th>Marked by</th></tr></thead><tbody><tr><td>Listening</td><td>about 30 minutes + 10 to transfer answers</td><td>4 recordings, 40 questions; you hear each recording once</td><td>Answer key (1 mark per question)</td></tr><tr><td>Reading</td><td>60 minutes</td><td>3 long texts, 40 questions; no extra time to transfer</td><td>Answer key (1 mark per question)</td></tr><tr><td>Writing</td><td>60 minutes</td><td>Task 1: describe a graph, chart, table, map or process (150+ words). Task 2: an essay (250+ words)</td><td>Trained examiners against 4 criteria; Task 2 counts twice as much as Task 1</td></tr><tr><td>Speaking</td><td>11–14 minutes</td><td>A face-to-face interview in three parts, recorded</td><td>Trained examiner against 4 criteria</td></tr></tbody></table>
<p>Every part is reported as a band from 1 to 9 (half bands are possible). Your <strong>overall band</strong> is the average of the four, rounded to the nearest half band. Universities and immigration bodies usually name a minimum overall band and sometimes a minimum in each part, so know both numbers for your goal.</p>
<h5>How the four Writing and Speaking criteria work</h5>
<p>You will hear four names again and again in this course. Learn them now.</p>
<table><thead><tr><th>Writing</th><th>Speaking</th></tr></thead><tbody><tr><td>Task Achievement (Task 1) / Task Response (Task 2): did you answer the question fully?</td><td>Fluency and Coherence: do you speak smoothly and logically?</td></tr><tr><td>Coherence and Cohesion: is it organised and easy to follow?</td><td>Lexical Resource: how wide and how accurate is your vocabulary?</td></tr><tr><td>Lexical Resource: range and accuracy of vocabulary</td><td>Grammatical Range and Accuracy: variety and control of structures</td></tr><tr><td>Grammatical Range and Accuracy: variety and control of structures</td><td>Pronunciation: how easy are you to understand?</td></tr></tbody></table>
<h5>Your diagnostic</h5>
<p>The diagnostic is a short, honest snapshot of where you start. It is <strong>not</strong> a pass or fail. The most useful thing you can do is take it under real conditions.</p>
<ol><li>Find a quiet place, use headphones and put your phone away.</li><li>Do not look anything up. A wrong answer now tells us exactly what to teach you.</li><li>Finish in one sitting.</li><li>Afterwards, write down the two things that felt hardest. We will come back to them in Week 8.</li></ol>
<p><a class="btn btn-primary" href="<?= ACADEMY_URL ?>resources/mock_tests/ielts_aca_diagnostic.php"><i class="bi bi-play-circle me-1"></i>Open the Academic diagnostic</a></p>
<h5>Reading your result</h5>
<ul><li><strong>Listening and Reading</strong> are scored by the machine. Look at which question types you lost marks on, not only the total.</li><li><strong>Writing</strong> is scored against the four criteria. A weak criterion is more useful to know than a single band.</li><li>A result is a starting point. Most learners move by half a band to one band over eight weeks when they follow the plan and practise every week.</li></ul>
<h5>Set your target and your rhythm</h5>
<ul><li>Write your target overall band and the minimum you need in each part.</li><li>Subtract your diagnostic from your target. That gap decides how much you must add each week.</li><li>Plan two class sessions a week, plus at least three short practice sessions of 20–30 minutes. Little and often beats one long weekend session.</li></ul>
<div class="lc-tip"><strong>Tip.</strong> Put your study times in your calendar today. A plan with a time attached gets done; a plan without one is a wish.</div>
<div class="lc-take"><strong>Take away.</strong> The test rewards steady, specific practice. Know your target, know your starting point, and let the next eight weeks close the gap one skill at a time.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 1, 1, ob_get_clean());
