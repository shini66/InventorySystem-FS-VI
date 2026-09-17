<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('a guest is redirected to login for protected routes', function (): void {
    $this->get(route('movements.index'))->assertRedirect(route('login'));
});

test('a user can log in with valid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
    ]);
    $user->assignRole('Administrador');

    $this->post(route('login.store'), [
        'email' => 'admin@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with invalid credentials', function (): void {
    $user = User::factory()->create(['email' => 'admin@example.com']);
    $user->assignRole('Administrador');

    $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'incorrecta',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('an administrator can access product routes', function (): void {
    $administrator = autor('Administrador');
    $product = productFor($administrator->company);

    $this->actingAs($administrator)->get(route('products.index'))->assertOk();
    $this->actingAs($administrator)->get(route('products.create'))->assertOk();
    $this->actingAs($administrator)->post(route('products.store'), [
        'name' => 'Producto nuevo',
        'sku' => 'SKU-NUEVO',
        'category' => 'General',
    ])->assertRedirect(route('products.index'));
    $this->actingAs($administrator)->get(route('products.edit', $product))->assertOk();
    $this->actingAs($administrator)->put(route('products.update', $product), [
        'name' => 'Producto actualizado',
        'sku' => $product->sku,
        'category' => 'General',
    ])->assertRedirect(route('products.index'));
    $this->actingAs($administrator)->delete(route('products.destroy', $product))->assertRedirect(route('products.index'));
});

test('an operario receives forbidden for every product route', function (): void {
    $operario = autor('Operario');
    $product = productFor($operario->company);

    $this->actingAs($operario)->get(route('products.index'))->assertForbidden();
    $this->actingAs($operario)->get(route('products.create'))->assertForbidden();
    $this->actingAs($operario)->post(route('products.store'), [])->assertForbidden();
    $this->actingAs($operario)->get(route('products.edit', $product))->assertForbidden();
    $this->actingAs($operario)->put(route('products.update', $product), [])->assertForbidden();
    $this->actingAs($operario)->delete(route('products.destroy', $product))->assertForbidden();
});

test('both roles can view and register movements', function (string $role): void {
    $user = autor($role);
    $product = productFor($user->company, ['stock' => 0]);

    $this->actingAs($user)->get(route('movements.index'))->assertOk();
    $this->actingAs($user)->post(route('movements.store'), [
        'product_id' => $product->id,
        'type' => 'entrada',
        'quantity' => 5,
        'supplier' => 'Proveedor de prueba',
        'moved_at' => now()->format('Y-m-d H:i:s'),
    ])->assertRedirect(route('movements.index'));

    $this->assertDatabaseHas('movements', [
        'product_id' => $product->id,
        'type' => 'entrada',
        'quantity' => 5,
    ]);
    expect($product->refresh()->stock)->toBe(5);
})->with(['administrador' => 'Administrador', 'operario' => 'Operario']);

test('a product update uses the product request validation', function (): void {
    $administrator = autor('Administrador');
    $product = productFor($administrator->company);

    $this->actingAs($administrator)
        ->put(route('products.update', $product), [
            'name' => 'Producto actualizado',
            'sku' => $product->sku,
            'category' => 'General',
        ])
        ->assertRedirect(route('products.index'));

    expect($product->refresh()->name)->toBe('Producto actualizado');
});
