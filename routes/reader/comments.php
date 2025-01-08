<?php

use App\Http\Controllers\CommentController;

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
