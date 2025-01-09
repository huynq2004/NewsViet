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
use App\Http\Controllers\CommentController;

// // Các route admin cho bài viết
Route::prefix('admin/articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('admin.articles.index');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');
});


// // Các route reader cho bài viết

Route::prefix('articles')->group(function () {
    // Hiển thị danh sách bài viết cho người đọc
    Route::get('/', [ArticleController::class, 'index'])->name('reader.articles.index');
    // Hiển thị chi tiết bài viết
    Route::get('/{id}', [ArticleController::class, 'show'])->name('reader.articles.show');
});

// // Các route author cho bài viết
Route::prefix('author/articles')->group(function () {
    Route::get('/', [ArticleController::class, 'authorIndex'])->name('author.articles.index');
    Route::get('/create', [ArticleController::class, 'create'])->name('author.articles.create');
    Route::post('/', [ArticleController::class, 'store'])->name('author.articles.store');
    Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('author.articles.edit');
    Route::put('/{id}', [ArticleController::class, 'update'])->name('author.articles.update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('author.articles.destroy');
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
Route::get('author/dashboard', [DashboardController::class, 'dashboard'])->name('author.dashboard');


Route::get('/reader/dashboard', [ReaderController::class, 'dashboard'])->name('reader.dashboard');



require base_path('routes/admin/tags.php');

// Group các route dành cho quản lý bình luận
Route::prefix('comments')->name('comments.')->group(function () {

    // Hiển thị danh sách bình luận của bài viết
    Route::get('/article/{articleId}', [CommentController::class, 'index'])->name('index');

    // Thêm bình luận vào bài viết
    Route::post('/article/{articleId}', [CommentController::class, 'store'])->name('store');

    // Hiển thị thông tin chi tiết của bình luận
    Route::get('/{id}', [CommentController::class, 'show'])->name('show');

    // Hiển thị form chỉnh sửa bình luận
    Route::get('/{id}/edit', [CommentController::class, 'edit'])->name('edit');  // Đường dẫn cho edit

    // Cập nhật bình luận
    Route::put('/{id}', [CommentController::class, 'update'])->name('update');

    // Xóa bình luận
    Route::delete('/{id}', [CommentController::class, 'destroy'])->name('destroy');

    // Báo cáo bình luận vi phạm
    Route::post('/{id}/report', [CommentController::class, 'report'])->name('report');
});


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

