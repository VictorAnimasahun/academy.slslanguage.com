# API — Subsystem Documentation

Parent: [`../documentation/SITE_MAP.md`](../documentation/SITE_MAP.md)

JSON/AJAX endpoints. Not REST in any formal sense — each file is a single-purpose POST/GET handler called via `fetch()` from the corresponding front-end page.

| File | Called from | Purpose |
|---|---|---|
| `api_handler.php` | `resources/essay_analyzer.php`, `resources/audio_analyzer.php` | Main AI dispatcher. Actions: `analyze_essay`, `transcribe_audio`, `analyze_speaking`, `analyze_speaking_batch`. Checks session + `includes/rate_limiter.php`, calls the configured AI provider (`ANALYSIS_API` in `config/api_keys.php`, currently `'gemini'`), returns JSON. |
| `get_messages_preview.php` | dashboard / topbar | Returns the student's most recent unread broadcast messages (limit 5) for a preview dropdown |
| `get_unread_count.php` | topbar (polled) | Returns a single integer — unread broadcast message count, respecting targeting rules (all-students / course / individual) |

## Relationship to `resources/mock_tests/mock_save_section.php` and `resources/practice_tests/save_attempt.php`

Those two files are *also* AJAX/JSON endpoints but live next to the test pages that call them rather than in this `api/` folder — they're test-scoring endpoints, conceptually different from the AI/messaging endpoints here. Don't be surprised they're not listed in this folder; this is a historical/organizational split, not a strict rule about where endpoints must live.

## AI provider configuration

Lives in `config/api_keys.php` (shared across all PHP projects, not just academy): `GEMINI_API_KEY`, `ANALYSIS_API`, `TRANSCRIPTION_API`, and the rate-limit constants. See `ACADEMY_PLATFORM_DOCUMENTATION.md` § API & Rate Limiting for the deeper internals (request flow diagrams, error handling).
