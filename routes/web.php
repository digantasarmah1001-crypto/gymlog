<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutSetController;
use Illuminate\Support\Facades\Route;

// Every route points to a controller method.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Exercises: the 7 CRUD actions written out one by one.
Route::get('/exercises', [ExerciseController::class, 'index'])->name('exercises.index');                 // list
Route::get('/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');        // empty form
Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');                // save new
Route::get('/exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');        // detail
Route::get('/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');   // filled form
Route::put('/exercises/{exercise}', [ExerciseController::class, 'update'])->name('exercises.update');    // save changes
Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy'); // delete

// Workouts: same 7 actions.
Route::get('/workouts', [WorkoutController::class, 'index'])->name('workouts.index');
Route::get('/workouts/create', [WorkoutController::class, 'create'])->name('workouts.create');
Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
Route::get('/workouts/{workout}', [WorkoutController::class, 'show'])->name('workouts.show');
Route::get('/workouts/{workout}/edit', [WorkoutController::class, 'edit'])->name('workouts.edit');
Route::put('/workouts/{workout}', [WorkoutController::class, 'update'])->name('workouts.update');
Route::delete('/workouts/{workout}', [WorkoutController::class, 'destroy'])->name('workouts.destroy');

// Sets: listing and adding happen inside a workout (the URL needs the workout)...
Route::get('/workouts/{workout}/sets', [WorkoutSetController::class, 'index'])->name('workouts.sets.index');
Route::get('/workouts/{workout}/sets/create', [WorkoutSetController::class, 'create'])->name('workouts.sets.create');
Route::post('/workouts/{workout}/sets', [WorkoutSetController::class, 'store'])->name('workouts.sets.store');
// ...but once a set exists it has its own id, so the workout isn't needed in the URL.
Route::get('/sets/{set}', [WorkoutSetController::class, 'show'])->name('sets.show');
Route::get('/sets/{set}/edit', [WorkoutSetController::class, 'edit'])->name('sets.edit');
Route::put('/sets/{set}', [WorkoutSetController::class, 'update'])->name('sets.update');
Route::delete('/sets/{set}', [WorkoutSetController::class, 'destroy'])->name('sets.destroy');
