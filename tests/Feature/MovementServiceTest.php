<?php

use App\Models\Company;
use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use App\Services\MovementService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

function register(User $user, array $data): Movement
{
    return app(MovementService::class)->register($user, $data);
}

function movementData(Product $product, array $override = []): array
{
    return array_merge([
        'product_id' => $product->id,
        'type' => 'entrada',
        'quantity' => 5,
        'supplier' => 'Distribuidora Sur',
        'reason' => null,
        'moved_at' => now(),
    ], $override);
}

it('suma stock cuando el movimiento es una entrada', function () {
    $admin = autor('Administrador');
    $product = productFor($admin->company, ['stock' => 5]);

    register($admin, movementData($product, ['quantity' => 10]));

    expect($product->refresh()->stock)->toBe(15);
});

it('descuenta stock cuando el movimiento es una salida', function () {
    $operario = autor('Operario');
    $product = productFor($operario->company, ['stock' => 8]);

    register($operario, movementData($product, [
        'type' => 'salida',
        'quantity' => 3,
        'supplier' => null,
        'reason' => 'Venta mostrador',
    ]));

    expect($product->refresh()->stock)->toBe(5);
});

it('rechaza una salida sin stock suficiente', function () {
    $operario = autor('Operario');
    $product = productFor($operario->company, ['stock' => 2]);

    expect(fn () => register($operario, movementData($product, [
        'type' => 'salida',
        'quantity' => 5,
        'supplier' => null,
        'reason' => 'Venta mostrador',
    ])))->toThrow(ValidationException::class);

    expect($product->refresh()->stock)->toBe(2)
        ->and(Movement::query()->count())->toBe(0);
});

it('no permite mover un producto de otra empresa', function () {
    $operario = autor('Operario');
    $ajeno = productFor(Company::factory()->create(), ['stock' => 50]);

    expect(fn () => register($operario, movementData($ajeno)))
        ->toThrow(ModelNotFoundException::class);

    expect($ajeno->refresh()->stock)->toBe(50);
});
