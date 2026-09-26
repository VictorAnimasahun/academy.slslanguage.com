<?php
// Speaking Part 3 Drilling — IELTS Academic — 2-Month Plan, Class 12, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>give extended, reasoned answers to abstract questions</li><li>use a simple structure to organise a spoken answer</li><li>compare past and present, and speak about society, not only yourself</li></ul></div><h5>What is different in Part 3</h5>
<p>Part 3 is a discussion of about four to five minutes. The questions are more <strong>abstract</strong> and connected to the Part 2 topic: not <em>Did you enjoy the book?</em> but <em>Do you think people read less than in the past?</em> The examiner wants opinions, reasons and comparisons, in longer answers.</p>
<h5>A simple answer structure (PEE)</h5>
<ol><li><strong>Point:</strong> answer the question directly.</li><li><strong>Explain:</strong> say why, or give the reason.</li><li><strong>Example:</strong> a short example from your life, a friend or your country.</li></ol>
<div class="lc-ex"><div class="lc-ex-t">Question and answer</div><p><em>Do you think people read less than in the past?</em></p><p><strong>P</strong> I think they probably do, at least books. <strong>E</strong> The main reason is that screens compete for people's free time. <strong>E</strong> For example, most of my friends read news on their phones but only a few read a novel each month.</p></div>
<h5>Useful language</h5>
<table><thead><tr><th>To do this</th><th>Say</th></tr></thead><tbody><tr><td>Give an opinion</td><td>In my view / As far as I'm concerned / I tend to think</td></tr><tr><td>Be careful (hedging)</td><td>It depends on ... / Generally speaking / to some extent</td></tr><tr><td>Compare past and present</td><td>Years ago ... whereas nowadays ...</td></tr><tr><td>Talk about society</td><td>Many people ... / Younger people tend to ...</td></tr><tr><td>Buy thinking time</td><td>That's an interesting question. Let me think for a moment.</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> Do not answer with one sentence, and do not just say 'I don't know'. If you have no strong view, say what people <em>might</em> think and why.</div>
<div class="lc-try"><div class="lc-try-t">Try it</div><p>Answer each question aloud in 30-40 seconds using Point, Explain, Example.</p><ol><li>Why do some people prefer to live in a big city?</li><li>How has technology changed the way we communicate?</li><li>Should children be taught to manage money at school?</li></ol></div>
<div class="lc-take"><strong>Take away.</strong> Point, explanation, example. Talk about people in general, compare past and present, and keep your answers to about half a minute.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_2Mo', 12, 2, ob_get_clean());
