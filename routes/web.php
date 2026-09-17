<?php

use App\Http\Controllers\MovementController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Livewire\Login;
use App\Models\Movement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function (): void {
    Route::view('/login', 'auth.login')->name('login');
    Route::get('/register', [RegistrationController::class, 'create'])->name('register');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
    Route::post('/login', function (Request $request): RedirectResponse {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Las credenciales proporcionadas no son válidas.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return to_route(Login::redirectRouteName());
    })->name('login.store');
});

Route::post('/logout', function (Request $request): RedirectResponse {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return to_route('login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'role:Administrador'])->group(function (): void {
    Route::get('/dashboard', function (Request $request) {
        $companyId = $request->user()->company_id;

        return view('dashboard', [
            'productCount' => Product::forCompany($companyId)->count(),
            'totalStock' => Product::forCompany($companyId)->sum('stock'),
            'lowStockCount' => Product::forCompany($companyId)->where('stock', '<=', 5)->count(),
            'stockByCategory' => Product::forCompany($companyId)
                ->selectRaw('category, SUM(stock) as total_stock')
                ->groupBy('category')
                ->orderBy('category')
                ->get(),
            'recentMovements' => Movement::forCompany($companyId)
                ->with('product')
                ->latest('moved_at')
                ->limit(10)
                ->get(),
        ]);
    })->name('dashboard');

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('operators', OperatorController::class)->except('show');
});

Route::middleware(['auth', 'role:Administrador|Operario'])->group(function (): void {
    Route::resource('movements', MovementController::class)->only('index', 'store');
});
