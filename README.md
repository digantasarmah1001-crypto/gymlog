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
php artisan serve
```

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
- Welcome email after registering. With the default `MAIL_MAILER=log` it is written to
  `storage/logs/laravel.log` instead of being sent.

## Packages

- `barryvdh/laravel-debugbar` (dev only): a bar at the bottom of each page in local development
  that shows the SQL queries, timing and views. Disable it with `DEBUGBAR_ENABLED=false` in `.env`.

## Versions

See `CHANGELOG.md`. Each session is tagged `v0.<session>.0`.

## Project files for AI tools

- `AGENTS.md` / `CLAUDE.md`: instructions for coding agents.
- `docs/plans/roadmap.md`: what each of the 15 sessions/commits contains.
