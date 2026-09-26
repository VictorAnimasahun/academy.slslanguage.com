from lib import *
L={}
L['Test-Day Coaching']=page(
 ["know what happens on test day from arrival to the last section","have a plan for the night before and the morning of the test","handle nerves, and know what to do when something goes wrong"],
 h("Before test day"),
 ul(["Check your <strong>booking confirmation</strong>: date, time, address, and the identification the centre requires. Bring exactly that document, and make sure the name on it matches your booking.","Visit the route or plan the travel so that you arrive at least 30 minutes early. Nobody is let in late.","Pack a clear water bottle if allowed and check the centre's rules on what you may carry into the room. Phones, watches and notes are normally not allowed."]),
 h("The order of the test"),
 table(["Section","Time","Notes"],[["Listening","about 30 minutes plus 10 minutes to transfer answers (paper) or 2 minutes to check (computer)","Recording played once"],["Reading","60 minutes","No extra transfer time on paper"],["Writing","60 minutes","Task 1 (20 min) then Task 2 (40 min)"],["Speaking","11-14 minutes","Often on the same day, or up to a week before or after"]]),
 warn("Confirm the format you are sitting (paper or computer) and where your Speaking test is. Check timings against your booking confirmation, because centres differ."),
 h("The night before"),
 ul(["Review only: your error log, word list, and one model answer for each Writing task.","Do not take a full mock or learn new material.","Prepare your documents and clothes. Sleep."]),
 h("On the day"),
 ol(["Eat something light. Do a five-minute warm-up: say a few sentences aloud in English.","Arrive early, be calm, follow every instruction from the invigilator.","In Listening use the preview time to underline keywords.","In Reading keep to 20 minutes per passage.","In Writing spend 2-5 minutes planning each task."]),
 h("If something goes wrong"),
 ul(["Missed an answer in Listening: leave it, write a guess when you can, and listen to the next question.","Ran out of time in Reading: fill every blank; there is no penalty.","Equipment or noise problem: tell the invigilator immediately, not after the section.","Feeling anxious: breathe out slowly for a count of six and read the next question. One weak section does not decide your result."]),
 take("Know your logistics, sleep, keep to your time plan, and speak up straight away if something is wrong."))
L['AI Scoring Strategies & Test-Day Preparation']=page(
 ["explain how the computer scores each PTE task","use that knowledge to choose habits that raise your score","prepare for the test-day process at the centre"],
 h("How scoring works"),
 p("PTE is scored by computer. One answer can count towards several skills, so a habit like <em>speak in smooth phrases</em> improves Speaking, oral fluency and pronunciation together. The report shows a score from 10 to 90 for Speaking, Writing, Reading and Listening and for six enabling skills: grammar, oral fluency, pronunciation, spelling, vocabulary and written discourse."),
 h("Strategies that follow from that"),
 table(["The computer measures","So you should"],[
  ["Content words spoken in the right order","Repeat and re-tell using the key nouns and verbs you hear"],
  ["Oral fluency: rhythm, few hesitations","Speak in phrases with short pauses; do not restart after a mistake"],
  ["Pronunciation: clear sounds and stress","Stress content words and say word endings"],
  ["Length and form in writing","Keep to 5-75 words (one sentence) for SWT and 200-300 words for the essay"],
  ["Spelling","Check every word; keep one spelling style (British or American)"],
  ["Written discourse","One idea per paragraph with clear links"]]),
 warn("The computer does not reward memorised templates that ignore the question. Use a frame, then fill it with the actual content."),
 h("Test-day preparation"),
 ul(["Book your test and check the ID rules of your test centre, since you need the ID that matches your booking.","Expect a check-in with a photo, a palm-vein or fingerprint scan and a signature, done by the centre.","Practise wearing the headset and speaking at a steady, moderate volume; the microphone picks up other candidates when you are too quiet.","You get a noteboard: practise your note-taking format (topic, main points, numbers).","Do not stop between tasks. If a task goes badly, forget it and start the next one fresh."]),
 h("The last day"),
 p("Review your error list and speak aloud for ten minutes. Do not try a full simulation. Sleep well."),
 take("Know what the AI measures, build the habits that raise several scores at once, and arrive prepared for the centre routine."))
LESSONS = L
