<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryaController;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $trending = [
        (object)[
            'title' => 'Karya Pertama',
            'deskripsi' => 'Sebuah karya menarik tentang petualangan fantasi.',
            'rating' => 4.8
        ],
        (object)[
            'title' => 'Karya Kedua',
            'deskripsi' => 'Cerita drama penuh emosi yang menyentuh hati.',
            'rating' => 4.6
        ],
        (object)[
            'title' => 'Karya Ketiga',
            'deskripsi' => 'Komedi ringan yang cocok menemani waktu santai.',
            'rating' => 4.7
        ],
    ];

    return view('dashboard', compact('trending'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Index Karya (GET)
Route::get('/karya', [KaryaController::class, 'index'])
    ->middleware('auth')
    ->name('karya.index');

// Create Karya (GET form)
Route::get('/karya/create', [KaryaController::class, 'create'])
    ->middleware('auth')
    ->name('karya.create');

// Store Karya (POST)
Route::post('/karya', [KaryaController::class, 'store'])
    ->middleware('auth')
    ->name('karya.store');

Route::middleware('auth')->get('/statistik', [KaryaController::class, 'statistik'])
    ->name('karya.statistik');


Route::get('/monetisasi', [KaryaController::class, 'monetisasi'])
    ->middleware('auth')
    ->name('karya.monetisasi');

Route::get('/subscription', function () {
    return view('subscription'); 
})->middleware('auth')->name('subscription');

Route::get('/notification', function () {
    // Ambil notifikasi user yang login
    $notifications = Notification::where('user_id', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->get();

    return view('notification', compact('notifications'));
})->middleware('auth')->name('notification');

require __DIR__.'/auth.php';

#autosave
Route::post('/karya/autosave', [KaryaController::class, 'autosave'])
    ->middleware('auth')
    ->name('karya.autosave');
