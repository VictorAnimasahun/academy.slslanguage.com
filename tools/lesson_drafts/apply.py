"""Put the drafted lessons into the EMPTY lesson pages. Never touches a page that already has a body.
Usage: python3 tools/lesson_drafts/apply.py [--dry]   (reads the piece titles from the local database)"""
import re,os,subprocess,sys,importlib
HERE=os.path.dirname(os.path.abspath(__file__)); ROOT=os.path.dirname(os.path.dirname(HERE))+'/'
sys.path.insert(0,HERE)
LIB={}
for mod in ('lessons_ielts_a','lessons_ielts_b','lessons_pte_a','lessons_pte_b','lessons_pte_c'):
    try: LIB.update(importlib.import_module(mod).LESSONS)
    except ModuleNotFoundError: pass
def q(sql):
    r=subprocess.run(['/Applications/MAMP/Library/bin/mysql80/bin/mysql','-uroot','-proot','-S','/Applications/MAMP/tmp/mysql/mysql.sock','useraccounts','--default-character-set=utf8mb4','-N','-B','-e',sql],capture_output=True,text=True)
    return [l.split('\t') for l in r.stdout.strip().split('\n') if l]
rows=q("SELECT lp.title, lp.file_path FROM lesson_parts lp WHERE lp.file_path LIKE 'courses/%/lessons/class%\\_p%'")
done=skipped=missing=0; miss=set()
for title,path in rows:
    f=ROOT+path
    if not os.path.isfile(f): continue
    s=open(f,encoding='utf-8').read()
    m=re.search(r'(<!-- LESSON BODY START -->)(.*?)(<!-- LESSON BODY END -->)',s,re.S)
    if not m: continue
    if re.sub(r'<!--.*?-->','',m.group(2),flags=re.S).strip(): skipped+=1; continue
    body=LIB.get(title)
    if not body: missing+=1; miss.add(title); continue
    if '--dry' not in sys.argv: open(f,'w',encoding='utf-8').write(s[:m.end(1)]+"\n"+body+"\n"+s[m.start(3):])
    done+=1
print(f'filled {done} pages | already had content (left alone) {skipped} | no draft yet {missing}: {sorted(miss)}')
