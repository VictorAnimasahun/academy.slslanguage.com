from lib import *
L={}
L['Advanced Listening — Fill Blanks, Dictation & Highlight']=page(
 ["type missing words correctly while a recording plays","write from dictation with accurate spelling and word order","find the correct summary and the incorrect words"],
 h("Fill in the Blanks (Listening)"),
 p("A transcript with gaps is on screen while the recording plays. Type the missing words as you hear them. Each correct word scores; <strong>spelling must be exact</strong>."),
 ul(["Read ahead: look at the words around each gap and predict its form.","Do not fall behind: type what you heard and move on.","Check plural and past endings (<em>-s</em>, <em>-ed</em>)."]),
 h("Write From Dictation"),
 p("You hear one sentence and type it exactly. It is scored on the number of correct words. Listen for <strong>chunks</strong>, type the first half while you remember the second, and check spelling at the end."),
 h("Highlight Correct Summary and Highlight Incorrect Words"),
 ul(["<strong>Correct Summary:</strong> the right summary gives the main idea and not a detail. Wrong options change a fact, overstate, or add something not said.","<strong>Incorrect Words:</strong> follow the transcript with your mouse as the recording plays. Click a word only when you are sure it differs from what you hear. Wrong clicks cost points."]),
 tip("Practise dictation every day with a 10-word sentence. Spelling from memory improves fast with short daily work."),
 take("Type ahead of the recording, spell exactly, and click only when you are sure."))
L['Advanced Reading — Speed & Complex Item Types']=page(
 ["read faster while keeping accuracy","handle complex Re-order Paragraphs and multi-answer items","budget time across the Reading part"],
 h("Time in the Reading part"),
 p("Reading is short (about 30 minutes). The fill-in-the-blanks tasks are quick marks; Re-order Paragraphs and multi-answer questions are slower. Aim for about one minute for a Multiple Choice item and two to three minutes for each Re-order task."),
 h("Ways to read faster"),
 ul(["Read in phrases, not words. Practise with short news articles and a timer.","Skim the whole passage first, then answer.","In gap-fill, do the easy gaps first and return to the harder ones."]),
 h("Complex items"),
 table(["Item","Approach"],[["Re-order with five paragraphs","Find the first paragraph, then link pairs by pronouns and linking words"],["Multiple answers","Eliminate options that the text contradicts, then choose the ones it supports"],["Fill in blanks with a word box","Use each word once; match by grammar first, then meaning"]]),
 warn("Speed without accuracy loses points. Keep a record of the item types you get wrong and slow down on those."),
 take("Skim first, take quick marks first, and use logic on the complex items."))
L['Advanced Speaking — Fluency, Pronunciation & Oral Fluency Score']=page(
 ["explain how oral fluency and pronunciation are scored","improve rhythm, stress and linking","find and fix your own weak sounds"],
 h("What is scored"),
 table(["Score","What it looks at"],[["Oral fluency","Smooth, natural speech: rhythm, phrasing, few hesitations, no repetition"],["Pronunciation","Clear individual sounds, word stress and sentence stress so a listener can understand you easily"]]),
 p("The computer does not mark accent. It marks how <em>easy you are to understand</em>."),
 h("Fluency"),
 ul(["Speak in <strong>phrases of three to six words</strong>, with short pauses between them.","Do not fill silence with <em>um</em> and <em>er</em>. Pause instead.","If you make a mistake, do not restart: keep going."]),
 h("Stress and linking"),
 ul(["Stress the important word in each phrase: <em>a big <strong>change</strong> in <strong>climate</strong></em>.","Link words: <em>an apple</em> sounds like <em>a-napple</em>.","Say the end of each word: <em>walked, boxes, months</em>."]),
 h("Fix your weak sounds"),
 ol(["Record 60 seconds of speaking.","Listen and write the words you were unsure of.","Look each up and listen to a native pronunciation.","Say each word ten times, then in a sentence."]),
 take("Phrases, pauses, stress and clear word endings. Fix your own weak words every day."))
L['Advanced Writing — Essay Coherence & Discourse Markers']=page(
 ["organise an essay so each paragraph has one job","use discourse markers accurately","raise the written-discourse score"],
 h("Coherence"),
 p("<strong>Written discourse</strong> is scored on how well the ideas connect: a clear position, one main idea per paragraph, and links between sentences."),
 h("Discourse markers"),
 table(["To...","Use"],[["Add","In addition, Moreover, Furthermore"],["Contrast","However, On the other hand, Whereas"],["Give a reason","Because, Since, Due to"],["Give a result","Therefore, As a result, Consequently"],["Give an example","For instance, For example, Such as"],["Conclude","In conclusion, To sum up, Overall"]]),
 warn("Do not put a marker at the start of every sentence. Two or three per paragraph is enough."),
 ex("Paragraph pattern","<p><strong>Claim:</strong> Online learning is more flexible. <strong>Reason:</strong> Students can study at any time. <strong>Example:</strong> For instance, working adults can attend lessons in the evening. <strong>Link:</strong> As a result, more people can continue their education.</p>"),
 take("One idea per paragraph, clear links, and markers used with care."))
