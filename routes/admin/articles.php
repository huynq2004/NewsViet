<?php
// routes/admin/articles.php

use App\Http\Controllers\ArticleController;

Route::prefix('admin/articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('admin.articles.index');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');
});

