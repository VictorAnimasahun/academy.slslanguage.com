<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

$test_num = isset($_GET['test']) ? intval($_GET['test']) : 0;
if ($test_num < 1 || $test_num > 4) {
    header("Location: model_answers.php");
    exit();
}

$all_tests = [
    1 => [
        'label' => 'Test 1',
        'tasks' => [
            [
                'id' => 1, 'task_num' => 1, 'title' => 'Task 1: Letter to Mrs Barrett',
                'type' => 'General Training Letter', 'band' => '5.5',
                'question' => "You have recently seen an advertisement in which Mrs Barrett, who lives near you, says she needs help in her home. You think this is a job you could do.\n\nWrite a letter to Mrs Barrett. In your letter:\n• suggest how you could help her at home\n• explain why you think you would be suitable for the work\n• say when you would be available\n\nWrite at least 150 words.",
                'answer' => "Good morning Mrs Barrett. My name is Alfonso Jose Suaza I'm from Colombia. I saw your advertised that you are looking for someone to help you in your home for a few hours a day next summer. Also I'm really interested to get this opportunity. A few years ago I was working as a cook in a Hotel, so I can help you in domestic task in your house and made your meals for breakfast, lunch and dinner. Forthemore I enjoyed to work in the hospitality and the cookery is my passion I love making food for everyone this make me happy. Actually I'm am international student, so I go to the school in southport at the afternoon Monday to Friday from 1:30 till 5:30, and free on the weekend, I'm able in the mornings and weekends. Thanks for your time\n\nKind regrets,\n\nDear Mrs Barrett",
                'comment' => "The writer has addressed each of the bullet points, although more details could have been given, as they only mention cooking and no other ways to help. However, there is a clear progression which follows the order of the bullet points in the question. We can see the format is appropriate for a letter but there are errors at the close [Kind regrets | Dear Mrs Barrett].\n\nVocabulary is generally adequate for the task [opportunity | cookery is my passion | make me happy] although some is taken from the question and there are errors in word choice [able / available | regrets / regards]. There are some attempts at complex structures (using relative pronouns) but the range is limited. The Band Score could be improved if spelling and word choice were more accurate and if there was more accuracy in sentence structures.",
            ],
            [
                'id' => 2, 'task_num' => 2, 'title' => 'Task 2: Plastic Pollution Essay',
                'type' => 'Discussion Essay', 'band' => '7.0',
                'question' => "Plastic waste is damaging our environment. Some people think that individuals should take responsibility for reducing the damage. Others believe that governments and large companies should take action instead.\n\nDiscuss both these views and give your own opinion.\n\nWrite at least 250 words.",
                'answer' => "Nowadays, our environment suffers from plastic packaging and plastic bags, plastic does a great damage to the planet. In this essay, the actions how to solve the plastic problem will be mentioned and what can be done to lower the use of plastic packaging will be discussed.\n\nFirstly, plastic products are usually thrown under the ground, so that it is able to cause ground pollution, the soil becomes poor. It is one of the reasons why trees and plants do not grow as they used to fifty years ago. Secondly, production of plastic causes air pollution. Factories that produce plastic bags, packaging and other plastic products emit exhaust fumes to the atmosphere. All of the facts mentioned can prove that plastic pollution plays the leading role in the list of environmental problems.\n\nWhat pollution abutement can be done to lower the risk of Earth damaging? First, plastic bags can be changed to bags made of natural materials like clothes of wood. To realize this, factories should lower the production of plastic bags and change them to more natural products. Moreover, the problem of ground pollution because of plastic production should be discussed in social media to let people know the scope of plastic problem. Second, already used plastic can be recycled in other products. There is a Russian programm of recycling plastic into T-shirts, caps and even balls for football and basketball.\n\nIn conclusion, I would like to say that, nowadays, people appear as the main reason of environmental problems, the manufacturing of plastic products increases the risk of the further Earth life. Everything should be done to lower the risk of destroying the planet.",
                'comment' => "This is a strong response which addresses all parts of the task. It has an introduction to the topic, refers to ground and air pollution and presents solutions to address the problems identified. To score more highly, there could be a clearer outline of what 'governments' and 'individuals' can do. Ideas are logically organised into four paragraphs, including an introduction and a conclusion. Within the paragraphs, we can see sequencing [Firstly | Secondly] and other cohesive devices [Moreover | In conclusion], as well as some effective referencing [All of the facts mentioned | change them].\n\nThere is a wide range of vocabulary and evidence of the ability to convey precise meanings [exhaust fumes | leading role | scope | risk of destroying the planet]. There are occasional errors in spelling [emmit / emit | abutement / abatement | programm / programme] but generally good control over word choice.\n\nThe writing also shows a variety of complex grammatical structures with frequent error-free sentences. We can see a range of tenses, including modal forms [can prove that | should be done]. There are a few errors remaining, e.g., with articles [plastic does a great damage | scope of plastic problem] and prepositions [main reason of | main reason for].",
            ],
        ],
    ],
    2 => [
        'label' => 'Test 2',
        'tasks' => [
            [
                'id' => 3, 'task_num' => 1, 'title' => 'Task 1: Letter About Town Centres',
                'type' => 'General Training Letter', 'band' => '6.5',
                'question' => "You have just read an article in a national newspaper which claims that town centres in your country all look very similar to each other. You don't fully agree with this opinion.\n\nWrite a letter to the editor of the newspaper. In your letter:\n• say which points in the article you agree with\n• explain ways in which your town centre is different from most other town centres\n• offer to give a guided tour of your town to the writer of the article\n\nWrite at least 150 words.",
                'answer' => "Dear Sir or Madam,\n\nI am writing you because I would like to give you my opinion on your article about town centres which you published in a national newspaper called Denik.\n\nFirst of all I have to agree with the part where you mentioned a shape and an organization of main squares. Most of them have a rounded shape with lots of different shops around and to a fountain in the center.\n\nHowever, I found the center of Brno quite different from most of other cities. Mostly because it has nice older architecture combined with modern style. Brno has also one unique difference that is that a tram goes right across of the middle of the main square. Also the main train station together with tram station is placed right under the main square, which is not so common in other cities.\n\nAt the end, I would like to offer a tour in Brno in hope that it could change your opinion. If you decide to visit my town, please don't hesitate to contact me. I will be more than happy to show you the beauty of this city.\n\nYours faithfully,",
                'comment' => "All three bullet points are addressed and the tone is polite in this letter to a newspaper. In this task, the bullet points ask 'which points / explain ways' (plural) which means more than one point or example is required per bullet point. In this response, two examples are provided for each of the first two bullet points, satisfying the requirements for this task.\n\nProgression is clear and cohesive devices are used well [which you published | Most of them | that is that]. There are some inaccuracies [At the end / finally]. The range of vocabulary does allow some flexibility [mentioned | architecture combined with modern style | unique difference | not so common | change your opinion]. However, a wider range would be needed to score more highly here. Similarly, complex sentence forms are attempted, but generally sentences are short and flexibility is limited in this response.\n\nNote: It is important for test takers to notice if more than one example is required for each bullet point (e.g., problems, points, ways, examples) because if only one is provided, this is not a complete response to the question.",
            ],
            [
                'id' => 4, 'task_num' => 2, 'title' => 'Task 2: Keeping to Routines Essay',
                'type' => 'Discussion Essay', 'band' => '5.5',
                'question' => "Some people like to try new things, for example, places to visit and types of food. Other people prefer to keep doing things they are familiar with.\n\nDiscuss both these attitudes and give your own opinion.\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.",
                'answer' => "Some people prefer to keep to things that they can or use to. I think it has good side and bad side.\n\nFirst of all, keep to do same thing make you forcus to things. You can learn and enjoy deeply. Also, keep to do same thing makes you comfortable. You can relax to do it. If you have bad day, you don't need to look for what makes you relax because you already used to do it. For example, you can watch film, visit place, eat food whatever you like or used to. You can go back your routines.\n\nOn the other hand, keep to do same thing is avoid your charenge. When you try something that you don't like or new, it makes you uncomfortable. When you face things that you don't understand well. It must be scared and uncomfortable and then you have two choices. Charenge or escape. You don't need to always choose charenge, but you have to ask yourself, is escape good for you?\n\nYou need to have confortable time or things like hobbies. It must be good for you. But sometime you have to try to put yourself in unconfortable. It makes you improve and it brings knowledge.",
                'comment' => "This response does address the requirements of the question. There are relevant main ideas and an opinion in the concluding paragraph. However, the response is quite repetitive [keep to do same thing] and cohesion is faulty.\n\nVocabulary is generally adequate for the task, but there is a limited range with repeated errors in spelling [unconfortable / uncomfortable | charenge / challenge]. There are some attempts at complex sentence structures [You don't need to always choose ... but]; otherwise, sentences are short, indicating a lack of complexity. The level of error is significant in this response, including continuous structures [keep to do / keep doing | you already used to do it / you are already used to doing it].\n\nThis response is below the minimum word count for Task 2. The overall score could be improved if spelling and word choice were more accurate and if there was a wider variety in sentence structures.",
            ],
        ],
    ],
    3 => [
        'label' => 'Test 3',
        'tasks' => [
            [
                'id' => 5, 'task_num' => 1, 'title' => 'Task 1: Letter About an Influential Book',
                'type' => 'General Training Letter', 'band' => '7.0',
                'question' => "A magazine wants to include contributions from its readers for an article called 'The book that influenced me most'.\n\nWrite a letter to the editor of the magazine about the book that influenced you most. In your letter:\n• describe what this book was about\n• explain how this book influenced you\n• say whether this book would be likely to influence other people\n\nWrite at least 150 words.",
                'answer' => "Dear Sir or Madam,\n\nI am writing you in response to the article 'the book the influenced me most' printed in the last issue of your magazine, as it was stated there that you would be waiting for readers' contributions. Having noticed this, I couldn't have failed taking an opportunity to share my experience.\n\nSo, the book that literally turned my world upside down is called \"Nina\", by an italian author writing under the pen name of Moony Witcher. The book was about a girl who suddenly found out that her grandfather had not only been a great alchemist but had also kept saving the world from an evil mage. And it was her turn to take up his task and save all the children's dreams and fantasies from complete extinction.\n\nI was twelve when I cracked this book open and I was completely lost in an interesting plot and a breathtaking philosophy concerning Good and Evil, Creation and Destruction, Everything and Nothing. This book had a great influence on me as a writer, it encouraged me to try writing fantasy stories and made me a person that I am now.\n\nI would advise reading this book to everyone from age 8 to 16, as it is sure to be very beneficial for children's upbringing and to teach them some good things in a very interesting way.\n\nBest regards,",
                'comment' => "This response addresses all the requirements of the question. There is a good description of what the book was about and a clear idea of how it influenced the writer. To improve the score, more detail could be added to the final bullet point. The response is organised clearly into paragraphs and the cohesive devices make it easy to follow.\n\nVocabulary is strong, there are some colloquial [cracked ... open] and higher-level items [alchemist | fantasies | philosophy]. There are also some good examples of complex grammatical structures but a few errors remain [writing you / writing to you | a person / the person].",
            ],
            [
                'id' => 6, 'task_num' => 2, 'title' => 'Task 2: Living Close to Birthplace Essay',
                'type' => 'Advantages/Disadvantages Essay', 'band' => '6.0',
                'question' => "Some people spend most of their lives living close to where they were born.\n\nWhat might be the reasons for this?\nWhat are the advantages and disadvantages?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.",
                'answer' => "It is very common for many people to spend most of their lives living in the same city they were born. There are many reasons for that, but in my opinion there are more pros then cons to live in the same city.\n\nSome advantages to live in the same city that you were born are you will be close to your family and that means you can have more support for them, for example, emotional or financial support or even advices. Another good point is you will see your nephews grow up and more important than that, you could help to take care of your parents when they get older. What is more you might have a lot of friends and if you live in the same city it is very easy to keep contact with them. Also, you will know the city, where it is good to have fun, where it is safe etc.\n\nIn the disadvantages side, if you live in the same city where were born you probably will lost the opportunity to meet people from another city. Also living alone far of your family you will grow up and be more responsible. What is more, you can have more jobs opportunities, because you can try to find a job in any city. Also you will have more adventures.\n\nEven with some disadvantages, I think there are much more advantages to live in the same city that you were born.",
                'comment' => "After the introduction, this response moves immediately into the advantages and disadvantages. This means there is no real reference to the first point, the 'reasons'. If the candidate had included the 'reasons', this response would have scored more highly.\n\nParagraphing has been used to organise the ideas in this response. There is an introduction, a conclusion, one paragraph for advantages and another for disadvantages. Some effective cohesive devices are used, but overall, the response is a little repetitive [Also].\n\nVocabulary is appropriate but the range is restricted, and sentences are generally short which limits the score for grammatical structures. Although the candidate is able to use a range of clauses [if you live | because], this is with some error.",
            ],
        ],
    ],
    4 => [
        'label' => 'Test 4',
        'tasks' => [
            [
                'id' => 7, 'task_num' => 1, 'title' => 'Task 1: Email About Student Accommodation',
                'type' => 'General Training Email', 'band' => '5.0',
                'question' => "Your friend has been offered a place on a course at the university where you studied. He/She would like your advice about finding a place to live.\n\nWrite an email to your friend. In your email:\n• describe where you lived when you were a student at the university\n• recommend the best way for him/her to look for accommodation\n• warn him/her of mistakes students make when choosing accommodation\n\nWrite at least 150 words.",
                'answer' => "Dear Lisa,\n\nI am so glad to help you to look for accommodation where is near my university. When I was at the university, I had been lived in an apartment for 4 years, which is offered to students or others from my school. It only takes 5 minutes on foot to the main gate of school, and it just takes 10 minutes on foot to the bus stop or train station. There has very convinent transport around my apartment. By the way, if you search apartment online, you should go to double check the information at reception. Because they might not update the information on time. So I advise you go to see the teacher who is in charge of accommodation at first. It will be saving more your time and energy.\n\nWhat's more, when you are choosing accomdation, there are two suggestions for you:\n\nFirst, please check the date that you are going to school. Is it any avilable accomadation for you at that date.\n\nIn addition, please make sure how long will you stay there. You are not able to get a refund even if you move out the accomdation earlier. So check how long is available you can stay.\n\nAt last, hopefully you can find a suitable accomodation soon and enjoy the life at university.\n\nFriendly,",
                'comment' => "This is an attempt to provide advice on finding a place to live. All bullet points are touched on but not clearly presented, especially the third bullet point; there is no indication that this is a typical mistake students make. The format is appropriate for a letter despite the use of [Friendly] as a closing. The response presents the bullet points in the same order as the question. There is some good use of cohesive devices [which | who], but most are quite basic [Because | So | In addition] and faulty cohesion results in some repetition [on foot | accommodation].\n\nThe range of vocabulary is just adequate for the task but there are errors in spelling, even with the same word [accomdation | accomadation | accomodation]. There are some attempts at complex sentence structures [even if], but there are frequent errors in the use of articles and tenses [had been lived | It will be saving more your time], including the use of present tenses to talk about where they 'studied' in the past. These errors do cause some difficulty for the reader.\n\nThe Band Score could be improved if spelling and word choice were more accurate and if there was more accuracy in sentence structures.",
            ],
            [
                'id' => 8, 'task_num' => 2, 'title' => 'Task 2: Best Time in History Essay',
                'type' => 'Opinion Essay', 'band' => '7.0',
                'question' => "Some people say that now is the best time in history to be living.\n\nWhat is your opinion about this?\nWhat other time in history would be interesting to live in?\n\nGive reasons for your answer and include any relevant examples from your own knowledge or experience.\nWrite at least 250 words.",
                'answer' => "I personally agree with those who claim that the present days are the best period in the history of mankind to be living.\n\nBreakthroughs in science and advancements in technology have dramatically improved our standards of living, at least in the Western world, making life actually worth living. Advances in medical treatments and the invention of new drugs all have allowed us to live long and healthy lives. We no longer face the problems of food and water scarcity, thanks to new agricultural techniques that allow farmers to produce larger quantities of food. Everyone, no matter its race and religion, can receive a proper education and make a decent living out of a good job. We should not also undervalue things such as the freedom of speech, which has not always been guaranteed in the past.\n\nHowever, if I had the chance to choose an other time in our history to live in, I would opt for ancient Rome. I think it would be interesting to try living in a society whose beliefs and values significantly differs from our. In today's world, the most valuable personal \"qualities\" are selfishness and greed. We tend to put ourselves before other, whereas, in my opinion, things were different for the ancient civilizations, for wich the society as a whole came first.\n\nIn the end, I think we could learn some very interesting lessons from our past, without having to sacrifice all our the efforts made to get where we are today.",
                'comment' => "This is a well-developed response which presents a range of evidence to justify the opinion expressed, including improvements in science, technology, medical treatments, agriculture, education, employment opportunities and freedom of speech. There is also a clear rationale for the 'other' time in history that would be interesting to live in.\n\nIdeas are arranged logically with one paragraph exploring each part of the question. However, we cannot say that paragraphing is used appropriately.\n\nThe first and last paragraphs have only one sentence, and it is not clear if the final sentence is a new paragraph. The overall score of this response would be improved with an appropriate introductory and concluding paragraph. Other aspects of cohesion are good [those who claim that | whose beliefs | to get where we are today] with some slips [its race / their race | our / ours].\n\nThe use of vocabulary is precise [Breakthroughs in science and advancements in technology | making life ... worth living | freedom of speech | ancient civilizations] despite a few slips [an other / another | wich / which] and the range of grammatical structures is wide with frequent examples of error-free complex sentences. There are a few slips, e.g., third-person agreement [differs / differ] and plural ending [before other / before others], but overall there is good control.",
            ],
        ],
    ],
];

