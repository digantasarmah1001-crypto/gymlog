# GymLog: build up to Session 5 (CS404)

## Context
CS404 runs Nov 9 – Nov 27, 2026: 15 sessions over 15 weekdays, so one session per weekday. The syllabus doesn't give a date for each session; this mapping is inferred from that:

| Session | Date | Topic |
|---|---|---|
| 1 | Mon Nov 9 | MVC, requests, dev environment |
| 2 | Tue Nov 10 | Models, relationships, migrations, factories, seeds |
| 3 | Wed Nov 11 | Views, layouts, components |
| 4 | Thu Nov 12 (today) | Controllers, full CRUD, validation |
| 5 | Fri Nov 13 | Clean routing, resource controllers, component thinking, intro to coding agents |

Auth, authorisation and policies come in Session 7, so this phase leaves them out. The goal is to have GymLog working through Session 5: models, seed data, a layout, and full validated CRUD on resource routes.

The project is **GymLog**, a personal workout tracker:
- `User` (Laravel default) 1‑N `Workout`
- `Workout` (user_id, title, date, duration_minutes, notes) 1‑N `WorkoutSet`
- `Exercise` (name, muscle_group, equipment, description) 1‑N `WorkoutSet`
- `WorkoutSet` (workout_id, exercise_id, set_number, reps, weight_kg)
- (Tag ↔ Workout N‑M is deferred to later, if there's time)

## Steps (one atomic commit each)
1. **Setup (S1):** `laravel new gymlog` (Blade, SQLite or MySQL), `git init`, push to a public GitHub repo, and add a `CLAUDE.md` describing the project (counts as an AI artefact).
2. **Models (S2):** `php artisan make:model Exercise -mf`, then `Workout -mf`, then `WorkoutSet -mf`.
   - Migrations: `foreignId('user_id')->constrained()->cascadeOnDelete()`, the same for workout_id and exercise_id; `decimal('weight_kg', 6, 2)`.
   - Relationships: `User::workouts()`, `Workout::user()`, `Workout::sets()`, `Exercise::sets()`, `WorkoutSet::workout()`, `WorkoutSet::exercise()`.
   - `$fillable` on each model; cast `date` on Workout.
   - Factories use realistic data (exercise names from a fixed list, reps 5–15, weight 10–150).
3. **Seeder (S2):** in `DatabaseSeeder`, create `admin@example.com` / `password`, 10 users, 20 exercises, 3–6 workouts per user, and 3–6 sets per workout using existing exercises. Check that `php artisan migrate:fresh --seed` runs.
4. **Views (S3):** `resources/views/layouts/app.blade.php` (nav, `@yield('content')`, flash message), a reusable Blade component for form errors or inputs (`x-input`), and a welcome page showing the exercise library and the number of workouts logged.
5. **CRUD (S4):** `make:controller --resource --model` for Exercise, Workout and WorkoutSet, each with all 7 methods. Use Form Requests (`make:request StoreWorkoutRequest`, etc.) with rules such as `reps|integer|min:1|max:100` and `exercise_id|exists:exercises,id`. Views: index, show, create and edit, with `@csrf`, `@method`, `@error` and `old()`.
   - **Temporary until S7:** the Workout form has a "user" dropdown (`user_id|exists:users,id`). In S7 this is replaced by `auth()->id()` and a policy.
   - WorkoutSet create and edit pick the workout and exercise from dropdowns.
6. **Routing (S5):** `Route::resource('exercises', …)`, `Route::resource('workouts', …)` and `Route::resource('workouts.sets', WorkoutSetController::class)->shallow()` for nested sets. Every route points to a controller method (check with `php artisan route:list`). Use eager loading (`Workout::with('user', 'sets.exercise')`) on index and show pages.

## Phase 2: Sessions 6–10 (Mon Nov 16 – Fri Nov 20)
Each session's feature is built and committed on the day of that session.
| Session | Date | Topic | GymLog work |
|---|---|---|---|
| 6 | Mon Nov 16 | Eloquent vs Collections, privacy, profiles | Stats with Eloquent queries (personal best per exercise, workouts this month, total volume), Collection grouping (sets per muscle group), a user profile page (name, goal, body weight) shown only to its owner |
| 7 | Tue Nov 17 | Auth, authorisation, middleware, business logic | Breeze login and register, `is_admin` column (the seeded admin gets true), remove the temporary user dropdown in favour of `auth()->id()`, `WorkoutPolicy` (owner only), `ExercisePolicy` (admin only), `WorkoutSetPolicy` (via the workout owner), `auth` middleware, nav shows "Hi, {name}" |
| 8 | Wed Nov 18 | Packages, versioning, external services | Tag ↔ Workout N‑M (model, migration, factory, CRUD, pivot `tag_workout`), a welcome email or weekly-summary email via Mailtrap/log driver, a package such as `barryvdh/laravel-debugbar` (dev), tag a release `v0.1` |
| 9 | Thu Nov 19 | File uploads, frontend assets | Exercise demo image (public disk, admin upload), optional private progress photo on Workout (private disk, served only to the owner through a controller), Vite + Tailwind styling |
| 10 | Fri Nov 20 | Errors, exceptions, security | Custom 403/404 pages, try/catch around uploads and mail, mass-assignment and XSS check, rate-limit login, check that no route leaks other users' workouts |

## Phase 3: Sessions 11–15 (Mon Nov 23 – Fri Nov 27)
| Session | Date | Topic | GymLog work |
|---|---|---|---|
| 11 | Mon Nov 23 | Caching, N+1, sessions | Fix N+1 with `with()` (check with debugbar), cache the exercise library and welcome stats, store the "last used exercise" or unit preference (kg/lb) in the session |
| 12 | Tue Nov 24 | Queues, model events | Queue the summary email (`ShouldQueue`, database queue), a model event or observer on WorkoutSet that recalculates the cached personal best and flags new PRs |
| 13 | Wed Nov 25 | Testing | Feature tests: CRUD per model, validation errors, policy 403s (another user's workout), seeder runs; unit test for the stats/PR calculation |
| 14 | Thu Nov 26 | Refactoring | Thin controllers: move logic into Action classes (`LogWorkoutSet`, `CalculatePersonalBest`), an Artisan command such as `gymlog:weekly-summary`, cleanup, final README |
| 15 | Fri Nov 27 | Presentation | Final `migrate:fresh --seed` from a clean clone, demo script (guest → user → admin), be ready to explain every model, policy and query |

## Rules for the whole project
- Before each session's work, pull and check that `migrate:fresh --seed` still works. After it, make small commits and push the same day.
- Every new model gets a migration, factory and seeder entry, plus full CRUD if it's user-facing.
- Keep `CLAUDE.md` and the plan files updated in the repo (AI artefacts are graded).
- If you fall behind, the minimum requirements (S1–S7 work) come first; S8–S14 are extras that improve the grade.

## Verification
- `php artisan migrate:fresh --seed` runs clean.
- `php artisan serve`: open the welcome page, then create, edit and delete an exercise, a workout and a set; invalid input shows errors and keeps the old values.
- `php artisan route:list`: every route uses a controller and resource names are clean.
- `git log --oneline`: small commits, one per step or model.
