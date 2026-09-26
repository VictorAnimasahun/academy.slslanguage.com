from lib import *
L={}
L['Mastery Listening — Write From Dictation & All Types']=page(
 ["reach high accuracy on Write From Dictation","switch quickly between all eight listening task types","keep spelling exact under pressure"],
 h("Write From Dictation: the biggest single source of points"),
 p("You hear one sentence (about 3-5 seconds) and type it. Each correct word scores, so it is one of the most reliable tasks in the test, and it also feeds Writing and Listening. Around ten of these appear in the test."),
 ol(["<strong>Listen for chunks</strong>, not words. Group the sentence into two or three meaning units.","<strong>Type while you still hear it</strong> in your head: first chunk, then the second.","<strong>Spell every word</strong> and check word endings: <em>-s, -ed, -ing</em>.","Do not stop to fix one word: a missing word costs one mark, a missed sentence costs more."]),
 table(["Often lost","Example","Check"],[["Plural","The students was ...","Match the verb"],["Past tense","She walk to school","Did I hear /t/ or /d/ at the end?"],["Small words","of, the, a, to","They are quiet but count"],["Homophones","their / there","Decide by grammar"]]),
 h("All the types, mixed"),
 table(["Task","Key habit"],[["Summarize Spoken Text","Keywords only; 50-70 words"],["Multiple Choice (multiple)","Choose only what you are sure about"],["Fill in the Blanks","Type ahead; exact spelling"],["Highlight Correct Summary","Main idea, not a detail"],["Multiple Choice (single)","Read the question first"],["Select Missing Word","Predict what comes next (usually the last idea)"],["Highlight Incorrect Words","Click only if sure"],["Write From Dictation","Chunks and word endings"]]),
 tryit("Dictation drill: have someone read these aloud or use a text-to-speech tool, then check your spelling.",["The library closes early on public holidays.","Students who registered late will receive their timetable by email.","Rising temperatures have changed the way farmers plan their year."],["The library closes early on public holidays.","Students who registered late will receive their timetable by email.","Rising temperatures have changed the way farmers plan their year."]),
 take("Dictation is worth the daily practice: chunk, spell, check endings. Mix all eight types weekly."))
L['Mastery Reading — Accuracy Under Pressure']=page(
 ["keep accuracy while the clock runs","decide when to guess and when to think","review each item type with a checklist"],
 h("Why accuracy drops under pressure"),
 p("Under time pressure, candidates read the options before the passage, pick the first word that <em>sounds</em> right, and skip the final check. The fixes are small habits."),
 h("Item-by-item checklist"),
 table(["Item","Before you submit"],[
  ["Fill in the Blanks","Read the whole sentence with your word in it: correct grammar, correct meaning, natural collocation?"],
  ["Multiple Choice (multiple answers)","Can I point to the words in the text for each answer I chose?"],
  ["Re-order Paragraphs","Read the whole order once, top to bottom. Does each paragraph follow logically?"],
  ["Multiple Choice (single answer)","Did I eliminate the two options the text contradicts?"]]),
 h("Guess or think?"),
 ul(["Gap-fill, single choice: always answer. There is no penalty.","Multiple-answer items: wrong answers cost points; choose only what the text supports.","Re-order: if stuck after 2-3 minutes, place the paragraphs you are sure of and move on."]),
 h("Train with a tighter clock"),
 p("In practice, set the timer for 10% less than the real time. When the real test comes, the clock feels generous."),
 take("Use the checklist for each item, guess where guessing is free, and practise on a shorter clock."))
L['Mastery Speaking — Perfect Pronunciation Patterns']=page(
 ["use stress and intonation patterns naturally","compare your recording with a native model","polish the tasks that carry the most speaking marks"],
 h("Three patterns worth mastering"),
 table(["Pattern","Rule","Example"],[
  ["Word stress","Every word of two or more syllables has one strong syllable","PHOtograph, phoTOGraphy, photoGRAPHic"],
  ["Sentence stress","Content words (nouns, main verbs, adjectives, adverbs) are stronger","The SALES ROSE sharply in MARCH"],
  ["Intonation","Statements fall at the end, lists rise on each item and fall on the last","Coffee↗, tea↗ and juice↘"]]),
 h("Shadowing routine (10 minutes a day)"),
 ol(["Choose a 20-second clip of a clear speaker (news or a lecture).","Listen twice, reading the transcript.","Play it again and speak along, about one word behind.","Record yourself, then play your recording next to the original and compare rhythm."]),
 h("Where the marks are"),
 p("Read Aloud, Repeat Sentence, Describe Image and Re-tell Lecture all give marks for pronunciation and oral fluency. Improving rhythm helps all four at once."),
 warn("Do not try to change your accent. Aim to be clear: full word endings, the right stress and steady rhythm."),
 take("Learn stress and intonation as patterns, shadow a real speaker daily, and record yourself to check."))
