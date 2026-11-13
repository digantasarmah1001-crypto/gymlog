# GymLog

A personal workout tracker built with Laravel for **CS404 – Modern Web Application 1**.

A workout (e.g. "Leg day") belongs to an athlete and contains exercises.
For each exercise the athlete logs their sets: reps × kg.

## Getting started

Run these one at a time (works in PowerShell, Terminal and Git Bash):

```bash
git clone https://github.com/digantasarmah1001-crypto/gymlog.git
cd gymlog
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

`storage:link` is needed once, so uploaded exercise photos are reachable at `/storage/...`.

Emails (welcome, new personal record) go through the **queue**. To process them, run in a second terminal:

```bash
php artisan queue:work
```

With the default `MAIL_MAILER=log` the emails then appear in `storage/logs/laravel.log`.

When `migrate` asks to create the SQLite database, answer **yes**.
Then open http://localhost:8000. No `npm` build is needed.

**Seeded admin:** `admin@example.com` / `password`

## Data model

`User 1‑N Workout 1‑N WorkoutSet N‑1 Exercise` and `Workout N‑M Tag`

| Model | Fields | Relationships |
|---|---|---|
| `User` | name, email, password | has many `Workout` |
| `Workout` | user_id, title, date, duration_minutes, notes | belongs to `User`, has many `WorkoutSet` |
| `WorkoutSet` | workout_id, exercise_id, set_number, reps, weight_kg | belongs to `Workout` and `Exercise` |
| `Exercise` | name (unique), muscle_group, equipment, description | has many `WorkoutSet` |
| `Tag` | name (unique) | belongs to many `Workout` (pivot `tag_workout`) |

`WorkoutSet` sits in the middle: Workout ↔ Exercise is many-to-many *through* it,
with its own data (reps, weight, set number).

## Features

- Full CRUD (all 7 resource actions) for exercises, workouts and sets.
- Resource routes; sets are a shallow nested resource (`/workouts/{workout}/sets`, `/sets/{set}`).
- Every input validated with Form Requests; errors shown per field and old input kept.
  - Exercise names are unique.
  - Workouts can't be dated in the future.
  - A set number can only be used once per workout.
- Welcome page with totals, latest workouts and the exercise library.
- Shared layout and Blade components (`x-input`, `x-select`, `x-textarea`, `x-stat`, `x-delete-button`).
- Plain CSS with self-hosted fonts (Big Shoulders Display + Archivo, SIL Open Font License).
- Athlete profiles with stats: personal bests (database: `MAX` + `GROUP BY`) and volume per muscle (Collections).
- Register, log in and log out (hand-written `AuthController`).
- Permissions with policies:
  - workouts and their sets are private (owner or admin only);
  - only admins add, edit or delete exercises; everyone can browse them;
  - your bodyweight and email are never shown to other people.
- Tags on workouts (many-to-many via the `tag_workout` pivot table); admins manage the tag list.
- Photo uploads:
  - exercise photos on the **public** disk (`storage/app/public`, visible to everyone);
  - progress photos on the **private** disk (`storage/app/private`), only served through
    `WorkoutPhotoController` after the same permission check as the workout.
- Small vanilla JavaScript (`public/js/app.js`): image preview before upload, delete confirmation,
  auto-submitting filter. No build step.
- Welcome email after registering (if sending fails, registering still works and the error is logged).
- Performance: lazy loading is forbidden outside production (catches N+1 queries), the
  welcome-page totals are cached.
- Personal records: a `WorkoutSet` observer (model event) marks a set 🏆 when it beats the athlete's
  earlier best for that exercise, and dispatches the queued `NotifyPersonalRecord` job (email).
  Model events also clear the cached welcome-page totals.
- kg / lb switch in the nav, remembered in the session (weights are always stored in kg).
- Errors & security: custom error pages, exercises that are in use can't be deleted
  (`ExerciseInUseException`), login rate limiting. See `docs/security.md` for the full review. With the default `MAIL_MAILER=log` it is written to
  `storage/logs/laravel.log` instead of being sent.

## Code structure: where does what live?

| Place | Responsibility | Examples |
|---|---|---|
| `routes/web.php` | Which URL goes to which controller method, which routes need login | `Route::resource('workouts', …)` |
| `app/Http/Controllers` | **Skinny**: read the request, call the right class, return a view or redirect | `WorkoutController` |
| `app/Http/Requests` | Validation rules and "may this user do this?" (`authorize()`) | `StoreWorkoutRequest` |
| `app/Policies` | Permission rules per model | `WorkoutPolicy` (owner or admin) |
| `app/Models` | Data + relationships + small model logic and query scopes | `Workout::visibleTo($user)`, `WorkoutSet::beatsPreviousBest()` |
| `app/Actions` | One reusable task, used by controllers, commands and the seeder | `CalculateAthleteStats`, `ReplaceUploadedFile`, `RecalculatePersonalRecords` |
| `app/Observers`, model `booted()` | Things that must happen whenever a model is saved/deleted | `WorkoutSetObserver` (records, cache) |
| `app/Jobs`, `app/Mail` | Slow work done later by the queue worker | `NotifyPersonalRecord`, `WelcomeMail` |
| `app/Console/Commands` | Artisan commands | `gymlog:weekly-summary`, `gymlog:recalculate-records` |
| `app/Support` | Small helpers without database | `Weight` (kg/lb) |

### Artisan commands

```bash
php artisan gymlog:weekly-summary --days=7
php artisan gymlog:recalculate-records
```

## Tests

```bash
php artisan test
```

60 tests (about 2 seconds) on an in-memory SQLite database, also run by GitHub Actions on every push
(`.github/workflows/tests.yml`).

| Folder | What it tests |
|---|---|
| `tests/Unit` | Single classes without database or HTTP: `Weight` conversion, `WorkoutPolicy` rules |
| `tests/Feature/Crud` | All 7 actions + validation for exercises, workouts, sets and tags |
| `tests/Feature/Auth` | Register, login, logout, wrong password, rate limiting, welcome mail |
| `tests/Feature/Authorization` | Private workouts, admin-only exercises/tags, no self-promotion to admin |
| `tests/Feature/*Test.php` | Home page & cache, profiles, uploads, personal records & queue, kg/lb, error pages, seeder |

Helpers in `tests/TestCase.php`: `signIn()` and `signInAsAdmin()`.

## Packages

- `barryvdh/laravel-debugbar` (dev only): a bar at the bottom of each page in local development
  that shows the SQL queries, timing and views. Disable it with `DEBUGBAR_ENABLED=false` in `.env`.

## Versions

See `CHANGELOG.md`. Each session is tagged `v0.<session>.0`.

## Project files for AI tools

- `AGENTS.md` / `CLAUDE.md`: instructions for coding agents.
- `docs/plans/roadmap.md`: what each of the 15 sessions/commits contains.
