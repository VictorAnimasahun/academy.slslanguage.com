<?php
// Test-Day Coaching — IELTS Academic Masterclass — 3 Months, Class 24, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>know what happens on test day from arrival to the last section</li><li>have a plan for the night before and the morning of the test</li><li>handle nerves, and know what to do when something goes wrong</li></ul></div><h5>Before test day</h5>
<ul><li>Check your <strong>booking confirmation</strong>: date, time, address, and the identification the centre requires. Bring exactly that document, and make sure the name on it matches your booking.</li><li>Visit the route or plan the travel so that you arrive at least 30 minutes early. Nobody is let in late.</li><li>Pack a clear water bottle if allowed and check the centre's rules on what you may carry into the room. Phones, watches and notes are normally not allowed.</li></ul>
<h5>The order of the test</h5>
<table><thead><tr><th>Section</th><th>Time</th><th>Notes</th></tr></thead><tbody><tr><td>Listening</td><td>about 30 minutes plus 10 minutes to transfer answers (paper) or 2 minutes to check (computer)</td><td>Recording played once</td></tr><tr><td>Reading</td><td>60 minutes</td><td>No extra transfer time on paper</td></tr><tr><td>Writing</td><td>60 minutes</td><td>Task 1 (20 min) then Task 2 (40 min)</td></tr><tr><td>Speaking</td><td>11-14 minutes</td><td>Often on the same day, or up to a week before or after</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Confirm the format you are sitting (paper or computer) and where your Speaking test is. Check timings against your booking confirmation, because centres differ.</div>
<h5>The night before</h5>
<ul><li>Review only: your error log, word list, and one model answer for each Writing task.</li><li>Do not take a full mock or learn new material.</li><li>Prepare your documents and clothes. Sleep.</li></ul>
<h5>On the day</h5>
<ol><li>Eat something light. Do a five-minute warm-up: say a few sentences aloud in English.</li><li>Arrive early, be calm, follow every instruction from the invigilator.</li><li>In Listening use the preview time to underline keywords.</li><li>In Reading keep to 20 minutes per passage.</li><li>In Writing spend 2-5 minutes planning each task.</li></ol>
<h5>If something goes wrong</h5>
<ul><li>Missed an answer in Listening: leave it, write a guess when you can, and listen to the next question.</li><li>Ran out of time in Reading: fill every blank; there is no penalty.</li><li>Equipment or noise problem: tell the invigilator immediately, not after the section.</li><li>Feeling anxious: breathe out slowly for a count of six and read the next question. One weak section does not decide your result.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Know your logistics, sleep, keep to your time plan, and speak up straight away if something is wrong.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 24, 2, ob_get_clean());
