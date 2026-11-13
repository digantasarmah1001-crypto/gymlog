# GymLog security review (session 10)

Each row is a common threat, how GymLog handles it, and where to find it.

| Threat | What could go wrong | How GymLog prevents it | Where |
|---|---|---|---|
| **Broken access control** | Opening or changing someone else's workout by changing the id in the URL | Policies checked on every action (`Gate::authorize`, Form Request `authorize()`); private photos only through a checked route | `app/Policies/*`, controllers, `WorkoutPhotoController` |
| **Mass assignment** | Sending `is_admin=1` or `user_id=5` with a form | `#[Fillable]` whitelist on every model; `is_admin`, `image_path`, `photo_path` are not fillable; `user_id` comes from `auth()` | `app/Models/*`, `WorkoutController@store` |
| **SQL injection** | Typing SQL in a form field | Eloquent/query builder always uses bound parameters; the only `selectRaw`/`DB::raw` contain no user input | `ProfileController@show`, `HomeController` |
| **XSS** (cross-site scripting) | Saving `<script>` in a workout title or notes | Blade `{{ }}` escapes all output; no `{!! !!}` with user data | all views |
| **CSRF** | Another site submitting a form in your name | `@csrf` token in every form; Laravel rejects POST/PUT/DELETE without it (419 page) | all forms |
| **Password guessing** | Trying thousands of passwords | `throttle:5,1` on login and register (429 page); passwords hashed (`hashed` cast) | `routes/web.php`, `User` |
| **Session fixation** | Reusing a session id from before login | `session()->regenerate()` after login/register, `invalidate()` on logout | `AuthController` |
| **Dangerous uploads** | Uploading a `.php` file or a huge file | `image` + `mimes` + `max` rules; files get random names from `store()`; private files outside `public/` | Form Requests, `config/filesystems.php` |
| **Information leaks** | Showing emails/bodyweight, or stack traces | `#[Hidden]` on `User`; profiles never print email/bodyweight; custom error pages; `APP_DEBUG=false` in production | `User`, `resources/views/errors/*` |
| **Data loss** | Deleting an exercise wipes everyone's logged sets (cascade) | `ExerciseInUseException`: exercises with sets can't be deleted | `ExerciseController@destroy` |
| **Secrets in git** | Committing passwords/API keys | `.env` is git-ignored; only `.env.example` (no secrets) is committed | `.gitignore` |

## Before going live (not needed for the local demo)
- `APP_ENV=production`, `APP_DEBUG=false` (otherwise error pages show code and settings).
- Use HTTPS and a real mail provider (`MAIL_*` in `.env`).
- `composer install --no-dev` (removes the debugbar).
