<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

require base_path('routes/admin/categories.php');
require base_path('routes/admin/tags.php');

// Group route admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});
