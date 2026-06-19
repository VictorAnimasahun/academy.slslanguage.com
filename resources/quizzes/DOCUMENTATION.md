# Quizzes — Subsystem Documentation

Parent: [`../DOCUMENTATION.md`](../DOCUMENTATION.md)

Short knowledge-check quizzes (terminology/concept checks, not skill assessment). No database persistence — purely client-side, scored in the browser via `assets/js/quizzes.js`.

## Entry point

`quizzes_home.php` — catalog of available quizzes.

## Quiz pages

| File | Quiz |
|---|---|
| `maps_quiz.php` | IELTS Writing Task 1 — map-description vocabulary/knowledge check |
| `process_desc_quiz.php` | IELTS Writing Task 1 — process-diagram description knowledge check |
| `IELTS_Listening_Quiz.php` | General Listening-section knowledge check |
| `IELTS_Mastery_Quiz.php` | Broad IELTS exam-format knowledge check |

## Notes

- Quiz question data/scoring logic lives in `assets/js/quizzes.js`, shared across all four pages — if you add a quiz, add its question set there rather than inlining JS per page.
- Distinct from `exercises/` (interactive skill drills) and `diagnostic_tests/` (placement assessment) — quizzes are the lightest-weight, no-stakes category.
