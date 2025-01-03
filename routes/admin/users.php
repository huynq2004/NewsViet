<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;


// Nhóm route admin
Route::prefix('admin')->middleware('auth')->group(function () {
    // Quản lý người dùng
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('users/{id}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
    Route::post('users/{id}/assign-role', [UserController::class, 'storeRole'])->name('users.storeRole');
});
