<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;





// Các route admin cho bài viết
require base_path('routes/admin/articles.php');

// Các route reader cho bài viết
require base_path('routes/reader/articles.php');

// Các route author cho bài viết
require base_path('routes/author/articles.php');

require base_path('routes/admin/categories.php');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('author/dashboard', [AuthorController::class, 'dashboard'])->name('author.dashboard');


