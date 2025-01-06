<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReaderController;






// Các route admin cho bài viết
require base_path('routes/admin/articles.php');

// Các route reader cho bài viết
require base_path('routes/reader/articles.php');

// Các route author cho bài viết
require base_path('routes/author/articles.php');


require base_path('routes/admin/categories.php');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('author/dashboard', [AuthorController::class, 'dashboard'])->name('author.dashboard');


Route::get('/reader/dashboard', [ReaderController::class, 'dashboard'])->name('reader.dashboard');



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

