<?php
/**
 * Shared look and behaviour for the IELTS Academic Crash Course's Class 2 listening lessons (founder, 2026-10-28:
 * "lessons, quiz, exercises, to be joined with the existing listening test"; content from the SL1/SL5 decks and the
 * two listening videos). Each lesson page includes this once and echoes listening_kit_assets() into its body.
 *
 * Exercises are checked in the browser; nothing is stored -- a class's completion comes from its quiz and test.
 *   <div class="lx" data-lx>                                  one exercise
 *     <input data-answer="haidt|heidt">                         typed answer: any of the |-separated forms, compared
 *                                                               without case, spaces or punctuation
 *     <label><input type="radio" name="q1" value="C" data-correct></label>   choice answers: data-correct on the right ones
 *     <div class="lx-why" hidden>...</div>                       shown after Check
 *     <button type="button" data-lx-check>Check answers</button>
 */
if (!function_exists('listening_kit_assets')) {
    function listening_kit_assets(): string {
        return <<<'HTML'
<style>
.lc { font-size:.97rem; line-height:1.75; color:#1f2937; }
.lc h5 { margin:1.6rem 0 .5rem; font-weight:700; color:#0b4fb3; }
.lc .lc-aim { border:1px solid #dbe1ea; background:#f0f7ff; border-radius:3px; padding:1rem 1.25rem; margin-bottom:1.25rem; }
.lc .lc-aim strong { display:block; margin-bottom:.35rem; }
.lc .lc-aim ul { margin:0; padding-left:1.1rem; }
.lc .lc-tip { border:1px solid #dbe1ea; background:#f0fdf4; border-radius:3px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc .lc-warn { border:1px solid #dbe1ea; background:#fef2f2; border-radius:3px; padding:.8rem 1.1rem; margin:1rem 0; }
.lc table { width:100%; border-collapse:collapse; margin:.75rem 0 1rem; font-size:.92rem; }
.lc th { background:#eef2ff; text-align:left; padding:.5rem .7rem; border:1px solid #dbe1f0; }
.lc td { padding:.45rem .7rem; border:1px solid #e5e7eb; vertical-align:top; }
.lc .lc-take { background:#111827; color:#f9fafb; border-radius:3px; padding:1rem 1.25rem; margin-top:1.6rem; }
.lc .lc-take strong { color:#93c5fd; }
.lc video { width:100%; max-width:860px; display:block; border:1px solid #dbe1ea; border-radius:3px; background:#000; margin:.5rem 0 1rem; }
.lc audio { width:100%; max-width:560px; display:block; margin:.5rem 0 1rem; }
/* An exercise: a bordered block, its own title bar, Check / Try again, feedback per answer. */
.lx { border:1px solid #cfd8e3; border-radius:3px; margin:1.25rem 0; background:#fff; }
.lx-head { background:#f3f6fa; border-bottom:1px solid #cfd8e3; padding:.55rem 1rem; font-size:.78rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:#334155; }
.lx-body { padding:1rem 1.1rem; }
.lx-q { margin:0 0 .9rem; }
.lx-q > strong { display:block; margin-bottom:.35rem; }
.lx-opt { display:flex; gap:.6rem; align-items:flex-start; border:1px solid #dbe1ea; border-radius:3px; padding:.45rem .7rem; margin:0 0 .4rem; cursor:pointer; background:#fff; }
.lx-opt:hover { background:#f8fafc; }
.lx-opt input { margin-top:.35rem; accent-color:#0b77ff; }
.lx input[type=text] { border:1px solid #b8c4d3; border-radius:3px; padding:.3rem .55rem; font:inherit; min-width:11rem; }
.lx .lx-form { display:grid; grid-template-columns:max-content 1fr; gap:.45rem 1rem; align-items:center; max-width:640px; }
.lx .lx-form label { font-weight:600; color:#334155; }
.lx-actions { display:flex; gap:.6rem; align-items:center; flex-wrap:wrap; margin-top:.75rem; }
.lx-btn { background:#0b77ff; color:#fff; border:1px solid #0b77ff; border-radius:3px; padding:.4rem 1.1rem; font-weight:600; font-size:.9rem; cursor:pointer; }
.lx-btn.secondary { background:#fff; color:#0b77ff; }
.lx-score { font-weight:700; color:#0f172a; }
.lx .ok { border-color:#16a34a !important; background:#f0fdf4 !important; }
.lx .no { border-color:#dc2626 !important; background:#fef2f2 !important; }
.lx-mark { font-size:.8rem; font-weight:700; margin-left:.4rem; }
.lx-mark.ok-t { color:#15803d; } .lx-mark.no-t { color:#b91c1c; }
.lx-why { border-top:1px solid #e2e8f0; margin-top:.75rem; padding-top:.75rem; font-size:.92rem; color:#334155; }
.lx-transcript { white-space:pre-line; font-size:.9rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:3px; padding:.75rem 1rem; }
.lc u.key { text-decoration-thickness:2px; text-decoration-color:#0b77ff; }
body.dark .lc { color:#e5e7eb; }
body.dark .lx, body.dark .lx-opt, body.dark .lx input[type=text] { background:#1f1f1f; border-color:rgba(255,255,255,.14); color:#e5e7eb; }
body.dark .lx-head { background:#262626; color:#cbd5e1; border-color:rgba(255,255,255,.14); }
body.dark .lx-score, body.dark .lx .lx-form label { color:#e5e7eb; }
body.dark .lx-transcript, body.dark .lc .lc-aim, body.dark .lc .lc-tip, body.dark .lc .lc-warn { background:#1b2638; border-color:#2c3e57; color:#e2e8f0; }
body.dark .lc th { background:#1b2638; border-color:#2c3e57; } body.dark .lc td { border-color:#2c3e57; }
</style>
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-lx-check], [data-lx-reset]');
    if (!btn) return;
    const ex = btn.closest('[data-lx]');
    const norm = s => (s || '').toLowerCase().replace(/[^a-z0-9@]/g, '');
    ex.querySelectorAll('.lx-mark').forEach(m => m.remove());
    ex.querySelectorAll('.ok, .no').forEach(el => el.classList.remove('ok', 'no'));
    if (btn.hasAttribute('data-lx-reset')) {
        ex.querySelectorAll('input[type=text]').forEach(i => i.value = '');
        ex.querySelectorAll('input[type=radio], input[type=checkbox]').forEach(i => i.checked = false);
        ex.querySelectorAll('.lx-why').forEach(w => w.hidden = true);
        const sc = ex.querySelector('.lx-score'); if (sc) sc.textContent = '';
        return;
    }
    let right = 0, total = 0;
    ex.querySelectorAll('input[type=text][data-answer]').forEach(inp => {
        total++;
        const ok = inp.dataset.answer.split('|').some(a => norm(a) === norm(inp.value));
        if (ok) right++;
        inp.classList.add(ok ? 'ok' : 'no');
        if (!ok) inp.insertAdjacentHTML('afterend', '<span class="lx-mark no-t">Answer: ' + inp.dataset.answer.split('|')[0] + '</span>');
    });
    ex.querySelectorAll('.lx-q').forEach(q => {
        const opts = q.querySelectorAll('input[type=radio], input[type=checkbox]');
        if (!opts.length) return;
        total++;
        let ok = true;
        opts.forEach(o => {
            const should = o.hasAttribute('data-correct');
            if (o.checked !== should) ok = false;
            const box = o.closest('.lx-opt');
            if (should) box.classList.add('ok'); else if (o.checked) box.classList.add('no');
        });
        if (ok) right++;
        q.querySelector('strong').insertAdjacentHTML('beforeend', '<span class="lx-mark ' + (ok ? 'ok-t">Correct' : 'no-t">Not quite') + '</span>');
    });
    const sc = ex.querySelector('.lx-score'); if (sc) sc.textContent = right + ' / ' + total + ' correct';
    ex.querySelectorAll('.lx-why').forEach(w => w.hidden = false);
});
</script>
HTML;
    }
}
