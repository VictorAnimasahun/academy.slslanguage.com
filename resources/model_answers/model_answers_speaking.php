<?php
require_once dirname(dirname(__DIR__)) . '/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../edu_hub_registration.php?message=Please+login+to+access+resources");
    exit();
}

// Real transcript from official IELTS.org Speaking sample task (2026-09-17).
// Speaking is content-neutral between Academic and General Training.
$parts = [
    1 => [
        'label' => 'Part 1 – Introduction & Interview',
        'exchanges' => [
            ['q' => "Let's talk about your home town or village. What kind of place is it?",
             'a' => "It's quite a small village, about 20km from Zurich. And it's very quiet. And we have only little ... two little shops because most of the people work in Zurich or are orientated to the city."],
            ['q' => "What's the most interesting part of this place ... village?",
             'a' => "On the top of a hill we have a little castle which is very old and quite well known in Switzerland."],
            ['q' => 'What kind of jobs do people in the village do?',
             'a' => "We have some farmers in the village as well as people who work in Zurich as bankers or journalists or there are also teachers and some doctors, some medicines."],
            ['q' => "Would you say it's a good place to live?",
             'a' => "Yes. Although it is very quiet, it is … people are friendly and I would say it is a good place to live there, yes."],
        ],
    ],
    2 => [
        'label' => 'Part 2 – Individual Long Turn',
        'cue_card' => "Describe something you own which is very important to you.\n\nYou should say:\n• where you got it from\n• how long you have had it\n• what you use it for\n\nand explain why it is important to you.",
        'exchanges' => [
            ['q' => 'Can you start speaking now, please?',
             'a' => "Yes. One of the most important things I have is my piano because I like playing the piano. I got it from my parents to my twelve birthday, so I have it for about nine years, and the reason why it is so important for me is that I can go into another world when I'm playing piano. I can forget what's around me and what ... I can forget my problems and this is sometimes quite good for a few minutes. Or I can play to relax or just, yes to … to relax and to think of something completely different."],
            ['q' => 'Thank you. Would it be easy to replace this, this piano?',
             'a' => "Yes, I think it wouldn't be that big problem but I like my piano as it is because I have it from my parents, it's some kind unique for me."],
        ],
    ],
    3 => [
        'label' => 'Part 3 – Two-way Discussion',
        'exchanges' => [
            ['q' => 'In Switzerland, what kind of possessions do you think give status to people?',
             'a' => "The first thing which comes in my mind is the car. Yes, because lots of people like to have posh cars or expensive cars to show their status, their place in the society."],
            ['q' => 'Is that a new development?',
             'a' => "No, I think it isn't."],
            ['q' => 'People have thought like that for quite a long time?',
             'a' => "Yes. Another thing is probably the clothing. It starts already when you are young. When the children go to school they want to have posh labels on their jumpers or good shoes."],
            ['q' => 'What do you think of this way of thinking, that I need to have a car or certain clothes to show my status?',
             'a' => "Probably it's sometimes a replacement for something you don't have, so if your wife has left you or your girlfriend, you just buy some new, I don't know, new watches or new clothes to make you satisfied again."],
            ['q' => "You don't think of it as a healthy way of thinking?",
             'a' => "It's probably not honest to yourself. You can understand what I mean?"],
            ['q' => 'And do you think this will change? In the future, will cars and designer clothes be status symbols in the same way?',
             'a' => "I'm sure that clothes will be ... that the thing with the clothes will be the same. I'm not so sure about the cars because cars cause lots of environmental problems and probably in some years, a few years, this will change because it's not reasonable to drive a car anymore."],
        ],
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IELTS Speaking — Model Answer | EduHub</title>
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
        .part-card {
            background: #fff; border-radius: 14px;
            box-shadow: 0 2px 10px rgba(15,23,42,0.07);
            margin-bottom: 1.5rem; overflow: hidden;
        }
        .part-header {
            padding: 1rem 1.4rem; color: #fff; background: #10b981;
            font-weight: 600; font-size: 1rem;
        }
        .part-body { padding: 1.4rem; }
        .cue-card {
            background: linear-gradient(135deg,#eff6ff,#dbeafe); border-radius: 12px;
            padding: 1.2rem; font-size: 0.93rem; line-height: 1.7; white-space: pre-line;
            border: 1px solid #bfdbfe; margin-bottom: 1.2rem;
        }
        .exchange { margin-bottom: 1.1rem; }
        .exchange:last-child { margin-bottom: 0; }
        .q-label, .a-label {
            font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.06em; margin-bottom: 0.2rem;
        }
        .q-label { color: #0b77ff; }
        .a-label { color: #10b981; }
        .q-text { font-weight: 600; color: #1e293b; font-size: 0.93rem; margin-bottom: 0.5rem; }
        .a-text { color: #334155; font-size: 0.93rem; line-height: 1.6; background:#f8fafc; border-radius:8px; padding:0.75rem 1rem; }
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

                <h1 style="font-size:1.5rem;font-weight:700;margin-bottom:0.2rem;">IELTS Speaking — Test 1</h1>
                <p class="text-muted mb-4" style="font-size:0.9rem;">Official IELTS sample-task transcript · Valid for both Academic and General Training</p>

                <?php foreach ($parts as $pNum => $part): ?>
                <div class="part-card">
                    <div class="part-header"><?= htmlspecialchars($part['label']) ?></div>
                    <div class="part-body">
                        <?php if (!empty($part['cue_card'])): ?>
                        <div class="cue-card"><?= htmlspecialchars($part['cue_card']) ?></div>
                        <?php endif; ?>
                        <?php foreach ($part['exchanges'] as $ex): ?>
                        <div class="exchange">
                            <div class="q-label">Examiner</div>
                            <div class="q-text"><?= htmlspecialchars($ex['q']) ?></div>
                            <div class="a-label">Candidate</div>
                            <div class="a-text"><?= htmlspecialchars($ex['a']) ?></div>
                        </div>
                        <?php endforeach; ?>
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
