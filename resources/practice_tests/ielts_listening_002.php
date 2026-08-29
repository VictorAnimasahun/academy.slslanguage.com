<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>IELTS Listening – Practice Test 2</title>
<style>
  :root{
    --navy:#1b3a5c; --navy-dark:#122a41; --accent:#2f7de1;
    --bg:#f4f6f9; --card:#ffffff; --border:#dbe2ea; --text:#22303f; --muted:#647084;
  }
  *{box-sizing:border-box;}
  body{margin:0;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;background:var(--bg);color:var(--text);}
  header.top{background:var(--navy);color:#fff;padding:14px 20px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;z-index:20;flex-wrap:wrap;gap:8px;}
  header.top h1{font-size:1.05rem;margin:0;font-weight:600;}
  #timer{background:var(--navy-dark);padding:6px 14px;border-radius:20px;font-variant-numeric:tabular-nums;font-weight:600;}
  #timer.warn{background:#8a2020;}
  main{max-width:900px;margin:0 auto;padding:20px 16px 80px;}
  .part{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:20px 22px;margin-bottom:22px;}
  .part h2{margin-top:0;color:var(--navy);border-bottom:2px solid var(--accent);padding-bottom:8px;}
  .instructions{font-style:italic;color:var(--muted);margin-bottom:14px;}
  .q-group{margin-bottom:22px;}
  .q-group h3{margin-bottom:6px;font-size:1rem;color:var(--navy-dark);}
  table.info{width:100%;border-collapse:collapse;margin:10px 0;}
  table.info th,table.info td{border:1px solid var(--border);padding:8px 10px;text-align:left;font-size:.95rem;}
  table.info th{background:#eaf0f8;}
  input.blank{border:none;border-bottom:2px solid var(--accent);background:transparent;font-size:.95rem;padding:2px 4px;width:150px;font-family:inherit;color:var(--navy-dark);}
  input.blank:focus{outline:none;background:#eaf3ff;}
  .num{display:inline-flex;align-items:center;justify-content:center;min-width:22px;height:22px;background:var(--accent);color:#fff;border-radius:50%;font-size:.75rem;margin-right:6px;font-weight:700;}
  ul.notes{list-style:none;padding-left:0;}
  ul.notes li{margin:8px 0;padding-left:4px;}
  ul.notes li.indent{padding-left:26px;}
  fieldset.mcq{border:1px solid var(--border);border-radius:8px;padding:10px 14px;margin:10px 0;}
  fieldset.mcq legend{font-weight:600;padding:0 6px;}
  label.opt{display:block;margin:6px 0;cursor:pointer;font-size:.95rem;}
  label.opt input{margin-right:8px;}
  .match-row{display:flex;align-items:center;gap:10px;margin:8px 0;flex-wrap:wrap;}
  .match-row span.title{flex:1;min-width:180px;font-style:italic;}
  select.letter{padding:5px 8px;border:1px solid var(--border);border-radius:6px;font-family:inherit;min-width:70px;}
  .topics-box{background:#eaf0f8;border:1px solid var(--border);border-radius:8px;padding:14px 18px;margin:12px 0;max-width:360px;}
  .topics-box h4{margin:0 0 8px;color:var(--navy);}
  .topics-box div{margin:3px 0;font-size:.9rem;}
  .map-wrap{display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start;margin:14px 0;}
  .map-image{display:block;width:440px;max-width:100%;height:auto;background:#fff;border:1px solid var(--border);border-radius:8px;flex-shrink:0;}
  .submit-bar{position:sticky;bottom:0;background:var(--card);border-top:1px solid var(--border);padding:14px 16px;display:flex;justify-content:center;gap:14px;}
  button{background:var(--accent);color:#fff;border:none;padding:11px 26px;border-radius:8px;font-size:1rem;cursor:pointer;font-weight:600;}
  button.secondary{background:#8a96a8;}
  button:hover{filter:brightness(1.08);}
  #resultsBox{display:none;background:#eaf7ee;border:1px solid #9ed4ac;border-radius:10px;padding:16px 20px;margin-bottom:20px;}
  #resultsBox h2{color:#1e6b34;margin-top:0;}
  .flagged{background:#fff6e0 !important;}
  @media(max-width:640px){.map-wrap{flex-direction:column;} .map-image{width:100%;}}
</style>
</head>
<body>

<header class="top">
  <h1>IELTS Listening – Practice Test 2</h1>
  <div id="timer">20:00</div>
</header>

<main>
  <p class="instructions" style="margin-top:0;">Answer all 40 questions, then click <strong>Check Answers</strong> at the bottom. Where a listening audio track is not yet attached to this page, treat this as an answer sheet to complete from the accompanying script.</p>

  <div id="resultsBox"></div>

  <form id="testForm">

  <!-- ================= PART 1 ================= -->
  <section class="part">
    <h2>Part 1 &nbsp;<small style="color:var(--muted);font-weight:400;">Questions 1–10</small></h2>

    <div class="q-group">
      <h3>Questions 1–4</h3>
      <p class="instructions">Complete the table below. Write <strong>ONE WORD ONLY</strong> for each answer.</p>
      <table class="info">
        <tr><th colspan="3" style="text-align:center;">Festival information</th></tr>
        <tr><th>Date</th><th>Type of event</th><th>Details</th></tr>
        <tr>
          <td>17th</td><td>a concert</td><td>performers from Canada</td>
        </tr>
        <tr>
          <td>18th</td><td>a ballet</td>
          <td>company called <span class="num">1</span><input class="blank" type="text" data-q="1"></td>
        </tr>
        <tr>
          <td>19th–20th (afternoon)</td><td>a play</td>
          <td>type of play: a comedy called <em>Jemima</em><br>
              has had a good <span class="num">2</span><input class="blank" type="text" data-q="2"></td>
        </tr>
        <tr>
          <td>20th (evening)</td>
          <td>a <span class="num">3</span><input class="blank" type="text" data-q="3"> show</td>
          <td>show is called <span class="num">4</span><input class="blank" type="text" data-q="4"></td>
        </tr>
      </table>
    </div>

    <div class="q-group">
      <h3>Questions 5–10</h3>
      <p class="instructions">Complete the notes below. Write <strong>ONE WORD ONLY</strong> for each answer.</p>

      <strong>Workshops</strong>
      <ul class="notes">
        <li>Making <span class="num">5</span><input class="blank" type="text" data-q="5"> food</li>
        <li>(children only) Making <span class="num">6</span><input class="blank" type="text" data-q="6"></li>
        <li>(adults only) Making toys from <span class="num">7</span><input class="blank" type="text" data-q="7"> using various tools</li>
      </ul>

      <strong>Outdoor activities</strong>
      <ul class="notes">
        <li>Swimming in the <span class="num">8</span><input class="blank" type="text" data-q="8"></li>
        <li>Walking in the woods, led by an expert on <span class="num">9</span><input class="blank" type="text" data-q="9"></li>
        <li>See the festival organiser's <span class="num">10</span><input class="blank" type="text" data-q="10"> for more information</li>
      </ul>
    </div>
  </section>

  <!-- ================= PART 2 ================= -->
  <section class="part">
    <h2>Part 2 &nbsp;<small style="color:var(--muted);font-weight:400;">Questions 11–20</small></h2>

    <div class="q-group">
      <h3>Questions 11–14</h3>
      <p class="instructions">Choose the correct letter, A, B or C.</p>
      <h3 style="text-align:center;color:var(--navy);">Minster Park</h3>

      <fieldset class="mcq">
        <legend><span class="num">11</span>The park was originally established</legend>
        <label class="opt"><input type="radio" name="q11" value="A"> A. as an amenity provided by the city council.</label>
        <label class="opt"><input type="radio" name="q11" value="B"> B. as land belonging to a private house.</label>
        <label class="opt"><input type="radio" name="q11" value="C"> C. as a shared area set up by the local community.</label>
      </fieldset>

      <fieldset class="mcq">
        <legend><span class="num">12</span>Why is there a statue of Diane Gosforth in the park?</legend>
        <label class="opt"><input type="radio" name="q12" value="A"> A. She was a resident who helped to lead a campaign.</label>
        <label class="opt"><input type="radio" name="q12" value="B"> B. She was a council member responsible for giving the public access.</label>
        <label class="opt"><input type="radio" name="q12" value="C"> C. She was a senior worker at the park for many years.</label>
      </fieldset>

      <fieldset class="mcq">
        <legend><span class="num">13</span>During the First World War, the park was mainly used for</legend>
        <label class="opt"><input type="radio" name="q13" value="A"> A. exercises by troops.</label>
        <label class="opt"><input type="radio" name="q13" value="B"> B. growing vegetables.</label>
        <label class="opt"><input type="radio" name="q13" value="C"> C. public meetings.</label>
      </fieldset>

      <fieldset class="mcq">
        <legend><span class="num">14</span>When did the physical transformation of the park begin?</legend>
        <label class="opt"><input type="radio" name="q14" value="A"> A. 2013</label>
        <label class="opt"><input type="radio" name="q14" value="B"> B. 2015</label>
        <label class="opt"><input type="radio" name="q14" value="C"> C. 2016</label>
      </fieldset>
    </div>

    <div class="q-group">
      <h3>Questions 15–20</h3>
      <p class="instructions">Label the map below. Write the correct letter, A–I, next to Questions 15–20.</p>

      <div class="map-wrap">
        <img class="map-image" src="../../assets/images/minster-park-map.png" alt="Minster Park map with locations labelled A to I">

        <div style="flex:1;min-width:220px;">
          <div class="match-row"><span class="num">15</span><span class="title">statue of Diane Gosforth</span>
            <select class="letter" data-q="15"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
          <div class="match-row"><span class="num">16</span><span class="title">wooden sculptures</span>
            <select class="letter" data-q="16"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
          <div class="match-row"><span class="num">17</span><span class="title">playground</span>
            <select class="letter" data-q="17"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
          <div class="match-row"><span class="num">18</span><span class="title">maze</span>
            <select class="letter" data-q="18"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
          <div class="match-row"><span class="num">19</span><span class="title">tennis courts</span>
            <select class="letter" data-q="19"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
          <div class="match-row"><span class="num">20</span><span class="title">fitness area</span>
            <select class="letter" data-q="20"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option><option>I</option></select></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ================= PART 3 ================= -->
  <section class="part">
    <h2>Part 3 &nbsp;<small style="color:var(--muted);font-weight:400;">Questions 21–30</small></h2>

    <div class="q-group">
      <h3>Questions 21 and 22</h3>
      <p class="instructions">Choose <strong>TWO</strong> letters, A–E. Which TWO groups of people is the display primarily intended for?</p>
      <fieldset class="mcq">
        <legend><span class="num">21–22</span>select two</legend>
        <label class="opt"><input type="checkbox" name="q21_22" value="A"> A. students from the English department</label>
        <label class="opt"><input type="checkbox" name="q21_22" value="B"> B. residents of the local area</label>
        <label class="opt"><input type="checkbox" name="q21_22" value="C"> C. the university's teaching staff</label>
        <label class="opt"><input type="checkbox" name="q21_22" value="D"> D. potential new students</label>
        <label class="opt"><input type="checkbox" name="q21_22" value="E"> E. students from other departments</label>
      </fieldset>
    </div>

    <div class="q-group">
      <h3>Questions 23 and 24</h3>
      <p class="instructions">Choose <strong>TWO</strong> letters, A–E. What are Cathy and Graham's TWO reasons for choosing the novelist Charles Dickens?</p>
      <fieldset class="mcq">
        <legend><span class="num">23–24</span>select two</legend>
        <label class="opt"><input type="checkbox" name="q23_24" value="A"> A. His speeches inspired others to try to improve society.</label>
        <label class="opt"><input type="checkbox" name="q23_24" value="B"> B. He used his publications to draw attention to social problems.</label>
        <label class="opt"><input type="checkbox" name="q23_24" value="C"> C. His novels are well-known now.</label>
        <label class="opt"><input type="checkbox" name="q23_24" value="D"> D. He was consulted on a number of social issues.</label>
        <label class="opt"><input type="checkbox" name="q23_24" value="E"> E. His reputation has changed in recent times.</label>
      </fieldset>
    </div>

    <div class="q-group">
      <h3>Questions 25–30</h3>
      <p class="instructions">What topic do Cathy and Graham choose to illustrate with each novel? Choose <strong>SIX</strong> answers from the box and write the correct letter, A–H, next to Questions 25–30.</p>

      <div class="topics-box">
        <h4>Topics</h4>
        <div>A&nbsp;&nbsp;poverty</div>
        <div>B&nbsp;&nbsp;education</div>
        <div>C&nbsp;&nbsp;Dickens's travels</div>
        <div>D&nbsp;&nbsp;entertainment</div>
        <div>E&nbsp;&nbsp;crime and the law</div>
        <div>F&nbsp;&nbsp;wealth</div>
        <div>G&nbsp;&nbsp;medicine</div>
        <div>H&nbsp;&nbsp;a woman's life</div>
      </div>

      <strong>Novels by Dickens</strong>
      <div class="match-row"><span class="num">25</span><span class="title"><em>The Pickwick Papers</em></span>
        <select class="letter" data-q="25"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
      <div class="match-row"><span class="num">26</span><span class="title"><em>Oliver Twist</em></span>
        <select class="letter" data-q="26"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
      <div class="match-row"><span class="num">27</span><span class="title"><em>Nicholas Nickleby</em></span>
        <select class="letter" data-q="27"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
      <div class="match-row"><span class="num">28</span><span class="title"><em>Martin Chuzzlewit</em></span>
        <select class="letter" data-q="28"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
      <div class="match-row"><span class="num">29</span><span class="title"><em>Bleak House</em></span>
        <select class="letter" data-q="29"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
      <div class="match-row"><span class="num">30</span><span class="title"><em>Little Dorrit</em></span>
        <select class="letter" data-q="30"><option value=""> </option><option>A</option><option>B</option><option>C</option><option>D</option><option>E</option><option>F</option><option>G</option><option>H</option></select></div>
    </div>
  </section>

  <!-- ================= PART 4 ================= -->
  <section class="part">
    <h2>Part 4 &nbsp;<small style="color:var(--muted);font-weight:400;">Questions 31–40</small></h2>
    <p class="instructions">Complete the notes below. Write <strong>ONE WORD ONLY</strong> for each answer.</p>
    <h3 style="text-align:center;color:var(--navy);">Agricultural programme in Mozambique</h3>

    <strong>How the programme was organised</strong>
    <ul class="notes">
      <li>It focused on a dry and arid region in Chicualacuala district, near the Limpopo River.</li>
      <li>People depended on the forest to provide charcoal as a source of income.</li>
      <li><span class="num">31</span><input class="blank" type="text" data-q="31"> was seen as the main priority to ensure the supply of water.</li>
      <li>Most of the work organised by farmers' associations was done by <span class="num">32</span><input class="blank" type="text" data-q="32">.</li>
      <li>Fenced areas were created to keep animals away from crops.</li>
      <li>The programme provided
        <ul class="notes">
          <li class="indent">– <span class="num">33</span><input class="blank" type="text" data-q="33"> for the fences</li>
          <li class="indent">– <span class="num">34</span><input class="blank" type="text" data-q="34"> for suitable crops</li>
          <li class="indent">– water pumps.</li>
        </ul>
      </li>
      <li>The farmers provided
        <ul class="notes">
          <li class="indent">– labour</li>
          <li class="indent">– <span class="num">35</span><input class="blank" type="text" data-q="35"> for the fences on their land.</li>
        </ul>
      </li>
    </ul>

    <strong>Further developments</strong>
    <ul class="notes">
      <li>The marketing of produce was sometimes difficult due to lack of <span class="num">36</span><input class="blank" type="text" data-q="36">.</li>
      <li>Training was therefore provided in methods of food <span class="num">37</span><input class="blank" type="text" data-q="37">.</li>
      <li>Farmers made special places where <span class="num">38</span><input class="blank" type="text" data-q="38"> could be kept.</li>
      <li>Local people later suggested keeping <span class="num">39</span><input class="blank" type="text" data-q="39">.</li>
    </ul>

    <strong>Evaluation and lessons learned</strong>
    <ul class="notes">
      <li>Agricultural production increased, improving incomes and food security.</li>
      <li>Enough time must be allowed, particularly for the <span class="num">40</span><input class="blank" type="text" data-q="40"> phase of the programme.</li>
    </ul>
  </section>

  </form>

  <div class="submit-bar">
    <button type="button" id="checkBtn">Check Answers</button>
    <button type="button" class="secondary" id="resetBtn">Reset</button>
  </div>
</main>

<script>
// ---- Countdown timer ----
let secondsLeft = 20 * 60;
const timerEl = document.getElementById('timer');
function tick(){
  if(secondsLeft <= 0){
    timerEl.textContent = "Time's up";
    timerEl.classList.add('warn');
    return;
  }
  const m = Math.floor(secondsLeft/60).toString().padStart(2,'0');
  const s = (secondsLeft%60).toString().padStart(2,'0');
  timerEl.textContent = m+':'+s;
  if(secondsLeft <= 60) timerEl.classList.add('warn');
  secondsLeft--;
}
tick();
const timerInterval = setInterval(tick, 1000);

// ---- Answer key ----
const answerKey = {
  1:"Eustatis", 2:"Review", 3:"Dance", 4:"Chat", 5:"Healthy",
  6:"Posters", 7:"Wood", 8:"Lake", 9:"Insects", 10:"Blog",
  11:"C", 12:"A", 13:"B", 14:"C",
  15:"E", 16:"C", 17:"B", 18:"A", 19:"G", 20:"D",
  q21_22: ["B", "D"], q23_24: ["B", "C"],
  25:"G", 26:"B", 27:"D", 28:"C", 29:"H", 30:"F",
  31:"Irrigation", 32:"Women", 33:"Wire", 34:"Seeds", 35:"Posts",
  36:"Transport", 37:"Preservation", 38:"Fish", 39:"Bees", 40:"Design"
};

function normalize(v){ return (v||"").trim().toLowerCase(); }

document.getElementById('checkBtn').addEventListener('click', function(){
  let answered = 0, score = 0, total = 40;
  document.querySelectorAll('input.blank').forEach(el=>{
    const questionNumber = el.dataset.q;
    const value = normalize(el.value);
    if(value) answered++;
    if(value && value === normalize(answerKey[questionNumber])) score++;
  });
  document.querySelectorAll('select.letter').forEach(el=>{
    const questionNumber = el.dataset.q;
    if(el.value) answered++;
    if(el.value && normalize(el.value) === normalize(answerKey[questionNumber])) score++;
  });
  [11,12,13,14].forEach(n=>{
    const selected = document.querySelector('input[name="q'+n+'"]:checked');
    if(selected) answered++;
    if(selected && normalize(selected.value) === normalize(answerKey[n])) score++;
  });
  [['q21_22', answerKey.q21_22], ['q23_24', answerKey.q23_24]].forEach(([name, key])=>{
    const selected = Array.from(document.querySelectorAll('input[name="'+name+'"]:checked')).map(el=>el.value).sort();
    answered += selected.length;
    if(selected.length === key.length && selected.every((value, index)=>value === key.slice().sort()[index])) score += key.length;
  });

  const box = document.getElementById('resultsBox');
  box.style.display = 'block';
  box.innerHTML = '<h2>Results</h2><p>Score: <strong>' + score + ' / ' + total + '</strong>. ' + answered + ' of ' + total + ' questions have a response entered.</p>';
  box.scrollIntoView({behavior:'smooth'});
});

document.getElementById('resetBtn').addEventListener('click', function(){
  document.getElementById('testForm').reset();
  document.getElementById('resultsBox').style.display = 'none';
});
</script>
</body>
</html>