L['Mastery Writing — Band 90 Essay Structures']=page(
 ["use a structure that fits every prompt type","develop each paragraph with a reason and an example","check the form, structure and language points the computer scores"],
 h("The four-paragraph structure"),
 table(["Paragraph","Job","About"],[["Introduction","Paraphrase the prompt and state your position","35 words"],["Body 1","Reason 1 with explanation and example","75 words"],["Body 2","Reason 2 or the opposing view, with example","75 words"],["Conclusion","Restate your position and the main reasons","30 words"]]),
 h("Fits every prompt"),
 ul(["<strong>Opinion</strong> (Do you agree?): position in the introduction, both bodies support it.","<strong>Discuss both views:</strong> Body 1 = view A, Body 2 = view B, your position in the conclusion.","<strong>Advantages and disadvantages:</strong> Body 1 = advantages, Body 2 = disadvantages."]),
 h("What the computer checks"),
 table(["Criterion","What to do"],[["Form","200-300 words; one essay; no bullet points"],["Development and structure","Clear paragraphs; each with a topic sentence"],["Grammar","A mix of simple and complex sentences, all correct"],["Vocabulary","Precise words; avoid repeating the same one"],["Spelling","One consistent spelling style; check every word"]]),
 tip("Keep a list of ten topic-specific words for common themes (education, technology, environment, health, work) so you are not searching for words in the test."),
 take("One structure, developed ideas, and a final check of length, grammar and spelling."))
L['Full Exam Simulation Day 1']=page(
 ["sit the Speaking and Writing part under exam conditions","practise moving from task to task without stopping","review your results and choose one habit to change"],
 h("Before you start"),
 ul(["Book 2 hours with no interruptions. Phone off.","Use a headset with a microphone in a quiet room.","Have a timer and a pen and paper for notes (the test gives you a noteboard).","Do not restart a task, even if you make a mistake."]),
 h("Order (as in the test)"),
 ol(["Personal Introduction (not scored: use it to warm up)","Read Aloud","Repeat Sentence","Describe Image","Re-tell Lecture","Answer Short Question","Summarize Written Text","Essay"]),
 h("After the simulation"),
 table(["Ask yourself","Note"],[["Which task ran out of time?",""],["Where did I go silent for more than two seconds?",""],["Did my essay fall between 200 and 300 words?",""],["Which task felt hardest?",""]]),
 take("Sit it like the real thing, do not stop, and use the review to pick one habit to fix."))
L['Full Exam Simulation Day 2']=page(
 ["sit the Reading and Listening parts under exam conditions","manage energy and attention across a long test","compare with Day 1 and plan the last days"],
 h("Before you start"),
 ul(["Same conditions as Day 1: quiet room, headset, timer.","Eat and drink beforehand; do not stop during the test."]),
 h("Order"),
 ol(["Reading (about 30 minutes): Fill in the Blanks, Multiple Choice, Re-order Paragraphs.","Listening (about 30-40 minutes): the eight listening task types, including Summarize Spoken Text and Write From Dictation."]),
 h("After the simulation"),
 table(["Ask yourself","Note"],[["Which item type lost me the most marks?",""],["Did my concentration drop in the last 15 minutes?",""],["Did my spelling in dictation get worse when I was tired?",""],["What will I change in the last week?",""]]),
 take("Repeat the two days once more if you can, and use the review to set a short plan for the days that remain."))
L['AI Score Review & Final Targeted Practice']=page(
 ["read your score profile in the right order","choose the two task types that will raise your score most","build a simple daily plan for the last days"],
 h("Read the profile"),
 p("In a PTE score report you see the four skills and the six enabling skills. Read it in this order:"),
 ol(["Find your <strong>lowest enabling skill</strong>: it often limits several main skills.","Find the lowest main skill, and the task types inside it.","Check where practice and test scores disagree: that is usually a timing or stress problem."]),
 table(["If this is low","Practise"],[["Oral fluency","Shadowing and reading aloud in phrases"],["Pronunciation","Stress patterns, word endings"],["Spelling","Dictation drills, personal spelling list"],["Grammar","Error log from your essays and summaries"],["Vocabulary","Topic word lists, paraphrasing"],["Written discourse","Paragraph structure and linking"]]),
 h("The last days"),
 ul(["Two task types per day, 20 minutes each.","One timed part on alternate days.","The day before: light review, no new practice."]),
 take("Fix the lowest enabling skill first, keep the plan short and daily, and rest before the test."))
LESSONS = L
