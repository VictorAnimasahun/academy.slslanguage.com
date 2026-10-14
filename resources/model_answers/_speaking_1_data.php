<?php
// Shared data for the IELTS Speaking Test 1 model-answer transcript. Extracted from
// model_answers_speaking.php (2026-10-14) -- real official IELTS.org sample transcript, valid for
// both Academic and General Training.
function speaking1Data(): array {
    return [
        1 => [
            'label' => 'Part 1 – Introduction & Interview',
            'exchanges' => [
                ['q' => "Let's talk about your home town or village. What kind of place is it?", 'a' => "It's quite a small village, about 20km from Zurich. And it's very quiet. And we have only little ... two little shops because most of the people work in Zurich or are orientated to the city."],
                ['q' => "What's the most interesting part of this place ... village?", 'a' => 'On the top of a hill we have a little castle which is very old and quite well known in Switzerland.'],
                ['q' => 'What kind of jobs do people in the village do?', 'a' => 'We have some farmers in the village as well as people who work in Zurich as bankers or journalists or there are also teachers and some doctors, some medicines.'],
                ['q' => "Would you say it's a good place to live?", 'a' => "Yes. Although it is very quiet, it is … people are friendly and I would say it is a good place to live there, yes."],
            ],
        ],
        2 => [
            'label' => 'Part 2 – Individual Long Turn',
            'cue_card' => "Describe something you own which is very important to you.\n\nYou should say:\n• where you got it from\n• how long you have had it\n• what you use it for\n\nand explain why it is important to you.",
            'exchanges' => [
                ['q' => 'Can you start speaking now, please?', 'a' => "Yes. One of the most important things I have is my piano because I like playing the piano. I got it from my parents to my twelve birthday, so I have it for about nine years, and the reason why it is so important for me is that I can go into another world when I'm playing piano. I can forget what's around me and what ... I can forget my problems and this is sometimes quite good for a few minutes. Or I can play to relax or just, yes to … to relax and to think of something completely different."],
                ['q' => 'Thank you. Would it be easy to replace this, this piano?', 'a' => "Yes, I think it wouldn't be that big problem but I like my piano as it is because I have it from my parents, it's some kind unique for me."],
            ],
        ],
        3 => [
            'label' => 'Part 3 – Two-way Discussion',
            'exchanges' => [
                ['q' => 'In Switzerland, what kind of possessions do you think give status to people?', 'a' => 'The first thing which comes in my mind is the car. Yes, because lots of people like to have posh cars or expensive cars to show their status, their place in the society.'],
                ['q' => 'Is that a new development?', 'a' => "No, I think it isn't."],
                ['q' => 'People have thought like that for quite a long time?', 'a' => 'Yes. Another thing is probably the clothing. It starts already when you are young. When the children go to school they want to have posh labels on their jumpers or good shoes.'],
                ['q' => 'What do you think of this way of thinking, that I need to have a car or certain clothes to show my status?', 'a' => "Probably it's sometimes a replacement for something you don't have, so if your wife has left you or your girlfriend, you just buy some new, I don't know, new watches or new clothes to make you satisfied again."],
                ['q' => "You don't think of it as a healthy way of thinking?", 'a' => "It's probably not honest to yourself. You can understand what I mean?"],
                ['q' => 'And do you think this will change? In the future, will cars and designer clothes be status symbols in the same way?', 'a' => "I'm sure that clothes will be ... that the thing with the clothes will be the same. I'm not so sure about the cars because cars cause lots of environmental problems and probably in some years, a few years, this will change because it's not reasonable to drive a car anymore."],
            ],
        ],
    ];
}
