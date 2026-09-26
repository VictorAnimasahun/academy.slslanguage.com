"""Layout helpers for the lesson drafts. Each lesson is built from a few blocks so every page has the same clear shape:
an aim box, short sections, phrase tables, worked examples, a "try it" with answers hidden until asked, and a takeaway."""
import html
def e(t): return html.escape(t,quote=False)
CSS = """<style>
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
</style>"""
def h(t): return f"<h5>{e(t)}</h5>"
def p(t): return f"<p>{t}</p>"          # p() takes HTML so a lesson can use <strong>/<em>
def ul(items): return "<ul>"+"".join(f"<li>{i}</li>" for i in items)+"</ul>"
def ol(items): return "<ol>"+"".join(f"<li>{i}</li>" for i in items)+"</ol>"
def tip(t): return f'<div class="lc-tip"><strong>Tip.</strong> {t}</div>'
def warn(t): return f'<div class="lc-warn"><strong>Watch out.</strong> {t}</div>'
def ex(title,t): return f'<div class="lc-ex"><div class="lc-ex-t">{e(title)}</div>{t}</div>'
def table(head,rows): return "<table><thead><tr>"+"".join(f"<th>{e(x)}</th>" for x in head)+"</tr></thead><tbody>"+"".join("<tr>"+"".join(f"<td>{c}</td>" for c in r)+"</tr>" for r in rows)+"</tbody></table>"
def tryit(instr,items,answers):
    body=f'<div class="lc-try"><div class="lc-try-t">Try it</div><p>{instr}</p>'+(ol(items) if items else '')
    if answers: body+="<details><summary>Show suggested answers</summary>"+ol(answers)+"</details>"
    return body+"</div>"
def take(t): return f'<div class="lc-take"><strong>Take away.</strong> {t}</div>'
def page(aims,*blocks):
    aim='<div class="lc-aim"><strong>By the end of this lesson you can:</strong>'+ul(aims)+'</div>'
    return ("<!-- DRAFT: written by Claude on 2026-09-26 for the instructor to review and edit before students see it. Not yet checked. -->\n"
            +CSS+'\n<div class="lc">'+aim+"\n".join(blocks)+"</div>")
