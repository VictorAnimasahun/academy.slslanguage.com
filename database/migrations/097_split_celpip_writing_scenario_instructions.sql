-- Migration 097: split CELPIP Full Mock A/B Writing question_text (a single
-- combined "scenario + instructions" blob) into two parts, matching the
-- real CELPIP interface's two-pane layout (left = scenario, right =
-- instructions + response). question_text becomes scenario-only;
-- instructions becomes a JSON blob: {"lead":"...","bullets":[...]} for
-- Task 1 (email), or {"lead":"...","options":{"A":"...","B":"..."}} for
-- Task 2 (survey response). The `instructions` column was previously only
-- used by IELTS Academic (as an image path for Task 1 charts) — free for
-- CELPIP to repurpose since CELPIP never shows a diagram there.

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "You recently made reservations for dinner at a very famous and expensive restaurant in town. However, the meal and the service were terrible. The restaurant manager was not available to solve the problem, so you left without a resolution.",
    q.instructions = '{"lead":"Write an email to the restaurant manager. Your email should do the following things:","bullets":["State what problems you had with the food you ordered.","Complain about the service.","Describe how you want the restaurant to resolve the problem to your satisfaction."]}'
WHERE t.code = 'CELPIP_FMA_W' AND q.question_number = 1;

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "City Development Survey.\n\nYou live in a small town of 10,000 people. A large green area in the centre of town is undeveloped. The city has sent out an opinion survey to see what residents would like to have built in that area.",
    q.instructions = '{"lead":"Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.","options":{"A":"Shopping Complex (restaurants, a large supermarket, and a movie theatre)","B":"Recreational Park (a sports complex, a large green area, and a small petting zoo)"}}'
WHERE t.code = 'CELPIP_FMA_W' AND q.question_number = 2;

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "You and your family visit the local shopping mall every week. However, it has become more and more difficult to find a parking spot recently. You would like to let the shopping mall manager know about this problem.",
    q.instructions = '{"lead":"Write an email to the mall manager. Your email should do the following things:","bullets":["Describe the problem you are having with the mall''s parking.","Explain what you and your family have to do in order to visit the shopping mall now.","Provide some suggestions for how the mall manager can solve this problem."]}'
WHERE t.code = 'CELPIP_FMB_W' AND q.question_number = 1;

UPDATE questions q JOIN tests t ON t.id = q.test_id
SET q.question_text = "Childcare Survey.\n\nYou work in a very big office. There is a popular and cheap restaurant in the building. The boss is thinking of removing the restaurant and replacing it with a childcare facility for the working parents in the office. You have been asked to respond to an opinion survey.",
    q.instructions = '{"lead":"Choose the option that you prefer. Why do you prefer your choice? Explain the reasons for your choice. Write about 150-200 words.","options":{"A":"I think we should keep the restaurant.","B":"I think we should replace the restaurant with a childcare facility."}}'
WHERE t.code = 'CELPIP_FMB_W' AND q.question_number = 2;

-- Verify:
-- SELECT t.code, q.question_number, q.question_text, q.instructions FROM questions q JOIN tests t ON t.id=q.test_id WHERE t.code IN ('CELPIP_FMA_W','CELPIP_FMB_W') ORDER BY t.code, q.question_number;
