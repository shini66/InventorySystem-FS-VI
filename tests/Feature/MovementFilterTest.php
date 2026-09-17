<?php

use App\Models\Movement;
use Illuminate\Support\Str;

it('filtra el historial por tipo de movimiento', function () {
    $operario = autor('Operario');
    $entrada = productFor($operario->company, ['name' => 'Producto entrada', 'stock' => 10]);
    $salida = productFor($operario->company, ['name' => 'Producto salida', 'stock' => 10]);

    Movement::factory()->for($entrada)->create(['type' => 'entrada']);
    Movement::factory()->for($salida)->create([
        'type' => 'salida', 'supplier' => null, 'reason' => 'Venta',
    ]);

    $response = $this->actingAs($operario)->get(route('movements.index', ['type' => 'salida']));
    $response->assertOk();

    $historial = Str::after($response->getContent(), 'Historial de movimientos');

    expect($historial)
        ->toContain('Producto salida')
        ->not->toContain('Producto entrada');
});
