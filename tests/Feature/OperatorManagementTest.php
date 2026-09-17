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

function administratorFor(Company $company): User
{
    $administrator = User::factory()->for($company)->create();
    $administrator->assignRole('Administrador');

    return $administrator;
}

function operatorFor(Company $company): User
{
    $operator = User::factory()->for($company)->create();
    $operator->assignRole('Operario');

    return $operator;
}

test('an administrator manages only operators in their company', function (): void {
    $company = Company::factory()->create();
    $administrator = administratorFor($company);
    $operator = operatorFor($company);
    $foreignOperator = operatorFor(Company::factory()->create());
    $otherAdministrator = administratorFor($company);

    $this->actingAs($administrator)->get(route('operators.index'))
        ->assertOk()
        ->assertSee($operator->email)
        ->assertDontSee($foreignOperator->email)
        ->assertDontSee($otherAdministrator->email);

    $this->actingAs($administrator)->post(route('operators.store'), [
        'name' => 'Nuevo Operario',
        'email' => 'operario@nueva.test',
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_id' => $foreignOperator->company_id,
        'role' => 'Administrador',
    ])->assertRedirect(route('operators.index'));

    $created = User::query()->where('email', 'operario@nueva.test')->sole();
    expect($created->company_id)->toBe($company->id)
        ->and($created->hasRole('Operario'))->toBeTrue()
        ->and($created->hasRole('Administrador'))->toBeFalse();

    $this->actingAs($administrator)->get(route('operators.edit', $foreignOperator))->assertNotFound();
    $this->actingAs($administrator)->get(route('operators.edit', $otherAdministrator))->assertNotFound();
});

test('an administrator can update and delete an operator in their company', function (): void {
    $company = Company::factory()->create();
    $administrator = administratorFor($company);
    $operator = operatorFor($company);

    $this->actingAs($administrator)->put(route('operators.update', $operator), [
        'name' => 'Operario Actualizado',
        'email' => $operator->email,
    ])->assertRedirect(route('operators.index'));

    expect($operator->refresh()->name)->toBe('Operario Actualizado');

    $this->actingAs($administrator)->delete(route('operators.destroy', $operator))
        ->assertRedirect(route('operators.index'));
    $this->assertModelMissing($operator);
});