$test = $all_tests[$test_num];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Writing — <?php echo $test['label']; ?> | EduHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <?php include INCLUDES_PATH . '/navbar_styles.php'; ?>
    <style>
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.85rem; color: #0b77ff; text-decoration: none;
            margin-bottom: 1.5rem;
        }
        .back-link:hover { text-decoration: underline; color: #0b77ff; }

        .task-card {
            background: #fff; border-radius: 14px;
            box-shadow: 0 2px 10px rgba(15,23,42,0.07);
            margin-bottom: 1.5rem; overflow: hidden;
        }
        .task-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.4rem;
            color: #fff;
        }
        .task-header.blue { background: #0b77ff; }
        .task-header.pink { background: #ec4899; }
        .task-header-left { display: flex; align-items: center; gap: 0.75rem; }
        .task-badge {
            width: 30px; height: 30px; border-radius: 50%;
            background: rgba(255,255,255,0.25);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
        }
        .task-title { font-weight: 600; font-size: 1rem; }
        .task-type  { font-size: 0.78rem; opacity: 0.85; margin-top: 0.1rem; }
        .band-pill {
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
            border-radius: 20px; padding: 0.2rem 0.75rem;
            font-weight: 700; font-size: 0.9rem; white-space: nowrap;
        }

        .task-body { padding: 1.4rem; }
        .question-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8; margin-bottom: 0.6rem;
        }
        .question-text {
            white-space: pre-line; line-height: 1.65; color: #334155;
            font-size: 0.93rem; margin-bottom: 1.2rem;
        }
        .btn-view-blue {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #0b77ff; color: #fff; border: none;
            padding: 0.55rem 1.2rem; border-radius: 8px;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-view-blue:hover { background: #0062d6; color: #fff; }
        .btn-view-pink {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: #ec4899; color: #fff; border: none;
            padding: 0.55rem 1.2rem; border-radius: 8px;
            font-weight: 600; font-size: 0.88rem; cursor: pointer;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-view-pink:hover { background: #db2777; color: #fff; }

        .modal-passage { white-space: pre-line; line-height: 1.65; margin-bottom: 0; }
        .modal-section-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8; margin-bottom: 0.4rem;
        }
    </style>
</head>
<body class="light">
    <?php include INCLUDES_PATH . '/mobile_header.php'; ?>
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <?php include INCLUDES_PATH . '/navbar.php'; ?>

    <div class="main-wrapper flex-grow-1" style="flex:1;">
        <?php include INCLUDES_PATH . '/topbar.php'; ?>

        <main class="content p-4">
            <div style="max-width:720px;">

                <a href="model_answers.php" class="back-link">
                    <i class="bi bi-arrow-left"></i> Model Answers
                </a>

                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:0.2rem;">
                    IELTS Writing — <?php echo htmlspecialchars($test['label']); ?>
                </h1>
                <p class="text-muted mb-4" style="font-size:0.9rem;">Cambridge IELTS 16 General Training · Sample answers with examiner feedback</p>

                <?php
                $colors = ['blue', 'pink'];
                foreach ($test['tasks'] as $i => $task):
                    $color = $colors[$i % 2];
                    $btn_class = 'btn-view-' . $color;
                ?>
                    <div class="task-card">
                        <div class="task-header <?php echo $color; ?>">
                            <div class="task-header-left">
                                <div class="task-badge"><?php echo $task['task_num']; ?></div>
                                <div>
                                    <div class="task-title"><?php echo htmlspecialchars($task['title']); ?></div>
                                    <div class="task-type"><?php echo htmlspecialchars($task['type']); ?></div>
                                </div>
                            </div>
                            <div class="band-pill">Band <?php echo htmlspecialchars($task['band']); ?></div>
                        </div>
                        <div class="task-body">
                            <div class="question-label">Question</div>
                            <div class="question-text"><?php echo htmlspecialchars($task['question']); ?></div>
                            <button class="<?php echo $btn_class; ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modal-<?php echo $task['id']; ?>">
                                <i class="bi bi-file-text"></i> Read Sample Answer
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="modal-<?php echo $task['id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header" style="background:<?php echo $color === 'blue' ? '#0b77ff' : '#ec4899'; ?>;color:#fff;">
                                    <h5 class="modal-title">
                                        <?php echo htmlspecialchars($task['title']); ?>
                                        &nbsp;<span style="opacity:0.85;font-size:0.9rem;">Band <?php echo htmlspecialchars($task['band']); ?></span>
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="font-size:0.93rem;">
                                    <div class="modal-section-label">Sample Answer</div>
                                    <p class="modal-passage"><?php echo htmlspecialchars($task['answer']); ?></p>
                                    <hr>
                                    <div class="modal-section-label">Examiner's Comment</div>
                                    <p class="modal-passage"><?php echo htmlspecialchars($task['comment']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>
        </main>
    </div>

    <?php include INCLUDES_PATH . '/adverts.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include INCLUDES_PATH . '/navbar_scripts.php'; ?>
    <?php include INCLUDES_PATH . '/footer.php'; ?>
</body>
</html>
