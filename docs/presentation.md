# GymLog – presentation guide (session 15)

## 1. Setup before presenting (2 minutes)

```bash
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Second terminal (for the emails): `php artisan queue:work`.
Logins: `admin@example.com` / `password` (admin). Register a fresh account live to show a normal athlete.

## 2. Demo script (about 10 minutes)

| # | Show | Say | Requirement covered |
|---|---|---|---|
| 1 | Welcome page as guest | "GymLog: a workout holds exercises, every exercise has sets (reps × kg). Totals are cached." | Relevant welcome page, caching |
| 2 | Exercises, Athletes → a profile | "Guests can browse the shared exercise library and profiles. Email and bodyweight are never shown." | Views, privacy |
| 3 | Register a new athlete | "Hand-written auth. Password hashed, session regenerated, welcome email goes on the queue." | Register/login, queues |
| 4 | Log workout → add tags + progress photo | "The workout belongs to me automatically, tags are a many-to-many, the photo is on the private disk." | CRUD, N‑M, uploads |
| 5 | Add 2 sets, the 2nd heavier than the 1st on a later day | "🏆 – the WorkoutSetObserver detected a personal record and queued an email job." Show the worker terminal + `storage/logs/laravel.log`. | Model events, jobs |
| 6 | Submit a set with reps 0 and a duplicate set number | "Form Requests validate everything; errors per field, input kept." | Validation + feedback |
| 7 | Change the URL to someone else's workout (e.g. `/workouts/1`) | "403 – WorkoutPolicy. Same for the photo URL." | Authorisation, route protection |
| 8 | Log in as admin: All workouts, delete an exercise in use | "Admins see everything and manage exercises/tags. An exercise with sets can't be deleted: custom exception." | Business logic, exceptions |
| 9 | kg/lb switch | "Stored in kg, shown in the unit you pick, remembered in the session." | Sessions |
| 10 | Terminal: `php artisan test`, `php artisan gymlog:weekly-summary`, GitHub Actions tab | "65 tests, run on every push. Artisan command reuses the same queries." | Testing, CLI |
| 11 | GitHub commit list | "One commit per session, each a working state; AGENTS.md and docs/plans show how AI was used." | Git, AI artefacts |

## 3. Where is each assessment requirement?

| Requirement | Where |
|---|---|
| ≥ 4 models incl. User, Model + Migration + Factory each | `User`, `Exercise`, `Workout`, `WorkoutSet`, `Tag` in `app/Models`, `database/migrations`, `database/factories` |
| ≥ 2 one-to-many | User→Workout, Workout→WorkoutSet, Exercise→WorkoutSet (+ N‑M Workout↔Tag, hasManyThrough User→WorkoutSet) |
| Factories in `DatabaseSeeder` | `database/seeders/DatabaseSeeder.php` |
| Full CRUD (7 methods) for all non-User models | `ExerciseController`, `WorkoutController`, `WorkoutSetController`, `TagController` + `Route::resource` |
| Every route → controller | `routes/web.php` (`php artisan route:list --except-vendor`) |
| Validate every input + feedback | `app/Http/Requests/*`, `<x-input>` / `<x-error>` components, `old()` |
| Login / register | `AuthController`, `resources/views/auth/*` |
| User info in a view | Nav "Hi, {name}", "My workouts", own bodyweight on own profile |
| User info in a controller | `WorkoutController@store` (`$request->user()->workouts()->create`), `visibleTo($user)` |
| Business logic + route protection | Policies, `auth`/`guest` middleware, `ExerciseInUseException`, PR detection, rate limiting |
| Layout | `resources/views/layouts/app.blade.php` (+ `layouts/error.blade.php`) |
| Admin `admin@example.com` / `password` | `DatabaseSeeder` (`User::factory()->admin()`) |
| Relevant welcome page | `resources/views/welcome.blade.php` |

## 4. Questions you may get (and short answers)

**Why is WorkoutSet a model and not a pivot table?**
A set has its own data (reps, weight, order) and its own CRUD. Workout ↔ Exercise is many-to-many *through* it. Tag ↔ Workout *is* a plain pivot (`tag_workout`), because the link has no data of its own.

**What does `cascadeOnDelete()` do, and why can't I delete an exercise then?**
Deleting a workout deletes its sets in the database. For exercises that would silently erase everyone's history, so the controller throws `ExerciseInUseException` first (its `render()` turns it into a red message).

**Where is authorisation checked?**
In two places that both use the policies: Form Request `authorize()` for create/update (runs *before* validation), and `Gate::authorize()` in the controller for show/edit/delete. In views, `@can` only hides buttons, it is not the protection.

**What stops someone from sending `user_id` or `is_admin` in a form?**
The `#[Fillable]` whitelist. `is_admin` isn't fillable at all; the workout's `user_id` is set from `$request->user()`, never from the form.

**What is N+1 and how do you prevent it?**
Loading a relationship inside a loop = 1 query for the list + 1 per row. We eager load with `with()`, and `Model::preventLazyLoading()` (AppServiceProvider) makes it crash during development if we forget.

**Eloquent vs Collections?**
`ProfileController` → `CalculateAthleteStats`: personal bests are calculated by the database (`MAX` + `GROUP BY`, only results come back); volume per muscle is calculated in PHP on a loaded collection (`groupBy` + `sum`). The database is better for big data, collections are more flexible.

**Why a queue for the email?**
Sending mail can take seconds or fail. The request only puts a job in the `jobs` table; `php artisan queue:work` sends it in the background and retries (`$tries = 3`).

**Observer vs `booted()`?**
Both listen to model events. The observer class (`WorkoutSetObserver`) is used for bigger logic; small closures in `booted()` (Workout, Exercise) just clear the cache.

**Public vs private files?**
Exercise photos: public disk (`storage/app/public`, linked to `public/storage`). Progress photos: private disk (`storage/app/private`), only reachable through `WorkoutPhotoController`, which checks the policy first.

**Why did you cache arrays and not models?**
Laravel 13 refuses to unserialize objects from the cache (`serializable_classes => false`) to block attacks if the APP_KEY leaks. We found this bug with a crawl over all pages and fixed it in session 14, with a regression test.

**Unit vs feature test?**
`tests/Unit/WeightTest` tests one class without Laravel or a database (milliseconds). Feature tests send real HTTP requests to the app with an in-memory database.

**How did you use AI?**
Agent instructions in `AGENTS.md`, the plan in `docs/plans/roadmap.md`. Every step was checked by running the seeder, the tests and the app in the browser before committing, and bugs the checks found were fixed (e.g. `is_admin` null default, PR comparison, cached objects).

## 5. If the teacher asks for a live change

| Request | What to change |
|---|---|
| "Add a field to exercises" | new migration (`make:migration add_x_to_exercises_table`), `#[Fillable]` on `Exercise`, rule in `StoreExerciseRequest`, input in `exercises/_form.blade.php`, show it in `exercises/show.blade.php` |
| "Let athletes create tags too" | `TagPolicy@create` → `return true;` |
| "Show 25 workouts per page" | `WorkoutController@index` → `paginate(25)` |
| "Max 50 reps instead of 100" | `StoreWorkoutSetRequest` → `'max:50'` |
| "Make workouts public" | `WorkoutPolicy@view` → `return true;` and `Workout::visibleTo` → no condition |
