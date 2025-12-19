<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryaController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
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
    //notifikasi user yang login
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

// Show Karya (GET)
Route::get('/karya/{karya}', [KaryaController::class, 'show'])
    ->middleware('auth')
    ->name('karya.show');

// Edit Karya (GET form)
Route::get('/karya/{karya}/edit', [KaryaController::class, 'edit'])
    ->middleware('auth')
    ->name('karya.edit');

// Update Karya (PUT/PATCH)
Route::put('/karya/{karya}', [KaryaController::class, 'update'])
    ->middleware('auth')
    ->name('karya.update');

// Delete Karya (DELETE)
Route::delete('/karya/{karya}', [KaryaController::class, 'destroy'])
    ->middleware('auth')
    ->name('karya.destroy');
// new route (Dashboard)
Route::get('/dashboard', [KaryaController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// Like & Comment Routes
Route::middleware('auth')->group(function () {
    Route::post('/karya/{karya}/like', [LikeController::class, 'toggle'])->name('karya.like');
    Route::post('/karya/{karya}/comment', [CommentController::class, 'store'])->name('karya.comment');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

// Notification mark as read
Route::middleware('auth')->post('/notification/{notification}/read', function($id) {
    $notification = Notification::findOrFail($id);
    if ($notification->user_id === Auth::id()) {
        $notification->markAsRead();
    }
    return back();
})->name('notification.read');

// Mark all notifications as read
Route::middleware('auth')->post('/notifications/mark-all-read', function() {
    Notification::where('user_id', Auth::id())
        ->whereNull('read_at')
        ->update(['read_at' => now()]);
    return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
})->name('notification.markAllRead');