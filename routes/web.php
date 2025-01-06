<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


require base_path('routes/admin/categories.php');

require base_path('routes/admin/tags.php');

// Group route admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});

require base_path('routes/admin/users.php');


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
// Sửa route "dashboard"
Route::get('admin.dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');




Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::prefix('author')->middleware('auth')->group(function () {
    Route::get('dashboard', [AuthController::class, 'authorDashboard'])->name('author.dashboard');
});

Route::prefix('reader')->middleware('auth')->group(function () {
    Route::get('home', [AuthController::class, 'readerHome'])->name('reader.home');
});
