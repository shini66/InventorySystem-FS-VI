<?php

use App\Models\Company;

it('cuenta solo los productos de la empresa del usuario', function () {
    $admin = autor('Administrador');
    productFor($admin->company, ['stock' => 10]);
    productFor($admin->company, ['stock' => 3]);
    productFor(Company::factory()->create(), ['stock' => 999]);

    $this->actingAs($admin)->get(route('dashboard'))
        ->assertOk()
        ->assertViewHas('productCount', 2)
        ->assertViewHas('totalStock', 13)
        ->assertViewHas('lowStockCount', 1);
});

it('no deja entrar al dashboard a un operario', function () {
    $this->actingAs(autor('Operario'))
        ->get(route('dashboard'))
        ->assertForbidden();
});
