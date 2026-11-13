# GymLog roadmap: one commit per session

CS404 runs Mon 9 – Fri 27 November 2026, one session per weekday.
Each session ends with **one commit** that contains exactly that session's GymLog work.

| # | Topic | GymLog work in this commit |
|---|---|---|
| 1 | Introduction to MVC, dev environment | Fresh Laravel project, agent instructions, roadmap, first route → controller → view |
| 2 | Models: relationships, migrations, factories, seeds | `Exercise`, `Workout`, `WorkoutSet` models + migrations + factories, relationships, `DatabaseSeeder` with admin |
| 3 | Views: layouts and components | Layout, Blade form components, styling + fonts, welcome page with real data |
| 4 | Controllers: full CRUD and validation | 7-method CRUD for exercises, workouts and sets, Form Requests, error feedback; one explicit route per action |
| 5 | Clean routing, resource controllers | Replace explicit routes with `Route::resource` + shallow nested sets, eager loading, README |
| 6 | Eloquent vs Collections, privacy, profiles | Stats (personal bests, workouts this month), profile page |
| 7 | Authentication & authorisation | Login/register, `is_admin`, policies (owner edits workouts, admin edits exercises), `auth` middleware |
| 8 | Packages, versioning, external services | Tag ↔ Workout N‑M, summary email, a package, release tag |
| 9 | File uploads, frontend assets | Exercise images (public), progress photos (private), Vite |
| 10 | Errors, exceptions, security | Custom error pages, exception handling, security review |
| 11 | Caching, N+1, sessions | Cache stats, check N+1, unit preference in the session |
| 12 | Queues, model events | Queued email, observer that detects new personal records |
| 13 | Testing | Feature + unit tests |
| 14 | Refactoring | Action classes, Artisan command, cleanup |
| 15 | Presentation | Final check from a clean clone, demo script |

## Data model
`User 1‑N Workout 1‑N WorkoutSet N‑1 Exercise`
(Workout ↔ Exercise is many-to-many through WorkoutSet.)
