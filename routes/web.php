<?php

use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductController;
use App\Models\Movement;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard', [
        'productCount' => Product::count(),
        'totalStock' => Product::sum('stock'),
        'lowStockCount' => Product::where('stock', '<=', 5)->count(),
        'stockByCategory' => Product::query()
            ->selectRaw('category, SUM(stock) as total_stock')
            ->groupBy('category')
            ->orderBy('category')
            ->get(),
        'recentMovements' => Movement::with('product')
            ->latest('moved_at')
            ->limit(10)
            ->get(),
    ]);
})->name('dashboard');


Route::redirect('/', '/dashboard');
Route::resource('products', ProductController::class)->except('show');
Route::get('movements', [MovementController::class, 'index'])->name('movements.index');
Route::post('movements', [MovementController::class, 'store'])->name('movements.store');
