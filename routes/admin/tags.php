<?php
use App\Http\Controllers\TagController;
use App\Http\Controllers\AdminController;
// Group các route dành cho admin quản lý tags
Route::prefix('admin')->name('admin.')->group(function () {


    // Hiển thị danh sách tags
    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');

    // Hiển thị form tạo mới tag
    Route::get('/tags/create', [TagController::class, 'create'])->name('tags.create');

    // Lưu tag mới
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');

    // Hiển thị thông tin chi tiết của tag và các bài viết liên quan
    Route::get('/tags/{id}', [TagController::class, 'show'])->name('tags.show');

    // Hiển thị form chỉnh sửa tag
    Route::get('/tags/{id}/edit', [TagController::class, 'edit'])->name('tags.edit');

    // Cập nhật tag
    Route::put('/tags/{id}', [TagController::class, 'update'])->name('tags.update');

    // Xóa tag
    Route::delete('/tags/{id}', [TagController::class, 'destroy'])->name('tags.destroy');

    // Hiển thị danh sách bài viết liên quan đến một tag
    Route::get('/tags/{id}/articles', [TagController::class, 'articlesByTag'])->name('tags.articles');
});
