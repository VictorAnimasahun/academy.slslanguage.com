<?php
// Speaking — Describe Image & Re-tell Lecture — PTE Academic — 1-Month Plan, Class 3, piece 1 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>describe a graph, chart, map or picture in 40 seconds with a clear structure</li><li>re-tell a short lecture using its key points</li><li>use a template as a frame without sounding rehearsed</li></ul></div><h5>Describe Image</h5>
<p>An image (bar chart, line graph, pie chart, table, map, process or picture) appears. You get about <strong>25 seconds to prepare</strong> and <strong>40 seconds to speak</strong>. The score is based on content, oral fluency and pronunciation.</p>
<h5>A four-part frame</h5>
<ol><li><strong>Introduce:</strong> what the image shows (<em>This graph shows the number of ... between ... and ...</em>).</li><li><strong>Highest and lowest / main feature:</strong> the biggest, smallest or most striking thing, with numbers.</li><li><strong>Trend or comparison:</strong> how things change or compare.</li><li><strong>Conclude:</strong> one closing sentence (<em>Overall, ...</em>).</li></ol>
<div class="lc-tip"><strong>Tip.</strong> Use your 25 seconds to find the title, the units, the highest and the lowest values, and the trend. Say numbers accurately.</div>
<h5>Re-tell Lecture</h5>
<p>You hear a lecture of up to about 90 seconds and may see an image. You have about 10 seconds to prepare and 40 seconds to speak.</p>
<ul><li>Take <strong>keyword notes</strong> while it plays: the topic, two or three main points, one example, one conclusion.</li><li>Speak in order: <em>The lecture is about ... The speaker says ... Another important point is ... Finally / In conclusion ...</em></li><li>Include the key nouns and numbers you heard. Content words count.</li></ul>
<div class="lc-warn"><strong>Watch out.</strong> A template is a frame, not a script. If the words you memorised do not fit the image, the content score falls. Fill the frame with what is in front of you.</div>
<h5>Practice</h5>
<ul><li>Take any graph from a news website. Describe it aloud in 40 seconds and time yourself.</li><li>Watch a 90-second talk, write five keywords, then re-tell it.</li></ul>
<div class="lc-take"><strong>Take away.</strong> Have a frame, find the numbers in the preparation time, and speak for the whole 40 seconds.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'PTE_Gen_1Mo', 3, 1, ob_get_clean());
