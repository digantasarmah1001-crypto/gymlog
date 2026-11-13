# GymLog roadmap: one commit per session

CS404 runs Mon 9 – Fri 27 November 2026, one session per weekday.
Each session ends with **one commit** that contains exactly that session's GymLog work.
Status: all 15 sessions done.

| # | Topic | GymLog work in this commit | Version |
|---|---|---|---|
| 1 | Introduction to MVC, dev environment | Fresh Laravel project, agent instructions, roadmap, first route → controller → view | v0.1.0 |
| 2 | Models: relationships, migrations, factories, seeds | `Exercise`, `Workout`, `WorkoutSet` models + migrations + factories, relationships, `DatabaseSeeder` with admin | v0.2.0 |
| 3 | Views: layouts and components | Layout, Blade form components, styling + fonts, welcome page with real data | v0.3.0 |
| 4 | Controllers: full CRUD and validation | 7-method CRUD for exercises, workouts and sets, Form Requests, error feedback; one explicit route per action | v0.4.0 |
| 5 | Clean routing, resource controllers | Replace explicit routes with `Route::resource` + shallow nested sets, eager loading, README | v0.5.0 |
| 6 | Eloquent vs Collections, privacy, profiles | Stats (personal bests, workouts this month), profile page | v0.6.0 |
| 7 | Authentication & authorisation | Login/register, `is_admin`, policies (owner edits workouts, admin edits exercises), `auth` middleware | v0.7.0 |
| 8 | Packages, versioning, external services | Tag ↔ Workout N‑M, summary email, a package, release tag | v0.8.0 |
| 9 | File uploads, frontend assets | Exercise images (public disk), progress photos (private disk), small vanilla JS (no build step) | v0.9.0 |
| 10 | Errors, exceptions, security | Custom error pages, exception handling, security review | v0.10.0 |
| 11 | Caching, N+1, sessions | Cache stats, check N+1, unit preference in the session | v0.11.0 |
| 12 | Queues, model events | Queued email, observer that detects new personal records | v0.12.0 |
| 13 | Testing | Feature + unit tests, GitHub Actions CI | v0.13.0 |
| 14 | Refactoring | Action classes, query scope, Artisan commands, cleanup | v0.14.0 |
| 15 | Presentation | Final check from a clean clone, demo script (`docs/presentation.md`) | v1.0.0 |

## Data model
`User 1‑N Workout 1‑N WorkoutSet N‑1 Exercise`
(Workout ↔ Exercise is many-to-many through WorkoutSet.) and `Workout N‑M Tag` (pivot `tag_workout`).
