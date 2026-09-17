<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::forCompany($request->user()->company_id)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create', ['product' => new Product]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create([
            'company_id' => $request->user()->company_id,
            ...$request->validated(),
        ]);

        return to_route('products.index')->with('status', 'Producto creado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $product): View
    {
        return view('products.edit', ['product' => $this->productForCompany($request, $product)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $this->productForCompany($request, $product)->update($request->validated());

        return to_route('products.index')->with('status', 'Producto actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $product = $this->productForCompany($request, $product);
        abort_if($product->movements()->exists(), 422, 'Producto con historial.');
        $product->delete();

        return to_route('products.index')->with('status', 'Producto eliminado.');
    }

    private function productForCompany(Request $request, Product $product): Product
    {
        return Product::forCompany($request->user()->company_id)->findOrFail($product->id);
    }
}
