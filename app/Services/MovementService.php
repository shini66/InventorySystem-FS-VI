<?php

namespace App\Services;

use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MovementService
{
    public function register(User $user, array $data): Movement
    {
        return DB::transaction(function () use ($data, $user) {
            $product = Product::forCompany($user->company_id)
                ->lockForUpdate()
                ->findOrFail($data['product_id']);

            if ($data['type'] === 'salida' && $product->stock < $data['quantity']) {
                throw ValidationException::withMessages([
                    'quantity' => "Stock insuficiente. Disponible: {$product->stock}.",
                ]);
            }

            $data['type'] === 'entrada'
                ? $product->increment('stock', $data['quantity'])
                : $product->decrement('stock', $data['quantity']);

            return Movement::create($data);
        }, attempts: 3);
    }
}
