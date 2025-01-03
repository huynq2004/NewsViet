<?php

use App\Http\Controllers\ArticleController;

Route::prefix('articles')->group(function () {
    // Hiển thị danh sách bài viết cho người đọc
    Route::get('/', [ArticleController::class, 'index'])->name('reader.articles.index');
    // Hiển thị chi tiết bài viết
    Route::get('/{id}', [ArticleController::class, 'show'])->name('reader.articles.show');
});
