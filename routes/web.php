<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Every route points to a controller method.
Route::get('/', [HomeController::class, 'index'])->name('home');
