<?php
// Advanced Speaking — IELTS Academic Masterclass — 3 Months, Class 20, piece 2 (lesson).
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
<div class="lc"><div class="lc-aim"><strong>By the end of this lesson you can:</strong><ul><li>raise your fluency and range across all three parts</li><li>use more precise vocabulary and a wider range of grammar</li><li>improve stress and intonation so you are easy to follow</li></ul></div><h5>What examiners reward at higher bands</h5>
<ul><li><strong>Fluency:</strong> you speak at length with few pauses, and hesitation is about ideas, not words.</li><li><strong>Vocabulary:</strong> you use less common words and idiomatic phrases accurately, and paraphrase when you cannot remember a word.</li><li><strong>Grammar:</strong> a mix of simple and complex sentences with most of them correct.</li><li><strong>Pronunciation:</strong> clear sounds, natural stress and rhythm.</li></ul>
<h5>Range without risk</h5>
<table><thead><tr><th>Do</th><th>Example</th></tr></thead><tbody><tr><td>Use conditionals</td><td>If I had more time, I would learn a second instrument.</td></tr><tr><td>Use relative clauses</td><td>My brother, who lives in Toronto, ...</td></tr><tr><td>Use less common words you know well</td><td>reliable, exhausting, thought-provoking</td></tr><tr><td>Paraphrase</td><td>It's a sort of tool for measuring ... (when you lack the word)</td></tr></tbody></table>
<div class="lc-warn"><strong>Watch out.</strong> A rare idiom used wrongly costs more than plain vocabulary used correctly. Use only what you can say naturally.</div>
<h5>Stress and intonation</h5>
<p>English is stress-timed: content words (nouns, main verbs, adjectives) are louder and longer; small words are quick. Your voice should rise and fall, not stay flat. Record yourself reading a short paragraph and listen for that rhythm.</p>
<div class="lc-take"><strong>Take away.</strong> Aim for extended, natural answers, use a range of structures you control, and let stress and intonation carry your meaning.</div></div>
<!-- LESSON BODY END -->
<?php
render_lesson_shell($db, 'IELTS_Aca_3Mo', 20, 2, ob_get_clean());
