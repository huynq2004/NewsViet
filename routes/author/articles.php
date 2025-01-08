<?php
// routes/author/articles.php

use App\Http\Controllers\ArticleController;

Route::prefix('author/articles')->group(function () {
    Route::get('/', [ArticleController::class, 'authorIndex'])->name('author.articles.index');
    Route::get('/create', [ArticleController::class, 'create'])->name('author.articles.create');
    Route::post('/', [ArticleController::class, 'store'])->name('author.articles.store');
    Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('author.articles.edit');
    Route::put('/{id}', [ArticleController::class, 'update'])->name('author.articles.update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('author.articles.destroy');
});
