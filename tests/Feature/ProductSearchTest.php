<?php

it('filtra productos por nombre, sku o categoria', function () {
    $admin = autor('Administrador');
    productFor($admin->company, ['name' => 'Yerba Selecta', 'sku' => 'YRB-001', 'category' => 'Almacen']);
    productFor($admin->company, ['name' => 'Lavandina', 'sku' => 'LAV-001', 'category' => 'Limpieza']);

    $this->actingAs($admin)->get(route('products.index', ['search' => 'YRB']))
        ->assertOk()
        ->assertSee('Yerba Selecta')
        ->assertDontSee('Lavandina');

    $this->actingAs($admin)->get(route('products.index', ['search' => 'Limpieza']))
        ->assertSee('Lavandina')
        ->assertDontSee('Yerba Selecta');
});
