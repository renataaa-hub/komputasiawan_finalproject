<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    KaryaController,
    LikeController,
    CommentController,
    SubscriptionController
};
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Models\Notification;
use App\Http\Controllers\CollaborationController;


Route::get('/', fn() => view('welcome'));

require __DIR__.'/auth.php';


Route::middleware(['auth', 'verified'])->get('/dashboard', [KaryaController::class, 'dashboard'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Google 
Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/google', 'redirect')->name('auth.google');
    Route::get('/auth/google/callback', 'callback')->name('auth.google.callback');
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/karya', [KaryaController::class, 'index'])->name('karya.index');
    Route::get('/karya/create', [KaryaController::class, 'create'])->name('karya.create');
    Route::post('/karya', [KaryaController::class, 'store'])->name('karya.store');

    // ✅ INI YANG WAJIB (biar route('karya.autosave') gak error)
    Route::post('/karya/autosave', [KaryaController::class, 'autosave'])->name('karya.autosave');

    Route::get('/karya/{karya}', [KaryaController::class, 'show'])->name('karya.show');
    Route::get('/karya/{karya}/edit', [KaryaController::class, 'edit'])->name('karya.edit');
    Route::put('/karya/{karya}', [KaryaController::class, 'update'])->name('karya.update');
    Route::delete('/karya/{karya}', [KaryaController::class, 'destroy'])->name('karya.destroy');

    Route::get('/statistik', [KaryaController::class, 'statistik'])->name('karya.statistik');
});

Route::middleware('auth')->group(function () {
    Route::post('/karya/{karya}/like', [LikeController::class, 'toggle'])->name('karya.like');
    Route::post('/karya/{karya}/comment', [CommentController::class, 'store'])->name('karya.comment');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/finish', [SubscriptionController::class, 'finish'])->name('subscription.finish');
    Route::post('/subscription/{id}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

// webhook (TANPA auth)
Route::post('/midtrans/webhook', [SubscriptionController::class, 'webhook'])
    ->name('midtrans.webhook');

Route::middleware(['auth', 'subscription:monetization'])->group(function () {
    Route::get('/monetisasi', [KaryaController::class, 'monetisasi'])->name('karya.monetisasi');
    Route::post('/karya/{karya}/monetisasi', [KaryaController::class, 'updateMonetisasi'])
    ->name('karya.monetisasi.update');

});

Route::middleware('auth')->group(function () {
    Route::get('/notification', function () {
        $notifications = Notification::where('user_id', Auth::id())->latest()->get();
        return view('notification', compact('notifications'));
    })->name('notification');

    Route::post('/notification/{notification}/read', function ($id) {
        $notification = Notification::findOrFail($id);
        if ($notification->user_id === Auth::id()) {
            $notification->markAsRead();
        }
        return back();
    })->name('notification.read');

    Route::post('/notifications/mark-all-read', function () {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    })->name('notification.markAllRead');
});

Route::middleware('auth')->group(function () {
    // invite dari owner ke user lain (by email)
    Route::post('/karya/{karya}/collaboration/invite', [CollaborationController::class, 'invite'])
        ->name('collaboration.invite');

    // request dari user ke owner
    Route::post('/karya/{karya}/collaboration/request', [CollaborationController::class, 'requestToOwner'])
        ->name('collaboration.request');

    // accept / reject
    Route::post('/collaboration/{requestModel}/accept', [CollaborationController::class, 'accept'])
        ->name('collaboration.accept');

    Route::post('/collaboration/{requestModel}/reject', [CollaborationController::class, 'reject'])
        ->name('collaboration.reject');
});

