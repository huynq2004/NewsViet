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

use App\Http\Controllers\CategoryController;
// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
Route::prefix('admin')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

// Author
Route::middleware(['auth', 'role:author'])->group(function () {
    Route::get('/author/dashboard', function () {
        return view('author.dashboard');
    })->name('author.dashboard');
});

// Reader
Route::middleware(['auth', 'role:reader'])->group(function () {
    Route::get('/reader/dashboard', function () {
        return view('reader.dashboard');
    })->name('reader.dashboard');
});

// require base_path('routes/admin/categories.php');
Route::get('author/dashboard', [AuthorController::class, 'dashboard'])->name('author.dashboard');


Route::get('/reader/dashboard', [ReaderController::class, 'dashboard'])->name('reader.dashboard');



require base_path('routes/admin/tags.php');

// Group route admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});


// Đảm bảo rằng tệp `comments.php` được bao gồm từ thư mục routes/reader/
require __DIR__.'/reader/comments.php';

require base_path('routes/admin/users.php');


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
// Sửa route "dashboard"
Route::get('admin.dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');




Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

