<?php

use App\Models\Company;
use App\Models\Movement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::findOrCreate('Administrador', 'web');
    Role::findOrCreate('Operario', 'web');
});

function tenantUser(Company $company, string $role): User
{
    $user = User::factory()->for($company)->create();
    $user->assignRole($role);

    return $user;
}

function tenantProduct(Company $company, array $attributes = []): Product
{
    return Product::factory()->for($company)->create($attributes);
}

test('the same SKU is allowed in separate companies and product queries are isolated', function (): void {
    $firstCompany = Company::factory()->create();
    $secondCompany = Company::factory()->create();
    $administrator = tenantUser($firstCompany, 'Administrador');
    $firstProduct = tenantProduct($firstCompany, ['name' => 'Producto propio', 'sku' => 'SKU-COMPARTIDO']);
    $secondProduct = tenantProduct($secondCompany, ['name' => 'Producto ajeno', 'sku' => 'SKU-COMPARTIDO']);

    expect(Product::query()->where('sku', 'SKU-COMPARTIDO')->count())->toBe(2);

    $this->actingAs($administrator)->post(route('products.store'), [
        'name' => 'SKU repetido',
        'sku' => 'SKU-COMPARTIDO',
        'category' => 'General',
    ])->assertInvalid('sku');

    $this->actingAs($administrator)->get(route('products.index'))
        ->assertOk()
        ->assertSee($firstProduct->name)
        ->assertDontSee($secondProduct->name);
    $this->actingAs($administrator)->get(route('products.edit', $secondProduct))->assertNotFound();
    $this->actingAs($administrator)->delete(route('products.destroy', $secondProduct))->assertNotFound();
});

test('movements cannot use foreign products and insufficient stock is atomic', function (): void {
    $firstCompany = Company::factory()->create();
    $operator = tenantUser($firstCompany, 'Operario');
    $ownProduct = tenantProduct($firstCompany, ['stock' => 3]);
    $foreignProduct = tenantProduct(Company::factory()->create(), ['stock' => 7]);

    $this->actingAs($operator)->post(route('movements.store'), [
        'product_id' => $foreignProduct->id,
        'type' => 'entrada',
        'quantity' => 2,
        'supplier' => 'Proveedor',
        'moved_at' => now()->format('Y-m-d H:i:s'),
    ])->assertInvalid('product_id');

    $this->actingAs($operator)->post(route('movements.store'), [
        'product_id' => $ownProduct->id,
        'type' => 'salida',
        'quantity' => 4,
        'reason' => 'Venta',
        'moved_at' => now()->format('Y-m-d H:i:s'),
    ])->assertInvalid('quantity');

    expect(Movement::query()->count())->toBe(0)
        ->and($ownProduct->refresh()->stock)->toBe(3)
        ->and($foreignProduct->refresh()->stock)->toBe(7);
});

test('movement history and product selector exclude foreign company data', function (): void {
    $company = Company::factory()->create();
    $operator = tenantUser($company, 'Operario');
    $ownProduct = tenantProduct($company, ['name' => 'Producto propio']);
    $foreignProduct = tenantProduct(Company::factory()->create(), ['name' => 'Producto ajeno']);
    Movement::factory()->for($ownProduct)->create();
    Movement::factory()->for($foreignProduct)->create();

    $this->actingAs($operator)->get(route('movements.index'))
        ->assertOk()
        ->assertSee($ownProduct->name)
        ->assertDontSee($foreignProduct->name);
});
