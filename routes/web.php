<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;

// Route quản lý thẻ
Route::prefix('tags')->group(function () {
    // Lấy danh sách tất cả các thẻ
    Route::get('/', [TagController::class, 'index'])->name('tags.index');

    // Tạo thẻ mới
    Route::post('/', [TagController::class, 'store'])->name('tags.store');

    // Lấy thông tin một thẻ cụ thể
    Route::get('/{id}', [TagController::class, 'show'])->name('tags.show');

    // Cập nhật thông tin một thẻ
    Route::put('/{id}', [TagController::class, 'update'])->name('tags.update');

    // Xóa một thẻ
    Route::delete('/{id}', [TagController::class, 'destroy'])->name('tags.destroy');
});
