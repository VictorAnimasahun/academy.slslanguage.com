<?php
// Shared data for the IELTS Academic Writing Test 1 model-answer comparison (Band 5 vs Band 6
// scripts). Extracted from model_answers_academic_writing_1.php (2026-10-14) so the mobile API and
// the web page serve the same real content -- image paths are relative to ACADEMY_URL, resolved
// by the caller (same env-aware constant the web page already uses).
function academicWriting1Data(): array {
    return [
        'howMarked' => [
            'task1' => 'Task Achievement, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.',
            'task2' => 'Task Response, Coherence and Cohesion, Lexical Resource, Grammatical Range and Accuracy.',
            'note' => 'Task 2 carries more weight than Task 1. Scripts are penalised if they are under the minimum word length (150 / 250), copied, or not written as full connected text. These scripts are examples, not definitive models of any band.',
        ],
        'task1' => [
            'label' => 'Task 1A · The chart',
            'prompt' => "The chart below shows the number of men and women in further education in Britain in three periods and whether they were studying full-time or part-time.\n\nSummarise the information by selecting and reporting the main features, and make comparisons where relevant.",
            'note' => 'Write at least 150 words · about 20 minutes.',
            'chartImage' => 'assets/images/ielts_academic/writing_001_task1_chart.png',
            'scripts' => [
                ['name' => 'Sample Script A', 'band' => '5', 'image' => 'assets/images/ielts_academic/writing_001_task1_script_a.png', 'comment' => 'The length of the answer is just acceptable. There is a good attempt to describe the overall trends but the content would have been greatly improved if the candidate had included some reference to the figures given on the graph. Without these, the reader is lacking some important information. The answer is quite difficult to follow and there are some punctuation errors that cause confusion. The structures are fairly simple and efforts to produce more complex sentences are not successful.'],
                ['name' => 'Sample Script B', 'band' => '6', 'image' => 'assets/images/ielts_academic/writing_001_task1_script_b.png', 'comment' => 'The candidate has made a good attempt to describe the graphs looking at global trends and more detailed figures. There is, however, some information missing and the information is inaccurate in minor areas. The answer flows quite smoothly although connectives are overused or inappropriate, and some of the points do not link up well. The grammatical accuracy is quite good and the language used to describe the trends is well-handled. However, there are problems with expression and the appropriate choice of words and whilst there is good structural control, the complexity and variation in the sentences are limited.'],
            ],
        ],
        'task2' => [
            'label' => 'Task 2A · The essay',
            'prompt' => "The first car appeared on British roads in 1888. By the year 2000 there may be as many as 29 million vehicles on British roads.\n\nAlternative forms of transport should be encouraged and international laws introduced to control car ownership and use.\n\nTo what extent do you agree or disagree?\n\nGive reasons for your answer and include any relevant examples from your knowledge or experience.",
            'note' => 'Write at least 250 words · about 40 minutes.',
            'scripts' => [
                ['name' => 'Sample Script A', 'band' => '5', 'image' => 'assets/images/ielts_academic/writing_001_task2_script_a.png', 'comment' => 'The answer is short at just over 200 words and thus loses marks for content. There are some relevant arguments but these are not very well developed and become unclear in places. The organisation of the answer is evident through the use of fairly simple connectives but there are problems for the reader in that there are many missing words and word order is often incorrect. The structures are quite ambitious but often faulty and vocabulary is kept quite simple.'],
                ['name' => 'Sample Script B', 'band' => '6', 'image' => 'assets/images/ielts_academic/writing_001_task2_script_b.png', 'comment' => 'There are quite a lot of ideas and while some of these are supported better than others, there is an overall coherence to the answer. The introduction is perhaps slightly long and more time could have been devoted to answering the question. The answer is fairly easy to follow and there is good punctuation. Organisational devices are evident although some areas of the answer become unclear and would benefit from more accurate use of connectives. There are some errors in the structures but there is also evidence of the production of complex sentence forms. Grammatical errors interfere slightly with comprehension.'],
            ],
        ],
        'source' => 'IELTS.org, Academic Writing sample tasks and candidate scripts.',
    ];
}
