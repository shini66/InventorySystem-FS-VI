<?php

use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductController;
use App\Models\Movement;
use App\Models\Product;
use Illuminate\Support\Facades\Route;




Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function (){
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

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('movements', MovementController::class)->only('index', 'store');

});
