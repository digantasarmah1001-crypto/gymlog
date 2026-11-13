<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutSetController;
use Illuminate\Support\Facades\Route;

// Every route points to a controller method.
Route::get('/', [HomeController::class, 'index'])->name('home');

// One line = the 7 CRUD routes (index, create, store, show, edit, update, destroy).
Route::resource('exercises', ExerciseController::class);
Route::resource('workouts', WorkoutController::class);

// Sets are nested inside a workout. shallow() keeps the workout in the URL only where it's needed:
//   /workouts/{workout}/sets        index, create, store
//   /sets/{set}                     show, edit, update, destroy
Route::resource('workouts.sets', WorkoutSetController::class)->shallow();
