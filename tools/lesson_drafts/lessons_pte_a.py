from lib import *
L={}
L['Introduction & PTE Academic Overview']=page(
 ["describe the three parts of PTE Academic and the tasks in each","explain how the AI scoring works and what it rewards","plan how to use this course"],
 h("The test at a glance"),
 p("PTE Academic is a computer-based test of about two hours. You answer at a computer, wearing a headset with a microphone. The test is scored by computer on a scale of <strong>10 to 90</strong>, and one task can count towards more than one skill."),
 table(["Part","What it contains","Skills scored"],[
  ["Part 1: Speaking and Writing","Personal Introduction, Read Aloud, Repeat Sentence, Describe Image, Re-tell Lecture, Answer Short Question, Summarize Written Text, Essay","Speaking, Writing"],
  ["Part 2: Reading","Fill in the Blanks (two kinds), Multiple Choice (two kinds), Re-order Paragraphs","Reading, Writing"],
  ["Part 3: Listening","Summarize Spoken Text, Multiple Choice (two kinds), Fill in the Blanks, Highlight Correct Summary, Select Missing Word, Highlight Incorrect Words, Write from Dictation","Listening, Writing"]]),
 h("Enabling skills"),
 p("Besides the four main skills, the computer also scores <strong>enabling skills</strong>: grammar, oral fluency, pronunciation, spelling, vocabulary and written discourse. They are reported separately and they feed into the main skills. A single Read Aloud answer, for example, gives marks for reading, speaking, oral fluency and pronunciation."),
 h("What the computer can and cannot judge"),
 ul(["It can hear <strong>clarity</strong>, speed and rhythm, not accent. A clear accent is fine.","It counts <strong>content words</strong> you say in the right order in Repeat Sentence and Re-tell Lecture.","It checks spelling, grammar, structure and length in writing.","It does not reward memorised templates that ignore the question."]),
 warn("Task time and word limits matter. An essay under 200 words or over 300, or a summary that is not a single sentence, loses marks even if the writing is good."),
 h("How this course works"),
 ul(["Each week teaches two or three task types, then practises them under time.","Use the practice tests to find your weak task types. The AI review lesson later in the course looks at your scores.","Speak aloud and record yourself every day. Speaking tasks are where most candidates lose marks."]),
 take("Learn the tasks, know what the AI rewards, and practise under real timings from the first week."))
L['Speaking — Read Aloud & Repeat Sentence']=page(
 ["read a text aloud with natural stress and pauses","repeat a sentence exactly after hearing it once","avoid the habits that lower oral fluency and pronunciation scores"],
 h("Read Aloud"),
 p("A short text (up to about 60 words) appears on the screen. You have around 30-40 seconds to prepare, then you read it into the microphone. It is scored on <strong>content</strong> (do you say every word), <strong>oral fluency</strong> and <strong>pronunciation</strong>."),
 ul(["Use the preparation time to <strong>read the whole text silently</strong> and mark the pauses (commas, ends of phrases).","Speak at a steady, natural speed: not fast, not word by word.","If you make a mistake, <strong>keep going</strong>. Stopping and starting again hurts fluency more than the error.","Stress content words (nouns, verbs, adjectives). Small words like <em>the, of, to</em> are said quickly."]),
 h("Repeat Sentence"),
 p("You hear a sentence of about 3-9 seconds, once. Then you repeat it. It is scored on content, fluency and pronunciation. You get a mark for each word you say correctly in the right order."),
 ul(["Listen for <strong>meaning</strong>, not for each word: chunks of meaning are easier to remember.","Do not take notes; repeat straight away.","If you forget a word, keep going with a natural rhythm. A missing word costs less than a long pause."]),
 h("Habits that lower scores"),
 table(["Habit","Fix"],[["Long silence at the start or in the middle (the recording may end after about three seconds of silence)","Start within two seconds of the beep; use short pauses at commas"],["Rushing to finish","Practise at a pace where every word is clear"],["Reading in a flat voice","Let your voice rise and fall with meaning"],["Adding words","Say exactly what you hear or read"]]),
 h("Daily drill (15 minutes)"),
 ol(["Record yourself reading any short English paragraph.","Listen: mark places where you paused or tripped.","Read it again, fixing only those places.","Listen to a short news clip, pause it, and repeat what you heard."]),
 take("Read in phrases, keep moving after a slip, and repeat by meaning. Smooth and clear scores better than fast."))
L['Speaking — Describe Image & Re-tell Lecture']=page(
 ["describe a graph, chart, map or picture in 40 seconds with a clear structure","re-tell a short lecture using its key points","use a template as a frame without sounding rehearsed"],
 h("Describe Image"),
 p("An image (bar chart, line graph, pie chart, table, map, process or picture) appears. You get about <strong>25 seconds to prepare</strong> and <strong>40 seconds to speak</strong>. The score is based on content, oral fluency and pronunciation."),
 h("A four-part frame"),
 ol(["<strong>Introduce:</strong> what the image shows (<em>This graph shows the number of ... between ... and ...</em>).","<strong>Highest and lowest / main feature:</strong> the biggest, smallest or most striking thing, with numbers.","<strong>Trend or comparison:</strong> how things change or compare.","<strong>Conclude:</strong> one closing sentence (<em>Overall, ...</em>)."]),
 tip("Use your 25 seconds to find the title, the units, the highest and the lowest values, and the trend. Say numbers accurately."),
 h("Re-tell Lecture"),
 p("You hear a lecture of up to about 90 seconds and may see an image. You have about 10 seconds to prepare and 40 seconds to speak."),
 ul(["Take <strong>keyword notes</strong> while it plays: the topic, two or three main points, one example, one conclusion.","Speak in order: <em>The lecture is about ... The speaker says ... Another important point is ... Finally / In conclusion ...</em>","Include the key nouns and numbers you heard. Content words count."]),
 warn("A template is a frame, not a script. If the words you memorised do not fit the image, the content score falls. Fill the frame with what is in front of you."),
 h("Practice"),
 ul(["Take any graph from a news website. Describe it aloud in 40 seconds and time yourself.","Watch a 90-second talk, write five keywords, then re-tell it."]),
 take("Have a frame, find the numbers in the preparation time, and speak for the whole 40 seconds."))
