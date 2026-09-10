<?php

use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');


Route::redirect('/', '/dashboard');
Route::resource('products', ProductController::class)->except('show');
Route::get('movements', [MovementController::class, 'index'])->name('movements.index');
Route::post('movements', [MovementController::class, 'store'])->name('movements.store');
