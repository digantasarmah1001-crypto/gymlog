# Changelog

GymLog follows [semantic versioning](https://semver.org): `0.x.0` while the course runs,
where **x = the session number**. Every version is a git tag (`git tag` lists them).

## v0.13.0 – Session 13
- Test suite: 60 tests in `tests/Unit` and `tests/Feature/{Crud,Auth,Authorization}`.
- GitHub Actions runs the tests on every push to `main`; Laravel's own repository bots
  (Dependabot auto-merge, changelog and issue workflows) removed.

## v0.12.0 – Session 12
- Personal records: `WorkoutSetObserver` marks `is_pr` and dispatches the queued `NotifyPersonalRecord` job.
- Welcome email is queued (`ShouldQueue`); run `php artisan queue:work` to send queued mail.
- Model events (observer + `booted()` closures) clear the welcome-page cache when data changes.

## v0.11.0 – Session 11
- N+1 guard: `Model::preventLazyLoading()` outside production (every page checked, none found).
- Welcome-page totals and exercise library cached (`Cache::remember`, 10 minutes).
- kg / lb switch saved in the session (`UnitController`, `App\Support\Weight`, `<x-weight>`).

## v0.10.0 – Session 10
- Custom error pages (403, 404, 419, 429, 500, 503) with their own minimal layout.
- `ExerciseInUseException`: exercises with logged sets can't be deleted.
- Registration survives a failing mail server (try/catch + `report()`); failed uploads show a form error.
- Login and register are rate limited (`throttle:5,1`). Security review in `docs/security.md`.

## v0.9.0 – Session 9
- Exercise photos (public disk) and private progress photos served by `WorkoutPhotoController`.
- `public/js/app.js`: upload preview, delete confirmation, auto-submit filter (no inline JS).

## v0.8.0 – Session 8
- Tags: many-to-many between tags and workouts (`tag_workout` pivot), full CRUD for admins.
- Welcome email on registration (`App\Mail\WelcomeMail`, log mailer by default).
- Dev package: `barryvdh/laravel-debugbar` (shows queries and timing while developing).

## v0.7.0 – Session 7
- Hand-written register/login/logout, `is_admin`, policies, route protection.

## v0.6.0 – Session 6
- Athlete profiles with stats (Eloquent vs Collections), privacy-friendly profile fields.

## v0.5.0 – Session 5
- Resource routes with shallow nested sets.

## v0.4.0 – Session 4
- Full CRUD with validation for exercises, workouts and sets.

## v0.3.0 – Session 3
- Layout, Blade components, styling and the welcome page.

## v0.2.0 – Session 2
- Models, migrations, factories and seeder.

## v0.1.0 – Session 1
- Laravel project setup, first route → controller → view.