L['Writing — Summarize Written Text & Essay']=page(
 ["write a one-sentence summary of a passage in 5-75 words","write a 200-300 word essay with clear structure","meet the form requirements that the computer checks"],
 h("Summarize Written Text (SWT)"),
 p("You read a passage of up to about 300 words and write <strong>one sentence</strong> of 5 to 75 words that summarises it. You have 10 minutes. It is scored on content, form, grammar and vocabulary."),
 ul(["<strong>One sentence only</strong>, ending with a single full stop. Use a semicolon or a linking word (<em>although, which, while</em>) to join ideas.","Include the main idea and two or three key supporting points.","Do not copy long strings from the passage; paraphrase where you can.","Aim for about 50-70 words. Check the word count under the box."]),
 ex("Pattern","<p><em>Although [main topic] is [description], [main point 1], [main point 2], and [conclusion or result].</em></p>"),
 h("Essay"),
 p("You write an essay of <strong>200 to 300 words</strong> in 20 minutes on a short prompt. It is scored on content, form, development and structure, grammar, vocabulary and spelling."),
 table(["Paragraph","Content","Length"],[["Introduction","Paraphrase the question and state your view","30-40 words"],["Body 1","Main reason with an example","70-80 words"],["Body 2","Second reason or the opposing view, with an example","70-80 words"],["Conclusion","Restate your position","25-30 words"]]),
 warn("Under 200 or over 300 words loses marks on form. Check the word counter before you submit, and leave two minutes for spelling."),
 h("Timing plan"),
 p("Essay: 3 minutes planning, 14 minutes writing, 3 minutes checking. SWT: 2 minutes reading, 6 minutes writing, 2 minutes checking."),
 take("Summary: one sentence, right length, main ideas. Essay: four paragraphs, 200-300 words, checked for spelling."))
L['Reading — Multiple Choice, Re-order & Fill in Blanks']=page(
 ["recognise the five Reading task types and how each is scored","re-order paragraphs by logic and linking words","choose the right word in fill-in-the-blank tasks by grammar and collocation"],
 h("The five task types"),
 table(["Task","What you do","Scoring"],[
  ["Reading &amp; Writing: Fill in the Blanks","Choose a word from a drop-down list for each gap in a text","1 mark per correct gap"],
  ["Multiple Choice, multiple answers","Choose all correct answers","Marks for correct, penalty for wrong"],
  ["Re-order Paragraphs","Drag paragraphs into the right order","Marks for each correct adjacent pair"],
  ["Reading: Fill in the Blanks","Drag words from a box into the gaps","1 mark per correct gap"],
  ["Multiple Choice, single answer","Choose one answer","1 mark"]]),
 h("Re-order Paragraphs"),
 ol(["Find the <strong>opening sentence</strong>: it introduces the topic and has no reference back (<em>this, they, however</em>).","Look for <strong>pronouns and linking words</strong> that connect to the sentence before.","Look for chronology (dates), cause and effect, and a general-to-specific order.","Build pairs first, then fit the pairs together."]),
 h("Fill in the Blanks"),
 ul(["Decide the part of speech the gap needs before you look at the options.","Check <strong>collocation</strong> (which words go together) and grammar (singular/plural, tense).","Read the sentence with your answer in it before you move on."]),
 warn("In multiple-answer questions, wrong answers cost points. Choose only the answers you can support from the text."),
 take("Learn the five formats, use linking words for re-ordering, and choose blanks by grammar and collocation."))
L['Listening — Summarize Spoken Text & MCQ']=page(
 ["write a 50-70 word summary of a spoken talk","take notes that help, not slow you down","answer multiple-choice listening tasks accurately"],
 h("Summarize Spoken Text (SST)"),
 p("You hear a recording of about 60-90 seconds and write a summary of <strong>50 to 70 words</strong> in 10 minutes. It is scored on content, form, grammar, vocabulary and spelling."),
 h("Note-taking"),
 ul(["Write <strong>keywords only</strong>: nouns, numbers and names. Never full sentences.","Use short forms (&amp;, &gt;, -&gt;, ↑, ↓).","Split your page: <em>topic</em>, <em>main points</em>, <em>conclusion</em>."]),
 h("Writing the summary"),
 ol(["Sentence 1: topic and speaker's main idea.","Sentences 2-3: two or three key points.","Sentence 4: conclusion or result."]),
 tip("Stay inside 50-70 words. Under 40 or over 100 can score zero for form."),
 h("Multiple Choice in Listening"),
 ul(["Read the question before the recording starts, and mark the key words.","In multiple-answer tasks, choose only what the speaker clearly says; wrong choices lose marks.","Distractors use words from the talk but change the meaning. Match the meaning, not the words."]),
 take("Take keyword notes, write 50-70 words in a clear order, and choose answers by meaning."))
LESSONS = L
