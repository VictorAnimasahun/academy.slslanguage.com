<?php
// /academy/includes/faq_knowledge.php
// Facts the FAQ chatbox (api/faq_chat.php) uses for questions about SLS and EduHub.
// Edit this file to change what the bot says about SLS. Only put facts here that are
// confirmed: prices, refunds and exam-date promises must come from the team, not guesses.
// Questions outside this list are still answered (general English and test help).

return [
    'contact' => [
        'phone' => '+234 706 130 9737',
        'email' => 'info@slslanguage.com',
        'facebook' => 'https://web.facebook.com/profile.php?id=61572546444418',
        'instagram' => 'https://www.instagram.com/slslanguage/',
    ],
    'facts' => [
        'SLS is an education service offering IELTS, CELPIP and PTE preparation, and study abroad guidance.',
        'EduHub is the online learning platform at academy.slslanguage.com. Students sign in there with their email and password.',
        'Courses are grouped by length: 1-month (crash course), 2-month and 3-month (masterclass). Each course is organised into weeks and classes.',
        'Free courses are available to everyone. Paid courses are bought through the course catalogue; the purchase is made on Selar.',
        'Students can preview the first class of a paid course before buying.',
        'Practice tests (timed, for each section) and full mock tests are available in EduHub.',
        'Model answers for IELTS Writing are in the Resources section of EduHub.',
        'The SLS Android app is available from the SLS website (slslanguage.com, footer: "Download Android app").',
        'Prices are shown on each course page in the catalogue and in Naira or the local currency where available. The exact amount is shown at checkout.',
        // General exam facts. Durations and scores are approximate; official sites (ielts.org, celpip.ca, pearsonpte.com) have current details and fees.
        'IELTS has four parts: Listening (30 minutes, plus 10 to transfer answers), Reading (60 minutes), Writing (60 minutes: Task 1 then Task 2) and Speaking (11 to 14 minutes, a face-to-face interview with an examiner). Scores are bands from 0 to 9, in half-band steps.',
        'IELTS comes in two versions. IELTS Academic is for university and professional registration. IELTS General Training is for migration, secondary education, training programmes and work. Listening and Speaking are the same in both; Reading and Writing differ.',
        'CELPIP (Canadian English Language Proficiency Index Program) is taken on a computer in one sitting of about 3 hours. It has Listening, Reading, Writing and Speaking. Scores are Canadian Language Benchmarks (CLB) from 1 to 12. It is widely used for Canadian immigration and citizenship applications.',
        'PTE Academic is taken on a computer in about 2 hours. It has Speaking and Writing, Reading and Listening, scored by computer from 10 to 90. Many universities and some immigration programmes accept it.',
    ],
    'faqs' => [
        ['q' => 'What is IELTS and what is in the test?', 'a' => 'IELTS tests English in four parts: Listening (about 30 minutes), Reading (60 minutes), Writing (60 minutes, two tasks) and Speaking (11 to 14 minutes, a face-to-face interview). The whole test takes about 2 hours 45 minutes. Each part gets a band from 0 to 9, and the overall score is the average of the four.'],
        ['q' => 'What is the difference between IELTS Academic and IELTS General Training?', 'a' => 'Both have the same Listening and Speaking. Academic is for university admission and professional registration. General Training is for migration to an English-speaking country, secondary school, training or work. Their Reading and Writing tasks are different, so pick the one your university, employer or visa asks for.'],
        ['q' => 'What is a band score?', 'a' => 'IELTS scores run from 0 to 9 in half-band steps (for example 6.0 or 6.5). Universities and immigration offices each set the band they require, so check that requirement before you book.'],
        ['q' => 'What is the IELTS Writing test like?', 'a' => 'Task 1 asks you to describe a chart, table, map or process in at least 150 words (about 20 minutes), or write a letter in General Training. Task 2 is an essay answering an opinion or discussion question in at least 250 words (about 40 minutes). Task 2 counts for more of the Writing score.'],
        ['q' => 'What is the IELTS Speaking test like?', 'a' => 'It is a conversation with an examiner in three parts: introduction questions about yourself, a short talk on a topic you are given (with one minute to prepare), and a deeper discussion of related ideas. It is usually 11 to 14 minutes.'],
        ['q' => 'What is CELPIP?', 'a' => 'CELPIP is an English test taken on a computer in one sitting of about 3 hours, with Listening, Reading, Writing and Speaking. Results are Canadian Language Benchmarks (CLB) from 1 to 12. CELPIP-General is used for Canadian immigration and citizenship applications. Check the current requirement with IRCC before booking.'],
        ['q' => 'What does a CELPIP test involve?', 'a' => 'Listening and Reading are on the computer with multiple-choice and short answers. Writing has two tasks: writing an email for a real situation, and giving your opinion in a survey response. Speaking is recorded on a computer, with tasks such as giving advice, describing a situation and expressing an opinion.'],
        ['q' => 'What is PTE Academic?', 'a' => 'PTE Academic is a computer-based English test of about 2 hours, with Speaking and Writing, Reading and Listening. Scores run from 10 to 90, and the computer scores the test. Many universities accept it, and some immigration programmes do too.'],
        ['q' => 'Which test should I take: IELTS, CELPIP or PTE?', 'a' => 'It depends on who needs the score. Ask the university, employer or immigration office which test and score they accept. IELTS is taken widely for study and migration, CELPIP is mainly for Canada, and PTE is computer-based and often has quicker results. SLS offers preparation for all three.'],
        ['q' => 'How long is a test score valid?', 'a' => 'IELTS and CELPIP results are generally accepted for two years from the test date, and PTE results are valid for two years as well. Many organisations ask for a recent result, so check their rule.'],
        ['q' => 'How long does it take to get results?', 'a' => 'IELTS results usually come out about 13 days after the test. PTE results are usually within about 5 business days. CELPIP results usually arrive within about 4 business days. Times vary, so confirm on the official site.'],
        ['q' => 'What do I need to bring on test day?', 'a' => 'Bring the photo identity document you used to register, exactly as it appears on your booking. Arrive early, because late arrival can mean you are not admitted. Rules differ by test and centre, so read the confirmation email from the test provider.'],
        ['q' => 'Can you tell me the exam fees or test dates?', 'a' => 'Fees and test dates differ by country and change over time, so please check the official test website or ask the team at info@slslanguage.com.'],
        ['q' => 'How do I sign in to EduHub?', 'a' => 'Go to academy.slslanguage.com and sign in with the email and password you registered with. If you have no account yet, use the registration option on the same page.'],
        ['q' => 'How do I buy a course?', 'a' => 'Open the Courses catalogue in EduHub, choose a course and select Buy. Payment is completed on Selar, then you choose the course you bought.'],
        ['q' => 'Can I try a course before paying?', 'a' => 'Yes. The first class of each paid course is free to preview.'],
        ['q' => 'Is there an app?', 'a' => 'There is an Android app for SLS. Download it from the SLS website using the "Download Android app" link in the footer.'],
        ['q' => 'Who do I contact about payments, refunds or account problems?', 'a' => 'Email info@slslanguage.com or call +234 706 130 9737. The team handles payments, refunds and account issues.'],
    ],
];
