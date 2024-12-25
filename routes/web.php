<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

require base_path('routes/admin/categories.php');
Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');