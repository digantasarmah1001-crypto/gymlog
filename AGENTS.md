# GymLog – agent instructions

GymLog is a personal workout tracker built for CS404 (Modern Web Application 1, Laravel).
A workout (e.g. "Leg day") belongs to an athlete and contains exercises; for each exercise
the athlete logs their sets (reps × kg).
Roadmap and session schedule: `docs/plans/roadmap.md`.

## Domain
- `User` 1‑N `Workout` (title, date, duration_minutes, notes)
- `Workout` 1‑N `WorkoutSet` (set_number, reps, weight_kg)
- `Exercise` 1‑N `WorkoutSet` (name, muscle_group, equipment, description)
- So Workout ↔ Exercise is many-to-many *through* `WorkoutSet` (a pivot with its own data).

## Stack & conventions
- Laravel 13, PHP 8.3, Blade views (no SPA), SQLite locally.
- Every model has a Model, Migration and Factory; factories are called from `DatabaseSeeder`.
- Every non-User model gets a controller with all 7 resource methods.
- All input is validated in Form Requests (`app/Http/Requests`); forms show `@error` + `old()`.
- Views extend `resources/views/layouts/app.blade.php`; shared bits live in `resources/views/components`.
- Styling: plain CSS in `public/css/app.css`, self-hosted fonts in `public/fonts`
  (Big Shoulders Display for headings, Archivo for text). No npm build step until session 9.
- Seeded admin: `admin@example.com` / `password`.
- Until session 7 there is no authentication: workouts pick their owner from a dropdown. Do not add auth early.

## Workflow
- One commit per course session (15 in total), containing exactly that session's work from the roadmap.
- Keep code simple and commented where it isn't obvious: the student must be able to explain and change every line.
- `php artisan migrate:fresh --seed` must always succeed before committing.
