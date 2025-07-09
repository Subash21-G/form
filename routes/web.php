<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

// Show the form
Route::get('/form', [PostController::class, 'create'])->name('form.create');

// Handle form submission
Route::post('/form', [PostController::class, 'store'])->name('form.store');
