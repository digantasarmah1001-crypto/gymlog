# GymLog – agent instructions

GymLog is a personal workout tracker built for CS404 (Modern Web Application 1, Laravel).
Roadmap and session schedule: `docs/plans/roadmap.md`.

## Domain
- `User` 1‑N `Workout` (title, date, duration_minutes, notes)
- `Workout` 1‑N `WorkoutSet` (set_number, reps, weight_kg)
- `Exercise` 1‑N `WorkoutSet` (name, muscle_group, equipment, description)

## Stack & conventions
- Laravel 13, PHP 8.3, Blade views (no SPA), SQLite locally.
- Every model has a Model, Migration and Factory; factories are called from `DatabaseSeeder`.
- Every non-User model gets a resource controller with all 7 methods; routes use `Route::resource`.
- All input is validated in Form Requests (`app/Http/Requests`); forms show `@error` + `old()`.
- Views extend `resources/views/layouts/app.blade.php`; shared bits live in `resources/views/components`.
- Eager-load relationships on index/show pages (`with()`) to avoid N+1.
- Seeded admin: `admin@example.com` / `password`.
- Until session 7 there is no authentication: workouts pick their owner from a dropdown. Do not add auth early.

## Workflow
- Small, atomic commits: one functional step per commit.
- `php artisan migrate:fresh --seed` must always succeed before committing.
- Run `php artisan test` before pushing.
