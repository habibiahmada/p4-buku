<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('books', App\Http\Controllers\Admin\BookController::class);

    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    Route::resource('transactions', App\Http\Controllers\Admin\TransactionController::class);
});

Route::prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('transactions', [App\Http\Controllers\Siswa\TransactionController::class, 'index'])->name('transactions.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
