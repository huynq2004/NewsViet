<?php
// routes/reader/articles.php

use App\Http\Controllers\ArticleController;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('reader.articles.index');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('reader.articles.show');
});

