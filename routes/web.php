<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

// // Các route admin cho bài viết
require base_path('routes/admin/articles.php');

// // Các route reader cho bài viết
require base_path('routes/reader/articles.php');

// // Các route author cho bài viết
require base_path('routes/author/articles.php');

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

require __DIR__.'/reader/comments.php';

Route::get('/', [DashboardController::class, 'reader'])->name('home');


Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard route
    Route::get('dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // Quản lý người dùng
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Routes cho Category
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('categories/tree', [CategoryController::class, 'showTree'])->name('categories.tree');
    Route::post('categories/move', [CategoryController::class, 'moveSubcategories'])->name('categories.move');
    Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');


    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');

});

