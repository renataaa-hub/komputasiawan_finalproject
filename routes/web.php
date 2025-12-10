<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/karya/create', function () {
       return view('karya.create');
   })->name('karya.create');

Route::get('/karya/karya', function () {
       return view('karya.karya');
    })->name('karya.karya');

require __DIR__.'/auth.php';
