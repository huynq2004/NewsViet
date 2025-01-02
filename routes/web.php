<?php

use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream

Route::get('/', function () {
    return view('welcome');
});
=======
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;

require base_path('routes/admin/categories.php');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');


// Routes cho module quản lý bình luận
Route::prefix('admin')->group(function () {
    Route::get('/comments', [CommentController::class, 'index'])->name('admin.comments.index'); // Xem danh sách bình luận
    Route::get('/comments/create', [CommentController::class, 'create'])->name('admin.comments.create'); // Thêm bình luận
    Route::post('/comments', [CommentController::class, 'store'])->name('admin.comments.store'); // Lưu bình luận mới
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('admin.comments.edit'); // Sửa bình luận
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('admin.comments.update'); // Cập nhật bình luận
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('admin.comments.destroy'); // Xóa bình luận

    Route::get('/comments/pending', [CommentController::class, 'pending'])->name('admin.comments.pending'); // Danh sách bình luận chưa duyệt
    Route::get('/comments/reported', [CommentController::class, 'reported'])->name('admin.comments.reported'); // Danh sách bình luận bị báo cáo
});

>>>>>>> Stashed changes
