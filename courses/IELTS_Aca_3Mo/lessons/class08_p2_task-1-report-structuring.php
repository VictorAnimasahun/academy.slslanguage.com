<?php
// Task 1 Report Structuring — IELTS Academic Masterclass — 3 Months, Class 8, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>build a Task 1 report from four clear paragraphs</li><li>write an overview that earns the higher bands</li><li>decide what to include and what to leave out</li></ul></div><h5>The four-paragraph report</h5>
<table><thead><tr><th>Paragraph</th><th>Job</th><th>Length</th></tr></thead><tbody><tr><td>1 Introduction</td><td>Paraphrase what the visual shows</td><td>1–2 sentences</td></tr><tr><td>2 Overview</td><td>The two or three main features, with no detailed numbers</td><td>2–3 sentences</td></tr><tr><td>3 Detail 1</td><td>One group of data, with evidence and comparison</td><td>3–4 sentences</td></tr><tr><td>4 Detail 2</td><td>The other group of data</td><td>3–4 sentences</td></tr></tbody></table>
<h5>Writing the overview</h5>
<p>The overview is the most important paragraph in Task 1. Look for: the <strong>highest</strong> and <strong>lowest</strong>, the <strong>biggest change</strong>, the <strong>overall trend</strong>, and any <strong>striking similarity or difference</strong>.</p>
<div class="lc-ex"><div class="lc-ex-t">Weak and strong</div><p><strong>Weak (details, not an overview):</strong> In 2010, 40% of people used cars.</p><p><strong>Strong:</strong> Overall, car use grew steadily, while bus and train use fell, and cars remained the most popular option throughout.</p></div>
<h5>Choosing what to include</h5>
<ul><li>Select the key features. You do not have to report every number.</li><li>Group data that behave alike (the three rising lines together).</li><li>Always include units (%, million, tonnes) and years.</li><li>Never explain <em>why</em>.</li></ul>
<h5>A template you can adapt</h5>
<p><em>The [chart] illustrates [what] in [where] [when]. Overall, [main trend 1], while [main trend 2]. [Group A] ... [figures and comparison]. In contrast, [Group B] ... [figures and comparison].</em></p>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Use these notes to write an overview (two sentences, no figures): coffee sales 20 → 45 million over 10 years; tea sales 30 → 28 million; coffee overtook tea in year 6.</p><details><summary>Show suggested answers</summary><ol><li>Overall, coffee sales more than doubled while tea sales stayed almost flat. Coffee overtook tea part-way through the period and ended well ahead.</li></ol></details></div>
<div class="lc-warn"><strong>Watch out.</strong> If you run short of time, write the introduction and overview first and one detail paragraph. A report with no overview cannot score above band 5 for Task Achievement.</div>
<div class="lc-take"><strong>Take away.</strong> Introduction, overview, two detail paragraphs. Spend your best effort on the overview: it shows the examiner you understand the whole picture.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 8, 2, ob_get_clean());
