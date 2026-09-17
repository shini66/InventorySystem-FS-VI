<?php

use App\Models\Movement;

it('elimina un producto sin historial', function () {
    $admin = autor('Administrador');
    $product = productFor($admin->company);

    $this->actingAs($admin)
        ->delete(route('products.destroy', $product))
        ->assertRedirect(route('products.index'));

    $this->assertDatabaseCount('products', 0);
});

it('no elimina un producto que tiene movimientos', function () {
    $admin = autor('Administrador');
    $product = productFor($admin->company, ['stock' => 10]);
    Movement::factory()->for($product)->create();

    $this->actingAs($admin)
        ->delete(route('products.destroy', $product))
        ->assertStatus(422);

    $this->assertDatabaseCount('products', 1);
});
