<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('sales/trash', [SaleController::class, 'trash'])->name('sales.trash');
    Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
    Route::resource('sales', SaleController::class)->except(['show', 'edit', 'update']);

    Route::resource('products', ProductController::class)->only(['index']);
    Route::resource('customers', CustomerController::class)->only(['index']);
});

require __DIR__.'/auth.php';
