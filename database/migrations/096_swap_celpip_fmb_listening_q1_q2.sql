-- Migration 096: CELPIP_FMB_L Q1 and Q2 content were transcribed onto the
-- wrong question numbers — the source CELPIP audio for "Question 1" asks
-- about the man's workstation (the photo-option question), and "Question 2"
-- asks which word best describes the man's task, but our DB had them
-- swapped. Confirmed by the user listening to the actual audio tracks
-- (audio says "Question 1, ..." itself, so this is a source transcription
-- error, not a file-naming bug). Swaps question_text + all 4 answer
-- options between the two question rows; the intro scene-setting
-- (stimulus_text/instructions) stays on question_number=1 since that's
-- read once at the start of the part regardless of which specific
-- question follows it.

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "You will hear a conversation between a man and a woman. The conversation takes place in an office. What is at the man's workstation?"
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 1;

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "Which word best describes the man's task?"
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2;

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = '[Photo] A yellow sponge on a black background', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 1 AND qo.option_label = 'A';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = '[Photo] Two stacks/sets of open cardboard boxes (4 boxes total)', qo.is_correct = 1
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 1 AND qo.option_label = 'B';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = '[Photo] A clear glass cup of water on a small glass saucer', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 1 AND qo.option_label = 'C';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = '[Photo] A black office desk telephone with keypad and display screen', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 1 AND qo.option_label = 'D';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = 'boring', qo.is_correct = 1
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2 AND qo.option_label = 'A';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = 'exciting', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2 AND qo.option_label = 'B';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = 'difficult', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2 AND qo.option_label = 'C';

UPDATE question_options qo JOIN questions q ON q.id = qo.question_id JOIN tests t ON t.id = q.test_id
SET qo.option_text = 'enjoyable', qo.is_correct = 0
WHERE t.code = 'CELPIP_FMB_L' AND q.question_number = 2 AND qo.option_label = 'D';

-- Verify:
-- SELECT q.question_number, q.question_text FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code='CELPIP_FMB_L' AND q.question_number IN (1,2);
-- SELECT q.question_number, qo.option_label, qo.option_text, qo.is_correct FROM question_options qo JOIN questions q ON q.id=qo.question_id JOIN tests t ON t.id=q.test_id WHERE t.code='CELPIP_FMB_L' AND q.question_number IN (1,2) ORDER BY q.question_number, qo.option_label;
