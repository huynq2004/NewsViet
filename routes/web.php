<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
    Route::delete('/{id}', [ArticleController::class, 'adminDestroy'])->name('destroy');

});

Route::prefix('reader')->name('reader.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('show');
    Route::get('/search', [ArticleController::class, 'search'])->name('search');
});

Route::prefix('author')->name('author.')->middleware('auth')->group(function () {
    Route::get('/', [ArticleController::class, 'authorIndex'])->name('index');
    Route::get('/create', [ArticleController::class, 'create'])->name('create');
    Route::post('/', [ArticleController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
});
