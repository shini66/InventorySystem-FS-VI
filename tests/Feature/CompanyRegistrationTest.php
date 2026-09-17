<?php

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Role::findOrCreate('Administrador', 'web');
    Role::findOrCreate('Operario', 'web');
});

test('a guest can register a company and its first administrator', function (): void {
    $this->get(route('register'))->assertOk();

    $this->post(route('register.store'), [
        'company_name' => 'Acme SA',
        'cuit' => '30-12345678-9',
        'address' => 'Calle 123',
        'phone' => '11 1234 5678',
        'name' => 'Ana Administradora',
        'email' => 'ana@acme.test',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_id' => 999,
        'role' => 'Operario',
    ])->assertRedirect(route('dashboard'));

    $company = Company::query()->sole();
    $administrator = User::query()->sole();

    expect($company->cuit)->toBe('30123456789')
        ->and($administrator->company_id)->toBe($company->id)
        ->and($administrator->hasRole('Administrador'))->toBeTrue()
        ->and($administrator->hasRole('Operario'))->toBeFalse();
    $this->assertAuthenticatedAs($administrator);
});

test('registration requires company and administrator data', function (): void {
    $this->from(route('register'))
        ->post(route('register.store'), [])
        ->assertRedirect(route('register'))
        ->assertInvalid(['company_name', 'cuit', 'address', 'phone', 'name', 'email', 'password']);

    expect(Company::query()->count())->toBe(0)
        ->and(User::query()->count())->toBe(0);
});
