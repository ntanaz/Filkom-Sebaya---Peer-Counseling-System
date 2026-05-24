<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\KonseliController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Guest / Public Routes
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Role: Konseli Routes
Route::middleware(['auth', 'role:konseli'])->prefix('konseli')->name('konseli.')->group(function () {
    Route::get('/', [KonseliController::class, 'dashboard'])->name('dashboard');
    Route::get('/ajukan', [KonseliController::class, 'create'])->name('create');
    Route::post('/ajukan', [KonseliController::class, 'store'])->name('store');
    Route::get('/riwayat', [KonseliController::class, 'history'])->name('history');
    Route::get('/detail/{id}', [KonseliController::class, 'detail'])->name('detail');
});

// Role: Counselor Routes
Route::middleware(['auth', 'role:counselor'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::get('/', [CounselorController::class, 'dashboard'])->name('dashboard');
    Route::get('/permintaan', [CounselorController::class, 'requests'])->name('requests');
    Route::post('/permintaan/terima/{id}', [CounselorController::class, 'acceptRequest'])->name('accept');
    Route::post('/permintaan/tolak/{id}', [CounselorController::class, 'rejectRequest'])->name('reject');
    Route::get('/sesi', [CounselorController::class, 'sessions'])->name('sessions');
    Route::post('/sesi/jadwalkan/{id}', [\App\Http\Controllers\ScheduleController::class, 'createSchedule'])->name('schedule');
    Route::post('/sesi/selesai/{id}', [CounselorController::class, 'completeSession'])->name('complete');
    Route::get('/detail/{id}', [CounselorController::class, 'detail'])->name('detail');
});

// Role: Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengguna', [AdminController::class, 'users'])->name('users');
    Route::post('/pengguna/tambah', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/pengguna/edit/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/pengguna/hapus/{id}', [AdminController::class, 'deleteUser'])->name('users.destroy');
});

// Notifications & Schedule Features
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ScheduleController;

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/schedule/reschedule', [ScheduleController::class, 'reschedule'])->name('schedule.reschedule');
    Route::post('/schedule/cancel', [ScheduleController::class, 'cancel'])->name('schedule.cancel');
});
