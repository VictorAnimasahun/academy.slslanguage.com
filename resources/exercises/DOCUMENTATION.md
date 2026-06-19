# Exercises — Subsystem Documentation

Parent: [`../DOCUMENTATION.md`](../DOCUMENTATION.md)

Short, ungraded (or self-checked) interactive drills — lighter weight than a full practice test, no DB-backed scoring/attempt history.

## Entry point

`exercises.php` — catalog page listing available exercises by category (currently: Writing exercises are built; Speaking and Reading are stubs/placeholders).

## Exercise pages

| File | Exercise |
|---|---|
| `letter_parts.php` | Drag-and-drop classifier — sort sentence fragments into the correct part of an IELTS GT letter (10 difficulty levels) |
| `data_detective.php` | IELTS Writing Task 1 chart/graph analysis drill — interpret data visualizations |
| `build_your_evidence.php` | Evidence-gathering scaffold for essay writing |
| `thin_to_thick.php` | Sentence/paragraph elaboration drill — expand a thin response into a fuller one |
| `sentence_sequencing.php` | Sentence-ordering exercise — **currently disabled/commented out**, not linked from `exercises.php` |

## Notes

- All client-side logic; no `save_attempt.php`-style persistence layer — these are practice/drill tools, not assessed tests, so there's no attempt history in the dashboard for them.
- If `sentence_sequencing.php` is ever re-enabled, re-add its card to `exercises.php`.
