<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutPhotoController;
use App\Http\Controllers\WorkoutSetController;
use Illuminate\Support\Facades\Route;

// Every route points to a controller method.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Only for visitors who are NOT logged in ('guest' sends logged-in users away).
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Public pages: the exercise library and athlete profiles.
// (ExerciseController itself requires login for create/edit/delete.)
Route::resource('exercises', ExerciseController::class);
Route::get('/athletes', [ProfileController::class, 'index'])->name('profiles.index');
Route::get('/athletes/{user}', [ProfileController::class, 'show'])->name('profiles.show');

// Everything below requires login ('auth' redirects guests to the 'login' route).
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // One line = the 7 CRUD routes (index, create, store, show, edit, update, destroy).
    Route::resource('workouts', WorkoutController::class);
    // Private progress photo of a workout (checked by WorkoutPolicy@view).
    Route::get('/workouts/{workout}/photo', [WorkoutPhotoController::class, 'show'])->name('workouts.photo');

    // Sets are nested inside a workout. shallow() keeps the workout in the URL only where it's needed:
    //   /workouts/{workout}/sets        index, create, store
    //   /sets/{set}                     show, edit, update, destroy
    Route::resource('workouts.sets', WorkoutSetController::class)->shallow();

    // Tags (many-to-many with workouts): everyone logged in can view, admins manage (TagPolicy).
    Route::resource('tags', TagController::class);
});