L['AI Score Maximisation — Common Errors & Fixes']=page(
 ["recognise the errors that lose the most marks in each PTE task","fix them with specific habits","use your practice scores to choose what to fix first"],
 h("Common errors by task"),
 table(["Task","Common error","Fix"],[
  ["Read Aloud","Reading in a flat voice or with long pauses","Read in phrases; mark pauses in preparation time"],
  ["Repeat Sentence","Trying to remember every word","Remember chunks of meaning"],
  ["Describe Image","Not using the whole 40 seconds; missing numbers","Use the frame; state highest and lowest values"],
  ["Re-tell Lecture","Missing keywords","Write five nouns and numbers while listening"],
  ["SWT","More than one sentence","Use one sentence with links"],
  ["Essay","Below 200 or above 300 words; spelling errors","Plan and check the word counter"],
  ["Listening dictation","Spelling and word endings","Check each word ending"],
  ["Multiple answer","Guessing all options","Choose only what the text supports"]]),
 h("Find your biggest loss"),
 p("Look at your last three practice results. List the task types where you lost the most marks. Fix the <strong>most frequent</strong> error first."),
 take("Match each error to a specific habit and fix the most frequent one first."))
L['Timed Practice — Reading & Listening']=page(
 ["do Reading and Listening tasks under real timings","learn to move on when a task takes too long","record results so you can see progress"],
 h("Work to the clock"),
 p("Use the timing of each task as it is in the test. When time runs out, the answer is submitted, so there is no time to think again."),
 table(["Task","Time guide"],[["Reading Fill in the Blanks","about 2 minutes"],["Re-order Paragraphs","about 2-3 minutes"],["Multiple Choice","about 1-2 minutes"],["Summarize Spoken Text","10 minutes"],["Write From Dictation","one sentence, type straight after"]]),
 h("Session plan (60 minutes)"),
 ol(["20 minutes: Reading tasks, timed.","20 minutes: Listening tasks, timed.","20 minutes: mark, and log errors by task type."]),
 take("Practise under the real clock and log your errors."))
L['Timed Practice — Speaking & Writing']=page(
 ["do Speaking and Writing tasks with the real preparation and answer times","record yourself and review","practise writing SWT and Essay under time"],
 h("Speaking under time"),
 table(["Task","Prepare","Speak"],[["Read Aloud","30-40 s","up to 40 s"],["Describe Image","25 s","40 s"],["Re-tell Lecture","10 s","40 s"]]),
 p("Use a timer. Record every answer and listen to it once."),
 h("Writing under time"),
 ul(["SWT: 10 minutes, one sentence, 5-75 words.","Essay: 20 minutes, 200-300 words."]),
 h("Session plan"),
 ol(["30 minutes Speaking: six tasks, recorded.","30 minutes Writing: one SWT and one essay.","Check word counts, spelling and structure."]),
 take("Real times, recorded speaking, and word counts checked."))
L['Mastery Listening — Write From Dictation & All Types']=page(
 ["reach high accuracy on Write From Dictation","review all listening task types together","keep spelling exact under pressure"],
 h("Dictation accuracy"),
 ul(["Type in chunks; check word endings.","Practise with sentences of 8-12 words.","Use a spelling list of your own errors."]),
 h("All task types"),
 p("Mix all eight listening tasks in one session so you switch between them as you will in the test."),
 take("Exact spelling and quick switching between tasks."))
L['Mastery Reading — Accuracy Under Pressure']=page(
 ["keep accuracy while the clock runs","work out when to guess and when to think","review each item type"],
 h("Accuracy first"),
 ul(["Read the question before the text.","For each gap, check part of speech and collocation.","For Re-order, verify the final order by reading it through."]),
 h("Under pressure"),
 p("Practise with a timer set 10% shorter than the test. Then the real test feels easier."),
 take("Accuracy under a slightly tighter clock."))
L['Mastery Speaking — Perfect Pronunciation Patterns']=page(
 ["use stress and intonation patterns naturally","record and compare with a native model","polish the tasks that matter most"],
 h("Patterns"),
 ul(["Content words are stressed; function words are short.","Questions rise; statements fall.","Lists rise on each item and fall on the last."]),
 h("Shadowing"),
 p("Play a 20-second clip of a native speaker and speak along with it. Record and compare your rhythm with the clip."),
 take("Copy real rhythm and stress, and record yourself to check."))
L['Mastery Writing — Band 90 Essay Structures']=page(
 ["use a structure that fits every prompt","develop each paragraph with a reason and example","reach top scores for form, development and grammar"],
 h("A structure that fits every prompt"),
 table(["Part","Job"],[["Introduction","Paraphrase; give position"],["Body 1","Reason 1 + example"],["Body 2","Reason 2 or counter-argument + example"],["Conclusion","Restate position"]]),
 h("Check before submitting"),
 ul(["200-300 words.","One clear position.","No spelling errors.","Varied sentence length."]),
 take("A clear structure, developed ideas, and a final check."))
L['Full Exam Simulation Day 1']=page(
 ["sit the Speaking and Writing part under exam conditions","practise the transitions between tasks","review your results afterwards"],
 h("Setup"),
 ul(["Quiet room, headset, timer, no notes except what the test allows.","No pauses between tasks; do not stop to look up a word."]),
 h("After"),
 p("Write down which tasks felt slow, and which ones you missed content on."),
 take("Simulate the conditions to make the real test feel familiar."))
L['Full Exam Simulation Day 2']=page(
 ["sit the Reading and Listening parts under exam conditions","manage the time between parts","compare with Day 1"],
 h("Setup"),
 ul(["Same conditions as Day 1.","Do Reading first and then Listening."]),
 h("After"),
 p("Compare your scores and errors with Day 1. Which items improved?"),
 take("Use the two days to see the whole test and find what still needs work."))
L['AI Score Review & Final Targeted Practice']=page(
 ["read your score profile","pick the tasks that will move your score most","plan the last days"],
 h("Read your profile"),
 p("Look at each skill and each enabling skill. The lowest enabling skill often limits several main skills at once."),
 h("Targeted practice"),
 ul(["Choose two task types to improve.","Practise each one daily for 20 minutes.","Check again after three days."]),
 take("Fix the lowest enabling skill first and keep a short daily plan."))
LESSONS = L
